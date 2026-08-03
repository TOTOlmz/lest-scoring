<?php
declare(strict_types=1);

namespace App\controllers;
use App\Models\TournamentModel;
use App\Models\DetailsModel;
use App\Models\EditTournModel;
/**
 * UserSpaceController
 * 
 * Contrôleur gérant l'affichage de la page de détail du tournoi
 * 
 */

class TournamentEditController extends BaseController {

    protected string $userStatus = "";
    protected bool $userAuthorisation = false;
    protected string $tournamentPId = "";
    protected int $tournamentId = 0;
    protected array $tournament = [];
    protected array $tournaments = []; // Utile uniquement pour appeller src/views/components/tournamentsList.php
    protected TournamentModel $tournamentModel;
    protected DetailsModel $detailsModel;
    protected EditTournModel $editTournModel;

    public function __construct() {
        $this->tournamentModel = new TournamentModel;
        $this->detailsModel = new DetailsModel;
        $this->editTournModel = new EditTournModel;
        $this->checkConnectedStatus();
    }


    // Fonction de rendu de la page directeurs
    public function rendered() : void {

        // Récupération de l'id du tournoi :
        if (isset($_GET["tournament"]) && $_GET["tournament"] !== "") {
            $this->tournamentPId = $_GET["tournament"];
            $this->tournamentId = $this->detailsModel->getIdByPublicId("tournaments", $this->tournamentPId);
        }

        // Edition du token csrf s'il n'est pas déjà édité + Vérification des droits d'accès
        $this->editToken();
        if (isset($_SESSION) && isset($_SESSION["user_role"])) {
            $this->userStatus = $_SESSION["user_role"];

            // L'admin doit pouvoir éditer tous les tournois            
            if ($this->userStatus === "ADMIN") {
                $this->userAuthorisation = true;
            
            // Et un directeur doit pouvoir le faire également pour ses tournois
            } elseif ($this->userStatus === "DIRECTOR") {
                $currentUserId = $this->tournamentModel->getIdByPublicId("users", $_SESSION["user_public_id"]);
                $userTournaments = $this->tournamentModel->getTournamentsIdsByDirectorId($currentUserId);
                if (in_array($this->tournamentId, $userTournaments)) {
                    $this->userAuthorisation = true;
                }
            }
        }

        if ($this->userAuthorisation === false) {
            require_once ROOT_PATH . "/src/views/NonAuthorizationView.php";
            exit;
        }



        // Si le formulaire d'édition du tournoi est soumi :
        if (isset($_POST) && isset($_POST["edit-tourn"])) {
            $token = isset($_POST["token"]) ? $_POST["token"] : "";
            $this->checkCsrfToken($token);
            if (empty($this->errors)) {
                $this->editTourn($_POST);
            }
        }
        // Fomrulaire d'ajout d'un élément est soumis :
        if (isset($_POST) && isset($_POST["add-elt"])) {
            $token = isset($_POST["token"]) ? $_POST["token"] : "";
            $this->checkCsrfToken($token);
            if (empty($this->errors)) {
                $element = $_POST["add-elt"];
                if ($element === "draw") { $this->addDraw($_POST); }
                if ($element === "court") { $this->addCourt($_POST); }
                if ($element === "player") { $this->addPlayer($_POST); }
                if ($element === "match") { $this->addMatch($_POST); }
                if ($element === "umpire") { $this->addUmpire($_POST); }
            }
        }



        // Si l'url contient bien un identifiant de tournoi, on le récupère
        if ($this->tournamentId !== 0) {
            $this->getTournament($this->tournamentId);
        }

        // echo "<pre>";
        // print_r($this->tournament);
        // echo "</pre>";
        require_once ROOT_PATH . "/src/views/TournamentEditView.php";
    }


    // Fonction permettant de récupérer un tournoi à partir de son ID
    private function getTournament(int $tournId): void {
    
        
        // On prépare this->tournaments pour l'affichage
        $this->tournaments[0] = $this->tournamentModel->getAllTournamentDataById($tournId);
        if (!empty($this->tournaments[0])) {
            $this->tournaments = $this->processTournaments($this->tournaments);
        }
        $this->tournament = $this->detailsModel->getAllTournamentElements($tournId); 

        // Pour chaque draw, on ajoute ses matches en les classant par round
        foreach ($this->tournament["draws"] as $drawKey => $draw) {
            foreach ($this->tournament["matches"] as $match) {

                // Si le match appartient au draw :
                if ($match["draw_id"] === $draw["id"]) {

                    $roundPos = substr($match["draw_position"], 0, 2); // On stocke sa position dans le draw (1 = finale, 2 = demis ....)
                    $linePos = substr($match["draw_position"], 2, 2); // On stocke sa position dans le round (a = 1er match, b)

                    // Enfin, on stocke le match dans le tableau avec sa position précise
                    $this->tournament["draws"][$drawKey]["rounds"][$roundPos][$linePos] = $match;
                    
                }
            }
            // On trie les rounds par ordre décroissant (pour avoir la finale en dernier)
            krsort($this->tournament["draws"][$drawKey]["rounds"], SORT_NUMERIC);
            // On trie également les matchs dans l'ordre croissant
            foreach ($this->tournament["draws"][$drawKey]["rounds"] as &$round) {
                ksort($round, SORT_NUMERIC);
            }
            unset($round);
        }

    }

    // Fonction permettant d'éditer un tournoi (le mettre à jour en fonction des éléments nvoyés par l'utilisateur)
    private function editTourn(array $post): void {
        $name = isset($post["name"]) ? $post["name"] : "";
        $club = isset($post["club"]) ? $post["club"] : "";
        $city = isset($post["city"]) ? $post["city"] : "";
        $start = isset($post["start"]) ? $post["start"] : "";
        $end = isset($post["end"]) ? $post["end"] : "";
        $tournPId = isset($post["id_to_display"]) ? $post["id_to_display"] : "";
        $tournId = 0;

        // On récupère l'id de l'utilisateur
        $pId = $_SESSION["user_public_id"];
        if (!$pId) {
            return;
        }
        $userId = $this->tournamentModel->getIdByPublicId("users", $pId);
        if (!$userId) {
            return;
        }

        if (!$tournPId || !$name || !$club || !$city || !$start || !$end) {
            $this->errors[] = "Merci de renseigner tous les champs.";
            return;
        }
        // On récupère l'id du tournoi
        if ($tournPId !== "") {
        $tournId = $this->tournamentModel->getIdByPublicId("tournaments", $tournPId);
        }
        if ($tournId === 0) {
            $this->errors[] = "Problème rencontré lors de la récupération de l'Id du tournoi";
        }

        // On convertie les dates en timestamps
        $startTime = strtotime($start);
        $endTime = strtotime($end);
        if (!$startTime || !$endTime) {
            $this->errors[] = "Merci de renseigner des dates valides.";
            return;
        }
        if ($startTime > $endTime) {
            $this->errors[] = "Le début du tournoi doit avoir lieu avant la fin.";
            return;
        }

        // Si tout a fonctionné, on met a jour le tournoi
        if (empty($this->errors)) {
            $update = $this->editTournModel->editTourn($tournId, $name, $club, $city, $startTime, $endTime);
            if (!$update) {
                $this->errors[] = "Problème rencontré lors de la mise à jour du tournoi.";
            } else {
                $this->success = "Mise à jour réalisée avec succès.";
            }
        }
        return;

    }

    // Fonction permettant d'ajouter un tableau
    private function addDraw (array $post): void {
        $title = isset($post["title"]) ? $post["title"] : "";
        $size = isset($post["size"]) ? intval($post["size"]) : "";
        $type = isset($post["type"]) ? intval($post["type"]) : "";
        $publicId = $this->generatePublicCode("draws");

        $realDrawSize = $this->nextPowerOfTwo($size);

        if (!$size || !$type) {
            $this->errors[] = "Impossible de récupérer les éléments. merci de réessayer à minima la taille du tableau et le format de jeu.";
        }
        if (empty($this->errors)) {
            $drawId = $this->editTournModel->addDraw($publicId, $title, $realDrawSize, $type, $this->tournamentId);
            if ($drawId) { $this->success = "Ajout réalisé avec succès"; }
            else { $this->errors[] = "Échec de l'ajout."; }
            $matchesNb = $this->generateDrawMatches($realDrawSize, $drawId, $type);  // On génère tous les matchs du draw
            if ($matchesNb) {
                $this->success = "Ajout du tableau et de " . count($matchesNb) . " matchs avec succès.";
            } else {
                $this->errors[] = "Échec lors de l'ajout des matchs";
            }
        }
        return;
    }
    // Fonction permettant d'ajouter un court
    private function addCourt (array $post): void { 
        $name = isset($post["name"]) ? $post["title"] : "";
        $pass = isset($post["pass"]) ? $post["pass"] : "";
        $publicId = $this->generatePublicCode("courts");

        if (!$name || !$pass) {
            $this->errors[] = "Impossible de récupérer les éléments. merci de réessayer des valeurs correctes.";
        }
        if (empty($this->errors)) {
            $result = $this->editTournModel->addCourt($publicId, $name, $pass, $this->tournamentId);
            if ($result) { $this->success = "Ajout réalisé avec succès"; return; }
            else { $this->errors[] = "Échec de l'ajout."; return; }
        }
        return;
    }
    // Fonction permettant d'ajouter un joueur
    private function addPlayer (array $post): void {
        $fname = isset($post["firstname"]) ? $post["firstname"] : "";
        $lname = isset($post["lastname"]) ? $post["lastname"] : "";
        $nat = isset($post["nationality"]) ? $post["nationality"] : "";
        $rank = isset($post["rank"]) ? $post["rank"] : "";
        $publicId = $this->generatePublicCode("players");

        if (!$fname || !$lname) {
            $this->errors[] = "Impossible de récupérer les éléments. merci de réessayer au moins les prénom et nom.";
        }
        if (empty($this->errors)) {
            $result = $this->editTournModel->addPlayer($publicId, $fname, $lname, $nat, $rank, $this->tournamentId);
            if ($result) { $this->success = "Ajout réalisé avec succès"; return; }
            else { $this->errors[] = "Échec de l'ajout."; return; }
        }
        return;
    }
    // Fonction permettant d'ajouter un match
    private function addMatch (array $post): void {
        $type = isset($post["type"]) ? $post["type"] : "";
        $TAP1 = isset($post["TAP1"]) ? intval($post["TAP1"]) : 0;
        $TAP2 = isset($post["TAP2"]) ? intval($post["TAP2"]) : 0;
        $TBP1 = isset($post["TBP1"]) ? intval($post["TBP1"]) : 0;
        $TBP2 = isset($post["TBP2"]) ? intval($post["TBP2"]) : 0;
        $publicId = $this->generatePublicCode("courts");

        if (!$type || !$TAP1 || !$TBP1) {
            $this->errors[] = "Impossible de récupérer les éléments. merci de réessayer des valeurs correctes (au moins le format de jeu et les deux joueurs).";
        }
        if (empty($this->errors)) {
            $result = $this->editTournModel->addMatch($publicId, $this->tournamentId, $type, $TAP1, $TAP2, $TBP1, $TBP2);
            if ($result) {$this->success = "Ajout réalisé avec succès"; return; }
            else {$this->errors[] = "Échec de l'ajout."; return; }
        }
        return;
    }
    // Fonction permettant d'ajouter un arbitre
    private function addUmpire (array $post): void {
        $uname = isset($post["username"]) ? $post["username"] : "";
        $fname = isset($post["firstname"]) ? $post["firstname"] : "";
        $lname = isset($post["lastname"]) ? $post["lastname"] : "";
        $publicId = $this->generatePublicCode("courts");

        if (!$uname) {
            $this->errors[] = "Impossible de récupérer les éléments. merci de réessayer à minima un pseudo.";
        }
        if (empty($this->errors)) {
            $result = $this->editTournModel->addUmpire($publicId, $uname, $fname, $lname, $this->tournamentId);
            if ($result) {$this->success = "Succès"; return; }
            else {$this->errors[] = "Échec de l'ajout."; return; }
        }
        return;
    }




    // Fonction permettant de calculer la taille d'un draw en fonction du nombre de joueurs
    private function nextPowerOfTwo (int $nb): int {
        if ($nb <= 1) {return 1; }
        $power = 1; 
        while ($power < $nb) { $power *= 2; }
        return $power;
    } 


    // Fonction permettant de générer tous les matchs d'un draw
    private function generateDrawMatches (int $playersNb, int $drawId, int $scoringType): ?array {
        if (!$playersNb || $playersNb < 2) { 
            $this->errors[] = "Nombre de joueurs inssuffisant.";
        }
        if (!$drawId || $drawId < 1) { 
            $this->errors[] = "Identifiant du tableau incorrect.";
        }
        if (!$scoringType || $scoringType === 0) { 
            $this->errors[] = "Format de jeu incorrect.";
        }

        if (empty($this->errors)) {
            $matches = [];          // Tableau qui contiendra tous les matchs
            $round = 1;            // Nombre de tour initial (on génère la finale en premier donc position 1 dans le draw)
            $matchesPerRound = 1;   // Nombre de match par tour initial (on génère la finale en premier donc 1 match)

            $roundsNumber = log($playersNb, 2);    // On calcul le nombre de tours prévus dans le draw (ex: 5 tours pour 32 joueurs)

            while ($round <= $roundsNumber) {
                for ($i = 1; $i <= $matchesPerRound; $i++) {
                    $drawPos = str_pad(strval($round), 2, "0", STR_PAD_LEFT) . str_pad(strval($i), 2, "0", STR_PAD_LEFT);   // On construit la position (0101 pour la finale, 0516 pour le dernier match des 16èmes).
                    $matchPId = $this->generatePublicCode("matches");
                    $this->editTournModel->addDrawMatch($matchPId, $this->tournamentId, $scoringType, $drawId, $drawPos, null, null, null, null);
                    $matches[] = $drawPos;
                }
                $round++; // On incrémente round de 1
                $matchesPerRound *= 2; // On multiplie matchesPerRound par 2
            }
            
            return $matches;
        }

        return null;
    } 
}
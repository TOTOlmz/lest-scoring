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
}
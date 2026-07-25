<?php
declare(strict_types=1);

namespace App\controllers;
use App\Models\TournamentModel;
use App\Models\DetailsModel;
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

    public function __construct() {
        $this->tournamentModel = new TournamentModel;
        $this->detailsModel = new DetailsModel;
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



        /* GESTION DES DIFFERENTS FORMULAIRES */

        // Si le formulaire d'édition de joueur est soumis :
        if (isset($_POST) && isset($_POST["edit-player"])) {
            $token = isset($_POST["token"]) ? $_POST["token"] : "";
            $this->checkCsrfToken($token);
            if (empty($this->errors)) {
                $this->editPlayer($_POST);
            }
        }
        // Si le formulaire d'édition de court est soumis :
        if (isset($_POST) && isset($_POST["edit-court"])) {
            $token = isset($_POST["token"]) ? $_POST["token"] : "";
            $this->checkCsrfToken($token);
            if (empty($this->errors)) {
                $this->editCourt($_POST);
            }
        }
        // Si le formulaire d'édition d'arbitre est soumis :
        if (isset($_POST) && isset($_POST["edit-umpire"])) {
            $token = isset($_POST["token"]) ? $_POST["token"] : "";
            $this->checkCsrfToken($token);
            if (empty($this->errors)) {
                $this->editUmpire($_POST);
            }
        }

        
        // Si l'un des formulaire de suppression est soumis, on appèle la fonction deleteByPublicId() :
        if (isset($_POST) && isset($_POST["delete-player"])) {
            $token = isset($_POST["token"]) ? $_POST["token"] : "";
            $this->checkCsrfToken($token);
            if (empty($this->errors) && isset($_POST["public-id"]) && $_POST["public-id"] !== "") {
                $this->deleteElementByPublicId("players", $_POST["public-id"]);
            }
        }
        // Si le formulaire de suppression de court est soumis :
        if (isset($_POST) && isset($_POST["delete-court"])) {
            $token = isset($_POST["token"]) ? $_POST["token"] : "";
            $this->checkCsrfToken($token);
            if (empty($this->errors) && isset($_POST["public-id"]) && $_POST["public-id"] !== "") {
                $this->deleteElementByPublicId("courts", $_POST["public-id"]);
            }
        }
        // Si le formulaire de suppression d'arbitre est soumis :
        if (isset($_POST) && isset($_POST["delete-umpire"])) {
            $token = isset($_POST["token"]) ? $_POST["token"] : "";
            $this->checkCsrfToken($token);
            if (empty($this->errors) && isset($_POST["public-id"]) && $_POST["public-id"] !== "") {
                $this->deleteElementByPublicId("umpires", $_POST["public-id"]);
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


    // Fonction permettant de récupérer un tournoi à partir de son public ID
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

    // Fonction permettant d'éditer un joueur
    private function editPlayer(array $post): void {
        // On récupère toutes les valeurs
        $pId = isset($post["public-id"]) ? $post["public-id"] : null;
        $pFn = isset($post["player-fname"]) ? ucfirst($post["player-fname"]) : null;
        $pLn = isset($post["player-lname"]) ? strtoupper($post["player-lname"]) : null;
        $pNat = (isset($post["player-nat"]) && strlen($post["player-nat"]) === 3) ? strtoupper($post["player-nat"]) : null;
        $pRank = isset($post["player-rank"]) ? $post["player-rank"] : null;

        if (!$pId || !$pFn || !$pLn || !$pNat || !$pRank) {
            $this->errors[] = "Impossible de modifier les informations de la personne, des valeurs sont manqantes ou incorrectes.";
            return;
        }

        // On récupère l'identifiant après avoir vérifié la validité des valeurs du post
        $id = intval($this->detailsModel->getIdByPublicId("players", $pId));
        if (!$id || $id <= 0) {
            $this->errors[] = "Impossible de récupérer l'identifiant du joueur. Une erreur s'est produite.";
        }

        // Et s'il n'y a pas d'erreur, on tente de mettre à jour le joueur :
        if (empty($this->errors)) {
            if ($this->detailsModel->editPlayer($id, $pFn, $pLn, $pNat, $pRank )) {
                $this->success = "Mise à jour réussie pour " . $pFn . " " . $pLn;
            } else {
                $this->errors[] = "Erreur lors de la mise à jour.";
            }
        }
    }

    
    // Fonction permettant d'éditer un court
    private function editCourt(array $post): void {
        // On récupère toutes les valeurs
        $pId = isset($post["public-id"]) ? $post["public-id"] : null;
        $name = isset($post["court-name"]) ? ucfirst($post["court-name"]) : null;
        $password = isset($post["court-pass"]) ? $post["court-pass"] : null;

        if (!$pId || !$name || !$password) {
            $this->errors[] = "Impossible de modifier les informations du court, des valeurs sont manqantes ou incorrectes.";
            return;
        }

        // On récupère l'identifiant après avoir vérifié la validité des valeurs du post
        $id = intval($this->detailsModel->getIdByPublicId("courts", $pId));
        if (!$id || $id <= 0) {
            $this->errors[] = "Impossible de récupérer l'identifiant du court. Une erreur s'est produite.";
        }

        // Et s'il n'y a pas d'erreur, on tente de mettre à jour le court :
        if (empty($this->errors)) {
            if ($this->detailsModel->editCourt($id, $name, $password)) {
                $this->success = "Mise à jour réussie pour le court " . $name;
            } else {
                $this->errors[] = "Erreur lors de la mise à jour.";
            }
        }
    }

    
    // Fonction permettant d'éditer un arbitre
    private function editUmpire(array $post): void {
        // On récupère toutes les valeurs
        $pId = isset($post["public-id"]) ? $post["public-id"] : null;
        $uname = isset($post["umpire-uname"]) ? ucfirst($post["umpire-uname"]) : null;
        $fname = isset($post["umpire-fname"]) ? ucfirst($post["umpire-fname"]) : null;
        $lname = isset($post["umpire-lname"]) ? ucfirst($post["umpire-lname"]) : null;

        if (!$pId || !$uname || !$fname || !$lname) {
            $this->errors[] = "Impossible de modifier les informations du court, des valeurs sont manqantes ou incorrectes.";
            return;
        }

        // On récupère l'identifiant après avoir vérifié la validité des valeurs du post
        $id = intval($this->detailsModel->getIdByPublicId("umpires", $pId));
        if (!$id || $id <= 0) {
            $this->errors[] = "Impossible de récupérer l'identifiant du court. Une erreur s'est produite.";
        }

        // Et s'il n'y a pas d'erreur, on tente de mettre à jour le court :
        if (empty($this->errors)) {
            if ($this->detailsModel->editUmpire($id, $uname, $fname, $lname)) {
                $this->success = "Mise à jour réussie pour le court " . $fname . " " . $lname . "(" . $uname . ")";
            } else {
                $this->errors[] = "Erreur lors de la mise à jour.";
            }
        }
    }

    // Fonction permettant de supprimer ou anonymiser les élements en BDD
    private function deleteElementByPublicId(string $table, string $pId): void {


        $result = "";
        if ($table === "players") {
            $id = $this->detailsModel->getIdByPublicId($table, $pId);
            $result = $this->detailsModel->deletePlayerById($id);
        } elseif ($table === "courts") {
            $id = $this->detailsModel->getIdByPublicId($table, $pId);
            $result = $this->detailsModel->deleteCourtById($id);
        } elseif ($table === "umpires") {
            $id = $this->detailsModel->getIdByPublicId($table, $pId);
            $result = $this->detailsModel->deleteUmpireById($id);
        } else {
            $result = $this->errors[] = "Impossible de supprimer l'élément. Il n'est pas possible de le chercher dans la base de données.";
        }




        if ($result === "OK") {
            $this->success = "Suppression réussie.";
        } elseif ($result === "OK r") {
            $this->success = "Suppression impossible mais anonymisation réalisée (Il n'est pas possible de supprimer l'élément car il est lié à des matchs déjà joués ou programmés).";
        } else {
            $this->errors[] = $result;
        }
    }
}
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

        if ($this->userAuthorisation === false) {
            require_once ROOT_PATH . "/src/views/NonAuthorizationView.php";
            exit;
        }



        // Si le formulaire de suppression d'arbitre est soumis :
        if (isset($_POST) && isset($_POST["add-tournament"])) {
            echo "yes";
            $token = isset($_POST["token"]) ? $_POST["token"] : "";
            $this->checkCsrfToken($token);
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
}
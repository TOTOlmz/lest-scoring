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

class TournamentDetailsController extends BaseController {

    protected string $userStatus = "";
    protected array $tournament = [];
    protected TournamentModel $tournamentModel;
    protected DetailsModel $detailsModel;

    public function __construct() {
        $this->tournamentModel = new TournamentModel;
        $this->detailsModel = new DetailsModel;
        $this->checkConnectedStatus();
    }


    // Fonction de rendu de la page directeurs
    public function rendered() : void {

        // Edition du token csrf s'il n'est pas déjà édité + Vérification des droits d'accès
        $this->editToken();
        if (isset($_SESSION) && isset($_SESSION["user_role"])) {
            $this->userStatus = $_SESSION["user_role"];
        }

        // Si l'url contient bien un identifiant de tournoi, on le récupère
        if (isset($_GET["tournament"]) && $_GET["tournament"] !== "") {
            $this->getTournamentFromUrl($_GET["tournament"]);
        }
        // Si le formulaire de création d'un directeur est soumis :
        if (isset($_POST) && isset($_POST["add-director"])) {
            $token = isset($_POST["token"]) ? $_POST["token"] : "";
            $this->checkCsrfToken($token);
            // $this->addDirector($_POST);
        }




        // echo "<pre>";
        // print_r($this->tournament);
        // echo "</pre>";
        require_once ROOT_PATH . "/src/views/TournamentDetailsView.php";
    }


    private function getTournamentFromUrl(string $publicId): void {

        $tournId = $this->tournamentModel->getTournamentIdByPublicId($publicId);    
        // echo "id du tournoi : " . $tournId . "<br>";
        $this->tournament = $this->detailsModel->getAllTournamentElements(intval($tournId)); 

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
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
        echo "id du tournoi : " . $tournId . "<br>";
        $this->tournament = $this->detailsModel->getAllTournamentElements(intval($tournId)); 

    }


}
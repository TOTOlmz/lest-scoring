<?php
declare(strict_types=1);

namespace App\controllers;
use App\Models\TournamentModel;
/**
 * UserSpaceController
 * 
 * Contrôleur gérant l'affichage de 'espace utilisateur
 * 
 */

class UserSpaceController extends BaseController {

    protected array $tournaments = [];
    protected TournamentModel $tournamentModel;

    public function __construct() {
        $this->tournamentModel = new TournamentModel;
        $this->checkConnectedStatus();
    }


    // Fonction de rendu de la page directeurs
    public function rendered() : void {

        // Si personne ne semble connecter ou si l'utilisateur n'est pas directeur, on détruit la session par sécurité
        $this->checkAccessAutorisation("DIRECTOR");
        
        $userId = $this->tournamentModel->getIdByPublicId("users", $_SESSION["user_public_id"]);
        $directorTournaments = $this->tournamentModel->getTournamentsByDirectorId($userId);
        foreach ($directorTournaments as $dt) {
            $this->tournaments[] = $this->tournamentModel->getAllTournamentDataById($dt["id"]);

            $this->tournaments = $this->processTournaments($this->tournaments);
        }

        require_once ROOT_PATH . "/src/views/UserSpaceView.php";
    }
}
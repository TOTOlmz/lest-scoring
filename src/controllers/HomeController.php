<?php

declare(strict_types=1);

namespace App\controllers;
use App\models\TournamentModel;
/**
 * HomeController
 * 
 * Contrôleur responsable de la gestion de la page d'accueil
 */

class HomeController extends BaseController {

    protected TournamentModel $tournamentModel;
    

    // Constructeur permettant d'initialiser les services et modèles nécessaires
    public function __construct() {
        $this->tournamentModel = new TournamentModel;
        $this->checkConnectedStatus();
    }

    // Fonction principale pour afficher la page d'accueil
    public function rendered(): void {
        $currentTournament = $this->tournamentModel->getAllTournamentDataById(1);
        // echo "<pre>"; print_r($currentTournament); echo "</pre>";
        require_once ROOT_PATH . '/src/views/HomeView.php';
    }   

}
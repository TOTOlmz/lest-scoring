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

    protected array $tournaments = [];
    protected TournamentModel $tournamentModel;
    

    // Constructeur permettant d'initialiser les services et modèles nécessaires
    public function __construct() {
        $this->tournamentModel = new TournamentModel;
        $this->checkConnectedStatus();
    }

    // Fonction principale pour afficher la page d'accueil
    public function rendered(): void {

        $getTournaments = $this->tournamentModel->getTournamentsIdByDate(time());
        $tournData = [];
        foreach ($getTournaments as $i => $t) {
            $tournData[$i] = $this->tournamentModel->getAllTournamentDataById($t);
            //echo "<pre>"; print_r($tournData); echo "</pre>";
        }
        
        $this->tournaments = $this->processTournaments($tournData);
        //echo "<pre>"; print_r($this->tournaments); echo "</pre>";


        require_once ROOT_PATH . '/src/views/HomeView.php';
    }   

}
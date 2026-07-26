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

        
        // Si le formulaire de suppression d'arbitre est soumis :
        if (isset($_POST) && isset($_POST["add-tournament"])) {
            $token = isset($_POST["token"]) ? $_POST["token"] : "";
            $this->checkCsrfToken($token);
            if (empty($this->errors)) {
                $this->createTournament($userId, $_POST);
            }
        }

        
        $directorTournaments = $this->tournamentModel->getTournamentsByDirectorId($userId);
        foreach ($directorTournaments as $dt) {
            $this->tournaments[] = $this->tournamentModel->getAllTournamentDataById($dt["id"]);

            $this->tournaments = $this->processTournaments($this->tournaments);
        }



        require_once ROOT_PATH . "/src/views/UserSpaceView.php";
    }



    // Fonction permettant de créer un tournoi
    private function createTournament(int $userId, array $post): void {
        // On récupère les différentes valeurs du post
        $name = isset($post["name"]) ? $post["name"] : "";
        $city = isset($post["city"]) ? $post["city"] : "";
        $club = isset($post["club"]) ? $post["club"] : "";
        $tempStart = isset($post["start"]) ? $post["start"] : "";
        $tempEnd = isset($post["end"]) ? $post["end"] : "";
        $publicId = isset($post["director"]) ? $post["director"] : "";

        // On s'assure qu'elles soient toutes fournies
        if (!$name || !$tempStart || !$tempEnd || !$publicId || !$city || !$club) {
            $this->errors[] = "Erreur lors de la réception des informations, merci de renseigner tous les champs.";
        }

        // On vérifie la correspondance de l'utilisateur
        if (!isset($_SESSION["user_public_id"]) || $_SESSION["user_public_id"] !== $publicId) {
            $this->errors[] = "Erreur lors de la vérification de votre identifiant public. Veuillez réessayer.";
        }

        // On vérifie que l'ID de l'utilisateur soit correct
        if (!is_int($userId) || intval($userId) <= 0) {
            $this->errors[] = "Erreur lors de la vérification de votre identifiant. Veuillez réessayer.";
        }

        // On transforme les dates en timestamps et on s'assure que end soit supérieur à start
        $start = strtotime($tempStart);
        $end = strtotime($tempEnd);
        if ($start >= $end) {
            $this->errors[] = "la date de début doit être supérieure à la date de fin.";
        }

        if (empty($this->errors)) {
            $tournPId = $this->generatePublicCode("tournaments");
            $this->tournamentModel->setTournament($tournPId, $name, $start, $end, $userId, $city, $club);
        }

    }
}
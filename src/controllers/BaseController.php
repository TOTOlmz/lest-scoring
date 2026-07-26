<?php 
declare(strict_types=1);
namespace App\controllers;

use App\models\BaseModel;

/**
 * BaseController
 * 
 * Contrôleur de base
 * Il fournie les fonctions de sécurités génériques
 * ainsi que les fonctions de mailing
 */

class BaseController {

    protected array $errors = [];
    protected string $success = '';
    protected bool $isConnected = false;
    protected bool $isAdmin = false;
    protected bool $isInUserSpace = false;

    
    // Fonction permettant de vérifier si l'utilisateur est connecté et si c'est l'administrateur
    public function checkConnectedStatus(): void {
        if (isset($_SESSION) && isset($_SESSION["user_role"])) {
            $this->isConnected = true;
            if ($_SESSION["user_role"] === "ADMIN") {
                $this->isAdmin = true;
            }
            if ($_ENV["app_uri"] === "/mon-espace" || $_ENV["app_uri"] === "/espace-admin") {
                $this->isInUserSpace = true;
            }
        }
    }

    // Fonction créant un token si celui ci n'existe pas
    public function editToken(): void {
        if (empty($_SESSION['token'])) {
            $_SESSION['token'] = bin2hex(random_bytes(32));
        }
    }
    // Fonction permettant de vérifier le jeton csrf
    protected function checkCsrfToken(string $token): void {
        if (!isset($token) || !isset($_SESSION['token']) || !hash_equals($_SESSION['token'], $token)) {
            $this->errors[] = 'Accès non autorisé. Le formulaire a été manipulé entre sa soumission et sa réception.';
            return;
        } else {
            $_SESSION["token"] = bin2hex(random_bytes(32));
            return;
        }
    }
    // Fonction permettant la déconnexion
    public function logout(): void {
        session_unset();
        session_destroy();
        header('Location: ./');
        exit();
    }

    // Vérification de l'autorisation d'accès à une page
    public function checkAccessAutorisation(string $role) {
        if (!isset($_SESSION) || !isset($_SESSION["user_public_id"])
            || (isset($_SESSION["user_role"]) && $_SESSION["user_role"] !== $role)) { 
            $this->logout();
            header('Location: ./');
            exit();
        }
    }


    // Fonction permettant de traiter les informations d'un tableau de tournois
    public function processTournaments(array $tournaments): array {
        // cette fonction traite les tournois récupérés avec tournamentModel -> getAllTournamentDataById()
        foreach ($tournaments as $ti => $t) {
            // On adapte les timestamps des dates de tournoi

            $tournaments[$ti]["start_time"] = date("d/m/y", intval($t["start_time"]));
            $tournaments[$ti]["end_time"] = date("d/m/y", intval($t["end_time"]));

            foreach ($t["courts"] as $ci => $c) {
                foreach ($c["matches"] as $mi => $m) {
                    
                // On adapte le nom des joueurs pour l'affichage
                    // Si c'est un double, on écrit les noms sur 2 lignes au format P. NOM
                    if (isset($m["PA2_lastname"]) && $m["PA2_lastname"] !== "" && $m["PA2_lastname"] !== null) {

                        $teamA = 
                        strtoupper(substr($m["PA1_firstname"], 0, 1)) . ". " . strtoupper($m["PA1_lastname"]) . " <br> " .
                        strtoupper(substr($m["PA2_firstname"], 0, 1)) . ". " . strtoupper($m["PA2_lastname"]);

                        $teamB = 
                        strtoupper(substr($m["PB1_firstname"], 0, 1)) . ". " . strtoupper($m["PB1_lastname"]) . " <br> " .
                        strtoupper(substr($m["PB2_firstname"], 0, 1)) . ". " . strtoupper($m["PB2_lastname"]);


                    // Si c'est un simple, on écrit les noms sur 2 lignes au format Prénom <br> NOM
                    } else {
                        $teamA = ucfirst($m["PA1_firstname"]) . " <br> " . strtoupper($m["PA1_lastname"]);
                        $teamB = ucfirst($m["PB1_firstname"]) . " <br> " . strtoupper($m["PB1_lastname"]);
                    }

                    $tournaments[$ti]["courts"][$ci]["matches"][$mi]["teamAName"] = $teamA;
                    $tournaments[$ti]["courts"][$ci]["matches"][$mi]["teamBName"] = $teamB;


                // On adapte les tie-breaks et les temps de set pour l'affichage
                    foreach ([1, 2, 3] as $n) {
                        if (isset($m["teamA_tie$n"]) && ($m["teamA_tie$n"] !== null || $m["teamA_tie$n"] !== "")) {
                            $tournaments[$ti]["courts"][$ci]["matches"][$mi]["teamA_set$n"] = 
                            $tournaments[$ti]["courts"][$ci]["matches"][$mi]["teamA_set$n"] . "<sup>" . intval($tournaments[$ti]["courts"][$ci]["matches"][$mi]["teamA_tie$n"]) . "</sup>";
                            $tournaments[$ti]["courts"][$ci]["matches"][$mi]["teamB_set$n"] . "<sup>" . intval($tournaments[$ti]["courts"][$ci]["matches"][$mi]["teamB_tie$n"]) . "</sup>";
                        }
                        if (isset($m["set" . $n . "_time"]) && ($m["set" . $n . "_time"] !== null || $m["set" . $n . "_time"] !== "") &&
                        is_int($m["set" . $n . "_time"])) {
                            $tournaments[$ti]["courts"][$ci]["matches"][$mi]["set" . $n . "_time"] = date("H:i", $m["set" . $n . "_time"]);

                        }
                    } 
                // On adapte le temps de match pour l'affichage
                    if (isset($tournaments[$ti]["courts"][$ci]["matches"][$mi]["match_time"]) && 
                    ($tournaments[$ti]["courts"][$ci]["matches"][$mi]["match_time"] !== null || $tournaments[$ti]["courts"][$ci]["matches"][$mi]["match_time"] !== "") &&
                    is_int($tournaments[$ti]["courts"][$ci]["matches"][$mi]["match_time"])) {
                        $tournaments[$ti]["courts"][$ci]["matches"][$mi]["match_time"] = date("H:i", $tournaments[$ti]["courts"][$ci]["matches"][$mi]["match_time"]);
                    }
                                
                }
            } 
        }
        return $tournaments;
    }

    // Fonction permettant de générer un code utilisable pour identifier un élément via les paramètres d'url
    public function generatePublicCode(string $table, int $length = 11): string {
        
        // On crée une chaine comportant toutes les lettres en MAJ et en min ainsi que tous les chiffres
        $characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        
        // tant que le code généré n'est pas unique, on génère un nouveau code
        do {
            $code = "";

            // On récupère un caractère aléatoire de la chaine "characters" et on l'ajoute à code. On fait ca autant de fois que "length" nous le demande
            for ($i = 0; $i < $length; $i++) {
                $code .= $characters[random_int(0, strlen($characters) - 1)];
            }
            // On vérifie la présence du code en BDD
        } while (!BaseModel::isUniqueCode($code, $table));

        return $code;
    }

}
<?php
declare(strict_types=1);

namespace App\controllers;
use App\Models\TournamentModel;
use App\Models\AdminModel;
/**
 * UserSpaceController
 * 
 * Contrôleur gérant l'affichage de l'espace administrateur
 * 
 */

class AdminSpaceController extends BaseController {

    protected array $tournaments = [];
    protected array $result = [];
    protected int $userId = 0;
    protected TournamentModel $tournamentModel;
    protected AdminModel $adminModel;

    public function __construct() {
        $this->tournamentModel = new TournamentModel;
        $this->adminModel = new AdminModel;
        $this->checkConnectedStatus();
    }


    // Fonction de rendu de la page directeurs
    public function rendered() : void {

        // Edition du token csrf s'il n'est pas déjà édité + Vérification des droits d'accès
        $this->editToken();
        $this->checkAccessAutorisation("ADMIN");


        // Si le formulaire de création d'un directeur est soumis :
        if (isset($_POST) && isset($_POST["add-director"])) {
            $token = isset($_POST["token"]) ? $_POST["token"] : "";
            $this->checkCsrfToken($token);
            $this->addDirector($_POST);
        }

        // Si le formulaire d'édition de directeur est envoyé :
        if (isset($_POST) && isset($_POST["edit-director"])) {
            $token = isset($_POST["token"]) ? $_POST["token"] : "";
            $this->checkCsrfToken($token);
            $this->editDirector($_POST);
        }

        // Si le formulaire de création de tournoi est soumis :
        if (isset($_POST) && isset($_POST["create-tournament"])) {
            $token = isset($_POST["token"]) ? $_POST["token"] : "";
            $this->checkCsrfToken($token);
            $this->addTournament($_POST);
        }

        // Si le formulaire de recherche est soumis :
        if (isset($_POST) && isset($_POST["search-form"])) {
            $token = isset($_POST["token"]) ? $_POST["token"] : "";
            $this->checkCsrfToken($token);
            $this->research($_POST);
        }

        // On récupère les tournois dont l'admin a la charge et on les adapte :
        $this->userId = $this->tournamentModel->getIdByPublicId("users", $_SESSION["user_public_id"]);
        $directorTournaments = $this->tournamentModel->getTournamentsByDirectorId($this->userId);
        foreach ($directorTournaments as $dt) {
            $this->tournaments[] = $this->tournamentModel->getAllTournamentDataById($dt["id"]);

            $this->tournaments = $this->processTournaments($this->tournaments);
        }

        
        require_once ROOT_PATH . "/src/views/AdminSpaceView.php";
    }


    // Fonction traitant le formulaire d'ajout d'un directeur
    private function addDirector(array $post): void {
        // On réédite le token CSRF
        $this->editToken();

        $email = null;

        // Si le chammps d'email est présent dans le post :
        if (isset($post["tournament-director"])) {
            $email = $post["tournament-director"];
            // On vérifie que ce soit bien un format d'email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->errors[] = "Merci de reneigner une adresse email valide.";
                return;
            }
            
            $isExisting = $this->adminModel->emailExists($email);

            if (empty($this->errors) && $isExisting) {
                $this->errors[] = "L'adresse email correspond déjà à un directeur permanent" . (is_int($isExisting) ? " avec l'id : " . $isExisting . "." : ".");
                return;                
            }
            
            $password = $_ENV["DEFAULT_PASSWORD"];
            $publicId = $this->generatePublicCode("users");
            if (empty($this->errors) && $this->adminModel->setDirector($publicId, $email, $password)) {
                $this->success = "Directeur ajouté avec succès.";
            } else {
                $this->errors[] = "Erreur lors de l'ajout du directeur.";
            }

        } else {
            $this->errors[] = "Aucun email reçu, merci de recommencer la création.";
        }
    }

    private function editDirector(array $post): void {
        // On réédite le token CSRF
        $this->editToken();

        // On récupère les différentes valeurs
        $publicId = isset($post["public-id"]) ? $post["public-id"] : "";
        $name = isset($post["name"]) ? $post["name"] : "";
        $email = isset($post["email"]) ? $post["email"] : "";
        $role = isset($post["role"]) ? $post["role"] : "";
        $tempPerm = isset($post["permanent"]) ? $post["permanent"] : "";
        $tempSusp = isset($post["suspended"]) ? $post["suspended"] : "";

        
        $dirId = $this->tournamentModel->getIdByPublicId("users", $publicId);
        if ($dirId <= 0) {
            $this->errors[] = "Impossible de récupérer l'identifiant de l'utilisateur. Mise à jour arrêtée.";
        }

        // On vérifie l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "Merci de reneigner une adresse email valide.";
        }
        // On s'assure qu'il soit bien unnique
        $isExisting = $this->adminModel->emailExists($email);
        if (empty($this->errors) && $isExisting !== $dirId) {
            $this->errors[] = "L'adresse email correspond déjà à un autre directeur" . (is_int($isExisting) ? " avec l'id : " . $isExisting . "." : ".");
        }
        // on vérifie que toutes les valeurs sont présentes
        if (!$publicId || !$name || !$email || !$role) {
            $this->errors[] = "Impossible de modifier l'utilisateur. Des valeurs sont manquantes.";
        }
        if (($tempPerm == "on" || $tempPerm == 0) && ($tempSusp == "on" || $tempSusp == 0)) {
            $this->errors[] = "Impossible de modifier l'utilisateur. Les valeurs 'permanent' et 'suspendu' sont incorrectes.";
        }
        $perm = $tempPerm === "on" ? 1 : 0;
        $susp = $tempSusp === "on" ? 1 : 0;

        $acceptedRoles = ["DIRECTOR", "ADMIN", "USER", "UMPIRE"];
        if (!in_array($role, $acceptedRoles)) {
            $this->errors[] = "Impossible de modifier l'utilisateur, le role attribué n'est pas acceptable.";
        }

        
        if (empty($this->errors)) {
            if ($this->adminModel->editDirector($dirId, $name, $email, $role, $perm, $susp)) {
                $this->success = "Mise à jour réussie.";
            } else {
                $this->errors[] = "Erreur rencontrée lors de la mise à jour, veuillez réessayer.";
            }
        }




    }

    // Fonction permettant d'ajouter un tournoi
    private function addTournament(array $post): void {
        // On réédite le token CSRF
        $this->editToken();

        // On prépare les valeurs
        $email = null;
        $dirId = 1;

        // On vérifie le champ du nom du tournoi
        $tournamentName = (isset($post["tournament-name"]) && is_string($post["tournament-name"])) ? $post["tournament-name"] : "";
        if ($tournamentName === "" || $tournamentName === null) {
            $this->errors[] = "Aucun nom fourni pour le tournoi.";
        }

        // Si le chammps d'email est présent dans le post :
        $directorEmail = (isset($post["director-email"]) && is_string($post["director-email"])) ? $post["director-email"] : "";
        if ($directorEmail !== "" && $directorEmail !== null) {
            // On vérifie que ce soit bien un format d'email
            if (!filter_var($directorEmail, FILTER_VALIDATE_EMAIL)) {
                $this->errors[] = "Merci de reneigner une adresse email valide.";
                return;
            }

            // On stocke l'ID du directeur s'il existe
            $dirId = $this->adminModel->emailExists($directorEmail);

            // Si le directeur n'existe pas, on le crée
            if (!$dirId || $dirId === 1) {
                $password = $_ENV["DEFAULT_PASSWORD"];
                
                $publicDirId = $this->generatePublicCode("users");
                // On crée le directeur en BDD
                $dirId = $this->adminModel->setDirector($publicDirId, $directorEmail, $password);
                if (empty($this->errors) && $dirId && is_int($dirId)) {
                    $this->success = "Directeur ajouté avec succès.";
                } else {
                    $this->errors[] = "Erreur lors de la création du directeur.";
                }              
            }
        }
        // S'il n'y a pas de mail envoyé, le tournoi est sous la responsabilité de l'admin ($dirId reste donc 1)
        
        // On crée le tournoi s'il n'y a pas d'erreurs
        $publicTournId = $this->generatePublicCode("users");
        if (empty($this->errors)  && $this->adminModel->setTournament($publicTournId, $tournamentName, $dirId)) {
            $this->success = "Tournoi ajouté avec succès.";
        } else {
            $this->errors[] = "Erreur lors de la création du tournoi.";
        }

    }

    // Fonction permettant de rechercher un élément (tournoi et directeur)
    private function research(array $post): void {
        
        // On réédite le token CSRF
        $this->editToken();


        $this->result = [];

        $field = isset($post["search-field"]) ? $post["search-field"] : null;
        $category = isset($post["search-category"]) ? $post["search-category"] : null;

        // On regarde si la catégorie est reconnue
        $categoryChecker = ["name", "city", "club", "email"];
        if (!in_array($category, $categoryChecker)) {
            $this->errors[] = "La catégorie est invalide.";
            return;
        }
        if ($field === null) {
            $this->errors[] = "Aucune valeur renseigné.";
            return;
        }

        if (empty($this->errors)) {
            $this->result = $this->adminModel->adminSearch($field, $category);
        }
        if (!empty($this->result)) {
            $this->result["searching"] = true;
        }
        
        return;
    }
}
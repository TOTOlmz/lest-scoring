<?php

declare(strict_types=1);

namespace App\controllers;
use App\models\AuthModel;
use PDOException;
/**
 * HomeController
 * 
 * Contrôleur responsable de la gestion de la page d'accueil
 */

class AuthController extends BaseController {

    protected AuthModel $authModel;
    

    // Constructeur permettant d'initialiser les services et modèles nécessaires
    public function __construct() {
        $this->authModel = new AuthModel;
        $this->checkConnectedStatus();
    }

    // Fonction principale pour afficher la page de connexion
    public function connection(): void {


        // On génère un token csrf s'il n'en existe pas pour pouvoir l'insérer dans le formulaire
        $this->editToken();


        // Si le formulaire de connexion est soumis :
        if (isset($_POST['login'])) {


            // on vérifie si les tokens existent dans la session et dans le formulaire utilisateur, et qu'ils sont égaux.
            $token = isset($_POST["token"]) ? $_POST["token"] : "";
            $this->checkCsrfToken($token);


            if (empty($this->errors)) {

                
                $email = trim($_POST['email']);
                $password = $_POST['password'];

                if (!$email || !$password) {
                    $this->errors[] = 'Merci de renseigner tous les champs';
                }
                
                
                // Si pas d'erreurs, on connecte l'utilisateur
                if (empty($this->errors)) {
                    
                    // On appelle la fonction qui crée la session
                    $sessionSet = $this->setSession($email, $password);

                    // On gère la redirection
                    if ($sessionSet === 'ADMIN') {
                        header ('Location: ./espace-admin');
                        exit();
                    } elseif ($sessionSet !== null && $sessionSet !== "") {
                        header ('Location: ./mon-espace');
                        exit();
                    }
                }
            }

        }   

        require_once ROOT_PATH . '/src/views/authentication/ConnectionView.php';

    }   

    // Fonction principale pour afficher la page de création de compte
    public function registration(): void {

        $this->editToken();

        
        // Si le formulaire est soumis
        if (isset($_POST) && isset($_POST["registration"])) {

            // on vérifie si les tokens existent dans la session et dans le formulaire utilisateur, et qu'ils sont égaux.
            $token = isset($_POST["token"]) ? $_POST["token"] : "";
            $this->checkCsrfToken($token);

            // Si tout est bon, on peut continuer :
            if (empty($this->errors)) {
                $name = trim($_POST['name'])  ?? '';
                $email = trim($_POST['email'])  ?? '';
                $password = $_POST['password'] ?? '';
                $confirmPassword = $_POST['confirm-password'] ?? '';
                
                
                if (empty($name) || empty($email) || empty($password)) {
                    $this->errors[] = 'Merci de renseigner tous les champs';
                }
                
                if ($password !== $confirmPassword) {
                    $this->errors[] = 'Les mots de passe ne correspondent pas';
                }
                
                if (!$this->passwordCheck($password)) {
                    $this->errors[] = 'Le mot de passe ne respecte pas les critères requis';
                }
                $password = password_hash($password, PASSWORD_BCRYPT);
                
                // On appelle les fonctions du modèle pour vérifier que le mailest bien unique
                if ($this->authModel->emailExists($email)) {
                    $this->errors[] = 'Cet email est déjà utilisé';
                }
                

                
                // Si pas d'erreurs :
                if (empty($this->errors)) {
                    try {
                        // On crée l'utilisateur
                        $publicId = $this->generatePublicCode("users", 16);
                        $userId = $this->authModel->setUser($publicId, $name, $email, $password, 'USER');        
                        
                    } catch (PDOException $e) {
                        $this->errors[] = 'Erreur lors de la création du compte : ' . $e->getMessage();
                    }
                }
                if (empty($this->errors)) {
                    if ($_SESSION['user_role'] && $_SESSION['user_role'] === "DIRECTOR") {
                        header('Location: ./mon-espace');
                    } elseif ($_SESSION['user_role'] && $_SESSION['user_role'] === "ADMIN") {
                        header('Location: ./espace-admin');
                    }

                }
            }
        }

        require_once ROOT_PATH . '/src/views/authentication/RegistrationView.php';

    }   




    /* 
    FONCTIONS LIEES A LA CREATION DE COMPTE
    */

    // Fonction permettant de vérifier la robustesse du mot de passe
    private function passwordCheck($password) {
        return strlen($password) >= 8 &&        // On vérifie la longueur minimale
        strtolower($password) !== $password &&  // La presence d'une minuscule
        strtoupper($password) !== $password &&  // La presence d'une majuscule
        preg_match('/[0-9]/', $password);       // La presence d'un chiffre
    }


    /* 
    FONCTION LIEE A L'OUVERTURE DE SESSION  
    */


    // Fonction permettant d'ouvrir une session
    private function setSession(string $email, string $password): ?string {

        $user = $this->authModel->getVerifiedUser($email, $password);
        if ($user === null || empty($user)) {  // Si user n'est pas trouvé :
            $this->errors[] = 'Ces identifiants ne correspondent à aucun compte';
        }

        if (empty($this->errors)) {
            // On démarre la session
            $_SESSION['user_public_id'] = $user['id_to_display'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];

            session_regenerate_id(true);

            // On gère la redirection
            return $_SESSION["user_role"];
        }
        return "";
    }
}
<?php
/* |||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    Modele gérant les différentes opérations liées à la connexion
    et l'inscription des utilisateurs
||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||*/

namespace App\models;



class AuthModel extends BaseModel {

    // Fonction permettant de récupérer les informations de l'utilisateur dans la bdd si son mot de passe correspond
    public function getVerifiedUser(string $email, string $password): ?array {
        $sql = 'SELECT * FROM users WHERE email = :email';
        $stmt = $this->fetchOne($sql, ['email' => $email]);
        $user = $stmt;
        // On vérifie la correspondance du mot de passe
        if ($user && password_verify($password, $user['password'])) {  
            return $user;
        }
        return null;
    }

    // Fonction permettant de vérifier l'unicité d'un email
    public function emailExists(string $email): bool {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $result = $this->fetchOne($sql, ["email" => $email]);
        if ($result && !empty($result)) {
            return true;
        } else {
            return false;
        }
    }

    // Fonction permettant de créer un nouvel utilisateur
    public function setUser(string $name, string $email, string $password, string $role): int {
        $sql = "INSERT INTO users (name, email, password, role, is_permanent, is_suspended)
        VALUES (:name, :email, :password, :role, 1, 0)";
        return $this->lastInsert($sql, [
            "name" => $name,
            "email" => $email,
            "password" => $password,
            "role" => $role
        ]);
    }

    // Fonction permettant de créer un nouvel utilisateur
    public function getUserById(int $id): array {
        $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
        return $this->fetchOne($sql, ["id" => $id]);
    }




}
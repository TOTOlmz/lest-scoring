<?php
/* |||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    Modele gérant les différentes opérations liées aux
    manipulations de l'administrateur
||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||*/

namespace App\models;



class AdminModel extends BaseModel {

    // Fonction permettant de récupérer les informations de l'utilisateur dans la bdd si son mot de passe correspond
    public function setDirector(string $publicId, string $email, string $password): ?int {
        $sql = 'INSERT INTO users (id_to_display, email, password, role, is_permanent, is_suspended) 
        VALUES (:public_id, :email, :password, :role, 1, 0)';
        return $this->lastInsert($sql, ['public_id' => $publicId,'email' => $email, 'password' => $password, "role" => "DIRECTOR"]);
    }

    // Fonction permettant de modifier les informations d'un utilsateur (directeur)
    public function editDirector(int $id, string $name, string $email, string $role, int $perm, int $susp): ?bool {
        $sql = 'UPDATE users SET name = :name, email = :email, role = :role, is_permanent = :perm, is_suspended = :susp WHERE id = :id';
        $params = [
            "id" => $id,
            "name" => $name,
            "email" => $email,
            "role" => $role,
            "perm" => $perm,
            "susp" => $susp
        ];
        return $this->executeQuery($sql, $params) ? true : false;
    }

    // Fonction permettant de vérifier l'unicité d'un email
    public function emailExists(string $email): ?int {
        $sql = "SELECT id FROM users WHERE email = :email LIMIT 1";
        return $this->fetchColumn($sql, ["email" => $email]);
    }

    // Fonction permettant de créer un nouvel utilisateur
    public function setTournament(string $publicId, string $name, int $dId): int {
        $sql = "INSERT INTO tournaments (id_to_display, name, director_id)
        VALUES (:public_id, :name, :director_id)";
        return $this->lastInsert($sql, [
            "public_id" => $publicId,
            "name" => $name,
            "director_id" => $dId
        ]);
    }

    // Fonction permettant de créer un nouvel utilisateur
    public function getUserById(int $id): array {
        $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
        return $this->fetchOne($sql, ["id" => $id]);
    }


    // Fonction permettant de rechercher un tournoi ou un directeur
    public function adminSearch(mixed $field, string $category): array {

        
        $result = [];

        // On cherche les tournois qui correspondent
        if ($category !== "email") {
            $sql = "SELECT t.id, t.id_to_display, t.club, t.city, t.name, t.start_time, t.end_time, d.name AS director_name, d.email AS director_email  
            FROM tournaments t JOIN users d ON d.id = t.director_id 
            WHERE t.$category = :field";
            if ($category === "name") {
                $sql = $sql . " OR d.$category = :field";
            }
            $tournaments = $this->fetchAll($sql, ["field" => $field]);
            if (is_array($tournaments) && !empty($tournaments)) {
                $result["tournaments"] = $tournaments;
            }   
        }
        // Puis on cherche les directeurs qui correspondent
        $sql2 = "SELECT * FROM users WHERE name = :field OR email = :field";
        $users = $this->fetchAll($sql2, ["field" => $field]);
        if (is_array($users) && !empty($users)) {
            $result["directors"] = $users;
        }
        // Enfin, on retourne le tableau
        if (!empty($result)) {
            return $result;
        } else {
            return [];
        }
    }


}
<?php
/* |||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    Modele gérant les différentes opérations liées à la connexion
    et l'inscription des utilisateurs
||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||*/

namespace App\models;



class DetailsModel extends BaseModel {

    // Fonction permettant de récupérer toutes les infos d'un tournoi
    public function getAllTournamentElements(int $tournId): ?array {

        // On récupère déjà les éléments du tournoi
        $sql = 'SELECT t.id_to_display, t.club, t.city, t.name, t.start_time, t.end_time, d.name AS director_name 
        FROM tournaments t JOIN users d ON d.id = t.director_id WHERE t.id = :id';
        $result = $this->fetchOne($sql, ['id' => $tournId]);
        if (!$result) {
            return null;
        }

        // On récupère les draws du tournoi
        $sql = "SELECT id, id_to_display, title, type, format, size FROM draws WHERE tournament_id = :id";
        $result["draws"] = $this->fetchAll($sql, ['id' => $tournId]);

        // On récupère les courts du tournoi
        $sql = "SELECT id_to_display, name, password FROM courts WHERE tournament_id = :id";
        $result["courts"] = $this->fetchAll($sql, ['id' => $tournId]);

        // On récupère les arbitres du tournoi
        $sql = "SELECT id_to_display, username, firstname, lastname FROM umpires WHERE tournament_id = :id";
        $result["umpires"] = $this->fetchAll($sql, ['id' => $tournId]);

        // On récupère ensuite ses matchs
        $sql = 'SELECT m.id_to_display, m.scoring_type, m.draw_position, m.draw_id, m.final_score, 
        taa.lastname AS teamAP1_name, tab.lastname AS teamAP2_name, tba.lastname AS teamBP1_name, tbb.lastname AS teamBP2_name, m.status, m.winner
        FROM matches m
        LEFT JOIN players taa ON m.teamA_player1_id = taa.id
        LEFT JOIN players tab ON m.teamA_player2_id = tab.id
        LEFT JOIN players tba ON m.teamB_player1_id = tba.id
        LEFT JOIN players tbb ON m.teamB_player2_id = tbb.id
        WHERE m.tournament_id = :id ORDER BY m.draw_position ASC';
        $result["matches"] = $this->fetchAll($sql, ['id' => $tournId]);

        // On récupère ensuite ses joueurs
        $sql = 'SELECT DISTINCT id_to_display, firstname, lastname, nationality, rank FROM players 
        WHERE tournament_id = :id ORDER BY lastname, firstname ASC';
        $result["players"] = $this->fetchAll($sql, ['id' => $tournId]);

        return $result;
    }


    /* FONCTIONS GÉRANT L'ÉDITION D'ÉLÉMENTS*/

    // fonction en charge de l'édition de joueurs
    public function editPlayer(int $id, string $fname, string $lname, string $nat, string $rank): bool {
        $sql = "UPDATE players SET firstname = :fname, lastname = :lname, nationality = :nat, rank = :rank WHERE id = :id LIMIT 1";
        $result = $this->executeQuery($sql, [
            "fname" => $fname,
            "lname" => $lname,
            "nat" => $nat,
            "rank" => $rank,
            "id" => $id
        ]);
        return ($result) ? true : false;
    }
    // fonction en charge de l'édition de courts
    public function editCourt(int $id, string $name, string $password): bool {
        $sql = "UPDATE courts SET name = :name, password = :password WHERE id = :id LIMIT 1";
        $result = $this->executeQuery($sql, [
            "name" => $name,
            "password" => $password,
            "id" => $id
        ]);
        return ($result) ? true : false;
    }
    // fonction en charge de l'édition d'arbitres
    public function editUmpire(int $id, string $uname, string $fname, string $lname): bool {
        $sql = "UPDATE umpires SET username = :username, firstname = :firstname, lastname = :lastname WHERE id = :id LIMIT 1";
        $result = $this->executeQuery($sql, [
            "username" => $uname,
            "firstname" => $fname,
            "lastname" => $lname,
            "id" => $id
        ]);
        return ($result) ? true : false;
    }


    // Fonction permettant de supprimer un joueur
    public function deletePlayerById (int $id): ?string {
        $sql = "DELETE * FROM players WHERE id = :id LIMIT 1";
        try {
            $result = $this->executeQuery($sql, ["id" => $id]) ? "OK" : "Échec de la suppression.";
        } catch (\PDOException $e) {
            try {
                $sql = "UPDATE players SET firstname = :msg, lastname = '', nationality = '', rank = 0 WHERE id = :id";
                $result = $this->executeQuery($sql, ["msg" => "personne retirée", ":id" => $id]) ? "OK r" : "Échec de la suppression.";
            } catch (\PDOException $e) {
                $result = 'Échec de la suppression : ' . $e->getMessage();
            }
        }
        return $result;
    }
    // Fonction permettant de supprimer un court
    public function deleteCourtById (int $id): ?string {
        $sql = "DELETE * FROM courts WHERE id = :id LIMIT 1";
        try {
            $result = $this->executeQuery($sql, ["id" => $id]) ? "OK" : "Échec de la suppression.";
        } catch (\PDOException $e) {
            try {
                $sql = "UPDATE courts SET name = :msg, password = '000' WHERE id = :id";
                $result = $this->executeQuery($sql, ["msg" => "court supprimé", ":id" => $id]) ? "OK r" : "Échec de la suppression.";
            } catch (\PDOException $e) {
                $result = 'Échec de la suppression : ' . $e->getMessage();
            }
        }
        return $result;
    }
    // Fonction permettant de supprimer un arbitre
    public function deleteUmpireById (int $id): ?string {
        $sql = "DELETE * FROM umpires WHERE id = :id LIMIT 1";
        try {
            $result = $this->executeQuery($sql, ["id" => $id]) ? "OK" : "Échec de la suppression.";
        } catch (\PDOException $e) {
            try {
                $sql = "UPDATE umpires SET username = :msg, firstname = '', lastname = '' WHERE id = :id";
                $result = $this->executeQuery($sql, ["msg" => "arbitre retirée", ":id" => $id]) ? "OK r" : "Échec de la suppression.";
            } catch (\PDOException $e) {
                $result = 'Échec de la suppression : ' . $e->getMessage();
            }
        }
        return $result;
    }


}
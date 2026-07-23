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
        $sql = "SELECT id, id_to_display, title, type, size FROM draws WHERE tournament_id = :id";
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
        $sql = 'SELECT DISTINCT p.id_to_display, p.firstname, p.lastname, p.nationality, p.rank FROM players p
        JOIN matches m ON p.id = m.teamA_player1_id OR p.id = m.teamA_player2_id OR p.id = m.teamB_player1_id OR p.id = m.teamB_player2_id
        WHERE m.tournament_id = :id ORDER BY p.lastname, p.firstname ASC';
        $result["players"] = $this->fetchAll($sql, ['id' => $tournId]);

        return $result;
    }


}
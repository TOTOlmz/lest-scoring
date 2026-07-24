<?php
/* |||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    Classe TournamentModel
    Gère les opérations liées aux tournois
||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| */
namespace App\models;




class TournamentModel extends BaseModel {


    // Fonction permettant d'ajouter un tournoi
    public function setTournament(string $publicId, string $name, string $sTime, string $eTime, int $directorId): ?int {
        $sql = "INSERT INTO tournaments (id_to_display, name, start_time, end_time, director_id) 
        VALUES (:public_id, :name, :start_time, :end_time, :director_id)";
        $params = [
            "public_id" => $publicId,
            "name" => $name,
            "start_time" => $sTime,
            "end_time" => $eTime,
            "director_id" => $directorId
        ];
        return $this->lastInsert($sql, $params);
    }

    // Fonction permettant de récupérer les tournois d'une date précise
    public function getTournamentsByDate(string $time): ?array {
        $sql = "SELECT * FROM tournaments WHERE start_time <= :current_time AND end_time >= current_time";
        return $this->fetchAll($sql, ["current_time" => $time]);
    }

    // Fonction permettant de récupérer les tournois liés à un directeur
    public function getTournamentsByDirectorId(int $id): ?array {
        $sql = "SELECT * FROM tournaments WHERE director_id = :id";
        return $this->fetchAll($sql, ["id" => $id]);
    }
    // Fonction permettant de récupérer les id des tournois liés à un directeur
    public function getTournamentsIdsByDirectorId(int $id): ?array {
        $sql = "SELECT id FROM tournaments WHERE director_id = :id";
        return $this->fetchAll($sql, ["id" => $id]);
    }


    // Fonction permettant de récupérer toutes les données d'un tournoi précis (via plusieurs requêtes pour un tableau multidimensionnel)
    public function getAllTournamentDataById(int $tournamentId): ?array {

        // On récupère le tournoi
        $sql = "SELECT * FROM tournaments WHERE id = :t_id";
        $tournament = $this->fetchOne($sql, [":t_id" => $tournamentId]);
        // On récupère les courts liés au tournoi
        $sql = "SELECT * FROM courts WHERE tournament_id = :t_id";
        $tournament["courts"] = $this->fetchAll($sql, [":t_id" => $tournamentId]);

        foreach ($tournament["courts"] as $cIndex => $court) {
            $sql = "SELECT s.*,
                        pA1.firstname AS PA1_firstname, pA1.lastname AS PA1_lastname, pA1.nationality AS PA1_nationality,
                        pA2.firstname AS PA2_firstname, pA2.lastname AS PA2_lastname, pA2.nationality AS PA2_nationality,
                        pB1.firstname AS PB1_firstname, pB1.lastname AS PB1_lastname, pB1.nationality AS PB1_nationality,
                        pB2.firstname AS PB2_firstname, pB2.lastname AS PB2_lastname, pB2.nationality AS PB2_nationality
                    FROM scores s
                    LEFT JOIN players pA1 ON pA1.id = s.teamA_Player1_id
                    LEFT JOIN players pA2 ON pA2.id = s.teamA_Player2_id
                    LEFT JOIN players pB1 ON pB1.id = s.teamB_Player1_id
                    LEFT JOIN players pB2 ON pB2.id = s.teamB_Player2_id
                    WHERE s.court_id = :court_id";

            $matches = $this->fetchAll($sql, [":court_id" => $court["id"]]);

            // On reconstruit un sous-tableau "players" propre pour chaque match
            // Si l'id existe, on fzait un tableau, sinon, on met null
            foreach ($matches as &$match) {
                $match["players"] = [
                    "teamA_player1" => $match["teamA_Player1_id"] ? [
                        "id" => $match["teamA_Player1_id"],
                        "firstname" => $match["PA1_firstname"],
                        "lastname" => $match["PA1_lastname"],
                        "nationality" => $match["PA1_nationality"],
                    ] : null,
                    "teamA_player2" => $match["teamA_Player2_id"] ? [
                        "id" => $match["teamA_Player2_id"],
                        "firstname" => $match["PA2_firstname"],
                        "lastname" => $match["PA2_lastname"],
                        "nationality" => $match["PA2_nationality"],
                    ] : null,
                    "teamB_player1" => $match["teamB_Player1_id"] ? [
                        "id" => $match["teamB_Player1_id"],
                        "firstname" => $match["PB1_firstname"],
                        "lastname" => $match["PB1_lastname"],
                        "nationality" => $match["PB1_nationality"],
                    ] : null,
                    "teamB_player2" => $match["teamB_Player2_id"] ? [
                        "id" => $match["teamB_Player2_id"],
                        "firstname" => $match["PB2_firstname"],
                        "lastname" => $match["PB2_lastname"],
                        "nationality" => $match["PB2_nationality"],
                    ] : null,
                ];
            }
            unset($match);

            $tournament["courts"][$cIndex]["matches"] = $matches;
        }

        return $tournament;
    }

    // Fonction permettant de récupérer un tournoi via l'id du directeur
    public function getCurrentTournamentByDirectorId(int $dirId, $time): ?array {
        $sql = "SELECT * FROM tournaments WHERE director_id = :dirId AND start_time <= :current_time AND end_time >= current_time";
        return $this->fetchAll($sql, ["idrId" => $dirId, "current_time" => $time]);
    }

    // Fonction permettant de récupérer un identifiant de tournoi via l'id_to_display
    public function getTournamentIdByPublicId(string $publicId): ?int {
        $sql = "SELECT id FROM tournaments WHERE id_to_display = :publicId";
        return $this->fetchColumn($sql, ["publicId" => $publicId]);
    }
}

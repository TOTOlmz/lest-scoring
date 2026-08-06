<?php
/* |||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    Classe EditTournModel
    Gère les opérations liées à l'édition des différents éléments d'un tournoi
||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| */
namespace App\models;




class EditTournModel extends BaseModel {


    // Fonction permettant de modifier les valeurs d'un tournoi
    public function editTourn (int $id, string $name, string $club, string $city, $start, $end): bool {

        $sql = "UPDATE tournaments SET name = :name, club = :club, city = :city, start_time = :startT, end_time = :endT
        WHERE id = :id";
        $params = [
            "id" => $id,
            "name" => $name,
            "club" => $club,
            "city" => $city,
            "startT" => $start,
            "endT" => $end
        ];
        $result = $this->executeQuery($sql, $params);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    // Fonction permettant d'ajouter un tableau
    public function addDraw(string $publicId, string $title, int $size, int $type, string $format, int $tournamentId): ?int {

        $sql = "INSERT INTO draws (id_to_display, title, type, format, size, tournament_id) 
        VALUES (:pId, :title, :type, :format, :size, :tournId)";
        $params = [
            "pId" => $publicId,
            "title" => $title,
            "type" => $type,
            "format" => $format,
            "size" => $size,
            "tournId" => $tournamentId
        ];

        return $this->lastInsert($sql, $params);
    }

    // Fonction permettant d'ajouter un match du tableau
    public function addDrawMatch(string $publicId, int $tournamentId, int $scoringType, int $drawId, string $drawPos, ?int $TAP1, ?int $TAP2, ?int $TBP1, ?int $TBP2): ?int {

        $sql = "INSERT INTO matches (id_to_display, tournament_id, scoring_type, draw_id, draw_position, teamA_Player1_id, teamA_Player2_id, teamB_Player1_id, teamB_Player2_id, status) 
        VALUES (:pId, :tournId, :score, :drawId, :drawPos, :TAP1, :TAP2, :TBP1, :TBP2, :status)";
        $params = [
            "pId" => $publicId,
            "tournId" => $tournamentId,
            "score" => $scoringType,
            "drawId" => $drawId,
            "drawPos" => $drawPos,
            "TAP1" => $TAP1,
            "TAP2" => $TAP2,
            "TBP1" => $TBP1,
            "TBP2" => $TBP2,
            "status" => "PLANNED"
        ];
        return $this->lastInsert($sql, $params);
    }

    // Fonction permettant d'ajouter un tableau
    public function addCourt(string $publicId, string $name, string $password, int $tournamentId): ?int {

        $sql = "INSERT INTO courts (id_to_display, name, password, tournament_id) 
        VALUES (:pId, :name, :password, :tournId)";
        $params = [
            "pId" => $publicId,
            "name" => $name,
            "password" => $password,
            "tournId" => $tournamentId
        ];

        return $this->lastInsert($sql, $params);
    }

    // Fonction permettant d'ajouter un tableau
    public function addPlayer(string $publicId, string $fname, string $lname, string $nat, string $rank, int $tournamentId): ?int {

        $sql = "INSERT INTO players (id_to_display, firstname, lastname, nationality, rank) 
        VALUES (:pId, :fname, :lname, :nat, :rank, :tournId)";
        $params = [
            "pId" => $publicId,
            "fname" => $fname,
            "lname" => $lname,
            "nat" => $nat,
            "rank" => $rank,
            "tournId" => $tournamentId
        ];

        return $this->lastInsert($sql, $params);
    }

    // Fonction permettant d'ajouter un tableau
    public function addMatch(string $publicId, int $tournamentId, int $scoringType, int $TAP1, int $TAP2, int $TBP1, int $TBP2): ?int {

        $sql = "INSERT INTO matches (id_to_display, tournament_id, scoring_type, teamA_Player1_id, teamA_Player2_id, teamB_Player1_id, teamB_Player2_id, status) 
        VALUES (:pId, :tournId, :score, :TAP1, :TAP2, :TBP1, :TBP2, :status)";
        $params = [
            "pId" => $publicId,
            "tournId" => $tournamentId,
            "score" => $scoringType,
            "TAP1" => $TAP1,
            "TAP2" => $TAP2,
            "TBP1" => $TBP1,
            "TBP2" => $TBP2,
            "status" => "PLANNED"
        ];
        return $this->lastInsert($sql, $params);
    }

    // Fonction permettant d'ajouter un tableau
    public function addUmpire(string $publicId, string $uname, string $fname, string $lname, int $tournamentId): ?int {

        $sql = "INSERT INTO umpires (id_to_display, username, firstname, lastname, tournament_id) 
        VALUES (:pId, :uname, :fname, :lname, :tournId)";
        $params = [
            "pId" => $publicId,
            "uname" => $uname,
            "fname" => $fname,
            "lname" => $lname,
            "tournId" => $tournamentId
        ];

        return $this->lastInsert($sql, $params);
    }


    
}

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
    
}

<?php
// 
class SeanceModel extends AbstractModel
{
    protected $database;

    public function FindSeancesByDate($date)
    {
        $sql =
        "SELECT seance.`id`,`id_salle`,`date_seance`,DATE_FORMAT(`debut_seance`, '%H:%i') AS debut_seance,title,duration,status  FROM `seance`
        INNER JOIN film
        ON seance.id_film = film.id
        WHERE `date_seance` = ?  
        ORDER BY `seance`.`id_film`  ASC, `seance`.`debut_seance` ASC";
        return $this->database->query($sql,[$date]);
    }

    public function FindSeancesByFilmId($film_Id)
    {
        $sql =
        "SELECT seance.`id`,`id_salle`, DATE_FORMAT(`date_seance`, '%d-%m-%Y') AS date_seance, DATE_FORMAT(`debut_seance`, '%H:%i') AS debut_seance FROM `seance` INNER JOIN film ON seance.id_film = film.id WHERE film.id = ? ORDER BY `seance`.`date_seance` ASC ,`seance`.`debut_seance` ASC";
        return $this->database->query($sql,[$film_Id]);
    }

    public function FindHourSeanceByDay($currentTime,$currentDate,$seances, $dateSeance)
    {
        $seanceHours = [];

        //pour chaque séance
        foreach($seances as $seance)
        {
            //var_dump($seance);
            /**
            * si la date de la séance en bdd est = à la date récupéré 
            * ET date de la séance en bdd est > a la date du jour
            * OU si la date de la seance en bdd est = a la date recupéré 
            * ET date de date de la seance en bdd est = a la date du jour 
            * ET heure de debut de la bdd est >= a l'heure actuelle alors 
            * ON PUSH
            **/
             
            if($seance['date_seance'] == $dateSeance && $seance['date_seance'] > $currentDate || $seance['date_seance'] == $dateSeance && $seance['date_seance'] = $currentDate && $seance['debut_seance'] >= $currentTime)
            {
                array_push($seanceHours, $seance['debut_seance']); 
                
            }


        }
        return $seanceHours;
        
    }

    public function FindSeanceById($id)
    {
        $sql=
        "SELECT seance.`id`,`id_film`,`date_seance`,`id_salle`,`debut_seance`,title FROM `seance` INNER JOIN film ON seance.id_film = film.id WHERE seance.id = ?";

        return $seance = $this->database->queryOne($sql,[$id]);
    }

    public function SeanceIsExist($date_seance,$id_salle)
    {
        $sql=
        "SELECT seance.`id`,`id_film`,`id_salle`,`date_seance`,DATE_FORMAT(`debut_seance`, '%H:%i') AS debut_seance, DATE_FORMAT(`duration`, '%H:%i') AS duration, title, SEC_TO_TIME(TIME_TO_SEC(`debut_seance`)+TIME_TO_SEC(`duration`)) AS fin_seance  FROM `seance` INNER JOIN film ON seance.id_film = film.id WHERE `date_seance`='$date_seance' AND `id_salle`= $id_salle ORDER BY `fin_seance` ASC";
        
        return $this->database->query($sql);
    }


    public function UpdateSeance($id,$id_film,$id_salle,$date_seance,$debut_seance)
    {
        $sql=
        "UPDATE `seance` 
        SET `id_film` = $id_film,
            `id_salle` = $id_salle, 
            `date_seance` = '$date_seance',
            `debut_seance` = '$debut_seance'
          WHERE `seance`.`id` = $id";

        $this->database->executeSql($sql,[$id,$id_film,$id_salle, $date_seance, $debut_seance]);
    }

    public function SaveSeanceInBdd($id_film,$id_salle,$date_seance,$debut_seance)
    {
        //var_dump($id_film,$id_salle,$date_seance,$debut_seance);
        $sql =
        "INSERT INTO `seance` (`id`, `id_film`, `id_salle`, `date_seance`, `debut_seance`) VALUES (NULL, '$id_film', '$id_salle', '$date_seance', '$debut_seance')";

        $this->database->executeSql($sql,[$id_film,$id_salle, $date_seance, $debut_seance]);
    }

    public function UpdateStatus($id_film)
    {
        $sql=
        "UPDATE `film` 
        SET `status` = 'en ligne'
        WHERE `id` = $id_film";

        return $this->database->executeSql($sql,[$id_film]);
    }

    public function deleteSeance($id)
    {
        $sql = 
        "DELETE FROM `Seance`
        WHERE Seance.`Id` = $id";
        $this->database->executeSql($sql); 
        
    }

}
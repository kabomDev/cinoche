<?php

class FilmModel extends AbstractModel
{
    protected $database;

    public function FindAllFilms()
    {
        $sql =
        "SELECT `id`, title,`picture`,`status`,date_sortie FROM `film` ORDER BY `film`.`id`";
        return $this->database->query($sql);
    }

    public function FindFilmById($film_Id)
    {
        //paramètre la zone et l'heure
        date_default_timezone_set('Europe/Paris');
        setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);

        $sql =
        "SELECT `id`, title,`content`,`picture`,`video`,DATE_FORMAT(`duration`,'%Hh%i') AS duration,`status`,DATE_FORMAT(`date_sortie`,'%d %M %Y') AS date_sortie FROM `film` WHERE film.id = ?";
        return $this->database->queryOne($sql, [$film_Id]);
    }

    
    public function FindFilmByTitle($films)
    {
        $sql =
        "SELECT `id`, title,`content`,`picture`,`video`,DATE_FORMAT(`duration`,'%Hh%i') AS duration,`status`,DATE_FORMAT(`date_sortie`,'%d %M %Y') AS date_sortie FROM `film` WHERE title LIKE '%$films%'";
        return $this->database->query($sql);
    }

    public function FindFilmDurationById($id)
    {
        $sql =
        "SELECT DATE_FORMAT(`duration`,'%H:%i') AS duration FROM `film` WHERE id = ?";
        return $this->database->queryOne($sql,[$id]);
    }

    public function FindAllFilmsByStatus()
    {
        $sql =
        "SELECT `id`, title,DATE_FORMAT(`duration`,'%Hh%i') AS duration,`status`,DATE_FORMAT(`date_sortie`,'%d-%m-%Y') AS date_sortie FROM `film` WHERE film.status = 'prochainement' ORDER BY `date_sortie` ASC";
        return $this->database->query($sql);
    }
    
    public function SaveInBdd($title,$content,$picture,$video,$duration,$statut,$date_sortie)
    {
        //var_dump($title,$content,$picture,$video,$duration,$statut,$date_sortie);
        
        $sql =
        "INSERT INTO `film` (`id`, `title`, `content`, `picture`, `video`,`duration`,`status`,`date_sortie`) VALUES (NULL, '$title', '$content', '$picture', '$video','$duration','$statut', '$date_sortie')";

        $this->database->executeSql($sql,[$title,$content,$picture,$video,$duration,$statut,$date_sortie]);
        
    }

}
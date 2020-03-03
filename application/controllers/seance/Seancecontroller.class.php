<?php

class SeanceController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
        //change le type en entier pour recuperer l'id du film
        $filmId = intval($queryFields['film_id']);

        //film
        $filmModel = new FilmModel;
        $film = $filmModel->FindFilmById($filmId);

        $seanceModel = new SeanceModel;
        $seances = $seanceModel->FindSeancesByFilmId($filmId);
        
        //date et heure actuelle
        date_default_timezone_set('Europe/Paris');
        setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
        $date = date('d-m-Y');
        $currentTime = date("H:i");

        //retourne les variables pour pouvoir les utiliser dans la view
        return ['film'=> $film,
                'seances'=>$seances,
                'currentTime' => $currentTime,
                'date' => $date,
                ];

    }

    public function httpPostMethod(Http $http, array $formFields)
    {
        
        //date et heure actuelle
        date_default_timezone_set('Europe/Paris');
        setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
        $currentDate = date('d-m-Y');
        $currentTime = date("H:i");

        //si la cle date existe dans le tableau $formFields
        if(array_key_exists('date',$formFields))
        {
            $dateSeance = $formFields['date'];
            $filmId = $formFields['id'];

        }

        $seanceModel = new SeanceModel;
        $seances = $seanceModel->FindSeancesByFilmId($filmId);
        $seancesHours = $seanceModel->FindHourSeanceByDay($currentTime,$currentDate, $seances,$dateSeance); 

        //on renvoie les seances vers le coté client
        $http->sendJsonResponse($seancesHours); 
    }

}
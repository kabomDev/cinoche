<?php

class FilmController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
		
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
    	//var_dump($formFields);
        //exit();
    	
        if(array_key_exists("film", $formFields))
        {
            $filmTitle = ucwords($formFields["film"]);
        }

        $filmModel = new FilmModel;
        $film = $filmModel->FindFilmByTitle($filmTitle);
        
        $http->sendJsonResponse($film);

    }
}
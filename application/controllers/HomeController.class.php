<?php

class HomeController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
    	$filmModel = new FilmModel;
		$films = $filmModel->FindAllFilms();

		//pour afficher les alerts
		$flashBag = new FlashBag;

		//on retourne le resultat dans un tableau associatif
		return ['films'=> $films,
				'flashBag'=> $flashBag];
		
    }

	public function httpPostMethod(Http $http, array $formFields)
    {
		//var_dump($formFields);
		//exit();
		if(array_key_exists("film", $formFields))
        {
			//on capitalise chaque mot
			$films = ucwords($formFields["film"]);
			//var_dump($films);
			//exit();
			$filmModel = new FilmModel;
			$searches = $filmModel->FindFilmByTitle($films);
			
			if(!empty($searches))
			{
				//var_dump($searches);
				$http->sendJsonResponse($searches);
			}
			else
			{
				$http->sendJsonResponse(null);
			}
		}
		$http->redirectTo('/');
    }
}
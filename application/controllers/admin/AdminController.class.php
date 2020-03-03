<?php

class AdminController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
        $userSession = new UserSession;
        if($userSession->isAuthenticated()== false)
        {
            $http->redirectTo('/admin/login');
        }
        
        $filmModel = new FilmModel;
        $films = $filmModel->FindAllFilmsByStatus();
        
        $date = date('Y-m-d');

        $flashBag = new FlashBag;
        return ['flashBag'=> $flashBag,
                'date'=>$date,
                'films'=>$films];
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
        //on recupere les seances de la journée
        $currentDate = $formFields['date'];
        $seanceModel = new SeanceModel;
        $seances = $seanceModel->FindSeancesByDate($currentDate);
        
        //on renvoie la reponse coté client
        $http->sendJsonResponse($seances);
        
    }
}
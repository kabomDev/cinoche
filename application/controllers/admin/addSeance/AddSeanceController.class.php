<?php

class AddSeanceController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
        $filmModel = new FilmModel;
        $films = $filmModel->FindAllFilms();

        $salleModel = new SalleModel;
        $salles = $salleModel->FindAllSalles();

        $currentDate = date('Y-m-d');
        $flashBag = new FlashBag;

        return['films'=>$films,
                'salles'=>$salles,
                'currentDate'=>$currentDate,
                'flashBag'=> $flashBag
            ];
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
        
        if(array_key_exists('date', $formFields))
        {
            $startDay = $formFields['date'] . " 10:00";    
            $endDay = $formFields['date'] . " 23:00";
    
            $availableTime = [];
    
            $filmModel = new FilmModel;
            $duration = $filmModel->FindFilmDurationById($formFields['film_id']);
            $duration = $duration['duration'];
    
            $durationSeconde = $this->calculInSecond($duration);
    
            $seanceModel = new SeanceModel;
            $seances = $seanceModel->SeanceIsExist($formFields['date'],$formFields['salle']);
            
            //si pas de seances en bdd, on dit que la 1ere seance est a 10h
            if(empty($seances))
            {   
                $startSeance = $startDay;
                /*et on calcule tous les horaires possible de la journée par rapport au film et 
                * sa durée + les entractes puis on push dans notre tableau
                */
                do{
                    $beginningInSecond = $this->calculationStartOfSeance($startSeance);
                    $beginningOfOurSeance = date('Y-m-d H:i', $beginningInSecond);
                    array_push($availableTime,$beginningOfOurSeance);
                    $endOfSeanceInSecond = $beginningInSecond + $durationSeconde;
                    
                    $endOfOurSeance = date('Y-m-d H:i', $endOfSeanceInSecond);
                    $startSeance = $endOfOurSeance;
                    
                }while($endOfSeanceInSecond < strtotime($endDay));
                
                //on renvois le tableau coté client
                $http->sendJsonResponse($availableTime); 
            }
            else //sinon
            {
                $startSeance = $startDay;
                
                //pour chaque seance récupéré en bdd on verifie l'heure de la seance
                foreach($seances as $seance)
                {
                    $seanceInBdd = $seance['date_seance']. " " . $seance['debut_seance'];
                    $seanceInBddInSecond = strtotime($seanceInBdd);
    
                    //si la seance en bdd n'est pas a 10h on calcule les disponibilités entre l'heure de depart et celle-ci
                    if($seanceInBdd > $startSeance)
                    {
                        $beginningOfOurSeance = $startSeance;
    
                        do
                        {
                            $beginningInSecond = $this->calculationStartOfSeance($beginningOfOurSeance);
    
                            $beginningOfOurSeance = date('Y-m-d H:i', $beginningInSecond);
                            
                            $endOfSeanceInSecond = $beginningInSecond + $durationSeconde;
                            $endOfOurSeance = date('Y-m-d H:i', $endOfSeanceInSecond);
                            
                            if($endOfSeanceInSecond <= $seanceInBddInSecond)
                            {   //si notre horaire potentielle est possible alors on push dans notre tableau
                                array_push($availableTime,$beginningOfOurSeance);
                                $beginningOfOurSeance = $endOfOurSeance;
                            }
                        }
                        while($endOfSeanceInSecond <= $seanceInBddInSecond);
                        $beginningOfOurSeance = $endOfOurSeance;
                    }
    
                    $beginningOfOurSeance = $seanceInBdd;
                    $beginningInSecond = strtotime($beginningOfOurSeance);
                    $endOfSeanceInSecond = $beginningInSecond + $durationSeconde;
                    $endOfOurSeance = date('Y-m-d H:i', $endOfSeanceInSecond);
                    
                    $startSeance = $seance['date_seance']. " " .$seance['fin_seance'];
                }
                //tant que la seance n'est pas arrivé en fin de journée on continue de push dans notre tableau
                while($endOfSeanceInSecond < strtotime($endDay))
                {
                    $beginningInSecond = $this->calculationStartOfSeance($startSeance);
                    $beginningOfOurSeance = date('Y-m-d H:i', $beginningInSecond);
                    array_push($availableTime,$beginningOfOurSeance);
                    $endOfSeanceInSecond = $beginningInSecond + $durationSeconde;
                    
                    $endOfOurSeance = date('Y-m-d H:i', $endOfSeanceInSecond);
                    $startSeance = $endOfOurSeance;
                }

                //on renvois le tableau coté client
                $http->sendJsonResponse($availableTime); 
            }
        }
    }

    public function calculInSecond($time)
    {
        //on convertit la fin de seance en seconde
        $timeInSecond = strtotime($time) - strtotime(date("Y-m-d"));

        return $timeInSecond;
        
    }

    public function calculationStartOfSeance($s)
    {
        //on transforme la seance en seconde
        $seconde = strtotime($s);

        $entractInSecond = 900;

        //on rajoute l'entract ce qui nous donne le debut potentiel de notre seance
        $seconde += $entractInSecond;

        return $seconde;
    }


}
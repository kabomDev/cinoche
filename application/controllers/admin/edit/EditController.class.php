<?php

class EditController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
        $id = $queryFields['seance_id'];
        $seanceModel = new SeanceModel;
        $seance = $seanceModel->FindSeanceById($id);

        $filmModel = new FilmModel;
        $films = $filmModel->FindAllFilms();

        $salleModel = new SalleModel;
        $salles = $salleModel->FindAllSalles();

        $date = date('Y-m-d');

        return['seance'=>$seance,
                'films'=>$films,
                'salles'=>$salles,
                'date'=>$date
            ];
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
        $id = $formFields['id'];
        $seanceModel = new SeanceModel;
        $seance = $seanceModel->FindSeanceById($id);

        $filmModel = new FilmModel;
        $films = $filmModel->FindAllFilms();

        $salleModel = new SalleModel;
        $salles = $salleModel->FindAllSalles();

        $date = date('Y-m-d');

        $seanceModel = new SeanceModel;
        //on verifie si la seance existe
        $seances = $seanceModel->SeanceIsExist($formFields["date_seance"], $formFields["id_salle"]);

        $filmModel = new FilmModel;
        //on recupere les infos necessaires
        $duration = $filmModel->FindFilmDurationById($formFields['id_film']);
        $duration = $duration['duration'];  
        $debutSeance = $formFields['debut_seance']; 
        $debutSeance = strtotime($debutSeance);  
        $duration = strtotime($duration);
        $finSeance = $debutSeance + $duration - strtotime(date("Y-m-d"));
        
        $error = [];
        /*
        * on va verifié qu'a la saisie de l'horaire, qu'elle est disponible
        */
        foreach($seances as $seance)
        {
            $debutInBDD = strtotime($seance['debut_seance']);
            $finInBDD = strtotime($seance['fin_seance']);

            if($debutSeance >= $debutInBDD && $debutSeance <= $finInBDD || $finSeance >= $debutInBDD && $finSeance <= $finInBDD)
            {
                $error['debut_seance'] = "Veuillez séléctionner une autre heure";
            } 
        }
        //si pas d'erreur on update dans la bdd et confirme a l'utilisateur
        if(empty($error))
        {   
            $seanceAdd = new SeanceModel;
            $seanceAdd->UpdateSeance($formFields['id'], $formFields['id_film'],$formFields['id_salle'], $formFields['date_seance'], $formFields['debut_seance']);

            $flashBag = new FlashBag;
            $flashBag->add('La séance a bien été modifié');

            $http->redirectTo('/admin');

        }
        
        return['seance'=>$seance,
                'films'=>$films,
                'salles'=>$salles,
                'date'=>$date,
                'error'=>$error
            ];
    }
}
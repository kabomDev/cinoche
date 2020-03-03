<?php

class CreateController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
        
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
        $seanceModel = new SeanceModel;
        //on recupere dans des variables les differentes infos
        $seances = $formFields["debut_seance"];
        $id_film = $formFields['id_film'];
        $id_salle = $formFields['id_salle'];
        $date_seance = $formFields['date_seance'];

        //pour chaque debut de seances on crée une seance
        foreach($seances as $seance)
        {
            $seanceModel->UpdateStatus($id_film);
            $seanceModel->SaveSeanceInBdd($id_film, $id_salle, $date_seance, $seance);
        }

        //on confirme la creation à l'utilisateur
        $flashBag = new FlashBag;
        $flashBag->add("la ou les séance(s) ont bien été crée");
    }
}
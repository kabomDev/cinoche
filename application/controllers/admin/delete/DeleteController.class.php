<?php

class DeleteController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
        $seanceModel = new SeanceModel;
        //on recupere l'id de la seance et on la supprime
        $seanceModel->deleteSeance($queryFields['seance_id']);
        
        //on confirme la suppression a l'utilisateur
        $flashBag = new FlashBag;
        $flashBag->add('La séance a bien été supprimé');
        $http->redirectTo('/admin');
        
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
        
    }
}
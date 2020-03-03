<?php

class LoginController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
        
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
        //on verifie que les champs sont bien remplis
        $error = [];

        if(empty($formFields['userName']))
        {
            $error['userName'] = "Veuillez entrez votre nom d'administrateur";
        }
        if(empty($formFields['password']))
        {
            $error['password'] = "Veuillez entrer votre mot de passe";
        }

        if (empty($error)) //si il n'y a pas d'erreur
        {
            try{
                //on instancie la class AdminModel
                $userConnexion = new AdminModel;
                //on fait la requete via la class AdminModel
                $user = $userConnexion->checkCredentials($formFields['userName'],$formFields['password']);
                
                //on crée une session
                $userSession = new UserSession;
                $userSession->signIn($user['userName'],$user['password']);
                //on confirme à l'utilisateur qu'il est bien connecté
                $flashBag = new FlashBag;
                $flashBag->add('Vous êtes bien connecté');
                //on redirige vers l'administration
                $http->redirectTo('/admin');
                
            }
            //si l'email n'existe pas on execute le catch 
            catch(Exception $e)
            {
                //on renvois l'erreur qui vient de adminModel->checkCredentials
                $error['userName'] = $e->getMessage();//methode natif de php
            }
        }
        return ['error'=> $error];
    }  
}
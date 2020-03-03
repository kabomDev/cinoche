<?php
class LogoutController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
        $userLogout = new UserSession;
        $userLogout->logout();

        $flashBag = new FlashBag;
        $flashBag->add('Vous êtes bien déconnecté');

        $http->redirectTo('/');
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
    	
	}
}
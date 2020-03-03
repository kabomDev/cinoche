<?php

class UserSessionFilter implements InterceptingFilter 
{
    public function run(Http $http, array $queryFields, array $formFields)
    { // fontionne comme un controleur mais partout
        return ['userSession'=>new UserSession];
    }
}
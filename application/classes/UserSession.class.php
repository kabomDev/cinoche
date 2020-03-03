<?php
class UserSession
{
    public function __construct()
    {
        if(session_status()== PHP_SESSION_NONE)
        {
            session_start();
        }
    }

    public function signIn($username,$password)
    {
        $_SESSION['user']= [
            'userName'=>$username,
            'password'=>$password
        ];
    }

    public function logout()
    {
        $_SESSION['user']= [];
        session_destroy();
    }

    public function isAuthenticated()
    {   //renvois true si la clé user existe et si le tableau session et pleine
        return (array_key_exists('user', $_SESSION) && !empty($_SESSION['user']));
    }
}

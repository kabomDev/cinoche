<?php

class AdminModel extends AbstractModel
{
    protected $database;

    public function FindAdminAccess($userName)
    {
        $sql =
        "SELECT * FROM `admin` WHERE `userName` = ?";
        
        return $this->database->queryOne($sql, [$userName]);

    }

    public function checkCredentials($userName,$password)
   {
        $user = $this->FindAdminAccess($userName);
        $hashPassword = $user['password'];
        
        if(!$user)
        {
            throw new Exception("Cet utilisateur n'existe pas");
        }
        /*
        if(password_verify($password,$hashPassword))
        {
            return $user;
        }
        else
        {
            throw new Exception("Le mot de passe n'est pas correct");
        }
        */
        if($password !== $hashPassword)
        {
            throw new Exception("Le mot de passe n'est pas correct");
        }
        return $user;
        
   }
    
}
<?php

class BackgroundController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
        $max = 512000;
        return['max_size'=>$max];
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
        $error = [];
        /*
        * si la methode est bien un post et que l'on a bien un tableau image
        * et que celui-ci ne comporte pas d'erreur:
        */
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            if(isset($_FILES['image']) && $_FILES['image']['error'] == 0)
            {
                //on recupere le detail lié à l'image
                $path = pathinfo($_FILES["image"]["name"]);
                //on recupere le chemin de notre dossier image
                $dossier = WWW_PATH ."/images/";

                //on vérifie l'extension du fichier
                $extension = isset($path['extension']) ? strtolower($path['extension']): null;
                if(!in_array($extension, array('jpg', 'jpeg', 'png')))
                {
                    $error["format"] ="Veuillez selectionner un format de fichier valide !";
                }
                //on vérifie la taille de l'image
                $maxSize = 512 * 1024;
                if($_FILES['image']['size'] > $maxSize)
                {
                    $error["size"] = "Veuillez selectionner un fichier moins gros (512ko) !";
                }

                //si pas d'erreur
                if(empty($error))
                {
                    move_uploaded_file($_FILES["image"]["tmp_name"], $dossier . $_FILES["image"]["name"]);
                    $this->displayBackground($_FILES["image"]["name"]);

                    $flashBag = new FlashBag;
                    $flashBag->add('votre arrière plan a bien été modifié');

                    $http->redirectTo('/admin');
                }
                
                return['error'=>$error];
            }
        }
        
        
        
    }

    public function displayBackground($bg)
    {
        
        $bg = $bg.'");}}';

        $file = fopen(WWW_PATH.'/css/bg.css','rb');
        $line = fread($file, filesize(WWW_PATH.'/css/bg.css'));

        $replaceLine = substr($line,0,90);
        $newBG = $replaceLine.$bg;

        fclose($file);

        $file = fopen(WWW_PATH.'/css/bg.css','w');
        fwrite($file,$newBG);
        fclose($file);

    }
}
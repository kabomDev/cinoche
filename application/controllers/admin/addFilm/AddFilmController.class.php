<?php

class AddFilmController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
        $flashBag = new FlashBag;
        return ['flashBag'=> $flashBag];
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
        $filmModel = new FilmModel;
        $statut = 'prochainement';
        
        if(!empty($formFields))
        {
            //on verifie que le film n'est pas déja dans la bdd avant d'enregistrer
            $film = $filmModel->FindFilmByTitle($formFields['title']);
            if(!empty($film))
            {
                $flashBag = new FlashBag;
                $flashBag->add("Le film est déja en base de données ");

                $http->redirectTo("/admin/addFilm");
            }
            else 
            {   //on retire les apostrophes qui pourrait gener lors de l'enregistrement en bdd
                $title = str_replace("'", " ", $formFields['title']);
                $content = str_replace("'", " ", $formFields['content']);
            
                //on verifie si on a bien une image
                if(array_key_exists('picture', $formFields))
                {   /**
                    * on recupere le lien original de l'image pour pouvoir l'enregistrer dans notre dossier image
                    **/
                    $url = "https://image.tmdb.org/t/p/original".$formFields["picture"];

                    $path = pathinfo($url);

                    $extension = isset($path['extension']) ? strtolower($path['extension']): null;

                    if(in_array($extension, array('jpg','jpeg', 'png')))
                    {
                        $dossier = WWW_PATH ."/images/films/";
                        
                        $newName = str_replace(':', "", $formFields['title']);
                        $newName = str_replace(' ', '_', $newName);
                        $newName = $newName. ".".$extension;
                       
                        $currentFile = file_get_contents($url);
                        file_put_contents($dossier.$newName, $currentFile);
                    }
                }
                //on remet la durée du film dans le bon format
                var_dump($formFields['runtime']);
                $debut_seance = date("H:i:s", strtotime($formFields['runtime']));
                //on enregiste dans la bdd
                $filmModel->SaveInBdd($title,$content, $newName,$formFields['video'],$debut_seance,$statut,$formFields['date_sortie']);
            }
            //on confirme a l'utilisateur que le film a bien été enregistré
            $flashBag = new FlashBag;
            $flashBag->add("Le film ". $title. " à bien été ajouté");
            //on redirige
            $http->redirectTo("/admin");
        }
    }
}
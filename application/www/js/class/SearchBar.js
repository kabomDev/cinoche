'use strict';

class SearchBar
{
    constructor()
    {
        this.show = document.querySelector('#show');
        this.actu = document.querySelector('#actu');
        this.prev = document.querySelector('#prev');
        this.seanceByIdFilm = document.querySelector('#search-form');

    }
    init()
    {   
        if(this.seanceByIdFilm)
        {
            this.seanceByIdFilm.addEventListener('submit', this.searchMovie.bind(this));
        }

    }

    searchMovie(event)
        {
            event.preventDefault();
            const film = document.querySelector('#search').value;

            //on indique le homecontroller vers lequel on va faire la requete
            const url = getRequestUrl()+ '/';

            //on sauve les données dans data
            const data = {'film': film};
            //console.log(data);
            $.post(url,data, this.onAjaxDeJSON.bind(this));
        }

    onAjaxDeJSON(films)
    { 
        if(films !== 'null')
        {
            //on dejson pour avoir un tableau
            const tab = JSON.parse(films);
            this.DisplayFilms(tab);
        }
        
    }

    DisplayFilms(films)
    {
        //on vide la page a chaque nouvelle recherche
        this.refresh();
        //on crée la page a afficher
        const ul = document.createElement('ul');

        films.forEach(element => {
                
                //on crée les elements
                var li = document.createElement('li');
                var a = document.createElement('a');
                var image = document.createElement('img');
                this.h2 = document.createElement('h2');
                var h3 = document.createElement('h3');
                var texth2 = document.createTextNode("Liste des films");
                var texth3 = document.createTextNode(element.title);
                //on ajoute les attributs
                a.setAttribute('href', getRequestUrl()+'/seance?film_id='+ element.id);
                image.setAttribute('src', getWwwUrl()+'/images/films/'+ element.picture);
                //on lie tous les elements 
                a.appendChild(image)
                this.h2.appendChild(texth2);
                h3.appendChild(texth3);
                li.appendChild(a);
                li.appendChild(h3);
                ul.appendChild(li);

        });
        //on relie a la div show
        this.actu.appendChild(this.h2);
        this.actu.appendChild(ul); 

    }

    refresh()
    {
        this.actu.innerHTML = "";
        this.prev.classList.add('hidden');
    }


}
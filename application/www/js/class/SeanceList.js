'use strict';

class SeanceList
{
    constructor()
    {
        this.seanceDay = document.querySelector('.seance-description');
        this.div = document.querySelector('#seances');
    }

    init()
    {
        if(this.seanceDay)
        {
            //on ecoute les boutons qui sont dans la div .seance-description
            this.seanceDay.addEventListener('click', this.onChangeSeanceDay.bind(this));
        }
    }

    onChangeSeanceDay(event)
    {
        //on recupere la cible lié au click
        const target = event.target;

        //si la cible contient la classe seance day 
        if(target.classList.contains('seanceDay'))
        {
            //on recupere l'attribut date
            let date = target.dataset.date;
            //on recupere l'attribut id du film
            let id = target.dataset.id;
            //on indique le chemin du controller qu'on va utiliser
            const url = getRequestUrl()+ '/seance';
            //on met dans un tableau les infos utiles
            const data = {'date': date, 'id': id};
            //on fait la requete ajax
            $.post(url,data, this.onAjaxDisplaySeance.bind(this));
        }
    }

    onAjaxDisplaySeance(seances)
    {
        //on transforme seances qui est une string en un tableau
        var seancesHour = JSON.parse(seances);
    
        this.refreshSeances();
        
        //si le tableau seanceHour est n'est pas vide
        if(seancesHour !== 0)
        {
            var ul = document.createElement('ul');

            seancesHour.forEach(function(seance){
                var li = document.createElement('li');
                var text = document.createTextNode(seance);
                li.appendChild(text);
                ul.appendChild(li);
            });
            this.div.appendChild(ul);
        }
        
    }

    refreshSeances()
    {
        this.div.innerHTML = "";
    }


}
'use strict';

class SelectFilm
{
    constructor()
    {
        this.selectDate = document.querySelector('input[name="date"]');
    }

    init()
    {
        if(this.selectDate)
        {
            this.selectDate.addEventListener('change',this.onChangeDate.bind(this));
    
            const e = new Event('change');//crée un event
            this.selectDate.dispatchEvent(e);//déclenche l'event
        }

    }

    onChangeDate(event)
    {
        if(event.target.classList.contains('select-date'))
        {
            const date = event.target.value;
            const url = getRequestUrl()+ '/admin'
            const data = {'date':date};
            $.post(url,data, this.onDisplay.bind(this));
        }
    }

    onDisplay(seances)
    {
        const allSeances = JSON.parse(seances);

        if(allSeances.length == 0)
        {
            this.tbody = document.querySelector('.tbody');
            this.refreshSeances();
            this.trNoSeance = document.createElement('tr');
            this.tdNoSeance = document.createElement('td');
            this.tdNoSeance.setAttribute('colspan', "6");
            this.tdNoSeance.innerHTML = "pas de séances";
            this.trNoSeance.appendChild(this.tdNoSeance);
            this.tbody.appendChild(this.trNoSeance);
        }
        else
        {
            this.tbody = document.querySelector('.tbody');
            this.refreshSeances();
            
            allSeances.forEach(element => {
                
               //affichage du titre
               this.tr = document.createElement('tr');
               this.titleFilm = document.createElement('td');
               this.titleFilm.innerHTML = element.title;
               this.tr.appendChild(this.titleFilm); 
               //affichage de l'heure de debut
               this.salle = document.createElement('td');
               this.salle.innerHTML = element.id_salle;
               this.tr.appendChild(this.salle);
               //affichage de l'heure de debut
               this.startTime = document.createElement('td');
               this.startTime.innerHTML = element.debut_seance;
               this.tr.appendChild(this.startTime);
               //affichage de la durée du film
               this.duration = document.createElement('td');
               this.duration.innerHTML = element.duration;
               this.tr.appendChild(this.duration);
               
               //affichage de la ligne modifier
               this.modificationBox = document.createElement('td');
               this.modificationLink = document.createElement('a');
               this.modificationLink.setAttribute('href', getRequestUrl()+ "/admin/edit?seance_id="+ element.id);
               this.i = document.createElement('i');
               this.i.setAttribute('class', 'fas fa-edit');
               this.modificationLink.appendChild(this.i);
               this.tr.appendChild(this.modificationBox);
               //affichage de la ligne supprimer
               this.deleteBox = document.createElement('td');
               this.deleteLink = document.createElement('a');
               this.deleteLink.setAttribute('href', getRequestUrl()+ "/admin/delete?seance_id="+ element.id);
               this.deleteLink.setAttribute('type', 'submit');
               this.deleteLink.setAttribute('onclick', 'return confirm("Etes-vous sûr ?")');
               this.i = document.createElement('i');
               this.i.setAttribute('class', 'fas fa-trash-alt');
               this.deleteLink.appendChild(this.i);
               this.modificationBox.appendChild(this.modificationLink);
               this.deleteBox.appendChild(this.deleteLink);
               this.tr.appendChild(this.deleteBox);

               this.tbody.appendChild(this.tr);
            });
            
            
        }
    }

    refreshSeances()
    {
        this.tbody.innerHTML = "";
    }

}
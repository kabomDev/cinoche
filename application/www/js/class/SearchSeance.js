'use strict';

class SearchSeance
{
    constructor(){
        this.selectFilm = document.querySelector('#filmTitle');
        this.selectSalle = document.querySelector('#salle');
        this.selectDate = document.querySelector('#dateSeance');
        this.searchSeance = document.querySelector('#searchFilmSeance');
        this.fieldset = document.querySelector('#block-seances');
        this.createSeance = document.querySelector('#create-seance');
    }

    init()
    {
        if(this.searchSeance)
        {
            this.searchSeance.addEventListener('click', this.onSearchSeances.bind(this));
        }

        if(this.createSeance)
        {
            this.createSeance.addEventListener('click', this.addSeance.bind(this));
        }
    }

    onSearchSeances()
    {
        
        const film_id_selected = this.selectFilm.value;
        const salle_id_selected = this.selectSalle.value;
        const date_selected = this.selectDate.value;
        
        const url = getRequestUrl()+ '/admin/addSeance';
        //on met dans un tableau les infos utiles
        const data = {'date': date_selected, 'film_id': film_id_selected, 'salle': salle_id_selected};
        //on fait la requete ajax
        $.post(url,data, this.displayAvailabilitySeance.bind(this));

    }

    displayAvailabilitySeance(s)
    {
        const availableSeances = JSON.parse(s);

        if(availableSeances.length > 0)
        {
            this.refresh();
            this.fieldset.classList.remove('hidden');
            document.querySelector('#create-seance').classList.remove('hidden');
            let index = 1;
            let legend = document.createElement('legend');
            this.fieldset.appendChild(legend);
            availableSeances.forEach(element => {
                
                let div = document.createElement('div');
                let checkbox = document.createElement('input');
                let label = document.createElement("label");
                let text = document.createTextNode(element.substr(11,5));
                checkbox.setAttribute("type", "checkbox");
                checkbox.setAttribute("name", "debut_seance"+ index)
                checkbox.setAttribute('class', 'check');
                checkbox.setAttribute("value", element.substr(11,5));
                checkbox.setAttribute("id", "validSeance" + index);
                label.setAttribute("for","validSeance");
                legend.innerHTML = "Horaires disponibles";
                label.appendChild(text);
                div.appendChild(checkbox);
                div.appendChild(label);
                
                this.fieldset.appendChild(div);
                
                index++;
            }); 
        }
        else
        {
            this.refresh();
            this.fieldset.classList.remove('hidden');
            let p = document.createElement('p');
            let legend = document.createElement('legend');
            legend.innerHTML = "Horaires disponibles";
            p.innerHTML = "pas de séances de libre pour cette salle à cette date";
            
            this.fieldset.appendChild(legend);
            this.fieldset.appendChild(p);
            
        }
    }

    addSeance()
    {
        
        const film_id_selected = this.selectFilm.value;
        const salle_id_selected = this.selectSalle.value;
        const date_selected = this.selectDate.value;
        
        const array = [];
        this.seanceSchedule = document.querySelectorAll('input[type=checkbox]:checked');
        this.seanceSchedule.forEach(function(s)
        {
            array.push(s.value); 
        })
       
        const url = getRequestUrl()+ '/admin/create';
        //on met dans un tableau les infos utiles
        const data = {'debut_seance': array, 'id_film': film_id_selected, 'id_salle': salle_id_selected, 'date_seance': date_selected};
        //on fait la requete ajax
        $.post(url,data, document.location.href=getRequestUrl()+"/admin");
    }

    refresh()
    {
        this.fieldset.innerHTML = " ";
    }
}
'use strict';

class MoviesDB
{
    constructor()
    {
        this.date = new Date();
        this.proposition_films = document.querySelector('#proposition_films');
        this.film_description = document.querySelector('.film-description');
        this.runtimeTab = [];
    }
    
    init()
    {
        if(this.proposition_films)
        {   //requete pour recuperer tous les films qui vont sortir via l'API de themovieDB
            this.films = $.ajax({
                url: "https://api.themoviedb.org/3/movie/upcoming?api_key=f3a7347fd19c436dc3e9858efb1769c5&language=fr&page=1&region=FR",
                success: this.displayAll.bind(this)});
        }
    }

    displayAll(films)
    {
        const currentdate = this.takeDate();
        this.films = films.results;
        //console.log(this.films);
        const section_movies = document.querySelector('#movies_content');
        section_movies.innerHTML = "";
        const ul = document.createElement("ul");
        
        this.films.forEach(function(film)
        {
            console.log(film.release_date);
            
            if(film.release_date >= currentdate)
            {   //requete pour recuperer la video
                $.ajax({
                    url: "https://api.themoviedb.org/3/movie/"+film.id+"/videos?api_key=f3a7347fd19c436dc3e9858efb1769c5&language=fr&region=FR",
                    timeout: 5000,
                    dataType: "json",
                }).done(function(video)
                {
                    let videoMovie = video.results[0].key;
                    //requete pour recuperer la durée du film
                    $.ajax({
                        url: "https://api.themoviedb.org/3/movie/"+ film.id +"?api_key=f3a7347fd19c436dc3e9858efb1769c5&language=fr&region=FR",
                        timeout: 5000,
                        dataType: "json",
                        
                    }).done(function(f)
                    {   //calcul pour mettre la durée au format que l'on souhaite
                        let runtime = f.runtime;
                        let h = Math.floor(runtime/60);
                        let moduloRuntime = runtime%60;
                        let min = moduloRuntime.toString().padStart(2, '0');
                        runtime = h+ ":" +min;

                        //construction des li pour chaque film
                        const li = document.createElement("li");
                        const titleText = document.createTextNode(film.title);
                        const p = document.createElement("p");
                        const img = document.createElement("img");
                        const p2 = document.createElement("small");
                        const dateText = document.createTextNode("date de sortie: " + film.release_date);
                        const button = document.createElement("Button");
                        const textBtn = document.createTextNode("Lire le synopsis");
                        if(film.poster_path !== null)
                        {
                            img.src =`http://image.tmdb.org/t/p/w154${film.poster_path}`;
                        }
                        else
                        {
                            img.src =getWwwUrl()+"/images/films/no-photo.png";
                        }
                        li.setAttribute("class", 'card product mb-3');
                        li.setAttribute("id", film.id);
                        img.setAttribute("class", "card-img-top");
                        p.setAttribute("class", "card-title");
                        button.setAttribute("type", "button");
                        button.setAttribute("class", "film-description btn btn-primary");
                        button.setAttribute("data-toggle", "modal");
                        button.setAttribute("data-target", "#modal_"+film.id);
                        
                        //construction du modal
                        const modal = document.createElement("form");
                        modal.setAttribute("method", "post");   
                        modal.setAttribute("class", "modal fade");
                        modal.setAttribute("id", "modal_"+film.id);
                        modal.setAttribute("tabindex", "-1");
                        modal.setAttribute("role","dialog");
                        modal.setAttribute("aria-labelledby", "modal-film-title");
                        modal.setAttribute("aria-hidden", "true");
                        const modal_dialog = document.createElement("div");
                        modal_dialog.setAttribute("class", "modal-dialog");
                        modal_dialog.setAttribute("role", "document");
                        const modal_content = document.createElement("div");
                        modal_content.setAttribute("class", "modal-content");
                        const modal_header = document.createElement("div");
                        modal_header.setAttribute("class", "modal-header");
                        const h5 = document.createElement("h5");
                        h5.setAttribute("class", "modal-title");
                        h5.setAttribute("id", "modal-film-title");
                        const modal_title_text = document.createTextNode(film.title);
                        const span_modal = document.createElement("span");
                        span_modal.setAttribute("aria-hidden", "true");
                        span_modal.innerHTML ="&times;";
                        const modal_body = document.createElement("div");
                        const h6 = document.createElement("h6");
                        h6.innerHTML = "Synopsis";
                        const synopsis = document.createElement("p");
                        synopsis.innerHTML = film.overview;
                        const runtimeText = document.createElement("p");

                        if(runtime !== "0:00")
                        {
                            runtimeText.setAttribute('class', 'text-primary');
                            runtimeText.innerHTML = "durée du film: " +runtime;
                        }
                        else
                        {
                            runtimeText.setAttribute('class', 'text-danger');
                            runtimeText.innerHTML = "durée du film: pas défini pour le moment";
                        }
                        modal_body.setAttribute("class", "modal-body");
                        
                        const modal_footer = document.createElement("div");
                        modal_footer.setAttribute("class", "modal-footer");

                        const closeBtn2 = document.createElement("button");
                        closeBtn2.setAttribute("type", "button");
                        closeBtn2.setAttribute("class","btn btn-secondary");
                        closeBtn2.setAttribute("data-dismiss", "modal");
                        closeBtn2.innerHTML = "Close";
                        
                        const submitBtn = document.createElement("button");
                        submitBtn.setAttribute("type", "submit");
                        submitBtn.setAttribute("class","modal-add-film btn btn-primary");
                        submitBtn.innerHTML = "Enregistrer";

                        //creation des input caché pour recupere les infos dont on aura besoin pour enregistrer le film dans la bdd
                        const input_id = document.createElement('input');
                        input_id.setAttribute("type", "hidden");
                        input_id.setAttribute("name", "id");
                        input_id.setAttribute("value", film.id);

                        const input_title = document.createElement("input");
                        input_title.setAttribute("type", "hidden");
                        input_title.setAttribute("name", "title");
                        input_title.setAttribute("value", film.title);

                        const input_content = document.createElement("input");
                        input_content.setAttribute("type", "hidden");
                        input_content.setAttribute("name", "content");
                        input_content.setAttribute("value", film.overview);

                        const input_picture = document.createElement("input");
                        input_picture.setAttribute("type", "hidden");
                        input_picture.setAttribute("name", "picture");
                        input_picture.setAttribute("value", film.poster_path);

                        const input_runtime = document.createElement("input");
                        input_runtime.setAttribute("type", "hidden");
                        input_runtime.setAttribute("name", "runtime");
                        input_runtime.setAttribute("value", runtime);

                        const input_video = document.createElement("input");
                        input_video.setAttribute("type", "hidden");
                        input_video.setAttribute("name", "video");
                        input_video.setAttribute("value", "https://www.youtube.com/watch?v=" + videoMovie);

                        const input_releaseDate = document.createElement("input");
                        input_releaseDate.setAttribute("type", "hidden");
                        input_releaseDate.setAttribute("name", "date_sortie");
                        input_releaseDate.setAttribute("value", film.release_date);

                        //on rattache tous les elements lié au modal
                        h5.appendChild(modal_title_text);
                        modal_header.appendChild(h5);
                        modal_content.appendChild(modal_header);
                        modal_content.appendChild(modal_body);
                        modal_body.appendChild(h6);
                        modal_body.appendChild(synopsis);
                        modal_body.appendChild(runtimeText);
                        modal_content.appendChild(modal_footer);
                        modal_footer.appendChild(closeBtn2);
                        if(runtime !== "0:00")
                        {
                            modal_footer.appendChild(submitBtn);
                        }
                        modal_dialog.appendChild(modal_content);
                        modal.appendChild(modal_dialog);
                        modal.appendChild(input_title);
                        modal.appendChild(input_content);
                        modal.appendChild(input_picture);
                        modal.appendChild(input_runtime);
                        modal.appendChild(input_video);
                        modal.appendChild(input_releaseDate);

                        //on rattache tous les elements lié a la section
                        li.appendChild(img);
                        p.appendChild(titleText);
                        p2.appendChild(dateText);
                        li.appendChild(p);       
                        li.appendChild(p2);
                        button.appendChild(textBtn);
                        li.appendChild(button);
                        li.appendChild(modal);
                        ul.appendChild(li);
                    })
                })
            }
            section_movies.appendChild(ul);
        });
    }


    takeDate()
    {
        let day = this.date.getUTCDate();
        day = day.toString().padStart(2,'0');
        
        let month = this.date.getMonth()+1;
        month = month.toString().padStart(2,'0');

        let year = this.date.getFullYear();

        let currentDate = day +"-"+ month +"-"+ year;
        //console.log(currentDate);
        return currentDate;
    }

}
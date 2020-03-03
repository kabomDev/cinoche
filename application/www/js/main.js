'use strict';

document.addEventListener("DOMContentLoaded", function(event) {
    const seance = new SeanceList();
    seance.init();

    const search = new SearchBar();
    search.init();

    const selectFilm = new SelectFilm();
    selectFilm.init();

    const searchSeance = new SearchSeance();
    searchSeance.init();

    const movies = new MoviesDB();
    movies.init();

    //=====================================================//
    
});


function rafraichirPage() {
    location.reload();
}

function resultatWeb() {
    const results = document.querySelectorAll('.search-result');
}

function displayDetails(nom, genre, idImage, saison, synopsis) {
    // Affiche les informations dans la div "details"
    const detailsDiv = document.getElementById('details');
    console.log({saison});
    if (saison == 1){
        detailsDiv.innerHTML = `
        <h2>${nom}</h2>
        <p id="genre">Genre: ${genre}</p>
        <p id="saison">Il y a ${saison} saison</p>
        <div class="afficheMangas">
        <img src="./Mangas/${idImage}.jpg" alt="${idImage}.jpg">
        <p id="synopsis"> ${synopsis}</p>
        </div>
    `;
    }else {
        detailsDiv.innerHTML = `
        <h2>${nom}</h2>
        <p id="genre">Genre: ${genre}</p>
        <p id="saison">Il y a ${saison} saisons</p>
        <div class="afficheMangas">
        <img src="./Mangas/${idImage}.jpg" alt="${idImage}.jpg">
        <p id="synopsis"> ${synopsis}</p>
        </div>
    `;
    }


    // Réinitialise la zone de recherche en effaçant son contenu
    const searchInput = document.getElementById('searchInput');
    searchInput.value = ''; // Efface le contenu de la zone de recherche

    // Efface la liste des résultats
    const resultsDiv = document.getElementById('results');
    resultsDiv.innerHTML = '';
}

    function regexp() {
    const searchInput = document.getElementById('searchInput');
    const results = document.getElementById('results');

    // Écoute les changements dans le champ de recherche
    searchInput.addEventListener('input', () => {
    const searchTerm = searchInput.value;

    // Vérifie si la longueur du terme de recherche est supérieure à 0 caractère
    if (searchTerm.length >= 1) {
    // Utilise AJAX pour envoyer une requête au serveur PHP
    const ajaxReq = new XMLHttpRequest();
    ajaxReq.open('GET', `search.php?q=${searchTerm}`, true);
    ajaxReq.onreadystatechange = function () {
    if (ajaxReq.readyState === 4 && ajaxReq.status === 200) {
    // Efface les résultats précédents
    results.innerHTML = '';

    // Parse la réponse JSON du serveur
    const response = JSON.parse(ajaxReq.responseText);

    // classe les resultats par ordre alphabétique
    response.sort((a, b) => a.Nom.localeCompare(b.Nom));

    // Parcourt les résultats
    response.forEach(result => {
    // Crée un lien hypertexte pour chaque nom d'anime
    const animeLink = document.createElement('div');
    animeLink.classList.add('search-result');
    animeLink.classList.add('anime-name'); // Ajoute la classe "anime-name"
    animeLink.setAttribute('data-nom', result.Nom);
    animeLink.setAttribute('data-genre', result.Genre);
    animeLink.setAttribute('data-idImage', result.idImage);
    animeLink.setAttribute('data-saison', result.Saison);
    animeLink.setAttribute('data-synopsis', result.synopsis);
    animeLink.textContent = result.Nom; // Texte du lien

    // Ajoute le lien au div des résultats
    results.appendChild(animeLink);

    // Ajoute un gestionnaire d'événements "click" à ce résultat
    animeLink.addEventListener('click', () => {
    // Récupère les informations du résultat cliqué
    const nomAnime = result.Nom;
    const genreAnime = result.Genre;
    const idImageAnime = result.idImage;
    const saisonAnime = result.Saison;
    const synopsisAnime = result.Synopsis;

    // Affiche les informations
    displayDetails(nomAnime, genreAnime, idImageAnime, saisonAnime, synopsisAnime);
});
});

    // Ajoute un gestionnaire d'événements aux résultats
    resultatWeb();
}
};
    ajaxReq.send();
} else {
    // Efface les résultats en temps réel si le terme de recherche est trop court
    results.innerHTML = '';
}
});
}
function afficheSynopsis() {
    var synopsis = document.getElementById("synopsis");
    synopsis.style.display = "block";
}
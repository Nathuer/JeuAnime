<?php
if (isset($_GET["q"])) {
    $anime = strtoupper($_GET["q"]);

    // CONNEXION À LA BASE DE DONNÉES
    $source = "mysql:host=81.250.164.20;dbname=JeuAnime;charset=utf8";
    $utilisateur = "Nathuer";
    $motdepasse = "anime";

    try {
        $connexion = new PDO($source, $utilisateur, $motdepasse);
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        // Gérer les erreurs de connexion ici
        header('HTTP/1.1 500 Internal Server Error');
        exit();
    }

    // RECHERCHE DANS LA BASE DE DONNÉES
    $requete = "SELECT Nom, Genre, idImage, Saison, Synopsis FROM Anime WHERE Nom LIKE :anime";
    $jeu = $connexion->prepare($requete);
    $searchTerm = '%' . $anime . '%'; // Ajoute des jokers pour la recherche LIKE
    $jeu->bindParam(":anime", $searchTerm, PDO::PARAM_STR); // Mettez à jour ici
    $jeu->execute();
    $resultats = $jeu->fetchAll(PDO::FETCH_ASSOC);



    // Envoie les résultats au format JSON
    header('Content-Type: application/json');

    if ($resultats) {
        echo json_encode($resultats);
    } else {
        // Aucun résultat trouvé, renvoie un tableau vide
        echo json_encode([]);
    }
} else {
    // Aucun terme de recherche fourni, renvoie un tableau vide
    header('Content-Type: application/json');
    echo json_encode([]);
}
?>

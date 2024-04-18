<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mettre à jour un animé</title>
    <link rel="stylesheet" href="update.css">
</head>
<body>

<h1>Mettre à jour un animé</h1>

<form method="post">
    <label for="nom_anime">Nom de l'animé à mettre à jour :</label>
    <div id="affichage">
        <select name="nom_anime" id="nom_anime">
        <option value="">Sélectionner un animé</option>

            <?php
            // Connexion à la base de données
            $source = "mysql:host=81.250.164.20;dbname=JeuAnime;charset=utf8";
            $utilisateur = "Nathuer";
            $motdepasse = "anime";

            try {
                $connexion = new PDO($source, $utilisateur, $motdepasse);
                $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Erreur de connexion à la base de données : " . $e->getMessage();
                exit;
            }

            // Requête pour récupérer tous les noms des animés triés par ordre alphabétique
            $requeteNoms = "SELECT Nom FROM Anime ORDER BY Nom";
            $resultatsNoms = $connexion->query($requeteNoms)->fetchAll(PDO::FETCH_COLUMN);

            // Afficher chaque nom dans une option du menu déroulant
            foreach ($resultatsNoms as $nom) {
                echo "<option value='$nom'>$nom</option>";
            }
            ?>
        </select>
    </div>

    <input id="recherche" type="submit" value="Rechercher">
    <button id="rafraichir" onclick="rafraichirPage()">Rafraîchir la page</button>

</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nom_anime"])) {
// Traitement du formulaire

// Connexion à la base de données
$source = "mysql:host=81.250.164.20;dbname=JeuAnime;charset=utf8";
$utilisateur = "Nathuer";
$motdepasse = "anime";

try {
    $connexion = new PDO($source, $utilisateur, $motdepasse);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
    exit;
}

// Récupérer le nom de l'animé à mettre à jour depuis le formulaire
$nom_anime = $_POST["nom_anime"];

// Requête pour récupérer les informations de l'animé
$requete = "SELECT * FROM Anime WHERE Nom = :nom_anime";

// Préparer et exécuter la requête
$jeu = $connexion->prepare($requete);
$jeu->bindParam(":nom_anime", $nom_anime, PDO::PARAM_STR);
$jeu->execute();
$anime = $jeu->fetch(PDO::FETCH_ASSOC);

// Afficher le formulaire avec les informations de l'animé pour mise à jour
if ($anime) {
?>
<form method="post">
    <input class="infos" type="hidden" name="id" value="<?= $anime['Id'] ?>">
    <label for="nom">Nom :</label>
    <input id="Nom" type="text" id="nom" name="nom" value="<?= $anime['Nom'] ?>"><br>
    <label for="genre">Genre :</label>
    <input class="infos" type="text" id="genre" name="genre" value="<?= $anime['Genre'] ?>"><br>
    <label for="saison">Saison :</label>
    <input class="infos" type="text" id="saison" name="saison" value="<?= $anime['Saison'] ?>"><br>
    <label for="synopsis">Synopsis :</label>
    <textarea id="synopsis" name="synopsis" rows="6" cols="50" readonly><?= $anime['Synopsis'] ?></textarea><br>
    <input id="maj" type="submit" value="Mettre à jour">
</form>
<?php
    } else {
        echo "<p>Aucun animé trouvé avec ce nom.</p>";
    }

} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nom"])) {
    // Traitement du formulaire de mise à jour

    // Récupérer les valeurs du formulaire
    $id = $_POST["id"];
    $nom = $_POST["nom"];
    $genre = $_POST["genre"];
    $saison = $_POST["saison"];
    $synopsis = $_POST["synopsis"];

    // Connexion à la base de données
    $source = "mysql:host=81.250.164.20;dbname=JeuAnime;charset=utf8";
    $utilisateur = "Nathuer";
    $motdepasse = "anime";

    try {
        $connexion = new PDO($source, $utilisateur, $motdepasse);
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "<p>Erreur de connexion à la base de données :</p> " . $e->getMessage();
        exit;
    }

    // Requête pour mettre à jour les informations de l'animé
    $requete = "UPDATE Anime SET Nom = :nom, Genre = :genre, Saison = :saison, Synopsis = :synopsis WHERE Id = :id";

    // Préparer et exécuter la requête
    $jeu = $connexion->prepare($requete);
    $jeu->bindParam(":id", $id, PDO::PARAM_INT);
    $jeu->bindParam(":nom", $nom, PDO::PARAM_STR);
    $jeu->bindParam(":genre", $genre, PDO::PARAM_STR);
    $jeu->bindParam(":saison", $saison, PDO::PARAM_INT);
    $jeu->bindParam(":synopsis", $synopsis, PDO::PARAM_STR);
    $jeu->execute();

    echo "<p>Les informations de l'animé ont été mises à jour avec succès.</p>";
}
?>


<p><a href="../PROJET/projet_anime.php">Retour au jeu</a></p>


<script>
    function rafraichirPage() {
        location.reload();
    }
</script>
</body>
</html>

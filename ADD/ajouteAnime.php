<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajoute un animé</title>
    <link rel="stylesheet" href="ajout.css">
</head>
<body>
<form method="post">
    <h1> Ajoute un animé à la base de donnée</h1>
    <?php
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

    // Récupérer les valeurs uniques de la colonne 'Genre' dans la table 'Anime'
    $requeteGenres = "SELECT DISTINCT Genre FROM Anime";
    $resultatsGenres = $connexion->query($requeteGenres)->fetchAll(PDO::FETCH_COLUMN);

    $requeteSaison = "SELECT DISTINCT Saison FROM Anime ORDER BY Saison ASC";
    $resultatsSaison = $connexion->query($requeteSaison)->fetchAll(PDO::FETCH_COLUMN);

    if (isset($_POST["anime"], $_POST["genre"], $_POST["saison"])) {
        $anime = $_POST["anime"];
        $genre = $_POST["genre"];
        $saison = $_POST["saison"];

        if (empty($anime) || empty($genre) || empty($saison)) {
            echo "<p>Tous les champs doivent être remplis.</p>";
        } else {
            $requete2 = "SELECT COUNT(*) AS existe FROM Anime WHERE Nom = :anime";
            $jeu = $connexion->prepare($requete2);
            $jeu->bindParam(":anime", $anime, PDO::PARAM_STR);
            $jeu->execute();
            $resultat2 = $jeu->fetch(PDO::FETCH_ASSOC);

            if ($resultat2["existe"] > 0) {
                echo "<p>L'animé '$anime' existe déjà dans la base de données.</p>";
            } else {
                $requete3 = "INSERT INTO Anime (Nom, Genre, Saison) VALUES (:anime, :genre, :saison)";
                $jeu = $connexion->prepare($requete3);
                $jeu->bindParam(":anime", $anime, PDO::PARAM_STR);
                $jeu->bindParam(":genre", $genre, PDO::PARAM_STR);
                $jeu->bindParam(":saison", $saison, PDO::PARAM_STR);

                if ($jeu->execute()) {
                    echo "<p>L'animé '$anime' a été ajouté à la base de données.</p>";
                } else {
                    echo "<p>Erreur lors de l'ajout de l'animé à la base de données.</p>";
                }
            }
        }
    }
    ?>
    <div id="affichage">
        <input autocomplete="off" type="text" name="anime" class="anime" placeholder="Nom de l'animé">
        <select name="genre" class="genre">
            <option value="">Sélectionner un genre</option>
            <?php foreach ($resultatsGenres as $genre) : ?>
                <option value="<?php echo $genre; ?>"><?php echo $genre; ?></option>
            <?php endforeach; ?>
        </select>
        <select name="saison" class="saison">
            <option value="">Combien de saison ?</option>
            <?php foreach ($resultatsSaison as $saison) : ?>
                <option value="<?php echo $saison; ?>"><?php echo $saison; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <input type="submit" value="Valider">
    <button onclick="rafraichirPage()">Rafraîchir la page</button>
    <p><a href="../PROJET/projet_anime.php">Retour au jeu</a></p>
</form>
<script>
    function rafraichirPage() {
        location.reload();
    }
</script>
</body>
<footer>
</footer>
</html>

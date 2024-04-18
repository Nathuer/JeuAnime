<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Jeu Anime</title>
        <link rel="stylesheet" href="anime.css">
        <script type="text/javascript" src="projet.js"></script>
    </head>

    <body>
        <nav>
            <button class="refresh" type="button" onclick="rafraichirPage()">Rafraîchir la page</button>
            <button class="add" onclick="window.location.href = '../ADD/ajouteAnime.php';">Ajouter un nom</button>
            <button class="update" onclick="window.location.href = '../UPDATE/update.php';">Modifier un nom</button>

        </nav>
        <?php
        // CONNEXION À LA BASE DE DONNÉES
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

        // Requête SQL pour compter le nombre d'éléments dans la table Anime
        $nombreAnime = "SELECT COUNT(*) AS total FROM Anime";

        try {
            // Préparez et exécutez la requête
            $jeu = $connexion->prepare($nombreAnime);
            $jeu->execute();

            // Récupérez le résultat
            $resultat = $jeu->fetch(PDO::FETCH_ASSOC);

            // Affichez le nombre d'éléments
            echo "<span id='monNombreAnime'>Il y a  " . $resultat['total'] . " animés différents dans la base de données </span>";
        } catch (PDOException $e) {
            echo "Erreur lors de l'exécution de la requête : " . $e->getMessage();
        }
        ?>

        <form method="post">
            <div id="heartBody">
            <h1> Bienvenue dans ma liste de manga</h1>
            <p id="anime"> Entrer un animé</p>
             <input type="text" id="searchInput" autocomplete="off" name="anime" class="recherche">
        </form>
        <?php
        if (isset($_POST["anime"])) {
            $anime = strtoupper($_POST["anime"]);

            // CONNEXION À LA BASE DE DONNÉES
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

            // RECHERCHE DANS LA BASE DE DONNÉES
            $requete = "SELECT idImage, Synopsis FROM nom WHERE Nom = :anime";
            $jeu = $connexion->prepare($requete);
            $jeu->bindParam(":anime", $anime, PDO::PARAM_STR);
            $jeu->execute();
            $resultats = $jeu->fetchAll(PDO::FETCH_ASSOC);

                    $cheminDossier = '../Mangas/';
                    $nomAnimeTrouve = $resultats["idImage"];

                    // Scanne le dossier pour récupérer la liste des fichiers
                    $images = scandir($cheminDossier);

                    // Parcourt les fichiers du dossier
                    foreach ($images as $image) {
                        // Exclure les dossiers spéciaux (. et ..)
                        if ($image == '.' || $image == '..') {
                            continue;
                        }

                        // Vérifie si le nom de l'image correspond exactement au nom trouvé dans la base de données
                        if (strcasecmp($image, $nomAnimeTrouve . '.jpg') === 0) {
                            echo '<img src="' . $cheminDossier . $image . '" alt="' . $image . '"><br>';
                        }
                    }
        }
        ?>

        <div id="results" class="resultat"></div>
        <div id="details"></div>
        </div>
        <script>

            // Appeler la fonction regexp pour la première fois
            regexp();

        </script>


    </body>
</html>

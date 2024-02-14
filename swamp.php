<?php

try {
    // Connexion à la base de données SQLite
    $bdd = new PDO('sqlite:db.sqlite');
    // Activation du mode d'erreur PDO pour afficher les erreurs
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête SQL pour récupérer les horaires
    $sql = "SELECT * FROM horaires;";
    $result = $bdd->query($sql);
} catch (PDOException $e) {
    // En cas d'erreur, affiche le message d'erreur
    echo "Erreur de connexion ou d'exécution de la requête : " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Arcadia, marais</title>
        <meta name="viewport" content="width=device-width, initial-scale=0.60, maximum-scale=2.0, minimum-scale=0.60">        
        <meta name="description" content="Explorez la biodiversité extraordinaire du parc animalier Arcadia, un lieu magique abritant plusieurs habitats uniques. Plongez au cœur de la nature sauvage et découvrez des espèces fascinantes, de la faune endémique aux majestueux prédateurs. Rejoignez-nous pour une aventure inoubliable au sein d'Arcadia, où la préservation de la vie sauvage est notre engagement passionné.">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <header>
            <a class="login" href="login.php">Connexion</a>
            <h1 class="title">Marais</h1>
            <nav class="navbar">
                <ul>
                    <li>
                        <a href="index.php">Accueil</a>
                    </li>
                    <li>
                        <a href="services.php">Services</a>
                    </li>
                    <li>
                        <a href="housing.php">Habitats</a>
                    </li>
                    <li>
                        <a href="contact.php">Contact</a>
                    </li>
                </ul>
            </nav>
        </header>
        <div class="animals">
            <p class="annonce">
            Nous vous présentons les animaux présent dans notre marais. La magie prend vie à travers une biodiversité exceptionnelle. Explorez cet habitat et découvrez la grâce des flamants roses qui évoluent en toute liberté, observez nos crocodiles qui se cachent astucieusement dans les eaux sombres, et laissez-vous émerveiller par la variété éclatante d'oiseaux tropicaux qui peuplent cet environnement unique. Rencontrez nos grenouilles colorées et serpents mystérieux, tous contribuant à l'équilibre délicat de cet écosystème fascinant. Bienvenue dans nos marécage, où chaque pas révèle une nouvelle facette captivante de ce monde ! 
            </p>
            <div class="box">
                <a href="crocodile.html">
                    <img src="img/animals/crocodile.gif" alt="Image du Crocodile du parc" width="200" height="200">
                </a>  
                <p>Odile, la femelle Crocodile</p>
            </div>
            <div class="box">
                <a href="boa.html">
                    <img src="img/animals/boa-constrictor.gif" alt="Image du boa constrictor du parc" width="200" height="200">
                </a>                
                <p>Victor, le Boa constrictor</p>
            </div>
        </div>
        <footer>
            <p>© 2024 Arcadia, tous droits réservés</p>
            <div class="horaires">
                <ul>
                    <li>
                        Horaires d'ouverture
                    </li>
                    <br>
                    <?php
                    // Affichage des horaires
                    $row = $result->fetch(PDO::FETCH_ASSOC);
                    if ($row) {
                        do {
                            $openDay = $row["jour"];
                            $openHours = $row["heures"];
                            ?>
                            <li><?php echo $openDay; ?>: <?php echo $openHours; ?></li>
                            <?php
                        } while ($row = $result->fetch(PDO::FETCH_ASSOC));
                    } else {
                        echo "<li>Aucun horaire d'ouverture trouvé.</li>";
                    }
                    ?>
                </ul>
            </div>
        </footer>
    </body>
</html>
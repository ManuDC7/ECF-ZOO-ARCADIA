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
        <title>Arcadia, jungle</title>
        <meta name="viewport" content="width=device-width, initial-scale=0.60, maximum-scale=2.0, minimum-scale=0.60">        
        <meta name="description" content="Explorez la biodiversité extraordinaire du parc animalier Arcadia, un lieu magique abritant plusieurs habitats uniques. Plongez au cœur de la nature sauvage et découvrez des espèces fascinantes, de la faune endémique aux majestueux prédateurs. Rejoignez-nous pour une aventure inoubliable au sein d'Arcadia, où la préservation de la vie sauvage est notre engagement passionné.">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <header>
            <a class="login" href="login.php">Connexion</a>
            <h1 class="title">Jungle</h1>
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
                Nous vous présentons les animaux de notre jungle luxuriante. Explorez notre refuge où la vie sauvage prospère dans un écosystème exotique. Rencontrez nos amis à fourrure tels que les singes malicieux et les paresseux paisibles se balançant entre les branches. Admirez la beauté majestueuse des tigres rayés et la vivacité des perroquets aux plumes éclatantes. Chaque recoin de notre jungle abrite des créatures étonnantes, des serpents colorés aux papillons féériques.             
            </p>
            <div class="box">
                <a href="leopard.html">
                    <img src="img/animals/leopard.gif" alt="Image du Léopard du parc" width="200" height="200">
                </a>  
                <p>Léonard, le Léopard</p>
            </div>
            <div class="box">
                <a href="toucan.html">
                    <img src="img/animals/toucan.gif" alt="Image du Toucan du parc" width="200" height="200">
                </a>                
                <p>Adan, le Toucan</p>
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
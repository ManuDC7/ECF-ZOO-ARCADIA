<?php

try {
    // Connexion à la base de données SQLite
    $bdd = new PDO('sqlite:db.sqlite');
    // Activation du mode d'erreur PDO pour afficher les erreurs
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête SQL pour récupérer les habitats
    $house = "SELECT * FROM habitat;";
    $resultHouse = $bdd->query($house);
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
        <title>Arcadia, habitats</title>
        <meta name="viewport" content="width=device-width, initial-scale=0.60, maximum-scale=2.0, minimum-scale=0.60">        
        <meta name="description" content="Explorez la biodiversité extraordinaire du parc animalier Arcadia, un lieu magique abritant plusieurs habitats uniques. Plongez au cœur de la nature sauvage et découvrez des espèces fascinantes, de la faune endémique aux majestueux prédateurs. Rejoignez-nous pour une aventure inoubliable au sein d'Arcadia, où la préservation de la vie sauvage est notre engagement passionné.">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <header>
            <a class="login" href="login.php">Connexion</a>
            <h1 class="title">Habitats</h1>
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
        <div class="horizontal-section">

        <?php
            // Affichage des habitats
            $rowHouse = $resultHouse->fetch(PDO::FETCH_ASSOC);
            if ($rowHouse) {
                do {
                    $houseImg = $rowHouse["slug_img"];
                    $houseName = $rowHouse["nom"];
                    $houseText = $rowHouse["description"];
                    ?>
                        <div class="horizontal-box"
                            style="margin: 40px 0;
                                    border-radius: 12px;
                                    color: #FFF;
                                    text-decoration: none;
                                    padding: 30px 190px;
                                    text-align: center;"
                            onmouseover="this.style.backgroundImage='linear-gradient(to bottom, #0008, #0002), url(\'<?php echo $houseImg; ?>\')'"
                            onmouseout="this.style.backgroundImage='linear-gradient(to bottom, #0002, #0008), url(\'<?php echo $houseImg; ?>\')'">
                            <a href="housingNext.php?nom=<?php echo $houseName; ?>"><?php echo $houseName; ?></a>
                            <p>
                                <?php echo $houseText; ?>             
                            </p>
                        </div>
        <?php
                        } while ($rowHouse = $resultHouse->fetch(PDO::FETCH_ASSOC));
                    } else {
                        echo "<li>Aucun habitat trouvé.</li>";
                    }
        ?>
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
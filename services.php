<?php

try {
    // Connexion à la base de données SQLite
    $bdd = new PDO('sqlite:db.sqlite');

    // Activation du mode d'erreur PDO pour afficher les erreurs
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête SQL pour récupérer les services
    $serv = "SELECT * FROM services;";
    $resultServ = $bdd->query($serv);

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
        <title>Arcadia, services</title>
        <meta name="viewport" content="width=device-width, initial-scale=0.60, maximum-scale=2.0, minimum-scale=0.60">        
        <meta name="description" content="Explorez la biodiversité extraordinaire du parc animalier Arcadia, un lieu magique abritant plusieurs habitats uniques. Plongez au cœur de la nature sauvage et découvrez des espèces fascinantes, de la faune endémique aux majestueux prédateurs. Rejoignez-nous pour une aventure inoubliable au sein d'Arcadia, où la préservation de la vie sauvage est notre engagement passionné.">
        <link rel="stylesheet" href="style.css">
    </head>

    <body>

        <header>
            <a class="login" href="login.php">Connexion</a>
            <h1 class="title">Services</h1>
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

        <div class="container-services">
            <?php
                // Affichage des services
                $rowServ = $resultServ->fetch(PDO::FETCH_ASSOC);
                if ($rowServ) {
                                do {
                                    $servTitle = $rowServ["nom"];
                                    $servText = $rowServ["description"];
                                    $servImg = $rowServ["slug_img"]
                                    ?>
                                        <div class="service">
                                            <img src="<?php echo $servImg; ?>" alt="Image d'un service' du parc" width="810px" height="250px">
                                            <h3><?php echo $servTitle; ?></h3>
                                            <p>
                                                <?php echo $servText; ?>                
                                            </p>
                                        </div>
                                    <?php
                                    } while ($rowServ = $resultServ->fetch(PDO::FETCH_ASSOC));
                            } else {
                                echo "Aucun service trouvé.";
                            }
            ?>
            <a class="Back" onclick="plusSlides(-1)">&#10094;</a>
            <a class="forward" onclick="plusSlides(1)">&#10095;</a>
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

        <script src="carousel.js"></script>

    </body>

</html>
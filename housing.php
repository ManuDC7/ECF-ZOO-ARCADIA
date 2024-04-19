<?php

try {
    $bdd = new PDO('sqlite:db.sqlite');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $house = "SELECT * FROM housings;";
    $resultHouse = $bdd->query($house);
    
    $open = "SELECT * FROM opening;";
    $resultOpen = $bdd->query($open);
    } catch (PDOException $e) {
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
        <link rel="stylesheet" href="normalize.css">
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
            $rowHouse = $resultHouse->fetch(PDO::FETCH_ASSOC);
            if ($rowHouse) {
                            do {
                                $house_img = $rowHouse["slug"];
                                $house_name = $rowHouse["name"];
                                $house_description = $rowHouse["description"];
                                ?>
                                    <div class="horizontal-box"
                                style="margin: 40px 0;
                                    border-radius: 12px;
                                    color: #FFF;
                                    text-decoration: none;
                                    padding: 30px 190px;
                                    text-align: center;
                                    background-image: linear-gradient(to bottom, #0002, #0008), url('<?php echo $house_img; ?>');">
                                        <a href="housingNext.php?firstname=<?php echo $house_name; ?>"><?php echo $house_name; ?></a>
                                        <p>
                                            <?php echo $house_description; ?>             
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
            <p>© -Tous droits réservés - <a href="mentions_legales.php" style="text-decoration: underline; color: #000;">Mentions légales</a></p>
            <div class="horaires">
                <ul>
                    <li>
                        Horaires d'ouverture
                    </li>
                    <li>
                        <br>
                    </li>
                    <?php
                    $footer = $resultOpen->fetch(PDO::FETCH_ASSOC);
                    if ($footer) {
                        do {
                            $footer_day = htmlspecialchars($footer["day"]);
                            $footer_hours = htmlspecialchars($footer["hours"]);
                            ?>
                            <li><?php echo $footer_day; ?>: <?php echo $footer_hours; ?></li>
                            <?php
                        } while ($footer = $resultOpen->fetch(PDO::FETCH_ASSOC));
                    } else {
                        echo "<li>Aucun horaire d'ouverture trouvé.</li>";
                    }
                    ?>
                </ul>
            </div>
        </footer>

    </body>

</html>
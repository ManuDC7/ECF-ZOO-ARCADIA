<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Connexion à la base de données SQLite
$bdd = new PDO('sqlite:db.sqlite');
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Requête SQL pour récupérer les infos d'un animal
$animalName = $_GET['prénom'];
$creature = "SELECT * FROM animaux WHERE prénom = :prénom;";
$stmt = $bdd->prepare($creature);
$stmt->bindParam(':prénom', $animalName);
$stmt->execute();
$rowAnimal = $stmt->fetch(PDO::FETCH_ASSOC);

// Requête SQL pour récupérer les horaires
$sql = "SELECT * FROM horaires;";
$result = $bdd->query($sql);

// Récupération des informations de l'animal
$descAnimal = $rowAnimal["description"];
$prenomAnimal = $rowAnimal["prénom"];
$raceAnimal = $rowAnimal["race"];
$imgAnimal = $rowAnimal["slug_img"];
$sexeAnimal = $rowAnimal["sexe"];
$tailleAnimal = $rowAnimal["taille"];
$poidsAnimal = $rowAnimal["poids"];
$etatAnimal = $rowAnimal["état"];
$houseAnimal = $rowAnimal["habitat"];

?>

<!DOCTYPE html>

<html>

    <head>
        <meta charset="utf-8">
        <title>Arcadia, <?php echo $prenomAnimal; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=0.60, maximum-scale=2.0, minimum-scale=0.60">        
        <meta name="description" content="Explorez la biodiversité extraordinaire du parc animalier Arcadia, un lieu magique abritant plusieurs habitats uniques. Plongez au cœur de la nature sauvage et découvrez des espèces fascinantes, de la faune endémique aux majestueux prédateurs. Rejoignez-nous pour une aventure inoubliable au sein d'Arcadia, où la préservation de la vie sauvage est notre engagement passionné.">
        <link rel="stylesheet" href="style.css">
    </head>

    <body>

        <header>
            <a class="login" href="login.php">Connexion</a>
            <h1 class="title"><?php echo $prenomAnimal; ?></h1>
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

        <p class="left-box">
            <img src="<?php echo $imgAnimal; ?>" alt="Image d'une créature du parc'" width="400" height="300">
            Prénom : <strong><?php echo ucfirst($prenomAnimal); ?></strong><br><br> 
            Race : <strong><?php echo ucfirst($raceAnimal); ?></strong><br><br> 
            Sexe : <strong><?php echo ucfirst($sexeAnimal); ?></strong><br><br> 
            Etat : <strong><?php echo ucfirst($etatAnimal); ?></strong><br><br> 
            Tailee : <strong><?php echo ucfirst($tailleAnimal); ?></strong><br><br> 
            Poids : <strong><?php echo ucfirst($poidsAnimal); ?></strong><br><br> 
            Habitat : <strong><?php echo ucfirst($houseAnimal); ?></strong><br><br> 
        </p>

        <hr>

        <p style="margin: 60px; text-indent: 40px;">
            <?php echo $descAnimal; ?>
            </p>

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
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Connexion à la base de données SQLite
$bdd = new PDO('sqlite:db.sqlite');
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Requête SQL pour récupérer les infos d'un habitat
$housingName = $_GET['nom'];
$housing = "SELECT * FROM habitat WHERE nom = :nom;";
$stmt = $bdd->prepare($housing);
$stmt->bindParam(':nom', $housingName);
$stmt->execute();
$rowHouse = $stmt->fetch(PDO::FETCH_ASSOC);

// Requête SQL pour récupérer les animaux
$animHouse = "SELECT * FROM animaux WHERE habitat = :habitat;";
$stmtAnim = $bdd->prepare($animHouse);
$stmtAnim->bindParam(':habitat', $housingName);
$stmtAnim->execute();
$rowAnim = $stmtAnim->fetch(PDO::FETCH_ASSOC);

// Récupération des informations de l'habitat
$descHouse = $rowHouse["description2"];
$nameHouse = $rowHouse["nom"];

// Requête SQL pour récupérer les horaires
$sql = "SELECT * FROM horaires;";
$result = $bdd->query($sql);

?>

<!DOCTYPE html>

<html>

    <head>
        <meta charset="utf-8">
        <title>Arcadia, <?php echo $nameHouse; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=0.60, maximum-scale=2.0, minimum-scale=0.60">        
        <meta name="description" content="Explorez la biodiversité extraordinaire du parc animalier Arcadia, un lieu magique abritant plusieurs habitats uniques. Plongez au cœur de la nature sauvage et découvrez des espèces fascinantes, de la faune endémique aux majestueux prédateurs. Rejoignez-nous pour une aventure inoubliable au sein d'Arcadia, où la préservation de la vie sauvage est notre engagement passionné.">
        <link rel="stylesheet" href="style.css">
    </head>

    <body>

        <header>
            <a class="login" href="login.php">Connexion</a>
            <h1 class="title"><?php echo $nameHouse; ?></h1>
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
                <?php echo $descHouse; ?>
            </p>
            <?php
                // Affichage des animaux
                if ($rowAnim) {
                                do {
                                    $imgAnim = $rowAnim["slug_img"];
                                    $nameAnim = $rowAnim["prénom"];
                                    $raceAnim = $rowAnim["race"];
                                    $clickAnim = $rowAnim["click"];
                                    ?>
                                <div class="box">
                                    <a href="animals.php?prénom=<?php echo $nameAnim; ?>">
                                        <img src="<?php echo $imgAnim; ?>" alt="Image d'un animal du parc'" width="200" height="200">
                                    </a>  
                                    <p><?php echo ucfirst($nameAnim); ?>, notre <?php echo $raceAnim; ?></p>
                                </div>
                                    <?php
                                    } while ($rowAnim = $stmtAnim->fetch(PDO::FETCH_ASSOC));
                            } else {
                                echo "Aucun animal trouvé pour cet habitat.";
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

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script>
        $('.box').click(function() {
            var nameAnim = $(this).find('a').attr('href').split('=')[1];

            $.ajax({
                url: 'increment_click.php',
                method: 'POST',
                data: { name: nameAnim },
                success: function(response) {
                    console.log(response);
                }
            });
        });
        </script>

    </body>

</html>
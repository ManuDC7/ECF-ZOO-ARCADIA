<?php
session_start();

$bdd = new PDO('sqlite:db.sqlite');
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$housing_name = $_GET['firstname'];
$housing = "SELECT * FROM housings WHERE name = :name;";
$stmt = $bdd->prepare($housing);
$stmt->bindParam(':name', $housing_name);
$stmt->execute();
$rowHouse = $stmt->fetch(PDO::FETCH_ASSOC);

$animal_house = "SELECT * FROM animals WHERE housing = :housing;";
$stmtAnimal = $bdd->prepare($animal_house);
$stmtAnimal->bindParam(':housing', $housing_name);
$stmtAnimal->execute();
$rowAnimal = $stmtAnimal->fetch(PDO::FETCH_ASSOC);

$description_house = $rowHouse["description"];
$name_house = $rowHouse["name"];

$open = "SELECT * FROM opening;";
$resultOpen = $bdd->query($open);
?>

<!DOCTYPE html>

<html>

    <head>
        <meta charset="utf-8">
        <title>Arcadia, <?php echo $nameHouse; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=0.60, maximum-scale=2.0, minimum-scale=0.60">        
        <meta name="description" content="Explorez la biodiversité extraordinaire du parc animalier Arcadia, un lieu magique abritant plusieurs habitats uniques. Plongez au cœur de la nature sauvage et découvrez des espèces fascinantes, de la faune endémique aux majestueux prédateurs. Rejoignez-nous pour une aventure inoubliable au sein d'Arcadia, où la préservation de la vie sauvage est notre engagement passionné.">
        <link rel="stylesheet" href="normalize.css">
        <link rel="stylesheet" href="style.css">
    </head>

    <body>

        <header>
            <a class="login" href="login.php">Connexion</a>
            <h1 class="title"><?php echo $name_house; ?></h1>
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
                <?php echo $description_house; ?>
            </p>
            <?php
                if ($rowAnimal) {
                                do {
                                    $animal_img = $rowAnimal["slug"];
                                    $animal_name = $rowAnimal["firstname"];
                                    $animal_breed = $rowAnimal["breed"];
                                    ?>
                                <div class="box">
                                    <a href="animals.php?firstname=<?php echo $animal_name; ?>">
                                        <img src="<?php echo $animal_img; ?>" alt="Image d'un animal du parc'" width="200" height="200">
                                    </a>  
                                    <p><?php echo ucfirst($animal_name); ?>, notre <?php echo $animal_breed; ?></p>
                                </div>
                                    <?php
                                    } while ($rowAnimal = $stmtAnimal->fetch(PDO::FETCH_ASSOC));
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

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script>
        $('.box').click(function() {
            var name_animal = $(this).find('a').attr('href').split('=')[1];

            $.ajax({
                url: 'incrementClick.php',
                method: 'POST',
                data: { firstname: name_animal },
                success: function(response) {
                    console.log(response);
                }
            });
        });
        </script>

    </body>

</html>
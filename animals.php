<?php
session_start();
$bdd = new PDO('sqlite:db.sqlite');
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$animal_id = $_GET['id'];
$animal = "SELECT * FROM animals WHERE id = :id;";
$stmt = $bdd->prepare($animal);
$stmt->bindParam(':id', $animal_id);
$stmt->execute();
$rowAnimal = $stmt->fetch(PDO::FETCH_ASSOC);

$open = "SELECT * FROM opening;";
$resultOpen = $bdd->query($open);

$description_animal = $rowAnimal["description"];
$firstname_animal = $rowAnimal["firstname"];
$breed_animal = $rowAnimal["breed"];
$img_animal = $rowAnimal["slug"];
$house_animal = $rowAnimal["housing"];

$food = "SELECT * FROM foods WHERE animal_id = :id ORDER BY date DESC LIMIT 1;";
$animal_food = $bdd->prepare($food);
$animal_food->execute([':id' => $animal_id]);

$resultFood = $animal_food->fetch(PDO::FETCH_ASSOC);

$state_animal = $resultFood ? $resultFood["state"] : "Se porte bien";
$food_animal = $resultFood ? $resultFood["food"] : "Aucune information trouvée";
$weight_food_animal = $resultFood ? $resultFood["weight"] : "null";


require 'vendor/autoload.php';
$client = new MongoDB\Client("mongodb://manu:vanEtlaura7@localhost:27017");
$database = $client->selectDatabase("animals_click"); 
$collection = $database->selectCollection("animals_click"); 

$animal = $collection->findOne(['id' => (int)$animal_id]);
if ($animal) {
    $collection->updateOne(
        ['id' => (int)$animal_id],
        ['$inc' => ['click' => 1]]
    );
} else {
    $collection->insertOne(['id' => (int)$animal_id, 'click' => 0]);
}
?>

<!DOCTYPE html>

<html>

    <head>
        <meta charset="utf-8">
        <title>Arcadia, <?php echo $firstname_animal; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=0.60, maximum-scale=2.0, minimum-scale=0.60">        
        <meta name="description" content="Explorez la biodiversité extraordinaire du parc animalier Arcadia, un lieu magique abritant plusieurs habitats uniques. Plongez au cœur de la nature sauvage et découvrez des espèces fascinantes, de la faune endémique aux majestueux prédateurs. Rejoignez-nous pour une aventure inoubliable au sein d'Arcadia, où la préservation de la vie sauvage est notre engagement passionné.">
        <link rel="stylesheet" href="normalize.css">
        <link rel="stylesheet" href="style.css">
    </head>

    <body>

        <header>
            <a class="login" href="login.php">Connexion</a>
            <h1 class="title"><?php echo $firstname_animal; ?></h1>
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
            <img src="<?php echo $img_animal; ?>" alt="Image d'une créature du parc'" width="400" height="300">
            Prénom : <strong><?php echo ucfirst($firstname_animal); ?></strong><br><br> 
            Race : <strong><?php echo ucfirst($breed_animal); ?></strong><br><br> 
            Etat : <strong><?php echo ucfirst($state_animal); ?></strong><br><br> 
            Habitat : <strong><?php echo ucfirst($house_animal); ?></strong><br><br> 
            Dernier repas (Qté) : <strong><?php echo ucfirst($food_animal); ?></strong>(<strong><?php echo $weight_food_animal; ?>)<br><br>
        </p>

        <hr>

        <p style="margin: 60px; text-indent: 40px;">
            <?php echo $description_animal; ?>
            </p>

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
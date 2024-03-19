<?php
session_start();

$userId = $_SESSION['userId'];

$bdd = new PDO('sqlite:db.sqlite');
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$username = "SELECT firstname FROM users WHERE userId = :userId;";
$query = $bdd->prepare($username);
$query->bindValue(':userId', $userId, PDO::PARAM_INT);
$query->execute();
$user = $query->fetch(PDO::FETCH_ASSOC);
$firstname = htmlspecialchars($user['firstname']);

$user = "SELECT * FROM users;";
$resultUser = $bdd->query($user);

$service = "SELECT * FROM services;";
$resultService = $bdd->query($service);

$house = "SELECT * FROM housings;";
$resultHouse = $bdd->query($house);

$animal = "SELECT * FROM animals;";
$resultAnimal = $bdd->query($animal);

$open = "SELECT * FROM opening;";
$resultOpen = $bdd->query($open);

try {
// Connection MongoDB Database 
require 'vendor/autoload.php'; 

$client = new MongoDB\Client("mongodb://manu:vanEtlaura7@localhost:27017");
$database = $client->selectDatabase("animals_click"); 
$collection = $database->selectCollection("animals_click"); 
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
    exit;
}

$options = ['sort' => ['click' => -1], 'limit' => 4];
$cursor = $collection->find([], $options);
$ids = [];
foreach ($cursor as $document) {
    $ids[] = $document['id'];
}

$placeholders = str_repeat('?,', count($ids) - 1) . '?';
$animals = "SELECT * FROM animals WHERE id IN ($placeholders) ORDER BY FIELD(id, " . implode(',', $ids) . ")";
$stmtAnimal = $bdd->prepare($animals);
$stmtAnimal->execute($ids);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Arcadia, administration</title>
        <meta name="viewport" content="width=device-width, initial-scale=0.60, maximum-scale=2.0, minimum-scale=0.60">        
        <meta name="description" content="Explorez la biodiversité extraordinaire du parc animalier Arcadia, un lieu magique abritant plusieurs habitats uniques. Plongez au cœur de la nature sauvage et découvrez des espèces fascinantes, de la faune endémique aux majestueux prédateurs. Rejoignez-nous pour une aventure inoubliable au sein d'Arcadia, où la préservation de la vie sauvage est notre engagement passionné.">
        <link rel="stylesheet" href="normalize.css">
        <link rel="stylesheet" href="style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>

    <body>
        <header>
            <a class="login" href="index.php">Se deconnecter</a>
            <h1 class="title">Administration</h1>
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

        <section class="panel">
            <h2>Bienvenue <?php echo $firstname; ?> !</h2>

            <div class="favorite_animals">
                <h3>Animaux les plus consultés</h3>
                <?php
                if ($rowAnimal = $stmtAnimal->fetch(PDO::FETCH_ASSOC)) {
                    do {
                        $animal_img = $rowAnimal["slug"];
                        ?>
                        <div class="box">
                                <img src="<?php echo $animal_img; ?>" alt="Image d'un animal du parc'" width="200" height="200">
                        </div>
                        <?php
                    } while ($rowAnimal = $stmtAnimal->fetch(PDO::FETCH_ASSOC));
                } else {
                    echo "Aucun animal favorit trouvé.";
                }
                ?>
            </div>

            <div class="container">
                <h3>Gestion du personnel <span style="float:right"><button class="but_add">Ajouter du personnel</button></span></h3>
                <table class="makeEditable" style="table-layout: fixed; width: 100%;">
                    <colgroup>
                        <col style="width: 12%;">
                        <col style="width: 42%;">
                        <col style="width: 26%;">
                        <col style="width: 16%;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Prénom</th>
                            <th>eMail</th>
                            <th>Mot de passe</th>
                            <th>Poste</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $rowUser = $resultUser->fetch(PDO::FETCH_ASSOC);
                            if ($rowUser) {
                                do {
                                    $name = htmlspecialchars($rowUser["firstname"]);
                                    $mail = htmlspecialchars($rowUser["email"]);
                                    $pass = str_repeat('*', strlen($rowUser["password_hash"]));
                                    $id = htmlspecialchars($rowUser["id"]);

                                    $jobs = "SELECT label FROM roles WHERE userId = :userId";
                                    $users_job = $bdd->prepare($jobs);
                                    $users_job->bindValue(':userId', $id, PDO::PARAM_INT);
                                    $users_job->execute();
                                    $resultJob = $users_job->fetch(PDO::FETCH_ASSOC);
                                    $job = htmlspecialchars($resultJob['label']);

                        ?>
                            <tr>
                                <td><?php echo $name; ?></td>
                                <td><?php echo $mail; ?></td>
                                <td><?php echo $pass; ?></td>
                                <td><?php echo $job; ?></td>
                            </tr>
                        <?php
                            } while ($rowUser = $resultUser->fetch(PDO::FETCH_ASSOC));
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="container">
                <h3>Gestion des services <span style="float:right"><button class="but_add">Ajouter un service</button></span></h3>
                <table class="makeEditable" style="table-layout: fixed; width: 100%;">
                    <colgroup>
                        <col style="width: 12%;">
                        <col style="width: 42%;">
                        <col style="width: 42%;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Image</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $rowService = $resultService->fetch(PDO::FETCH_ASSOC);
                        if ($rowService) {
                            do {
                                $service_name = htmlspecialchars($rowService["name"]);
                                $service_description = htmlspecialchars($rowService["description"]);
                                $service_img = htmlspecialchars($rowService["slug"]);
                        ?>
                            <tr>
                                <td><?php echo $service_name; ?></td>
                                <td><?php echo $service_description; ?></td>
                                <td><?php echo $service_img; ?></td>
                            </tr>
                        <?php
                            } while ($rowService = $resultService->fetch(PDO::FETCH_ASSOC));
                        } else {
                            echo "<tr><td colspan='3'>Aucun service trouvé.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="container">
                <h3>Gestion des habitats <span style="float:right"><button class="but_add">Ajouter un habitat</button></span></h3>
                <table class="makeEditable" style="table-layout: fixed; width: 100%;">
                    <colgroup>
                        <col style="width: 12%;">
                        <col style="width: 42%;">
                        <col style="width: 42%;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Image</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $rowHouse = $resultHouse->fetch(PDO::FETCH_ASSOC);
                        if ($rowHouse) {
                            do {
                                $house_name = htmlspecialchars($rowHouse["name"]);
                                $house_description = isset($rowHouse["description"]) ? substr($rowHouse["description"], 0, 200) : '';
                                $house_img = htmlspecialchars($rowHouse["slug"]);
                        ?>
                            <tr>
                                <td><?php echo $house_name; ?></td>
                                <td><?php echo $house_description; ?></td>
                                <td><?php echo $house_img; ?></td>
                            </tr>
                        <?php
                            } while ($rowHouse = $resultHouse->fetch(PDO::FETCH_ASSOC));
                        } else {
                            echo "<tr><td colspan='4'>Aucun habitat trouvé.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="container">
                <h3>Gestion des animaux <span style="float:right"><button class="but_add">Ajouter un animal</button></span></h3>
                <table class="makeEditable" style="table-layout: fixed; width: 100%;">
                    <colgroup>
                        <col style="width: 7%;">
                        <col style="width: 7%;">
                        <col style="width: 7%;">
                        <col style="width: 18%;">
                        <col style="width: 52%;">
                        <col style="width: 5%;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Prénom</th>
                            <th>Race</th>
                            <th>Habitat</th>
                            <th>Image</th>
                            <th>Description</th>
                            <th>Visite</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $rowAnimal = $resultAnimal->fetch(PDO::FETCH_ASSOC);
                        if ($rowAnimal) {
                            do {
                                $animal_name = htmlspecialchars($rowAnimal["firstname"]);
                                $animal_breed = htmlspecialchars($rowAnimal["breed"]);
                                $animal_housing = htmlspecialchars($rowAnimal["housing"]);
                                $animal_img = substr($rowAnimal["slug"], 0, 30);
                                $animal_description = isset($rowAnimal["description"]) ? substr($rowAnimal["description"], 0, 200) : '';
                                $animal_id = $rowAnimal["id"];

                                $document = $collection->findOne(['id' => $animal_id]);
                                $animal_visit = $document ? $document['click'] : 0;
                    ?>
                            <tr>
                                <td><?php echo $animal_name; ?></td>
                                <td><?php echo $animal_breed; ?></td>
                                <td><?php echo $animal_housing; ?></td>
                                <td><?php echo $animal_img; ?></td>
                                <td><?php echo $animal_description; ?></td>
                                <td><?php echo $animal_visit; ?></td>
                            </tr>
                    <?php
                            } while ($rowAnimal = $resultAnimal->fetch(PDO::FETCH_ASSOC));
                        } else {
                            echo "<tr><td colspan='9'>Aucun animal trouvé.</td></tr>";
                        }
                    ?>
                    </tbody>
                </table>
            </div>

            <div class="container">
                <h3>Gestion des horaires d'ouverture <span style="float:right"><button class="but_add">Ajouter un jour</button></span></h3>
                <table class="makeEditable" style="table-layout: fixed; width: 100%;">
                    <colgroup>
                        <col style="width: 20%;">
                        <col style="width: 73%;">
                    <thead>
                        <tr>
                            <th>Jour</th>
                            <th>Horaires d'ouverture</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $openings = [];
                        $rowOpen = $resultOpen->fetch(PDO::FETCH_ASSOC);
                        if ($rowOpen) {
                            do {
                                $open_day = htmlspecialchars($rowOpen["day"]);
                                $open_hours = htmlspecialchars($rowOpen["hours"]);
                                $openings[] = ['day' => $open_day, 'hours' => $open_hours];
                        ?>
                            <tr>
                                <td><?php echo $open_day; ?></td>
                                <td><?php echo $open_hours; ?></td>
                            </tr>
                        <?php
                            } while ($rowOpen = $resultOpen->fetch(PDO::FETCH_ASSOC));
                        } else {
                            echo "<tr><td colspan='2'>Aucun horaire trouvé.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        </section>

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
                    if (!empty($openings)) {
                        foreach ($openings as $opening) {
                            $footer_day = $opening["day"];
                            $footer_hours = $opening["hours"];
                    ?>
                            <li><?php echo $footer_day; ?>: <?php echo $footer_hours; ?></li>
                    <?php
                        }
                    } else {
                        echo "<li>Aucun horaire d'ouverture trouvé.</li>";
                    }
                    ?>
                </ul>
            </div>
        </footer>

        <script src="tablePanel.js" defer></script>

    </body>
</html>
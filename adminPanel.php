<?php
$bdd = new PDO('sqlite:db.sqlite');
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$username = "José";

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
            <h2>Bienvenue <?php echo $username; ?> !</h2>

            <div class="container">
                <h3>Personnel <span style="float:right"><button class="but_add">Ajouter du personnel</button></span></h3>
                <table class="makeEditable">
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
                                $pass = htmlspecialchars($rowUser["password"]);
                                $job = htmlspecialchars($rowUser["job"]);
                        ?>
                            <tr>
                                <td><?php echo $name; ?></td>
                                <td><?php echo $mail; ?></td>
                                <td><?php echo $pass; ?></td>
                                <td><?php echo $job; ?></td>
                            </tr>
                        <?php
                            } while ($rowUser = $resultUser->fetch(PDO::FETCH_ASSOC));
                        } else {
                            echo "<tr><td colspan='3'>Aucun personnel trouvé.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="container">
                <h3>Services <span style="float:right"><button class="but_add">Ajouter un service</button></span></h3>
                <table class="makeEditable">
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
                <h3>Habitats <span style="float:right"><button class="but_add">Ajouter un habitat</button></span></h3>
                <table class="makeEditable">
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
                <h3>Animaux <span style="float:right"><button class="but_add">Ajouter un animal</button></span></h3>
                <table class="makeEditable">
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
                                $animal_visit = 0;
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
                <h3>Horaires d'ouverture <span style="float:right"><button class="but_add">Ajouter un jour</button></span></h3>
                <table class="makeEditable">
                    <thead>
                        <tr>
                            <th>Jour</th>
                            <th>Horaires d'ouverture</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $rowOpen = $resultOpen->fetch(PDO::FETCH_ASSOC);
                        if ($rowOpen) {
                            do {
                                $open_day = htmlspecialchars($rowOpen["day"]);
                                $open_hours = htmlspecialchars($rowOpen["hours"]);
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

        <script src="tablePanel.js" defer></script>

    </body>
</html>
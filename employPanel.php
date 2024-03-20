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

$com = "SELECT * FROM comments;";
$resultCom = $bdd->query($com);

$service = "SELECT * FROM services;";
$resultService = $bdd->query($service);

$open = "SELECT * FROM opening;";
$resultOpen = $bdd->query($open);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Arcadia, espace employé</title>
        <meta name="viewport" content="width=device-width, initial-scale=0.60, maximum-scale=2.0, minimum-scale=0.60">        
        <meta name="description" content="Explorez la biodiversité extraordinaire du parc animalier Arcadia, un lieu magique abritant plusieurs habitats uniques. Plongez au cœur de la nature sauvage et découvrez des espèces fascinantes, de la faune endémique aux majestueux prédateurs. Rejoignez-nous pour une aventure inoubliable au sein d'Arcadia, où la préservation de la vie sauvage est notre engagement passionné.">
        <link rel="stylesheet" href="normalize.css">
        <link rel="stylesheet" href="style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>

    <body>
        <header>
            <a class="login" href="index.php">Se deconnecter</a>
            <h1 class="title">Employé</h1>
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

        <div class="container">
                <h3>Gestion des avis clients</h3>
                <table class="makeEditable" style="table-layout: fixed; width: 100%;">
                    <colgroup>
                        <col style="width: 12%;">
                        <col style="width: 72%;">
                        <col style="width: 12%;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Pseudo</th>
                            <th>Message</th>
                            <th>Validation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $rowCom = $resultCom->fetch(PDO::FETCH_ASSOC);
                        if ($rowCom) {
                            do {
                                $com_pseudo = htmlspecialchars($rowCom["pseudo"]);
                                $com_message = htmlspecialchars($rowCom["message"]);
                                $com_validation = htmlspecialchars($rowCom["validate"]);
                        ?>
                            <tr>
                                <td><?php echo $com_pseudo; ?></td>
                                <td><?php echo $com_message; ?></td>
                                <td><?php echo $com_validation; ?></td>
                            </tr>
                        <?php
                            } while ($rowCom = $resultCom->fetch(PDO::FETCH_ASSOC));
                        } else {
                            echo "<tr><td colspan='4'>Aucun avis trouvé.</td></tr>";
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
                <h3>Gestion des comptes rendus sur un animal</h3>
                <table style="table-layout: fixed; width: 100%;">
                    <colgroup>
                        <col style="width: 20%;">
                        <col style="width: 14%;">
                        <col style="width: 14%;">
                        <col style="width: 14%;">
                        <col style="width: 14%;">
                        <col style="width: 14%;">
                        <col style="width: 10%;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Animal</th>
                            <th>Etat</th>
                            <th>Nourriture</th>
                            <th>Grammage</th>
                            <th>Date</th>
                            <th>Heure</th>
                            <th> </th>
                        </tr>
                    </thead>
                    <tbody>
                            <tr>
                                <td>
                                    <select name="selectmenu">
                                        <?php
                                        $resultAnimal = $bdd->query("SELECT * FROM animals");
                                        while ($animal = $resultAnimal->fetch(PDO::FETCH_ASSOC)) {
                                            $animal_name = htmlspecialchars($animal["firstname"]);
                                            echo "<option value=\"$animal_name\">$animal_name</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <form>
                                        <div>
                                            <input type="text" required name="text" placeholder="Se porte bien">
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    <form>
                                        <div>
                                            <input type="text" required name="text" placeholder="Poulet">
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    <form>
                                        <div>
                                            <input type="text" required name="text" placeholder="10 Kg">
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    <form>
                                        <div>
                                            <input type="text" required name="text" placeholder="1970/01/01">
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    <form>
                                        <div>
                                            <input type="text" required name="text" placeholder="00h00">
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    <form>
                                        <div>
                                            <input type="submit" value="Soumettre">
                                        </div>
                                    </form>
                                </td>
                            </tr>
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
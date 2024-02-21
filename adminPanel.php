<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connexion à la base de données SQLite
$bdd = new PDO('sqlite:db.sqlite');
// Activation du mode d'erreur PDO pour afficher les erreurs
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$username = "José";

// Requête SQL pour récupérer le personnel
$log = "SELECT * FROM connexion;";
$resultPers = $bdd->query($log);
// Requête SQL pour récupérer les services
$serv = "SELECT * FROM services;";
$resultServ = $bdd->query($serv);
// Requête SQL pour récupérer les habitats
$house = "SELECT * FROM habitat;";
$resultHous = $bdd->query($house);
// Requête SQL pour récupérer les animaux
$animals = "SELECT * FROM animaux;";
$resultAnim = $bdd->query($animals);
// Requête SQL pour récupérer les horaires
$sql = "SELECT * FROM horaires;";
$resultHours = $bdd->query($sql);
$result = $bdd->query($sql);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Arcadia, administration</title>
        <meta name="viewport" content="width=device-width, initial-scale=0.60, maximum-scale=2.0, minimum-scale=0.60">        
        <meta name="description" content="Explorez la biodiversité extraordinaire du parc animalier Arcadia, un lieu magique abritant plusieurs habitats uniques. Plongez au cœur de la nature sauvage et découvrez des espèces fascinantes, de la faune endémique aux majestueux prédateurs. Rejoignez-nous pour une aventure inoubliable au sein d'Arcadia, où la préservation de la vie sauvage est notre engagement passionné.">
        <link rel="stylesheet" href="Normalize.css">
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
                        // Affichage du personnel
                        $rowPers = $resultPers->fetch(PDO::FETCH_ASSOC);
                        if ($rowPers) {
                            do {
                                $persName = htmlspecialchars($rowPers["prénom"]);
                                $persMail = htmlspecialchars($rowPers["email"]);
                                $persPass = htmlspecialchars($rowPers["password"]);
                                $persType = htmlspecialchars($rowPers["type"]);
                        ?>
                            <tr>
                                <td><?php echo $persName; ?></td>
                                <td><?php echo $persMail; ?></td>
                                <td><?php echo $persPass; ?></td>
                                <td><?php echo $persType; ?></td>
                            </tr>
                        <?php
                            } while ($rowPers = $resultPers->fetch(PDO::FETCH_ASSOC));
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
                        // Affichage des services
                        $rowServ = $resultServ->fetch(PDO::FETCH_ASSOC);
                        if ($rowServ) {
                            do {
                                $servName = htmlspecialchars($rowServ["nom"]);
                                $servDesc = htmlspecialchars($rowServ["description"]);
                                $servImg = htmlspecialchars($rowServ["slug_img"]);
                        ?>
                            <tr>
                                <td><?php echo $servName; ?></td>
                                <td><?php echo $servDesc; ?></td>
                                <td><?php echo $servImg; ?></td>
                            </tr>
                        <?php
                            } while ($rowServ = $resultServ->fetch(PDO::FETCH_ASSOC));
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
                            <th>Seconde Description</th>
                            <th>Image</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Affichage des habitats
                        $rowHous = $resultHous->fetch(PDO::FETCH_ASSOC);
                        if ($rowHous) {
                            do {
                                $housName = htmlspecialchars($rowHous["nom"]);
                                $housDesc = isset($rowHous["description"]) ? substr($rowHous["description"], 0, 200) : '';
                                $housDesc2 = isset($rowHous["description"]) ? substr($rowHous["description2"], 0, 200) : '';
                                $housImg = htmlspecialchars($rowHous["slug_img"]);
                        ?>
                            <tr>
                                <td><?php echo $housName; ?></td>
                                <td><?php echo $housDesc; ?></td>
                                <td><?php echo $housDesc2; ?></td>
                                <td><?php echo $housImg; ?></td>
                            </tr>
                        <?php
                            } while ($rowHous = $resultHous->fetch(PDO::FETCH_ASSOC));
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
                            <th>Sexe</th>
                            <th>Habitat</th>
                            <th>Image</th>
                            <th>Description</th>
                            <th>Taille</th>
                            <th>Poids</th>
                            <th>Visite</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Affichage des animaux
                        $rowAnim = $resultAnim->fetch(PDO::FETCH_ASSOC);
                        if ($rowAnim) {
                            do {
                                $animName = htmlspecialchars($rowAnim["prénom"]);
                                $animRace = htmlspecialchars($rowAnim["race"]);
                                $animSexe = htmlspecialchars($rowAnim["sexe"]);
                                $animHous = htmlspecialchars($rowAnim["habitat"]);
                                $animImg = substr($rowAnim["slug_img"], 0, 30);
                                $animDesc = isset($rowAnim["description"]) ? substr($rowAnim["description"], 0, 200) : '';
                                $animSize = htmlspecialchars($rowAnim["taille"]);
                                $animWeight = htmlspecialchars($rowAnim["poids"]);
                                $animClick = htmlspecialchars($rowAnim["click"]);
                        ?>
                            <tr>
                                <td><?php echo $animName; ?></td>
                                <td><?php echo $animRace; ?></td>
                                <td><?php echo $animSexe; ?></td>
                                <td><?php echo $animHous; ?></td>
                                <td><?php echo $animImg; ?></td>
                                <td><?php echo $animDesc; ?></td>
                                <td><?php echo $animSize; ?></td>
                                <td><?php echo $animWeight; ?></td>
                                <td><?php echo $animClick; ?></td>
                            </tr>
                        <?php
                            } while ($rowAnim = $resultAnim->fetch(PDO::FETCH_ASSOC));
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
                        // Affichage des horaires
                        $rowHours = $resultHours->fetch(PDO::FETCH_ASSOC);
                        if ($rowHours) {
                            do {
                                $dayOpen = htmlspecialchars($rowHours["jour"]);
                                $hoursOpen = htmlspecialchars($rowHours["heures"]);
                        ?>
                            <tr>
                                <td><?php echo $dayOpen; ?></td>
                                <td><?php echo $hoursOpen; ?></td>
                            </tr>
                        <?php
                            } while ($rowHours = $resultHours->fetch(PDO::FETCH_ASSOC));
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
                    $row = $result->fetch(PDO::FETCH_ASSOC);
                    if ($row) {
                        do {
                            $openDay = htmlspecialchars($row["jour"]);
                            $openHours = htmlspecialchars($row["heures"]);
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

        <script src="tablePanel.js" defer></script>

    </body>
</html>
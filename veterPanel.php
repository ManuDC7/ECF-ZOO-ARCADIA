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

$open = "SELECT * FROM opening;";
$resultOpen = $bdd->query($open);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Arcadia, espace vétérinaire</title>
        <meta name="viewport" content="width=device-width, initial-scale=0.60, maximum-scale=2.0, minimum-scale=0.60">        
        <meta name="description" content="Explorez la biodiversité extraordinaire du parc animalier Arcadia, un lieu magique abritant plusieurs habitats uniques. Plongez au cœur de la nature sauvage et découvrez des espèces fascinantes, de la faune endémique aux majestueux prédateurs. Rejoignez-nous pour une aventure inoubliable au sein d'Arcadia, où la préservation de la vie sauvage est notre engagement passionné.">
        <link rel="stylesheet" href="normalize.css">
        <link rel="stylesheet" href="style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>

    <body>
        <header>
            <a class="login" href="index.php">Se deconnecter</a>
            <h1 class="title">Vétérinaire</h1>
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
                <h3>Soumettre un compte rendu sur un animal</h3>
                <table style="table-layout: fixed; width: 100%;">
                    <colgroup>
                        <col style="width: 21%;">
                        <col style="width: 14%;">
                        <col style="width: 14%;">
                        <col style="width: 14%;">
                        <col style="width: 14%;">
                        <col style="width: 14%;">
                        <col style="width: 9%;">
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
                                            echo "<option value=\"$animal_name\">". ucfirst($animal_name) ."</option>";
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
                                            <input type="text" required name="text" placeholder="01/01/1970">
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

            <div class="container">
                <h3>Soumettre un compte rendu sur un habitat</h3>
                <table style="table-layout: fixed; width: 100%;">
                    <colgroup>
                        <col style="width: 26%;">
                        <col style="width: 64%;">
                        <col style="width: 10%;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Habitat</th>
                            <th>Compte rendu</th>
                            <th> </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="selectmenu">
                                    <?php
                                    $resultHouse = $bdd->query("SELECT * FROM housings");
                                    while ($housing = $resultHouse->fetch(PDO::FETCH_ASSOC)) {
                                        $housing_name = htmlspecialchars($housing["name"]);
                                        echo "<option>". ucfirst($housing_name) ."</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <form>
                                    <div>
                                        <input type="text" required name="text" placeholder="Compte rendu détaillé">
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

            <div class="container">
                <h3>Gestion des comptes rendus des animaux</h3>
                <table style="table-layout: fixed; width: 100%;">
                    <colgroup>
                        <col style="width: 20%;">
                        <col style="width: 20%;">
                        <col style="width: 60%;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Animal</th>
                            <th>Date</th>
                            <th>Compte rendu</th>
                        </tr>
                    </thead>
                    <tbody>
                            <tr>
                                <td>
                                    <select id="animal-select" name="selectmenu">
                                    <?php
                                    $resultAnimals = $bdd->query("SELECT * FROM animals");
                                    while ($animal = $resultAnimals->fetch(PDO::FETCH_ASSOC)) {
                                        $animal_id = htmlspecialchars($animal["id"]);
                                        $animal_name = htmlspecialchars($animal["firstname"]);
                                        echo "<option value=\"$animal_id\">" . ucfirst($animal_name) . "</option>";
                                    }
                                    ?>
                                    </select>
                                </td>
                                <td>
                                <select id="date-select" name="selectmenu">
                                    <option value=""> </option>
                                </select>
                                </td>
                                <td>
                                    <input id="report-field" type="text" value="Sélectionnez un animal et une date" readonly>
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

        <script src="selectMenu.js" defer></script>
        <script>
        $(document).ready(function() {
            $('#date-select').change(function() {
                var selectedDate = $(this).val();
                var animalId = $('#animal-select').val(); 
                if (selectedDate && animalId) {
                    $.get('date_report.php', { date: selectedDate, animal_id: animalId }, function(data) {
                        if (data.error) {
                            $('#report-field').val(data.error);
                        } else {
                            $('#report-field').val(data.content);
                        }
                    });
                } else {
                    $('#report-field').val('Aucune date de compte rendu ou animal sélectionné.');
                }
            });
        });
        </script>

    </body>
</html>
<?php
session_start();

$userId = $_SESSION['userId'];

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$host = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_NAME'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];

$bdd = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$username = "SELECT firstname FROM users WHERE userId = :userId;";
$query = $bdd->prepare($username);
$query->bindValue(':userId', $userId, PDO::PARAM_INT);
$query->execute();
$user = $query->fetch(PDO::FETCH_ASSOC);
$firstname = htmlspecialchars($user['firstname']);

$open = "SELECT * FROM opening;";
$resultOpen = $bdd->query($open);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['firstFormInput'])) {
    $animal = strtolower($_POST["animal"]);

    $state = htmlspecialchars($_POST["state"], ENT_QUOTES, 'UTF-8');
    $food = htmlspecialchars($_POST["food"], ENT_QUOTES, 'UTF-8');
    $weight = htmlspecialchars($_POST["weight"], ENT_QUOTES, 'UTF-8');
    $date = htmlspecialchars($_POST["date"], ENT_QUOTES, 'UTF-8');
    $hours = htmlspecialchars($_POST["hours"], ENT_QUOTES, 'UTF-8');

    $animal_id_query = "SELECT id FROM animals WHERE firstname = :animal;";
    $stmt = $bdd->prepare($animal_id_query);
    $stmt->bindValue(':animal', $animal);
    $stmt->execute();
    $animal_id = $stmt->fetchColumn();

    $sql = "INSERT INTO foods (state, food, weight, date, hours, animal_id) VALUES (:state, :food, :weight, :date, :hours, :animal_id)";

    $stmt = $bdd->prepare($sql);
    $stmt->bindValue(':animal_id', $animal_id);
    $stmt->bindValue(':state', $state);
    $stmt->bindValue(':food', $food);
    $stmt->bindValue(':weight', $weight);
    $stmt->bindValue(':date', $date);
    $stmt->bindValue(':hours', $hours);
    $stmt->execute();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['secondFormInput'])) {
    $habitat = strtolower($_POST["selectmenu"]);
    $report = htmlspecialchars($_POST["text"], ENT_QUOTES, 'UTF-8');

    $sql = "UPDATE housings SET comments = :report WHERE name = :habitat";

    $stmt = $bdd->prepare($sql);
    $stmt->bindValue(':habitat', $habitat);
    $stmt->bindValue(':report', $report);
    $stmt->execute();
}

if (isset($_GET['id'])) {
    $animalId = $_GET['id'];
    $result = $bdd->query("SELECT * FROM foods WHERE animal_id = $animalId ORDER BY id DESC LIMIT 1");
    $report = $result->fetch(PDO::FETCH_ASSOC);
    echo json_encode($report); 
    exit;
}
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
                <form action="veterPanel.php" method="post">
                    <input type="hidden" name="firstFormInput" value="1">
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
                                <select name="animal" required>
                                        <option value="" disabled selected>Sélectionnez</option>
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
                                    <div>
                                        <input type="text" name="state" placeholder="Se porte bien">
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <input type="text" name="food" placeholder="Poulet">
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <input type="text" name="weight" placeholder="10 Kg">
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <input type="text" name="date" placeholder="1970/01/01">
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <input type="text" required name="hours" placeholder="00h00">
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <input type="submit" value="Soumettre">
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>

            <div class="container">
                <h3>Soumettre un compte rendu sur un habitat</h3>
                <form action="veterPanel.php" method="post">
                    <input type="hidden" name="secondFormInput" value="2">
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
                                    <select name="selectmenu" required>
                                        <option value="" disabled selected>Sélectionnez</option>
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
                                    <div>
                                        <input type="text" required name="text" placeholder="Compte rendu détaillé">
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <input type="submit" value="Soumettre">
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>

            <div class="container">
                <h3>Gestion des comptes rendus des animaux</h3>
                <table style="table-layout: fixed; width: 100%;">
                    <colgroup>
                        <col style="width: 20%;">
                        <col style="width: 20%;">
                        <col style="width: 20%;">
                        <col style="width: 20%;">
                        <col style="width: 20%;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Animal</th>
                            <th>Nourriture</th>
                            <th>Grammage</th>
                            <th>Date</th>
                            <th>Heure</th>
                        </tr>
                    </thead>
                    <tbody>
                            <tr>
                                <td>
                                    <select id="animal-select" name="selectmenu"> required>
                                        <option value="" disabled selected>Sélectionnez</option>
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
                                    <input id="report-field-food" type="text" value="Nourriture" readonly>
                                </td>
                                <td>
                                    <input id="report-field-weight" type="text" value="Grammage" readonly>
                                </td>
                                <td>
                                    <input id="report-field-date" type="text" value="Date" readonly>
                                </td>
                                <td>
                                    <input id="report-field-hour" type="text" value="Heure" readonly>
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

        <script>
        document.querySelector('#animal-select').addEventListener('change', function() {
            let animalId = this.value;
            fetch('veterPanel.php?id=' + animalId)
                .then(response => response.json())
                .then(data => {
                    document.querySelector('#report-field-food').value = data.food;
                    document.querySelector('#report-field-weight').value = data.weight;
                    document.querySelector('#report-field-date').value = data.date;
                    document.querySelector('#report-field-hour').value = data.hours;
                })
                .catch(error => console.error('Error:', error));
        });
        </script>

    </body>
</html>
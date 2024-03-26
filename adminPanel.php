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
    
    $orderClause = 'CASE id ';
    foreach ($ids as $index => $id) {
        $orderClause .= sprintf('WHEN %d THEN %d ', $id, $index);
    }
    $orderClause .= 'END';
    
    $placeholders = str_repeat('?,', count($ids) - 1) . '?';
    $animals = "SELECT * FROM animals WHERE id IN ($placeholders) ORDER BY $orderClause";
    $stmtAnimal = $bdd->prepare($animals);
    $stmtAnimal->execute($ids);

if (isset($_POST['form_name']) && $_POST['form_name'] == 'addUsersForm') {
    $nom = htmlspecialchars($_POST['name']);
    $mail = htmlspecialchars($_POST['email']);
    $pass = $_POST['pass'];
    $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);  
    $job = $_POST['selectmenu'];
    
    $stmt = $bdd->prepare("INSERT INTO users (firstname, email, password_hash) VALUES (:nom, :mail, :hashed_pass)");
    $stmt->bindValue(':nom', $nom);
    $stmt->bindValue(':mail', $mail); 
    $stmt->bindValue(':hashed_pass', $hashed_pass);
    $stmt->execute();

    $id = $bdd->lastInsertId();

    $stmtjob = $bdd->prepare("INSERT INTO roles (label, userId) VALUES (:job, :id)");
    $stmtjob->bindValue(':job', $job);
    $stmtjob->bindValue(':id', $id);
    $stmtjob->execute();

    $to = "$mail"; 
    $subject = "Votre inscriptipn sur Arcadia";
    $headers = "From: secog38972@irnini.com";
    $message = "Votre inscrition a été enregistré !\r\nVotre nom d'utilisateur est $mail.\r\nVeuillez vous rapprocher du directeur pour obtenir votre mot de passe.";

    if (mail($to, $subject, $message, $headers)) {
        echo "Email envoyé avec succès à $to.";
    } else {
        echo "L'envoi de l'e-mail a échoué.";
    }
}

if (isset($_POST['form_name']) && $_POST['form_name'] == 'editUsersForm') {
    $nom = htmlspecialchars($_POST['name']);
    $mail = htmlspecialchars($_POST['email']);
    $job = $_POST['selectmenu'];
    $stmtId = $bdd->prepare("SELECT userId FROM users WHERE email = :mail");
    $stmtId->bindValue(':mail', $mail);
    $stmtId->execute();

    $user = $stmtId->fetch();
    if ($user) {
        $userId = $user['userId'];

        $stmt = $bdd->prepare("UPDATE users SET firstname = :nom, email = :mail WHERE userId = :userId");
        $stmt->bindValue(':nom', $nom);
        $stmt->bindValue(':mail', $mail);
        $stmt->bindValue(':userId', $userId);
        $stmt->execute();

        $stmtjob = $bdd->prepare("UPDATE roles SET label = :job WHERE userId = :userId");
        $stmtjob->bindValue(':job', $job);
        $stmtjob->bindValue(':userId', $userId);
        $stmtjob->execute();
    }
}

if (isset($_POST['mail'])) {
    $mail = $_POST['mail'];
    $stmtId = $bdd->prepare("SELECT userID FROM users WHERE email = :mail");
    $stmtId->bindValue(':mail', $mail);
    $stmtId->execute();

    $user = $stmtId->fetch();
    if ($user) {
        $userId = $user['userId'];

        $stmtjob = $bdd->prepare("DELETE FROM roles WHERE userId = :userId");
        $stmtjob->bindValue(':userId', $userId);
        $stmtjob->execute();

        $sql = "DELETE FROM users WHERE email = :mail";
        $stmt = $bdd->prepare($sql);
        $stmt->bindValue(':mail', $mail);
        $stmt->execute();
    }
}

if (isset($_POST['form_name']) && $_POST['form_name'] == 'addServiceForm') {
    $nom = $_POST['Nom'];
    $description = $_POST['Description'];
    $url = $_POST['imageURL'];
    
    $stmt = $bdd->prepare("INSERT INTO services (name, description, slug) VALUES (:nom, :description, :url)");
    $stmt->bindValue(':nom', $nom);
    $stmt->bindValue(':description', $description);
    $stmt->bindValue(':url', $url);
    $stmt->execute();
}

if (isset($_POST['form_edit']) && $_POST['form_edit'] == 'editServiceForm') {
    $nom = $_POST['Nom'];
    $description = $_POST['Description'];
    $url = $_POST['imageURL'];
    $service_id = $_POST['service_id'];
    
    $stmt = $bdd->prepare("UPDATE services SET name = :nom, description = :description, slug = :url WHERE id = :service_id");
    $stmt->bindValue(':nom', $nom);
    $stmt->bindValue(':description', $description);
    $stmt->bindValue(':url', $url);
    $stmt->bindValue(':service_id', $service_id);
    $stmt->execute();
}

if (isset($_POST['service_name'])) {
    $service_name = $_POST['service_name'];

    $sql = "DELETE FROM services WHERE name = :service_name";
    $stmt = $bdd->prepare($sql);
    $stmt->bindValue(':service_name', $service_name);
    $stmt->execute();
}

if (isset($_POST['form_name']) && $_POST['form_name'] == 'addOpenForm') {
    $day = $_POST['Day'];
    $hours = $_POST['Hours'];
    
    $stmt = $bdd->prepare("INSERT INTO opening (day, hours) VALUES (:day, :hours)");
    $stmt->bindValue(':day', $day);
    $stmt->bindValue(':hours', $hours);
    $stmt->execute();
}

if (isset($_POST['form_edit']) && $_POST['form_edit'] == 'editOpenForm') {
    $day = $_POST['Day'];
    $hours = $_POST['Hours'];
    
    $stmt = $bdd->prepare("UPDATE opening SET day = :day, hours = :hours WHERE day = :day");
    $stmt->bindValue(':day', $day);
    $stmt->bindValue(':hours', $hours);
    $stmt->execute();
}

if (isset($_POST['open_id'])) {
    $open_id = $_POST['open_id'];

    $sql = "DELETE FROM opening WHERE id = :open_id";
    $stmt = $bdd->prepare($sql);
    $stmt->bindValue(':open_id', $open_id);
    $stmt->execute();
}

if (isset($_POST['form_name']) && $_POST['form_name'] == 'addHouseForm') {
    $nom = $_POST['Nom'];
    $description = $_POST['Description'];
    $url = $_POST['imageURL'];
    
    $stmt = $bdd->prepare("INSERT INTO housings (name, description, slug) VALUES (:nom, :description, :url)");
    $stmt->bindValue(':nom', $nom);
    $stmt->bindValue(':description', $description);
    $stmt->bindValue(':url', $url);
    $stmt->execute();
}

if (isset($_POST['form_edit']) && $_POST['form_edit'] == 'editHouseForm') {
    $nom = $_POST['Nom'];
    $description = $_POST['Description'];
    $url = $_POST['imageURL'];
    $house_id = $_POST['house_id'];
    
    $stmt = $bdd->prepare("UPDATE housings SET name = :nom, description = :description, slug = :url WHERE id = :house_id");
    $stmt->bindValue(':nom', $nom);
    $stmt->bindValue(':description', $description);
    $stmt->bindValue(':url', $url);
    $stmt->bindValue(':house_id', $house_id);
    $stmt->execute();
}

if (isset($_POST['house_name'])) {
    $house_name = $_POST['house_name'];

    $sql = "DELETE FROM housings WHERE name = :house_name";
    $stmt = $bdd->prepare($sql);
    $stmt->bindValue(':house_name', $house_name);
    $stmt->execute();
}
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
                <h3>Gestion du personnel <span style="float:right"><button class="users_add">Ajouter du personnel</button></span></h3>
                <table style="table-layout: fixed; width: 100%;">
                    <colgroup>
                        <col style="width: 15%;">
                        <col style="width: 42%;">
                        <col style="width: 29%;">
                        <col style="width: 10%;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Prénom</th>
                            <th>eMail</th>
                            <th>Poste</th>
                            <th> </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $rowUser = $resultUser->fetch(PDO::FETCH_ASSOC);
                            if ($rowUser) {
                                do {
                                    $name = htmlspecialchars($rowUser["firstname"]);
                                    $mail = htmlspecialchars($rowUser["email"]);
                                    $id = $rowUser["userId"];

                                    $jobs = "SELECT label FROM roles WHERE userId = :userId";
                                    $users_job = $bdd->prepare($jobs);
                                    $users_job->bindValue(':userId', $id, PDO::PARAM_INT);
                                    $users_job->execute();
                                    $resultJob = $users_job->fetch(PDO::FETCH_ASSOC);
                                    $job = htmlspecialchars($resultJob['label']);

                                    if ($job == "Administrator") {
                                        continue;
                                    }
                                    if ($job == "Veterinarian") {
                                        $job = "Vétérinaire";
                                    }
                                    
                                    if ($job == "Employee") {
                                        $job = "Employé";
                                    }

                        ?>
                            <tr>
                                <td><?php echo $name; ?></td>
                                <td><?php echo $mail; ?></td>
                                <td><?php echo $job; ?></td>
                                <td style="text-align: center;">
                                    <button class="users_edit" data-users_id="<?php echo $id; ?>">✏️</button>
                                    <button class="users_delete" data-mail="<?php echo $mail; ?>">🗑️</button>
                                </td>
                            </tr>
                        <?php
                            } while ($rowUser = $resultUser->fetch(PDO::FETCH_ASSOC));
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="container">
                <h3>Gestion des services <span style="float:right"><button class="serv_add">Ajouter un service</button></span></h3>
                <table style="table-layout: fixed; width: 100%;">
                    <colgroup>
                        <col style="width: 10%;">
                        <col style="width: 40%;">
                        <col style="width: 40%;">
                        <col style="width: 10%;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Image</th>
                            <th> </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $rowService = $resultService->fetch(PDO::FETCH_ASSOC);
                        if ($rowService) {
                            do {
                                $service_id = $rowService["id"];
                                $service_name = htmlspecialchars($rowService["name"]);
                                $service_description = htmlspecialchars($rowService["description"]);
                                $service_img = htmlspecialchars($rowService["slug"]);
                        ?>
                            <tr>
                                <td><?php echo $service_name; ?></td>
                                <td><?php echo $service_description; ?></td>
                                <td><?php echo $service_img; ?></td>
                                <td style="text-align: center;">
                                    <button class="serv_edit" data-service_id="<?php echo $service_id; ?>">✏️</button>
                                    <button class="serv_delete" data-service_name="<?php echo $service_name; ?>">🗑️</button>
                                </td>
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
                <h3>Gestion des habitats <span style="float:right"><button class="house_add">Ajouter un habitat</button></span></h3>
                <table style="table-layout: fixed; width: 100%;">
                    <colgroup>
                        <col style="width: 10%;">
                        <col style="width: 40%;">
                        <col style="width: 40%;">
                        <col style="width: 10%;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Image</th>
                            <th> </th>
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
                                $house_id = $rowHouse["id"];
                        ?>
                            <tr>
                                <td><?php echo $house_name; ?></td>
                                <td><?php echo $house_description; ?></td>
                                <td><?php echo $house_img; ?></td>
                                <td style="text-align: center;">
                                    <button class="house_edit" data-house_id="<?php echo $house_id; ?>">✏️</button>
                                    <button class="house_delete" data-house_name="<?php echo $house_name; ?>">🗑️</button>
                                </td>
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
                <h3>Gestion des horaires d'ouverture <span style="float:right"><button class="open_add">Ajouter un jour</button></span></h3>
                <table style="table-layout: fixed; width: 100%;">
                    <colgroup>
                        <col style="width: 20%;">
                        <col style="width: 70%;">
                        <col style="width: 10%;">
                    <thead>
                        <tr>
                            <th>Jour</th>
                            <th>Horaires d'ouverture</th>
                            <th> </th>
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
                                $open_id = $rowOpen["id"];
                                $openings[] = ['day' => $open_day, 'hours' => $open_hours];
                        ?>
                            <tr>
                                <td><?php echo $open_day; ?></td>
                                <td><?php echo $open_hours; ?></td>
                                <td style="text-align: center;">
                                    <button class="open_edit" data-open_day="<?php echo $open_day; ?>">✏️</button>
                                    <button class="open_delete" data-open_id="<?php echo $open_id; ?>">🗑️</button>
                                </td>
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

        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <form class="addUsersForm">
                    <input type="hidden" name="form_name" value="addUsersForm">
                    <input type="text" name="name" required placeholder="Le prénom de l'employé">
                    <input type="text" name="email" required placeholder="Son email">
                    <input type="text" name="pass" required placeholder="Mot de passe provisoire">
                    <select name="selectmenu" required>
                        <option value="" disabled selected>Sélectionnez</option>
                        <option value="Veterinarian">Vétérinaire</option>
                        <option value="Employee">Employé</option>
                    </select>
                    <input type="submit" value="Ajouter">
                </form>
            </div>
        </div>

        <div id="myModal2" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <form class="editUsersForm">
                    <input type="hidden" name="form_name" value="editUsersForm">
                    <input type="text" name="name" required value="<?php echo $name; ?>" onFocus="this.value=''">
                    <input type="text" name="email" required value="<?php echo $mail ?>" onFocus="this.value=''">
                    <select name="selectmenu" required>
                        <option value="" disabled selected>Sélectionnez</option>
                        <option value="Veterinarian">Vétérinaire</option>
                        <option value="Employee">Employé</option>
                    </select>
                    <input type="submit" value="Modifier">
                </form>
            </div>
        </div>

        <div id="myModal3" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <form class="addServiceForm">
                    <input type="hidden" name="form_name" value="addServiceForm">
                    <input type="text" name="Nom" required placeholder="Le nom du service">
                    <input type="text" name="Description" required placeholder="La description - maximun 255 caractère">
                    <input type="text" name="imageURL" required placeholder="https://maximum255caracteres.fr">
                    <input type="submit" value="Ajouter">
                </form>
            </div>
        </div>

        <div id="myModal4" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <form class="editServiceForm">
                    <input type="hidden" name="form_edit" value="editServiceForm">
                    <input type="text" name="Nom" required value="<? echo $service_name ?>" onFocus="this.value=''">
                    <input type="text" name="Description" required value="<? echo $service_description ?>" onFocus="this.value=''">
                    <input type="text" name="imageURL" required value="<? echo $service_img ?>" onFocus="this.value=''">
                    <input type="submit" value="Modifier">
                </form>
            </div>
        </div>

        <div id="myModal5" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <form class="addOpenForm">
                    <input type="hidden" name="form_name" value="addOpenForm">
                    <input type="text" name="Day" required placeholder="lundi">
                    <input type="text" name="Hours" required placeholder="10H00 - 18H00">
                    <input type="submit" value="Ajouter">
                </form>
            </div>
        </div>

        <div id="myModal6" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <form class="editOpenForm">
                    <input type="hidden" name="form_edit" value="editOpenForm">
                    <input type="text" name="Day" required value="<? echo $open_day ?>" onFocus="this.value=''">
                    <input type="text" name="Hours" required value="<? echo $open_hours ?>" onFocus="this.value=''">
                    <input type="submit" value="Modifier">
                </form>
            </div>
        </div>

        <div id="myModal7" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <form class="addHouseForm">
                    <input type="hidden" name="form_name" value="addHouseForm">
                    <input type="text" name="Nom" required placeholder="Le nom du l'habitat">
                    <input type="text" name="Description" required placeholder="La description - maximun 255 caractère">
                    <input type="text" name="imageURL" required placeholder="https://maximum255caracteres.fr">
                    <input type="submit" value="Ajouter">
                </form>
            </div>
        </div>

        <div id="myModal8" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <form class="editHouseForm">
                    <input type="hidden" name="form_edit" value="editHouseForm">
                    <input type="text" name="Nom" required value="<? echo $house_name ?>" onFocus="this.value=''">
                    <input type="text" name="Description" required value="<? echo $house_description ?>" onFocus="this.value=''">
                    <input type="text" name="imageURL" required value="<? echo $house_img ?>" onFocus="this.value=''">
                    <input type="submit" value="Modifier">
                </form>
            </div>
        </div>

        <script>
        $(document).ready(function(){
            var modal = document.getElementById("myModal");
            var span = document.getElementsByClassName("close")[0];

            $(".users_add").click(function(){
                modal.style.display = "block";
            });

            span.onclick = function() {
            modal.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }

            $(".addUsersForm").submit(function(e){
                e.preventDefault(); 

                $.ajax({
                    url: 'adminPanel.php',
                    type: 'post',
                    data: $(this).serialize(), 
                    success: function(response) {
                        console.log(response);
                        alert("L'utilisateur à bien été ajouté !");
                        modal.style.display = "none";
                        $(".addUsersForm")[0].reset(); 
                    }
                });
            });
        });

        $(".users_delete").click(function(){
            if (confirm("Êtes-vous sûr de vouloir supprimer l'utilisateur ?")) {
                var mail = $(this).data('mail');
                console.log(mail);
                $.ajax({
                    url: 'adminPanel.php',
                    type: 'post',
                    data: {mail: mail},
                });
                $(this).closest("tr").remove();
            } else {
                // Si l'utilisateur clique sur Annuler, ne rien faire
            }
        });

        var $form = $('.editUsersForm');
        var $modal = $('#myModal2');

        $('.users_edit').click(function() {
            var $row = $(this).closest('tr');
            var name = $row.find('td:eq(0)').text();
            var mail = $row.find('td:eq(1)').text();

            $form.find('input[name="name"]').val(name);
            $form.find('input[name="email"]').val(mail);

            $modal.show();
        });

        $('.close').click(function() {
            $modal.hide();
        });

        $form.on('submit', function(e) {
            e.preventDefault();

            var url = $form.attr('action');

            $.ajax({
                type: "POST",
                url: url,
                data: $form.serialize(),
                success: function(data)
                {
                    alert('Les données ont été modifiés avec succès.'); 
                    $modal.hide(); 
                },
                error: function()
                {
                    alert('Une erreur est survenue lors de l\'envoi des données.');
                }
            });
        });

        $(document).ready(function(){
            var modal = document.getElementById("myModal3");
            var span = document.getElementsByClassName("close")[0];

            $(".serv_add").click(function(){
                modal.style.display = "block";
            });

            span.onclick = function() {
            modal.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }

            $(".addServiceForm").submit(function(e){
                e.preventDefault(); 

                $.ajax({
                    url: 'adminPanel.php',
                    type: 'post',
                    data: $(this).serialize(), 
                    success: function(response) {
                        console.log(response);
                        alert("Le service à bien été ajouté !");
                        modal.style.display = "none";
                        $(".addServiceForm")[0].reset(); 
                    }
                });
            });
        });

        $(".serv_delete").click(function(){
            if (confirm("Êtes-vous sûr de vouloir supprimer la ligne entière ?")) {
                var service_name = $(this).data('service_name');
                console.log(service_name);
                $.ajax({
                    url: 'adminPanel.php',
                    type: 'post',
                    data: {service_name: service_name},
                });
                $(this).closest("tr").remove();
            } else {
                // Si l'utilisateur clique sur Annuler, ne rien faire
            }
        });

        var $modal = $('#myModal4');
        var $form = $('.editServiceForm');
        var service_id;

        $('.serv_edit').click(function() {
            service_id = $(this).data('service_id');
            var $row = $(this).closest('tr');
            var name = $row.find('td:eq(0)').text();
            var description = $row.find('td:eq(1)').text();
            var img = $row.find('td:eq(2)').text();

            $form.find('input[name="Nom"]').val(name);
            $form.find('input[name="Description"]').val(description);
            $form.find('input[name="imageURL"]').val(img);

            $modal.show();
        });

        $('.close').click(function() {
            $modal.hide();
        });

        $form.on('submit', function(e) {
            e.preventDefault();

            var url = $form.attr('action');

            $.ajax({
                type: "POST",
                url: url,
                data: $form.serialize() + "&service_id=" + service_id,
                success: function(data)
                {
                    alert('Les données ont été modifiés avec succès.'); 
                    $modal.hide(); 
                },
                error: function()
                {
                    alert('Une erreur est survenue lors de l\'envoi des données.');
                }
            });
        });

        $(document).ready(function(){
            var modal = document.getElementById("myModal5");
            var span = document.getElementsByClassName("close")[0];

            $(".open_add").click(function(){
                modal.style.display = "block";
            });

            span.onclick = function() {
            modal.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }

            $(".addOpenForm").submit(function(e){
                e.preventDefault(); 

                $.ajax({
                    url: 'adminPanel.php',
                    type: 'post',
                    data: $(this).serialize(), 
                    success: function(response) {
                        console.log(response);
                        alert("Le jour à bien été ajouté !");
                        modal.style.display = "none";
                        $(".addOpenForm")[0].reset(); 
                    }
                });
            });
        });

        $(".open_delete").click(function(){
            if (confirm("Êtes-vous sûr de vouloir supprimer la ligne entière ?")) {
                var open_id = $(this).data('open_id');
                console.log(open_id);
                $.ajax({
                    url: 'adminPanel.php',
                    type: 'post',
                    data: {open_id: open_id},
                });
                $(this).closest("tr").remove();
            } else {
                // Si l'utilisateur clique sur Annuler, ne rien faire
            }
        });

        var $modal = $('#myModal6');
        var $form = $('.editOpenForm');
        var open_day;

        $('.open_edit').click(function() {
            open_day = $(this).data('open_day');
            var $row = $(this).closest('tr');
            var day = $row.find('td:eq(0)').text();
            var hours = $row.find('td:eq(1)').text();

            $form.find('input[name="Day"]').val(day);
            $form.find('input[name="Hours"]').val(hours);

            $modal.show();
        });

        $('.close').click(function() {
            $modal.hide();
        });

        $form.on('submit', function(e) {
            e.preventDefault();

            var url = $form.attr('action');

            $.ajax({
                type: "POST",
                url: url,
                data: $form.serialize() + "&open_day=" + open_day,
                success: function(data)
                {
                    alert('Les données ont été modifiés avec succès.'); 
                    $modal.hide(); 
                },
                error: function()
                {
                    alert('Une erreur est survenue lors de l\'envoi des données.');
                }
            });
        });

        //HOUSINGS
        $(document).ready(function(){
            var modal = document.getElementById("myModal7");
            var span = document.getElementsByClassName("close")[0];

            $(".house_add").click(function(){
                modal.style.display = "block";
            });

            span.onclick = function() {
            modal.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }

            $(".addHouseForm").submit(function(e){
                e.preventDefault(); 

                $.ajax({
                    url: 'adminPanel.php',
                    type: 'post',
                    data: $(this).serialize(), 
                    success: function(response) {
                        console.log(response);
                        alert("L'habitat à bien été ajouté !");
                        modal.style.display = "none";
                        $(".addHouseForm")[0].reset(); 
                    }
                });
            });
        });

        $(".house_delete").click(function(){
            if (confirm("Êtes-vous sûr de vouloir supprimer la ligne entière ?")) {
                var house_name = $(this).data('house_name');
                console.log(house_name);
                $.ajax({
                    url: 'adminPanel.php',
                    type: 'post',
                    data: {house_name: house_name},
                });
                $(this).closest("tr").remove();
            } else {
                // Si l'utilisateur clique sur Annuler, ne rien faire
            }
        });

        var $modal = $('#myModal8');
        var $form = $('.editHouseForm');
        var house_id;

        $('.house_edit').click(function() {
            house_id = $(this).data('house_id');
            var $row = $(this).closest('tr');
            var name = $row.find('td:eq(0)').text();
            var description = $row.find('td:eq(1)').text();
            var img = $row.find('td:eq(2)').text();

            $form.find('input[name="Nom"]').val(name);
            $form.find('input[name="Description"]').val(description);
            $form.find('input[name="imageURL"]').val(img);

            $modal.show();
        });

        $('.close').click(function() {
            $modal.hide();
        });

        $form.on('submit', function(e) {
            e.preventDefault();

            var url = $form.attr('action');

            $.ajax({
                type: "POST",
                url: url,
                data: $form.serialize() + "&house_id=" + house_id,
                success: function(data)
                {
                    alert('Les données ont été modifiés avec succès.'); 
                    $modal.hide(); 
                },
                error: function()
                {
                    alert('Une erreur est survenue lors de l\'envoi des données.');
                }
            });
        });
        </script>

    </body>
</html>
<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['userId'])) {
    header('Location: login.php');
    exit;
}

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$bdd = new PDO("mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$userId = $_SESSION['userId'];
$stmtRole = $bdd->prepare("SELECT label FROM roles WHERE userId = :userId");
$stmtRole->bindParam(':userId', $userId, PDO::PARAM_INT);
$stmtRole->execute();
$userRole = $stmtRole->fetch(PDO::FETCH_ASSOC);

if (!$userRole || $userRole['label'] !== 'Employee') {
    header('Location: login.php');
    exit;
}

$query = $bdd->prepare("SELECT firstname FROM users WHERE userId = :userId;");
$query->bindValue(':userId', $userId, PDO::PARAM_INT);
$query->execute();
$user = $query->fetch(PDO::FETCH_ASSOC);
$firstname = htmlspecialchars($user['firstname']);

$resultCom = $bdd->query("SELECT * FROM comments;");

$resultService = $bdd->query("SELECT * FROM services;");

$resultOpen = $bdd->query("SELECT * FROM opening;");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['com_message'])) {
        $com_message = $_POST['com_message'];

        $stmt = $bdd->prepare("UPDATE comments SET validate = 'true' WHERE message = :com_message");
        $stmt->bindParam(':com_message', $com_message);
        $stmt->execute();
    }

    if (isset($_POST['com_message2'])) {
        $com_message2 = $_POST['com_message2'];

        $stmt = $bdd->prepare("UPDATE comments SET validate = 'false' WHERE message = :com_message2");
        $stmt->bindParam(':com_message2', $com_message2);
        $stmt->execute();
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

    if (isset($_POST['firstFormInput'])) {
        $animal = strtolower($_POST["animal"]);

        $food = htmlspecialchars($_POST["food"], ENT_QUOTES, 'UTF-8');
        $weight = htmlspecialchars($_POST["weight"], ENT_QUOTES, 'UTF-8');
        $date = htmlspecialchars($_POST["date"], ENT_QUOTES, 'UTF-8');
        $hours = htmlspecialchars($_POST["hours"], ENT_QUOTES, 'UTF-8');

        $animal_id_query = "SELECT id FROM animals WHERE firstname = :animal;";
        $stmt = $bdd->prepare($animal_id_query);
        $stmt->bindValue(':animal', $animal);
        $stmt->execute();
        $animal_id = $stmt->fetchColumn();

        $sql = "INSERT INTO foods (food, weight, date, hours, animal_id) VALUES (:food, :weight, :date, :hours, :animal_id)";

        $stmt = $bdd->prepare($sql);
        $stmt->bindValue(':animal_id', $animal_id);
        $stmt->bindValue(':food', $food);
        $stmt->bindValue(':weight', $weight);
        $stmt->bindValue(':date', $date);
        $stmt->bindValue(':hours', $hours);
        $stmt->execute();
    }

    if (isset($_POST['service_name'])) {
        $service_name = $_POST['service_name'];

        $sql = "DELETE FROM services WHERE name = :service_name";
        $stmt = $bdd->prepare($sql);
        $stmt->bindValue(':service_name', $service_name);
        $stmt->execute();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}
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
            <form id="logoutForm" method="post" action="employPanel.php" style="display: none;">
                <input type="hidden" name="logout" value="1">
            </form>

            <a href="#" onclick="confirmLogout(); return false;" class ="login">Se deconnecter</a>

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
                <table class="" style="table-layout: fixed; width: 100%;">
                    <colgroup>
                        <col style="width: 14%;">
                        <col style="width: 72%;">
                        <col style="width: 14%;">
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
                                $validate = $rowCom["validate"];
                        ?>
                            <tr>
                                <td><?php echo $com_pseudo; ?></td>
                                <td><?php echo $com_message; ?></td>
                                <td style="text-align: center;">
                                    <?php if ($validate == 'true') { ?>
                                        <button class="unvalidateButton" data-com-message2="<?php echo $com_message; ?>">✔️</button>
                                    <?php } else { ?>
                                        <button class="validateButton" data-com-message="<?php echo $com_message; ?>">❌</button>
                                    <?php } ?>
                                </td>
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
                <h3>Soumettre un compte rendu sur un animal</h3>
                <form action="employPanel.php" method="post">
                    <input type="hidden" name="firstFormInput" value="1">
                    <table style="table-layout: fixed; width: 100%;">
                        <colgroup>
                            <col style="width: 20%;">
                            <col style="width: 18%;">
                            <col style="width: 18%;">
                            <col style="width: 17%;">
                            <col style="width: 17%;">
                            <col style="width: 10%;">
                        </colgroup>
                        <thead>
                            <tr>
                                <th>Animal</th>
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
                                <td style="text-align: center;">
                                    <div>
                                        <input type="submit" value="Soumettre">
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
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

        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <form class="addServiceForm">
                    <input type="hidden" name="form_name" value="addServiceForm">
                    <input type="text" name="Nom" required placeholder="Le nom du service">
                    <input type="text" name="Description" required placeholder="La description - maximun 255 caractère">
                    <input type="text" name="imageURL" required placeholder="Image -https://maximum255caracteres.fr">
                    <input type="submit" value="Ajouter">
                </form>
            </div>
        </div>

        <div id="myModal2" class="modal">
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

    <script>
    document.querySelectorAll('.validateButton').forEach(button => {
        button.addEventListener('click', function() {
            let com_message = this.dataset.comMessage;
            fetch('employPanel.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({com_message: com_message})
            })
            .then(response => response.text())
            .then(response => {
                alert("L'avis a été validé avec succès");
            })
            .catch(error => console.error('Error:', error));
        });
    });

        document.querySelectorAll('.unvalidateButton').forEach(button => {
            button.addEventListener('click', function() {
                let com_message2 = this.dataset.comMessage2;
                fetch('employPanel.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({com_message2: com_message2})
                })
                .then(response => response.text())
                .then(response => {
                    alert("L'avis a été dévalidé avec succès");
                })
                .catch(error => console.error('Error:', error));
            });
        });

        document.addEventListener('DOMContentLoaded', function(){
    let modal = document.getElementById("myModal");
    let span = document.getElementsByClassName("close")[0];

    document.querySelectorAll(".serv_add").forEach(button => {
        button.addEventListener('click', function() {
                modal.style.display = "block";
            });
        });

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        document.querySelector(".addServiceForm").addEventListener('submit', function(e){
            e.preventDefault(); 

            let formData = new FormData(this);
            fetch('employPanel.php', {
                method: 'post',
                body: formData
            })
            .then(response => response.text())
            .then(response => {
                console.log(response);
                alert("Le service à bien été ajouté !");
                modal.style.display = "none";
                document.querySelector(".addServiceForm").reset(); 
            })
            .catch(error => console.error('Error:', error));
        });
    });

    document.querySelectorAll(".serv_delete").forEach(button => {
        button.addEventListener('click', function() {
            if (confirm("Êtes-vous sûr de vouloir supprimer la ligne entière ?")) {
                let service_name = this.dataset.service_name;
                console.log(service_name);
                fetch('employPanel.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({service_name: service_name})
                })
                .then(response => response.text())
                .then(response => {
                    this.closest("tr").remove();
                })
                .catch(error => console.error('Error:', error));
            } else {
                // Si l'utilisateur clique sur Annuler, ne rien faire
            }
        });
    });

    const $modal = $('#myModal2');
    const $form = $('.editServiceForm');
    let service_id;

        $('.serv_edit').click(function() {
        service_id = $(this).data('service_id');
        const $row = $(this).closest('tr');
        const name = $row.find('td:eq(0)').text();
        const description = $row.find('td:eq(1)').text();
        const img = $row.find('td:eq(2)').text();

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

        const url = $form.attr('action');

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

    function confirmLogout() {
        if (confirm('Voulez-vous vous déconnecter ?')) {
            document.getElementById('logoutForm').submit();
        }
    }
    </script>

    </body>
</html>
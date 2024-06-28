<?php
if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    $bdd = new PDO("mysql:host=".$_ENV['DB_HOST'].";dbname=".$_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

function redirectToRolePage($roleLabel) {
    if ($roleLabel == 'Administrator') {
        header('Location: adminPanel.php');
    } elseif ($roleLabel == 'Veterinarian') {
        header('Location: veterPanel.php');
    } elseif ($roleLabel == 'Employee') {
        header('Location: employPanel.php');
    }
    exit;
}

if (isset($_SESSION['userId'])) {
    $stmtRole = $bdd->prepare("SELECT label FROM roles WHERE userId = :userId");
    $stmtRole->bindParam(':userId', $_SESSION['userId']);
    $stmtRole->execute();
    $role = $stmtRole->fetch(PDO::FETCH_ASSOC);

    if ($role) {
        redirectToRolePage($role['label']);
    } else {
        header('Location: login.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['pass']);

    $stmtUser = $bdd->prepare("SELECT * FROM users WHERE email = :email");
    $stmtUser->bindParam(':email', $email);
    $stmtUser->execute();

    $user = $stmtUser->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['userId'] = $user['userId'];

        redirectToRolePage($user['role']); 
    } else {
        echo "Email ou mot de passe incorrect";
    }
}
?>

<!DOCTYPE html>

<html>

    <head>
        <meta charset="utf-8">
        <title>Arcadia, connexion</title>
        <meta name="viewport" content="width=device-width, initial-scale=0.60, maximum-scale=2.0, minimum-scale=0.60">        
        <meta name="description" content="Explorez la biodiversité extraordinaire du parc animalier Arcadia, un lieu magique abritant plusieurs habitats uniques. Plongez au cœur de la nature sauvage et découvrez des espèces fascinantes, de la faune endémique aux majestueux prédateurs. Rejoignez-nous pour une aventure inoubliable au sein d'Arcadia, où la préservation de la vie sauvage est notre engagement passionné.">
        <link rel="stylesheet" href="normalize.css">
        <link rel="stylesheet" href="style.css">
    </head>

    <body>

        <header>
            <a class="login" href="login.php">Connexion</a>
            <h1 class="title">Connexion</h1>
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

        <div class="form">
            <form action="login.php" method="POST">
                <div>
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email" required>
                </div>
                <div>
                    <label for="pass">Mot de passe</label>
                    <input type="password" required id="pass" name="pass" minlength="7">
                </div>
                <div class="button">
                    <input type="submit" value="Connexion">
                </div>
            </form>            
        </div>

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
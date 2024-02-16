<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $title = $_POST["title"];
    $message = $_POST["message"];

    $to = "dctmp@icloud.com"; 
    $subject = "Nouveau message de $email: $title";
    $headers = "From: $email";

    if (mail($to, $subject, $message, $headers)) {
        $message_success = "Votre message a bien été envoyé, une réponse vous sera donné par retour d'eMail.";
    } else {
        $message_fail = "Votre message n'a pas été envoyé, merci de réessayer plus tard.";
    }
}

try {
    // Connexion à la base de données SQLite
    $bdd = new PDO('sqlite:db.sqlite');
    // Activation du mode d'erreur PDO pour afficher les erreurs
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête SQL pour récupérer les horaires
    $sql = "SELECT * FROM horaires;";
    $result = $bdd->query($sql);
} catch (PDOException $e) {
    // En cas d'erreur, affiche le message d'erreur
    echo "Erreur de connexion ou d'exécution de la requête : " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Arcadia, nous contacter</title>
        <meta name="viewport" content="width=device-width, initial-scale=0.60, maximum-scale=2.0, minimum-scale=0.60">
        <meta name="description" content="Explorez la biodiversité extraordinaire du parc animalier Arcadia, un lieu magique abritant plusieurs habitats uniques. Plongez au cœur de la nature sauvage et découvrez des espèces fascinantes, de la faune endémique aux majestueux prédateurs. Rejoignez-nous pour une aventure inoubliable au sein d'Arcadia, où la préservation de la vie sauvage est notre engagement passionné.">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <header>
            <a class="login" href="login.php">Connexion</a>
            <h1 class="title">Nous contacter</h1>
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
            <form method="post">
                <div>
                    <label for="email">eMail</label>
                    <input type="email" required id="email" name="email" placeholder="hervedupont@gmail.fr">
                </div>
                <div>
                    <label for="title">Titre</label>
                    <input type="text" required id="title" name="title" placeholder="Merci de donner un titre a votre message">
                </div>
                <div>
                    <label for="message">Description</label>
                    <textarea required id="message" name="message" placeholder="Ce zoo est fantastique !" style="height:200px"></textarea>
                </div>
                <div class="button">
                    <input type="submit" value="Envoyer">
                </div>
            </form>
        </div>
        <?php 
        if (!empty($message_success)) : ?>
            <div class="form">
                <p><?php echo $message_success; ?></p>
            </div>
        <?php 
        else: ?>
            <div class="form">
                <p><?php echo $message_fail; ?></p>
            </div>
        <?php 
        endif; 
        ?>
        <footer>
            <p>© 2024 Arcadia, tous droits réservés</p>
            <div class="horaires">
                <ul>
                    <li>
                        Horaires d'ouverture
                    </li>
                    <br>
                    <?php
                    // Affichage des horaires
                    $row = $result->fetch(PDO::FETCH_ASSOC);
                    if ($row) {
                        do {
                            $openDay = $row["jour"];
                            $openHours = $row["heures"];
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
    </body>
</html>

<?php

try {
    putenv('SQLITE_TMPDIR=/img');

    $bdd = new PDO('sqlite:db.sqlite');

    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $comment = "SELECT * FROM comments;";
    $resultComment = $bdd->query($comment);

    $open = "SELECT * FROM opening;";
    $resultOpen = $bdd->query($open);
    $message_success = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

        $name = htmlspecialchars($name);
        $message = htmlspecialchars($message);

        $form = "INSERT INTO comments (pseudo, message, validate) VALUES (?, ?, 'false')";
        $stmt= $bdd->prepare($form);
        $stmt->execute([$name, $message]);

        $message_success = "Votre message a bien été envoyé et sera visible après sa validation.";
    }

    } catch (PDOException $e) {
        echo "Erreur de connexion ou d'exécution de la requête : " . $e->getMessage();
}
?>

<!DOCTYPE html>

<html>

    <head>
        <meta charset="utf-8">
        <title>Arcadia, votre parc animalier</title>
        <meta name="viewport" content="width=device-width, initial-scale=0.60, maximum-scale=2.0, minimum-scale=0.60">        
        <meta name="description" content="Explorez la biodiversité extraordinaire du parc animalier Arcadia, un lieu magique abritant plusieurs habitats uniques. Plongez au cœur de la nature sauvage et découvrez des espèces fascinantes, de la faune endémique aux majestueux prédateurs. Rejoignez-nous pour une aventure inoubliable au sein d'Arcadia, où la préservation de la vie sauvage est notre engagement passionné.">
        <link rel="stylesheet" href="normalize.css">
        <link rel="stylesheet" href="style.css">
    </head>

    <body>

        <header>
            <a class="login" href="login.php">Connexion</a>
            <h1 class="title">Arcadia</h1>
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

        <p class="left-box">
            <img src="img/presentation.jpg" alt="Image de présentation du zoo" width="400" height="300">
            Fondé en 1995 par l'explorateur passionné de la nature, José Rodriguez, Arcadia offre une immersion unique dans trois habitats distincts : le marais, la savane et la jungle. </br></br>Depuis son ouverture, ce zoo familial s'engage à sensibiliser à la conservation tout en offrant des expériences captivantes. Aujourd'hui dirigé par la fille de José, Josette Rodriguez, Arcadia continue de perpétuer la vision originale, invitant les visiteurs à découvrir la magie de la vie sauvage.
        </p>
        <p class="right-box">
            <img src="img/ecology.jpg" alt="Illustration système autonomie énergétique" width="400" height="300">
            Arcadia est bien plus qu'un zoo, c'est un havre écologique. Engagé envers la préservation de la biodiversité, le parc met en avant des habitats soigneusement conçus pour reproduire les écosystèmes naturels. Sa particularité réside dans son engagement envers l'écologie, avec des initiatives innovantes. Arcadia s'autoalimente en énergie grâce à des sources durables, témoignant ainsi de son engagement envers un avenir respectueux de l'environnement. En visitant Arcadia, vous participez à une expérience où la préservation de la nature va de pair avec le plaisir de la découverte.
        </p>
        <hr>
        <?php
            $rowComment = $resultComment->fetch(PDO::FETCH_ASSOC);
            if ($rowComment) {
                            do {
                                $comment_message = $rowComment["message"];
                                $comment_pseudo = $rowComment["pseudo"];
                                $comment_validate = $rowComment["validate"];
                                if ($comment_validate == 'true') {
                                    ?>
                                    <blockquote>
                                        <p><?php echo $comment_message; ?> </br>
                                            <cite><?php echo $comment_pseudo; ?></cite>
                                        </p>
                                    </blockquote>
                                    <?php
                                }
                                } while ($rowComment = $resultComment->fetch(PDO::FETCH_ASSOC));
                        } else {
                            echo "<li>Aucun commentaire trouvé.</li>";
                        }
        ?>

        <div class="form">
            <p>Laissez nous un commentaire : </br></p>
            <form method="POST">
                <div>
                    <label for="name">Pseudo</label>
                    <input type="text" required id="name" name="name" placeholder="Votre pseudo" maxlength="20">
                </div>
                <div>
                    <label for="message">Commentaire</label>
                    <textarea required id="message" name="message" placeholder="Ce zoo est fantastique !" style="height:200px" maxlength="500"></textarea>
                </div>
                <div class="button">
                    <input type="submit" value="Envoyer">
                </div>
            </form>
        </div>

        <?php if (!empty($message_success)) : ?>
            <div class="form">
            <p><?php echo $message_success; ?></p>
            </div>
        <?php endif; ?>

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
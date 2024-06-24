<?php
if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

try {
    require 'vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    $host = $_ENV['DB_HOST'];
    $dbname = $_ENV['DB_NAME'];
    $username = $_ENV['DB_USER'];
    $password = $_ENV['DB_PASS'];

    $bdd = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $open = "SELECT * FROM opening;";
    $resultOpen = $bdd->query($open);

} catch (PDOException $e) {
    echo "Erreur de connexion ou d'exécution de la requête : " . $e->getMessage();
}
?>

<!DOCTYPE html>

<html>

    <head>
        <meta charset="utf-8">
        <title>Arcadia, mentions légales</title>
        <meta name="viewport" content="width=device-width, initial-scale=0.60, maximum-scale=2.0, minimum-scale=0.60">        
        <meta name="description" content="Explorez la biodiversité extraordinaire du parc animalier Arcadia, un lieu magique abritant plusieurs habitats uniques. Plongez au cœur de la nature sauvage et découvrez des espèces fascinantes, de la faune endémique aux majestueux prédateurs. Rejoignez-nous pour une aventure inoubliable au sein d'Arcadia, où la préservation de la vie sauvage est notre engagement passionné.">
        <link rel="stylesheet" href="normalize.css">
        <link rel="stylesheet" href="style.css">
    </head>

    <body>

        <header>
            <a class="login" href="login.php">Connexion</a>
            <h1 class="title">Mentions légales</h1>
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

        <div class="mentions">
            <h3>Arcadia</h3>
            <br>
            <p>Ce site fictif à  été créer de toutes pièce par <strong>DE CARVALHO TEIXEIRA Manuel</strong></p>
            <br>
            <p>Le site est hébergé par <strong>Nexus-Games-7 Rue du Vieux Cours, 35000 Rennes (France)</strong></p>
            <br>
            <p><strong>Photo et images :</strong> Toutes les photos et images sont libre de droit</p>
            <br>
            <p><strong>Accessibilité</strong>
            <p>Arcadia s'est donné pour objectif d'être accessible au plus grand nombre, dans le respect des normes internationales. Nous répondons aux normes W3C et WAI. Les personnes qui souffrent d'un handicap sont invitées à nous communiquer les difficultés qu'elles rencontrent dans l'accessibilité au site.</p>
            <br>
            <p><strong>Liens externes</strong>
            <p>Arcadia autorise la création d'hyperliens ou liens hypertextes vers la page d'accueil, les services et les autres pages.</p>
            <br>
            <p><strong>Protection des données personnelles</strong>
            <p>Conformément à la Loi Informatique et Libertés (Loi n° 78-17 du 6 janvier 1978), nous vous informons que vous disposez d'un droit d'accès, de modification, de rectification et de suppression des données nécessaires au traitement de vos demandes de renseignement ou de documentation ou pour une inscription à une newsletter. 
                Les informations que vous nous communiquez ou figurant sur notre site ont pour objet de mieux répondre à votre demande. Nous pourrons les utiliser pour vous informer de nos offres de produits et services. 
                Nous nous engageons à ne pas communiquer ou céder vos informations personnelles, y compris votre adresse mail à des tiers.
                Vous disposez d'un droit d'accès, de modification, de rectification et de suppression des données qui vous concernent (art. 34 de la loi "Informatique et Libertés"). Pour l'exercer, contactez-nous.</p>
            <br>
            <p><strong>Consultation du site</strong>
            <p>L'utilisateur s'engage à respecter toutes les législations en vigueur, notamment les dispositions de la loi Informatique, Fichiers et Liberté, celles qui sont liées à la vie privée, aux droits liés à la propriété intellectuelle et artistique, droit d'auteur et droits des marques.</p>
            <br>
            <p><strong>Reproduction</strong>
            <p>Toute reproduction de textes et images publiés sur le site, par quelque procédé que ce soit est interdite et constitue une contrefaçon sanctionnée par les articles L.335-2 et suivants du code de la propriété intellectuelle et artistique. 
                Les photos publiées sur Arcadia sont la propriété de Arcadia ou ont fait l'objet d'une autorisation de publication par les titulaires des droits.</p>
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
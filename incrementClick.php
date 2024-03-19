<?php
try {
require 'vendor/autoload.php';

$client = new MongoDB\Client("mongodb://manu:vanEtlaura7@localhost:27017");
$database = $client->selectDatabase("animals_click"); 
$collection = $database->selectCollection("animals_click"); 
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_animal = $_POST['id'];

    $filter = ['_id' => new MongoDB\BSON\ObjectId($id_animal)];
    $options = [];

    $query = new MongoDB\Driver\Query($filter, $options);
    $cursor = $manager->executeQuery("{$db}.animals_click", $query);

    $click_animal = 0;
    foreach ($cursor as $document) {
        $click_animal = $document->click;
    }

    $click_animal++;

    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->update(
        ['_id' => new MongoDB\BSON\ObjectId($id_animal)],
        ['$set' => ['click' => $click_animal]],
        ['upsert' => true]
    );

    $manager->executeBulkWrite("{$db}.animals_click", $bulk);
}
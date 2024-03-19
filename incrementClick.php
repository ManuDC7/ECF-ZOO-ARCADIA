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

    $animal = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($id_animal)]);

    if ($animal) {
        $collection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($id_animal)],
            ['$inc' => ['click' => 1]]
        );
    } else {
        $collection->insertOne(['_id' => new MongoDB\BSON\ObjectId($id_animal), 'click' => 0]);
    }
}
<?php
require 'vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$user = 'manu';
$password = 'vanEtlaura7';
$db = 'animals_click';

$manager = new MongoDB\Driver\Manager("mongodb://{$user}:{$password}@localhost:27017/{$db}");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_animal = $_POST['id'];

    $filter = ['_id' => new MongoDB\BSON\ObjectId($id_animal)];
    $options = [];

    $query = new MongoDB\Driver\Query($filter, $options);
    $cursor = $manager->executeQuery("{$db}.animal_click", $query);

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

    $manager->executeBulkWrite("{$db}.animal_click", $bulk);
}
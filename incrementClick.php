<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$bdd = new PDO('sqlite:db.sqlite');
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name_animal = $_POST['firstname'];

    $stmt = $bdd->prepare('SELECT click FROM animals WHERE firstname = :name');
    $stmt->execute(['firstname' => $name_animal]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $click_animal = $row['click'];

    $click_animal++;

    $stmt = $bdd->prepare('UPDATE animals SET click = :click WHERE firstname = :name');
    $stmt->execute(['click' => $click_animal, 'firstname' => $name_animal]);
}
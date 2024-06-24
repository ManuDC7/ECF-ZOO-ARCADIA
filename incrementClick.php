<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Connexion à la base de données SQLite
require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$host = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_NAME'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];

$bdd = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nameAnim = $_POST['name'];

    // Récupérez la valeur actuelle de click pour l'animal
    $stmt = $bdd->prepare('SELECT click FROM animaux WHERE prénom = :name');
    $stmt->execute(['name' => $nameAnim]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $clickAnim = $row['click'];

    // Incrémentez la valeur de click
    $clickAnim++;

    // Mettez à jour la valeur de click dans la base de données
    $stmt = $bdd->prepare('UPDATE animaux SET click = :click WHERE prénom = :name');
    $stmt->execute(['click' => $clickAnim, 'name' => $nameAnim]);

    echo 'Click incrementé pour ' . $nameAnim;
}
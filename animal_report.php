<?php
$bdd = new PDO('sqlite:db.sqlite');
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_GET['id'])) {
    $animalId = $_GET['id'];

    $stmt = $bdd->prepare("SELECT * FROM reports WHERE animal_id = ?");
    $stmt->execute([$animalId]);

    while ($report = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $report_date = htmlspecialchars($report["date"]);
        echo "<option value=\"$report_date\">$report_date</option>";
    }
} else {
    echo "<option value=\"\">Pas de date</option>";
}
?>
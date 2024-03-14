<?php
header('Content-Type: application/json');
$bdd = new PDO('sqlite:db.sqlite');
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_GET['date'])) {
    $selectedDate = $_GET['date'];
    $formattedDate = preg_match('/^\d{2}-\d{2}-\d{4}$/', $selectedDate) ? $selectedDate : date('d-m-Y', strtotime($selectedDate));
    $query = $bdd->prepare("SELECT report FROM reports WHERE date = :date");
    $query->execute([':date' => $selectedDate]);
    $report = $query->fetch(PDO::FETCH_ASSOC);

    if ($report) {
        echo json_encode(['date' => $formattedDate, 'content' => $report['report']]);
    } else {
        echo json_encode(['error' => 'Pas de compte rendu pour cette date']);
    }
} else {
    echo json_encode(['error' => 'Pas de date sélectionnée']);
}
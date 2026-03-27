<?php 
session_start();
$databases = require __DIR__ . "/database.php";
$mysqli2 = $databases['books'];

if (!$mysqli2 instanceof mysqli) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed.']);
    exit;
}

$sql = "SELECT kid, vorname, name, email, kunde_seit FROM kunden";
$result = $mysqli2->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Query execution failed.']);
    exit;
}

$customers = [];

while ($row = $result->fetch_assoc()) {
    $customers[] = $row;
}

header('Content-Type: application/json');
echo json_encode($customers);
?>

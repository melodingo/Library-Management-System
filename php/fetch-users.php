<?php
session_start();

$databases = require __DIR__ . "/database.php";
$mysqli1 = $databases['login_db']; // Access the specific database connection

if (!$mysqli1 instanceof mysqli) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed.']);
    exit;
}

$sql = "SELECT name, email, password_hash FROM user";
$result = $mysqli1->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Query execution failed.']);
    exit;
}

$users = [];

while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

header('Content-Type: application/json');
echo json_encode($users);
?>
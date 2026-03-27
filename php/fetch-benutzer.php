<?php
session_start();
$databases = require __DIR__ . "/database.php";
$mysqli = $databases['books']; // besser direkt $mysqli nennen

$query = "SELECT 
    ID,
    benutzername,
    name,
    vorname,
    email,
    admin
    FROM benutzer";

$result = $mysqli->query($query);

if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Query execution failed.', 'mysqli_error' => $mysqli->error]);
    exit;
}

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = [
        'ID' => (int)$row['ID'],
        'benutzername' => htmlspecialchars($row['benutzername']),
        'name' => htmlspecialchars($row['name']),
        'vorname' => htmlspecialchars($row['vorname']),
        'email' => htmlspecialchars($row['email']),
        'admin' => (bool)$row['admin']
    ];
}

header('Content-Type: application/json');
echo json_encode($users);

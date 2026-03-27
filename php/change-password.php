<?php
session_start();
header('Content-Type: application/json');
$connections = include 'database.php';
$mysqli2 = $connections['books'];

$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    echo json_encode(['success' => false, 'message' => 'Nicht angemeldet.']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['oldPassword'], $data['newPassword'])) {
    echo json_encode(['success' => false, 'message' => 'Altes und neues Passwort erforderlich.']);
    exit;
}

$oldPassword = $data['oldPassword'];
$newPassword = $data['newPassword'];

// Passwort nach den Regeln Checken
if (strlen($newPassword) < 8) {
    echo json_encode(['success' => false, 'message' => 'Neues Passwort muss mindestens 8 Zeichen lang sein.']);
    exit;
}
if (!preg_match("/[a-z]/i", $newPassword)) {
    echo json_encode(['success' => false, 'message' => 'Neues Passwort muss mindestens einen Buchstaben enthalten.']);
    exit;
}
if (!preg_match("/[0-9]/", $newPassword)) {
    echo json_encode(['success' => false, 'message' => 'Neues Passwort muss mindestens eine Zahl enthalten.']);
    exit;
}

// Altes Passwort aus DB holen
$stmt = $mysqli2->prepare("SELECT passwort_hash FROM benutzer WHERE ID = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($storedHash);
if (!$stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Benutzer nicht gefunden.']);
    exit;
}
$stmt->close();

// Altes Passwort prüfen
if (!password_verify($oldPassword, $storedHash)) {
    echo json_encode(['success' => false, 'message' => 'Das alte Passwort ist falsch.']);
    exit;
}

// Neues Passwort hashen und speichern
$newHash = password_hash($newPassword, PASSWORD_DEFAULT);
$update = $mysqli2->prepare("UPDATE benutzer SET passwort_hash = ? WHERE ID = ?");
$update->bind_param("si", $newHash, $userId);

if ($update->execute()) {
    echo json_encode(['success' => true, 'message' => '✅ Passwort erfolgreich geändert']);
} else {
    echo json_encode(['success' => false, 'message' => '❌ Fehler beim Ändern des Passworts: ' . $update->error]);
}

$update->close();
$mysqli2->close();

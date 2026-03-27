<?php
session_start();
$connections = include 'database.php';
$mysqli = $connections['books'];

$data = json_decode(file_get_contents('php://input'), true);

$errors = [];
if (strlen($data['benutzername']) > 45) $errors[] = 'Benutzername zu lang';
if (empty($data['benutzername'])) $errors[] = 'Benutzername ist erforderlich';
if (empty($data['name'])) $errors[] = 'Name ist erforderlich';
if (empty($data['vorname'])) $errors[] = 'Vorname ist erforderlich';
if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Ungültige Email';
if (!$data['id'] && (empty($data['password']) || strlen($data['password']) < 8)) $errors[] = 'Passwort zu kurz oder fehlt';
if (!isset($data['admin'])) $errors[] = 'Admin-Status fehlt';

if ($errors) {
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

try {
    if (!empty($data['id'])) {
        $stmt = $mysqli->prepare("
            UPDATE benutzer 
            SET benutzername = ?, name = ?, vorname = ?, email = ?, admin = ? 
            WHERE ID = ?
        ");
        $stmt->bind_param("ssssii", $data['benutzername'], $data['name'], $data['vorname'], $data['email'], $data['admin'], $data['id']);
    } else {
        $hash = password_hash($data['password'], PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare("
            INSERT INTO benutzer (benutzername, name, vorname, email, passwort, admin) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("sssssi", $data['benutzername'], $data['name'], $data['vorname'], $data['email'], $hash, $data['admin']);
    }

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Benutzer erfolgreich gespeichert']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Fehler beim Speichern']);
    }

    $stmt->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Interner Fehler']);
}
?>

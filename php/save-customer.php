<?php
session_start();
$connections = include 'database.php';
$mysqli = $connections['books'];

$data = json_decode(file_get_contents('php://input'), true);

$errors = [];
if (empty($data['vorname'])) $errors[] = 'Vorname ist erforderlich';
if (empty($data['name'])) $errors[] = 'Name ist erforderlich';
if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Ungültige Email';
if (!isset($data['kontaktpermail'])) $errors[] = 'Kontakt per Mail fehlt';

if ($errors) {
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

try {
    if (empty($data['kid'])) {
        $kontakt = !empty($data['kontaktpermail']) ? 1 : 0;

        $stmt = $mysqli->prepare("
            INSERT INTO kunden 
            (vorname, name, email, kontaktpermail) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param('sssi', $data['vorname'], $data['name'], $data['email'], $kontakt);
    } else {
        if (empty($data['kid']) || !is_numeric($data['kid'])) {
            echo json_encode(['success' => false, 'message' => 'Ungültige ID']);
            exit;
        }

        $kontakt = !empty($data['kontaktpermail']) ? 1 : 0;

        $stmt = $mysqli->prepare("
            UPDATE kunden 
            SET vorname = ?, name = ?, email = ?, kontaktpermail = ? 
            WHERE kid = ?
        ");
        $stmt->bind_param('ssssi', $data['vorname'], $data['name'], $data['email'], $kontakt, $data['kid']);
    }

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Kunde erfolgreich gespeichert']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Fehler beim Speichern: ' . $stmt->error]);
    }

    $stmt->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Interner Fehler']);
}
?>

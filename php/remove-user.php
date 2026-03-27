<?php
session_start();
$connections = include 'database.php';
$mysqli = $connections['books'];

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID ist erforderlich']);
    exit;
}

$stmt = $mysqli->prepare("DELETE FROM benutzer WHERE ID = ?");
$stmt->bind_param('i', $data['id']);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Kunde gelöscht']);
} else {
    echo json_encode(['success' => false, 'message' => 'Fehler: ' . $stmt->error]);
}
?>
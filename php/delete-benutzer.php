<?php
session_start();
$connections = include 'database.php';
$mysqli2 = $connections['books'];

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id']) || !is_numeric($data['id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Ungültige ID']);
    exit;
}
//das hier hat 100% first try funktioniert :P
try {
    $stmt = $mysqli2->prepare("DELETE FROM benutzer WHERE ID = ?");
    $stmt->bind_param("i", $data['id']);
    if ($stmt->execute()) {
        echo json_encode(['success' => true,  'message' => 'Erfolgreich gelöscht']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Löschen fehlgeschlagen']);
    }
    $stmt->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Interner Fehler']);
}
?>

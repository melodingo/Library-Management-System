<?php
// remove-user.php

session_start();

$mysqli1 = require __DIR__ . "/database.php";

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['email'])) {
    echo json_encode(['success' => false, 'message' => 'Email is required']);
    exit;
}

$email = $data['email'];

$sql = "DELETE FROM user WHERE email = ?";
$stmt = $mysqli1->prepare($sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'SQL error: ' . $mysqli1->error]);
    exit;
}

$stmt->bind_param('s', $email);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'User removed successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to remove user']);
}
?>
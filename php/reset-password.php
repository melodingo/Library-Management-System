<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$mysqli1 = require __DIR__ . "/database.php";

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['email'])) {
    echo json_encode(['success' => false, 'message' => 'Email is required']);
    exit;
}

$email = $data['email'];
$new_password = bin2hex(random_bytes(4)); // Generate a random 8-character password
$new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

$sql = "UPDATE user SET password_hash = ? WHERE email = ?";
$stmt = $mysqli1->prepare($sql);

if (!$stmt) {
    error_log('SQL error: ' . $mysqli1->error); // Log the SQL error
    echo json_encode(['success' => false, 'message' => 'SQL error: ' . $mysqli1->error]);
    exit;
}

$stmt->bind_param('ss', $new_password_hash, $email);

if ($stmt->execute()) {
    // Send the new password to the user's email
    $subject = "Your new password";
    $message = "Your new password is: $new_password";
    $headers = "From: samuelstucki44@gmail.com";

    if (mail($email, $subject, $message, $headers)) {
        echo json_encode(['success' => true, 'message' => 'Password reset successfully and email sent']);
    } else {
        error_log('Failed to send email to ' . $email); // Log email sending error
        echo json_encode(['success' => false, 'message' => 'Password reset but failed to send email']);
    }
} else {
    error_log('Failed to execute statement: ' . $stmt->error); // Log statement execution error
    echo json_encode(['success' => false, 'message' => 'Failed to reset password']);
}
?>
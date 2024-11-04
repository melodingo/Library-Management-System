<?php

if (empty($_POST["name"])) {
    die("Name is required");
}

if ( ! filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    die("Valid email is required");
}

if (strlen($_POST["password"]) < 8) {
    die("Password must be at least 8 characters");
}

if ( ! preg_match("/[a-z]/i", $_POST["password"])) {
    die("Password must contain at least one letter");
}

if ( ! preg_match("/[0-9]/", $_POST["password"])) {
    die("Password must contain at least one number");
}

if ($_POST["password"] !== $_POST["password_confirmation"]) {
    die("Passwords must match");
}

$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

$mysqli1 = require __DIR__ . "/database.php";

$sql = "INSERT INTO user (name, email, password_hash, is_admin)
        VALUES (?, ?, ?, ?)";
        
$stmt = $mysqli1->stmt_init();

if ( ! $stmt->prepare($sql)) {
    die("SQL error: " . $mysqli1->error);
}

$is_admin = ($_POST["email"] === "admin@gmail.com") ? 1 : 0;

$stmt->bind_param("sssi",
                  $_POST["name"],
                  $_POST["email"],
                  $password_hash,
                  $is_admin);
                  
if ($stmt->execute()) {
    // Debugging output to check if the header is being called
    error_log("Redirecting to success page");
    header("Location: /pages/Signup-succes.html");
    exit;
} else {
    // Debugging output to check the error
    error_log("Error: " . $mysqli1->error . " " . $mysqli1->errno);
    
    if ($mysqli1->errno === 1062) {
        die("email already taken Error");
    } else {
        die($mysqli1->error . " " . $mysqli1->errno);
    }
}









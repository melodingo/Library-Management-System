<?php
if (empty($_POST["benutzername"])) die("Benutzername ist erforderlich");
if (empty($_POST["vorname"])) die("Vorname ist erforderlich");
if (empty($_POST["name"])) die("Nachname ist erforderlich");
if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) die("Eine gültige E-Mail-Adresse ist erforderlich");
if (strlen($_POST["password"]) < 8) die("Das Passwort muss mindestens 8 Zeichen lang sein");
if (strlen($_POST["benutzername"]) > 45) die("Der Benutzername darf höchstens 45 Zeichen haben");
if (!preg_match("/[a-z]/i", $_POST["password"])) die("Das Passwort muss mindestens einen Buchstaben enthalten");
if (!preg_match("/[0-9]/", $_POST["password"])) die("Das Passwort muss mindestens eine Zahl enthalten");
if ($_POST["password"] !== $_POST["password_confirmation"]) die("Die Passwörter müssen übereinstimmen");

$connections = include 'database.php';
$mysqli = $connections['books'];

$stmt = $mysqli->prepare("SELECT ID FROM benutzer WHERE benutzername = ?");
$stmt->bind_param("s", $_POST["benutzername"]);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) die("Benutzername ist bereits vergeben");
$stmt->close();

$stmt = $mysqli->prepare("SELECT ID FROM benutzer WHERE email = ?");
$stmt->bind_param("s", $_POST["email"]);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) die("E-Mail ist bereits registriert");
$stmt->close();

$stmt = $mysqli->prepare("INSERT INTO benutzer (benutzername, vorname, name, email, passwort, admin) VALUES (?, ?, ?, ?, ?, ?)");
$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

$stmt->bind_param("sssssi", $_POST["benutzername"], $_POST["vorname"], $_POST["name"], $_POST["email"], $password_hash, $is_admin);

if ($stmt->execute()) {
    header("Location: ../pages/Signup-succes.html");
    exit;
} else {
    die($mysqli->error ?: "Registrierung fehlgeschlagen");
}
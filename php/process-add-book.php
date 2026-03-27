<?php
session_start();
$connections = include 'database.php';
$mysqli = $connections['books'];

$sql = "INSERT INTO buecher (Title, autor, nummer, kategorie, katalog, foto, zustand, Beschreibung, verfasser, verkauft, kaufer) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    die("SQL-Fehler: " . $mysqli->error);
}

$Title = isset($_POST['Title']) ? $_POST['Title'] : '';
$autor = isset($_POST['autor']) ? $_POST['autor'] : '';
$nummer = isset($_POST['nummer']) ? $_POST['nummer'] : '';
$kategorie = isset($_POST['kategorie']) ? $_POST['kategorie'] : '';
$katalog = isset($_POST['katalog']) ? $_POST['katalog'] : '';
$foto = isset($_POST['foto']) ? $_POST['foto'] : '';
$zustand = isset($_POST['zustand']) ? $_POST['zustand'] : 'Neu';
$Beschreibung = isset($_POST['Beschreibung']) ? $_POST['Beschreibung'] : '';
$verfasser = isset($_POST['verfasser']) ? $_POST['verfasser'] : '';
$verkauft = isset($_POST['verkauft']) ? (int)$_POST['verkauft'] : 0;
$kaufer = isset($_POST['kaufer']) ? $_POST['kaufer'] : '';

$stmt->bind_param("sssssssssii",
    $Title,
    $autor,
    $nummer,
    $kategorie,
    $katalog,
    $foto,
    $zustand,
    $Beschreibung,
    $verfasser,
    $verkauft,
    $kaufer
);

if ($stmt->execute()) {
    header("Location: ../pages/admin-dashboard.php?message=Buch+erfolgreich+hinzugefügt");
} else {
    header("Location: add-book.php?error=" . urlencode($stmt->error));
}

$stmt->close();
$mysqli->close();
?>                                                             
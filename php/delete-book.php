<?php
session_start();
$connections = include 'database.php';
$mysqli2 = $connections['books'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: edit-book.php?error=" . urlencode("Ungültige Anfrage"));
    exit;
}

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    header("Location: edit-book.php?error=" . urlencode("Ungültige ID"));
    exit;
}

$id = (int)$_POST['id'];
$search_title = isset($_POST['search_title']) ? $_POST['search_title'] : '';

try {
    $stmt = $mysqli2->prepare("DELETE FROM buecher WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = 'Buch erfolgreich gelöscht';
        $_SESSION['error'] = false;
        header("Location: edit-book.php?message=" . urlencode("Buch erfolgreich gelöscht"));
    } else {
        $_SESSION['message'] = 'Löschen fehlgeschlagen';
        $_SESSION['error'] = true;
        header("Location: edit-book.php?error=" . urlencode("Löschen fehlgeschlagen"));
    }

    $stmt->close();
} catch (Exception $e) {
    $_SESSION['message'] = 'Interner Fehler';
    $_SESSION['error'] = true;
    header("Location: edit-book.php?error=" . urlencode("Interner Fehler: " . $e->getMessage()));
}
?>

<?php  
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $connections = include 'database.php';
    $mysqli = $connections['books'];

    $sql = "UPDATE buecher SET 
            Title = ?, 
            autor = ?, 
            nummer = ?,
            kategorie = ?, 
            katalog = ?, 
            foto = ?,
            zustand = ?,
            Beschreibung = ?,
            verfasser = ?,
            verkauft = ?,
            kaufer = ?
            WHERE id = ?";

    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        $error = "SQL-Fehler: " . $mysqli->error;
        header("Location: edit-book.php?message=" . urlencode($error) . "&error=true");
        exit();
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
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    $stmt->bind_param("ssssssssssii",
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
        $kaufer,
        $id
    );

    if ($stmt->execute()) {
        header("Location: edit-book.php?message=" . urlencode("Buch erfolgreich aktualisiert"));
    } else {
        $error = "Fehler beim Ausführen der Abfrage: " . $stmt->error;
        header("Location: edit-book.php?message=" . urlencode($error) . "&error=true");
    }

    $stmt->close();
    $mysqli->close();
}
?>

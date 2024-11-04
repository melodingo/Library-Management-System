<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include the database connections
    $connections = include 'database.php';

    // Access the connection to the library_management
    $mysqli2 = $connections['library_management'];

    // Prepare the SQL statement
    $sql = "UPDATE books SET title = ?, author = ?, genre = ?, published_date = ?, isbn = ?, image_url = ? WHERE id = ?";

    $stmt = $mysqli2->stmt_init();

    if ( ! $stmt->prepare($sql)) {
        $error = "SQL error: " . $mysqli2->error;
        header("Location: edit-book.php?message=" . urlencode($error) . "&error=true");
        exit();
    }

    // Bind the parameters
    $stmt->bind_param("ssssssi",
                      $_POST["title"],
                      $_POST["author"],
                      $_POST["genre"],
                      $_POST["published_date"],
                      $_POST["isbn"],
                      $_POST["image_url"],
                      $_POST["id"]);

    // Execute the statement
    if ($stmt->execute()) {
        header("Location: edit-book.php?message=" . urlencode("Book updated successfully"));
    } else {
        $error = "Error: " . $stmt->error;
        header("Location: edit-book.php?message=" . urlencode($error) . "&error=true");
    }

    $stmt->close();
    $mysqli2->close();
}
?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include the database connections
    $connections = include 'database.php';

    // Access the connection to the library_management
    $mysqli2 = $connections['library_management'];

    // Prepare the SQL statement
    $sql = "DELETE FROM books WHERE id = ?";

    $stmt = $mysqli2->stmt_init();

    if ( ! $stmt->prepare($sql)) {
        die("SQL error: " . $mysqli2->error);
    }

    // Bind the parameters
    $stmt->bind_param("i", $_POST["id"]);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Book deleted successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $mysqli2->close();
}
?>
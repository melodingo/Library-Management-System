<?php
// Include the database connections
$connections = include 'database.php';

// Access the connection to the library_management
$mysqli2 = $connections['library_management'];

// Prepare the SQL statement
$sql = "INSERT INTO books (title, author, genre, published_date, isbn, image_url, is_borrowed) VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $mysqli2->stmt_init();

if ( ! $stmt->prepare($sql)) {
    die("SQL error: " . $mysqli2->error);
}

// Bind the parameters
$is_borrowed = false; // Default value for new books
$stmt->bind_param("ssssssi",
                  $_POST["title"],
                  $_POST["author"],
                  $_POST["genre"],
                  $_POST["published_date"],
                  $_POST["isbn"],
                  $_POST["image_url"],
                  $is_borrowed);

// Execute the statement
if ($stmt->execute()) {
    echo "New book added successfully";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$mysqli2->close();
?>
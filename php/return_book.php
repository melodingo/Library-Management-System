<?php
session_start();
$connections = include 'database.php';
$mysqli2 = $connections['library_management'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $borrowed_id = $_POST['borrowed_id'];

    // Get the book_id from the borrowed_books table
    $query = "SELECT book_id FROM borrowed_books WHERE id = ?";
    $stmt = $mysqli2->prepare($query);
    $stmt->bind_param("i", $borrowed_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $book_id = $row['book_id'];
    $stmt->close();

    // Delete the record from borrowed_books
    $query = "DELETE FROM borrowed_books WHERE id = ?";
    $stmt = $mysqli2->prepare($query);
    $stmt->bind_param("i", $borrowed_id);
    $stmt->execute();
    $stmt->close();

    // Update the book status to available
    $query = "UPDATE books SET is_borrowed = 0 WHERE id = ?";
    $stmt = $mysqli2->prepare($query);
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $stmt->close();

    echo json_encode(['status' => 'success', 'message' => 'Book returned successfully.']);
    exit;
}
?>
<?php
session_start();
$connections = include 'database.php';
$mysqli1 = $connections['login_db'];
$mysqli2 = $connections['library_management'];

$user_id = $_SESSION['user_id'];
$book_id = $_POST['book_id'];

// Check if the user has already borrowed 2 books
$query = "SELECT COUNT(*) as count FROM borrowed_books WHERE user_id = ? AND return_date > NOW()";
$stmt = $mysqli2->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['count'] >= 2) {
    echo json_encode(['status' => 'error', 'message' => 'You can only borrow 2 books at a time.']);
    exit;
}

// Check if the book is already borrowed
$query = "SELECT is_borrowed FROM books WHERE id = ?";
$stmt = $mysqli2->prepare($query);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['is_borrowed']) {
    echo json_encode(['status' => 'error', 'message' => 'This book is already borrowed.']);
    exit;
}

// Borrow the book
$borrowed_date = date('Y-m-d H:i:s');
$return_date = date('Y-m-d H:i:s', strtotime('+14 days')); // 2 weeks borrowing period

$query = "INSERT INTO borrowed_books (user_id, book_id, borrowed_date, return_date) VALUES (?, ?, ?, ?)";
$stmt = $mysqli2->prepare($query);
$stmt->bind_param("iiss", $user_id, $book_id, $borrowed_date, $return_date);
$stmt->execute();

// Update the book status to borrowed
$query = "UPDATE books SET is_borrowed = 1 WHERE id = ?";
$stmt = $mysqli2->prepare($query);
$stmt->bind_param("i", $book_id);
$stmt->execute();

echo json_encode(['status' => 'success', 'message' => 'Book borrowed successfully.', 'book_id' => $book_id]);
?>
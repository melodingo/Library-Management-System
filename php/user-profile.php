<?php
session_start();
$connections = include 'database.php';
$mysqli1 = $connections['login_db'];
$mysqli2 = $connections['library_management'];

$user_id = $_SESSION['user_id'];

// Fetch user details
$query = "SELECT name, email FROM user WHERE id = ?";
$stmt = $mysqli1->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Fetch borrowed books
$query = "SELECT b.title, bb.borrowed_date, bb.return_date 
          FROM borrowed_books bb 
          JOIN books b ON bb.book_id = b.id 
          WHERE bb.user_id = ?";
$stmt = $mysqli2->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$borrowed_books_result = $stmt->get_result();
$borrowed_books = [];
while ($row = $borrowed_books_result->fetch_assoc()) {
    $borrowed_books[] = $row;
}
$stmt->close();
$mysqli1->close();
$mysqli2->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - Library Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-200 text-gray-900 flex items-center justify-center min-h-screen" style="font-family: 'JetBrains Mono', monospace;">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-sm relative">
        <div class="absolute top-1 right-3">
            <a href="/index.php" class="text-gray-500 hover:text-gray-700 text-3xl">&times;</a>
        </div>
        <h1 class="text-2xl font-bold mb-6 text-center">User Profile</h1>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Name</label>
            <p class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm"><?= htmlspecialchars($user["name"]) ?></p>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <p class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm"><?= htmlspecialchars($user["email"]) ?></p>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Borrowed Books</label>
            <?php if (count($borrowed_books) > 0): ?>
                <ul class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                    <?php foreach ($borrowed_books as $book): ?>
                        <li class="mb-2">
                            <strong>Title:</strong> <?= htmlspecialchars($book['title']) ?><br>
                            <strong>Borrowed Date:</strong> <?= htmlspecialchars($book['borrowed_date']) ?><br>
                            <strong>Return Date:</strong> <?= htmlspecialchars($book['return_date']) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm">No borrowed books.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
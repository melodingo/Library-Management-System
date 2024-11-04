<?php
session_start();
// Include the database connections
$connections = include 'database.php';

// Access the connection to the library_management
$mysqli2 = $connections['library_management'];

// Get the book ID from the URL
$book_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Prepare the SQL statement
$sql = "SELECT title, author, genre, published_date, isbn, is_borrowed, image_url FROM books WHERE id = ?";

$stmt = $mysqli2->stmt_init();

if ( ! $stmt->prepare($sql)) {
    die("SQL error: " . $mysqli2->error);
}

// Bind the parameters
$stmt->bind_param("i", $book_id);

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $book = $result->fetch_assoc();
} else {
    die("Book not found");
}

$stmt->close();
$mysqli2->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($book['title']); ?> - Book Details</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* Notification styles */
        .notification {
            display: none;
            position: fixed;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #4caf50;
            color: white;
            padding: 15px;
            border-radius: 5px;
            z-index: 1000;
        }
        .notification.error {
            background-color: #f44336;
        }
    </style> 
</head>
<body class="bg-gray-200 text-gray-900 flex items-center justify-center min-h-screen" style="font-family: 'JetBrains Mono', monospace;">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-lg relative">
        <div class="absolute top-1 right-3">
            <a href="search-book.php" class="text-gray-500 hover:text-gray-700 text-3xl">&times;</a>
        </div>
        <h1 class="text-2xl font-bold mb-6 text-center"><?php echo htmlspecialchars($book['title']); ?></h1>
        <div class="mb-4">
            <img src="<?php echo htmlspecialchars($book['image_url']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>" class="w-full h-auto rounded-md shadow-sm">
        </div>
        <div class="mb-4">
            <p><strong>Author:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
            <p><strong>Genre:</strong> <?php echo htmlspecialchars($book['genre']); ?></p>
            <p><strong>Published Date:</strong> <?php echo htmlspecialchars($book['published_date']); ?></p>
            <p><strong>ISBN:</strong> <?php echo htmlspecialchars($book['isbn']); ?></p>
            <p><strong>Status:</strong> <?php echo $book['is_borrowed'] ? 'Borrowed' : 'Available'; ?></p>
        </div>
        <?php if (!$book['is_borrowed']): ?>
            <div class="text-center">
                <button id="borrowButton" data-book-id="<?php echo $book_id; ?>" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Borrow</button>
            </div>
        <?php endif; ?>
    </div>
    <div id="notification" class="notification"></div>
    <script>
        function showNotification(message, isError = false) {
            const notification = document.getElementById('notification');
            notification.innerText = message;
            notification.classList.toggle('error', isError);
            notification.style.display = 'block';
            setTimeout(() => {
            notification.style.display = 'none';
            }, 3000);
        }

        document.getElementById('borrowButton').addEventListener('click', function() {
            const bookId = this.getAttribute('data-book-id');
            fetch('borrow_book.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `book_id=${bookId}`
            })
            .then(response => response.json())
            .then(data => {
                showNotification(data.message, data.status !== 'success');
                if (data.status === 'success') {
                    setTimeout(() => {
                        location.reload();
                    }, 1000); // Delay the reload to allow the notification to be visible
                }
            });
        });

        // Display notification if message is present in URL
        const urlParams = new URLSearchParams(window.location.search);
        const message = urlParams.get('message');
        if (message) {
            showNotification(message);
        }
    </script>
</body>
</html>
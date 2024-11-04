<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit a Book - Library Management</title>
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
    <div class="bg-white p-8 rounded shadow-md w-full max-w-sm relative">
        <div class="absolute top-1 right-3">
            <a href="/pages/admin-dashboard.php" class="text-gray-500 hover:text-gray-700 text-3xl">&times;</a>
        </div>
        <h1 class="text-2xl font-bold mb-6 text-center">Edit a Book</h1>
        <form action="edit-book.php" method="get" class="mb-6">
            <div class="mb-4">
                <label for="search_title" class="block text-sm font-medium text-gray-700">Search by Title</label>
                <input type="text" id="search_title" name="search_title" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-indigo-500 text-white px-4 py-2 rounded-md hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Search</button>
            </div>
        </form>

        <?php
        if (isset($_GET['search_title'])) {
            // Include the database connections
            $connections = include 'database.php';

            // Access the connection to the library_management
            $mysqli2 = $connections['library_management'];

            // Prepare the SQL statement
            $search_title = $mysqli2->real_escape_string($_GET['search_title']);
            $sql = "SELECT * FROM books WHERE title LIKE '%$search_title%'";

            $result = $mysqli2->query($sql);

            if ($result->num_rows > 0) {
                // Display the book details in a form for editing
                $book = $result->fetch_assoc();
                ?>
                <form action="process-edit-book.php" method="post"> 
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div class="mb-4">
                        <label for="author" class="block text-sm font-medium text-gray-700">Author</label>
                        <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div class="mb-4">
                        <label for="genre" class="block text-sm font-medium text-gray-700">Genre</label>
                        <input type="text" id="genre" name="genre" value="<?php echo htmlspecialchars($book['genre']); ?>" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div class="mb-4">
                        <label for="published_date" class="block text-sm font-medium text-gray-700">Published Date</label>
                        <input type="date" id="published_date" name="published_date" value="<?php echo htmlspecialchars($book['published_date']); ?>" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div class="mb-4">
                        <label for="isbn" class="block text-sm font-medium text-gray-700">ISBN</label>
                        <input type="text" id="isbn" name="isbn" value="<?php echo htmlspecialchars($book['isbn']); ?>" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div class="mb-4">
                        <label for="image_url" class="block text-sm font-medium text-gray-700">Image URL</label>
                        <input type="text" id="image_url" name="image_url" value="<?php echo htmlspecialchars($book['image_url']); ?>" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <input type="hidden" name="id" value="<?php echo $book['id']; ?>">
                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-indigo-500 text-white px-4 py-2 rounded-md hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Update Book</button>
                        <button type="button" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2" onclick="deleteBook(<?php echo $book['id']; ?>)">Delete Book</button>
                    </div>
                </form>
                <?php
            } else {
                echo "<p class='text-red-500'>No book found with that title.</p>";
            }
        }

        if (isset($_GET['message'])) {
            $message = htmlspecialchars($_GET['message']);
            $isError = isset($_GET['error']) && $_GET['error'] == 'true';
            echo "<script>showNotification('$message', $isError);</script>";
        }
        ?>

    </div>

    <!-- Notification -->
    <div id="notification" class="notification"></div>

    <script>
    // Function to show the notification with a message
    function showNotification(message, isError = false) {
        const notification = document.getElementById('notification');
        notification.innerText = message;
        notification.classList.toggle('error', isError);
        notification.style.display = 'block';
        setTimeout(() => {
            notification.style.display = 'none';
        }, 3000);
    }

    // Function to delete a book
    function deleteBook(bookId) {
        if (confirm('Are you sure you want to delete this book?')) {
            window.location.href = 'delete-book.php?id=' + bookId;
        }
    }

    // Check for messages in the URL
    const urlParams = new URLSearchParams(window.location.search);
    const message = urlParams.get('message');
    const isError = urlParams.get('error') === 'true';
    if (message) {
        showNotification(message, isError);
    }
</script>   
</body>
</html> 
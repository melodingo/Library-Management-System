<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search a Book - Library Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-200 text-gray-900 flex items-center justify-center min-h-screen" style="font-family: 'JetBrains Mono', monospace;">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-sm relative">
        <div class="absolute top-1 right-3">
            <a href="/index.php" class="text-gray-500 hover:text-gray-700 text-3xl">&times;</a>
        </div>
        <h1 class="text-2xl font-bold mb-6 text-center">Search a Book</h1>
        <form action="search-book.php" method="get">
            <div class="mb-4">
                <label for="query" class="block text-sm font-medium text-gray-700">Search</label>
                <input type="text" id="query" name="query" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-indigo-500 text-white px-4 py-2 rounded-md hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Search</button>
            </div>
        </form>
        <?php if (isset($_GET['query'])): ?>
            <div class="mt-6">
                <h2 class="text-xl font-bold mb-4">Search Results</h2>
                <?php
                // Include the database connections
                $connections = include 'database.php';

                // Access the connection to the library_management
                $mysqli2 = $connections['library_management'];

                // Prepare the SQL statement
                $query = $mysqli2->real_escape_string($_GET['query']);
                $sql = "SELECT id, title, author, genre, published_date, isbn, is_borrowed FROM books WHERE title LIKE '%$query%' OR author LIKE '%$query%' OR genre LIKE '%$query%'";

                $result = $mysqli2->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='mb-4'>";
                        echo "<h3 class='text-lg font-bold'><a href='book-details.php?id=" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['title']) . "</a></h3>";
                        echo "<p>Author: " . htmlspecialchars($row['author']) . "</p>";
                        echo "<p>Genre: " . htmlspecialchars($row['genre']) . "</p>";
                        echo "<p>Published Date: " . htmlspecialchars($row['published_date']) . "</p>";
                        echo "<p>ISBN: " . htmlspecialchars($row['isbn']) . "</p>";
                        echo "<p>Is Borrowed: " . ($row['is_borrowed'] ? 'Yes' : 'No') . "</p>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No results found</p>";
                }

                $mysqli2->close();
                ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
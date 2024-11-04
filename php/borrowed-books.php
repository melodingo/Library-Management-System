<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrowed Books - Library Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
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
<body class="bg-gray-200 text-gray-900 flex flex-col min-h-screen" style="font-family: 'JetBrains Mono', monospace;">
    <header class="bg-indigo-900 text-gray-400 p-4">
        <nav class="container mx-auto flex justify-between">
            <div>
                <a href="/index.php" class="text-white hover:underline mx-2">Home</a>
                <a href="/php/logout.php" class="text-white hover:underline mx-2">Log out</a>
            </div>
        </nav>
    </header>

    <main class="container mx-auto p-4 bg-gray-100 shadow-md mt-4 flex-grow">
        <section class="mb-8">
            <h1 class="text-2xl font-bold mb-4">Borrowed Books</h1>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">Book Title</th>
                            <th class="py-2 px-4 border-b">Borrowed By</th>
                            <th class="py-2 px-4 border-b">Borrowed Date</th>
                            <th class="py-2 px-4 border-b">Return Date</th>
                            <th class="py-2 px-4 border-b">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $connections = include 'database.php';
                        $mysqli1 = $connections['login_db'];
                        $mysqli2 = $connections['library_management'];

                        $query = "SELECT bb.id as borrowed_id, b.title, u.name, bb.borrowed_date, bb.return_date 
                                  FROM library_management.borrowed_books bb 
                                  JOIN library_management.books b ON bb.book_id = b.id 
                                  JOIN login_db.user u ON bb.user_id = u.id";
                        $result = $mysqli2->query($query);
                        while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="py-2 px-4 border-b"><?php echo $row['title']; ?></td>
                                <td class="py-2 px-4 border-b"><?php echo $row['name']; ?></td>
                                <td class="py-2 px-4 border-b"><?php echo $row['borrowed_date']; ?></td>
                                <td class="py-2 px-4 border-b"><?php echo $row['return_date']; ?></td>
                                <td class="py-2 px-4 border-b">
                                    <form action="return_book.php" method="POST" onsubmit="return handleReturn(event, <?php echo $row['borrowed_id']; ?>)">
                                        <input type="hidden" name="borrowed_id" value="<?php echo $row['borrowed_id']; ?>">
                                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700">Return</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
    <div id="notification" class="notification"></div>
    <script>
        function showNotification(message, isError = false) {
            const notification = document.getElementById('notification');
            notification.innerText = message;
            notification.classList.toggle('error', isError);
            notification.style.display = 'block';
            setTimeout(() => {
                notification.style.display = 'none';
            }, 1000);
        }

        function handleReturn(event, borrowedId) {
            event.preventDefault();
            fetch('return_book.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `borrowed_id=${borrowedId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    window.location.href = `borrowed-books.php?message=${encodeURIComponent(data.message)}`;
                } else {
                    showNotification(data.message, true);
                }
            });
        }

        // Display notification if message is present in URL
        const urlParams = new URLSearchParams(window.location.search);
        const message = urlParams.get('message');
        if (message) {
            showNotification(message);
        }
    </script>
</body>
</html>
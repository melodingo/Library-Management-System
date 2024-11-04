<?php
session_start();

if (isset($_SESSION["user_id"])) {
    
    $connections = require __DIR__ . "/php/database.php";
    $mysqli1 = $connections['login_db'];
    
    $sql = "SELECT * FROM user
            WHERE id = {$_SESSION["user_id"]}";
            
    $result = $mysqli1->query($sql);
    
    $user = $result->fetch_assoc();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

<body class="bg-gray-200 text-gray-900 flex flex-col min-h-screen" style="font-family: 'JetBrains Mono', monospace;">
    <header class="bg-gray-900 text-gray-400 p-4">
        <nav class="container mx-auto flex justify-between">
            <div>
                <?php if (isset($_SESSION["user_id"])): ?>
                    <span class="text-white mx-2">Hello <?= htmlspecialchars($user["name"]) ?></span>
                    <a href="/php/logout.php" class="text-white hover:underline mx-2">Log out</a>
                    <?php if (isset($_SESSION["is_admin"]) && $_SESSION["is_admin"]): ?>
                        <a href="/pages/admin-dashboard.php" class="text-white hover:underline mx-2">Admin Dashboard</a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="/php/login.php" class="text-white hover:underline mx-2">Login</a>
                    <a href="/pages/signup.html" class="text-white hover:underline mx-2">Sign up</a>
                <?php endif; ?>
            </div>
            <div>
                <?php if (isset($_SESSION["user_id"])): ?>
                    <a href="/php/user-profile.php" class="text-white hover:underline mx-2">Profile</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <main class="container mx-auto p-4 bg-gray-100 shadow-md mt-4 flex-grow">
        <section class="mb-8">
            <h1 class="text-2xl font-bold mb-4">Welcome to Library Management</h1>
            <p class="text-base sm:text-lg">Library Management is a web application that helps you manage your library. You can add, edit, delete, and search books in your library.</p>
        </section>

        <section class="mb-8">
            <h2 class="text-xl font-semibold mb-4">Features</h2>
            <ul class="list-disc list-inside">
                <li>Add a book</li>
                <li>Edit a book</li>
                <li>Delete a book</li>
                <li><a href="/php/search-book.php" class="text-blue-500 hover:underline">Search a book</a></li>
            </ul>
        </section>

        <section>
            <h2 class="text-xl font-semibold mb-4">Contact Us</h2>
            <address>
                <p>Email: <a href="mailto:test@home.com" class="text-blue-500 hover:underline">test@home.com</a></p>
            </address>
        </section>
    </main>

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
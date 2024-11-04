<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Library Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="/js/scripts.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
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
            <h1 class="text-2xl font-bold mb-4">Admin Dashboard</h1>
            <p class="text-base sm:text-lg">Welcome to the admin dashboard. Here you can manage the library system.</p>
        </section>

        <section class="mb-8">
            <h2 class="text-xl font-semibold mb-4">Admin Actions</h2>
            <ul class="list-disc list-inside">
                <li><a href="/php/add-book.php" class="text-blue-500 hover:underline">Add a book</a></li>
                <li><a href="/php/edit-book.php" class="text-blue-500 hover:underline">Edit a book</a></li>
                <li><a href="/pages/manage-users.html" class="text-blue-500 hover:underline">Manage users</a></li>
                <li><a href="/php/search-book.php" class="text-blue-500 hover:underline">Search a book</a></li>
                <li><a href="/php/borrowed-books.php" class="text-blue-500 hover:underline">Borrowed books</a></li>
            </ul>
        </section>
    </main>
</body>
</html>
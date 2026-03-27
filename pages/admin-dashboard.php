<?php
session_start();

if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
    unset($_SESSION['error_message']);
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin-Dashboard - Bibliotheksverwaltung</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="/js/scripts.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        .modern-shell {
            background-image: radial-gradient(circle at top right, #f3f4f6 0%, #e5e7eb 55%, #d1d5db 100%);
        }

        .soft-card {
            border: 1px solid rgba(17, 24, 39, 0.08);
            box-shadow: 0 10px 26px -18px rgba(17, 24, 39, 0.55);
        }

        .nav-link {
            transition: color 160ms ease, background-color 160ms ease;
        }

        .nav-link:hover {
            color: #ffffff;
        }
    </style>
</head>
<body class="modern-shell text-gray-900 flex flex-col min-h-screen" style="font-family: 'JetBrains Mono', monospace;">
    <header class="bg-indigo-900 text-gray-300 border-b border-indigo-700/70 shadow-sm">
        <nav class="container mx-auto px-4 py-4 flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2">
                <a href="/index.php" class="nav-link bg-indigo-800 hover:bg-indigo-700 px-3 py-1.5 rounded-md">Startseite</a>
                <a href="/php/logout.php" class="nav-link bg-indigo-800 hover:bg-indigo-700 px-3 py-1.5 rounded-md">Abmelden</a>
            </div>
        </nav>
    </header>

    <main class="container mx-auto w-full max-w-5xl px-4 py-6 sm:py-8 flex-grow">
        <div class="soft-card bg-gray-50/95 rounded-xl p-6 sm:p-8">
            <section class="mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight mb-3 text-gray-900">Admin-Dashboard</h1>
                <p class="text-base sm:text-lg leading-relaxed text-gray-700">Willkommen im Admin-Dashboard. Hier kannst du das Bibliothekssystem verwalten.</p>
            </section>

            <section class="rounded-lg border border-gray-200 bg-white p-5 sm:p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-900">Admin-Aktionen</h2>
                <ul class="list-disc list-inside space-y-1.5 text-gray-700">
                    <li><a href="/php/add-book.php" class="text-blue-600 hover:text-blue-700 hover:underline">Buch hinzufügen</a></li>
                    <li><a href="/php/edit-book.php" class="text-blue-600 hover:text-blue-700 hover:underline">Buch bearbeiten</a></li>
                    <li><a href="/pages/manage-users.php" class="text-blue-600 hover:text-blue-700 hover:underline">Kunden verwalten</a></li>
                    <li><a href="/pages/manage-Benutzer.php" class="text-blue-600 hover:text-blue-700 hover:underline">Benutzer verwalten</a></li>
                    <li><a href="/php/search-book.php" class="text-blue-600 hover:text-blue-700 hover:underline">Buch suchen</a></li>
                </ul>
            </section>
        </div>
    </main>
</body>
</html>
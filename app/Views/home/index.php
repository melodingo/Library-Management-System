<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="/js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    <header class="bg-gray-900 text-gray-300 border-b border-gray-700/70 shadow-sm">
        <nav class="container mx-auto px-4 py-4 flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2">
                <?php if (!empty($user)): ?>
                    <span class="text-white/95 mr-2">Hallo <?= htmlspecialchars((string) ($user['vorname'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>
                    <a href="/logout" class="nav-link bg-gray-800 hover:bg-gray-700 px-3 py-1.5 rounded-md">Abmelden</a>
                    <?php if (!empty($_SESSION['is_admin'])): ?>
                        <a href="/pages/admin-dashboard.php" class="nav-link bg-gray-800 hover:bg-gray-700 px-3 py-1.5 rounded-md">Admin-Dashboard</a>
                        <a href="/pages/signup.html" class="nav-link bg-gray-800 hover:bg-gray-700 px-3 py-1.5 rounded-md">Registrieren</a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="/login" class="nav-link bg-gray-800 hover:bg-gray-700 px-3 py-1.5 rounded-md">Anmelden</a>
                <?php endif; ?>
            </div>
            <div class="flex items-center gap-2">
                <?php if (!empty($user)): ?>
                    <a href="/profile" class="nav-link bg-gray-800 hover:bg-gray-700 px-3 py-1.5 rounded-md">Profil</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <main class="container mx-auto w-full max-w-5xl px-4 py-6 sm:py-8 flex-grow">
        <div class="soft-card bg-gray-50/95 rounded-xl p-6 sm:p-8">
        <section class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight mb-3 text-gray-900">Willkommen bei der Bibliotheksverwaltung</h1>
            <p class="text-base sm:text-lg leading-relaxed text-gray-700">Diese Webanwendung hilft dir bei der Verwaltung deiner Bibliothek. Du kannst Buecher hinzufuegen, bearbeiten, loeschen und durchsuchen.</p>
        </section>

        <section class="mb-8 rounded-lg border border-gray-200 bg-white p-5 sm:p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-900">Funktionen</h2>
            <ul class="list-disc list-inside space-y-1.5 text-gray-700">
                <li>Buch hinzufuegen</li>
                <li>Buch bearbeiten</li>
                <li>Buch loeschen</li>
                <li><a href="/books/search" class="text-blue-600 hover:text-blue-700 hover:underline">Buch suchen</a></li>
            </ul>
        </section>

        <section class="rounded-lg border border-gray-200 bg-white p-5 sm:p-6">
            <h2 class="text-xl font-semibold mb-3 text-gray-900">Kontakt</h2>
            <address class="not-italic text-gray-700">
                <p>E-Mail: <a href="mailto:test@home.com" class="text-blue-600 hover:text-blue-700 hover:underline">test@home.com</a></p>
            </address>
        </section>
        </div>
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
            }, 3000);
        }

        const urlParams = new URLSearchParams(window.location.search);
        const message = urlParams.get('message');
        const isError = urlParams.get('error') === 'true';
        if (message) {
            showNotification(message, isError);
        }
    </script>
</body>
</html>

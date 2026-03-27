<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Benutzerprofil - Bibliotheksverwaltung</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        .modern-shell {
            background-image: radial-gradient(circle at top right, #f3f4f6 0%, #e5e7eb 55%, #d1d5db 100%);
        }

        .soft-card {
            border: 1px solid rgba(17, 24, 39, 0.08);
            box-shadow: 0 10px 26px -18px rgba(17, 24, 39, 0.55);
        }

        .info-row {
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            background: #ffffff;
            padding: 0.85rem;
        }

        .modal-input {
            border: 1px solid #d1d5db;
            transition: border-color 160ms ease, box-shadow 160ms ease;
        }

        .modal-input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
            outline: none;
        }

        .notification {
            display: none;
            position: fixed;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #16a34a;
            color: white;
            padding: 12px 16px;
            border-radius: 8px;
            z-index: 1000;
            box-shadow: 0 8px 20px -12px rgba(0, 0, 0, 0.55);
        }
        .notification.error {
            background-color: #dc2626;
        }
    </style>
</head>
<body class="modern-shell text-gray-900 min-h-screen" style="font-family: 'JetBrains Mono', monospace;">
    <main class="container mx-auto w-full max-w-3xl px-4 py-6 sm:py-8">
    <div class="soft-card bg-gray-50/95 p-6 sm:p-8 rounded-xl w-full relative">
        <div class="absolute top-3 right-4">
            <a href="/" class="inline-flex items-center justify-center h-9 w-9 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-md text-2xl leading-none">&times;</a>
        </div>
        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight mb-6 text-center">Benutzerprofil</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div class="info-row">
            <label class="block text-sm font-medium text-gray-700 mb-1">Benutzername</label>
            <p class="text-gray-900 sm:text-sm"><?= htmlspecialchars((string) ($user['benutzername'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
        </div>
        <div class="info-row">
            <label class="block text-sm font-medium text-gray-700 mb-1">Vorname</label>
            <p class="text-gray-900 sm:text-sm"><?= htmlspecialchars((string) ($user['vorname'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
        </div>
        <div class="info-row">
            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <p class="text-gray-900 sm:text-sm"><?= htmlspecialchars((string) ($user['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
        </div>
        <div class="info-row">
            <label class="block text-sm font-medium text-gray-700 mb-1">E-Mail</label>
            <p class="text-gray-900 sm:text-sm break-all"><?= htmlspecialchars((string) ($user['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
        </div>
        </div>

        <button onclick="openPasswordModal()" class="w-full mt-6 px-4 py-2.5 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">Passwort aendern</button>
    </div>
    </main>

    <div id="passwordModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="relative top-16 sm:top-20 mx-auto p-5 border border-gray-200 w-11/12 max-w-md shadow-lg rounded-xl bg-white">
            <h3 class="text-lg font-medium mb-4">Passwort aendern</h3>
            <form id="passwordForm">
                <input type="password" id="oldPassword" required class="modal-input w-full px-3 py-2 rounded mb-2" placeholder="Altes Passwort">
                <input type="password" id="newPassword" required minlength="8" class="modal-input w-full px-3 py-2 rounded mb-2" placeholder="Neues Passwort">
                <div class="flex justify-end">
                    <button type="button" onclick="closePasswordModal()" class="mr-2 px-4 py-2 text-gray-600 hover:text-gray-800">Abbrechen</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">Speichern</button>
                </div>
            </form>
        </div>
    </div>

    <div id="notification" class="notification"></div>

    <script>
        function openPasswordModal() {
            document.getElementById('passwordModal').classList.remove('hidden');
        }

        function closePasswordModal() {
            document.getElementById('passwordModal').classList.add('hidden');
        }

        document.getElementById('passwordForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const oldPassword = document.getElementById('oldPassword').value;
            const newPassword = document.getElementById('newPassword').value;

            if (newPassword.length < 8) {
                showNotification('Neues Passwort muss mindestens 8 Zeichen lang sein.', true);
                return;
            }

            const res = await fetch('/php/change-password.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ oldPassword, newPassword })
            });

            const result = await res.json();
            showNotification(result.message, !result.success);
            if (result.success) closePasswordModal();
        });

        function showNotification(msg, isError = false) {
            const el = document.getElementById('notification');
            el.textContent = msg;
            el.classList.toggle('error', isError);
            el.style.display = 'block';
            setTimeout(() => el.style.display = 'none', 3000);
        }
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Benutzerverwaltung</title>
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

    <!-- Passwort -->
    <div id="passwordModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-medium mb-4">Passwort ändern</h3>
            <form id="passwordForm">
                <input type="password" id="oldPassword" required class="w-full px-3 py-2 border rounded mb-2" placeholder="Altes Passwort">
                <input type="password" id="newPassword" required minlength="8" class="w-full px-3 py-2 border rounded mb-2" placeholder="Neues Passwort">
                <div class="flex justify-end">
                    <button type="button" onclick="closePasswordModal()" class="mr-2 px-4 py-2 text-gray-600">Abbrechen</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Speichern</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Benutzerformular -->
    <div id="userModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-medium mb-4" id="modalTitle">Neuen Benutzer erstellen</h3>
            <form id="userForm">
                <input type="hidden" id="userId">
                <input type="text" id="benutzername" maxlength="45" required class="w-full px-3 py-2 border rounded mb-2" placeholder="Benutzername">
                <input type="text" id="name" maxlength="45" required class="w-full px-3 py-2 border rounded mb-2" placeholder="Name">
                <input type="text" id="vorname" maxlength="45" required class="w-full px-3 py-2 border rounded mb-2" placeholder="Vorname">
                <input type="email" id="email" maxlength="100" required class="w-full px-3 py-2 border rounded mb-2" placeholder="E-Mail">
                <div id="passwordContainer" class="mb-2">
                    <input type="password" id="password" minlength="8" class="w-full px-3 py-2 border rounded" placeholder="Passwort (nur bei Neuerstellung)">
                </div>
                <select id="admin" class="w-full px-3 py-2 border rounded mb-4">
                    <option value="1">Administrator</option>
                    <option value="0">Normaler Benutzer</option>
                </select>
                <div class="flex justify-end">
                    <button type="button" onclick="closeModal()" class="mr-2 px-4 py-2 text-gray-600">Abbrechen</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Speichern</button>
                </div>
            </form>
        </div>
    </div>

    <header class="bg-indigo-900 text-white p-4">
        <nav class="container mx-auto flex justify-between">
            <div>
                <a href="/pages/admin-dashboard.php" class="hover:underline mx-2">Startseite</a>
                <a href="/php/logout.php" class="hover:underline mx-2">Abmelden</a>
            </div>
        </nav>
    </header>

    <main class="container mx-auto p-4 bg-gray-100 shadow-md mt-4 flex-grow">
        <section class="mb-8">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">Benutzerverwaltung</h1>
                <button onclick="openModal('create')" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    + Neuer Benutzer
                </button>
            </div>
            <div class="flex justify-between mb-4">
                <input type="text" id="searchInput" class="px-4 py-2 border rounded w-1/3" placeholder="🔍 Suche...">
                <select id="sortSelect" class="px-4 py-2 border rounded">
                    <option value="benutzername">Benutzername</option>
                    <option value="name">Name</option>
                    <option value="vorname">Vorname</option>
                    <option value="email">E-Mail</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">Benutzername</th>
                        <th class="py-2 px-4 border-b">Name</th>
                        <th class="py-2 px-4 border-b">Vorname</th>
                        <th class="py-2 px-4 border-b">E-Mail</th>
                        <th class="py-2 px-4 border-b">Admin</th>
                        <th class="py-2 px-4 border-b">Aktionen</th>
                    </tr>
                </thead>
                <tbody id="userTableBody"></tbody>
            </table>
        </section>
    </main>

    <div id="notification" class="notification"></div>

    <script>
        let usersData = [];

        document.addEventListener('DOMContentLoaded', () => {
            fetchUsers();
            document.getElementById('userForm').addEventListener('submit', saveUser);
            document.getElementById('passwordForm').addEventListener('submit', changePassword);
            document.getElementById('searchInput').addEventListener('input', () => {
                const filtered = filterUsers(usersData);
                renderUsers(filtered);
            });

            document.getElementById('sortSelect').addEventListener('change', () => {
                const filtered = filterUsers(usersData);
                renderUsers(filtered);
            });
        });

        async function fetchUsers() {
            const res = await fetch('/php/fetch-benutzer.php');
            usersData = await res.json();
            renderUsers(usersData);
        }

        function renderUsers(users) {
            const tbody = document.getElementById('userTableBody');
            tbody.innerHTML = users.map(u => `
                <tr>
                    <td class="py-2 px-4 border-b">${u.benutzername}</td>
                    <td class="py-2 px-4 border-b">${u.name}</td>
                    <td class="py-2 px-4 border-b">${u.vorname}</td>
                    <td class="py-2 px-4 border-b">${u.email}</td>
                    <td class="py-2 px-4 border-b">${u.admin ? '✅' : '❌'}</td>
                    <td class="py-2 px-4 border-b">
                        <button onclick='openModal("edit", ${JSON.stringify(u)})' class="bg-blue-500 text-white px-3 py-1 rounded">Bearbeiten</button>
                        <button onclick='deleteUser(${u.ID})' class="bg-red-500 text-white px-3 py-1 rounded">Löschen</button>
                    </td>
                </tr>`).join('');
        }

        function openModal(mode, user = {}) {
            document.getElementById('userModal').classList.remove('hidden');
            document.getElementById('modalTitle').textContent = mode === 'edit' ? 'Benutzer bearbeiten' : 'Neuen Benutzer erstellen';
            document.getElementById('userId').value = user.ID || '';
            document.getElementById('benutzername').value = user.benutzername || '';
            document.getElementById('name').value = user.name || '';
            document.getElementById('vorname').value = user.vorname || '';
            document.getElementById('email').value = user.email || '';
            document.getElementById('password').value = '';
            document.getElementById('admin').value = user.admin || '0';
        }

        function closeModal() {
            document.getElementById('userModal').classList.add('hidden');
        }

        async function saveUser(e) {
            e.preventDefault();
            const data = {
                id: document.getElementById('userId').value,
                benutzername: document.getElementById('benutzername').value.trim(),
                name: document.getElementById('name').value.trim(),
                vorname: document.getElementById('vorname').value.trim(),
                email: document.getElementById('email').value.trim(),
                password: document.getElementById('password').value,
                admin: document.getElementById('admin').value
            };

            if (!data.benutzername || !data.name || !data.vorname || !data.email || (data.id === '' && data.password.length < 8)) {
                showNotification('Alle Felder müssen ausgefüllt sein. Passwort mindestens 8 Zeichen.', true);
                return;
            }

            const res = await fetch('/php/save-benutzer.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const result = await res.json();
            showNotification(result.message, !result.success);
            if (result.success) {
                closeModal();
                fetchUsers();
            }
        }

        async function deleteUser(id) {  
            if (!confirm('Benutzer wirklich löschen?')) return;
            const res = await fetch('/php/delete-benutzer.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id })
            });
            const result = await res.json();
            showNotification(result.message, !result.success);
            if (result.success) fetchUsers();
        }

        function openPasswordModal() {
            document.getElementById('passwordModal').classList.remove('hidden');
        }

        function closePasswordModal() {
            document.getElementById('passwordModal').classList.add('hidden');
        }

        function showNotification(msg, isError = false) {
            const el = document.getElementById('notification');
            el.textContent = msg;
            el.classList.toggle('error', isError);
            el.style.display = 'block';
            setTimeout(() => el.style.display = 'none', 3000);
        }

        function filterUsers() {
            const query = document.getElementById('searchInput').value.toLowerCase();
            const sortBy = document.getElementById('sortSelect').value;

            let filtered = usersData.filter(u =>
                u.benutzername.toLowerCase().includes(query) ||
                u.name.toLowerCase().includes(query) ||
                u.vorname.toLowerCase().includes(query) ||
                u.email.toLowerCase().includes(query) ||
                (u.admin ? 'admin ✅ ja' : 'user ❌ nein').includes(query)
            );

            filtered.sort((a, b) => {
                const valA = (a[sortBy] ?? '').toString().toLowerCase();
                const valB = (b[sortBy] ?? '').toString().toLowerCase();
                if (valA < valB) return -1;
                if (valA > valB) return 1;
                return 0;
            });

            renderUsers(filtered);
        }

        document.getElementById('searchInput').addEventListener('input', filterUsers);
        document.getElementById('sortSelect').addEventListener('change', filterUsers);
    </script>
</body>
</html>

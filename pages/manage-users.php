<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kundenverwaltung</title>
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

    <!-- MODAL -->
    <div id="customerModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg font-medium mb-4" id="modalTitle">Neuen Kunden erstellen</h3>
                <form id="customerForm">
                    <input type="hidden" id="customerId">
                    <div class="mb-4">
                        <input type="text" id="customerVorname" required class="w-full px-3 py-2 border rounded" placeholder="Vorname">
                    </div>
                    <div class="mb-4">
                        <input type="text" id="customerName" required class="w-full px-3 py-2 border rounded" placeholder="Nachname">
                    </div>
                    <div class="mb-4">
                        <input type="email" id="customerEmail" required class="w-full px-3 py-2 border rounded" placeholder="Email">
                    </div>
                    <div class="mb-4">
                        <label for="customerGeburtstag" class="block text-sm font-medium text-gray-700">Geburtstag</label>
                        <input type="date" id="customerGeburtstag" class="w-full px-3 py-2 border rounded" placeholder="Geburtstag">
                    </div>
                    <div class="mb-4">
                        <select id="customerGeschlecht" class="w-full px-3 py-2 border rounded">
                            <option value="m">Männlich</option>
                            <option value="w">Weiblich</option>
                            <option value="d">Divers</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="customerKundeSeit" class="block text-sm font-medium text-gray-700">Kunde seit</label>
                        <input type="date" id="customerKundeSeit" class="w-full px-3 py-2 border rounded" placeholder="Kunde seit">
                    </div>      
                    <div class="mb-4">
                        <select id="customerKontakt" class="w-full px-3 py-2 border rounded">
                            <option value="1">Kontakt per Mail erlaubt</option>
                            <option value="0">Kein Mail-Kontakt</option>
                        </select>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" onclick="closeModal()" class="mr-2 px-4 py-2 text-gray-600">Abbrechen</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Speichern</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- HEADER -->
    <header class="bg-indigo-900 text-gray-400 p-4">
        <nav class="container mx-auto flex justify-between">
            <div>
                <a href="/pages/admin-dashboard.php" class="text-white hover:underline mx-2">Startseite</a>
                <a href="/php/logout.php" class="text-white hover:underline mx-2">Abmelden</a>
            </div>
        </nav>
    </header>

    <!-- MAIN -->
    <main class="container mx-auto p-4 bg-gray-100 shadow-md mt-4 flex-grow">
        <section class="mb-8">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">Kundenverwaltung</h1>
                <button onclick="openModal('create')" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    + Neuer Kunde
                </button>
            </div>

            <div class="flex justify-between mb-4">
                <input type="text" id="searchInput" class="px-4 py-2 border rounded w-1/3" placeholder="🔍 Suche nach Name, Vorname oder E-Mail">
                <select id="sortSelect" class="px-4 py-2 border rounded">
                    <option value="name">Nach Name sortieren</option>
                    <option value="vorname">Nach Vorname sortieren</option>
                    <option value="kunde_seit">Nach Kunde seit sortieren</option>
                    <option value="kontaktpermail">Nach Kontaktstatus sortieren</option>
                </select>
            </div>

            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">Vorname</th>
                        <th class="py-2 px-4 border-b">Name</th>
                        <th class="py-2 px-4 border-b">Email</th>
                        <th class="py-2 px-4 border-b">Geburtstag</th>
                        <th class="py-2 px-4 border-b">Kunde seit</th>
                        <th class="py-2 px-4 border-b">Kontakt</th>
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
            document.getElementById('customerForm').addEventListener('submit', saveCustomer);
            document.getElementById('searchInput').addEventListener('input', filterUsers);
            document.getElementById('sortSelect').addEventListener('change', sortUsers);
        });

        function openModal(mode, customer = null) {
            const form = document.getElementById('customerForm');
            form.reset();

            if (mode === 'edit' && customer) {
                document.getElementById('modalTitle').textContent = 'Kunde bearbeiten';
                document.getElementById('customerId').value = customer.kid;  //dieser scheiss "kid" anstelle von "id" hat hat mich den ganzen fu**ing nachmittag gekostet wieso kid?? und nicht einfach noormal id ;-;
                document.getElementById('customerVorname').value = customer.vorname;
                document.getElementById('customerName').value = customer.name;
                document.getElementById('customerEmail').value = customer.email;
                document.getElementById('customerGeburtstag').value = customer.geburtstag;
                document.getElementById('customerGeschlecht').value = customer.geschlecht;
                document.getElementById('customerKundeSeit').value = customer.kunde_seit;
                document.getElementById('customerKontakt').value = customer.kontaktpermail ? '1' : '0';
            } else {
                document.getElementById('modalTitle').textContent = 'Neuen Kunden erstellen';
            }

            document.getElementById('customerModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('customerModal').classList.add('hidden');
        }

        async function fetchUsers() {
            try {
                const response = await fetch('/php/fetch-users.php');
                usersData = await response.json();
                renderUsers(usersData);
            } catch (err) {
                showNotification('Fehler beim Laden der Daten', true);
            }
        }

        function renderUsers(users = usersData) {
            const tbody = document.getElementById('userTableBody');
            tbody.innerHTML = users.map(user => `
                <tr>
                    <td class="py-2 px-4 border-b">${escapeHTML(user.vorname)}</td>
                    <td class="py-2 px-4 border-b">${escapeHTML(user.name)}</td>
                    <td class="py-2 px-4 border-b">${escapeHTML(user.email)}</td>
                    <td class="py-2 px-4 border-b">${new Date(user.kunde_seit).toLocaleDateString()}</td>
                    <td class="py-2 px-4 border-b">${user.kontaktpermail ? '✅' : '❌'}</td>
                    <td class="py-2 px-4 border-b space-x-2">
                        <button onclick='openModal("edit", ${JSON.stringify(user).replace(/'/g, "\\'")})' class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">Bearbeiten</button>
                        <button onclick="deleteCustomer(${user.kid})" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Löschen</button>
                    </td>
                </tr>
            `).join('');
        }

        async function saveCustomer(e) {
            e.preventDefault();

            const data = {
                kid: document.getElementById('customerId').value,
                vorname: document.getElementById('customerVorname').value,
                name: document.getElementById('customerName').value,
                email: document.getElementById('customerEmail').value,
                geburtstag: document.getElementById('customerGeburtstag').value,
                geschlecht: document.getElementById('customerGeschlecht').value,
                kunde_seit: document.getElementById('customerKundeSeit').value,
                kontaktpermail: document.getElementById('customerKontakt').value === '1'
            };

            try {
                const response = await fetch('/php/save-customer.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                showNotification(result.message, !result.success);
                if (result.success) {
                    closeModal();
                    await fetchUsers();
                }
            } catch (err) {
                showNotification('Fehler beim Speichern', true);
            }
        }

        async function deleteCustomer(id) {
            if (!confirm('Diesen Kunden wirklich löschen?')) return;

            const response = await fetch('/php/remove-user.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id })
            });

            const result = await response.json();
            showNotification(result.message, !result.success);
            if (result.success) await fetchUsers();
        }

        function escapeHTML(str) {
            return str.replace(/&/g, "&amp;")
                      .replace(/</g, "&lt;")
                      .replace(/>/g, "&gt;")
                      .replace(/"/g, "&quot;")
                      .replace(/'/g, "&#039;");
        }

        function showNotification(message, isError = false) {
            const el = document.getElementById('notification');
            el.textContent = message;
            el.classList.toggle('error', isError);
            el.style.display = 'block';
            setTimeout(() => el.style.display = 'none', 3000);
        }

        function filterUsers() {
            const term = document.getElementById('searchInput').value.toLowerCase();
            const filtered = usersData.filter(u =>
                u.vorname.toLowerCase().includes(term) ||
                u.name.toLowerCase().includes(term) ||
                u.email.toLowerCase().includes(term)
            );
            renderUsers(filtered);
        }

        function sortUsers() {
            const key = document.getElementById('sortSelect').value;
            usersData.sort((a, b) => {
                if (a[key] < b[key]) return -1;
                if (a[key] > b[key]) return 1;
                return 0;
            });
            renderUsers();
        }
    </script>
</body>
</html>

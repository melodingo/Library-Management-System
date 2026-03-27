<?php
session_start();
$connections = include 'database.php';
$mysqli2 = $connections['books'];

$book_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT id, nummer, Title, autor, kategorie, katalog, zustand, foto, Beschreibung, verfasser 
        FROM buecher 
        WHERE id = ?";

$stmt = $mysqli2->stmt_init();

if (!$stmt->prepare($sql)) {
    die("SQL-Fehler: " . $mysqli2->error);
}

$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Buch nicht gefunden");
}

$kategorien = [
    "1" => "Alte Drucke, Bibeln, Klassische Autoren...",
    "2" => "Geographie und Reisen",
    "3" => "Geschichtswissenschaften",
    "4" => "Naturwissenschaften",
    "5" => "Kinderbücher",
    "6" => "Moderne Literatur und Kunst",
    "7" => "Moderne Kunst und Künstlergraphik",
    "8" => "Kunstwissenschaften",
    "9" => "Architektur",
    "10" => "Technik",
    "11" => "Naturwissenschaften - Medizin",
    "12" => "Ozeanien",
    "13" => "Afrika",
    "14" => "Alte Bücher"
];

$book = $result->fetch_assoc();
$stmt->close();
$mysqli2->close();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($book['Title'] ?: 'Buchdetails', ENT_QUOTES, 'UTF-8') ?> - Buchdetails</title>
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

        .detail-box {
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            background: #ffffff;
            padding: 0.9rem;
        }

        .cover-stage {
            position: relative;
            width: 100%;
            min-height: 420px;
            border: 1px solid #d1d5db;
            border-radius: 0.75rem;
            background: linear-gradient(160deg, #f9fafb 0%, #e5e7eb 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            padding: 1rem;
        }

        .cover-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 10px 16px rgba(17, 24, 39, 0.25));
            border-radius: 0.35rem;
        }

        .cover-fallback {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            color: #6b7280;
            text-align: center;
        }

        .cover-fallback svg {
            width: 48px;
            height: 48px;
            opacity: 0.8;
        }

        .notification {
            display: none;
            position: fixed;
            top: 12px;
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
    <main class="container mx-auto w-full max-w-5xl px-4 py-6 sm:py-8">
    <div class="soft-card bg-gray-50/95 p-6 sm:p-8 rounded-xl w-full relative">
        <div class="absolute top-3 right-4">
            <a href="search-book.php" class="inline-flex items-center justify-center h-9 w-9 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-md text-2xl leading-none">&times;</a>
        </div>
        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight mb-6 text-center"><?= htmlspecialchars($book['Title'] ?: 'Kein Titel', ENT_QUOTES, 'UTF-8') ?></h1>
        
        <div class="mb-6 rounded-lg border border-gray-200 bg-white p-4 sm:p-5">
            <div class="cover-stage mb-2">
                <?php if (!empty($book['foto'])): ?>
                    <img
                        src="<?= htmlspecialchars($book['foto'], ENT_QUOTES, 'UTF-8') ?>"
                        alt="Buchcover"
                        class="cover-image"
                        onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');"
                    >
                    <div class="cover-fallback hidden">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M6 3.75C6 3.34 6.34 3 6.75 3H17.25C17.66 3 18 3.34 18 3.75V20.25C18 20.66 17.66 21 17.25 21H6.75C6.34 21 6 20.66 6 20.25V3.75Z" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M9 7.5H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M9 11H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M9 14.5H12.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        <p class="font-semibold">Buchcover nicht verfügbar</p>
                        <p class="text-sm">Kein gültiges Bild hinterlegt</p>
                    </div>
                <?php else: ?>
                    <div class="cover-fallback">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M6 3.75C6 3.34 6.34 3 6.75 3H17.25C17.66 3 18 3.34 18 3.75V20.25C18 20.66 17.66 21 17.25 21H6.75C6.34 21 6 20.66 6 20.25V3.75Z" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M9 7.5H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M9 11H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M9 14.5H12.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        <p class="font-semibold">Buchcover nicht verfügbar</p>
                        <p class="text-sm">Kein Bild hinterlegt</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6">
            <div class="detail-box">
                <p class="font-bold">Nummer:</p>
                <p><?= htmlspecialchars((string)($book['nummer'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></p>
            </div>
            <div class="detail-box">
                <p class="font-bold">Katalog:</p>
                <p><?= htmlspecialchars((string)($book['katalog'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></p>
            </div>
            <div class="detail-box">
                <p class="font-bold">Autor:</p>
                <p><?= htmlspecialchars((string)($book['autor'] ?? 'Unbekannt'), ENT_QUOTES, 'UTF-8') ?></p>
            </div>
            <div class="detail-box">
            <p class="font-bold">Kategorie:</p>
            <p>
                <?= isset($kategorien[$book['kategorie']]) 
                    ? htmlspecialchars($kategorien[$book['kategorie']]) 
                    : 'Unbekannt' ?>
            </p>
            </div>
            <div class="detail-box">
                <p class="font-bold">Verfasser:</p>
                <p><?= htmlspecialchars((string)($book['verfasser'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></p>
            </div>
            <div class="detail-box">
                <p class="font-bold">Zustand:</p>
                <p><?= htmlspecialchars((string)($book['zustand'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></p>
            </div>
        </div>

        <?php if (!empty($book['Beschreibung'])): ?>
        <div class="mb-6 rounded-lg border border-gray-200 bg-white p-5">
            <h2 class="text-xl font-bold mb-2">Beschreibung</h2>
            <p class="whitespace-pre-wrap text-gray-700 leading-relaxed"><?= htmlspecialchars($book['Beschreibung'], ENT_QUOTES, 'UTF-8') ?></p>
        </div>
        <?php endif; ?>

        <?php if ($book['zustand'] === 'Verfügbar'): ?>
        <div class="text-center">
            <button id="borrowButton" data-book-id="<?= $book['id'] ?>" 
                class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors">
                Buch ausleihen
            </button>
        </div>
        <?php endif; ?>
    </div>
    </main>

    <div id="notification" class="notification"></div>
    
    <script>
        function showNotification(message, isError = false) {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.classList.toggle('error', isError);
            notification.style.display = 'block';
            setTimeout(() => {
                notification.style.display = 'none';
            }, 3000);
        }

        document.getElementById('borrowButton')?.addEventListener('click', function() {
            const bookId = this.dataset.bookId;
            fetch('borrow_book.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `book_id=${bookId}`
            })
            .then(response => response.json())
            .then(data => {
                showNotification(data.message, data.status !== 'success');
                if (data.status === 'success') {
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            })
            .catch(error => {
                showNotification('Fehler beim Verarbeiten der Anfrage', true);
            });
        });

        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('message')) {
            showNotification(urlParams.get('message'));
        }
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buch bearbeiten - Bibliotheksverwaltung</title>
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
<body class="bg-gray-200 text-gray-900 flex items-center justify-center min-h-screen" style="font-family: 'JetBrains Mono', monospace;">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-2xl relative">
        <div class="absolute top-1 right-3">
            <a href="/pages/admin-dashboard.php" class="text-gray-500 hover:text-gray-700 text-3xl">&times;</a>
        </div>
        <h1 class="text-2xl font-bold mb-6 text-center">Buch bearbeiten</h1>
        
        <!-- Search Form -->
        <form action="edit-book.php" method="get" class="mb-6">
            <div class="mb-4">
                <label for="search_title" class="block text-sm font-medium text-gray-700">Nach Titel suchen</label>
                <input type="text" id="search_title" name="search_title" 
                       value="<?= htmlspecialchars($_GET['search_title'] ?? '') ?>"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-indigo-500 text-white px-4 py-2 rounded-md hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Suchen</button>
            </div>
        </form>

        <?php
        if (isset($_GET['search_title']) || isset($_GET['id'])) {
            session_start();
            $connections = include 'database.php';
            $mysqli = $connections['books'];

            if (isset($_GET['id'])) {
                // Edit Form
                $stmt = $mysqli->prepare("SELECT * FROM buecher WHERE id = ?");
                $stmt->bind_param("i", $_GET['id']);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    $book = $result->fetch_assoc();
                    ?>
                    <form action="process-edit-book.php" method="post">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($book['id'], ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="search_title" value="<?= htmlspecialchars($_GET['search_title'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Left Column -->
                            <div class="space-y-4">
                                <div class="mb-4">
                                    <label for="Title" class="block text-sm font-medium text-gray-700">Titel</label>
                                    <input type="text" id="Title" name="Title" value="<?= htmlspecialchars($book['Title'], ENT_QUOTES, 'UTF-8') ?>" 
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>

                                <div class="mb-4">
                                    <label for="autor" class="block text-sm font-medium text-gray-700">Autor</label>
                                    <input type="text" id="autor" name="autor" value="<?= htmlspecialchars($book['autor'], ENT_QUOTES, 'UTF-8') ?>" 
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>

                                <div class="mb-4">
                                    <label for="verfasser" class="block text-sm font-medium text-gray-700">Verfasser</label>
                                    <input type="text" id="verfasser" name="verfasser" value="<?= htmlspecialchars($book['verfasser'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>

                                <div class="mb-4">
                                    <label for="kategorie" class="block text-sm font-medium text-gray-700">Kategorie</label>
                                    <select id="kategorie" name="kategorie" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <?php
                                        $categories = [
                                            1 => "Alte Drucke, Bibeln, Klassische Autoren...",
                                            2 => "Geographie und Reisen",
                                            3 => "Geschichtswissenschaften",
                                            4 => "Naturwissenschaften",
                                            5 => "Kinderbücher",
                                            6 => "Moderne Literatur und Kunst",
                                            7 => "Moderne Kunst und Künstlergraphik",
                                            8 => "Kunstwissenschaften",
                                            9 => "Architektur",
                                            10 => "Technik",
                                            11 => "Naturwissenschaften - Medizin",
                                            12 => "Ozeanien",
                                            13 => "Afrika",
                                            14 => "Alte Bücher"
                                        ];
                                        
                                        foreach ($categories as $value => $label) {
                                            $selected = (isset($book['kategorie']) && $value == $book['kategorie']) ? 'selected' : '';
                                            echo "<option value='" . htmlspecialchars($value) . "' $selected>" . htmlspecialchars($label) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="mb-4">
                                    <label for="nummer" class="block text-sm font-medium text-gray-700">Buchnummer</label>
                                    <input type="text" id="nummer" name="nummer" value="<?= htmlspecialchars($book['nummer'], ENT_QUOTES, 'UTF-8') ?>" 
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>

                                <div class="mb-4">
                                    <label for="katalog" class="block text-sm font-medium text-gray-700">Katalognummer</label>
                                    <input type="text" id="katalog" name="katalog" value="<?= htmlspecialchars($book['katalog'], ENT_QUOTES, 'UTF-8') ?>" 
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>

                                <div class="mb-4">
                                    <label for="zustand" class="block text-sm font-medium text-gray-700">Zustand</label>
                                    <select id="zustand" name="zustand" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <?php
                                    $conditions = [
                                        'Neu' => 'Neu',
                                            'Gut' => 'Gut',
                                            'Akzeptabel' => 'Akzeptabel',
                                            'Beschädigt' => 'Beschädigt'
                                        ];
                                        
                                        foreach ($conditions as $value => $label) {
                                            $selected = (isset($book['zustand']) && $value == $book['zustand']) ? 'selected' : '';
                                            echo '<option value="' . htmlspecialchars($value) . '" ' . $selected . '>';
                                            echo htmlspecialchars($label);
                                            echo '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="foto" class="block text-sm font-medium text-gray-700">Bild-URL</label>
                                    <input type="url" id="foto" name="foto" value="<?= htmlspecialchars($book['foto'], ENT_QUOTES, 'UTF-8') ?>" 
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 space-y-4">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Verkaufsstatus</label>
                                <div class="mt-1 space-y-2">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="verkauft" value="1" <?= $book['verkauft'] ? 'checked' : '' ?> class="form-radio h-4 w-4 text-indigo-600">
                                        <span class="ml-2">Verkauft</span>
                                    </label>
                                    <label class="inline-flex items-center ml-6">
                                        <input type="radio" name="verkauft" value="0" <?= !$book['verkauft'] ? 'checked' : '' ?> class="form-radio h-4 w-4 text-indigo-600">
                                        <span class="ml-2">Nicht verkauft</span>
                                    </label>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="kaufer" class="block text-sm font-medium text-gray-700">Käufer</label>
                                <input type="text" id="kaufer" name="kaufer" value="<?= htmlspecialchars($book['kaufer'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>

                            <div class="mb-4">
                                <label for="Beschreibung" class="block text-sm font-medium text-gray-700">Beschreibung</label>
                                <textarea id="Beschreibung" name="Beschreibung" rows="3" 
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"><?= htmlspecialchars($book['Beschreibung'], ENT_QUOTES, 'UTF-8') ?></textarea>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-6">
                            <button type="submit" class="bg-indigo-500 text-white px-4 py-2 rounded-md hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Änderungen speichern
                            </button>

                            <button
                                type="submit"
                                formaction="delete-book.php"
                                formmethod="post"
                                onclick="return confirm('Sind Sie sicher?')"
                                class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                            >
                                Buch löschen
                            </button>
                        </div>
                    </form>
                    <?php
                }
                $stmt->close();
            } else {
                $stmt = $mysqli->prepare("SELECT id, Title, autor, katalog, foto FROM buecher WHERE Title LIKE ? ORDER BY Title LIMIT 20");
                $search_term = "%" . $_GET['search_title'] . "%";
                $stmt->bind_param("s", $search_term);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    echo '<h2 class="text-xl font-bold mb-4">Suchergebnisse</h2>';
                    echo '<div class="space-y-4">';
                    
                    while ($book = $result->fetch_assoc()) {
                        echo '<div class="border rounded-md p-4">';
                        echo '<div class="flex items-start justify-between">';
                        echo '<div class="flex-1">';
                        echo '<h3 class="font-bold">' . htmlspecialchars($book['Title']) . '</h3>';
                        echo '<p class="text-sm">Autor: ' . htmlspecialchars($book['autor']) . '</p>';
                        echo '<p class="text-sm">Katalog: ' . htmlspecialchars($book['katalog']) . '</p>';
                        echo '</div>';
                        echo '<a href="edit-book.php?search_title=' . urlencode($_GET['search_title']) . '&id=' . $book['id'] . '" class="bg-indigo-500 text-white px-3 py-1 rounded-md hover:bg-indigo-600 text-sm">Bearbeiten</a>';
                        echo '</div>';
                        echo '</div>';
                    }
                    
                    echo '</div>';
                } else {
                    echo "<p class='text-red-500'>Keine Bücher gefunden.</p>";
                }
                $stmt->close();
            }
        }

        if (isset($_GET['message'])) {
            $message = htmlspecialchars($_GET['message']);
            $isError = isset($_GET['error']) && $_GET['error'] == 'true';
            echo "<script>showNotification('$message', $isError);</script>";
        }
        ?>
    </div>

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
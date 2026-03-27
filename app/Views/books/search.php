<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buchsuche - Bibliotheksverwaltung</title>
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

        .form-input-modern {
            border: 1px solid #d1d5db;
            transition: border-color 160ms ease, box-shadow 160ms ease;
        }

        .form-input-modern:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
            outline: none;
        }

        .book-card {
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            background: #ffffff;
        }
    </style>
</head>
<body class="modern-shell text-gray-900 min-h-screen" style="font-family: 'JetBrains Mono', monospace;">
    <main class="container mx-auto w-full max-w-6xl px-4 py-6 sm:py-8">
    <div class="soft-card bg-gray-50/95 p-6 sm:p-8 rounded-xl w-full relative">
        <div class="absolute top-3 right-4">
            <a href="/" class="inline-flex items-center justify-center h-9 w-9 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-md text-2xl leading-none">&times;</a>
        </div>
        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight mb-6 text-center">Buch suchen</h1>

        <form action="/books/search" method="get" class="rounded-lg border border-gray-200 bg-white p-5 sm:p-6">
            <div class="mb-4">
                <label for="filter" class="block text-sm font-medium text-gray-700">Filtern nach</label>
                <select id="filter" name="filter" class="form-input-modern mt-1 block w-full px-3 py-2 rounded-md sm:text-sm bg-white">
                    <option value="">Alle Felder</option>
                    <option value="katalog" <?= ($filter ?? '') === 'katalog' ? 'selected' : '' ?>>Katalog</option>
                    <option value="nummer" <?= ($filter ?? '') === 'nummer' ? 'selected' : '' ?>>Nummer</option>
                    <option value="Title" <?= ($filter ?? '') === 'Title' ? 'selected' : '' ?>>Titel</option>
                    <option value="autor" <?= ($filter ?? '') === 'autor' ? 'selected' : '' ?>>Autor</option>
                    <option value="kategorie" <?= ($filter ?? '') === 'kategorie' ? 'selected' : '' ?>>Kategorie</option>
                    <option value="zustand" <?= ($filter ?? '') === 'zustand' ? 'selected' : '' ?>>Zustand</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="query" class="block text-sm font-medium text-gray-700">Suchbegriff</label>
                <input type="text" id="query" name="query"
                    value="<?= htmlspecialchars((string) ($query ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                    class="form-input-modern mt-1 block w-full px-3 py-2 rounded-md sm:text-sm"
                    placeholder="Suchbegriff eingeben...">
            </div>

            <div class="mb-4">
                <label for="sort" class="block text-sm font-medium text-gray-700">Sortieren nach</label>
                <select id="sort" name="sort" class="form-input-modern mt-1 block w-full px-3 py-2 rounded-md sm:text-sm bg-white">
                    <?php foreach (($sortLabelMap ?? []) as $value => $label): ?>
                        <option value="<?= htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8') ?>" <?= ($sort ?? '') === $value ? 'selected' : '' ?>>
                            <?= htmlspecialchars((string) $label, ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="flex items-center justify-end">
                <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded-md hover:bg-indigo-700 focus:outline-none transition-colors">Suchen</button>
            </div>
        </form>

        <div class="mt-6 rounded-lg border border-gray-200 bg-white p-5 sm:p-6">
            <h2 class="text-xl font-bold mb-1"><?= (($query ?? '') !== '' || ($filter ?? '') !== '') ? 'Suchergebnisse' : 'Verfuegbare Buecher' ?></h2>
            <p class="text-sm text-gray-600 mb-4">Zeige <?= (int) ($resultStart ?? 0) ?>-<?= (int) ($resultEnd ?? 0) ?> von <?= (int) ($totalItems ?? 0) ?> Ergebnissen</p>

            <?php if (($dbError ?? '') !== ''): ?>
                <p class="text-red-600"><?= htmlspecialchars((string) $dbError, ENT_QUOTES, 'UTF-8') ?></p>
            <?php elseif (!empty($books)): ?>
                <?php foreach (array_chunk($books, 3) as $chunk): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                        <?php foreach ($chunk as $book): ?>
                            <?php $titleValue = trim((string) ($book['Title'] ?? '')); ?>
                            <div class="book-card p-4 flex">
                                <div class="w-full h-full flex flex-col">
                                    <div class="w-full h-48 bg-gray-100 border border-gray-300 rounded-md mb-3 flex items-center justify-center">
                                        <span class="text-gray-500">Cover</span>
                                    </div>
                                    <h3 class="text-lg font-bold mb-1">
                                        <?php if ($titleValue !== ''): ?>
                                            <?= htmlspecialchars($titleValue, ENT_QUOTES, 'UTF-8') ?>
                                        <?php else: ?>
                                            <span class="text-gray-500 italic">Kein Titel</span>
                                        <?php endif; ?>
                                    </h3>
                                    <p><span class="font-bold">Autor:</span> <?= htmlspecialchars((string) ($book['autor'] ?? 'Unbekannt'), ENT_QUOTES, 'UTF-8') ?></p>
                                    <p><span class="font-bold">Kategorie:</span> <?= htmlspecialchars((string) (($kategorienMap[$book['kategorie']] ?? 'Unbekannt')), ENT_QUOTES, 'UTF-8') ?></p>
                                    <p><span class="font-bold">Katalog:</span> <?= htmlspecialchars((string) ($book['katalog'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></p>
                                    <p><span class="font-bold">Zustand:</span> <?= htmlspecialchars((string) ($book['zustand'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></p>
                                    <p class="mt-auto pt-3"><a href="/php/book-details.php?id=<?= urlencode((string) ($book['id'] ?? '')) ?>" class="text-sm text-indigo-600 hover:text-indigo-800 hover:underline">Details öffnen</a></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-gray-500">Keine Buecher gefunden, die zu deinen Kriterien passen.</p>
            <?php endif; ?>
        </div>

        <?php if (($totalPages ?? 1) > 1): ?>
            <div class="mt-6 flex justify-center items-center space-x-2 flex-wrap">
                <?php
                $baseQuery = $_GET;
                if (($currentPage ?? 1) > 1) {
                    $prevQuery = http_build_query(array_merge($baseQuery, ['page' => $currentPage - 1]));
                    echo '<a href="/books/search?' . $prevQuery . '" class="px-3 py-1 rounded-md border bg-white text-indigo-500 hover:bg-indigo-100">Zurueck</a>';
                }

                echo '<a href="/books/search?' . http_build_query(array_merge($baseQuery, ['page' => 1])) . '" class="px-3 py-1 rounded-md border ' . (1 === $currentPage ? 'bg-indigo-500 text-white' : 'bg-white text-indigo-500 hover:bg-indigo-100') . '">1</a>';

                if (($currentPage ?? 1) > 4) {
                    echo '<span class="px-3 py-1">...</span>';
                }

                $start = max(2, ($currentPage ?? 1) - 1);
                $end = min(($totalPages ?? 1) - 1, ($currentPage ?? 1) + 1);

                for ($i = $start; $i <= $end; $i++) {
                    echo '<a href="/books/search?' . http_build_query(array_merge($baseQuery, ['page' => $i])) . '" class="px-3 py-1 rounded-md border ' . ($i === $currentPage ? 'bg-indigo-500 text-white' : 'bg-white text-indigo-500 hover:bg-indigo-100') . '">' . $i . '</a>';
                }

                if ($end < ($totalPages ?? 1) - 1) {
                    echo '<span class="px-3 py-1">...</span>';
                }

                echo '<a href="/books/search?' . http_build_query(array_merge($baseQuery, ['page' => $totalPages])) . '" class="px-3 py-1 rounded-md border ' . ($totalPages === $currentPage ? 'bg-indigo-500 text-white' : 'bg-white text-indigo-500 hover:bg-indigo-100') . '">' . $totalPages . '</a>';

                if (($currentPage ?? 1) < ($totalPages ?? 1)) {
                    $nextQuery = http_build_query(array_merge($baseQuery, ['page' => $currentPage + 1]));
                    echo '<a href="/books/search?' . $nextQuery . '" class="px-3 py-1 rounded-md border bg-white text-indigo-500 hover:bg-indigo-100">Weiter</a>';
                }
                ?>
            </div>
        <?php endif; ?>
    </div>
</main>
</body>
</html>

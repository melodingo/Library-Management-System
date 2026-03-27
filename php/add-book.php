<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buch hinzufügen - Bibliotheksverwaltung</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-200 text-gray-900 flex items-center justify-center min-h-screen" style="font-family: 'JetBrains Mono', monospace;">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-xl relative">
        <div class="absolute top-1 right-3">
            <a href="/pages/admin-dashboard.php" class="text-gray-500 hover:text-gray-700 text-3xl">&times;</a>
        </div>
        <h1 class="text-2xl font-bold mb-6 text-center">Buch hinzufügen</h1>
        <form action="process-add-book.php" method="post">
            
            <!-- Grundinformationen -->
            <div class="mb-4">
                <label for="Title" class="block text-sm font-medium text-gray-700">Titel*</label>
                <input type="text" id="Title" name="Title" required 
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="autor" class="block text-sm font-medium text-gray-700">Autor*</label>
                    <input type="text" id="autor" name="autor" required 
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div class="mb-4">
                    <label for="verfasser" class="block text-sm font-medium text-gray-700">Verfasser</label>
                    <input type="text" id="verfasser" name="verfasser" 
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
            </div>

            <!-- Katalogdaten -->
            <div class="grid grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="nummer" class="block text-sm font-medium text-gray-700">Buchnummer*</label>
                    <input type="text" id="nummer" name="nummer" required 
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div class="mb-4">
                    <label for="katalog" class="block text-sm font-medium text-gray-700">Katalognummer*</label>
                    <input type="text" id="katalog" name="katalog" required 
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
            </div>

            <!-- Kategorie & Zustand -->
            <div class="grid grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="kategorie" class="block text-sm font-medium text-gray-700">Kategorie*</label>
                    <select id="kategorie" name="kategorie" required 
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="1">Alte Drucke, Bibeln, Klassische Autoren...</option>
                        <option value="2">Geographie und Reisen</option>
                        <option value="3">Geschichtswissenschaften</option>
                        <option value="4">Naturwissenschaften</option>
                        <option value="5">Kinderbücher</option>
                        <option value="6">Moderne Literatur und Kunst</option>
                        <option value="7">Moderne Kunst und Künstlergraphik</option>
                        <option value="8">Kunstwissenschaften</option>
                        <option value="9">Architektur</option>
                        <option value="10">Technik</option>
                        <option value="11">Naturwissenschaften - Medizin</option>
                        <option value="12">Ozeanien</option>
                        <option value="13">Afrika</option>
                        <option value="14">Alte Bücher</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="zustand" class="block text-sm font-medium text-gray-700">Zustand*</label>
                    <select id="zustand" name="zustand" required 
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="Neu">Neu</option>
                        <option value="Gut">Gut</option>
                        <option value="Akzeptabel">Akzeptabel</option>
                        <option value="Beschädigt">Beschädigt</option>
                    </select>
                </div>
            </div>

            <!-- Beschreibung & Bild -->
            <div class="mb-4">
                <label for="Beschreibung" class="block text-sm font-medium text-gray-700">Beschreibung</label>
                <textarea id="Beschreibung" name="Beschreibung" rows="3" 
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
            </div>

            <div class="mb-4">
                <label for="foto" class="block text-sm font-medium text-gray-700">Bild-URL</label>
                <input type="url" id="foto" name="foto" 
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <!-- Verkaufsinformation -->
            <div class="grid grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Verkaufsstatus*</label>
                    <div class="mt-1 space-y-2">
                        <label class="inline-flex items-center">
                            <input type="radio" name="verkauft" value="1" class="form-radio h-4 w-4 text-indigo-600">
                            <span class="ml-2">Verkauft</span>
                        </label>
                        <label class="inline-flex items-center ml-6">
                            <input type="radio" name="verkauft" value="0" checked class="form-radio h-4 w-4 text-indigo-600">
                            <span class="ml-2">Nicht verkauft</span>
                        </label>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="kaufer" class="block text-sm font-medium text-gray-700">Käufer (falls verkauft)</label>
                    <input type="text" id="kaufer" name="kaufer" 
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" 
                    class="w-full bg-indigo-500 text-white px-4 py-2 rounded-md hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                    Buch hinzufügen
                </button>
            </div>
        </form>
    </div>
</body>
</html>
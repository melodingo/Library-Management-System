<?php

$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $databases = require __DIR__ . "/database.php";
    $mysqli1 = $databases['login_db']; // Access the specific database connection
    
    if ($mysqli1 instanceof mysqli) {
        $email = $mysqli1->real_escape_string($_POST["email"]);
        $sql = sprintf("SELECT * FROM user WHERE email = '%s'", $email);
        $result = $mysqli1->query($sql);
        
        $user = $result->fetch_assoc();
        
        if ($user) {
            
            if (password_verify($_POST["password"], $user["password_hash"])) {
                
                session_start();
                
                session_regenerate_id();
                
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["is_admin"] = $user["is_admin"];
                
                header("Location: ../index.php");
                exit;
            }
        }
        
        $is_invalid = true;
    } else {
        die('Database connection failed.');
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Library Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-200 text-gray-900 flex items-center justify-center min-h-screen" style="font-family: 'JetBrains Mono', monospace;">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-sm relative">
        <div class="absolute top-1 right-3">
            <a href="/index.php" class="text-gray-500 hover:text-gray-700 text-3xl">&times;</a>
        </div>
        <h1 class="text-2xl font-bold mb-6 text-center">Login</h1>
        <?php if ($is_invalid): ?>
            <em class="text-red-500 text-sm">Invalid login</em>
        <?php endif; ?>
        <form method="post">
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($_POST["email"] ?? "") ?>" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-indigo-500 text-white px-4 py-2 rounded-md hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Login</button>
                <a href="/pages/signup.html" class="text-sm text-indigo-500 hover:underline">Sign up</a>
            </div>
        </form>
    </div>
</body>
</html> 
<?php

declare(strict_types=1);

session_start();

$router = require __DIR__ . '/app/bootstrap.php';
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $requestPath);
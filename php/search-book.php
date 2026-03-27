<?php

declare(strict_types=1);

$queryString = isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] !== ''
    ? '?' . $_SERVER['QUERY_STRING']
    : '';

header('Location: /books/search' . $queryString);
exit;

<?php

declare(strict_types=1);

namespace App\Core;

use mysqli;
use RuntimeException;

class Database
{
    private static ?mysqli $booksConnection = null;

    public static function books(): mysqli
    {
        if (self::$booksConnection instanceof mysqli) {
            return self::$booksConnection;
        }

        $connections = require __DIR__ . '/../../php/database.php';

        if (!isset($connections['books']) || !($connections['books'] instanceof mysqli)) {
            throw new RuntimeException('Books database connection is not available.');
        }

        self::$booksConnection = $connections['books'];

        return self::$booksConnection;
    }
}

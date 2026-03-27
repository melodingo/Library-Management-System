<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class User
{
    public static function findByEmail(string $email): ?array
    {
        $mysqli = Database::books();
        $stmt = $mysqli->prepare('SELECT ID, email, passwort, admin FROM benutzer WHERE email = ?');

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result ? $result->fetch_assoc() : null;
        $stmt->close();

        return $user ?: null;
    }

    public static function findProfileById(int $id): ?array
    {
        $mysqli = Database::books();
        $stmt = $mysqli->prepare('SELECT ID, benutzername, vorname, name, email, admin FROM benutzer WHERE ID = ?');

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result ? $result->fetch_assoc() : null;
        $stmt->close();

        return $user ?: null;
    }

    public static function findHeaderUserById(int $id): ?array
    {
        $mysqli = Database::books();
        $stmt = $mysqli->prepare('SELECT ID, benutzername, vorname, admin FROM benutzer WHERE ID = ?');

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result ? $result->fetch_assoc() : null;
        $stmt->close();

        return $user ?: null;
    }
}

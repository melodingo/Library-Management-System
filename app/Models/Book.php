<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Book
{
    private const KATEGORIEN_MAP = [
        1 => 'Alte Drucke, Bibeln, Klassische Autoren...',
        2 => 'Geographie und Reisen',
        3 => 'Geschichts-wissenschaften',
        4 => 'Naturwissenschaften',
        5 => 'Kinderbuecher',
        6 => 'Moderne Literatur und Kunst',
        7 => 'Moderne Kunst und Kuenstlergraphik',
        8 => 'Kunst-wissenschaften',
        9 => 'Architektur',
        10 => 'Technik',
        11 => 'Naturwissenschaften - Medizin',
        12 => 'Ozeanien',
        13 => 'Afrika',
        14 => 'Alte Buecher',
    ];

    private const ALLOWED_FILTERS = [
        '' => '',
        'katalog' => 'katalog',
        'nummer' => 'nummer',
        'Title' => 'Title',
        'autor' => 'autor',
        'kategorie' => 'kategorie',
        'zustand' => 'zustand',
    ];

    private const SORT_SQL_MAP = [
        'title_asc' => 'Title ASC',
        'title_desc' => 'Title DESC',
        'nummer_asc' => 'nummer ASC',
        'nummer_desc' => 'nummer DESC',
        'katalog_asc' => 'CAST(katalog AS UNSIGNED) ASC',
        'katalog_desc' => 'CAST(katalog AS UNSIGNED) DESC',
    ];

    private const SORT_LABEL_MAP = [
        'title_asc' => 'Titel (A-Z)',
        'title_desc' => 'Titel (Z-A)',
        'nummer_asc' => 'Nummer (Niedrig-Hoch)',
        'nummer_desc' => 'Nummer (Hoch-Niedrig)',
        'katalog_asc' => 'Katalog (Niedrig-Hoch)',
        'katalog_desc' => 'Katalog (Hoch-Niedrig)',
    ];

    public static function search(array $queryParams): array
    {
        $mysqli = Database::books();

        $query = isset($queryParams['query']) ? trim((string) $queryParams['query']) : '';
        $filter = isset($queryParams['filter']) && isset(self::ALLOWED_FILTERS[$queryParams['filter']])
            ? (string) $queryParams['filter']
            : '';
        $sort = isset($queryParams['sort']) && isset(self::SORT_SQL_MAP[$queryParams['sort']])
            ? (string) $queryParams['sort']
            : 'title_asc';
        $currentPage = isset($queryParams['page']) && is_numeric($queryParams['page'])
            ? (int) $queryParams['page']
            : 1;

        $itemsPerPage = 12;
        $conditions = [];
        $params = [];
        $types = '';
        $dbError = '';

        if ($query !== '') {
            $likeTerm = '%' . $query . '%';

            if ($filter === '') {
                $allFieldConditions = [];
                $textColumns = ['katalog', 'nummer', 'Title', 'autor', 'zustand'];

                foreach ($textColumns as $column) {
                    $allFieldConditions[] = "$column LIKE ?";
                    $params[] = $likeTerm;
                    $types .= 's';
                }

                $matchedCategoryIds = self::findCategoryMatches($query);
                if ($matchedCategoryIds !== []) {
                    $placeholder = implode(',', array_fill(0, count($matchedCategoryIds), '?'));
                    $allFieldConditions[] = "kategorie IN ($placeholder)";
                    foreach ($matchedCategoryIds as $categoryId) {
                        $params[] = $categoryId;
                        $types .= 'i';
                    }
                }

                $conditions[] = '(' . implode(' OR ', $allFieldConditions) . ')';
            } elseif ($filter === 'kategorie') {
                $matchedCategoryIds = self::findCategoryMatches($query);
                if ($matchedCategoryIds !== []) {
                    $placeholder = implode(',', array_fill(0, count($matchedCategoryIds), '?'));
                    $conditions[] = "kategorie IN ($placeholder)";
                    foreach ($matchedCategoryIds as $categoryId) {
                        $params[] = $categoryId;
                        $types .= 'i';
                    }
                } else {
                    $conditions[] = '0=1';
                }
            } else {
                $conditions[] = "$filter LIKE ?";
                $params[] = $likeTerm;
                $types .= 's';
            }
        }

        $whereClause = $conditions !== [] ? ' WHERE ' . implode(' AND ', $conditions) : '';

        $totalItems = self::countItems($mysqli, $whereClause, $types, $params, $dbError);
        $totalPages = max(1, (int) ceil($totalItems / $itemsPerPage));
        $currentPage = max(1, min($currentPage, $totalPages));
        $offset = ($currentPage - 1) * $itemsPerPage;

        $books = self::fetchBooks($mysqli, $whereClause, $types, $params, $sort, $itemsPerPage, $offset, $dbError);

        $resultStart = $totalItems > 0 ? $offset + 1 : 0;
        $resultEnd = $totalItems > 0 ? min($offset + $itemsPerPage, $totalItems) : 0;

        return [
            'query' => $query,
            'filter' => $filter,
            'sort' => $sort,
            'sortLabelMap' => self::SORT_LABEL_MAP,
            'books' => $books,
            'totalItems' => $totalItems,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
            'resultStart' => $resultStart,
            'resultEnd' => $resultEnd,
            'dbError' => $dbError,
            'kategorienMap' => self::KATEGORIEN_MAP,
        ];
    }

    private static function findCategoryMatches(string $query): array
    {
        $matchedCategoryIds = [];

        foreach (self::KATEGORIEN_MAP as $id => $text) {
            if (stripos($text, $query) !== false) {
                $matchedCategoryIds[] = (int) $id;
            }
        }

        return $matchedCategoryIds;
    }

    private static function countItems($mysqli, string $whereClause, string $types, array $params, string &$dbError): int
    {
        $countSql = 'SELECT COUNT(*) as total FROM buecher' . $whereClause;
        $countStmt = $mysqli->prepare($countSql);

        if (!$countStmt) {
            if ($dbError === '') {
                $dbError = 'Die Anzahl-Abfrage konnte nicht vorbereitet werden.';
            }
            return 0;
        }

        self::bindDynamicParams($countStmt, $types, $params);
        $countStmt->execute();
        $countResult = $countStmt->get_result();
        $countRow = $countResult ? $countResult->fetch_assoc() : null;
        $countStmt->close();

        return isset($countRow['total']) ? (int) $countRow['total'] : 0;
    }

    private static function fetchBooks($mysqli, string $whereClause, string $types, array $params, string $sort, int $itemsPerPage, int $offset, string &$dbError): array
    {
        $orderBy = self::SORT_SQL_MAP[$sort] ?? self::SORT_SQL_MAP['title_asc'];
        $bookSql = 'SELECT id, nummer, Title, autor, kategorie, katalog, zustand, foto FROM buecher'
            . $whereClause
            . ' ORDER BY ' . $orderBy
            . ' LIMIT ? OFFSET ?';

        $bookStmt = $mysqli->prepare($bookSql);
        if (!$bookStmt) {
            if ($dbError === '') {
                $dbError = 'Die Ergebnis-Abfrage konnte nicht vorbereitet werden.';
            }
            return [];
        }

        $dataParams = $params;
        $dataTypes = $types . 'ii';
        $dataParams[] = $itemsPerPage;
        $dataParams[] = $offset;

        self::bindDynamicParams($bookStmt, $dataTypes, $dataParams);
        $bookStmt->execute();
        $bookResult = $bookStmt->get_result();
        $books = $bookResult ? $bookResult->fetch_all(MYSQLI_ASSOC) : [];
        $bookStmt->close();

        return $books;
    }

    private static function bindDynamicParams($stmt, string $types, array &$params): void
    {
        if ($types === '') {
            return;
        }

        $bindArgs = [$types];
        foreach ($params as $idx => $param) {
            $bindArgs[] = &$params[$idx];
        }

        call_user_func_array([$stmt, 'bind_param'], $bindArgs);
    }
}

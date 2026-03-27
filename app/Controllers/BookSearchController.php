<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Book;

class BookSearchController extends Controller
{
    public function index(): void
    {
        $searchData = Book::search($_GET);
        $this->render('books/search', $searchData);
    }
}

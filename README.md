![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?logo=php&logoColor=white) ![MySQL](https://img.shields.io/badge/MySQL-MariaDB-4479A1?logo=mysql&logoColor=white) ![TailwindCSS](https://img.shields.io/badge/TailwindCSS-3-06B6D4?logo=tailwindcss&logoColor=white) ![License](https://img.shields.io/badge/License-MIT-green)

# Library Management System

A web-based library management application built with PHP, MySQL, and Tailwind CSS. It allows librarians and administrators to manage books, users, and borrowing records through a clean, responsive interface.

**TL;DR:**

- 📚 **Librarians & end users:**  
  Browse, search and manage the book catalogue with an intuitive web interface. Log in with your credentials and start managing the library right away.

- 🛠️ **Administrators:**  
  Manage the full user database, assign admin roles, add and edit book records, and maintain catalogue categories — all from the admin dashboard.

- 💻 **Developers:**  
  The project follows an MVC architecture with a custom PHP router. It is structured for easy extension: add new controllers, models, and views without touching the core framework.

---

## Quick links

- [The Project](#the-project)
  - [Introduction](#introduction)
  - [User Features](#user-features)
  - [Admin Features](#admin-features)
  - [Developer Features](#developer-features)
- [Installation](#installation)
  - [Requirements](#requirements)
  - [Setup](#setup)
- [Getting Started](#getting-started)
  - [Default Credentials](#default-credentials)
  - [Adding Books](#adding-books)
  - [Managing Users](#managing-users)
- [Architecture](#architecture)
  - [MVC Structure](#mvc-structure)
  - [Routing](#routing)
  - [Database Schema](#database-schema)
- [Book Categories](#book-categories)
- [Future Expansion](#future-expansion)
- [Contributing](#contributing)

---

## The Project

### Introduction

The **Library Management System** is a fully-featured web application designed to help small to medium-sized libraries digitise their book catalogue and manage their members. The application is built with plain PHP (no external framework), a custom MVC core, MariaDB/MySQL as the database backend, and Tailwind CSS for the front-end styling.

_In this documentation, the term "admin" refers to a user with the `admin` flag set to `1` in the database._

### User Features

- **Book search:** Full-text search across title, author, catalogue number, and category with real-time results.
- **Book details:** View detailed information about each book, including condition, description, category, and availability.
- **User profile:** Each registered user has a personal profile page and the ability to change their password.
- **Responsive UI:** The interface is fully responsive and works on desktop and mobile browsers.

### Admin Features

- **Book management:** Add new books, edit existing entries (title, author, condition, category, description, cover image), and delete books from the catalogue.
- **User management:** Create, edit, and delete library members. Assign or revoke administrator privileges.
- **Admin dashboard:** A dedicated dashboard gives administrators a central overview of all management functions.

### Developer Features

- **Custom MVC core:** A lightweight router, base controller, and database abstraction layer live in `app/Core/` and are fully independent of any third-party framework.
- **Typed PHP:** The codebase uses `declare(strict_types=1)` throughout and targets PHP 8.2.
- **Prepared statements:** All database queries use PDO with prepared statements to prevent SQL injection.
- **Tailwind CSS build pipeline:** Styles are compiled with the Tailwind CLI. The `tailwind.config.js` and `package.json` are included for easy customisation.

---

## Installation

### Requirements

| Dependency | Version |
|---|---|
| PHP | ≥ 8.2 |
| MariaDB / MySQL | ≥ 10.4 |
| Node.js / npm | ≥ 18 (for CSS build only) |
| A web server | Apache / Nginx / PHP built-in server |

### Setup

1. **Clone the repository:**

   ```bash
   git clone https://github.com/melodingo/Library-Management-System.git
   cd Library-Management-System
   ```

2. **Import the database:**

   ```bash
   mysql -u root -p < books.sql
   ```

   This creates a `books` database with the `buecher` (books) and `benutzer` (users) tables and populates them with sample data.

3. **Configure the database connection:**

   Open `php/database.php` and `app/Core/Database.php` and update the host, username, and password to match your local environment:

   ```php
   $host     = "localhost";
   $username = "root";
   $password = "your_password";
   $dbname   = "books";
   ```

4. **Install front-end dependencies (optional, for CSS rebuilds):**

   ```bash
   npm install
   npx tailwindcss -i ./css/input.css -o ./css/output.css --watch
   ```

5. **Start the server:**

   With the PHP built-in server:

   ```bash
   php -S localhost:8000
   ```

   Or configure your Apache/Nginx vhost to point at the project root.

---

## Getting Started

### Default Credentials

After importing `books.sql` the following accounts are available:

| Username | Role | Password |
|---|---|---|
| `admin` | Administrator | `admin` (change immediately) |
| `user1` … `user5` | Regular user | `user1` … `user5` |

> ⚠️ **Change all default passwords before deploying to a public server.**

### Adding Books

1. Log in as an administrator.
2. Navigate to the **Admin Dashboard**.
3. Click **Add Book** and fill in the form (title, author, category, condition, description).
4. Submit — the book appears in the catalogue immediately.

### Managing Users

1. From the **Admin Dashboard**, open **User Management**.
2. Click **New User** to create a library member.
3. Use the edit icon to update details or toggle admin privileges.
4. Use the delete icon to remove a user permanently.

---

## Architecture

### MVC Structure

```
Library-Management-System/
├── app/
│   ├── Core/
│   │   ├── Auth.php          # Session-based authentication helper
│   │   ├── Controller.php    # Base controller (view rendering)
│   │   ├── Database.php      # PDO database abstraction
│   │   └── Router.php        # HTTP router (GET / POST dispatch)
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── BookSearchController.php
│   │   ├── HomeController.php
│   │   └── ProfileController.php
│   ├── Models/
│   │   ├── Book.php          # Book queries and business logic
│   │   └── User.php          # User queries and business logic
│   └── Views/
│       ├── auth/             # Login page
│       ├── books/            # Search results
│       ├── home/             # Landing page
│       └── profile/          # User profile
├── pages/                    # Legacy admin PHP pages
├── php/                      # Form handlers and AJAX endpoints
├── css/                      # Tailwind source and compiled output
├── js/                       # Client-side scripts
├── index.php                 # Application entry point
├── books.sql                 # Database dump
└── tailwind.config.js
```

### Routing

The application uses a simple front-controller pattern. `index.php` instantiates the router (bootstrapped in `app/bootstrap.php`) and dispatches the request:

```php
$router->get('/books/search', [BookSearchController::class, 'index']);
$router->post('/books/search', [BookSearchController::class, 'search']);
```

New routes can be added to `app/bootstrap.php` without touching any other file.

### Database Schema

**`buecher`** (books)

| Column | Type | Description |
|---|---|---|
| `id` | INT | Primary key |
| `katalog` | INT | Catalogue number |
| `nummer` | INT | Item number within the catalogue |
| `Title` | VARCHAR | Book title |
| `kategorie` | INT | Category ID (1–14) |
| `autor` | VARCHAR | Author name |
| `Beschreibung` | VARCHAR | Full description |
| `zustand` | VARCHAR | Condition (`G` = good, `M` = medium, `S` = poor) |
| `verkauft` | TINYINT | Whether the book has been sold/borrowed |
| `kaufer` | INT | Buyer / borrower user ID |
| `foto` | VARCHAR | Cover image filename |

**`benutzer`** (users)

| Column | Type | Description |
|---|---|---|
| `ID` | INT | Primary key |
| `benutzername` | VARCHAR | Username |
| `name` / `vorname` | VARCHAR | Last and first name |
| `passwort` | VARCHAR | Bcrypt-hashed password |
| `email` | VARCHAR | Email address |
| `admin` | TINYINT | `1` = administrator, `NULL` = regular user |

---

## Book Categories

The system ships with 14 book categories:

| ID | Category |
|---|---|
| 1 | Old Prints, Bibles, Classical Authors |
| 2 | Geography and Travel |
| 3 | Historical Sciences |
| 4 | Natural Sciences |
| 5 | Children's Books |
| 6 | Modern Literature and Art |
| 7 | Modern Art and Artist Graphics |
| 8 | Art Sciences |
| 9 | Architecture |
| 10 | Technology |
| 11 | Natural Sciences – Medicine |
| 12 | Oceania |
| 13 | Africa |
| 14 | Old Books |

---

## Future Expansion

The following areas are planned or suggested for future development:

- [ ] **Borrowing / loan tracking** — record who borrowed which book and when it is due back, with overdue notifications.
- [ ] **Advanced search filters** — filter by condition, availability, date added, or multiple categories at once.
- [ ] **Pagination** — paginate large book result sets instead of loading everything at once.
- [ ] **Book cover uploads** — replace the static `book.jpg` placeholder with real per-book image uploads.
- [ ] **REST API** — expose book and user data as a JSON API to support mobile clients or external integrations.
- [ ] **Email notifications** — send automated emails on account creation, password change, or loan reminders.
- [ ] **Audit log** — record admin actions (book add/edit/delete, user changes) for accountability.
- [ ] **Unit & integration tests** — add a test suite (e.g., PHPUnit) covering the model and controller layer.
- [ ] **Docker setup** — provide a `docker-compose.yml` for a one-command local development environment.
- [ ] **Internationalisation (i18n)** — make the UI language configurable to support English and other languages alongside German.

---

## Contributing

Contributions are welcome. To get started:

1. Fork the repository and create a feature branch.
2. Follow the existing code style (strict types, PSR-4 namespacing, prepared statements).
3. Open a pull request describing your changes.

Please open an issue first if you plan a larger change so it can be discussed before implementation.

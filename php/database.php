<?php
$host = "localhost";
$username = "root";
$password = ""; 


$dbname2 = "books";
$mysqli2 = new mysqli($host, $username, $password, $dbname2);
if ($mysqli2->connect_errno) {
    die("Connection error to books: " . $mysqli2->connect_error);
}

return [
    'books' => $mysqli2
];
?>


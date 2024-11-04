<?php
$host = "localhost";
$username = "root";
$password = ""; 

// Connection to the first database
$dbname1 = "login_db";
$mysqli1 = new mysqli($host, $username, $password, $dbname1);
if ($mysqli1->connect_errno) {
    die("Connection error to login_db: " . $mysqli1->connect_error);
}

// Connection to the second database
$dbname2 = "library_management";
$mysqli2 = new mysqli($host, $username, $password, $dbname2);
if ($mysqli2->connect_errno) {
    die("Connection error to library_management: " . $mysqli2->connect_error);
}

// Return an array of connections
return [
    'login_db' => $mysqli1,
    'library_management' => $mysqli2
];
?>
<?php

$mysqli1 = require __DIR__ . "/php/database.php";

$sql = sprintf("SELECT * FROM user
                WHERE email = '%s'",
                $mysqli1->real_escape_string($_GET["email"]));
                
$result = $mysqli1->query($sql);

$is_available = $result->num_rows === 0;

header("Content-Type: application/json");

echo json_encode(["available" => $is_available]);
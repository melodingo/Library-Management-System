<?php

session_start();

$_SESSION['logout_success'] = true;

session_destroy();

header("Location: ../index.php?message=" . urlencode("Successfully logged out"));
exit;
?>
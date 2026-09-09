<?php

require_once "config.php";

$conn = mysqli_connect(
    $db_host,
    $db_user,
    $db_password,
    $db_name
);

if (!$conn) {
    die("Database connection failed.");
}

?>
<?php

include "db.php";

if (isset($_GET['id'])) {

    $id = $_GET['id'];

    $sql = "DELETE FROM students WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $id);

    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
}

header("Location: students.php");
exit;

?>
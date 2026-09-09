<?php
session_start();
include "db.php";

if (!isset($_SESSION["student_id"])) {
    header("Location: login.php");
    exit;
}

$result = mysqli_query($conn, "SELECT * FROM students");
$total_students = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Student Management System</h1>

<h2>Welcome, <?php echo htmlspecialchars($_SESSION["student_name"]); ?>!</h2>

<h3>Total Students: <?php echo $total_students; ?></h3>

<br>

<a href="add_student.php">Add Student</a>
<br><br>

<a href="students.php">View All Students</a>
<br><br>

<a href="logout.php">Logout</a>

</body>
</html>
<?php
session_start();
include "db.php";

if (!isset($_SESSION["student_id"])) {
    header("Location: login.php");
    exit;
}

$result = mysqli_query($conn, "SELECT * FROM students");
$total_students = mysqli_num_rows($result);

$course_result = mysqli_query(
    $conn,
    "SELECT course, COUNT(*) AS total
     FROM students
     GROUP BY course"
);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Student Management System</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<nav class="navbar">
    <div class="logo">Student Management System</div>

    <div class="nav-links">
        <a href="dashboard.php">Dashboard</a>
        <a href="students.php">Students</a>
        <a href="add_student.php">Add Student</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>

<div class="container">

    <div class="welcome">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION["student_name"]); ?> 👋</h1>
        <p>Manage your student records from one place.</p>
    </div>

    <div class="dashboard-card">
        <h2>Total Students</h2>
        <div class="number">
            <?php echo $total_students; ?>
        </div>

        <a class="btn" href="students.php">
            View Students
        </a>
    </div>

    <div class="dashboard-card">
        <h2>Add New Student</h2>
        <p>Create a new student record.</p>

        <a class="btn" href="add_student.php">
            + Add Student
        </a>
    </div>

    <div class="dashboard-card">
    <h2>Course Statistics</h2>

    <?php while ($row = mysqli_fetch_assoc($course_result)) { ?>

        <p>
            <strong><?php echo htmlspecialchars($row['course']); ?></strong>
            :
            <?php echo $row['total']; ?> students
        </p>

    <?php } ?>
</div>

</div>

</body>
</html>
<?php
session_start();
include "db.php";

if (!isset($_SESSION["student_id"])) {
    header("Location: login.php");
    exit;
}

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;

$sql = "SELECT id, name, email, phone, course
        FROM students
        WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$student = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$student) {
    die("Student not found.");
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Student Profile</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<nav class="navbar">

    <div class="logo">
        Student Management System
    </div>

    <div class="nav-links">
        <a href="dashboard.php">Dashboard</a>
        <a href="students.php">Students</a>
        <a href="add_student.php">Add Student</a>
        <a href="logout.php">Logout</a>
    </div>

</nav>

<div class="container">

    <div class="profile-card">

        <h1>Student Profile</h1>

        <div class="profile-info">

            <p>
                <strong>Student ID:</strong>
                <?php echo $student["id"]; ?>
            </p>

            <p>
                <strong>Name:</strong>
                <?php echo htmlspecialchars($student["name"]); ?>
            </p>

            <p>
                <strong>Email:</strong>
                <?php echo htmlspecialchars($student["email"]); ?>
            </p>

            <p>
                <strong>Phone:</strong>
                <?php echo htmlspecialchars($student["phone"]); ?>
            </p>

            <p>
                <strong>Course:</strong>
                <?php echo htmlspecialchars($student["course"]); ?>
            </p>

        </div>

        <a
            class="btn"
            href="edit_student.php?id=<?php echo $student['id']; ?>"
        >
            Edit Profile
        </a>

        <a class="btn" href="students.php">
            Back to Students
        </a>

    </div>

</div>

</body>

</html>
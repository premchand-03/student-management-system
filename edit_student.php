<?php
include "db.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$message = "";
$message_type = "";

if ($id <= 0) {
    die("Invalid student ID.");
}

// Get student
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

// Update student
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $course = trim($_POST["course"]);

    if ($name == "" || $email == "") {

        $message = "Name and email are required.";
        $message_type = "error";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";
        $message_type = "error";

    } else {

        // Check duplicate email
        $check_sql = "SELECT id FROM students
                      WHERE email = ? AND id != ?";

        $check_stmt = mysqli_prepare($conn, $check_sql);

        mysqli_stmt_bind_param(
            $check_stmt,
            "si",
            $email,
            $id
        );

        mysqli_stmt_execute($check_stmt);

        $check_result = mysqli_stmt_get_result($check_stmt);

        if (mysqli_num_rows($check_result) > 0) {

            $message = "This email is already used by another student.";
            $message_type = "error";

        } else {

            $update_sql = "UPDATE students
                           SET name = ?,
                               email = ?,
                               phone = ?,
                               course = ?
                           WHERE id = ?";

            $update_stmt = mysqli_prepare($conn, $update_sql);

            mysqli_stmt_bind_param(
                $update_stmt,
                "ssssi",
                $name,
                $email,
                $phone,
                $course,
                $id
            );

            if (mysqli_stmt_execute($update_stmt)) {

                $message = "Student updated successfully!";
                $message_type = "success";

                // Update displayed values
                $student["name"] = $name;
                $student["email"] = $email;
                $student["phone"] = $phone;
                $student["course"] = $course;

            } else {

                $message = "Something went wrong.";
                $message_type = "error";
            }

            mysqli_stmt_close($update_stmt);
        }

        mysqli_stmt_close($check_stmt);
    }
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Edit Student - Student Management System</title>

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

    <h1>Edit Student</h1>

    <?php if ($message != ""): ?>

        <p class="<?php echo $message_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </p>

    <?php endif; ?>

    <form method="POST">

        <label>Name</label>

        <input
            type="text"
            name="name"
            value="<?php echo htmlspecialchars($student['name']); ?>"
            required
        >

        <label>Email</label>

        <input
            type="email"
            name="email"
            value="<?php echo htmlspecialchars($student['email']); ?>"
            required
        >

        <label>Phone</label>

        <input
            type="text"
            name="phone"
            value="<?php echo htmlspecialchars($student['phone']); ?>"
        >

        <label>Course</label>

        <input
            type="text"
            name="course"
            value="<?php echo htmlspecialchars($student['course']); ?>"
        >

        <button type="submit">
            Update Student
        </button>

        <a class="btn" href="students.php">
            Back to Students
        </a>

    </form>

</div>

</body>

</html>
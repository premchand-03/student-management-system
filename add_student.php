<?php
include "db.php";

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $course = trim($_POST["course"]);
    $password = $_POST["password"];

    // Basic validation
    if ($name == "" || $email == "" || $password == "") {

        $message = "Name, email and password are required.";
        $message_type = "error";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";
        $message_type = "error";

    } elseif (strlen($password) < 6) {

        $message = "Password must be at least 6 characters.";
        $message_type = "error";

    } else {

        // Check whether email already exists
        $check_sql = "SELECT id FROM students WHERE email = ?";
        $check_stmt = mysqli_prepare($conn, $check_sql);

        mysqli_stmt_bind_param($check_stmt, "s", $email);
        mysqli_stmt_execute($check_stmt);

        $check_result = mysqli_stmt_get_result($check_stmt);

        if (mysqli_num_rows($check_result) > 0) {

            $message = "This email is already registered.";
            $message_type = "error";

        } else {

            // Hash password before storing it
            $hashed_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $sql = "INSERT INTO students
                    (name, email, phone, course, password)
                    VALUES (?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "sssss",
                $name,
                $email,
                $phone,
                $course,
                $hashed_password
            );

            if (mysqli_stmt_execute($stmt)) {

                $message = "Student added successfully!";
                $message_type = "success";

            } else {

                $message = "Something went wrong. Please try again.";
                $message_type = "error";
            }

            mysqli_stmt_close($stmt);
        }

        mysqli_stmt_close($check_stmt);
    }
}
?>

<!DOCTYPE html>
<html>
<head>

    <title>Add Student - Student Management System</title>

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

    <h1>Add New Student</h1>

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
            placeholder="Enter student name"
            required
        >

        <label>Email</label>

        <input
            type="email"
            name="email"
            placeholder="Enter email"
            required
        >

        <label>Phone</label>

        <input
            type="text"
            name="phone"
            placeholder="Enter phone number"
        >

        <label>Course</label>

        <input
            type="text"
            name="course"
            value="BCA"
        >

        <label>Password</label>

        <input
            type="password"
            name="password"
            placeholder="Minimum 6 characters"
            required
        >

        <button type="submit">
            Add Student
        </button>

    </form>

</div>

</body>
</html>
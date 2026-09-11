<?php
session_start();
include "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if ($email == "" || $password == "") {

        $error = "Email and password are required.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Please enter a valid email address.";

    } else {

        $sql = "SELECT id, name, password
                FROM students
                WHERE email = ?";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $student = mysqli_fetch_assoc($result);

        if ($student && password_verify($password, $student["password"])) {

            $_SESSION["student_id"] = $student["id"];
            $_SESSION["student_name"] = $student["name"];

            header("Location: dashboard.php");
            exit;

        } else {

            $error = "Invalid email or password.";
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Login - Student Management System</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="login-container">

    <div class="login-card">

        <h1>Student Management System</h1>

        <p class="login-subtitle">
            Login to your account
        </p>

        <?php if ($error != ""): ?>

            <p class="error">
                <?php echo htmlspecialchars($error); ?>
            </p>

        <?php endif; ?>

        <form method="POST">

            <label>Email</label>

            <input
                type="email"
                name="email"
                placeholder="Enter your email"
                required
            >

            <label>Password</label>

            <input
                type="password"
                name="password"
                placeholder="Enter your password"
                required
            >

            <button type="submit" class="login-btn">
                Login
            </button>

        </form>

    </div>

</div>

</body>

</html>
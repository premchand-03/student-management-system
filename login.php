<?php
session_start();
include "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM students WHERE email = ?";
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
        $error = "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Student Login</h1>

<?php if ($error != ""): ?>
    <p><?php echo $error; ?></p>
<?php endif; ?>

<form method="POST">

    <label>Email:</label><br>
    <input type="email" name="email" required>

    <br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required>

    <br><br>

    <button type="submit">Login</button>

</form>

</body>
</html>
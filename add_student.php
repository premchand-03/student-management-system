<?php
include "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $course = $_POST["course"];
    $password = $_POST["password"];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO students (name, email, phone, course, password)
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
    } else {
        $message = "Error: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h1>Add Student</h1>

    <?php if ($message != ""): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST">

        <label>Name:</label><br>
        <input type="text" name="name" required>
        <br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required>
        <br><br>

        <label>Phone:</label><br>
        <input type="text" name="phone">
        <br><br>

        <label>Course:</label><br>
        <input type="text" name="course" value="BCA">
        <br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required>
        <br><br>

        <button type="submit">Add Student</button>

    </form>

</body>
</html>
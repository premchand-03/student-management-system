<?php
include "db.php";

$id = $_GET['id'];

$sql = "SELECT * FROM students WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$student = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $course = $_POST["course"];

    $sql = "UPDATE students
            SET name=?, email=?, phone=?, course=?
            WHERE id=?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssssi",
        $name,
        $email,
        $phone,
        $course,
        $id
    );

    mysqli_stmt_execute($stmt);

    header("Location: students.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Edit Student</h1>

<form method="POST">

    Name:
    <input type="text" name="name"
           value="<?php echo htmlspecialchars($student['name']); ?>"
           required>
    <br><br>

    Email:
    <input type="email" name="email"
           value="<?php echo htmlspecialchars($student['email']); ?>"
           required>
    <br><br>

    Phone:
    <input type="text" name="phone"
           value="<?php echo htmlspecialchars($student['phone']); ?>">
    <br><br>

    Course:
    <input type="text" name="course"
           value="<?php echo htmlspecialchars($student['course']); ?>">
    <br><br>

    <button type="submit">Update Student</button>

</form>

</body>
</html>
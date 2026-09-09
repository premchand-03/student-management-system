<?php
include "db.php";

$search = $_GET['search'] ?? '';

$sql = "SELECT id, name, email, phone, course
        FROM students
        WHERE name LIKE ?
        ORDER BY id DESC";

$stmt = mysqli_prepare($conn, $sql);

$searchTerm = "%" . $search . "%";

mysqli_stmt_bind_param($stmt, "s", $searchTerm);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Students</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<h1>All Students</h1>

<form method="GET">
    <input
        type="text"
        name="search"
        placeholder="Search student..."
        value="<?php echo htmlspecialchars($search); ?>"
    >

    <button type="submit">Search</button>
</form>

<br>

<a href="add_student.php">+ Add New Student</a>

<br><br>

<table border="1" cellpadding="10" cellspacing="0">

    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Course</th>
        <th>Action</th>
    </tr>

    <?php while ($student = mysqli_fetch_assoc($result)) { ?>

    <tr>

        <td><?php echo $student['id']; ?></td>

        <td>
            <?php echo htmlspecialchars($student['name']); ?>
        </td>

        <td>
            <?php echo htmlspecialchars($student['email']); ?>
        </td>

        <td>
            <?php echo htmlspecialchars($student['phone'] ?? ''); ?>
        </td>

        <td>
            <?php echo htmlspecialchars($student['course'] ?? ''); ?>
        </td>

        <td>

            <a href="edit_student.php?id=<?php echo $student['id']; ?>">
                Edit
            </a>

            |

            <a
                href="delete_student.php?id=<?php echo $student['id']; ?>"
                onclick="return confirm('Are you sure?');"
            >
                Delete
            </a>

        </td>

    </tr>

    <?php } ?>

</table>

</body>
</html>
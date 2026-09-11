<?php
include "db.php";

$search = trim($_GET["search"] ?? "");

if ($search != "") {

    $sql = "SELECT id, name, email, phone, course
            FROM students
            WHERE name LIKE ?
               OR email LIKE ?
               OR course LIKE ?
            ORDER BY id DESC";

    $stmt = mysqli_prepare($conn, $sql);

    $searchTerm = "%" . $search . "%";

    mysqli_stmt_bind_param(
        $stmt,
        "sss",
        $searchTerm,
        $searchTerm,
        $searchTerm
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

} else {

    $sql = "SELECT id, name, email, phone, course
            FROM students
            ORDER BY id DESC";

    $result = mysqli_query($conn, $sql);
}

if (!$result) {
    die("Unable to load students.");
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Students - Student Management System</title>

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

    <div class="page-header">

        <div>
            <h1>Students</h1>
            <p>Manage all registered students.</p>
        </div>

        <a class="btn" href="add_student.php">
            + Add Student
        </a>

    </div>

    <form class="search-form" method="GET">

        <input
            type="text"
            name="search"
            value="<?php echo htmlspecialchars($search); ?>"
            placeholder="Search by name, email or course..."
        >

        <button type="submit">
            Search
        </button>

        <?php if ($search != ""): ?>

            <a class="clear-btn" href="students.php">
                Clear
            </a>

        <?php endif; ?>

    </form>

    <div class="table-wrapper">

        <table>

            <thead>

                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Course</th>
                    <th>Actions</th>
                </tr>

            </thead>

            <tbody>

                <?php if (mysqli_num_rows($result) > 0): ?>

                    <?php while ($row = mysqli_fetch_assoc($result)): ?>

                        <tr>

                            <td>
                                <?php echo $row["id"]; ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row["name"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row["email"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row["phone"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row["course"]); ?>
                            </td>

                            <td class="actions">

                                <a
                                    class="edit-btn"
                                    href="edit_student.php?id=<?php echo $row['id']; ?>"
                                >
                                    Edit
                                </a>

                                <a
                                    class="delete-btn"
                                    href="delete_student.php?id=<?php echo $row['id']; ?>"
                                    onclick="return confirm('Are you sure you want to delete this student?');"
                                >
                                    Delete
                                </a>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>

                        <td colspan="6" class="no-data">
                            No students found.
                        </td>

                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

</body>

</html>
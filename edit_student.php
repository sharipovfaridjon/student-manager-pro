<?php

include 'admin_only.php';
include 'db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $conn->prepare("SELECT id, name, email, phone, course, group_name, status FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    header("Location: view_students.php");
    exit();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="loader"><div class="spinner"></div></div>

<div class="layout">

    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="index.php">Dashboard</a>
        <a href="view_students.php">Students</a>
        <a href="export_csv.php">Export CSV</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="main">
        <div class="container">

            <div class="header">
                <div>
                    <h1>Edit Student</h1>
                    <p class="subtitle">Update student information.</p>
                </div>

                <button onclick="toggleDarkMode()">🌙</button>
            </div>

            <div class="card">
                <form method="POST" action="update_student.php" autocomplete="off">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">

                    <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required minlength="3">
                    <input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($row['phone']); ?>" placeholder="Phone number">
                    <input type="text" name="course" value="<?php echo htmlspecialchars($row['course']); ?>" placeholder="Course">
                    <input type="text" name="group_name" value="<?php echo htmlspecialchars($row['group_name']); ?>" placeholder="Group">

                    <select name="status" class="input-select">
                        <option value="Active" <?php if ($row['status'] === "Active") echo "selected"; ?>>Active</option>
                        <option value="Inactive" <?php if ($row['status'] === "Inactive") echo "selected"; ?>>Inactive</option>
                    </select>

                    <button type="submit">Update</button>
                    <a class="btn btn-light" href="view_students.php">Back</a>
                </form>
            </div>

        </div>
    </div>

</div>

<script src="script.js"></script>

</body>
</html>
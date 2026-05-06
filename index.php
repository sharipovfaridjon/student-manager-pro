<?php

include 'auth.php';
include 'db.php';

$totalResult = $conn->query("SELECT COUNT(*) AS total FROM students");
$totalStudents = (int)$totalResult->fetch_assoc()['total'];

$recent = $conn->query("SELECT id, name, email, phone, course, group_name, status FROM students ORDER BY id DESC LIMIT 3");

?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Manager Pro</title>
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
                    <h1>Dashboard</h1>
                    <p class="subtitle">Professional student management system.</p>
                    <p>
                        Welcome,
                        <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
                        (<?php echo htmlspecialchars($_SESSION['role']); ?>)
                    </p>
                </div>

                <button onclick="toggleDarkMode()">🌙</button>
            </div>

            <div class="card-grid">

                <?php if ($_SESSION['role'] === "admin"): ?>
                    <div class="card">
                        <h2>Add New Student</h2>

                        <form id="addForm" method="POST" action="add_student.php" autocomplete="off">
                            <input type="text" name="name" placeholder="Student name" required minlength="3">
                            <input type="email" name="email" placeholder="Student email" required>
                            <input type="text" name="phone" placeholder="Phone number">
                            <input type="text" name="course" placeholder="Course">
                            <input type="text" name="group_name" placeholder="Group">
                            <select name="status" class="input-select">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                            <button type="submit">Add Student</button>
                        </form>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <h2>Dashboard Stats</h2>
                    <p>👥 Total Students: <strong id="total"><?php echo $totalStudents; ?></strong></p>
                    <p>🟢 Database: <strong>Connected</strong></p>
                    <p>⚙️ CRUD System: <strong>Active</strong></p>
                    <p>🛡️ Security: <strong>Prepared Statements</strong></p>
                    <p>📤 Export: <strong>CSV Ready</strong></p>
                </div>

            </div>

            <br>

            <div class="card">
                <h2>Student Growth</h2>

                <div class="chart">
                    <div class="bar" style="height: <?php echo max(40, $totalStudents * 20); ?>px;">
                        <span><?php echo $totalStudents; ?></span>
                    </div>
                </div>
            </div>

            <br>

            <div class="card">
                <h2>Recent Students</h2>

                <table>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Course</th>
                        <th>Group</th>
                        <th>Status</th>
                    </tr>

                    <?php while ($row = $recent->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['course']); ?></td>
                            <td><?php echo htmlspecialchars($row['group_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>

        </div>
    </div>

</div>

<script src="script.js"></script>

<script>
const form = document.getElementById("addForm");

if (form) {
    form.addEventListener("submit", function(e) {
        e.preventDefault();

        const btn = this.querySelector("button");
        const originalText = btn.innerText;

        btn.disabled = true;
        btn.innerText = "Adding...";

        const formData = new FormData(this);

        fetch("add_student.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.text())
        .then(data => {
            if (data.trim() === "success") {
                showToast("Student added successfully!");

                const totalEl = document.getElementById("total");
                totalEl.innerText = parseInt(totalEl.innerText, 10) + 1;

                this.reset();
                setTimeout(() => location.reload(), 600);
            } else {
                showToast(data, true);
            }
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerText = originalText;
        });
    });
}

function showToast(msg, error = false) {
    const toast = document.createElement("div");
    toast.className = "toast " + (error ? "error" : "success");
    toast.innerText = msg;
    document.body.appendChild(toast);

    setTimeout(() => toast.remove(), 3000);
}
</script>

</body>
</html>
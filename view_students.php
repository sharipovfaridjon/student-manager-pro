<?php

include 'auth.php';
include 'db.php';

$search = "";
$limit = 5;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$start = ($page - 1) * $limit;

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    $searchValue = "%" . $search . "%";

    $countStmt = $conn->prepare("SELECT COUNT(*) AS total FROM students WHERE name LIKE ? OR email LIKE ? OR phone LIKE ? OR course LIKE ? OR group_name LIKE ?");
    $countStmt->bind_param("sssss", $searchValue, $searchValue, $searchValue, $searchValue, $searchValue);
    $countStmt->execute();
    $totalRows = (int)$countStmt->get_result()->fetch_assoc()['total'];

    $stmt = $conn->prepare("SELECT id, name, email, phone, course, group_name, status FROM students WHERE name LIKE ? OR email LIKE ? OR phone LIKE ? OR course LIKE ? OR group_name LIKE ? ORDER BY id DESC LIMIT ?, ?");
    $stmt->bind_param("sssssii", $searchValue, $searchValue, $searchValue, $searchValue, $searchValue, $start, $limit);
} else {
    $totalRows = (int)$conn->query("SELECT COUNT(*) AS total FROM students")->fetch_assoc()['total'];

    $stmt = $conn->prepare("SELECT id, name, email, phone, course, group_name, status FROM students ORDER BY id DESC LIMIT ?, ?");
    $stmt->bind_param("ii", $start, $limit);
}

$stmt->execute();
$result = $stmt->get_result();

$totalPages = max(1, ceil($totalRows / $limit));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Students List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="loader"><div class="spinner"></div></div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="toast success">
        <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

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
                    <h1>Students List</h1>
                    <p class="subtitle">Search, edit and manage student records.</p>
                    <p>
                        Logged in as:
                        <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
                        (<?php echo htmlspecialchars($_SESSION['role']); ?>)
                    </p>
                </div>

                <button onclick="toggleDarkMode()">🌙</button>
            </div>

            <div class="card">
                <form method="GET" autocomplete="off">
                    <input
                        type="text"
                        name="search"
                        placeholder="Search by name, email, phone, course or group"
                        value="<?php echo htmlspecialchars($search); ?>"
                    >
                    <button type="submit">Search</button>
                    <a class="btn btn-light" href="view_students.php">Reset</a>
                </form>
            </div>

            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Course</th>
                    <th>Group</th>
                    <th>Status</th>
                    <?php if ($_SESSION['role'] === "admin"): ?>
                        <th>Action</th>
                    <?php endif; ?>
                </tr>

                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr id="row-<?php echo $row['id']; ?>">
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td><?php echo htmlspecialchars($row['course']); ?></td>
                        <td><?php echo htmlspecialchars($row['group_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>

                        <?php if ($_SESSION['role'] === "admin"): ?>
                            <td>
                                <div class="actions">
                                    <a class="btn btn-blue" href="edit_student.php?id=<?php echo $row['id']; ?>">Edit</a>
                                    <button class="btn btn-red" onclick="openDeleteModal(<?php echo $row['id']; ?>)">Delete</button>
                                </div>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            </table>

            <div class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a class="btn <?php if ($i === $page) echo 'btn-blue'; ?>"
                       href="view_students.php?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>

        </div>
    </div>

</div>

<div class="modal" id="deleteModal">
    <div class="modal-box">
        <h2>Delete Student?</h2>
        <p>This action cannot be undone.</p>

        <div class="actions">
            <button class="btn btn-red" onclick="confirmDelete()">Yes, Delete</button>
            <button class="btn btn-light" onclick="closeDeleteModal()">Cancel</button>
        </div>
    </div>
</div>

<script src="script.js"></script>

<script>
let deleteId = null;

function openDeleteModal(id) {
    deleteId = id;
    document.getElementById("deleteModal").style.display = "flex";
}

function closeDeleteModal() {
    deleteId = null;
    document.getElementById("deleteModal").style.display = "none";
}

function confirmDelete() {
    if (!deleteId) return;

    fetch("delete_student.php?id=" + deleteId + "&ajax=1")
        .then(response => response.text())
        .then(data => {
            if (data.trim() === "success") {
                const row = document.getElementById("row-" + deleteId);

                if (row) {
                    row.remove();
                }

                closeDeleteModal();
                showToast("Student deleted successfully!");
            } else {
                showToast("Delete failed.", true);
            }
        })
        .catch(() => showToast("Network error.", true));
}

function showToast(message, error = false) {
    const toast = document.createElement("div");
    toast.className = "toast " + (error ? "error" : "success");
    toast.innerText = message;
    document.body.appendChild(toast);

    setTimeout(() => toast.remove(), 3000);
}
</script>

</body>
</html>
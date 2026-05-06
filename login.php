<?php

include 'db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!empty($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $stmt = $conn->prepare("SELECT username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && $password === $user['password']) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        header("Location: index.php");
        exit();
    }

    $error = "Wrong username or password.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="loader"><div class="spinner"></div></div>

<div class="login-page">
    <div class="login-box">

        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h1>Student Manager</h1>
            <button onclick="toggleDarkMode()">🌙</button>
        </div>

        <p class="subtitle">Login to your account</p>

        <?php if ($error !== ""): ?>
            <div class="error-box"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" onsubmit="showLoading(this)" autocomplete="off">
            <input type="text" name="username" placeholder="Username" required autofocus>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>

        <p class="subtitle">
            Admin: admin / 12345
        </p>

        <a class="btn btn-light" href="register.php">Create viewer account</a>

    </div>
</div>

<script src="script.js"></script>

<script>
function showLoading(form) {
    const btn = form.querySelector("button");
    btn.disabled = true;
    btn.innerText = "Checking...";
    btn.classList.add("loading");
}
</script>

</body>
</html>
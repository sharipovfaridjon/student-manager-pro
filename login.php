<?php

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

    if ($username === "admin" && $password === "12345") {
        $_SESSION['username'] = "admin";
        $_SESSION['role'] = "admin";
        $_SESSION['success'] = "Welcome back, admin!";
        header("Location: index.php");
        exit();
    }

    if ($username === "viewer" && $password === "1111") {
        $_SESSION['username'] = "viewer";
        $_SESSION['role'] = "viewer";
        $_SESSION['success'] = "Welcome, viewer!";
        header("Location: view_students.php");
        exit();
    }

    $error = "Wrong username or password!";
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

        <p class="subtitle">Secure Admin Login System</p>

        <?php if ($error !== ""): ?>
            <div class="error-box"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" onsubmit="showLoading(this)" autocomplete="off">
            <input type="text" name="username" placeholder="Username" required autofocus>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>

        <p class="subtitle">
            Admin: admin / 12345<br>
            Viewer: viewer / 1111
        </p>

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
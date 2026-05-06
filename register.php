<?php
include 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (strlen($username) < 3) {
        $message = "Username too short";
    } elseif (strlen($password) < 4) {
        $message = "Password too short";
    } else {

        $check = $conn->prepare("SELECT id FROM users WHERE username=?");
        $check->bind_param("s", $username);
        $check->execute();

        if ($check->get_result()->num_rows > 0) {

            $message = "Username already exists";

        } else {

            $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'viewer')");
            $stmt->bind_param("ss", $username, $password);

            if ($stmt->execute()) {
                $message = "Registration successful";
            } else {
                $message = "Registration failed";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="login-page">

    <div class="login-box">

        <h1>Create Account</h1>

        <?php if ($message !== ""): ?>
            <div class="error-box">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <input
                type="text"
                name="username"
                placeholder="Username"
                required
            >

            <input
                type="password"
                name="password"
                placeholder="Password"
                required
            >

            <button type="submit">
                Register
            </button>

        </form>

        <br>

        <a class="btn btn-light" href="login.php">
            Back to Login
        </a>

    </div>

</div>

</body>
</html>
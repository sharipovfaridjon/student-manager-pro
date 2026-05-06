<?php

include 'admin_only.php';
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo "Invalid request";
    exit();
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');

if (strlen($name) < 3) {
    echo "Name too short";
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email";
    exit();
}

$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$check = $stmt->get_result();

if ($check->num_rows > 0) {
    echo "Email exists";
    exit();
}

$stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
$stmt->bind_param("ss", $name, $email);

if ($stmt->execute()) {
    echo "success";
    exit();
}

echo "error";
exit();

?>
<?php

include 'admin_only.php';
include 'db.php';

$id = (int)($_GET['id'] ?? 0);
$isAjax = isset($_GET['ajax']);

if ($id <= 0) {
    if ($isAjax) {
        echo "error";
        exit();
    }

    $_SESSION['success'] = "Invalid student ID.";
    header("Location: view_students.php");
    exit();
}

$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    if ($isAjax) {
        echo "success";
        exit();
    }

    $_SESSION['success'] = "Student deleted successfully.";
    header("Location: view_students.php");
    exit();
}

if ($isAjax) {
    echo "error";
    exit();
}

$_SESSION['success'] = "Delete failed.";
header("Location: view_students.php");
exit();

?>
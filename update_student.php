<?php

include 'admin_only.php';
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: view_students.php");
    exit();
}

$id = (int)($_POST['id'] ?? 0);
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$course = trim($_POST['course'] ?? '');
$groupName = trim($_POST['group_name'] ?? '');
$status = trim($_POST['status'] ?? 'Active');

if ($id <= 0) {
    $_SESSION['success'] = "Invalid student ID.";
    header("Location: view_students.php");
    exit();
}

if (strlen($name) < 3) {
    $_SESSION['success'] = "Name is too short.";
    header("Location: edit_student.php?id=" . $id);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['success'] = "Invalid email address.";
    header("Location: edit_student.php?id=" . $id);
    exit();
}

$stmt = $conn->prepare("
    UPDATE students
    SET name = ?, email = ?, phone = ?, course = ?, group_name = ?, status = ?
    WHERE id = ?
");

$stmt->bind_param("ssssssi", $name, $email, $phone, $course, $groupName, $status, $id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Student updated successfully.";
    header("Location: view_students.php");
    exit();
}

$_SESSION['success'] = "Update failed.";
header("Location: edit_student.php?id=" . $id);
exit();

?>
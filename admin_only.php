<?php

include 'auth.php';

if (empty($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: view_students.php");
    exit();
}

?>
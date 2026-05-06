<?php

include 'admin_only.php';
include 'db.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=students.csv');

$output = fopen('php://output', 'w');

fputcsv($output, ['ID', 'Name', 'Email']);

$result = $conn->query("SELECT id, name, email FROM users ORDER BY id DESC");

while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        $row['id'],
        $row['name'],
        $row['email']
    ]);
}

fclose($output);
exit();

?>
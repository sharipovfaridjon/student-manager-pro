<?php

include 'admin_only.php';
include 'db.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=students.csv');

$output = fopen('php://output', 'w');

fputcsv($output, [
    'ID',
    'Name',
    'Email',
    'Phone',
    'Course',
    'Group',
    'Status',
    'Created At'
]);

$result = $conn->query("
    SELECT id, name, email, phone, course, group_name, status, created_at
    FROM students
    ORDER BY id DESC
");

while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        $row['id'],
        $row['name'],
        $row['email'],
        $row['phone'],
        $row['course'],
        $row['group_name'],
        $row['status'],
        $row['created_at']
    ]);
}

fclose($output);
exit();

?>
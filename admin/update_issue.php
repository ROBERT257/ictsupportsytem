<?php
session_start();
require '../db.php';

// Restrict unauthorized access
if (!isset($_SESSION['staff_id'])) {
    die("Unauthorized access");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $issue_id = $_POST['issue_id'];
    $status = $_POST['status'];
    $assigned_to = $_POST['assigned_to'];
    $assigned_by = $_SESSION['full_name'];

    $stmt = $conn->prepare("UPDATE support_issues 
                            SET status = ?, assigned_to = ?, assigned_by = ? 
                            WHERE id = ?");
    $stmt->bind_param("sssi", $status, $assigned_to, $assigned_by, $issue_id);

    if ($stmt->execute()) {
        header("Location: get_issues.php");
        exit();
    } else {
        echo "Error updating issue: " . $conn->error;
    }
}
?>

<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        die("Unauthorized");
    }

    $user_id = $_SESSION['user_id'];
    $issue_type = $_POST['issue_type'];
    $sub_category = $_POST['sub_category'];
    $description = $_POST['description'];
    $submitted_at = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("INSERT INTO support_issues 
        (user_id, issue_type, sub_category, description, submitted_at) 
        VALUES (?, ?, ?, ?, ?)");

    $stmt->bind_param("sssss", $user_id, $issue_type, $sub_category, $description, $submitted_at);

    if ($stmt->execute()) {
        echo "Issue submitted successfully.";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $fullname = $_POST['fullname'];
    $building = $_POST['building'];
    $office = $_POST['office'];
    $department = $_POST['department'];
    $phone = $_POST['phone_number'];

    $stmt = $conn->prepare("UPDATE users SET fullname = ?, building = ?, office = ?, department = ?, phone_number = ? WHERE user_id = ?");
    $stmt->bind_param("ssssss", $fullname, $building, $office, $department, $phone, $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('Profile updated successfully!'); window.location.href='profile.php';</script>";
    } else {
        echo "<script>alert('Error updating profile.'); window.location.href='profile.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = uniqid("user_");
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone_number'];
    $department = $_POST['department'];
    $office = $_POST['office'];
    $building = $_POST['building'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (user_id, fullname, phone_number, password, department, office, building)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $user_id, $fullname, $phone, $password, $department, $office, $building);

    if ($stmt->execute()) {
        echo "✅ Registration successful. <a href='login.php'>Login now</a>";
    } else {
        echo "❌ Error: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
?>

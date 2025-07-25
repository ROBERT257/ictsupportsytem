<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../db.php';

$full_name = 'System Admin';
$department = 'ICT';
$email = 'robertochieng257@gmail.com';
$phone_number = '0792162416';
$password = password_hash('123', PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO staff (full_name, department, email, phone_number, password)
VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $full_name, $department, $email, $phone_number, $password);

$insertMessage = "";
if ($stmt->execute()) {
    $insertMessage = "Admin added successfully!";
} else {
    $insertMessage = "Error: " . $stmt->error;
}
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Admin</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; background: #f4f4f4; }
        .logo { margin-bottom: 20px; }
        .message { font-size: 20px; font-weight: bold; color: #333; }
    </style>
</head>
<body>
    <!-- Logo -->
    <div class="logo">
        <img src="../images/sdps.png" alt="System Logo" width="120">
    </div>

    <!-- Result Message -->
    <div class="message"><?php echo htmlspecialchars($insertMessage); ?></div>
</body>
</html>

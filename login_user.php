<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone_number = $_POST['phone_number'];
    $password = $_POST['password'];

    // === 1. Check if it's an admin (from staff table) ===
    $stmt = $conn->prepare("SELECT staff_id, full_name, password, role FROM staff WHERE phone_number = ?");
    $stmt->bind_param("s", $phone_number);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($staff_id, $full_name, $hashed_password, $role);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Save admin session
            $_SESSION['staff_id'] = $staff_id;
            $_SESSION['full_name'] = $full_name;
            $_SESSION['role'] = ($role === 'superadmin') ? 'superadmin' : 'admin';

            header("Location: admin/admin_dashboard.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        // === 2. Check if it's a normal user ===
        $stmt = $conn->prepare("SELECT user_id, fullname, password FROM users WHERE phone_number = ?");
        $stmt->bind_param("s", $phone_number);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($user_id, $fullname, $user_password);
            $stmt->fetch();

            if (password_verify($password, $user_password)) {
                // Save user session
                $_SESSION['user_id'] = $user_id;
                $_SESSION['fullname'] = $fullname;

                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "No account found with that phone number.";
        }
    }
    $stmt->close();
}
?>

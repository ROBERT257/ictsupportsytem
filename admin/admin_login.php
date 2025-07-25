<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];

    // Validate staff member
    $stmt = $conn->prepare("SELECT staff_id, full_name FROM staff WHERE email = ? AND phone_number = ?");
    $stmt->bind_param("ss", $email, $phone_number);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($staff_id, $full_name);
        $stmt->fetch();

        // Save session
        $_SESSION['staff_id'] = $staff_id;
        $_SESSION['full_name'] = $full_name;

        // Redirect to admin dashboard
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Invalid email or phone number.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - ICT Support System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('../images/ICT 1.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 10px;
            width: 350px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            text-align: center;
        }
       .login-card img {
        width: 300px;        /* Increase size */
        margin-bottom: 25px; /* Give more spacing below */
        }

        .login-card h2 {
            margin-bottom: 25px;
            color: #2c3e50;
        }
        .login-card label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        .login-card input[type="email"],
        .login-card input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }
        .login-card input[type="submit"] {
            width: 100%;
            padding: 12px;
            background: #3498db;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .login-card input[type="submit"]:hover {
            background: #2980b9;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
        .footer {
            margin-top: 15px;
            font-size: 12px;
            color: #777;
        }
        
    </style>
</head>
<body>
    <div class="login-card">
        <img src="../images/sdps.png" alt="Logo" onerror="this.style.display='none'">
        <h2>Admin Login</h2>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Phone Number:</label>
            <input type="text" name="phone_number" required>

            <input type="submit" value="Login">
        </form>
        <div class="footer">
            &copy; <?php echo date('Y'); ?> ICT Support System
        </div>
    </div>
</body>
</html>

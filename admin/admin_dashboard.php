<?php
session_start();

// Restrict access to admins only
if (!isset($_SESSION['staff_id'])) {
    header("Location: admin_login.php");
    exit();
}

$full_name = $_SESSION['full_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - ICT Support System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('../images/ICT 1.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        .header {
            background: rgba(44, 62, 80, 0.9);
            color: #fff;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .header img {
            height: 60px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .container {
            max-width: 900px;
            margin: 30px auto;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.3);
        }
        h2 {
            margin-top: 0;
            color: #2c3e50;
        }
        ul {
            list-style: none;
            padding: 0;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }
        ul li {
            display: flex;
            justify-content: center;
        }
        a.button {
            display: block;
            background: #3498db;
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-decoration: none;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            width: 100%;
            transition: 0.3s;
        }
        a.button:hover {
            background: #2980b9;
        }
        a.logout {
            background: #e74c3c;
        }
        .footer {
            text-align: center;
            margin: 20px 0;
            color: #fff;
            font-weight: bold;
            background: rgba(44, 62, 80, 0.7);
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="../images/sdps.png" alt="ICT Support Logo" onerror="this.src='https://via.placeholder.com/60';">
        <h1>Admin Dashboard</h1>
    </div>

    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($full_name); ?> (Admin)</h2>
        <ul>
            <li><a href="get_issues.php" class="button">📄 View Support Issues</a></li>
            <li><a href="view_feedback.php" class="button">💬 View Feedback</a></li>
            <li><a href="../inventory/view_items.php" class="button">📦 Manage Inventory</a></li>
            <li><a href="view_memos.php" class="button">📑 View Office Memos</a></li>
            <li><a href="logout.php" class="button logout">🚪 Logout</a></li>
        </ul>
    </div>

    <div class="footer">
        &copy; <?php echo date('Y'); ?> ICT Support System
    </div>
</body>
</html>

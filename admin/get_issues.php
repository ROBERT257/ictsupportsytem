<?php
session_start();
require '../db.php';

// Restrict access to admins only
if (!isset($_SESSION['staff_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch support issues
$stmt = $conn->prepare("SELECT id, user_id, issue_type, sub_category, description, status, 
                               assigned_to, submitted_at 
                        FROM support_issues 
                        ORDER BY submitted_at DESC");
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Support Issues</title>
    <link rel="stylesheet" href="../css/responsive.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: url('../images/ICT 2.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        .container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 25px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.3);
        }
        h2 {
            text-align: center;
            color: #2c3e50;
        }
        a.back-btn {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            background: #3498db;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 14px;
            transition: 0.3s;
        }
        a.back-btn:hover { background: #2980b9; }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            font-size: 14px;
            word-wrap: break-word;
        }
        th { background: #2c3e50; color: white; }
        tr:hover { background: #f9f9f9; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; color: white; }
        .pending { background: #f39c12; }
        .in-progress { background: #3498db; }
        .resolved { background: #27ae60; }
        form { display: flex; gap: 5px; flex-wrap: wrap; }
        form select, form input[type=text] {
            padding: 5px;
            font-size: 13px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        form input[type=submit] {
            background: #27ae60;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            transition: 0.3s;
        }
        form input[type=submit]:hover { background: #219150; }
        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr { display: block; width: 100%; }
            tr { margin-bottom: 15px; }
            td { 
                text-align: right; 
                padding-left: 50%; 
                position: relative; 
            }
            td::before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 50%;
                padding-left: 15px;
                font-weight: bold;
                text-align: left;
            }
            th { display: none; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>All Support Issues</h2>
        <a href="admin_dashboard.php" class="back-btn">← Back to Dashboard</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Issue Type</th>
                    <th>Sub-Category</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                    <th>Submitted At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td data-label="ID"><?= htmlspecialchars($row['id'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td data-label="User ID"><?= htmlspecialchars($row['user_id'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td data-label="Issue Type"><?= htmlspecialchars($row['issue_type'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td data-label="Sub-Category"><?= htmlspecialchars($row['sub_category'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td data-label="Description"><?= htmlspecialchars($row['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td data-label="Status">
                        <?php 
                            $status = strtolower($row['status'] ?? '');
                            $class = ($status=="pending"?"pending":($status=="in progress"?"in-progress":"resolved"));
                            echo "<span class='badge $class'>".htmlspecialchars($row['status'] ?? '', ENT_QUOTES, 'UTF-8')."</span>";
                        ?>
                    </td>
                    <td data-label="Assigned To"><?= htmlspecialchars($row['assigned_to'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td data-label="Submitted At"><?= htmlspecialchars(date("Y-m-d H:i", strtotime($row['submitted_at'] ?? '')), ENT_QUOTES, 'UTF-8') ?></td>
                    <td data-label="Actions">
                        <form action="update_issue.php" method="POST">
                            <input type="hidden" name="issue_id" value="<?= htmlspecialchars($row['id'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                            <select name="status">
                                <option value="Pending" <?= ($row['status']=="Pending"?"selected":"") ?>>Pending</option>
                                <option value="In Progress" <?= ($row['status']=="In Progress"?"selected":"") ?>>In Progress</option>
                                <option value="Resolved" <?= ($row['status']=="Resolved"?"selected":"") ?>>Resolved</option>
                            </select>
                            <input type="text" name="assigned_to" placeholder="Staff Name" value="<?= htmlspecialchars($row['assigned_to'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                            <input type="submit" value="Update">
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

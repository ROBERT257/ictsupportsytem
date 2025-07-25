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
        a.back-btn:hover {
            background: #2980b9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            table-layout: fixed;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            font-size: 14px;
            word-wrap: break-word;
        }
        th {
            background: #2c3e50;
            color: white;
        }
        tr:hover {
            background: #f9f9f9;
        }
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            color: white;
        }
        .pending { background: #f39c12; }
        .in-progress { background: #3498db; }
        .resolved { background: #27ae60; }
        form {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }
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
        form input[type=submit]:hover {
            background: #219150;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>All Support Issues</h2>
        <a href="admin_dashboard.php" class="back-btn">← Back to Dashboard</a>

        <table>
            <tr>
                <th style="width:5%;">ID</th>
                <th style="width:10%;">User ID</th>
                <th style="width:15%;">Issue Type</th>
                <th style="width:15%;">Sub-Category</th>
                <th style="width:20%;">Description</th>
                <th style="width:10%;">Status</th>
                <th style="width:10%;">Assigned To</th>
                <th style="width:15%;">Submitted At</th>
                <th style="width:20%;">Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['user_id']) ?></td>
                <td><?= htmlspecialchars($row['issue_type']) ?></td>
                <td><?= htmlspecialchars($row['sub_category']) ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
                <td>
                    <?php 
                        $status = strtolower($row['status']);
                        echo "<span class='badge ".($status=="pending"?"pending":($status=="in progress"?"in-progress":"resolved"))."'>"
                             .htmlspecialchars($row['status'])."</span>";
                    ?>
                </td>
                <td><?= htmlspecialchars($row['assigned_to']) ?></td>
                <td><?= date("Y-m-d H:i", strtotime($row['submitted_at'])) ?></td>
                <td>
                    <form action="update_issue.php" method="POST">
                        <input type="hidden" name="issue_id" value="<?= $row['id'] ?>">
                        <select name="status">
                            <option value="Pending" <?= $row['status']=="Pending"?"selected":"" ?>>Pending</option>
                            <option value="In Progress" <?= $row['status']=="In Progress"?"selected":"" ?>>In Progress</option>
                            <option value="Resolved" <?= $row['status']=="Resolved"?"selected":"" ?>>Resolved</option>
                        </select>
                        <input type="text" name="assigned_to" placeholder="Staff Name" value="<?= htmlspecialchars($row['assigned_to']) ?>">
                        <input type="submit" value="Update">
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>

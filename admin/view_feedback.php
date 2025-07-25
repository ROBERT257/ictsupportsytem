<?php
session_start();

// Restrict access
if (!isset($_SESSION['staff_id'])) {
    header("Location: admin_login.php");
    exit();
}

require_once __DIR__ . '/../db.php';

$query = "
    SELECT f.feedback_id, f.issue_id, f.user_id, f.rating, f.comments, f.timestamp,
           u.fullname 
    FROM feedback f
    LEFT JOIN users u ON f.user_id = u.user_id
    ORDER BY f.timestamp DESC
";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Feedback</title>
    <link rel="stylesheet" href="../css/responsive.css">

    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #3498db; color: white; }
        tr:nth-child(even) { background: #f9f9f9; }
        .container { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { margin-top: 0; }
        a { display: inline-block; margin-top: 15px; text-decoration: none; color: #3498db; }
    </style>
</head>
<body>
<div class="container">
    <h2>User Feedback</h2>
    <table>
        <tr>
            <th>Feedback ID</th>
            <th>Issue ID</th>
            <th>User</th>
            <th>Rating</th>
            <th>Comments</th>
            <th>Timestamp</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['feedback_id']); ?></td>
                <td><?php echo htmlspecialchars($row['issue_id']); ?></td>
                <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                <td><?php echo htmlspecialchars($row['rating']); ?></td>
                <td><?php echo htmlspecialchars($row['comments']); ?></td>
                <td><?php echo htmlspecialchars($row['timestamp']); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
    <a href="admin_dashboard.php">← Back to Dashboard</a>
</div>
</body>
</html>

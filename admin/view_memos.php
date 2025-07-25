<?php
session_start();
require '../db.php'; // adjust path if different

if (!isset($_SESSION['staff_id'])) {
    header("Location: admin_login.php");
    exit();
}

$sql = "SELECT office_name, subject, message, requested_by, created_at FROM memos ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Memos</title>
    <link rel="stylesheet" href="../css/responsive.css">

    <style>
        body { font-family: Arial; background: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 900px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; }
        h2 { color: #2c3e50; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background: #3498db; color: white; }
        tr:nth-child(even) { background: #f9f9f9; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Office Memos</h2>
        <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Office Name</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Requested By</th>
                <th>Date</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['office_name']) ?></td>
                <td><?= htmlspecialchars($row['subject']) ?></td>
                <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
                <td><?= htmlspecialchars($row['requested_by']) ?></td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
        <?php else: ?>
            <p>No memos available.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
require '../db.php';

// Fetch all inventory items
$result = $conn->query("SELECT * FROM inventory ORDER BY added_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Inventory List</title>
</head>
<body>
    <h2>Inventory Items</h2>
    <a href="add_item.php">➕ Add New Item</a>
    <br><br>
    <?php if ($result->num_rows > 0): ?>
        <table border="1" cellpadding="5">
            <tr>
                <th>Item Name</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Location</th>
                <th>Status</th>
                <th>Added At</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['item_name']) ?></td>
                <td><?= htmlspecialchars($row['category']) ?></td>
                <td><?= htmlspecialchars($row['quantity']) ?></td>
                <td><?= htmlspecialchars($row['location']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td><?= htmlspecialchars($row['added_at']) ?></td>
                <td>
                    <a href="edit_item.php?id=<?= $row['item_id'] ?>">Edit</a> | 
                    <a href="delete_item.php?id=<?= $row['item_id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No items in inventory.</p>
    <?php endif; ?>
</body>
</html>

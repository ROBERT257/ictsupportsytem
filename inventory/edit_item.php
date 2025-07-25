<?php
require '../db.php';

if (!isset($_GET['id'])) {
    die("Invalid item.");
}
$id = intval($_GET['id']);

// Get item details
$stmt = $conn->prepare("SELECT * FROM inventory WHERE item_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$item = $result->fetch_assoc();

if (!$item) {
    die("Item not found.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item_name = $_POST['item_name'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];
    $location = $_POST['location'];
    $status = $_POST['status'];

    $update = $conn->prepare("UPDATE inventory SET item_name=?, category=?, quantity=?, location=?, status=? WHERE item_id=?");
    $update->bind_param("ssissi", $item_name, $category, $quantity, $location, $status, $id);
    if ($update->execute()) {
        header("Location: view_items.php");
        exit();
    } else {
        echo "Error updating item.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Inventory Item</title>
</head>
<body>
    <h2>Edit Item</h2>
    <form method="POST">
        <label>Item Name:</label><br>
        <input type="text" name="item_name" value="<?= htmlspecialchars($item['item_name']) ?>" required><br><br>

        <label>Category:</label><br>
        <input type="text" name="category" value="<?= htmlspecialchars($item['category']) ?>"><br><br>

        <label>Quantity:</label><br>
        <input type="number" name="quantity" value="<?= htmlspecialchars($item['quantity']) ?>" required><br><br>

        <label>Location:</label><br>
        <input type="text" name="location" value="<?= htmlspecialchars($item['location']) ?>"><br><br>

        <label>Status:</label><br>
        <select name="status">
            <option value="Available" <?= $item['status']=="Available"?"selected":"" ?>>Available</option>
            <option value="In Use" <?= $item['status']=="In Use"?"selected":"" ?>>In Use</option>
            <option value="Under Maintenance" <?= $item['status']=="Under Maintenance"?"selected":"" ?>>Under Maintenance</option>
        </select><br><br>

        <input type="submit" value="Update Item">
    </form>
    <br>
    <a href="view_items.php">Back to Inventory</a>
</body>
</html>

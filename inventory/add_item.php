<?php
require '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item_name = $_POST['item_name'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];
    $location = $_POST['location'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO inventory (item_name, category, quantity, location, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiss", $item_name, $category, $quantity, $location, $status);

    if ($stmt->execute()) {
        echo "Item added successfully. <a href='view_items.php'>View Inventory</a>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Inventory Item</title>
</head>
<body>
    <h2>Add New Inventory Item</h2>
    <form method="POST">
        <label>Item Name:</label><br>
        <input type="text" name="item_name" required><br><br>

        <label>Category:</label><br>
        <input type="text" name="category"><br><br>

        <label>Quantity:</label><br>
        <input type="number" name="quantity" required><br><br>

        <label>Location:</label><br>
        <input type="text" name="location"><br><br>

        <label>Status:</label><br>
        <select name="status">
            <option value="Available">Available</option>
            <option value="In Use">In Use</option>
            <option value="Under Maintenance">Under Maintenance</option>
        </select><br><br>

        <input type="submit" value="Add Item">
    </form>
</body>
</html>

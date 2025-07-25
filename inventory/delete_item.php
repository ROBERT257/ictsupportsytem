<?php
require '../db.php';

if (!isset($_GET['id'])) {
    die("Invalid item.");
}
$id = intval($_GET['id']);

// Delete item
$stmt = $conn->prepare("DELETE FROM inventory WHERE item_id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: view_items.php");
    exit();
} else {
    echo "Error deleting item.";
}
?>

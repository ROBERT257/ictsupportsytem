<?php
require_once 'db.php';

header("Content-Type: application/json");
header("X-Content-Type-Options: nosniff");

session_start();

if (!isset($_SESSION['user'])) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

try {
    $conn = getDBConnection();
    $user_id = $_SESSION['user']['id'];
    $is_admin = ($_SESSION['user']['role'] === 'admin');
    
    // Check if we're just getting stats
    if (isset($_GET['stats'])) {
        if ($is_admin) {
            $query = "SELECT 
                SUM(CASE WHEN status = 'open' THEN 1 ELSE 0 END) as open,
                SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress,
                SUM(CASE WHEN status = 'resolved' THEN 1 ELSE 0 END) as resolved
                FROM issues";
        } else {
            $query = "SELECT 
                SUM(CASE WHEN status = 'open' THEN 1 ELSE 0 END) as open,
                SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress,
                SUM(CASE WHEN status = 'resolved' THEN 1 ELSE 0 END) as resolved
                FROM issues WHERE user_id = ?";
        }
        
        $stmt = $conn->prepare($query);
        if (!$is_admin) $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        echo json_encode([
            "status" => "success",
            "open" => $result['open'] ?? 0,
            "in_progress" => $result['in_progress'] ?? 0,
            "resolved" => $result['resolved'] ?? 0
        ]);
        exit;
    }
    
    // Get full issues list
    if ($is_admin) {
        $query = "SELECT i.*, u.fullname FROM issues i JOIN users u ON i.user_id = u.user_id ORDER BY created_at DESC";
        $stmt = $conn->prepare($query);
    } else {
        $query = "SELECT * FROM issues WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $issues = $result->fetch_all(MYSQLI_ASSOC);
    
    echo json_encode([
        "status" => "success",
        "issues" => $issues
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Failed to fetch issues"]);
} finally {
    if (isset($stmt)) $stmt->close();
    if (isset($conn)) $conn->close();
}
?>
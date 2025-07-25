<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// --- Role check (default to user if not set) ---
$role = $_SESSION['role'] ?? 'user';
if ($role === 'admin') {
    header("Location: view_feedback.php"); // Admins just view feedback
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION['user_id'];

    // General feedback (no issue_id)
    if (!empty($_POST['general'])) {
        $comments = trim($_POST['feedback']);
        $stmt = $conn->prepare("INSERT INTO feedback (issue_id, user_id, rating, comments) VALUES (NULL, ?, 5, ?)");
        $stmt->bind_param("is", $user_id, $comments);
        $message = $stmt->execute() ? "✅ General feedback submitted." : "❌ Error: " . $stmt->error;
        $stmt->close();
    } else {
        // Issue-specific feedback
        $issue_id = intval($_POST['issue_id']);
        $rating = intval($_POST['rating']);
        $comments = trim($_POST['comments']);
        $stmt = $conn->prepare("INSERT INTO feedback (issue_id, user_id, rating, comments) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $issue_id, $user_id, $rating, $comments);
        $message = $stmt->execute() ? "✅ Feedback submitted successfully." : "❌ Error: " . $stmt->error;
        $stmt->close();
    }
}

$prefill_issue_id = isset($_GET['issue_id']) ? intval($_GET['issue_id']) : "";
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/responsive.css">

    <title>Submit Feedback</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background: #f4f4f4; }
        .container { background: #fff; padding: 20px; border-radius: 10px; max-width: 500px; margin: auto; box-shadow: 0 2px 8px rgba(0,0,0,0.2); }
        h2 { color: #2c3e50; text-align: center; }
        label { font-weight: bold; }
        textarea, input, select { width: 100%; padding: 8px; margin-top: 5px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px; }
        button { background: #38b6ff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; }
        button:hover { background: #2980b9; }
        .stars { display: flex; flex-direction: row-reverse; justify-content: center; margin-bottom: 15px; }
        .stars input { display: none; }
        .stars label { font-size: 30px; color: #ccc; cursor: pointer; padding: 5px; }
        .stars input:checked ~ label { color: gold; }
        .stars label:hover,
        .stars label:hover ~ label { color: gold; }
        .message { text-align: center; font-weight: bold; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Submit Feedback</h2>
        <?php if (!empty($message)) echo "<p class='message'>$message</p>"; ?>

        <?php if ($prefill_issue_id): ?>
        <!-- Issue-specific feedback -->
        <form method="POST" action="give_feedback.php">
            <label for="issue_id">Issue ID:</label>
            <input type="number" name="issue_id" value="<?php echo htmlspecialchars($prefill_issue_id); ?>" readonly>
            <label for="comments">Your Comments:</label>
            <textarea name="comments" rows="4" placeholder="Write your feedback..." required></textarea>
            <label>Rate Our Support:</label>
            <div class="stars">
                <input type="radio" name="rating" value="5" id="star5" required><label for="star5">★</label>
                <input type="radio" name="rating" value="4" id="star4"><label for="star4">★</label>
                <input type="radio" name="rating" value="3" id="star3"><label for="star3">★</label>
                <input type="radio" name="rating" value="2" id="star2"><label for="star2">★</label>
                <input type="radio" name="rating" value="1" id="star1"><label for="star1">★</label>
            </div>
            <button type="submit">Submit Feedback</button>
        </form>
        <?php else: ?>
        <!-- General feedback -->
        <form method="POST" action="give_feedback.php">
            <input type="hidden" name="general" value="1">
            <label>Your Feedback:</label>
            <textarea name="feedback" rows="4" placeholder="Write your general feedback..." required></textarea>
            <button type="submit">Submit Feedback</button>
        </form>
        <?php endif; ?>
        <p style="text-align:center;margin-top:20px;"><a href="dashboard.php">← Back to Dashboard</a></p>
    </div>
</body>
</html>

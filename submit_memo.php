<?php
require 'db.php'; // adjust path if different

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $office_name = trim($_POST['office_name']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    $requested_by = trim($_POST['requested_by']);

    if (!empty($office_name) && !empty($subject) && !empty($message) && !empty($requested_by)) {
        $stmt = $conn->prepare("INSERT INTO memos (office_name, subject, message, requested_by) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $office_name, $subject, $message, $requested_by);

        if ($stmt->execute()) {
            echo "<script>alert('Memo submitted successfully'); window.location.href='submit_memo.php';</script>";
        } else {
            echo "<script>alert('Error: Could not submit memo');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('All fields are required');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Submit Memo</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin:0; padding:0; }
        .container { max-width: 600px; margin: 50px auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 8px rgba(0,0,0,0.2); }
        h2 { color: #2c3e50; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input[type="text"], textarea { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px; }
        textarea { resize: vertical; height: 100px; }
        input[type="submit"] { margin-top: 15px; background: #3498db; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 5px; }
        input[type="submit"]:hover { background: #2980b9; }
        .back-btn { display: inline-block; margin-top: 10px; text-decoration: none; color: #3498db; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Submit Memo</h2>
        <form method="POST" action="">
            <label for="office_name">Office Name</label>
            <input type="text" name="office_name" required>

            <label for="subject">Subject</label>
            <input type="text" name="subject" required>

            <label for="message">Message</label>
            <textarea name="message" required></textarea>

            <label for="requested_by">Requested By</label>
            <input type="text" name="requested_by" required>

            <input type="submit" value="Submit Memo">
        </form>
        <a href="index.php" class="back-btn">← Back</a>
    </div>
</body>
</html>

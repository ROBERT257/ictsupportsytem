<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get logged-in user details
$user_id = $_SESSION['user_id'];
$query = $conn->prepare("SELECT fullname FROM users WHERE user_id = ?");
$query->bind_param("s", $user_id);
$query->execute();
$query->bind_result($fullname);
$query->fetch();
$query->close();

// Handle general feedback submission
$feedback_message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['general_feedback'])) {
    $comment = trim($_POST['feedback']);
    $stmt = $conn->prepare("INSERT INTO feedback (issue_id, user_id, rating, comments) VALUES (NULL, ?, 5, ?)");
    $stmt->bind_param("is", $user_id, $comment);
    if ($stmt->execute()) {
        $feedback_message = "✅ Thank you for your feedback!";
    } else {
        $feedback_message = "❌ Error submitting feedback: ".$stmt->error;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>User Dashboard - ICT Support</title>
  <link rel="stylesheet" href="css/style.css"/>
  <style>
    body { font-family: Arial, sans-serif; margin: 0; background: #f4f6f8; }
    header { display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; background: #38b6ff; color: #fff; }
    header h2 { margin: 0; }
    nav a, nav form input[type=submit] { margin-left: 15px; text-decoration: none; color: #fff; font-weight: bold; background: transparent; border: none; cursor: pointer; font-size: 14px; }
    nav form { display: inline; }

    main { padding: 30px; }
    h3 { color: #2c3e50; }
    section { margin-bottom: 40px; }

    .dashboard-cards { display: flex; flex-wrap: wrap; gap: 20px; }
    .card {
      flex: 1 1 calc(50% - 20px);
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      transition: transform 0.2s ease-in-out;
    }
    .card:hover { transform: translateY(-5px); }
    .card h3 { margin-top: 0; }
    .card button {
      padding: 12px 20px;
      background: #38b6ff;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }
    .card button:hover { background: #2980b9; }

    table { border-collapse: collapse; width: 100%; margin-top: 20px; background: #fff; border-radius: 5px; overflow: hidden; }
    th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ccc; }
    th { background: #f4f4f4; }
    tr:hover { background: #f9f9f9; }

    textarea, select, input[type=text] { width: 100%; padding: 8px; margin: 5px 0; border: 1px solid #ccc; border-radius: 5px; }
    input[type=submit] {
      background: #38b6ff; color: #fff; padding: 10px 20px; border: none; cursor: pointer; border-radius: 5px;
    }
    input[type=submit]:hover { background: #2980b9; }

    .feedback-btn {
      background:#f39c12;
      color:white;
      padding:5px 10px;
      border-radius:5px;
      text-decoration:none;
      font-size:13px;
    }
    .feedback-btn:hover { background:#e67e22; }
    .message { font-weight: bold; margin-bottom: 10px; color: green; }
  </style>
</head>
<body>
  <div style="text-align: center; font-size: 15px; font-weight: bold; background-color: #38b6ff; color:white; padding:5px;">
    <h1>ICT E-SUPPORT SYSTEM</h1>
    <a href="" class="site-branding-logo">
      <img class="logo-site" src="images/sdps.png" alt="Home" />
    </a>
  </div>

  <header>
    <h2>Welcome, <?php echo htmlspecialchars($fullname); ?>!</h2>
    <nav>
      <a href="profile.php">Profile</a>
      <form action="logout.php" method="POST" style="display:inline;">
        <input type="submit" value="Log Out">
      </form>
    </nav>
  </header>

  <main>
    <!-- Dashboard Cards -->
    <div class="dashboard-cards">
      <div class="card">
        <h3>📝 Submit Office Memo</h3>
        <p>If your office needs equipment, stationery, or any request, submit a memo directly.</p>
        <button onclick="window.location.href='submit_memo.php'">➕ Submit Memo</button>
      </div>

      <div class="card">
        <h3>🛠️ Submit New ICT Support Issue</h3>
        <form action="submit_issue.php" method="POST">
          <label for="issue_type">Select Issue</label>
          <select name="issue_type" id="issue_type" required>
            <option value="">-- Select --</option>
            <option value="Network/Internet">Network / Internet</option>
            <option value="Software Installation">Software Installation</option>
            <option value="Hardware">Hardware</option>
            <option value="Other">Other</option>
          </select>
          <label for="sub_category">Sub Category</label>
          <select name="sub_category" id="sub_category" required>
            <option value="">-- Select Sub Category --</option>
            <option value="Wi-Fi Not Working">Wi-Fi Not Working</option>
            <option value="Slow Internet">Slow Internet</option>
            <option value="VPN Issues">VPN Issues</option>
            <option value="IFMIS / Hyperion">IFMIS / Hyperion</option>
            <option value="IB">IB</option>
            <option value="Windows">Windows</option>
            <option value="Office">Office</option>
            <option value="Activation">Activation</option>
            <option value="Printer">Printer</option>
            <option value="Computer (Monitor/CPU)">Computer (Monitor/CPU)</option>
            <option value="Other">Other</option>
          </select>
          <label for="description">Other (Describe issue)</label>
          <textarea name="description" rows="4" required></textarea>
          <input type="submit" value="Submit Issue">
        </form>
        <br>
        <button type="button" onclick="window.location.href='quick-fixes.html'">
          Common Support Issues and Fixes
        </button>
      </div>
    </div>

    <!-- Submitted Issues -->
    <section class="issues-section">
      <h3>📄 Your Submitted Issues</h3>
      <?php
      $stmt = $conn->prepare("SELECT id, issue_type, sub_category, description, status, assigned_to, submitted_at 
                              FROM support_requests 
                              WHERE user_id = ? ORDER BY submitted_at DESC");
      $stmt->bind_param("s", $user_id);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0):
      ?>
        <table>
          <tr>
            <th>Issue Type</th>
            <th>Sub-Category</th>
            <th>Description</th>
            <th>Status</th>
            <th>Assigned To</th>
            <th>Submitted At</th>
            <th>Feedback</th>
          </tr>
          <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['issue_type']); ?></td>
            <td><?php echo htmlspecialchars($row['sub_category']); ?></td>
            <td><?php echo htmlspecialchars($row['description']); ?></td>
            <td><?php echo htmlspecialchars($row['status']); ?></td>
            <td><?php echo htmlspecialchars($row['assigned_to'] ?? 'Pending'); ?></td>
            <td><?php echo htmlspecialchars($row['submitted_at']); ?></td>
            <td><a href="give_feedback.php?issue_id=<?php echo $row['id']; ?>" class="feedback-btn">⭐ Give Feedback</a></td>
          </tr>
          <?php endwhile; ?>
        </table>
      <?php else: ?>
        <p>No issues submitted yet.</p>
      <?php endif; $stmt->close(); ?>
    </section>

    <!-- General Feedback Section -->
    <section class="feedback-section">
      <h3>💬 General Feedback</h3>
      <?php if (!empty($feedback_message)) echo "<p class='message'>$feedback_message</p>"; ?>
      <form method="POST" action="">
        <textarea name="feedback" rows="3" placeholder="Your feedback..." required></textarea>
        <input type="hidden" name="general_feedback" value="1">
        <input type="submit" value="Send Feedback">
      </form>
    </section>
  </main>
</body>
</html>

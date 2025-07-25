<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$stmt = $conn->prepare("SELECT fullname, building, office, department, phone_number FROM users WHERE user_id = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$stmt->bind_result($fullname, $building, $office, $department, $phone);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Profile</title>
  <link rel="stylesheet" href="home.css">
  <style>
    .hidden { display: none; }
    input[readonly] { background: #f4f4f4; }
  </style>
</head>
<body>
  <div class="container">
    <header>
      <h2>User Profile</h2>
      <nav>
        <a href="dashboard.php">Home</a>
        <a href="logout.php">Log Out</a>
      </nav>
    </header>

    <main>
      <section class="profile-section">
        <h3>Profile Details</h3>
        <form id="profileForm" method="POST" action="update_profile.php">
          <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">

          <label for="name">Name:</label>
          <input type="text" id="name" name="fullname" value="<?php echo htmlspecialchars($fullname); ?>" readonly>

          <label for="building">Building:</label>
          <input type="text" id="building" name="building" value="<?php echo htmlspecialchars($building); ?>" readonly>

          <label for="office">Office:</label>
          <input type="text" id="office" name="office" value="<?php echo htmlspecialchars($office); ?>" readonly>

          <label for="department">Department:</label>
          <input type="text" id="department" name="department" value="<?php echo htmlspecialchars($department); ?>" readonly>

          <label for="phone">Phone Number:</label>
          <input type="tel" id="phone" name="phone_number" value="<?php echo htmlspecialchars($phone); ?>" readonly>

          <button type="button" onclick="enableEdit()">Edit Profile</button>
          <button type="submit" id="saveBtn" class="hidden">Save Changes</button>
        </form>
      </section>
    </main>
  </div>

  <script>
    function enableEdit() {
      const inputs = document.querySelectorAll('#profileForm input:not([type=hidden])');
      inputs.forEach(input => input.removeAttribute('readonly'));
      document.getElementById('saveBtn').classList.remove('hidden');
    }
  </script>
</body>
</html>

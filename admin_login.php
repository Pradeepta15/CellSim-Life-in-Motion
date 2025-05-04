<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $conn = new mysqli("localhost", "root", "", "game_of_life");
  $username = $_POST['username'];
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT password FROM admin_users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($row = $result->fetch_assoc()) {
    if ($password === $row['password']) {  // Plain-text match for testing
      $_SESSION['admin'] = true;
      header("Location: admin.php");
      exit();
    }
  }

  $error = "Invalid admin login.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
  <link rel="stylesheet" href="admin_login.css">
</head>
<body>
  <div class="admin-login-container">
    <form method="POST" class="admin-login-form">
      <h2>Admin Login</h2>
      <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

      <label for="username">Username:</label>
      <input type="text" name="username" id="username" required>

      <label for="password">Password:</label>
      <input type="password" name="password" id="password" required>

      <button type="submit">Login</button>
    </form>
  </div>
</body>
</html>

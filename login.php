<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $conn = new mysqli("localhost", "root", "", "game_of_life");
  $username = $_POST['username'];
  $password = $_POST['password'];
  $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($row = $result->fetch_assoc()) {
    if (password_verify($password, $row['password'])) {
      $_SESSION['user_id'] = $row['id'];
      header("Location: dashboard.php");
      exit();
    }
  }
  $error = "Invalid login.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="login.css">
</head>
<body>
  <div class="login-container">
    <form method="POST" class="login-form">
      <h2>Login</h2>
      <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
      
      <label for="username">Username:</label>
      <input type="text" name="username" id="username" required>
      
      <label for="password">Password:</label>
      <input type="password" name="password" id="password" required>
      
      <button type="submit">Login</button>
      
      <p class="register-link">Don't have an account? <a href="register.php">Register here</a>.</p>
    </form>
  </div>
</body>
</html>

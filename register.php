<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $conn = new mysqli("localhost", "root", "", "game_of_life");

  $username = $_POST['username'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $email = $_POST['email'];

  $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $username, $password, $email);

  if ($stmt->execute()) {
    header("Location: login.php");
    exit();
  } else {
    $error = "Username already taken or database error.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <link rel="stylesheet" href="register.css">
</head>
<body>
  <div class="register-container">
    <form method="POST" class="register-form">
      <h2>Register</h2>
      
      <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

      <label for="username">Username:</label>
      <input type="text" name="username" id="username" required>

      <label for="email">Email:</label>
      <input type="email" name="email" id="email" required>

      <label for="password">Password:</label>
      <input type="password" name="password" id="password" required>

      <button type="submit">Register</button>

      <p class="login-link">Already have an account? <a href="login.php">Login here</a>.</p>
    </form>
  </div>
</body>
</html>

<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$conn = new mysqli("localhost", "root", "", "game_of_life");
$userId = $_SESSION['user_id'];

$result = $conn->query("SELECT username FROM users WHERE id = $userId");
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Dashboard</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?> 👋</h1>

  <p><a href="game.html">▶️ Play Conway's Game of Life</a></p>
  <p><a href="logout.php">Logout</a></p>
</body>
</html>

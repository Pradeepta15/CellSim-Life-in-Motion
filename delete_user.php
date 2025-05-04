<?php
$conn = new mysqli("localhost", "root", "", "game_of_life");
if (isset($_GET['id'])) {
  $id = intval($_GET['id']);
  $conn->query("DELETE FROM users WHERE id = $id");
}
header("Location: admin.php");
exit();

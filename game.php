<?php
include 'db.php';
session_start();
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $stmt = $conn->prepare("INSERT INTO game_sessions (user_id, start_time, generations) VALUES (?, NOW(), 0)");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
}
?>

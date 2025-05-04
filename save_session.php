<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  http_response_code(401);
  exit('Not logged in');
}

$data = json_decode(file_get_contents("php://input"), true);
$generations = intval($data['generations']);
$duration = intval($data['duration']);

$conn = new mysqli("localhost", "root", "", "game_of_life");
$stmt = $conn->prepare("INSERT INTO game_sessions (user_id, generations, duration_seconds) VALUES (?, ?, ?)");
$stmt->bind_param("iii", $_SESSION['user_id'], $generations, $duration);
$stmt->execute();

http_response_code(200);
echo "Session saved";

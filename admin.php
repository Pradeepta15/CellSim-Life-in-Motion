<?php
session_start();
$conn = new mysqli("localhost", "root", "", "game_of_life");

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
  die("Access denied.");
}

// Handle update/delete user actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['delete_user'])) {
    $uid = intval($_POST['user_id']);
    $conn->query("DELETE FROM users WHERE id = $uid");
    $conn->query("DELETE FROM game_sessions WHERE user_id = $uid");
  }

  if (isset($_POST['update_user'])) {
    $uid = intval($_POST['user_id']);
    $new_email = $_POST['email'];
    $stmt = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
    $stmt->bind_param("si", $new_email, $uid);
    $stmt->execute();
  }
}

// Filter sessions if requested
$filter_user = isset($_GET['filter_user']) ? intval($_GET['filter_user']) : null;

$users = $conn->query("SELECT id, username, email FROM users");

$sessions_query = "SELECT * FROM game_sessions";
if ($filter_user !== null) {
  $sessions_query .= " WHERE user_id = $filter_user";
}
$sessions = $conn->query($sessions_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="admin.css">
</head>
<body>
  <div class="admin-container">
    <div class="admin-header">
      <h1>Admin Panel</h1>
      <form method="POST" action="logout.php">
        <button type="submit" class="logout-btn">Logout</button>
      </form>
    </div>

    <section>
      <h2>👤 Registered Users</h2>
      <table>
        <thead>
          <tr><th>ID</th><th>Username</th><th>Email</th><th>Actions</th></tr>
        </thead>
        <tbody>
          <?php
          $users_result = $conn->query("SELECT id, username, email FROM users");
          while ($u = $users_result->fetch_assoc()): ?>
            <tr>
              <form method="POST">
                <td><?= $u['id'] ?></td>
                <td><?= htmlspecialchars($u['username']) ?></td>
                <td>
                  <input type="email" name="email" value="<?= htmlspecialchars($u['email']) ?>" required>
                  <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                </td>
                <td>
                  <button type="submit" name="update_user">Update</button>
                  <button type="submit" name="delete_user" onclick="return confirm('Are you sure?')">Delete</button>
                </td>
              </form>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </section>

    <section>
      <h2>🎮 Game Sessions</h2>
      <form method="GET" style="margin-bottom: 20px;">
        <label>Filter by User ID:</label>
        <input type="number" name="filter_user" min="1" value="<?= $filter_user ?? '' ?>">
        <button type="submit">Filter</button>
        <a href="admin.php"><button type="button">Clear Filter</button></a>
      </form>
      <table>
        <thead>
          <tr><th>ID</th><th>User ID</th><th>Start Time</th><th>Generations</th><th>Duration (s)</th></tr>
        </thead>
        <tbody>
          <?php while($s = $sessions->fetch_assoc()): ?>
            <tr>
              <td><?= $s['id'] ?></td>
              <td><?= $s['user_id'] ?></td>
              <td><?= $s['start_time'] ?></td>
              <td><?= $s['generations'] ?></td>
              <td><?= $s['duration_seconds'] ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </section>
  </div>
</body>
</html>

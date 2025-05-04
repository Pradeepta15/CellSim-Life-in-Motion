<?php
session_start();
session_unset();
session_destroy();

// Display a message and redirect
echo "<script>
  alert('You have successfully logged out.');
  window.location.href = 'login.php';
</script>";
exit;

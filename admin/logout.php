<?php
session_start();
session_unset();
session_destroy();

// Redirect to admin login page
header("location: login.php");
echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
exit;
?>
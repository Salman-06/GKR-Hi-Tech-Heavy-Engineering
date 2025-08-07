<?php
session_start();
require_once '../config.php';

// Get input data
$data = json_decode(file_get_contents('php://input'), true);
$username = sanitizeInput($data['username']);
$password = sanitizeInput($data['password']);

// Validate credentials (in a real app, use password_hash() and password_verify())
$stmt = $conn->prepare("SELECT * FROM admin_users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    
    // Verify password (in a real app, use password_verify())
    if ($password === $user['password']) { // Replace with password_verify() in production
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $user['username'];
        $_SESSION['admin_id'] = $user['id'];
        
        echo json_encode(['success' => true, 'message' => 'Login successful']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
}

$stmt->close();
$conn->close();
?>
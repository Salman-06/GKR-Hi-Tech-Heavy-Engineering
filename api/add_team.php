<?php
require_once '../config.php';

// Handle file upload
$uploadDir = '../img/team/';
$photoPath = '';

if (isset($_FILES['photo'])) {
    $fileName = uniqid() . '_' . basename($_FILES['photo']['name']);
    $targetPath = $uploadDir . $fileName;
    
    if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {
        $photoPath = 'img/team/' . $fileName;
    }
}

$name = sanitizeInput($_POST['name']);
$position = sanitizeInput($_POST['position']);

// Get current max display order
$maxOrderQuery = $conn->query("SELECT MAX(display_order) as max_order FROM team_members");
$maxOrder = $maxOrderQuery->fetch_assoc()['max_order'];
$newOrder = $maxOrder ? $maxOrder + 1 : 1;

$stmt = $conn->prepare("INSERT INTO team_members (photo_path, name, position, display_order) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssi", $photoPath, $name, $position, $newOrder);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Team member added successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to add team member']);
}

$stmt->close();
$conn->close();
?>
<?php
require_once '../config.php';

$id = intval($_POST['id']);
$name = sanitizeInput($_POST['name']);
$position = sanitizeInput($_POST['position']);

// Get existing photo path
$stmt = $conn->prepare("SELECT photo_path FROM team_members WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$existingMember = $result->fetch_assoc();
$photoPath = $existingMember['photo_path'];

// Handle file upload if new photo is provided
if (isset($_FILES['photo']) && $_FILES['photo']['size'] > 0) {
    $uploadDir = '../img/team/';
    $fileName = uniqid() . '_' . basename($_FILES['photo']['name']);
    $targetPath = $uploadDir . $fileName;
    
    if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {
        // Delete old photo if it exists
        if ($photoPath && file_exists('../' . $photoPath)) {
            unlink('../' . $photoPath);
        }
        $photoPath = 'img/team/' . $fileName;
    }
}

$stmt = $conn->prepare("UPDATE team_members SET photo_path = ?, name = ?, position = ? WHERE id = ?");
$stmt->bind_param("sssi", $photoPath, $name, $position, $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Team member updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update team member']);
}

$stmt->close();
$conn->close();
?>
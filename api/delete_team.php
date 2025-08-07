<?php
require_once '../config.php';

$id = intval($_GET['id']);

// Get photo path before deleting
$stmt = $conn->prepare("SELECT photo_path FROM team_members WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$member = $result->fetch_assoc();

// Delete the record
$stmt = $conn->prepare("DELETE FROM team_members WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    // Delete the photo file if it exists
    if ($member['photo_path'] && file_exists('../' . $member['photo_path'])) {
        unlink('../' . $member['photo_path']);
    }
    echo json_encode(['success' => true, 'message' => 'Team member deleted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete team member']);
}

$stmt->close();
$conn->close();
?>
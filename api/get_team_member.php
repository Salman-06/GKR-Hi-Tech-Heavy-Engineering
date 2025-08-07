<?php
require_once '../config.php';

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM team_members WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $member = $result->fetch_assoc();
    echo json_encode(['success' => true, 'member' => $member]);
} else {
    echo json_encode(['success' => false, 'message' => 'Team member not found']);
}

$stmt->close();
$conn->close();
?>
<?php
require_once '../config.php';

header('Content-Type: application/json');

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM carousel WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $item = $result->fetch_assoc();
    echo json_encode(['success' => true, 'item' => $item]);
} else {
    echo json_encode(['success' => false, 'message' => 'Carousel item not found']);
}

$stmt->close();
$conn->close();
?>
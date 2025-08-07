<?php
require_once '../config.php';

$id = intval($_GET['id']);

// Get image path before deleting
$stmt = $conn->prepare("SELECT image_path FROM carousel WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$item = $result->fetch_assoc();

// Delete the record
$stmt = $conn->prepare("DELETE FROM carousel WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    // Delete the image file if it exists
    if ($item['image_path'] && file_exists('../' . $item['image_path'])) {
        unlink('../' . $item['image_path']);
    }
    echo json_encode(['success' => true, 'message' => 'Carousel item deleted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete carousel item']);
}

$stmt->close();
$conn->close();
?>
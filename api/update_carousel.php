<?php
require_once '../config.php';

header('Content-Type: application/json');

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// Sanitize and retrieve POST data
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$title = sanitizeInput($_POST['title']);
$subtitle = sanitizeInput($_POST['subtitle']);
$button_text = sanitizeInput($_POST['button_text']);
$button_path = sanitizeInput($_POST['button_path'] ?? '#');
$display_order = isset($_POST['display_order']) ? intval($_POST['display_order']) : 0;

if ($id === 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid carousel item ID.']);
    exit;
}

$imagePath = '';
$params = [];
$types = '';

// Handle file upload
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../img/carousel/';
    $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
    $targetPath = $uploadDir . $fileName;
    
    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
        $imagePath = 'img/carousel/' . $fileName;
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to upload image.']);
        exit;
    }
}

// Build the query dynamically
$sql = "UPDATE carousel SET title = ?, subtitle = ?, button_text = ?, button_path = ?, display_order = ?";
$types .= 'ssssi';
array_push($params, $title, $subtitle, $button_text, $button_path, $display_order);

if ($imagePath !== '') {
    $sql .= ", image_path = ?";
    $types .= 's';
    array_push($params, $imagePath);
}

$sql .= " WHERE id = ?";
$types .= 'i';
array_push($params, $id);

$stmt = $conn->prepare($sql);

$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Carousel item updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Update failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
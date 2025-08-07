<?php
require_once '../config.php';

header('Content-Type: application/json');

// Handle file upload
$uploadDir = '../img/carousel/';
$imagePath = '';

if (isset($_FILES['image'])) {
    $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
    $targetPath = $uploadDir . $fileName;
    
    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
        $imagePath = 'img/carousel/' . $fileName;
    }
}

$title = sanitizeInput($_POST['title']);
$subtitle = sanitizeInput($_POST['subtitle']);
$button_text = sanitizeInput($_POST['button_text']);
$button_path = sanitizeInput($_POST['button_path'] ?? '#');
$display_order = isset($_POST['display_order']) ? intval($_POST['display_order']) : 0;

$stmt = $conn->prepare("INSERT INTO carousel (image_path, title, subtitle, button_text, button_path, display_order) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssi", $imagePath, $title, $subtitle, $button_text, $button_path, $display_order);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Carousel item added successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to add carousel item: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
<?php
// Absolute first line - no whitespace before!
header('Content-Type: application/json');

require_once __DIR__ . '/../config.php';

// Error handling
try {
    $query = "SELECT * FROM carousel ORDER BY display_order ASC";
    $result = $conn->query($query);
    
    if (!$result) {
        throw new Exception('Database error: ' . $conn->error);
    }

    $carouselItems = [];
    while ($row = $result->fetch_assoc()) {
        // Ensure image paths are correct
        $row['image_path'] = 'img/carousel/' . basename($row['image_path']);
        $carouselItems[] = $row;
    }

    echo json_encode([
        'success' => true,
        'carouselItems' => $carouselItems
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$conn->close();
?>
<?php
header('Content-Type: application/json');

require_once '../config.php';

try {
    $query = "SELECT * FROM team_members ORDER BY display_order ASC";
    $result = $conn->query($query);

    if (!$result) {
        throw new Exception('Database error: ' . $conn->error);
    }

    $teamMembers = [];
    while ($row = $result->fetch_assoc()) {
        // Ensure photo paths are correct relative to the frontend
        if ($row['photo_path']) {
            $row['photo_path'] = 'img/team/' . basename($row['photo_path']);
        }
        $teamMembers[] = $row;
    }

    echo json_encode([
        'success' => true,
        'teamMembers' => $teamMembers
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$conn->close();
?>
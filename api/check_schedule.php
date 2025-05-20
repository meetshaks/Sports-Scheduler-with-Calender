<?php
require_once 'config.php';

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $data['date'] ?? '';
    $time = $data['time'] ?? '';
    $players = $data['players'] ?? [];
    $excludeId = $data['excludeId'] ?? null;
    
    // Check for duplicate players in the current selection
    if (count($players) !== count(array_unique($players))) {
        echo json_encode(['valid' => false, 'error' => 'The same player cannot be selected multiple times']);
        exit();
    }
    
    // Check if any player is already scheduled at this time
    $placeholders = implode(',', array_fill(0, count($players), '?'));
    $sql = "SELECT DISTINCT player FROM (
        SELECT id, player1 as player FROM schedules WHERE date = ? AND time = ? 
        UNION SELECT id, player2 FROM schedules WHERE date = ? AND time = ? 
        UNION SELECT id, player3 FROM schedules WHERE date = ? AND time = ? 
        UNION SELECT id, player4 FROM schedules WHERE date = ? AND time = ?
    ) as players WHERE player IN ($placeholders)";
    
    if ($excludeId) {
        $sql .= " AND id != ?";
    }
    
    $stmt = $conn->prepare($sql);
    
    $params = array_merge(
        [$date, $time, $date, $time, $date, $time, $date, $time],
        $players
    );
    
    if ($excludeId) {
        $params[] = $excludeId;
    }
    
    $stmt->execute($params);
    $conflicts = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (!empty($conflicts)) {
        echo json_encode(['valid' => false, 'error' => 'The following players are already scheduled: ' . implode(', ', $conflicts)]);
    } else {
        echo json_encode(['valid' => true]);
    }
}
?>
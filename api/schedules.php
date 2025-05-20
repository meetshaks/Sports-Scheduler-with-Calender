<?php
require_once 'config.php';

// Set content type to JSON
header('Content-Type: application/json');

try {
    $method = $_SERVER['REQUEST_METHOD'];
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Check for _method override in POST requests
    if ($method === 'POST' && isset($data['_method'])) {
        $method = strtoupper($data['_method']);
    }
    
    switch ($method) {
        case 'GET':
            // Get all schedules
            $stmt = $conn->prepare("SELECT * FROM schedules ORDER BY date, time");
            $stmt->execute();
            $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Format response
            $response = array_map(function($schedule) {
                return [
                    'id' => $schedule['id'],
                    'date' => $schedule['date'],
                    'time' => $schedule['time'],
                    'groupName' => $schedule['group_name'],
                    'matchCount' => $schedule['match_count'],
                    'players' => [
                        $schedule['player1'],
                        $schedule['player2'],
                        $schedule['player3'],
                        $schedule['player4']
                    ],
                    'createdAt' => $schedule['created_at'],
                    'updatedAt' => $schedule['updated_at']
                ];
            }, $schedules);
            
            echo json_encode($response);
            break;
            
        case 'POST':
            // Add new schedule
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!$data) {
                throw new Exception('Invalid input data');
            }
            
            $stmt = $conn->prepare("INSERT INTO schedules 
                (date, time, group_name, match_count, player1, player2, player3, player4) 
                VALUES (:date, :time, :group_name, :match_count, :player1, :player2, :player3, :player4)");
            
            $stmt->bindParam(':date', $data['date']);
            $stmt->bindParam(':time', $data['time']);
            $stmt->bindParam(':group_name', $data['groupName']);
            $stmt->bindParam(':match_count', $data['matchCount']);
            $stmt->bindParam(':player1', $data['players'][0]);
            $stmt->bindParam(':player2', $data['players'][1]);
            $stmt->bindParam(':player3', $data['players'][2]);
            $stmt->bindParam(':player4', $data['players'][3]);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'id' => $conn->lastInsertId()]);
            } else {
                throw new Exception('Failed to add schedule');
            }
            break;
            
        case 'PUT':
            // Update schedule
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (empty($data['id'])) {
                throw new Exception('Missing schedule ID');
            }
            
            if (empty($data['matchCount']) || !in_array($data['matchCount'], ['2', '3', '4', '5'])) {
                throw new Exception('Invalid match count. Must be 2, 3, 4, or 5');
            }
            
            if (empty($data['players']) || count($data['players']) !== 4) {
                throw new Exception('Players must be an array of exactly 4 players');
            }
            
            $matchCount = (int)$data['matchCount']; // Ensure integer
            
            $stmt = $conn->prepare("UPDATE schedules SET 
                time = :time, 
                match_count = :match_count,
                player1 = :player1, 
                player2 = :player2, 
                player3 = :player3, 
                player4 = :player4,
                updated_at = CURRENT_TIMESTAMP 
                WHERE id = :id");
            
            $stmt->bindParam(':time', $data['time']);
            $stmt->bindParam(':match_count', $matchCount, PDO::PARAM_INT);
            $stmt->bindParam(':player1', $data['players'][0]);
            $stmt->bindParam(':player2', $data['players'][1]);
            $stmt->bindParam(':player3', $data['players'][2]);
            $stmt->bindParam(':player4', $data['players'][3]);
            $stmt->bindParam(':id', $data['id']);
            
            if ($stmt->execute()) {
                $rowsAffected = $stmt->rowCount();
                echo json_encode(['success' => true, 'rowsAffected' => $rowsAffected]);
            } else {
                throw new Exception('Failed to update schedule');
            }
            break;
            
        case 'DELETE':
            // Delete schedule
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (empty($data['id'])) {
                throw new Exception('Missing schedule ID');
            }
            
            $stmt = $conn->prepare("DELETE FROM schedules WHERE id = :id");
            $stmt->bindParam(':id', $data['id']);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                throw new Exception('Failed to delete schedule');
            }
            break;
            
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
    }
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
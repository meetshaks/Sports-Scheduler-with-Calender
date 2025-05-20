<?php
require_once 'config.php';

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        // In a real application, use password_verify() with hashed passwords
        if (
            ($username === 'shakil' && $password === '1234') ||
            ($username === '' && $password === '') 
        )  {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Invalid credentials']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid credentials']);
    }
}
?>
<?php
include '../db.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    if (!isset($_GET['cliente_id'])) {
        echo json_encode(['error' => 'Missing cliente_id']);
        exit;
    }
    $cliente_id = intval($_GET['cliente_id']);
    $stmt = $conn->prepare("SELECT * FROM clienti_note WHERE cliente_id = ? ORDER BY data_creazione DESC");
    if (!$stmt) {
        echo json_encode(['error' => 'Database prepare error: ' . $conn->error]);
        exit;
    }
    $stmt->bind_param("i", $cliente_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $notes = [];
    while ($row = $result->fetch_assoc()) {
        $notes[] = $row;
    }
    echo json_encode($notes);
} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['cliente_id']) || !isset($data['nota'])) {
        echo json_encode(['error' => 'Missing data']);
        exit;
    }
    $cliente_id = intval($data['cliente_id']);
    $nota = $data['nota'];
    
    $stmt = $conn->prepare("INSERT INTO clienti_note (cliente_id, nota) VALUES (?, ?)");
    if (!$stmt) {
        echo json_encode(['error' => 'Database prepare error: ' . $conn->error]);
        exit;
    }
    $stmt->bind_param("is", $cliente_id, $nota);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Database error']);
    }
}
?>

<?php
header("Content-Type: application/json");
include '../db.php';

// Basic Auth o Session Check (qui uso session per semplicità, ma per API vere meglio Token)
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    
    $sql = "
        SELECT p.id, c.nome, c.cognome, d.citta, d.paese, p.data_prenotazione, p.acconto 
        FROM prenotazioni p
        JOIN clienti c ON p.id_cliente = c.id
        JOIN destinazioni d ON p.id_destinazione = d.id
        ORDER BY p.data_prenotazione DESC
    ";

    $result = $conn->query($sql);
    $prenotazioni = [];

    while($row = $result->fetch_assoc()){
        $prenotazioni[] = $row;
    }

    echo json_encode($prenotazioni);

} else {
    http_response_code(405);
    echo json_encode(["error" => "Method Not Allowed"]);
}
?>

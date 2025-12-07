<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Accesso negato.");
}

if (!isset($_GET['id'])) {
    die("ID Prenotazione mancante.");
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("
    SELECT p.*, c.nome, c.cognome, c.codice_fiscale, c.email, c.telefono, 
           d.citta, d.paese, d.prezzo as prezzo_unitario
    FROM prenotazioni p
    JOIN clienti c ON p.id_cliente = c.id
    JOIN destinazioni d ON p.id_destinazione = d.id
    WHERE p.id = ?
");

$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();

if (!$res) {
    die("Prenotazione non trovata.");
}

$totale = $res['prezzo_unitario'] * $res['numero_persone'];
$saldo = $totale - $res['acconto'];

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Fattura #<?= $id ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #eee; padding: 20px; }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
            background: #fff;
        }
        @media print {
            body { background: #fff; }
            .no-print { display: none; }
            .invoice-box { box-shadow: none; border: none; }
        }
    </style>
</head>
<body>

<div class="invoice-box">
    <div class="row mb-4">
        <div class="col-6">
            <h2 class="text-primary">Happy Skies Travel</h2>
            <p>Via Roma 123, Milano<br>P.IVA: 12345678901</p>
        </div>
        <div class="col-6 text-end">
            <h4>Fattura n. <?= $res['id'] ?></h4>
            <p>Data: <?= date("d/m/Y", strtotime($res['data_prenotazione'])) ?></p>
        </div>
    </div>

    <hr>

    <div class="row mb-4">
        <div class="col-6">
            <h5>Intestata a:</h5>
            <p>
                <strong><?= htmlspecialchars($res['nome'] . ' ' . $res['cognome']) ?></strong><br>
                <?= htmlspecialchars($res['email']) ?><br>
                CF: <?= htmlspecialchars($res['codice_fiscale']) ?>
            </p>
        </div>
    </div>

    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Descrizione</th>
                <th>Prezzo Unitario</th>
                <th>Quantità</th>
                <th>Totale</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Viaggio a <?= htmlspecialchars($res['citta'] . ', ' . $res['paese']) ?></td>
                <td>€ <?= number_format($res['prezzo_unitario'], 2) ?></td>
                <td><?= $res['numero_persone'] ?></td>
                <td>€ <?= number_format($totale, 2) ?></td>
            </tr>
        </tbody>
    </table>

    <div class="row justify-content-end">
        <div class="col-4">
            <table class="table table-sm">
                <tr>
                    <td><strong>Totale:</strong></td>
                    <td class="text-end">€ <?= number_format($totale, 2) ?></td>
                </tr>
                <tr>
                    <td>Acconto Versato:</td>
                    <td class="text-end text-success">- € <?= number_format($res['acconto'], 2) ?></td>
                </tr>
                <tr>
                    <td><strong>Saldo da Pagare:</strong></td>
                    <td class="text-end"><strong>€ <?= number_format($saldo, 2) ?></strong></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="mt-5 text-center no-print">
        <button onclick="window.print()" class="btn btn-primary">Stampa Fattura</button>
        <a href="prenotazioni.php" class="btn btn-secondary">Torna indietro</a>
    </div>

</div>

</body>
</html>

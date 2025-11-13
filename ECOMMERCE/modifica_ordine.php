<?php

    require 'db.php';

    $id = $_GET['id'];
    // Query dove recupero i dati dell'ordine e anche l'id del contatto
    $result = mysqli_query($conn, "SELECT * FROM ordini WHERE id=$id");
    $row = mysqli_fetch_assoc($result);
    // verifico se l ordine esiste
    if (!$row){
        die("Ordine non trovato.");
    }

    // salvo l'id del contatto per il reindirizzamento
    $contatto_id = $row['contatto_id'];

    // dati dal form gestione dell'invio form
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        // recupero degli input del form di modifica 
        $prodotto = $_POST['prodotto'];
        $quantita = $_POST['quantita'];
        $data = $_POST['data'];
        // query di aggiornamento
        $sql = "UPDATE ordini SET prodotto = '$prodotto', quantita = '$quantita', data_di_ordine = '$data' WHERE id = $id";
        // eseguo la query
        mysqli_query($conn, $sql);
        // reindirizzo alla lista degli ordini del contatto dopo la modifica
        header("Location: ordini.php?id=$contatto_id");   
    }

    ?>

    <!DOCTYPE html>
    <html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Modifica ordine</title>
        <link rel="stylesheet" href="style.css?v<?= time() ?>">
    </head>
    <body>
        <div class="container">
            <h1>Modifica Ordine</h1>
            <form action="" method= "POST">
                Prodotto : <input name="prodotto" type="text" value="<?= htmlspecialchars($row['prodotto']) ?>" required>
                Quantità : <input name="quantità" type="number" value="<?= htmlspecialchars($row['quantita']) ?>" required>
                Data di Ordine : <input name="data" type="date" value="<?= htmlspecialchars($row['data_di_ordine']) ?>" required>
                <button type="submit">Aggiorna Ordine</button>


            </form>
            <a href="ordini.php?id=<?= $contatto_id ?>" class="button">Torna agli ordini</a>
        </div>
    </body>
    </html>
<?php
    //importo il file db
    require 'db.php';

    $contatto_id = $_GET['id']; // recupero l id del contatto

    $contatto = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM contatti WHERE id=$contatto_id"));

    //salvo in una variabile $ordini i risultati della query
    $ordini = mysqli_query($conn, "SELECT * FROM ordini WHERE contatto_id=$contatto_id"); // query per prendermi tutta la tabella ordini
?>




<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ordini per contatto</title>
    <link rel="stylesheet" href="style.css?v<?= time() ?>">
</head>
<body>


    <div class="container">

        <h2>Ordini di <?= $contatto['nome'] ?> </h2>

        <a href="aggiungi_ordine.php?id= <?= $contatto_id ?>" class="button">Nuovo Ordine</a>

        <table>

            <tr>
                <th>Prodotto</th>
                <th>Quantità</th>
                <th>Data</th>
                <th>Actions</th>
            </tr>

            <?php  while ($row = mysqli_fetch_assoc($ordini)) :  ?>

            <tr>

                <td><?= $row['prodotto'] ?></td>
                <td><?= $row['quantita'] ?></td>
                <td><?= $row['data_di_ordine'] ?></td>

                <td class="actions">

                    <!--Modifica dell ordine-->
                    <a href="modifica_ordine.php?id=<?= $row['id']  ?>">🖊️</a>
                    <!--Elimina ordine-->
                    <a href="elimina_ordine.php?id=<?= $row['id']  ?>"
                    onclick="return confirm('Sei sicuro di voler eliminare questo ordine?')">🗑️</a>

                </td>




            </tr>

            



             <?php  endwhile;   ?>

        </table>

        <a href="index.php" class="button">Torna alla lista</a>
        

    </div>


    
</body>
</html>
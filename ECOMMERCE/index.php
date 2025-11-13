<?php
    //importo il file db
    require 'db.php';

    //salvo in una variabile $result, i risultati della query
    $result = mysqli_query($conn, "SELECT * FROM contatti"); // query per prendermi tutta la tabella contatti
?>


<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecommerce</title>
    <link rel="stylesheet" href="style.css?v<?= time() ?>">
</head>
<body>

    <div class="container">

        <h1>Rubrica contatti</h1>
        <a href="aggiungi_contatto.php" class="button">Aggiungi contatto</a>

        <!--TABELLA CONTATTI-->
        <table>

            <!--INTESTAZIONE TABELLA-->
            <thead>
                <tr>
                    <th>
                        Nome : 
                    </th>
                     <th>
                        Telefono : 
                    </th>
                    <th>
                        Email : 
                    </th>
                    <th>
                        Actions : 
                    </th>
                </tr>
            </thead>

            <!--CORPO TABELLA-->
            <tbody>
                <!--Ciclo WHILE FINTANTO CHE HO RESULT, MOSTRAMELI IN ROW DEDICATE--->
                <?php  while($row = mysqli_fetch_assoc($result)) :     ?>
                    <tr>
                        <td>
                        <!--HTMLSPECIALCHARS aggiunge alla pagina html parti di codice-->
                            <?= htmlspecialchars($row['nome']) ?> <!--mostra nome-->
                        </td>
                        <td>
                            <?= htmlspecialchars($row['telefono']) ?> <!--mostra telefono-->
                        </td>
                        <td>
                            <?= htmlspecialchars($row['email']) ?> <!--mostra email-->
                        </td>

                        <!--SEZIONE ACTIONS -->
                        <td class="actions">

                            <a href="modifica_contatto.php?id=<?= $row['id']?>">🖊️</a>
                            <a href="elimina_contatto.php?id=<?= $row['id']?>">🗑️</a>
                            <a href="ordini.php?id=<?= $row['id']?>">📦</a>

                        </td>                          
                            
                    </tr>

                <?php endwhile; ?>    
            </tbody>
        </table>
    </div>

</body>
</html>
<?php

    require 'db.php';


    $id = $_GET['id']; // recupero l id dell a risorsa da modificare

    $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM contatti WHERE id=$id"));



    //DATI DAL FORM

    if ($_SERVER["REQUEST_METHOD"] == "POST"){


        //RECUPERO DEGLI INPUT DEL FORM DI MODIFICA
        $nome = $_POST['nome'];
        $telefono = $_POST['telefono'];
        $email = $_POST['email'];
    
        //query
        mysqli_query($conn, "UPDATE contatti SET nome='$nome', telefono='$telefono', email='$email' WHERE id=$id");


        //reindirizzo una volta modificato
        header("Location: index.php");



    }




?>











<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifica contatto</title>
    <link rel="stylesheet" href="style.css?v<?= time() ?>">
</head>
<body>
    
    <div class="container">

            <h1>Modifica Contatto</h1>

            <form action="" method="POST">

                Nome : <input name="nome" type="text"  value="<?= $row['nome']    ?>"     required>

                Telefono : <input name="telefono" type="text" value="<?= $row['telefono']    ?>" required>

                Email : <input name="email" type="text" value="<?= $row['email']    ?>" required>


                <button type="submit">Aggiorna</button>



            </form>

            <a href="index.php" class="button">Torna alla lista</a>

    </div>




</body>
</html>
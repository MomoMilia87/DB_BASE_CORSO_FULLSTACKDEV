<?php

    //importante, importare la connessione db
    require 'db.php';

    //recupero l ID della risorsa
    $id = $_GET['id'];

    //query per eliminare
    mysqli_query($conn, "DELETE FROM contatti WHERE id=$id");

    //reindirizza alla home dopo aver cancellato
    header("Location: index.php");



?>
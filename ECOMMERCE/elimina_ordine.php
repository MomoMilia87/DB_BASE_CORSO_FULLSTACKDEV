<?php

//importazione file conn
require 'db.php';


$id = intval($_GET['id']);

//query che cerca l ordine tramite l id passato
$sql = "SELECT contatto_id FROM ordini WHERE id=$id";
$result = mysqli_query($conn, $sql);


//estraggo i dati dell ordine (se esiste)
$ordine = mysqli_fetch_assoc($result);

//se lordine non esiste mostro errore
if(!$ordine){

    die("Ordine non trovato");
}

//salvo l id del contatto per reindirizzare dopo la cancellazione
$contatto_id = $ordine['contatto_id'];


//query DELETE PER CANCELLAZIONE

$delete = "DELETE FROM ordini WHERE id=$id";

//lancio la query
mysqli_query($conn, $delete);



header("Location: ordini.php?id=$contatto_id");


//termina script
exit;

?>


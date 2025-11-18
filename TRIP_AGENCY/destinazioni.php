<?php 
    include 'header.php'; 
    include 'db.php'; 


    //Logica per impaginazione
    $perPagina = 10;  // n elementi mostrati per pagina
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $offset = ($page - 1) * $perPagina;





    //LOGICA DI AGGIUNTA
    //chiamata POST che prende il gancio del bottone aggiugi del form, prendendo i valori inseriti nei vari campi
    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['aggiungi'])){

        //Preparo lo stato stmt -> statement 
        $stmt = $conn->prepare("INSERT INTO destinazioni (citta, paese, prezzo, data_partenza, data_ritorno, posti_disponibili) 
                                VALUES  (?, ?, ?, ?, ?, ?)");
        //Binding dei parametri e tipizzo
        $stmt->bind_param("ssdssi", $_POST['citta'], $_POST['paese'], $_POST['prezzo'],$_POST['data_partenza'], $_POST['data_ritorno'], $_POST['posti_disponibili']);
        
        //eseguo lo statement
        $stmt->execute();

        echo "<div class='alert alert-success'>Destinazione Aggiunta!</div>";


    }
    




    //LOGICA DI MODIFICA
    $destinazione_modifica = null;

    if (isset($_GET['modifica'])){


        $res = $conn->query("SELECT * FROM destinazioni WHERE id = " . intval($_GET['modifica']));

        $destinazione_modifica = $res->fetch_assoc();

    }





    //MODIFICA DEL DATO, SALVATAGGIO 
    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['salva_modifica'])){

        //PREPARE
        $stmt = $conn->prepare("UPDATE destinazioni SET citta=?, paese=?, prezzo=?, data_partenza=?, data_ritorno=?, posti_disponibili=? WHERE id=?");
        //BINDING
        $stmt->bind_param("ssdssii" ,$_POST['citta'],$_POST['paese'],$_POST['prezzo'],$_POST['data_partenza'],$_POST['data_ritorno'],$_POST['posti_disponibili'],$_POST['id']);
        //ESECUZIONE QUERY
        $stmt->execute();
        //messaggio
        echo "<div class='alert alert-info'>Destinazione Modificata correttamente</div>";
    }





    //CANCELLAZIONE CLIENTE
    if(isset($_GET['elimina'])){

        $id = intval($_GET['elimina']);
        $conn->query("DELETE FROM destinazioni WHERE id = $id");

        echo "<div class='alert alert-info'>Destinazione Cancellata correttamente</div>";
    }

    
 ?>





<h2>Destinazioni</h2>

    <!--Form-->
    <div class="card mb-4">
    <div class="card-body">
        <form action="" method="POST">

            <?php if($destinazione_modifica): ?>
            
                <input type="hidden" name="id" value="<?= $destinazione_modifica['id'] ?>">

            <?php endif; ?>

            <div class="row g-3">
                
                <div class="col-md-6">
                    <label style="font-weight: 600;" for="citta">Città : </label>
                    <input type="text" name="citta" class="form-control" placeholder="es.: Milano"
                           list="lista-citta"
                           value="<?= $destinazione_modifica['citta'] ?? ''?>"
                           id="citta"
                           required>
                           <datalist id="lista-citta">
                                <option value="Roma">
                                <option value="Milano">
                                <option value="Firenze">
                                <option value="Parigi">
                                <option value="New York">
                                <option value="Tokio">
                           </datalist>
                </div>
                
                <div class="col-md-6">
                    <label style="font-weight: 600;" for="paese">Paese : </label>
                    <select name="paese" class="form-select" id="paese" required>
                        <option value="" disabled <?= !isset($destinazione_modifica['paese']) ? 'selected' : '' ?>>Seleziona un paese</option>
                        <option value="Afghanistan" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Afghanistan') ? 'selected' : '' ?>>🇦🇫 Afghanistan</option>
                        <option value="Albania" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Albania') ? 'selected' : '' ?>>🇦🇱 Albania</option>
                        <option value="Algeria" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Algeria') ? 'selected' : '' ?>>🇩🇿 Algeria</option>
                        <option value="Andorra" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Andorra') ? 'selected' : '' ?>>🇦🇩 Andorra</option>
                        <option value="Angola" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Angola') ? 'selected' : '' ?>>🇦🇴 Angola</option>
                        <option value="Antigua e Barbuda" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Antigua e Barbuda') ? 'selected' : '' ?>>🇦🇬 Antigua e Barbuda</option>
                        <option value="Arabia Saudita" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Arabia Saudita') ? 'selected' : '' ?>>🇸🇦 Arabia Saudita</option>
                        <option value="Argentina" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Argentina') ? 'selected' : '' ?>>🇦🇷 Argentina</option>
                        <option value="Armenia" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Armenia') ? 'selected' : '' ?>>🇦🇲 Armenia</option>
                        <option value="Australia" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Australia') ? 'selected' : '' ?>>🇦🇺 Australia</option>
                        <option value="Austria" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Austria') ? 'selected' : '' ?>>🇦🇹 Austria</option>
                        <option value="Azerbaigian" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Azerbaigian') ? 'selected' : '' ?>>🇦🇿 Azerbaigian</option>
                        <option value="Bahamas" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Bahamas') ? 'selected' : '' ?>>🇧🇸 Bahamas</option>
                        <option value="Bahrain" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Bahrain') ? 'selected' : '' ?>>🇧🇭 Bahrain</option>
                        <option value="Bangladesh" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Bangladesh') ? 'selected' : '' ?>>🇧🇩 Bangladesh</option>
                        <option value="Barbados" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Barbados') ? 'selected' : '' ?>>🇧🇧 Barbados</option>
                        <option value="Bielorussia" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Bielorussia') ? 'selected' : '' ?>>🇧🇾 Bielorussia</option>
                        <option value="Belgio" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Belgio') ? 'selected' : '' ?>>🇧🇪 Belgio</option>
                        <option value="Belize" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Belize') ? 'selected' : '' ?>>🇧🇿 Belize</option>
                        <option value="Benin" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Benin') ? 'selected' : '' ?>>🇧🇯 Benin</option>
                        <option value="Bhutan" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Bhutan') ? 'selected' : '' ?>>🇧🇹 Bhutan</option>
                        <option value="Bolivia" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Bolivia') ? 'selected' : '' ?>>🇧🇴 Bolivia</option>
                        <option value="Bosnia ed Erzegovina" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Bosnia ed Erzegovina') ? 'selected' : '' ?>>🇧🇦 Bosnia ed Erzegovina</option>
                        <option value="Botswana" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Botswana') ? 'selected' : '' ?>>🇧🇼 Botswana</option>
                        <option value="Brasile" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Brasile') ? 'selected' : '' ?>>🇧🇷 Brasile</option>
                        <option value="Brunei" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Brunei') ? 'selected' : '' ?>>🇧🇳 Brunei</option>
                        <option value="Bulgaria" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Bulgaria') ? 'selected' : '' ?>>🇧🇬 Bulgaria</option>
                        <option value="Burkina Faso" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Burkina Faso') ? 'selected' : '' ?>>🇧🇫 Burkina Faso</option>
                        <option value="Burundi" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Burundi') ? 'selected' : '' ?>>🇧🇮 Burundi</option>
                        <option value="Cambogia" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Cambogia') ? 'selected' : '' ?>>🇰🇭 Cambogia</option>
                        <option value="Camerun" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Camerun') ? 'selected' : '' ?>>🇨🇲 Camerun</option>
                        <option value="Canada" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Canada') ? 'selected' : '' ?>>🇨🇦 Canada</option>
                        <option value="Capo Verde" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Capo Verde') ? 'selected' : '' ?>>🇨🇻 Capo Verde</option>
                        <option value="Repubblica Ceca" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Repubblica Ceca') ? 'selected' : '' ?>>🇨🇿 Repubblica Ceca</option>
                        <option value="Repubblica Centrafricana" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Repubblica Centrafricana') ? 'selected' : '' ?>>🇨🇫 Repubblica Centrafricana</option>
                        <option value="Ciad" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Ciad') ? 'selected' : '' ?>>🇹🇩 Ciad</option>
                        <option value="Cile" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Cile') ? 'selected' : '' ?>>🇨🇱 Cile</option>
                        <option value="Cina" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Cina') ? 'selected' : '' ?>>🇨🇳 Cina</option>
                        <option value="Cipro" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Cipro') ? 'selected' : '' ?>>🇨🇾 Cipro</option>
                        <option value="Colombia" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Colombia') ? 'selected' : '' ?>>🇨🇴 Colombia</option>
                        <option value="Comore" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Comore') ? 'selected' : '' ?>>🇰🇲 Comore</option>
                        <option value="Corea del Nord" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Corea del Nord') ? 'selected' : '' ?>>🇰🇵 Corea del Nord</option>
                        <option value="Corea del Sud" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Corea del Sud') ? 'selected' : '' ?>>🇰🇷 Corea del Sud</option>
                        <option value="Costa d\'Avorio" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Costa d\'Avorio') ? 'selected' : '' ?>>🇨🇮 Costa d'Avorio</option>
                        <option value="Costa Rica" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Costa Rica') ? 'selected' : '' ?>>🇨🇷 Costa Rica</option>
                        <option value="Croazia" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Croazia') ? 'selected' : '' ?>>🇭🇷 Croazia</option>
                        <option value="Cuba" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Cuba') ? 'selected' : '' ?>>🇨🇺 Cuba</option>
                        <option value="Danimarca" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Danimarca') ? 'selected' : '' ?>>🇩🇰 Danimarca</option>
                        <option value="Dominica" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Dominica') ? 'selected' : '' ?>>🇩🇲 Dominica</option>
                        <option value="Repubblica Dominicana" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Repubblica Dominicana') ? 'selected' : '' ?>>🇩🇴 Repubblica Dominicana</option>
                        <option value="Ecuador" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Ecuador') ? 'selected' : '' ?>>🇪🇨 Ecuador</option>
                        <option value="Egitto" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Egitto') ? 'selected' : '' ?>>🇪🇬 Egitto</option>
                        <option value="El Salvador" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'El Salvador') ? 'selected' : '' ?>>🇸🇻 El Salvador</option>
                        <option value="Emirati Arabi Uniti" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Emirati Arabi Uniti') ? 'selected' : '' ?>>🇦🇪 Emirati Arabi Uniti</option>
                        <option value="Eritrea" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Eritrea') ? 'selected' : '' ?>>🇪🇷 Eritrea</option>
                        <option value="Estonia" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Estonia') ? 'selected' : '' ?>>🇪🇪 Estonia</option>
                        <option value="Etiopia" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Etiopia') ? 'selected' : '' ?>>🇪🇹 Etiopia</option>
                        <option value="Fiji" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Fiji') ? 'selected' : '' ?>>🇫🇯 Fiji</option>
                        <option value="Filippine" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Filippine') ? 'selected' : '' ?>>🇵🇭 Filippine</option>
                        <option value="Finlandia" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Finlandia') ? 'selected' : '' ?>>🇫🇮 Finlandia</option>
                        <option value="Francia" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Francia') ? 'selected' : '' ?>>🇫🇷 Francia</option>
                        <option value="Gabon" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Gabon') ? 'selected' : '' ?>>🇬🇦 Gabon</option>
                        <option value="Gambia" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Gambia') ? 'selected' : '' ?>>🇬🇲 Gambia</option>
                        <option value="Georgia" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Georgia') ? 'selected' : '' ?>>🇬🇪 Georgia</option>
                        <option value="Germania" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Germania') ? 'selected' : '' ?>>🇩🇪 Germania</option>
                        <option value="Ghana" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Ghana') ? 'selected' : '' ?>>🇬🇭 Ghana</option>
                        <option value="Giamaica" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Giamaica') ? 'selected' : '' ?>>🇯🇲 Giamaica</option>
                        <option value="Giappone" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Giappone') ? 'selected' : '' ?>>🇯🇵 Giappone</option>
                        <option value="Gibuti" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Gibuti') ? 'selected' : '' ?>>🇩🇯 Gibuti</option>
                        <option value="Giordania" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Giordania') ? 'selected' : '' ?>>🇯🇴 Giordania</option>
                        <option value="Grecia" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Grecia') ? 'selected' : '' ?>>🇬🇷 Grecia</option>
                        <option value="Grenada" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Grenada') ? 'selected' : '' ?>>🇬🇩 Grenada</option>
                        <option value="Guatemala" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Guatemala') ? 'selected' : '' ?>>🇬🇹 Guatemala</option>
                        <option value="Guinea" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Guinea') ? 'selected' : '' ?>>🇬🇳 Guinea</option>
                        <option value="Guinea-Bissau" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Guinea-Bissau') ? 'selected' : '' ?>>🇬🇼 Guinea-Bissau</option>
                        <option value="Guinea Equatoriale" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Guinea Equatoriale') ? 'selected' : '' ?>>🇬🇶 Guinea Equatoriale</option>
                        <option value="Guyana" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Guyana') ? 'selected' : '' ?>>🇬🇾 Guyana</option>
                        <option value="Haiti" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Haiti') ? 'selected' : '' ?>>🇭🇹 Haiti</option>
                        <option value="Honduras" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Honduras') ? 'selected' : '' ?>>🇭🇳 Honduras</option>
                        <option value="India" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'India') ? 'selected' : '' ?>>🇮🇳 India</option>
                        <option value="Indonesia" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Indonesia') ? 'selected' : '' ?>>🇮🇩 Indonesia</option>
                        <option value="Iran" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Iran') ? 'selected' : '' ?>>🇮🇷 Iran</option>
                        <option value="Iraq" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Iraq') ? 'selected' : '' ?>>🇮🇶 Iraq</option>
                        <option value="Irlanda" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Irlanda') ? 'selected' : '' ?>>🇮🇪 Irlanda</option>
                        <option value="Islanda" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Islanda') ? 'selected' : '' ?>>🇮🇸 Islanda</option>
                        <option value="Israele" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Israele') ? 'selected' : '' ?>>🇮🇱 Israele</option>
                        <option value="Italia" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Italia') ? 'selected' : '' ?>>🇮🇹 Italia</option>
                        <option value="Kazakistan" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Kazakistan') ? 'selected' : '' ?>>🇰🇿 Kazakistan</option>
                        <option value="Kenya" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Kenya') ? 'selected' : '' ?>>🇰🇪 Kenya</option>
                        <option value="Kirghizistan" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Kirghizistan') ? 'selected' : '' ?>>🇰🇬 Kirghizistan</option>
                        <option value="Kiribati" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Kiribati') ? 'selected' : '' ?>>🇰🇮 Kiribati</option>
                        <option value="Kuwait" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Kuwait') ? 'selected' : '' ?>>🇰🇼 Kuwait</option>
                        <option value="Laos" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Laos') ? 'selected' : '' ?>>🇱🇦 Laos</option>
                        <option value="Lesotho" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Lesotho') ? 'selected' : '' ?>>🇱🇸 Lesotho</option>
                        <option value="Lettonia" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Lettonia') ? 'selected' : '' ?>>🇱🇻 Lettonia</option>
                        <option value="Libano" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Libano') ? 'selected' : '' ?>>🇱🇧 Libano</option>
                        <option value="Liberia" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Liberia') ? 'selected' : '' ?>>🇱🇷 Liberia</option>
                        <option value="Libia" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Libia') ? 'selected' : '' ?>>🇱🇾 Libia</option>
                        <option value="Liechtenstein" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Liechtenstein') ? 'selected' : '' ?>>🇱🇮 Liechtenstein</option>
                        <option value="Lituania" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Lituania') ? 'selected' : '' ?>>🇱🇹 Lituania</option>
                        <option value="Lussemburgo" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Lussemburgo') ? 'selected' : '' ?>>🇱🇺 Lussemburgo</option>
                        <option value="Macedonia del Nord" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Macedonia del Nord') ? 'selected' : '' ?>>🇲🇰 Macedonia del Nord</option>
                        <option value="Madagascar" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Madagascar') ? 'selected' : '' ?>>🇲🇬 Madagascar</option>
                        <option value="Malawi" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Malawi') ? 'selected' : '' ?>>🇲🇼 Malawi</option>
                        <option value="Maldive" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Maldive') ? 'selected' : '' ?>>🇲🇻 Maldive</option>
                        <option value="Malesia" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Malesia') ? 'selected' : '' ?>>🇲🇾 Malesia</option>
                        <option value="Mali" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Mali') ? 'selected' : '' ?>>🇲🇱 Mali</option>
                        <option value="Malta" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Malta') ? 'selected' : '' ?>>🇲🇹 Malta</option>
                        <option value="Marocco" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Marocco') ? 'selected' : '' ?>>🇲🇦 Marocco</option>
                        <option value="Isole Marshall" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Isole Marshall') ? 'selected' : '' ?>>🇲🇭 Isole Marshall</option>
                        <option value="Mauritania" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Mauritania') ? 'selected' : '' ?>>🇲🇷 Mauritania</option>
                        <option value="Mauritius" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Mauritius') ? 'selected' : '' ?>>🇲🇺 Mauritius</option>
                        <option value="Messico" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Messico') ? 'selected' : '' ?>>🇲🇽 Messico</option>
                        <option value="Micronesia" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Micronesia') ? 'selected' : '' ?>>🇫🇲 Micronesia</option>
                        <option value="Moldavia" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Moldavia') ? 'selected' : '' ?>>🇲🇩 Moldavia</option>
                        <option value="Monaco" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Monaco') ? 'selected' : '' ?>>🇲🇨 Monaco</option>
                        <option value="Mongolia" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Mongolia') ? 'selected' : '' ?>>🇲🇳 Mongolia</option>
                        <option value="Montenegro" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Montenegro') ? 'selected' : '' ?>>🇲🇪 Montenegro</option>
                        <option value="Mozambico" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Mozambico') ? 'selected' : '' ?>>🇲🇿 Mozambico</option>
                        <option value="Myanmar" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Myanmar') ? 'selected' : '' ?>>🇲🇲 Myanmar</option>
                        <option value="Namibia" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Namibia') ? 'selected' : '' ?>>🇳🇦 Namibia</option>
                        <option value="Nauru" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Nauru') ? 'selected' : '' ?>>🇳🇷 Nauru</option>
                        <option value="Nepal" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Nepal') ? 'selected' : '' ?>>🇳🇵 Nepal</option>
                        <option value="Nicaragua" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Nicaragua') ? 'selected' : '' ?>>🇳🇮 Nicaragua</option>
                        <option value="Niger" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Niger') ? 'selected' : '' ?>>🇳🇪 Niger</option>
                        <option value="Nigeria" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Nigeria') ? 'selected' : '' ?>>🇳🇬 Nigeria</option>
                        <option value="Norvegia" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Norvegia') ? 'selected' : '' ?>>🇳🇴 Norvegia</option>
                        <option value="Nuova Zelanda" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Nuova Zelanda') ? 'selected' : '' ?>>🇳🇿 Nuova Zelanda</option>
                        <option value="Oman" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Oman') ? 'selected' : '' ?>>🇴🇲 Oman</option>
                        <option value="Paesi Bassi" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Paesi Bassi') ? 'selected' : '' ?>>🇳🇱 Paesi Bassi</option>
                        <option value="Pakistan" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Pakistan') ? 'selected' : '' ?>>🇵🇰 Pakistan</option>
                        <option value="Palau" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Palau') ? 'selected' : '' ?>>🇵🇼 Palau</option>
                        <option value="Palestina" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Palestina') ? 'selected' : '' ?>>🇵🇸 Palestina</option>
                        <option value="Panama" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Panama') ? 'selected' : '' ?>>🇵🇦 Panama</option>
                        <option value="Papua Nuova Guinea" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Papua Nuova Guinea') ? 'selected' : '' ?>>🇵🇬 Papua Nuova Guinea</option>
                        <option value="Paraguay" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Paraguay') ? 'selected' : '' ?>>🇵🇾 Paraguay</option>
                        <option value="Perù" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Perù') ? 'selected' : '' ?>>🇵🇪 Perù</option>
                        <option value="Polonia" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Polonia') ? 'selected' : '' ?>>🇵🇱 Polonia</option>
                        <option value="Portogallo" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Portogallo') ? 'selected' : '' ?>>🇵🇹 Portogallo</option>
                        <option value="Qatar" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Qatar') ? 'selected' : '' ?>>🇶🇦 Qatar</option>
                        <option value="Regno Unito" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Regno Unito') ? 'selected' : '' ?>>🇬🇧 Regno Unito</option>
                        <option value="Romania" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Romania') ? 'selected' : '' ?>>🇷🇴 Romania</option>
                        <option value="Ruanda" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Ruanda') ? 'selected' : '' ?>>🇷🇼 Ruanda</option>
                        <option value="Russia" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Russia') ? 'selected' : '' ?>>🇷🇺 Russia</option>
                        <option value="Isole Salomone" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Isole Salomone') ? 'selected' : '' ?>>🇸🇧 Isole Salomone</option>
                        <option value="Samoa" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Samoa') ? 'selected' : '' ?>>🇼🇸 Samoa</option>
                        <option value="San Marino" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'San Marino') ? 'selected' : '' ?>>🇸🇲 San Marino</option>
                        <option value="São Tomé e Príncipe" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'São Tomé e Príncipe') ? 'selected' : '' ?>>🇸🇹 São Tomé e Príncipe</option>
                        <option value="Senegal" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Senegal') ? 'selected' : '' ?>>🇸🇳 Senegal</option>
                        <option value="Serbia" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Serbia') ? 'selected' : '' ?>>🇷🇸 Serbia</option>
                        <option value="Seychelles" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Seychelles') ? 'selected' : '' ?>>🇸🇨 Seychelles</option>
                        <option value="Sierra Leone" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Sierra Leone') ? 'selected' : '' ?>>🇸🇱 Sierra Leone</option>
                        <option value="Singapore" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Singapore') ? 'selected' : '' ?>>🇸🇬 Singapore</option>
                        <option value="Siria" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Siria') ? 'selected' : '' ?>>🇸🇾 Siria</option>
                        <option value="Slovacchia" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Slovacchia') ? 'selected' : '' ?>>🇸🇰 Slovacchia</option>
                        <option value="Slovenia" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Slovenia') ? 'selected' : '' ?>>🇸🇮 Slovenia</option>
                        <option value="Somalia" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Somalia') ? 'selected' : '' ?>>🇸🇴 Somalia</option>
                        <option value="Spagna" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Spagna') ? 'selected' : '' ?>>🇪🇸 Spagna</option>
                        <option value="Sri Lanka" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Sri Lanka') ? 'selected' : '' ?>>🇱🇰 Sri Lanka</option>
                        <option value="Stati Uniti" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Stati Uniti') ? 'selected' : '' ?>>🇺🇸 Stati Uniti</option>
                        <option value="Sudafrica" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Sudafrica') ? 'selected' : '' ?>>🇿🇦 Sudafrica</option>
                        <option value="Sudan" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Sudan') ? 'selected' : '' ?>>🇸🇩 Sudan</option>
                        <option value="Sudan del Sud" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Sudan del Sud') ? 'selected' : '' ?>>🇸🇸 Sudan del Sud</option>
                        <option value="Suriname" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Suriname') ? 'selected' : '' ?>>🇸🇷 Suriname</option>
                        <option value="Svezia" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Svezia') ? 'selected' : '' ?>>🇸🇪 Svezia</option>
                        <option value="Svizzera" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Svizzera') ? 'selected' : '' ?>>🇨🇭 Svizzera</option>
                        <option value="Swaziland" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Swaziland') ? 'selected' : '' ?>>🇸🇿 Swaziland</option>
                        <option value="Tagikistan" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Tagikistan') ? 'selected' : '' ?>>🇹🇯 Tagikistan</option>
                        <option value="Tanzania" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Tanzania') ? 'selected' : '' ?>>🇹🇿 Tanzania</option>
                        <option value="Thailandia" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Thailandia') ? 'selected' : '' ?>>🇹🇭 Thailandia</option>
                        <option value="Timor Est" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Timor Est') ? 'selected' : '' ?>>🇹🇱 Timor Est</option>
                        <option value="Togo" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Togo') ? 'selected' : '' ?>>🇹🇬 Togo</option>
                        <option value="Tonga" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Tonga') ? 'selected' : '' ?>>🇹🇴 Tonga</option>
                        <option value="Trinidad e Tobago" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Trinidad e Tobago') ? 'selected' : '' ?>>🇹🇹 Trinidad e Tobago</option>
                        <option value="Tunisia" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Tunisia') ? 'selected' : '' ?>>🇹🇳 Tunisia</option>
                        <option value="Turchia" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Turchia') ? 'selected' : '' ?>>🇹🇷 Turchia</option>
                        <option value="Turkmenistan" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Turkmenistan') ? 'selected' : '' ?>>🇹🇲 Turkmenistan</option>
                        <option value="Tuvalu" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Tuvalu') ? 'selected' : '' ?>>🇹🇻 Tuvalu</option>
                        <option value="Ucraina" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Ucraina') ? 'selected' : '' ?>>🇺🇦 Ucraina</option>
                        <option value="Uganda" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Uganda') ? 'selected' : '' ?>>🇺🇬 Uganda</option>
                        <option value="Ungheria" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Ungheria') ? 'selected' : '' ?>>🇭🇺 Ungheria</option>
                        <option value="Uruguay" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Uruguay') ? 'selected' : '' ?>>🇺🇾 Uruguay</option>
                        <option value="Uzbekistan" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Uzbekistan') ? 'selected' : '' ?>>🇺🇿 Uzbekistan</option>
                        <option value="Vanuatu" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Vanuatu') ? 'selected' : '' ?>>🇻🇺 Vanuatu</option>
                        <option value="Venezuela" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Venezuela') ? 'selected' : '' ?>>🇻🇪 Venezuela</option>
                        <option value="Vietnam" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Vietnam') ? 'selected' : '' ?>>🇻🇳 Vietnam</option>
                        <option value="Yemen" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Yemen') ? 'selected' : '' ?>>🇾🇪 Yemen</option>
                        <option value="Zambia" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Zambia') ? 'selected' : '' ?>>🇿🇲 Zambia</option>
                        <option value="Zimbabwe" <?= (isset($destinazione_modifica['paese']) && $destinazione_modifica['paese'] == 'Zimbabwe') ? 'selected' : '' ?>>🇿🇼 Zimbabwe</option>
                    </select>
                </div>
                
                
                <div class="col-md-6">
                    <label style="font-weight: 600;" for="prezzo">Prezzo : </label>
                    <input type="number" min="1" name="prezzo" class="form-control" placeholder="" 
                    
                    value="<?= $destinazione_modifica['prezzo'] ?? ''?>"
                    
                    required>
                </div>
                
                <div class="col-md-6">
                    <label style="font-weight: 600;" for="data_partenza">Data Partenza : </label>
                    <input type="date" name="data_partenza" class="form-control" placeholder="" 
                    
                    value="<?= $destinazione_modifica['data_partenza'] ?? ''?>"
                    
                    required>
                </div>
                
                <div class="col-md-6">
                    <label style="font-weight: 600;" for="data_ritorno">Data Ritorno : </label>
                    <input type="date" name="data_ritorno" class="form-control" placeholder="" 
                    
                    value="<?= $destinazione_modifica['data_ritorno'] ?? ''?>"
                    
                    required>
                </div>
                
                <div class="col-md-6">
                    <label style="font-weight: 600;" for="posti_disponibili">Posti disponibili : </label>
                    <input type="number" min ="1" name="posti_disponibili" class="form-control" placeholder="" 
                    
                    value="<?= $destinazione_modifica['posti_disponibili'] ?? ''?>"
                    
                    required>
                </div>
                
                
                
                <div class="col-12">
                    
                    <button 
                        name="<?= $destinazione_modifica ? 'salva_modifica' : 'aggiungi' ?>" 
                        class="btn <?= $destinazione_modifica ? 'btn-warning' : 'btn-success' ?>" 
                        type="submit">
                        <?= $destinazione_modifica ? 'Salva' : 'Aggiungi' ?>
                    </button>
                
                </div>

            </div>
        </form>
    </div>
</div>



    <!--LOGICA RENDER -->
    <?php

        //vado a conteggiare il totale dei clienti con query
        $total = $conn->query("SELECT COUNT(*) as t FROM destinazioni")->fetch_assoc()['t'];
        $totalPages = ceil($total / $perPagina); // il numero di pagine della navigazione

        //QUERY PER ordinare i dati in modo DECRESCENTE IMPAGINATI PER valore di "$perPagina" 
        $result = $conn->query("SELECT * FROM destinazioni ORDER BY id ASC LIMIT $perPagina OFFSET $offset");

    ?>





    <!--Tabella-->
    <table class="table table-striped">

        <thead>
            <!--Intestazione tabella-->
            <tr>

                <th>ID</th>
                <th>Città</th>
                <th>Paese</th>
                <th>Prezzo</th>
                <th>Data di Partenza</th>
                <th>Data di Ritorno</th>
                <th>Posti Disponibili</th>
                <th>Azioni</th>

            </tr>

        </thead>
        <!--Corpo tabella-->
        <tbody>

            <?php while ($row = $result->fetch_assoc()) : ?>
                
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['citta'] ?></td>
                    <td><?= $row['paese'] ?></td>
                    <td><?= $row['prezzo'] ?></td>
                    <td><?= $row['data_partenza'] ?></td>
                    <td><?= $row['data_ritorno'] ?></td>
                    <td><?= $row['posti_disponibili'] ?></td>
                    <td>

                        <a class="btn btn-sm btn-warning" href="?modifica=<?= $row['id']  ?>">Modifica</a>
                        <a class="btn btn-sm btn-danger" href="?elimina=<?= $row['id']  ?>" onclick="return confirm ('Sicuro?')">Elimina</a>


                    </td>
                </tr>


            <?php endwhile; ?>

        </tbody>

    </table>



    <!--Paginazione-->
    <nav>

        <ul class="pagination">

            <?php for($i = 1; $i <= $totalPages; $i++ ) : ?>

                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>   

            <?php endfor; ?>



        </ul>
    </nav>

<?php include 'footer.php'; ?>
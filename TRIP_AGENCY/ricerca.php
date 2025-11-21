<?php  include 'header.php' ?>

<?php  include 'db.php' ?>

<?php  include 'header.php' ?>

<?php  include 'db.php' ?>


<?php

    //impaginazione
    $perPagina = 10;
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $offset = ($page - 1) * $perPagina;


    //salvo in varibaili le GET

    $nome_cliente = $_GET['nome_cliente'] ?? '';
    $paese = $_GET['paese'] ?? '';
    $citta = $_GET['citta'] ?? '';
    $prezzo_max = $_GET['prezzo_max'] ?? '';
    $data = $_GET['data'] ?? '';
    


    //Costruzione della QUERY

    $where = "WHERE 1=1"; // PARTO DA UNA CONDIZIONE CHE è SEMPRE VERA
    $params = []; //CONTIENE I VALORI PER ? (PLACEHOLDER DELLA QUERY)
    $types = ''; // CONTINENE IL BINDING (ssid)

    //se sto facendo la ricerca per nome
    if($nome_cliente !== ''){

        $where .= " AND (c.nome LIKE ? OR c.cognome LIKE ?)";
        $params[] = "%$nome_cliente%";
        $types .= 's';

    }
    if($paese !== ''){

        $where .= " AND (d.paese LIKE ?)";
        $params[] = "%$paese%";
        $types .= 's';

    }
    if($citta !== ''){

        $where .= " AND (d.citta LIKE ?)";
        $params[] = "%$citta%";
        $types .= 's';

    }
    if($prezzo_max !== ''){

        $where .= " AND (d.prezzo <= ? )";
        $params[] = floatval($prezzo_max);
        $types .= 'd';

    }
    if($data !== ''){

        $where .= " AND (p.data_prenotazione = ? )";
        $params[] = $data;
        $types .= 's';

    }
 


    //conteggio totale

    $stmt = $conn->prepare("SELECT COUNT(*) as total 
                            FROM prenotazioni p
                            JOIN clienti c ON p.id_cliente = c.id
                            JOIN destinazioni d ON p.id_destinazione = d.id
                            $where");
    if($types !== '') $stmt->bind_param($types, ...$params);

    $stmt->execute();
    $total = $stmt->get_result()->fetch_assoc()['total'];
    $totalPages = ceil($total / $perPagina);


    //Risultati impaginati

    $stmt = $conn->prepare("SELECT p.id, c.nome, c.cognome, d.citta, d.paese, d.prezzo, p.data_prenotazione
                            FROM prenotazioni p
                            JOIN clienti c ON p.id_cliente = c.id
                            JOIN destinazioni d ON p.id_destinazione = d.id
                            $where ORDER BY p.id DESC LIMIT ? OFFSET ?");
    
    $params[] = $perPagina;
    $params[] = $offset;
    $types .= "ii";
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();













?>








    <h2>Ricerca Prenotazioni</h2>

    <form action="" method="GET">

        <div><input type="text" name="nome_cliente"value="<?=  htmlspecialchars($nome_cliente)  ?>" class="form-control" placeholder="Cliente.."></div>
        <div><input type="text" name="paese" value="<?=  htmlspecialchars($paese)  ?>" class="form-control" placeholder="Paese"></div>
        <div><input type="text" name="citta" value="<?=  htmlspecialchars($citta)  ?>" class="form-control" placeholder="Città.."></div>
        <div><input type="number" name="prezzo_max" value="<?=  htmlspecialchars($prezzo_max)  ?>" class="form-control" placeholder="Prezzo max euro.."></div>
        <div><input type="date" value="<?=  htmlspecialchars($data)  ?>" class="form-control" name="data"></div>
        

        <button class="btn btn-primary">Cerca</button>

    </form>


    <table class="table table-striped">
        
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Paese</th>
                <th>Città</th>
                <th>Prezzo</th>
                <th>Data</th>
           
            </tr>
        </thead>

        <tbody>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['nome'] ?></td>
                <td><?= $row['paese'] ?></td>
                <td><?= $row['citta'] ?></td>
                <td><?= $row['prezzo'] ?></td>
                <td><?= $row['data_prenotazione'] ?></td>
         
            </tr>

            <?php endwhile; ?>
        </tbody>
    </table>


    <!--Paginazione-->
    



<?php  include 'footer.php' ?>

<?php  include 'footer.php' ?>
<?php 
    include 'header.php'; 
    include 'db.php'; 


    //Creazione tabella note se non esiste
    $conn->query("CREATE TABLE IF NOT EXISTS clienti_note (
        id INT AUTO_INCREMENT PRIMARY KEY,
        cliente_id INT NOT NULL,
        nota TEXT NOT NULL,
        data_creazione DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (cliente_id) REFERENCES clienti(id) ON DELETE CASCADE
    )");

    //Logica per impaginazione
    $perPagina = 5;  // n elementi mostrati per pagina
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $offset = ($page - 1) * $perPagina;

    //vado a conteggiare il totale dei clienti con query
    $total = $conn->query("SELECT COUNT(*) as t FROM clienti")->fetch_assoc()['t'];
    $totalPages = ceil($total / $perPagina); // il numero di pagine della navigazione

    //QUERY PER ordinare i dati in modo DECRESCENTE IMPAGINATI PER valore di "$perPagina" 
    //MODIFICATA PER INCLUDERE IL CONTEGGIO DELLE NOTE
    $result = $conn->query("SELECT c.*, (SELECT COUNT(*) FROM clienti_note WHERE cliente_id = c.id) as note_count 
                           FROM clienti c 
                           ORDER BY c.id ASC 
                           LIMIT $perPagina OFFSET $offset");
    //chiamata POST che prende il gancio del bottone aggiugi del form, prendendo i valori inseriti nei vari campi
    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['aggiungi'])){


        $upload = null;

        //controllo del campo documento -> se il file è stato effettivamente caricato
        //$_FILES -> contiene tutti i file caricati tramite l input
        //$_FILES['documento']['name'] -> il nome originale del file scelto dall utente
        //if(!empty($_FILES['documento']['name'])){ -> "solo se un file è stato caricato"
        //quindi se il campo è vuoto non si tenta l UPLOAD

        if(!empty($_FILES['documento']['name'])){

            // SICUREZZA UPLOAD
            $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
            $file_type = mime_content_type($_FILES['documento']['tmp_name']);
            
            if(!in_array($file_type, $allowed_types)){
                 echo "<div class='alert alert-danger'>Tipo di file non consentito! Solo JPG, PNG o PDF.</div>";
            } else {
                //ESTRAE IL NOME DEL FILE SENZA PERCORSO
                $ext = pathinfo($_FILES['documento']['name'], PATHINFO_EXTENSION);
                $upload = time() . "_" . uniqid() . "." . $ext; // Nome univoco sicuro
                
                //sposto il file dalla posizione tmp/ alla cartella uploads/
                move_uploaded_file($_FILES['documento']['tmp_name'], "uploads/" . $upload);
            }
        }

       


        //Preparo lo stato stmt -> statement 
        $stmt = $conn->prepare("INSERT INTO clienti (nome, cognome, email, telefono, nazione, codice_fiscale, documento) 
                                VALUES  (?, ?, ?, ?, ?, ?, ?)");
        //Binding dei parametri e tipizzo
        $stmt->bind_param("sssssss", $_POST['nome'], $_POST['cognome'], $_POST['email'], $_POST['telefono'], $_POST['nazione'], $_POST['codice_fiscale'], $upload);
        
        //eseguo lo statement
        $stmt->execute();

        echo "<div class='alert alert-info'>Cliente Aggiunto!</div>";
        echo "
        
                <script>

                    setTimeout(function () {

                        window.location.href = 'clienti.php'

                    }, 2500);

                </script>
        
             ";

        exit;


    }
    




    //LOGICA DI MODIFICA
    $cliente_modifica = null;

    if (isset($_GET['modifica'])){


        $res = $conn->query("SELECT * FROM clienti WHERE id = " . intval($_GET['modifica']));

        $cliente_modifica = $res->fetch_assoc();

    }





    //MODIFICA DEL DATO, SALVATAGGIO 
    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['salva_modifica'])){



        if(!empty($_FILES['documento']['name'])){

             // SICUREZZA UPLOAD
            $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
            $file_type = mime_content_type($_FILES['documento']['tmp_name']);
            
            if(!in_array($file_type, $allowed_types)){
                 echo "<div class='alert alert-danger'>Tipo di file non consentito! Solo JPG, PNG o PDF.</div>";
                 exit; // Blocco se il file non è valido
            }

            //ESTRAE IL NOME DEL FILE SENZA PERCORSO
            $ext = pathinfo($_FILES['documento']['name'], PATHINFO_EXTENSION);
            $nuovoDocumento = time() . "_" . uniqid() . "." . $ext; // Nome univoco sicuro
            
            //sposto il file dalla posizione tmp/ alla cartella uploads/
            move_uploaded_file($_FILES['documento']['tmp_name'], "uploads/" . $nuovoDocumento);

        }else{


            $nuovoDocumento = $_POST['documento_esistente'];

        }







        //PREPARE
        $stmt = $conn->prepare("UPDATE clienti SET nome=?, cognome=?, email=?, telefono=?, nazione=?, codice_fiscale=?, documento=? WHERE id=?");
        //BINDING
        $stmt->bind_param("sssssssi" ,$_POST['nome'],$_POST['cognome'],$_POST['email'],$_POST['telefono'],$_POST['nazione'],$_POST['codice_fiscale'],$nuovoDocumento, $_POST['id']);
        //ESECUZIONE QUERY
        $stmt->execute();
        //messaggio
        echo "<div class='alert alert-info'>Cliente modificato correttamente</div>";
        echo "
        
                <script>

                    setTimeout(function () {

                        window.location.href = 'clienti.php'

                    }, 2500);

                </script>
        
             ";

        exit;
    }





    //CANCELLAZIONE CLIENTE
    if(isset($_GET['elimina'])){

        $id = intval($_GET['elimina']);
        $conn->query("DELETE FROM clienti WHERE id = $id");

        echo "<div class='alert alert-info'>Cliente Cancellato correttamente</div>";
    }

    
 ?>





<h2>Clienti</h2>

    <!--Form-->
    <div class="card mb-4">
        <div class="card-body">

            <!--EncType è il supporto dell upload del file nel form-->
            <form action="" method="POST" enctype="multipart/form-data">

                <?php if($cliente_modifica): ?>
                
                    <input type="hidden" name="id" value="<?= $cliente_modifica['id'] ?>">

                <?php endif; ?>

                <div class="row g-3">
                    
                    <div class="col-md-6">
                        <label style="font-weight: 600;" for="">Nome : </label>
                        
                        <!--con value prendo il valore del campo inserito-->
                        <input type="text" name="nome" class="form-control" placeholder="es.: Mario"
                        
                        
                        value="<?= htmlspecialchars($cliente_modifica['nome'] ?? '')?>"
                        
                        required>
                    </div>
                    
                    <div class="col-md-6">
                        <label style="font-weight: 600;" for="">Cognome : </label>
                        <input type="text" name="cognome" class="form-control" placeholder="es.: Rossi" 
                        
                        value="<?= htmlspecialchars($cliente_modifica['cognome'] ?? '')?>"

                        required>
                    </div>
                    
                    <div class="col-md-6">
                        <label style="font-weight: 600;" for="">Email : </label>
                        <input type="text" name="email" class="form-control" placeholder="es.: mario.rossi@mail.it" 
                        
                        value="<?= htmlspecialchars($cliente_modifica['email'] ?? '')?>"
                        
                        required>
                    </div>
                    
                    <div class="col-md-6">
                        <label style="font-weight: 600;" for="">Telefono : </label>
                        <input type="text" name="telefono" class="form-control" placeholder="es.: 393406587398" 
                        
                        value="<?= htmlspecialchars($cliente_modifica['telefono'] ?? '')?>"
                        
                        required>
                    </div>
                    
                    <div class="col-md-6">
                        <label style="font-weight: 600;" for="">Nazione : </label>
                        <input type="text" name="nazione" class="form-control" placeholder="es.: Italia" 
                        
                        value="<?= htmlspecialchars($cliente_modifica['nazione'] ?? '')?>"
                        
                        required>
                    </div>
                    
                    <div class="col-md-6">
                        <label style="font-weight: 600;" for="">Codice Fiscale : </label>
                        <input type="text" name="codice_fiscale" class="form-control" placeholder="Codice Fiscale di 16 cifre..." 
                        
                        value="<?= htmlspecialchars($cliente_modifica['codice_fiscale'] ?? '')?>"
                        
                        required>
                    </div>
                    

                    <!--Ottengo il VECCHIO FILE DAL DATABASE-->
                    <?php if ($cliente_modifica) : ?>

                        <input type="hidden" name="documento_esistente" value="<?= htmlspecialchars($cliente_modifica['documento']) ?>">

                    <?php endif; ?>



                    <div class="col-md-6">
                        <label style="font-weight: 600;" for="">Documento : </label>
                        <input type="file" data-max-size="1000000" accept=".pdf, .jpg, .png"  name="documento" class="form-control" placeholder="La dimnesione massima consentita : 2MB !" 
                        
                        value="<?= htmlspecialchars($cliente_modifica['documento'] ?? '')?>"
                        >

                    </div>
                    
                    <div class="col-12">
                        
                        <button 
                            name="<?= $cliente_modifica ? 'salva_modifica' : 'aggiungi' ?>" 
                            class="btn <?= $cliente_modifica ? 'btn-warning' : 'btn-success' ?>" 
                            type="submit">
                            <?= $cliente_modifica ? 'Salva' : 'Aggiungi' ?>
                        </button>


                        <!--Pulsante ANNULLA-->
                        <?php if ($cliente_modifica) : ?>

                            <a href="clienti.php" class="btn btn-secondary ms-2">Annulla</a>

                        <?php endif;?>
                    
                    </div>

                </div>
            </form>
        </div>
    </div>



    <!--LOGICA RENDER -->
    <?php
        // La query è stata spostata in alto per ottimizzazione e gestione conteggio note
    ?>

    <!--Tabella-->
    <div class="table-responsive">
        <table class="table table-striped">

            <thead>
                <!--Intestazione tabella-->
                <tr>

                    <th>ID</th>
                    <th>Nome</th>
                    <th>Cognome</th>
                    <th>Email</th>
                    <th>Telefono</th>
                    <th>Nazione</th>
                    <th>Codice Fiscale</th>
                    <th>Documento</th>
                    <th class="text-center">Azioni</th>

                </tr>

            </thead>
            <!--Corpo tabella-->
            <tbody>

                <?php while ($row = $result->fetch_assoc()) : ?>
                    
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['nome']) ?></td>
                        <td><?= htmlspecialchars($row['cognome']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['telefono']) ?></td>
                        <td><?= htmlspecialchars($row['nazione']) ?></td>
                        <td><?= htmlspecialchars($row['codice_fiscale']) ?></td>
                        <td>
                            <?php if(!empty($row['documento'])) : ?>
                        
                                <a href="uploads/<?= htmlspecialchars($row['documento']) ?>"
                                
                                    download
                                    data-bs-toggle="tooltip"
                                    title="Scarica : <?= htmlspecialchars($row['documento']) ?>">

                                    <i class="bi bi-file-earmark-check"style="font-size: 1.5rem;"></i>

                                </a>
                            
                        
                            <?php else: ?>
                                
                                <span><i class="bi bi-file-x-fill" style="font-size: 1.5rem;"></i></span>


                            <?php endif; ?>

                        </td>
                        
                        <td class="text-center">

                            <a class="btn btn-sm btn-warning" href="?modifica=<?= $row['id']  ?>"><i class="bi bi-pen"></i></a>
                            
                            <!-- PULSANTE NOTE CON INDICATORE -->
                            <?php 
                                $hasNotes = $row['note_count'] > 0;
                                $btnClass = $hasNotes ? 'btn-info text-white' : 'btn-outline-info';
                                $iconClass = $hasNotes ? 'bi-journal-bookmark-fill' : 'bi-journal-text';
                                $tooltip = $hasNotes ? "Vedi note ({$row['note_count']})" : "Aggiungi nota";
                            ?>
                            <button class="btn btn-sm <?= $btnClass ?> position-relative" 
                                    onclick="openNotes(<?= $row['id'] ?>, '<?= htmlspecialchars($row['nome'] . ' ' . $row['cognome']) ?>')"
                                    data-bs-toggle="tooltip" title="<?= $tooltip ?>">
                                <i class="bi <?= $iconClass ?>"></i>
                                <?php if($hasNotes): ?>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        <?= $row['note_count'] ?>
                                        <span class="visually-hidden">note</span>
                                    </span>
                                <?php endif; ?>
                            </button>

                            <a class="btn btn-sm btn-danger" href="?elimina=<?= $row['id']  ?>" onclick="return confirm ('Sicuro?')"><i class="bi bi-trash"></i></a>


                        </td>
                    </tr>





                <?php endwhile; ?>

            </tbody>

        </table>
    </div>


    <!--Paginazione-->
    <nav>

        <ul class="pagination  pagination_personal">

            <?php for($i = 1; $i <= $totalPages; $i++ ) : ?>

                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>   

            <?php endfor; ?>



        </ul>
    </nav>

<?php include 'footer.php'; ?>

<!-- NOTES MODAL -->
<div class="modal fade" id="notesModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content glass">
      <div class="modal-header border-0">
        <h5 class="modal-title fw-bold text-primary">Note Cliente: <span id="modalClientName"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="notesList" class="mb-3" style="max-height: 300px; overflow-y: auto;">
            <!-- Notes will be loaded here -->
            <div class="text-center text-muted">Caricamento...</div>
        </div>
        <div class="form-group">
            <textarea id="newNoteText" class="form-control" rows="3" placeholder="Scrivi una nuova nota..."></textarea>
        </div>
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
        <button type="button" class="btn btn-primary" onclick="saveNote()">Salva Nota</button>
      </div>
    </div>
  </div>
</div>

<script>
let currentClientId = null;
const notesModal = new bootstrap.Modal(document.getElementById('notesModal'));

function openNotes(id, name) {
    currentClientId = id;
    document.getElementById('modalClientName').innerText = name;
    document.getElementById('newNoteText').value = '';
    loadNotes(id);
    notesModal.show();
}

function loadNotes(id) {
    const list = document.getElementById('notesList');
    list.innerHTML = '<div class="text-center text-muted">Caricamento...</div>';
    
    fetch(`api/note.php?cliente_id=${id}`)
        .then(response => response.json())
        .then(data => {
            list.innerHTML = '';
            if (data.length === 0) {
                list.innerHTML = '<div class="alert alert-light">Nessuna nota presente.</div>';
                return;
            }
            data.forEach(note => {
                const date = new Date(note.data_creazione).toLocaleString('it-IT');
                const item = `
                    <div class="card mb-2 p-3 border-0 shadow-sm" style="background: rgba(255,255,255,0.6);">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small class="text-muted" style="font-size: 0.8rem;">${date}</small>
                        </div>
                        <p class="mb-0" style="white-space: pre-wrap;">${note.nota}</p>
                    </div>
                `;
                list.innerHTML += item;
            });
        })
        .catch(err => {
            list.innerHTML = '<div class="alert alert-danger">Errore nel caricamento delle note.</div>';
            console.error(err);
        });
}

function saveNote() {
    const text = document.getElementById('newNoteText').value.trim();
    if (!text) return;

    fetch('api/note.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ cliente_id: currentClientId, nota: text })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('newNoteText').value = '';
            loadNotes(currentClientId);
        } else {
            alert('Errore nel salvataggio della nota');
        }
    })
    .catch(err => {
        alert('Errore di connessione');
        console.error(err);
    });
}
</script>
<?php include 'header.php'; ?>
<?php include 'db.php'; ?>

<h2>Prenotazioni</h2>

    <!--Form-->
    <div class="card mb-4">

        <div class="card-body">

            <form action="" method="POST">

                <div class="row g-3">
                    
                    <div class="col-md-6">
                        <label style="font-weight: 600;" for="">Seleziona Cliente : </label>
                        <input type="text" name="cliente" class="form-control" placeholder="" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label style="font-weight: 600;" for="">Seleziona Destinazione : </label>
                        <input type="text" name="destinazione" class="form-control" placeholder="" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label style="font-weight: 600;" for="">Data Prenotazione : </label>
                        <input type="date" name="dataprenotazione" class="form-control" placeholder="" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label style="font-weight: 600;" for="">Numero persone : </label>
                        <input type="number" name="num_persone" class="form-control" placeholder="es.: 10" required>
                    </div>
                    
                
                    
                    <div class="col-md-6">
                        <label style="font-weight: 600;" for="">Assicurazione : </label>
                        <input type="checkbox" name="assicurazione"  placeholder="Assicurazione del cliente..." required>
                    </div>
                    
                    <div class="col-md-6">
                        <label style="font-weight: 600;" for="">Acconto : </label>
                        <input type="number" name="acconto" class="form-control" placeholder="Acconto del cliente..." required>
                    </div>
                    
                    <div class="col-12">
                        <button class="btn btn-success" type="submit">Salva</button>
                    </div>

                </div>
            </form>
        </div>
    </div>


    <!--Tabella-->
    <table class="table table-striped">

        <thead>

            <tr>

                <th>ID</th>
                <th>Cliente</th>
                <th>Destinazione</th>
                <th>Data</th>
                <th>Persone</th>
                <th>Assicurazione</th>
                <th>Acconto</th>
                <th>Azioni</th>

            </tr>

        </thead>

        <tbody>


        

        </tbody>

    </table>


<?php include 'footer.php'; ?>
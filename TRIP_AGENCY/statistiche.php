<?php 
      include 'header.php'; 
      include 'db.php'; 

      // QUERY 1: Prenotazioni per mese (Anno corrente)
      $anno_corrente = date("Y");
      $sql_prenotazioni = "
        SELECT MONTH(data_prenotazione) as mese, COUNT(*) as totale 
        FROM prenotazioni 
        WHERE YEAR(data_prenotazione) = $anno_corrente
        GROUP BY MONTH(data_prenotazione)
        ORDER BY mese
      ";
      $res_pren = $conn->query($sql_prenotazioni);
      
      $dati_prenotazioni = array_fill(1, 12, 0); // Array 1-12 inizializzato a 0
      while($row = $res_pren->fetch_assoc()){
          $dati_prenotazioni[$row['mese']] = $row['totale'];
      }

      // QUERY 2: Entrate per mese (Acconti)
      $sql_entrate = "
        SELECT MONTH(data_prenotazione) as mese, SUM(acconto) as totale 
        FROM prenotazioni 
        WHERE YEAR(data_prenotazione) = $anno_corrente
        GROUP BY MONTH(data_prenotazione)
        ORDER BY mese
      ";
      $res_entrate = $conn->query($sql_entrate);

      $dati_entrate = array_fill(1, 12, 0);
      while($row = $res_entrate->fetch_assoc()){
          $dati_entrate[$row['mese']] = $row['totale'];
      }

      // Preparo dati per JS
      $json_prenotazioni = json_encode(array_values($dati_prenotazioni));
      $json_entrate = json_encode(array_values($dati_entrate));
?>

<h2>Statistiche (Anno <?= $anno_corrente ?>)</h2>

<div class="row">

    <div class="col-md-6 mb-4 mt-4">
        <div class="card p-3">
            <h5 class="text-center">Prenotazioni per mese</h5>
            <canvas id="lineaPrenotazioni"></canvas>
        </div>
    </div>

    <div class="col-md-6 mb-4 mt-4">
        <div class="card p-3">
            <h5 class="text-center">Entrate Mensili (Acconti)</h5>
            <canvas id="barEntrate"></canvas>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  const mesi = ['Gen', 'Feb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug', 'Ago', 'Set', 'Ott', 'Nov', 'Dic'];

  // GRAFICO PRENOTAZIONI
  const ctx = document.getElementById('lineaPrenotazioni');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: mesi,
      datasets: [{
        label: 'Numero Prenotazioni',
        data: <?= $json_prenotazioni ?>,
        borderColor: 'rgba(106, 5, 123, 1)',
        tension: 0.1,
        fill: true,
        backgroundColor: 'rgba(75, 192, 192, 0.2)'
      }]
    },
    options: { scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
  });

  // GRAFICO ENTRATE
  const ctx2 = document.getElementById('barEntrate');
  new Chart(ctx2, {
    type: 'bar',
    data: {
      labels: mesi,
      datasets: [{
        label: 'Entrate (€)',
        data: <?= $json_entrate ?>,
        backgroundColor: 'rgba(116, 12, 120, 0.6)',
        borderColor: 'rgba(87, 10, 106, 1)',
        borderWidth: 1
      }]
    },
    options: { scales: { y: { beginAtZero: true } } }
  });
</script>

<?php include 'footer.php'; ?>
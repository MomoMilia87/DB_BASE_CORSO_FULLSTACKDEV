
<?php

    //RECUPERO IL NOME DELLA PAGINA CORRENTE
    $currentPage = basename($_SERVER['PHP_SELF']);
    //questo restituisce  : clienti.php, destinazioni.php...

?>



<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Controllo se l'utente è loggato (escludo login.php per evitare loop)
    if (!isset($_SESSION['user_id']) && basename($_SERVER['PHP_SELF']) != 'login.php' && basename($_SERVER['PHP_SELF']) != 'setup_db.php') {
        header("Location: login.php");
        exit;
    }
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Happy Skies Travel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!--ICONS-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">

</head>
  <body>
    

    <!--NAVBAR-->
    <nav class="navbar navbar-expand-lg bg-body-tertiary mb-5 shadow-sm">
        <div class="container-fluid">
          
            <a class="navbar-brand fw-bold text-primary d-flex align-items-center" href="index.php">
                <img src="logo.png?v=<?php echo time(); ?>" alt="Logo" style="height: 50px; margin-right: 10px; border-radius: 50%;">
                Happy Skies Travel
            </a>
          
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage == 'index.php' ? 'active' : '' ?>" aria-current="page" href="index.php">Home</a>
                </li>
              
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage == 'clienti.php' ? 'active' : '' ?>" href="clienti.php">Clienti</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= $currentPage == 'destinazioni.php' ? 'active' : '' ?>" href="destinazioni.php">Destinazioni</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= $currentPage == 'prenotazioni.php' ? 'active' : '' ?>" href="prenotazioni.php">Prenotazioni</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= $currentPage == 'statistiche.php' ? 'active' : '' ?>" href="statistiche.php">Statistiche</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= $currentPage == 'ricerca.php' ? 'active' : '' ?>" href="ricerca.php">Ricerca</a>
                </li>
             
            </ul>
            
            <?php if(isset($_SESSION['user_id'])): ?>
                <div class="d-flex align-items-center gap-3">
                    <span class="text-muted small">Ciao, <?= htmlspecialchars($_SESSION['user_email']) ?></span>
                    <a href="logout.php" class="btn btn-sm btn-outline-danger">Esci</a>
                </div>
            <?php endif; ?>

          </div>
        </div>
      </nav>


    <div class="container mt-4">

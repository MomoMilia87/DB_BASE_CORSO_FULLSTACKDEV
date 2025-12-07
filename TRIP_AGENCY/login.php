<?php
session_start();
include 'db.php';

// Se già loggato, vai alla home
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $selected_role = $_POST['role']; // Ruolo selezionato nel form

    try {
        $stmt = $conn->prepare("SELECT id, password, role, email FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($user = $res->fetch_assoc()) {
            if (password_verify($password, $user['password'])) {
                
                // VERIFICA RUOLO
                if ($user['role'] === $selected_role) {
                    // Login corretto
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_role'] = $user['role'];
                    
                    header("Location: index.php");
                    exit;
                } else {
                    $error = "Accesso negato! Il tuo account non ha i permessi per accedere come " . ucfirst($selected_role) . ".";
                }

            } else {
                $error = "Password non corretta.";
            }
        } else {
            $error = "Utente non trovato.";
        }
    } catch (mysqli_sql_exception $e) {
        // Errore 1146: Table doesn't exist
        if ($e->getCode() == 1146) {
            echo "<div class='alert alert-warning'>
                    <strong>Primo avvio rilevato!</strong><br>
                    Il database deve essere aggiornato.<br>
                    <a href='setup_db.php' class='btn btn-warning mt-2'>Clicca qui per installare</a>
                  </div>";
            exit;
        } else {
            throw $e; // Rilancia altri errori
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Happy Skies Travel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .login-card {
            max-width: 400px;
            width: 100%;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            background: white;
        }
        .brand-logo {
            text-align: center;
            margin-bottom: 2rem;
            color: #0d6efd;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="brand-logo">
            <h2>Happy Skies Travel</h2>
            <p class="text-muted">Accedi al gestionale</p>
        </div>

        <?php if($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required placeholder="admin@tripagency.com">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="admin123">
            </div>
            <div class="mb-3">
                <label class="form-label d-block">Accedi come:</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="role" id="roleAdmin" value="admin" checked>
                    <label class="form-check-label" for="roleAdmin">Admin</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="role" id="roleStaff" value="staff">
                    <label class="form-check-label" for="roleStaff">Dipendente</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Accedi</button>
            <div class="text-center mt-3">
                <a href="register.php" class="text-decoration-none">Registra nuovo utente</a>
            </div>
        </form>
    </div>

</body>
</html>

<?php
include 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<div class='alert alert-danger'>Email non valida.</div>";
    } elseif (strlen($password) < 6) {
        $message = "<div class='alert alert-danger'>La password deve essere di almeno 6 caratteri.</div>";
    } else {
        // Check if user exists
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $res = $check->get_result();

        if ($res->num_rows > 0) {
            $message = "<div class='alert alert-warning'>Utente già registrato con questa email.</div>";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $email, $hashed_password, $role);
            
            if ($stmt->execute()) {
                $message = "<div class='alert alert-success'>Utente registrato con successo! <a href='login.php'>Torna al Login</a></div>";
            } else {
                $message = "<div class='alert alert-danger'>Errore nel database: " . $conn->error . "</div>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione - Happy Skies Travel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .register-card {
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

    <div class="register-card">
        <div class="brand-logo">
            <h2>Registrazione</h2>
            <p class="text-muted">Crea un nuovo account</p>
        </div>

        <?= $message ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required placeholder="email@esempio.com">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="Minimo 6 caratteri">
            </div>
            <div class="mb-3">
                <label class="form-label">Ruolo</label>
                <select name="role" class="form-select">
                    <option value="staff">Dipendente</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success w-100">Registra</button>
            <div class="text-center mt-3">
                <a href="login.php">Hai già un account? Accedi</a>
            </div>
        </form>
    </div>

</body>
</html>

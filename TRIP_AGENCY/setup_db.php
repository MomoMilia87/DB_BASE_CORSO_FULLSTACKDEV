<?php
include 'db.php';

$message = "";

// Create Users Table if not exists
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'staff') DEFAULT 'staff',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!$conn->query($sql)) {
    die("Errore nella creazione tabella users: " . $conn->error);
}

// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role']; // Recupero il ruolo dal form

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<div class='alert alert-danger'>Email non valida.</div>";
    } elseif (strlen($password) < 6) {
        $message = "<div class='alert alert-danger'>La password deve essere di almeno 6 caratteri.</div>";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if user exists
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $res = $check->get_result();

        if ($res->num_rows == 0) {
            // INSERT
            $stmt = $conn->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $email, $hashed_password, $role);
            if ($stmt->execute()) {
                $message = "<div class='alert alert-success'>Utente creato con successo! <br> <a href='login.php'>Vai al Login</a></div>";
            } else {
                $message = "<div class='alert alert-danger'>Errore: " . $stmt->error . "</div>";
            }
        } else {
            // UPDATE
            $stmt = $conn->prepare("UPDATE users SET password = ?, role = ? WHERE email = ?");
            $stmt->bind_param("sss", $hashed_password, $role, $email);
            $stmt->execute();
            $message = "<div class='alert alert-success'>Utente esistente aggiornato! Password e ruolo modificati. <br> <a href='login.php'>Vai al Login</a></div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Setup Admin - Happy Skies Travel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FONTS -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; font-family: 'Outfit', sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .card { border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: none; padding: 2rem; max-width: 500px; width: 100%; }
        h2 { color: #3a0ca3; font-weight: 800; }
    </style>
</head>
<body>

<div class="card">
    <h2 class="text-center mb-4">Setup Admin</h2>
    
    <?= $message ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label fw-bold">Email Utente</label>
            <input type="email" name="email" class="form-control" required placeholder="es. admin@tripagency.com">
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Password</label>
            <input type="password" name="password" class="form-control" required placeholder="Inserisci una password sicura">
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Ruolo</label>
            <select name="role" class="form-select">
                <option value="admin">Admin</option>
                <option value="staff">Dipendente</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary w-100 fw-bold py-2">Crea / Aggiorna Utente</button>
    </form>
</div>

</body>
</html>

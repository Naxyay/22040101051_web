<?php
session_start();
require 'db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $pass = $_POST['password'];
    
    // Şifreleme
    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

    // E-posta kontrol
    $check = $conn->query("SELECT id FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        $message = "Bu e-posta adresi zaten kullanımda.";
    } else {
        $sql = "INSERT INTO users (username, email, password) VALUES ('$user', '$email', '$hashed_password')";
        if ($conn->query($sql) === TRUE) {
            header("Location: login.php?status=registered");
            exit();
        } else {
            $message = "Kayıt hatası: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kayıt Ol - HeartRisk AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="card card-custom">
                <div class="card-header-custom bg-success"> <h3 class="mb-0"><i class="fa-solid fa-user-plus"></i> Kayıt Ol</h3>
                </div>
                <div class="card-body p-4">
                    
                    <?php if($message): ?>
                        <div class="alert alert-danger text-center"><?php echo $message; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Ad Soyad</label>
                            <input type="text" name="username" class="form-control" placeholder="Adınız" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">E-Posta Adresi</label>
                            <input type="email" name="email" class="form-control" placeholder="ornek@email.com" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Şifre</label>
                            <input type="password" name="password" class="form-control" placeholder="******" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Üye Ol</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-white text-center py-3 border-0">
                    <p class="mb-0 text-muted">Zaten üye misiniz? <a href="login.php" class="text-decoration-none fw-bold">Giriş Yap</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
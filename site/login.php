<?php
session_start();
require 'db.php';

// Zaten giriş yapmışsa ana sayfaya yolla
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $pass = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email='$email'");

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($pass, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header("Location: index.php");
            exit();
        } else {
            $message = "Hatalı şifre girdiniz!";
        }
    } else {
        $message = "Bu e-posta ile kayıtlı kullanıcı bulunamadı.";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Giriş Yap - HeartRisk AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="card card-custom">
                <div class="card-header-custom">
                    <h3 class="mb-0"><i class="fa-solid fa-right-to-bracket"></i> Giriş Yap</h3>
                </div>
                <div class="card-body p-4">
                    
                    <?php if($message): ?>
                        <div class="alert alert-danger text-center" role="alert">
                            <?php echo $message; ?>
                        </div>
                    <?php endif; ?>

                    <?php if(isset($_GET['status']) && $_GET['status'] == 'registered'): ?>
                        <div class="alert alert-success text-center">Kayıt başarılı! Şimdi giriş yapabilirsiniz.</div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">E-Posta Adresi</label>
                            <input type="email" name="email" class="form-control" placeholder="ornek@email.com" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Şifre</label>
                            <input type="password" name="password" class="form-control" placeholder="******" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Giriş Yap</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-white text-center py-3 border-0">
                    <p class="mb-0 text-muted">Hesabınız yok mu? <a href="register.php" class="text-decoration-none fw-bold">Kayıt Ol</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php
session_start();

// --- TARAYICI ÖNBELLEĞİNİ KAPATMA ---
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM analiz_sonuclar WHERE user_id = $user_id ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Profilim - HeartRisk AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-danger" href="index.php">HeartRisk AI</a>
            <div class="ms-auto">
                <a href="index.php" class="btn btn-outline-primary btn-sm me-2">Yeni Analiz</a>
                <a href="logout.php" class="btn btn-danger btn-sm">Çıkış</a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row">
            <div class="col-md-4">
                <div class="card card-custom p-4 text-center mb-4">
                    <div class="mb-3"><i class="fa-solid fa-user-circle fa-5x text-secondary"></i></div>
                    <h4><?php echo htmlspecialchars($_SESSION['username']); ?></h4>
                    <p class="text-muted">Kayıtlı Kullanıcı</p>
                    <hr>
                    <div class="d-grid"><a href="index.php" class="btn btn-primary">Yeni Analiz Başlat</a></div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card card-custom p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="fw-bold mb-0">Geçmiş Analizler</h4>
                        <input type="text" id="tableSearch" class="form-control w-50" placeholder="Ara...">
                    </div>

                    <?php if ($result->num_rows > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="historyTable">
                                <thead class="table-light">
                                    <tr>
                                        <th onclick="sortTable(0)" style="cursor:pointer">Tarih <i class="fa-solid fa-sort small ms-1"></i></th>
                                        <th onclick="sortTable(1)" style="cursor:pointer">Risk Skoru <i class="fa-solid fa-sort small ms-1"></i></th>
                                        <th onclick="sortTable(2)" style="cursor:pointer">Durum <i class="fa-solid fa-sort small ms-1"></i></th>
                                        <th>Detay</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = $result->fetch_assoc()): 
                                        $ham_tarih = isset($row['tarih']) ? $row['tarih'] : null;
                                        $tarih_goster = $ham_tarih ? date("d.m.Y H:i", strtotime($ham_tarih)) : '-';
                                        $yuzde = number_format($row['risk_skoru'] * 100, 1);
                                        $renk = ($row['risk_skoru'] > 0.30) ? 'text-danger' : 'text-success';
                                        $ikon = ($row['risk_skoru'] > 0.30) ? 'fa-triangle-exclamation' : 'fa-check';
                                    ?>
                                    <tr>
                                        <td><?php echo $tarih_goster; ?></td>
                                        <td class="fw-bold"><?php echo "%" . $yuzde; ?></td>
                                        <td class="<?php echo $renk; ?>">
                                            <i class="fa-solid <?php echo $ikon; ?>"></i> 
                                            <?php echo htmlspecialchars($row['sonuc_tahmini']); ?>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                BMI: <?php echo isset($row['bmi']) ? number_format($row['bmi'], 1) : '0'; ?> | 
                                                Sigara: <?php echo ($row['sigara_durumu']==0 ? 'Yok' : 'Var'); ?>
                                            </small>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">Henüz kayıt yok.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <footer class="pt-5 pb-2">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5 class="text-white"><i class="fa-solid fa-heart-pulse text-danger"></i> HeartRisk AI</h5>
                    <p class="small">Yapay zeka ile kalp krizi risk analizi.</p>
                    <div class="alert alert-dark p-2 small border-secondary">
                        <i class="fa-solid fa-triangle-exclamation text-warning me-1"></i>
                        <strong>Yasal Uyarı:</strong> Sonuçlar bilgilendirme amaçlıdır.
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Menü</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" data-bs-toggle="modal" data-bs-target="#gizlilikModal">Gizlilik</a></li>
                        <li class="mb-2"><a href="#" data-bs-toggle="modal" data-bs-target="#nasilCalisirModal">Nasıl Çalışır?</a></li>
                        <li class="mb-2"><a href="#" data-bs-toggle="modal" data-bs-target="#iletisimModal">İletişim</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5>Sponsorlu</h5>
                    <div class="ad-box"><span class="ad-badge">REKLAM</span>
                        <h6 class="text-white my-1">Özel Hastane</h6>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5>Sosyal</h5>
                    <div class="mt-2">
                        <a href="#" class="social-icon text-white"><i class="fa-brands fa-twitter"></i></a>
                        <a href="#" class="social-icon text-white"><i class="fa-brands fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <hr class="border-secondary mt-4">
            <div class="text-center">
                <p class="small mb-0">&copy; 2026 HeartRisk AI.</p>
            </div>
        </div>
    </footer>

    <div class="modal fade" id="gizlilikModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title">Gizlilik</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Verileriniz şifreli saklanır ve 3. şahıslarla paylaşılmaz.</p>
                </div>
                <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button></div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="nasilCalisirModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Nasıl Çalışır?</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>CDC verileriyle eğitilmiş Yapay Zeka modeli riskinizi hesaplar.</p>
                </div>
                <div class="modal-footer"><button class="btn btn-primary" data-bs-dismiss="modal">Tamam</button></div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="iletisimModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">İletişim</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="contactForm">
                        <div class="mb-3"><label>Konu</label><select class="form-select">
                                <option>Destek</option>
                                <option>Öneri</option>
                            </select></div>
                        <div class="mb-3"><label>Mesaj</label><textarea class="form-control" rows="3" required></textarea></div>
                        <div class="d-grid"><button type="submit" class="btn btn-danger">Gönder</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>
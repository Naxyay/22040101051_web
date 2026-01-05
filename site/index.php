<?php
session_start();

// --- TARAYICI ÖNBELLEĞİNİ KAPATMA (Bug Fix) ---
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tam Kapsamlı Analiz - HeartRisk AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-danger" href="index.php">
                <i class="fa-solid fa-heart-pulse"></i> HeartRisk AI Pro
            </a>
            <div class="ms-auto d-flex align-items-center">
                <span class="me-3 d-none d-md-block text-secondary">
                    👤 Merhaba, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
                </span>
                <a href="profile.php" class="btn btn-sm btn-outline-primary me-2">
                    <i class="fa-solid fa-user"></i> Profilim
                </a>
                <a href="logout.php" class="btn btn-sm btn-danger">
                    <i class="fa-solid fa-right-from-bracket"></i> Çıkış
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4">
        <div class="row">

            <div class="col-lg-2 d-none d-xxl-block">
                <div class="side-ad-container">
                    <div class="skyscraper-ad" onclick="window.open('https://www.google.com/search?q=sağlıklı+yaşam', '_blank')">
                        <span class="ad-label">REKLAM</span>
                        <i class="fa-solid fa-apple-whole fa-4x text-success mb-3"></i>
                        <div class="ad-content">
                            <h6>Organik Yaşam</h6>
                            <p class="small text-muted">Kalbiniz için doğal beslenin.</p>
                            <button class="ad-btn">İncele</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xxl-8">
                <div class="container">

                    <div id="saglikSlider" class="carousel slide mb-4 rounded overflow-hidden shadow-sm" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#saglikSlider" data-bs-slide-to="0" class="active"></button>
                            <button type="button" data-bs-target="#saglikSlider" data-bs-slide-to="1"></button>
                            <button type="button" data-bs-target="#saglikSlider" data-bs-slide-to="2"></button>
                        </div>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <div class="d-flex align-items-center justify-content-center bg-primary text-white p-4" style="height: 200px;">
                                    <div class="text-center">
                                        <i class="fa-solid fa-heart-pulse fa-2x mb-2"></i>
                                        <h5>Erken Teşhis</h5>
                                        <p class="small">Yapay zeka analizi ile riskleri önceden görün.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="d-flex align-items-center justify-content-center bg-success text-white p-4" style="height: 200px;">
                                    <div class="text-center">
                                        <i class="fa-solid fa-carrot fa-2x mb-2"></i>
                                        <h5>Sağlıklı Beslenme</h5>
                                        <p class="small">Akdeniz tipi beslenme kalp dostudur.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="d-flex align-items-center justify-content-center bg-warning text-dark p-4" style="height: 200px;">
                                    <div class="text-center">
                                        <i class="fa-solid fa-person-running fa-2x mb-2"></i>
                                        <h5>Hareket Et</h5>
                                        <p class="small">Günde 30 dakika yürüyüş ömrü uzatır.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-custom p-4">
                        <h2 class="text-center mb-4 text-primary fw-bold">Detaylı Sağlık Taraması</h2>
                        <p class="text-center text-muted mb-4">39 parametreli yapay zeka analizi için formu doldurun.</p>

                        <<form id="healthForm">

                <h5 class="text-primary border-bottom pb-2">1. Demografik Bilgiler</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label>Yaş Grubu</label>
                        <select id="AgeCategory" class="form-select">
                            <option value="0">18–24</option>
                            <option value="1">25–29</option>
                            <option value="2">30–34</option>
                            <option value="3">35–39</option>
                            <option value="4">40–44</option>
                            <option value="5">45–49</option>
                            <option value="6">50–54</option>
                            <option value="7">55–59</option>
                            <option value="8">60–64</option>
                            <option value="9">65–69</option>
                            <option value="10">70–74</option>
                            <option value="11">75–79</option>
                            <option value="12">80–84</option>
                            <option value="13">85+</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Cinsiyet</label>
                        <select id="Sex" class="form-select">
                            <option value="1">Erkek</option>
                            <option value="0">Kadın</option>
                        </select>
                    </div>
                </div>

                <h5 class="text-primary border-bottom pb-2">2. Fiziksel Bilgiler</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label>Boy (cm)</label>
                        <input type="number" id="HeightCM" class="form-control" placeholder="175" required>
                    </div>
                    <div class="col-md-4">
                        <label>Kilo (kg)</label>
                        <input type="number" id="WeightKG" class="form-control" placeholder="80" required>
                    </div>
                    <div class="col-md-4">
                        <label>Ortalama Uyku Saati</label>
                        <input type="number" id="SleepHours" class="form-control" value="7">
                    </div>
                </div>

                <h5 class="text-danger border-bottom pb-2">3. Sağlık Geçmişi</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="fw-bold">Genel Sağlık Durumu</label>
                        <select id="GeneralHealth" class="form-select">
                            <option value="0">Mükemmel</option>
                            <option value="4">Çok İyi</option>
                            <option value="2">İyi</option>
                            <option value="1">Orta</option>
                            <option value="3">Kötü (Riskli)</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold">Diyabet</label>
                        <select id="HadDiabetes" class="form-select">
                            <option value="0">Yok</option>
                            <option value="1">Sınırda / Gizli Şeker</option>
                            <option value="3">Gebelik Şekeri</option>
                            <option value="2">Var (Tip 1 veya 2)</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-3"><label>Göğüs Ağrısı</label><select id="HadAngina" class="form-select"><option value="0">Hayır</option><option value="1">Evet</option></select></div>
                    <div class="col-md-3"><label>İnme (Felç)</label><select id="HadStroke" class="form-select"><option value="0">Hayır</option><option value="1">Evet</option></select></div>
                    <div class="col-md-3"><label>Astım</label><select id="HadAsthma" class="form-select"><option value="0">Hayır</option><option value="1">Evet</option></select></div>
                    <div class="col-md-3"><label>KOAH</label><select id="HadCOPD" class="form-select"><option value="0">Hayır</option><option value="1">Evet</option></select></div>
                    <div class="col-md-3"><label>Cilt Kanseri</label><select id="HadSkinCancer" class="form-select"><option value="0">Hayır</option><option value="1">Evet</option></select></div>
                    <div class="col-md-3"><label>Depresyon</label><select id="HadDepressiveDisorder" class="form-select"><option value="0">Hayır</option><option value="1">Evet</option></select></div>
                    <div class="col-md-3"><label>Böbrek Hastalığı</label><select id="HadKidneyDisease" class="form-select"><option value="0">Hayır</option><option value="1">Evet</option></select></div>
                    <div class="col-md-3"><label>Romatizma (Artrit)</label><select id="HadArthritis" class="form-select"><option value="0">Hayır</option><option value="1">Evet</option></select></div>
                </div>

                <h5 class="text-success border-bottom pb-2">4. Yaşam Tarzı ve Alışkanlıklar</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label>Sigara Kullanımı</label>
                        <select id="SmokerStatus" class="form-select">
                            <option value="0">Aktif İçici (Her Gün)</option>
                            <option value="2">Ara Sıra</option>
                            <option value="1">Bıraktım</option>
                            <option value="4">Çok Nadir</option>
                            <option value="3">Hiç İçmedim</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Fiziksel Aktivite</label>
                        <select id="PhysicalActivities" class="form-select">
                            <option value="1">Yapıyorum</option>
                            <option value="0">Yapmıyorum</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Son Check-up</label>
                        <select id="LastCheckupTime" class="form-select">
                            <option value="0">Son 1 yıl içinde</option>
                            <option value="1">1-2 yıl önce</option>
                            <option value="2">2-5 yıl önce</option>
                            <option value="3">5+ yıl önce</option>
                            <option value="4">Hiç gitmedim</option>
                        </select>
                    </div>
                    <div class="col-md-3"><label>Alkol Tüketimi</label><select id="AlcoholDrinkers" class="form-select"><option value="0">Hayır</option><option value="1">Evet</option></select></div>
                    <div class="col-md-3"><label>E-Sigara</label><select id="ECigaretteUsage" class="form-select"><option value="0">Hayır</option><option value="1">Evet</option></select></div>
                    <div class="col-md-3"><label>Geçen Yıl Risk Uyarısı?</label><select id="HighRiskLastYear" class="form-select"><option value="0">Hayır</option><option value="1">Evet</option></select></div>
                    <div class="col-md-3"><label>Diş Çekimi (Son Yıl)</label><select id="RemovedTeeth" class="form-select"><option value="0">Hayır</option><option value="1">Evet</option></select></div>
                </div>
                
                <h5 class="text-secondary border-bottom pb-2">5. Fiziksel Zorluklar</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-4"><label>Yürüme Zorluğu</label><select id="DifficultyWalking" class="form-select"><option value="0">Hayır</option><option value="1">Evet</option></select></div>
                    <div class="col-md-4"><label>Giyinme/Banyo Zorluğu</label><select id="DifficultyDressingBathing" class="form-select"><option value="0">Hayır</option><option value="1">Evet</option></select></div>
                    <div class="col-md-4"><label>İş/Alışveriş Zorluğu</label><select id="DifficultyErrands" class="form-select"><option value="0">Hayır</option><option value="1">Evet</option></select></div>
                    
                    <div class="col-md-3"><label>Odaklanma Sorunu</label><select id="DifficultyConcentrating" class="form-select"><option value="0">Hayır</option><option value="1">Evet</option></select></div>
                    <div class="col-md-3"><label>İşitme Sorunu</label><select id="DeafOrHardOfHearing" class="form-select"><option value="0">Hayır</option><option value="1">Evet</option></select></div>
                    <div class="col-md-3"><label>Görme Sorunu</label><select id="BlindOrVisionDifficulty" class="form-select"><option value="0">Hayır</option><option value="1">Evet</option></select></div>
                    <div class="col-md-3"><label>Göğüs Filmi (BT)</label><select id="ChestScan" class="form-select"><option value="0">Hayır</option><option value="1">Evet</option></select></div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6"><label>Son 30 günde kaç gün fiziksel olarak kötü hissettiniz?</label><input type="number" id="PhysicalHealthDays" class="form-control" value="0" min="0" max="30"></div>
                    <div class="col-md-6"><label>Son 30 günde kaç gün mutsuz/depresif hissettiniz?</label><input type="number" id="MentalHealthDays" class="form-control" value="0" min="0" max="30"></div>
                </div>

                <h5 class="text-info border-bottom pb-2">6. Aşılar ve Testler</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-3"><label>Covid Pozitif</label><select id="CovidPos" class="form-select"><option value="0">Hayır</option><option value="1">Evet</option></select></div>
                    <div class="col-md-3"><label>Grip Aşısı (Son 1 Yıl)</label><select id="FluVaxLast12" class="form-select"><option value="0">Hayır</option><option value="1">Evet</option></select></div>
                    <div class="col-md-3"><label>Zatürre Aşısı</label><select id="PneumoVaxEver" class="form-select"><option value="0">Hayır</option><option value="1">Evet</option></select></div>
                    <div class="col-md-3"><label>Tetanoz Aşısı</label><select id="TetanusLast10Tdap" class="form-select"><option value="0">Hayır</option><option value="1">Evet</option></select></div>
                    <div class="col-md-3"><label>HIV Testi</label><select id="HIVTesting" class="form-select"><option value="0">Hayır</option><option value="1">Evet</option></select></div>
                </div>

                <div class="d-grid mt-3">
                    <button type="submit" class="btn btn-primary btn-lg">Tahmini Hesapla</button>
                </div>

            </form>


                        <div id="sonucKutusu" class="result-box mt-4" style="display:none;">
                            <h2 id="sonucBaslik" class="fw-bold"></h2>
                            <div class="display-4 fw-bold" id="riskOrani"></div>
                            <p id="sonucMesaj" class="fs-5"></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 d-none d-xxl-block">
                <div class="side-ad-container">
                    <div class="skyscraper-ad" onclick="window.open('https://www.google.com/search?q=kardiyoloji', '_blank')">
                        <span class="ad-label">REKLAM</span>
                        <i class="fa-solid fa-heart-circle-check fa-4x text-danger mb-3"></i>
                        <div class="ad-content">
                            <h6>Check-Up Paketi</h6>
                            <p class="small text-muted">%20 İndirim Fırsatı.</p>
                            <button class="ad-btn">Randevu Al</button>
                        </div>
                    </div>
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
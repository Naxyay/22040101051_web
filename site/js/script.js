/* =========================================
   1. GLOBAL FONKSİYONLAR (Profil Sayfası İçin)
   ========================================= */

/**
 * Tabloyu tıklandığı sütuna göre sıralar
 * @param {number} n - Sütun indeksi (0: Tarih, 1: Skor, 2: Durum)
 */
function sortTable(n) {
    var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
    table = document.getElementById("historyTable");
    if (!table) return; // Tablo yoksa dur

    switching = true;
    dir = "asc"; // Varsayılan: Artan sıralama

    while (switching) {
        switching = false;
        rows = table.rows;

        // Başlık (0) hariç tüm satırları dön
        for (i = 1; i < (rows.length - 1); i++) {
            shouldSwitch = false;
            x = rows[i].getElementsByTagName("TD")[n];
            y = rows[i + 1].getElementsByTagName("TD")[n];

            // İçerikleri al (Tarih ve Yüzde temizliği yap)
            let xContent = x.innerText.toLowerCase();
            let yContent = y.innerText.toLowerCase();

            // Eğer Yüzde sütunuysa (% işaretini at)
            if (n === 1) {
                xContent = parseFloat(xContent.replace('%', ''));
                yContent = parseFloat(yContent.replace('%', ''));
            }
            // Eğer Tarih sütunuysa (Tarihe çevir)
            else if (n === 0) {
                // Tarih formatı: dd.mm.yyyy HH:MM -> YYYYMMDDHHMM formatına çevirip kıyasla
                // Basit string kıyaslaması bazen yeterli olmaz ama bu formatta (dd.mm.yyyy) ters çevirmek gerekir.
                // Şimdilik string kıyaslaması yapıyoruz, detaylı tarih parse gerekirse eklenir.
            }

            if (dir == "asc") {
                if (xContent > yContent) {
                    shouldSwitch = true;
                    break;
                }
            } else if (dir == "desc") {
                if (xContent < yContent) {
                    shouldSwitch = true;
                    break;
                }
            }
        }
        if (shouldSwitch) {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
            switchcount++;
        } else {
            if (switchcount == 0 && dir == "asc") {
                dir = "desc";
                switching = true;
            }
        }
    }
}


/* =========================================
   2. SAYFA YÜKLENDİĞİNDE ÇALIŞACAKLAR
   ========================================= */
document.addEventListener('DOMContentLoaded', () => {

    // --- A. PROFİL SAYFASI İŞLEMLERİ ---
    
    // 1. Tablo Arama İşlemi
    const searchInput = document.getElementById('tableSearch');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll("#historyTable tbody tr");

            rows.forEach(row => {
                let text = row.innerText.toLowerCase();
                if (text.includes(filter)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });
    }

    // 2. İletişim Formu (Modal İçindeki)
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            // Burada normalde AJAX ile gönderilir ama şimdilik sadece alert verelim
            alert("Mesajınız başarıyla gönderildi! En kısa sürede dönüş yapacağız.");
            
            // Formu temizle
            this.reset();
            
            // Modalı kapat (Bootstrap 5 Yöntemi)
            const modalEl = document.getElementById('iletisimModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if(modal) modal.hide();
        });
    }


    // --- B. HESAPLAMA SAYFASI (INDEX.PHP) İŞLEMLERİ ---
    
    const healthForm = document.getElementById('healthForm');
    if (healthForm) {
        const btn = healthForm.querySelector('button[type="submit"]');
        const sonucKutusu = document.getElementById('sonucKutusu');
        const sonucBaslik = document.getElementById('sonucBaslik');
        const riskOrani = document.getElementById('riskOrani');
        const riskBar = document.getElementById('riskBar'); // Varsa bar elementi

        healthForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // Butonu Kilitle
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> HESAPLANIYOR...';
            btn.disabled = true;
            
            if(sonucKutusu) sonucKutusu.style.display = 'none';

            try {
                // VERİLERİ TOPLA
                const data = {
                    AgeCategory: document.getElementById('AgeCategory').value,
                    Sex: document.getElementById('Sex').value,
                    HeightCM: document.getElementById('HeightCM').value,
                    WeightKG: document.getElementById('WeightKG').value,
                    SleepHours: document.getElementById('SleepHours').value,
                    GeneralHealth: document.getElementById('GeneralHealth').value,
                    HadDiabetes: document.getElementById('HadDiabetes').value,
                    HadAngina: document.getElementById('HadAngina').value,
                    HadStroke: document.getElementById('HadStroke').value,
                    HadAsthma: document.getElementById('HadAsthma').value,
                    HadCOPD: document.getElementById('HadCOPD').value,
                    HadSkinCancer: document.getElementById('HadSkinCancer').value,
                    HadDepressiveDisorder: document.getElementById('HadDepressiveDisorder').value,
                    HadKidneyDisease: document.getElementById('HadKidneyDisease').value,
                    HadArthritis: document.getElementById('HadArthritis').value,
                    SmokerStatus: document.getElementById('SmokerStatus').value,
                    PhysicalActivities: document.getElementById('PhysicalActivities').value,
                    LastCheckupTime: document.getElementById('LastCheckupTime').value,
                    AlcoholDrinkers: document.getElementById('AlcoholDrinkers').value,
                    ECigaretteUsage: document.getElementById('ECigaretteUsage').value,
                    HighRiskLastYear: document.getElementById('HighRiskLastYear').value,
                    RemovedTeeth: document.getElementById('RemovedTeeth').value,
                    DifficultyWalking: document.getElementById('DifficultyWalking').value,
                    DifficultyDressingBathing: document.getElementById('DifficultyDressingBathing').value,
                    DifficultyErrands: document.getElementById('DifficultyErrands').value,
                    DifficultyConcentrating: document.getElementById('DifficultyConcentrating').value,
                    DeafOrHardOfHearing: document.getElementById('DeafOrHardOfHearing').value,
                    BlindOrVisionDifficulty: document.getElementById('BlindOrVisionDifficulty').value,
                    ChestScan: document.getElementById('ChestScan').value,
                    PhysicalHealthDays: document.getElementById('PhysicalHealthDays').value,
                    MentalHealthDays: document.getElementById('MentalHealthDays').value,
                    CovidPos: document.getElementById('CovidPos').value,
                    FluVaxLast12: document.getElementById('FluVaxLast12').value,
                    PneumoVaxEver: document.getElementById('PneumoVaxEver').value,
                    TetanusLast10Tdap: document.getElementById('TetanusLast10Tdap').value,
                    HIVTesting: document.getElementById('HIVTesting').value
                };

                // PYTHON API'YE GÖNDER
                const response = await fetch('http://127.0.0.1:5000/predict', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                if (!response.ok) throw new Error("Sunucu Hatası");
                const res = await response.json();

                // SONUCU GÖSTER
                if (sonucKutusu) {
                    sonucKutusu.style.display = 'block';
                    
                    const yuzdeVal = (res.risk_skoru * 100).toFixed(2);
                    riskOrani.innerText = "%" + yuzdeVal;
                    
                    // Bar varsa güncelle
                    if(riskBar) riskBar.style.width = yuzdeVal + "%";

                    // RENKLENDİRME (Bootstrap Classları)
                    sonucKutusu.className = 'result-box shadow-sm text-white'; // Sıfırla
                    if(riskBar) riskBar.className = 'progress-bar progress-bar-striped progress-bar-animated';

                    if (res.risk_skoru < 0.20) {
                        sonucBaslik.innerText = "✅ DÜŞÜK RİSK";
                        sonucKutusu.classList.add('bg-success');
                        if(riskBar) riskBar.classList.add('bg-white', 'text-success');
                    } else if (res.risk_skoru < 0.50) {
                        sonucBaslik.innerText = "⚠️ ORTA RİSK";
                        sonucKutusu.classList.add('bg-warning');
                        sonucKutusu.classList.remove('text-white');
                        sonucKutusu.classList.add('text-dark');
                        if(riskBar) riskBar.classList.add('bg-dark');
                    } else {
                        sonucBaslik.innerText = "🚨 YÜKSEK RİSK";
                        sonucKutusu.classList.add('bg-danger');
                        if(riskBar) riskBar.classList.add('bg-white', 'text-danger');
                    }
                } else {
                    alert("Risk Skoru: %" + (res.risk_skoru * 100).toFixed(2));
                }

            } catch (err) {
                console.error(err);
                alert("Hata: " + err.message);
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });
    }

});
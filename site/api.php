<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

require 'db.php';

// JSON Verisini Al
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true);

if (!$input) {
    echo json_encode(["status" => "error", "message" => "Veri alınamadı."]);
    exit();
}

// 1. ÖNCE PYTHON'A GÖNDER VE SONUCU AL
$ch = curl_init('http://127.0.0.1:5000/predict');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($input));
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$pythonResponse = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode(["status" => "error", "message" => "Python bağlantı hatası: " . curl_error($ch)]);
    exit();
}
curl_close($ch);

$result = json_decode($pythonResponse, true);
if (!$result || (isset($result['status']) && $result['status'] == 'error')) {
    $msg = isset($result['message']) ? $result['message'] : "Python hatası.";
    echo json_encode(["status" => "error", "message" => $msg]);
    exit();
}

// Python'dan gelen sonuçlar
$risk_skoru = $result['risk_skoru'];
$sonuc_tahmini = $result['mesaj']; // "Yüksek Risk" vs.

// 2. VERİTABANI İÇİN DEĞİŞKENLERİ HAZIRLA (MAPPING)
// Frontend (İngilizce Key) -> DB (Türkçe Sütun)

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "NULL";

// --- TEMEL ---
$bmi = isset($input['BMI']) ? $input['BMI'] : 0;
$boy = isset($input['HeightCM']) ? $input['HeightCM'] : 0;
$kilo = isset($input['WeightKG']) ? $input['WeightKG'] : 0;
$cinsiyet = isset($input['Sex']) ? $input['Sex'] : 0;
$yas_grubu = isset($input['AgeCategory']) ? $input['AgeCategory'] : 0; // DB'de sütun adı yoksa eklenmeli, varsa buraya yazılmalı. Şimdilik pas geçiyorum veya varsa ekle.

// --- SAĞLIK ---
$genel_saglik = isset($input['GeneralHealth']) ? $input['GeneralHealth'] : 0;
$fiziksel_gun = isset($input['PhysicalHealthDays']) ? $input['PhysicalHealthDays'] : 0;
$mental_gun = isset($input['MentalHealthDays']) ? $input['MentalHealthDays'] : 0;
$uyku = isset($input['SleepHours']) ? $input['SleepHours'] : 0;

// --- ALIŞKANLIKLAR ---
$sigara = isset($input['SmokerStatus']) ? $input['SmokerStatus'] : 0;
$e_sigara = isset($input['ECigaretteUsage']) ? $input['ECigaretteUsage'] : 0;
$alkol = isset($input['AlcoholDrinkers']) ? $input['AlcoholDrinkers'] : 0;
$fiziksel_akt = isset($input['PhysicalActivities']) ? $input['PhysicalActivities'] : 0;

// --- HASTALIKLAR ---
$kalp_agrisi = isset($input['HadAngina']) ? $input['HadAngina'] : 0;
$felc = isset($input['HadStroke']) ? $input['HadStroke'] : 0;
$astim = isset($input['HadAsthma']) ? $input['HadAsthma'] : 0;
$cilt_kanseri = isset($input['HadSkinCancer']) ? $input['HadSkinCancer'] : 0;
$koah = isset($input['HadCOPD']) ? $input['HadCOPD'] : 0;
$depresyon = isset($input['HadDepressiveDisorder']) ? $input['HadDepressiveDisorder'] : 0;
$bobrek = isset($input['HadKidneyDisease']) ? $input['HadKidneyDisease'] : 0;
$romatizma = isset($input['HadArthritis']) ? $input['HadArthritis'] : 0;
$diyabet = isset($input['HadDiabetes']) ? $input['HadDiabetes'] : 0;

// --- ZORLUKLAR ---
$yurume = isset($input['DifficultyWalking']) ? $input['DifficultyWalking'] : 0;
$banyo = isset($input['DifficultyDressingBathing']) ? $input['DifficultyDressingBathing'] : 0;
$is_zorlugu = isset($input['DifficultyErrands']) ? $input['DifficultyErrands'] : 0;
$odaklanma = isset($input['DifficultyConcentrating']) ? $input['DifficultyConcentrating'] : 0;
$isitme = isset($input['DeafOrHardOfHearing']) ? $input['DeafOrHardOfHearing'] : 0;
$gorme = isset($input['BlindOrVisionDifficulty']) ? $input['BlindOrVisionDifficulty'] : 0;

// --- DİĞER ---
$dis_cekimi = isset($input['RemovedTeeth']) ? $input['RemovedTeeth'] : 0;
$gogus_filmi = isset($input['ChestScan']) ? $input['ChestScan'] : 0;

// --- AŞILAR ---
$covid = isset($input['CovidPos']) ? $input['CovidPos'] : 0;
$hiv = isset($input['HIVTesting']) ? $input['HIVTesting'] : 0;
$grip = isset($input['FluVaxLast12']) ? $input['FluVaxLast12'] : 0;
$zaturre = isset($input['PneumoVaxEver']) ? $input['PneumoVaxEver'] : 0;
$tetanos = isset($input['TetanusLast10Tdap']) ? $input['TetanusLast10Tdap'] : 0;


// 3. SQL INSERT SORGUSU (TÜM SÜTUNLAR DOLACAK)
// DİKKAT: Veritabanındaki sütun adlarının bunlarla BİREBİR aynı olması lazım.
// Eğer "Column not found" hatası alırsan SQL tablosunda eksik sütun var demektir.

$sql = "INSERT INTO analiz_sonuclar (
    user_id, risk_skoru, sonuc_tahmini,
    bmi, boy, kilo, cinsiyet,
    genel_saglik, fiziksel_gun, mental_gun, uyku_saati,
    sigara_durumu, e_sigara, alkol, fiziksel_aktivite,
    gogus_agrisi, felc_gecmisi, astim_durumu, cilt_kanseri, koah, depresyon, bobrek_hastaligi, romatizma, diyabet,
    yurume_guclugu, banyo_zorlugu, is_zorlugu, konsantrasyon, isitme_sorunu, gorme_sorunu,
    dis_cekimi, gogus_filmi,
    covid_pozitif, hiv_testi, grip_asisi, zaturre_asisi, tetanos_asisi
) VALUES (
    '$user_id', '$risk_skoru', '$sonuc_tahmini',
    '$bmi', '$boy', '$kilo', '$cinsiyet',
    '$genel_saglik', '$fiziksel_gun', '$mental_gun', '$uyku',
    '$sigara', '$e_sigara', '$alkol', '$fiziksel_akt',
    '$kalp_agrisi', '$felc', '$astim', '$cilt_kanseri', '$koah', '$depresyon', '$bobrek', '$romatizma', '$diyabet',
    '$yurume', '$banyo', '$is_zorlugu', '$odaklanma', '$isitme', '$gorme',
    '$dis_cekimi', '$gogus_filmi',
    '$covid', '$hiv', '$grip', '$zaturre', '$tetanos'
)";

if ($conn->query($sql) === TRUE) {
    echo json_encode([
        "status" => "success",
        "risk_skoru" => $risk_skoru,
        "mesaj" => $sonuc_tahmini
    ]);
} else {
    // SQL hatası varsa ekrana bas ki görelim
    echo json_encode(["status" => "error", "message" => "SQL Hatası: " . $conn->error]);
}

$conn->close();
?>
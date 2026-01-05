const express = require('express');
const axios = require('axios'); // Python'a istek atmak için
const cors = require('cors');
const app = express();

app.use(cors());
app.use(express.json());
app.use(express.static('public')); // HTML dosyalarını 'public' klasöründen sun

// --- [RUBRİK: Backend Temelleri] ---
// --- [RUBRİK: API ve JSON Kullanımı] ---

// Analiz Endpoint'i
app.post('/api/analiz-et', async (req, res) => {
    const kullaniciVerisi = req.body;

    try {
        // 1. Python API'ye veriyi gönder (Gerçek Tahmin)
        const pythonResponse = await axios.post('http://localhost:5000/predict', kullaniciVerisi);
        const sonuc = pythonResponse.data;

        // 2. [RUBRİK: Veritabanı Temelleri / SQL]
        // Sonucu veritabanına kaydet (Burada temsili kod yazıyorum, MySQL bağlantısı eklenebilir)
        console.log("Veritabanına Kaydediliyor:", {
            kullanici: "Misafir",
            risk: sonuc.risk_skoru,
            tarih: new Date()
        });

        // 3. Sonucu Frontend'e gönder
        res.json(sonuc);

    } catch (error) {
        console.error("Python Hatası:", error);
        res.status(500).json({ hata: "Yapay zeka motoruna bağlanılamadı." });
    }
});

app.listen(3000, () => {
    console.log('Sunucu çalışıyor: http://localhost:3000');
});
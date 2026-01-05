from flask import Flask, request, jsonify
from flask_cors import CORS
import joblib
import pandas as pd
import numpy as np

app = Flask(__name__)
CORS(app)

# 1. Modeli Yükle
try:
    model = joblib.load('heart_model.pkl')
    scaler = joblib.load('scaler.pkl')
    print("✅ Model Yüklendi: FULL RÖNTGEN MODU (YENİ)")
except Exception as e:
    print(f"❌ HATA: {e}")

# 2. Beklenen 39 Sütun (Sırası Asla Değişmemeli)
expected_columns = [
    'State', 'Sex', 'GeneralHealth', 'PhysicalHealthDays', 'MentalHealthDays', 
    'LastCheckupTime', 'PhysicalActivities', 'SleepHours', 'RemovedTeeth', 
    'HadAngina', 'HadStroke', 'HadAsthma', 'HadSkinCancer', 
    'HadCOPD', 'HadDepressiveDisorder', 'HadKidneyDisease', 'HadArthritis', 
    'HadDiabetes', 'DeafOrHardOfHearing', 'BlindOrVisionDifficulty', 
    'DifficultyConcentrating', 'DifficultyWalking', 'DifficultyDressingBathing', 
    'DifficultyErrands', 'SmokerStatus', 'ECigaretteUsage', 'ChestScan', 
    'RaceEthnicityCategory', 'AgeCategory', 'HeightInMeters', 'WeightInKilograms', 
    'BMI', 'AlcoholDrinkers', 'HIVTesting', 'FluVaxLast12', 'PneumoVaxEver', 
    'TetanusLast10Tdap', 'HighRiskLastYear', 'CovidPos'
]

@app.route('/predict', methods=['POST'])
def predict():
    try:
        data = request.json
        
        # --- ÖNCE HESAPLAMALAR ---
        h_cm = float(data.get('HeightCM', 175))
        w_kg = float(data.get('WeightKG', 80))
        h_m = h_cm / 100.0
        bmi = w_kg / (h_m * h_m)

        # --- VERİ PAKETLEME (39 Sütun - Direkt Alıyoruz) ---
        input_dict = {
            'State': 0, 
            'Sex': float(data.get('Sex', 1)),
            'GeneralHealth': float(data.get('GeneralHealth', 0)),
            'PhysicalHealthDays': float(data.get('PhysicalHealthDays', 0)),
            'MentalHealthDays': float(data.get('MentalHealthDays', 0)),
            'LastCheckupTime': float(data.get('LastCheckupTime', 0)),
            'PhysicalActivities': float(data.get('PhysicalActivities', 1)),
            'SleepHours': float(data.get('SleepHours', 7)),
            'RemovedTeeth': float(data.get('RemovedTeeth', 0)),
            'HadAngina': float(data.get('HadAngina', 0)),
            'HadStroke': float(data.get('HadStroke', 0)),
            'HadAsthma': float(data.get('HadAsthma', 0)),
            'HadSkinCancer': float(data.get('HadSkinCancer', 0)),
            'HadCOPD': float(data.get('HadCOPD', 0)),
            'HadDepressiveDisorder': float(data.get('HadDepressiveDisorder', 0)),
            'HadKidneyDisease': float(data.get('HadKidneyDisease', 0)),
            'HadArthritis': float(data.get('HadArthritis', 0)),
            'HadDiabetes': float(data.get('HadDiabetes', 0)),
            'DeafOrHardOfHearing': float(data.get('DeafOrHardOfHearing', 0)),
            'BlindOrVisionDifficulty': float(data.get('BlindOrVisionDifficulty', 0)),
            'DifficultyConcentrating': float(data.get('DifficultyConcentrating', 0)),
            'DifficultyWalking': float(data.get('DifficultyWalking', 0)),
            'DifficultyDressingBathing': float(data.get('DifficultyDressingBathing', 0)),
            'DifficultyErrands': float(data.get('DifficultyErrands', 0)),
            'SmokerStatus': float(data.get('SmokerStatus', 0)),
            'ECigaretteUsage': float(data.get('ECigaretteUsage', 0)),
            'ChestScan': float(data.get('ChestScan', 0)),
            'RaceEthnicityCategory': 0,
            'AgeCategory': float(data.get('AgeCategory', 0)),
            'HeightInMeters': h_m,
            'WeightInKilograms': w_kg,
            'BMI': bmi,
            'AlcoholDrinkers': float(data.get('AlcoholDrinkers', 0)),
            'HIVTesting': float(data.get('HIVTesting', 0)),
            'FluVaxLast12': float(data.get('FluVaxLast12', 0)),
            'PneumoVaxEver': float(data.get('PneumoVaxEver', 0)),
            'TetanusLast10Tdap': float(data.get('TetanusLast10Tdap', 0)),
            'HighRiskLastYear': float(data.get('HighRiskLastYear', 0)),
            'CovidPos': float(data.get('CovidPos', 0))
        }

        # --- DETAYLI KONSOL ÇIKTISI (Burası Değişti) ---
        print("\n" + "="*60)
        print(f"📋 39 PARAMETRE KONTROL LİSTESİ")
        print("="*60)
        
        # Döngüyle tek tek yazdırıyoruz
        for i, col in enumerate(expected_columns, 1):
            val = input_dict[col]
            # Yanına açıklama ekleyelim ki karışmasın
            desc = ""
            if col == 'AgeCategory': desc = "(Yaş)"
            if col == 'BMI': desc = f"({w_kg}kg / {h_m}m)"
            
            print(f"{i:02d}. {col:<25} : {val} {desc}")

        print("-" * 60)

        # --- TAHMİN ---
        df_input = pd.DataFrame([input_dict])
        df_input = df_input[expected_columns]
        
        features_scaled = scaler.transform(df_input)
        features_scaled_df = pd.DataFrame(features_scaled, columns=expected_columns)
        
        raw_prob = model.predict_proba(features_scaled_df)[0][1]

        print(f"📊 SONUÇ RİSKİ: %{raw_prob*100:.2f}")
        print("="*60 + "\n")

        return jsonify({
            'status': 'success',
            'risk_skoru': float(raw_prob),
            'mesaj': 'Yüksek Risk' if raw_prob > 0.30 else 'Düşük Risk'
        })

    except Exception as e:
        print(f"❌ HATA: {e}")
        return jsonify({'status': 'error', 'message': str(e)})

if __name__ == '__main__':
    app.run(port=5000)
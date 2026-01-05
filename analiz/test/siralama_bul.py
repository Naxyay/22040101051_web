import joblib
import pandas as pd

try:
    # Modeli yükle
    model = joblib.load('../heart_model.pkl')
    
    print("--- MODELİN BEKLEDİĞİ GERÇEK SÜTUN SIRASI ---")
    
    # Eğer modelde feature_names_in_ özelliği varsa (yeni scikit-learn sürümleri)
    if hasattr(model, 'feature_names_in_'):
        cols = model.feature_names_in_
        print("Sıralama Bulundu:")
        print(list(cols))
        print("\nTOPLAM SÜTUN SAYISI:", len(cols))
        
        # Bunu kopyalayıp bana atman lazım
        print("\nBunu kopyala bana at, app.py'yi buna göre dizeceğiz.")
        
    else:
        print("Modelde sütun isimleri kayıtlı değil. Scaler'a bakıyoruz...")
        scaler = joblib.load('scaler.pkl')
        if hasattr(scaler, 'feature_names_in_'):
            cols = scaler.feature_names_in_
            print(list(cols))
        else:
            print("HATA: Sütun sırası pkl dosyalarında bulunamadı.")
            print("Lütfen eğitim kodundaki (ipynb) X.columns satırını kontrol et.")

except Exception as e:
    print(f"Hata: {e}")
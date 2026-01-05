import pandas as pd
from sklearn.preprocessing import LabelEncoder

# LabelEncoder'ı başlat
le = LabelEncoder()

print("--- MODELİN GİZLİ SÖZLÜĞÜ (ALFABETİK) ---")

# 1. GENEL SAĞLIK KONTROLÜ
print("\n[GeneralHealth] Değişkeni:")
health_data = ['Excellent', 'Very good', 'Good', 'Fair', 'Poor']
le.fit(health_data)
# Hangi kelime hangi sayıya denk geliyor?
health_mapping = dict(zip(le.classes_, le.transform(le.classes_)))
print(health_mapping)

# 2. SİGARA KONTROLÜ
print("\n[SmokerStatus] Değişkeni:")
smoker_data = ['Current smoker', 'Former smoker', 'Never smoked']
le.fit(smoker_data)
smoker_mapping = dict(zip(le.classes_, le.transform(le.classes_)))
print(smoker_mapping)

# 3. DİYABET KONTROLÜ
print("\n[HadDiabetes] Değişkeni:")
diabetes_data = ['Yes', 'No', 'No, borderline diabetes', 'Yes (during pregnancy)']
le.fit(diabetes_data)
diabetes_mapping = dict(zip(le.classes_, le.transform(le.classes_)))
print(diabetes_mapping)
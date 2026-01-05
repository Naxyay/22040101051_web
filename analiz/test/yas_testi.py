from sklearn.preprocessing import LabelEncoder
import numpy as np

# Veri setindeki orijinal yaş kategorileri (alfabetik sıralanacak)
ages = [
    '18-24', '25-29', '30-34', '35-39', '40-44', '45-49', 
    '50-54', '55-59', '60-64', '65-69', '70-74', '75-79', '80 or older'
]

le = LabelEncoder()
le.fit(ages)

print("--- YAŞ KATEGORİSİ SÖZLÜĞÜ ---")
mapping = dict(zip(le.classes_, le.transform(le.classes_)))
print(mapping)

print(f"\n80 or older Kodu: {mapping['80 or older']}")
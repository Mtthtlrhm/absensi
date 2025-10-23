import sys
import json
import numpy as np
import face_recognition

def find_best_match(new_encoding_json, known_data_json):
    """
    Mencari student_id yang paling cocok dari daftar data yang diketahui.
    """
    try:
        new_encoding = np.array(json.loads(new_encoding_json))
        known_data = json.loads(known_data_json)

        if not known_data:
            return None # Tidak ada data di database

        # Pisahkan antara student_ids dan encoding
        known_student_ids = [data['student_id'] for data in known_data]
        known_encodings = [np.array(data['encoding']) for data in known_data]

        # Hitung jarak antara wajah baru dengan semua wajah di database
        # Ini adalah fungsi inti dari library face_recognition
        distances = face_recognition.face_distance(known_encodings, new_encoding)

        # Temukan index dari jarak yang paling kecil (paling mirip)
        best_match_index = np.argmin(distances)

        # Cek apakah kemiripannya cukup tinggi (jarak di bawah 0.6 adalah standar "cocok")
        if distances[best_match_index] < 0.6:
            # Jika ya, kembalikan student_id yang cocok
            return known_student_ids[best_match_index]
        else:
            # Jika tidak ada yang cukup mirip, kembalikan None
            return None

    except Exception as e:
        sys.stderr.write(str(e))
        return None

if __name__ == "__main__":
    # Argumen 1: JSON string dari encoding baru
    # Argumen 2: JSON string dari array data {student_id, encoding}
    if len(sys.argv) > 2:
        matched_id = find_best_match(sys.argv[1], sys.argv[2])
        if matched_id:
            # Cetak ID siswa yang cocok, ini akan ditangkap oleh PHP
            print(matched_id)
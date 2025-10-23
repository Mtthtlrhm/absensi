<?php
// app/controllers/AttendanceController.php
require_once __DIR__ . '/../../core/Database.php';

function handle_record_attendance() {
    // Atur header agar respons selalu dalam format JSON
    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['message' => 'Hanya metode POST yang diizinkan.']);
        return;
    }

    if (empty($_FILES['face_image'])) {
        http_response_code(400);
        echo json_encode(['message' => 'File gambar wajah tidak ditemukan.']);
        return;
    }

    // 1. Simpan sementara foto yang diupload
    $photo = $_FILES['face_image'];
    $tempFile = $photo['tmp_name'];

    // 2. Dapatkan encoding dari wajah yang baru diupload (menggunakan face_encoder.py)
    // PASTIKAN PATH INI BENAR!
    $commandEncoder = "python C:/laragon/www/Absensi/scripts/face_encoder.py " . escapeshellarg($tempFile);
    $newEncodingJson = shell_exec($commandEncoder);

    if (empty($newEncodingJson)) {
        http_response_code(400);
        echo json_encode(['message' => 'Wajah tidak terdeteksi di gambar.']);
        return;
    }

    try {
        $pdo = get_pdo_connection();

        // 3. Ambil SEMUA data encoding dari database
        $stmt = $pdo->query("SELECT student_id, encoding FROM face_encodings");
        $knownEncodingsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Ubah data menjadi format JSON untuk dikirim ke Python
        $knownEncodingsJson = json_encode($knownEncodingsData);

        // 4. Panggil skrip pencocokan wajah (face_matcher.py)
        // PASTIKAN PATH INI BENAR!
        $commandMatcher = "python C:/laragon/www/Absensi/scripts/face_matcher.py " . escapeshellarg($newEncodingJson) . " " . escapeshellarg($knownEncodingsJson);
        $matchedStudentId = shell_exec($commandMatcher);
        $matchedStudentId = trim($matchedStudentId); // Bersihkan spasi atau newline

        // 5. Jika ada siswa yang cocok, catat kehadirannya
        if (!empty($matchedStudentId) && is_numeric($matchedStudentId)) {
            $today = date('Y-m-d');
            
            // Cek dulu apakah siswa sudah absen hari ini
            $sqlCheck = "SELECT id FROM attendance_records WHERE student_id = ? AND attendance_date = ?";
            $stmtCheck = $pdo->prepare($sqlCheck);
            $stmtCheck->execute([$matchedStudentId, $today]);
            
            if ($stmtCheck->fetch()) {
                 $stmtName = $pdo->prepare("SELECT name FROM students WHERE id = ?");
                 $stmtName->execute([$matchedStudentId]);
                 $student = $stmtName->fetch();
                 echo json_encode(['message' => 'SUDAH ABSEN: ' . $student['name'] . ' sudah tercatat hadir hari ini.']);
            } else {
                // Jika belum, masukkan data absensi baru
                $sqlInsert = "INSERT INTO attendance_records (student_id, attendance_date, check_in_time, status, entry_method) VALUES (?, ?, ?, 'Hadir', 'face_recognition')";
                $stmtInsert = $pdo->prepare($sqlInsert);
                $stmtInsert->execute([$matchedStudentId, $today, date('H:i:s')]);
                
                $stmtName = $pdo->prepare("SELECT name FROM students WHERE id = ?");
                $stmtName->execute([$matchedStudentId]);
                $student = $stmtName->fetch();

                echo json_encode(['message' => 'ABSENSI BERHASIL: Selamat datang, ' . $student['name'] . '!']);
            }
        } else {
            // Jika tidak ada yang cocok
            http_response_code(404);
            echo json_encode(['message' => 'WAJAH TIDAK DIKENALI: Siswa tidak terdaftar di sistem.']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Database error: ' . $e->getMessage()]);
    }
}
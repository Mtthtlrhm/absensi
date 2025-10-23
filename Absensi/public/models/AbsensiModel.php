<?php
// app/models/AbsensiModel.php

/**
 * Menyimpan data absensi siswa ke database.
 *
 * @param PDO $pdo Objek koneksi database
 * @param array $statuses Array status [id_siswa => 'status']
 * @param int $id_jadwal ID jadwal pelajaran
 * @param int $id_kelas ID kelas
 * @param int $id_guru ID guru yang mengabsen
 * @param string $tanggal Tanggal absensi (Y-m-d)
 * @return bool True jika berhasil
 */
function simpanAbsensiSiswa($pdo, $statuses, $id_jadwal, $id_kelas, $id_guru, $tanggal) {
    // Kita gunakan transaksi agar semua data masuk atau tidak sama sekali
    $pdo->beginTransaction();
    
    try {
        // Siapkan query INSERT
        // Pastikan nama tabel 'absensi' dan kolomnya sudah benar
        $sql = "INSERT INTO absensi (id_siswa, id_kelas, id_jadwal, id_guru, tanggal, status) 
                VALUES (:id_siswa, :id_kelas, :id_jadwal, :id_guru, :tanggal, :status)
                ON DUPLICATE KEY UPDATE status = VALUES(status)"; // Update jika data hari ini sudah ada
        
        $stmt = $pdo->prepare($sql);
        
        // Loop setiap siswa dari form
        foreach ($statuses as $id_siswa => $status) {
            $stmt->execute([
                ':id_siswa' => $id_siswa,
                ':id_kelas' => $id_kelas,
                ':id_jadwal' => $id_jadwal,
                ':id_guru' => $id_guru,
                ':tanggal' => $tanggal,
                ':status' => $status
            ]);
        }
        
        // Jika semua berhasil, commit transaksi
        $pdo->commit();
        return true;
        
    } catch (Exception $e) {
        // Jika ada error, batalkan semua
        $pdo->rollBack();
        throw $e; // Lempar error agar controller bisa menangkap
    }
}
?>
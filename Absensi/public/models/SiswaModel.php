<?php
// app/models/SiswaModel.php

/**
 * Mengambil semua siswa dari satu kelas.
 *
 * @param PDO $pdo Objek koneksi database
 * @param int $id_kelas ID kelas
 * @return array Daftar siswa
 */
function getSiswaByKelas($pdo, $id_kelas) {
    // Pastikan nama tabel Anda 'siswa' dan kolomnya 'id_kelas' & 'nama_lengkap'
    $sql = "SELECT 
                id,
                nama_lengkap,
                nisn
            FROM 
                siswa
            WHERE 
                id_kelas = ?
            ORDER BY 
                nama_lengkap ASC";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_kelas]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
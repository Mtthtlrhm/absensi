<?php
// app/views/teacher/mulai_absensi.php

// $jadwal, $hari_ini, dan $daftar_siswa
// dikirim dari AttendanceController.php
?>

<div class="content-header">
    <h1>Absensi: <?= htmlspecialchars($jadwal['nama_mapel']) ?></h1>
    <p>Kelas: <strong><?= htmlspecialchars($jadwal['nama_kelas']) ?></strong> | Hari Ini: <?= htmlspecialchars($hari_ini) ?></p>
</div>

<div class="card">
    <h2>Daftar Siswa</h2>
    
    <form action="?action=simpan_absensi" method="POST">
        <input type="hidden" name="id_jadwal" value="<?= htmlspecialchars($jadwal['id_jadwal']) ?>">
        <input type="hidden" name="id_kelas" value="<?= htmlspecialchars($jadwal['id_kelas']) ?>">

        <div class="siswa-list">
            <?php if (empty($daftar_siswa)): ?>
                <p>Belum ada siswa yang terdaftar di kelas ini.</p>
            <?php else: ?>
                <div class="siswa-item header">
                    <div class="siswa-nama">Nama Siswa (NISN)</div>
                    <div class="siswa-status">Status Kehadiran</div>
                </div>

                <?php foreach ($daftar_siswa as $siswa): ?>
                    <div class="siswa-item">
                        <div class="siswa-nama">
                            <strong><?= htmlspecialchars($siswa['nama_lengkap']) ?></strong>
                            <small>NISN: <?= htmlspecialchars($siswa['nisn']) ?></small>
                        </div>
                        <div class="siswa-status">
                            <div class="status-radio">
                                <input type="radio" id="status_H_<?= $siswa['id'] ?>" name="status[<?= $siswa['id'] ?>]" value="H" checked>
                                <label for="status_H_<?= $siswa['id'] ?>">H</label>
                                
                                <input type="radio" id="status_S_<?= $siswa['id'] ?>" name="status[<?= $siswa['id'] ?>]" value="S">
                                <label for="status_S_<?= $siswa['id'] ?>">S</label>
                                
                                <input type="radio" id="status_I_<?= $siswa['id'] ?>" name="status[<?= $siswa['id'] ?>]" value="I">
                                <label for="status_I_<?= $siswa['id'] ?>">I</label>
                                
                                <input type="radio" id="status_A_<?= $siswa['id'] ?>" name="status[<?= $siswa['id'] ?>]" value="A">
                                <label for="status_A_<?= $siswa['id'] ?>">A</label>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div style="text-align: right; margin-top: 2em;">
            <button type="submit" class="button">Simpan Absensi</button>
        </div>
    </form>
</div>
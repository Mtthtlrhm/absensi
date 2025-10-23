<div class="content-header">
    <h1>Manajemen Siswa</h1>
</div>

<?php if (isset($classes)): ?>
    <div class="card">
        <h2>Pilih Kelas</h2>
        <p>Silakan pilih kelas untuk melihat dan mengelola daftar siswanya.</p>

        <div class="info-card-container">
            <?php if (empty($classes)): ?>
                <p>Belum ada kelas yang dibuat. Silakan tambahkan kelas terlebih dahulu.</p>
            <?php else: ?>
                <?php $classCounter = 1; ?>
                <?php foreach ($classes as $class): ?>
                    <a href="?action=manage_students&class_id=<?= $class['id'] ?>" class="info-card">
                        <div class="info-card-info">
                            <span class="info-card-name">Kelas <?= $classCounter ?></span>
                            <?php 
                                $count = $class['student_count'];
                                $countColor = ($count == 0) ? '#e74c3c' : '#2e7d32'; // Merah atau Hijau tua
                            ?>
                            <span class="info-card-count" style="color: <?= $countColor ?>; font-weight: 600;">
                                <?= $count ?> Siswa Terdaftar
                            </span>
                        </div>
                    </a>
                    <?php $classCounter++; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="card" style="margin-top: 2em;">
        <h2>Riwayat Pendaftaran Siswa Terbaru</h2>
        <?php if (empty($recent_students)): ?>
            <p>Belum ada siswa yang didaftarkan.</p>
        <?php else: ?>
            <table class="recent-students-table">
                <thead>
                    <tr>
                        <th>Nama Siswa</th>
                        <th>NISN</th>
                        <th>Kelas</th>
                        <th>Waktu Daftar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_students as $student): ?>
                        <tr>
                            <td><?= htmlspecialchars($student['student_name']) ?></td>
                            <td><?= htmlspecialchars($student['nisn']) ?></td>
                            <td><?= htmlspecialchars($student['class_name']) ?></td>
                            <td><?= isset($student['created_at']) ? date('d M Y, H:i', strtotime($student['created_at'])) : '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

<?php endif; ?>

<?php if (isset($students)): ?>
    <div class="card"> <h2>Daftar Siswa di Kelas: <?= htmlspecialchars($current_class['name'] ?? 'Tidak Diketahui') ?></h2>
         
         <p>
            <a href="?action=add_student&class_id=<?= $current_class['id'] ?? '' ?>" class="button" style="background-color: #2ecc71; margin-right: 10px;">
                + Tambah Siswa Baru
            </a>
             <a href="?action=manage_students" class="button" style="background-color: #95a5a6;">
                &laquo; Kembali ke Pilihan Kelas
            </a>
        </p>

        <table> <thead>
                <tr>
                    <th>Nama</th>
                    <th>NISN</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($students)): ?>
                    <tr><td colspan="3">Belum ada siswa di kelas ini.</td></tr>
                <?php else: ?>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?= htmlspecialchars($student['name']) ?></td>
                            <td><?= htmlspecialchars($student['nisn']) ?></td>
                            <td>
                                <a href="?action=edit_student&id=<?= $student['id'] ?>">Edit</a> |
                                <a href="?action=delete_student&id=<?= $student['id'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus siswa bernama <?= htmlspecialchars(addslashes($student['name'])) ?>? Semua data terkait (absensi, wajah) juga akan terhapus.');" style="color: red;">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
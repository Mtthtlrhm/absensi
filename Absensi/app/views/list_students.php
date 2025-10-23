<div class="content-header">
    <h1>Daftar Semua Siswa</h1>
    <p>Menampilkan semua siswa yang terdaftar di sistem.</p>
</div>

<div class="card">
    <?php if (empty($all_students)): ?>
        <p>Belum ada siswa yang terdaftar.</p>
    <?php else: ?>
        <table style="vertical-align: middle;">
            <thead>
                <tr>
                    <th style="width: 50px;">Foto</th>
                    <th>Nama Siswa</th>
                    <th>NISN</th>
                    <th>Kelas</th>
                    <th>Nama Ortu/Wali</th>
                    </tr>
            </thead>
            <tbody>
                <?php foreach ($all_students as $student): ?>
                    <tr>
                        <td>
                            <?php
                            // Tampilkan foto jika ada, jika tidak tampilkan placeholder
                            $photoPath = isset($student['photo']) && !empty($student['photo']) ? '/uploads/' . $student['photo'] : '/student_2_front_1759820599'; // Ganti path placeholder jika perlu
                            ?>
                            <img src="<?= $photoPath ?>" alt="Foto <?= htmlspecialchars($student['student_name']) ?>" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                        </td>
                        <td><?= htmlspecialchars($student['student_name']) ?></td>
                        <td><?= htmlspecialchars($student['nisn']) ?></td>
                        <td><?= htmlspecialchars($student['class_name'] ?? 'Belum ada kelas') ?></td>
                        <td><?= htmlspecialchars($student['parent_name'] ?? '-') ?></td>
                        </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
     <p style="margin-top: 2em;"><a href="?action=home" class="button">&laquo; Kembali ke Dashboard</a></p>
</div>







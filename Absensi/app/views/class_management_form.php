<div class="content-header">
    <h1>Manajemen Kelas</h1>
</div>

<div class="card">
    <h2>Tambah Kelas Baru</h2>
    
    <?php 
    // Menampilkan pesan status dari URL
    if (isset($_GET['status'])): 
        $status = $_GET['status'];
        $message = '';
        $bgColor = '';
        $textColor = '';

        if ($status === 'add_success') {
            $message = 'Kelas baru berhasil ditambahkan!';
            $bgColor = '#d4edda'; $textColor = '#155724'; // Hijau
        } elseif ($status === 'delete_success') {
            $message = 'Data kelas berhasil dihapus.';
            $bgColor = '#d1ecf1'; $textColor = '#0c5460'; // Biru muda
        } elseif ($status === 'delete_error_exists') {
            $message = 'Gagal menghapus: Masih ada siswa yang terdaftar di kelas ini.';
            $bgColor = '#f8d7da'; $textColor = '#721c24'; // Merah
        } elseif ($status === 'add_error_duplicate') {
            $message = 'Gagal menambahkan: Kelas dengan nama dan tahun ajaran tersebut sudah ada.';
            $bgColor = '#f8d7da'; $textColor = '#721c24'; // Merah
        } elseif ($status === 'add_error_empty') {
            $message = 'Gagal menambahkan: Nama kelas dan tahun ajaran tidak boleh kosong.';
            $bgColor = '#f8d7da'; $textColor = '#721c24'; // Merah
        }

        if ($message): 
    ?>
            <div class="status-message" style="background-color: <?= $bgColor ?>; color: <?= $textColor ?>; padding: 10px; border-radius: 5px; margin-bottom: 1em;">
                <?= htmlspecialchars($message) ?>
            </div>
    <?php 
        endif;
    endif; 
    ?>

    <form action="?action=manage_classes" method="post">
        <div class="form-group">
            <label for="name">Nama Kelas:</label>
            <input type="text" id="name" name="name" required placeholder="Contoh: XII IPA 1">
        </div>
        <div class="form-group">
            <label for="academic_year">Tahun Ajaran:</label>
            <input type="text" id="academic_year" name="academic_year" required placeholder="Contoh: 2025/2026">
        </div>
        <button type="submit" class="button">Simpan Kelas</button>
    </form>
</div>

<div class="card" style="margin-top: 2em;">
    <h2>Daftar Kelas Tersedia</h2>
    <table>
        <thead>
            <tr>
                <th>Nama Kelas</th>
                <th>Tahun Ajaran</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($classes)): ?>
                <tr><td colspan="3">Belum ada kelas yang dibuat.</td></tr>
            <?php else: ?>
                <?php foreach ($classes as $class): ?>
                    <tr>
                        <td><?= htmlspecialchars($class['name']) ?></td>
                        <td><?= htmlspecialchars($class['academic_year']) ?></td>
                        <td>
                            <a href="?action=delete_class&id=<?= $class['id'] ?>" 
                               style="color: #e74c3c;" 
                               onclick="return confirm('Apakah Anda yakin ingin menghapus kelas <?= htmlspecialchars(addslashes($class['name'])) ?>? Kelas hanya bisa dihapus jika tidak ada siswa di dalamnya.');">
                                Hapus
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
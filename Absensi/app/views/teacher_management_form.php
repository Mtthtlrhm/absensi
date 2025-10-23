<div class="content-header">
    <h1>Manajemen Guru</h1>
</div>

<div class="card">
    <h2>Tambah Guru Baru</h2>
    
    <?php if (isset($_GET['status']) && $_GET['status'] === 'add_success'): ?>
        <div class="success-message" style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 1em;">
            Guru baru berhasil ditambahkan!
        </div>
    <?php elseif (isset($_GET['status']) && $_GET['status'] === 'delete_success'): ?>
        <div class="success-message" style="background-color: #d1ecf1; color: #0c5460; padding: 10px; border-radius: 5px; margin-bottom: 1em;">
            Data guru berhasil dihapus.
        </div>
    <?php endif; ?>

    <form action="?action=manage_teachers" method="post">
        <div class="form-group">
            <label for="name">Nama Lengkap:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="nuptk">NUPTK (Opsional):</label>
            <input type="text" id="nuptk" name="nuptk">
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="button">Simpan Guru</button>
    </form>
</div>


<div class="card" style="margin-top: 2em;">
    <h2>Daftar Guru Terdaftar</h2>
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>NUPTK</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($teachers)): ?>
                <tr><td colspan="4">Belum ada guru yang terdaftar.</td></tr>
            <?php else: ?>
                <?php foreach ($teachers as $teacher): ?>
                    <tr>
                        <td><?= htmlspecialchars($teacher['name']) ?></td>
                        <td><?= htmlspecialchars($teacher['email']) ?></td>
                        <td><?= htmlspecialchars($teacher['nuptk'] ?? '-') ?></td>
                        <td>
                            <a href="?action=edit_teacher&id=<?= $teacher['id'] ?>">Edit</a> | 
                            <a href="?action=delete_teacher&id=<?= $teacher['id'] ?>" 
                               style="color: #e74c3c;" 
                               onclick="return confirm('Apakah Anda yakin ingin menghapus guru bernama <?= htmlspecialchars(addslashes($teacher['name'])) ?>?');">
                                Hapus
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<div class="content-header">
    <h1>Edit Data Guru</h1>
</div>

<div class="card">
    <form action="?action=edit_teacher" method="post">
        <input type="hidden" name="id" value="<?= htmlspecialchars($teacher['id']) ?>">

        <div class="form-group">
            <label for="name">Nama Lengkap:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($teacher['name']) ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($teacher['email']) ?>" required>
        </div>
        <div class="form-group">
            <label for="nuptk">NUPTK (Opsional):</label>
            <input type="text" id="nuptk" name="nuptk" value="<?= htmlspecialchars($teacher['nuptk'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="password">Password Baru:</label>
            <input type="password" id="password" name="password" placeholder="Kosongkan jika tidak ingin diubah">
            <small style="color: #666;">Jika Anda mengisi kolom ini, password lama akan diganti.</small>
        </div>
        <button type="submit" class="button">Update Data Guru</button>
    </form>
    <p style="margin-top: 2em;"><a href="?action=manage_teachers">Batal dan Kembali</a></p>
</div>
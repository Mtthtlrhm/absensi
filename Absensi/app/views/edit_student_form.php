<div class="content-header">
    <h1>Edit Data Siswa</h1>
</div>

<div class="card">
    <form action="?action=edit_student" method="post">
        <input type="hidden" name="id" value="<?= htmlspecialchars($student['id']) ?>">

        <div class="form-group">
            <label for="name">Nama Lengkap:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($student['name']) ?>" required>
        </div>

        <div class="form-group">
            <label for="nisn">NISN:</label>
            <input type="text" id="nisn" name="nisn" value="<?= htmlspecialchars($student['nisn']) ?>" required>
        </div>

        <div class="form-group">
            <label for="class_id">Pilih Kelas:</label>
            <select id="class_id" name="class_id" required>
                <option value="" disabled>-- Pilih Kelas --</option>
                <?php foreach ($classes as $class): ?>
                    <option value="<?= htmlspecialchars($class['id']) ?>" <?= ($class['id'] == $student['class_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($class['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <button type="submit" class="button">Update Data Siswa</button>
    </form>
    <p style="margin-top: 2em;"><a href="?action=manage_students&class_id=<?= $student['class_id'] ?>">Batal dan Kembali</a></p>
</div>
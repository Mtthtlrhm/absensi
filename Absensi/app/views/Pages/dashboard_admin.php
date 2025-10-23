<div class="content-header">
    <h1>Dashboard Utama</h1>
    <p>Selamat datang kembali, <?= htmlspecialchars($_SESSION['user_name']) ?>!</p>
</div>

<div class="info-card-container">

    <a href="?action=list_all_students" class="info-card"> <div class="info-card-info">
            <span class="info-card-name"><?= $studentCount ?? 0 ?></span>
            <span class="info-card-count">Total Siswa Terdaftar</span>
        </div>
    </a>

    <div class="info-card"> <div class="info-card-info">
            <span class="info-card-name"><?= $teacherCount ?? 0 ?></span>
            <span class="info-card-count">Total Guru Terdaftar</span>
        </div>
    </div>

    <div class="info-card"> <div class="info-card-info">
            <span class="info-card-name"><?= $classCount ?? 0 ?></span>
            <span class="info-card-count">Total Kelas Tersedia</span>
        </div>
    </div>
</div>

<div class="card">
    <h2>Navigasi Cepat</h2>
    <p>Gunakan menu di sebelah kiri untuk mengelola data aplikasi.</p>
</div>
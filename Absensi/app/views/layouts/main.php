<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Dashboard Absensi' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F5F5F7; /* Latar belakang: Krim/Kuning Muda */
            margin: 0;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* --- Sidebar Tema Hijau --- */
        .sidebar {
            width: 250px;
            background-color: #1B3C53; /* Warna Sidebar: Hijau Tua */
            color: #FCFFE0; /* Teks/Ikon: Krim Muda */
            padding: 20px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }
        .sidebar h2 {
            font-size: 1.5em;
            color: white; /* Teks Logo: Krim Muda */
            margin-bottom: 30px;
            text-align: center;
        }
        .sidebar ul { list-style-type: none; padding: 0; }
        .sidebar ul li a {
            display: flex;
            align-items: center;
            color: white; /* Teks Menu: Krim Muda */
            padding: 15px;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 10px;
            transition: background-color 0.3s;
        }
        .sidebar ul li a:hover, .sidebar ul li a.active {
            background-color: #456882; /* Aksen Hijau (Lebih Gelap) */
        }
        .sidebar .logout { margin-top: auto; }
        .sidebar ul li a i { margin-right: 10px; font-size: 1.2em; width: 20px; }
        .sidebar ul li a .menu-text { display: inline; }

        /* --- Main Content --- */
        .main-content { flex-grow: 1; padding: 40px; overflow-y: auto; height: 100vh; box-sizing: border-box; }
        .content-header { margin-bottom: 30px; }
        .content-header h1 { font-size: 2.5em; font-weight: 700; color: black; margin: 0; } /* Judul: Hijau Tua */
        .content-header p { font-size: 1.1em; color: #8a8a8a; } /* Abu-abu netral */

        /* --- Gaya Kartu Umum --- */
        .card {
            background: white;
            padding: 2em;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(117, 164, 127, 0.1); /* Bayangan Hijau Lembut */
            margin-bottom: 2em;
        }
        .card h2 {
            margin-top: 0;
            margin-bottom: 1.5em;
            color: black; /* Judul Kartu: Hijau Tua */
            font-weight: 600;
            border-bottom: 1px solid #F5DAD2; /* Garis Pink Pastel */
            padding-bottom: 0.5em;
        }
        
        .info-card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        a.info-card, div.info-card {
            display: block;
            color: #5d5d5d;
            border: 1px solid transparent;
            border-radius: 15px;
            padding: 20px;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(117, 164, 127, 0.1); /* Bayangan Hijau Lembut */
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
            text-align: center;
        }
        a.info-card:hover, div.info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(117, 164, 127, 0.15);
            
        }
        .info-card-info { text-align: center; }
        .info-card-name { display: block; font-size: 1.5em; font-weight: 700; margin-bottom: 8px; }
        .info-card-count { display: block; font-size: 0.9em; }
        
        /* Variasi Warna Kartu (Palet Baru dengan 6 Variasi) */
    .info-card-container > *:nth-child(6n+1) { 
        background-color: #E1F2FB; /* Kelas 1: Mawar Pudar */
        color: var(--color-text-light);
    }
    .info-card-container > *:nth-child(6n+1) .info-card-name { color: var(--color-white); }
    .info-card-container > *:nth-child(6n+1) .info-card-count { color: var(--color-lightest-pink); }

    .info-card-container > *:nth-child(6n+2) { 
        background-color: #F1F9F9; /* Kelas 2: Ashy Pink */
        color: var(--color-text-dark);
    }
    .info-card-container > *:nth-child(6n+2) .info-card-name { color: var(--color-darkest-plum); }
    .info-card-container > *:nth-child(6n+2) .info-card-count { color: var(--color-medium-rose); }
    
    .info-card-container > *:nth-child(6n+3) { 
        background-color: #FBF4F9; /* Kelas 3: Plum Tua */
        color: var(--color-text-light);
    }
    .info-card-container > *:nth-child(6n+3) .info-card-name { color: var(--color-white); }
    .info-card-container > *:nth-child(6n+3) .info-card-count { color: var(--color-lightest-pink); }

    .info-card-container > *:nth-child(6n+4) { 
        background-color: #F4F3F3; /* Kelas 4: Pink Pucat */
        color: var(--color-text-dark);
        border: 1px solid var(--color-ashy-pink); /* Tambah border agar lebih menonjol */
    }
    .info-card-container > *:nth-child(6n+4) .info-card-name { color: var(--color-darkest-plum); }
    .info-card-container > *:nth-child(6n+4) .info-card-count { color: var(--color-medium-rose); }

    .info-card-container > *:nth-child(6n+5) { 
        background-color: #F9ECEC; /* Kelas 5: Mawar Pudar (ulang lagi tapi beda detail) */
        color: var(--color-text-light);
    }
    .info-card-container > *:nth-child(6n+5) .info-card-name { color: var(--color-white); }
    .info-card-container > *:nth-child(6n+5) .info-card-count { color: var(--color-lightest-pink); }
    
    .info-card-container > *:nth-child(6n+6) { 
        background-color: #FFFEEC; /* Kelas 6: Ashy Pink (ulang lagi tapi beda detail) */
        color: var(--color-text-dark);
    }
    .info-card-container > *:nth-child(6n+6) .info-card-name { color: var(--color-darkest-plum); }
    .info-card-container > *:nth-child(6n+6) .info-card-count { color: var(--color-darkest-plum); }
    /* ------------------------------------------------------- */

        /* --- Gaya TABEL (Tema Hijau) --- */
        table:not(.recent-students-table) {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1.5em;
            font-size: 0.95em;
        }
        table:not(.recent-students-table) th,
        table:not(.recent-students-table) td {
            border: 1px solid #C5C7BC; /* Border Light Mint */
            padding: 12px 15px;
            text-align: left;
            vertical-align: middle;
        }
        table:not(.recent-students-table) th {
            background-color: #ECF2F9; /* Header Light Gray */
            font-weight: 600;
            color: black; /* Teks Header Hijau Tua */
        }

        table:not(.recent-students-table) tbody tr:hover {
            background-color: #F6F6F6; /* Hover Mint Pucat */
        }
        td a { color: #6D94C5; text-decoration: none; margin-right: 10px; font-weight: 600; } /* Link Hijau Pastel */
        td a:hover { text-decoration: underline; color: #75A47F; } /* Link Hijau Tua */
        td a[style*="color: red"], td a[style*="color: #e74c3c"] { color: #e57373 !important; }
        td a[style*="color: red"]:hover, td a[style*="color: #e74c3t"] { color: #c0392b !important; }

        /* --- Gaya FORM (Tema Hijau) --- */
        .form-group { margin-bottom: 1.5em; }
        .form-group label { display: block; margin-bottom: 0.7em; color: black; font-weight: 600; font-size: 0.9em; } /* Label Hijau Tua */
        input[type="text"], input[type="email"], input[type="password"], input[type="number"], select {
            width: 100%;
            padding: 10px 12px;
            box-sizing: border-box;
            border-radius: 8px;
            border: 1px solid #D9E9CF; /* Border Light Mint */
            background-color: #fff;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #BACD92; /* Border Hijau Pastel saat focus */
            box-shadow: 0 0 0 2px rgba(182, 206, 180, 0.3);
        }

        /* --- Gaya TOMBOL UMUM (Tema Hijau) --- */
        button, a.button:not(.info-card) {
            padding: 10px 20px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            border: none;
            border-radius: 8px;
            background-color: #75A47F; /* Tombol Hijau Tua */
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: background-color 0.3s, transform 0.2s;
            height: auto;
            width: auto;
            margin-right: 10px;
            margin-bottom: 10px;
        }
        button:hover, a.button:not(.info-card):hover {
            background-color: #65906f; /* Hijau lebih gelap saat hover */
            transform: translateY(-2px);
        }
        /* Style tombol khusus */
        a.button[style*="#95a5a6"] { background-color: #bdc3c7 !important; } /* Abu-abu netral */
        a.button[style*="#95a5a6"]:hover { background-color: #95a5a6 !important; }
        a.button[style*="#2ecc71"] { background-color: #748873 !important; } /* Hijau Cerah -> Hijau Pastel */
        a.button[style*="#2ecc71"]:hover { background-color: #a9c081 !important; }

        /* --- Gaya Badge Jumlah Siswa (Disesuaikan untuk tombol hijau) --- */
        .student-count-badge { background-color: rgba(255, 255, 255, 0.2); padding: 3px 8px; border-radius: 5px; font-size: 0.8em; margin-left: 10px; font-weight: 400; color: white; line-height: 1; }

        /* --- Style Tabel Riwayat Siswa (Pastel Hijau) --- */
        .recent-students-table { width: 100%; border-collapse: separate; border-spacing: 0 8px; margin-top: 1.5em; font-size: 0.9em; }
        .recent-students-table thead th { background-color: transparent; font-weight: 600; color: black; padding: 15px 20px; text-align: left; border-bottom: 2px solid #D9E9CF; }
        .recent-students-table tbody tr { background-color: #fff; box-shadow: 0 2px 8px rgba(150, 167, 141, 0.1); border-radius: 10px; transition: transform 0.2s ease, box-shadow 0.2s ease; overflow: hidden; }
        .recent-students-table tbody tr:hover { transform: translateY(-3px); box-shadow: 0 6px 15px rgba(150, 167, 141, 0.15); }
        .recent-students-table td { padding: 15px 20px; border: none; vertical-align: middle; color: #5d5d5d; }
        .recent-students-table td:first-child { border-top-left-radius: 10px; border-bottom-left-radius: 10px; font-weight: 600; color: black; } /* Hijau Pastel */
        .recent-students-table td:last-child { border-top-right-radius: 10px; border-bottom-right-radius: 10px; text-align: right; font-size: 0.9em; color: red; } /* Dark Sage */
        .recent-students-table td:nth-child(2),
        .recent-students-table td:nth-child(3) { font-size: 0.9em; color: black; }
        /* ------------------------------------------------------- */

        /* --- Style Umum Lainnya --- */
        .success-message, .error-message { padding: 10px; border-radius: 5px; margin-bottom: 1em; }
    
    /* --- BARU: CSS untuk Daftar Jadwal Guru --- */
.jadwal-list {
    margin-top: 1.5em;
    display: flex;
    flex-direction: column;
    gap: 15px; /* Jarak antar item jadwal */
}

.jadwal-item {
    display: flex;
    justify-content: space-between; /* Pisahkan info dan tombol */
    align-items: center;
    flex-wrap: wrap; /* Agar responsif */
    gap: 10px;
    padding: 15px 20px;
    background-color: #fcfcfc;
    border: 1px solid var(--color-lightest-pink); /* Pakai warna tema Anda */
    border-radius: 10px;
    transition: box-shadow 0.2s ease;
}

.jadwal-item:hover {
    box-shadow: 0 4px 12px var(--shadow-color); /* Pakai warna tema Anda */
}

.jadwal-item.empty {
    justify-content: center;
    flex-direction: column;
    text-align: center;
    color: #888;
    padding: 30px;
    background-color: #fafafa;
}

.jadwal-info {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.jadwal-info .jam {
    font-size: 0.9em;
    color: #555;
    font-weight: 600;
}
.jadwal-info .jam i {
    margin-right: 5px;
    color: var(--color-medium-rose); /* Pakai warna tema Anda */
}

.jadwal-info .mapel {
    font-size: 1.2em;
    font-weight: 700;
    color: var(--color-darkest-plum); /* Pakai warna tema Anda */
}

.jadwal-info .kelas {
    font-size: 1em;
    color: #333;
}

.jadwal-aksi .button {
    padding: 8px 15px;
    font-size: 0.9em;
    margin: 0;
}

    </style>
</head>
<body>
    
    <?php
    // Logika pemilihan sidebar (Pastikan nama file ini benar)
    if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
        require_once __DIR__ . '/../partials/_sidebar_admin.php';
    } else {
        require_once __DIR__ . '/../partials/_sidebar_teacher.php';
    }
    ?>

    <main class="main-content">
        <?php 
        // Memuat konten halaman dinamis
        if (isset($view) && file_exists($view)) {
            require_once $view;
        } else {
            echo "<div class='card'><h2>Error Konfigurasi</h2><p>File view yang diminta ('" . htmlspecialchars($view ?? 'NULL') . "') tidak dapat ditemukan. Silakan periksa path di controller.</p></div>"; 
        }
        ?>
    </main>

</body>
</html>
<?php
// app/views/main.php

// Logika untuk menentukan sidebar berdasarkan role
$sidebar_file = '';
if (isset($_SESSION['user_role'])) {
    if ($_SESSION['user_role'] === 'admin') {
        $sidebar_file = __DIR__ . '/partials/_sidebar_admin.php';
    } else if ($_SESSION['user_role'] === 'guru') {
        $sidebar_file = __DIR__ . '/partials/_sidebar_teacher.php';
    }
}

// $page_title dan $view_to_load harus sudah di-set oleh controller
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($page_title ?? 'Absensi Pro') ?></title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <?php if (isset($action) && $action === 'add_student'): ?>
        <script defer src="public/models/face-api.min.js"></script>
    <?php endif; ?>

    <style>
        /* Definisi Variabel Tema (Vintage Rose) */
        :root {
          --color-darkest-plum: #944E63;
          --color-medium-rose: #B47B84;
          --color-ashy-pink: #CAA6A6;
          --color-lightest-pink: #FFE7E7;
          --color-white: #FFFFFF;
          --color-text-dark: #333333;
          --color-text-light: var(--color-lightest-pink);
          --shadow-color: rgba(148, 78, 99, 0.1);
          --shadow-color-hover: rgba(148, 78, 99, 0.15);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--color-lightest-pink);
            margin: 0;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* --- Sidebar --- */
        .sidebar {
            width: 250px;
            background-color: var(--color-darkest-plum);
            color: var(--color-text-light);
            padding: 20px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }
        .sidebar h2, .sidebar-header {
            font-size: 1.5em;
            color: var(--color-text-light);
            margin-bottom: 30px;
            text-align: center;
        }
        .sidebar ul { list-style-type: none; padding: 0; }
        .sidebar ul li a {
            display: flex;
            align-items: center;
            color: var(--color-text-light);
            padding: 15px;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 10px;
            transition: background-color 0.3s, color 0.3s;
        }
        .sidebar ul li a:hover {
            background-color: var(--color-medium-rose);
        }
        .sidebar ul li a.active {
            background-color: var(--color-lightest-pink);
            color: var(--color-darkest-plum);
            font-weight: 600;
        }
        .sidebar .logout { margin-top: auto; }
        .sidebar ul li a i { margin-right: 10px; font-size: 1.2em; width: 20px; }
        .sidebar ul li a .menu-text { display: inline; }

        /* --- Main Content --- */
        .main-content { flex-grow: 1; padding: 40px; overflow-y: auto; height: 100vh; box-sizing: border-box; }
        .content-header { margin-bottom: 30px; }
        .content-header h1 { font-size: 2.5em; font-weight: 700; color: var(--color-darkest-plum); margin: 0; }
        .content-header p { font-size: 1.1em; color: var(--color-medium-rose); }

        /* --- Kartu Umum --- */
        .card {
            background: var(--color-white);
            padding: 2em;
            border-radius: 15px;
            box-shadow: 0 5px 15px var(--shadow-color);
            margin-bottom: 2em;
        }
        .card h2 {
            margin-top: 0;
            margin-bottom: 1.5em;
            color: var(--color-darkest-plum);
            font-weight: 600;
            border-bottom: 1px solid var(--color-ashy-pink);
            padding-bottom: 0.5em;
        }
        
        /* --- Kartu Info (Admin Dashboard) --- */
        .info-card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        a.info-card {
            display: block;
            border-radius: 15px;
            padding: 20px;
            text-decoration: none;
            box-shadow: 0 4px 15px var(--shadow-color);
            transition: transform 0.2s ease;
        }
        a.info-card:hover { transform: translateY(-5px); }
        .info-card-name { display: block; font-size: 1.5em; font-weight: 700; margin-bottom: 8px; }
        .info-card-count { display: block; font-size: 0.9em; }
        /* Variasi Kartu */
        .info-card-container > *:nth-child(6n+1) { background-color: var(--color-medium-rose); color: var(--color-text-light); }
        .info-card-container > *:nth-child(6n+1) .info-card-name { color: var(--color-white); }
        .info-card-container > *:nth-child(6n+1) .info-card-count { color: var(--color-lightest-pink); }
        .info-card-container > *:nth-child(6n+2) { background-color: var(--color-ashy-pink); color: var(--color-text-dark); }
        .info-card-container > *:nth-child(6n+2) .info-card-name { color: var(--color-darkest-plum); }
        .info-card-container > *:nth-child(6n+2) .info-card-count { color: var(--color-medium-rose); }
        .info-card-container > *:nth-child(6n+3) { background-color: var(--color-darkest-plum); color: var(--color-text-light); }
        .info-card-container > *:nth-child(6n+3) .info-card-name { color: var(--color-white); }
        .info-card-container > *:nth-child(6n+3) .info-card-count { color: var(--color-lightest-pink); }
        .info-card-container > *:nth-child(6n+4) { background-color: var(--color-lightest-pink); border: 1px solid var(--color-ashy-pink); }
        .info-card-container > *:nth-child(6n+4) .info-card-name { color: var(--color-darkest-plum); }
        .info-card-container > *:nth-child(6n+4) .info-card-count { color: var(--color-medium-rose); }
        .info-card-container > *:nth-child(6n+5) { background-color: var(--color-medium-rose); color: var(--color-text-light); }
        .info-card-container > *:nth-child(6n+5) .info-card-name { color: var(--color-white); }
        .info-card-container > *:nth-child(6n+5) .info-card-count { color: var(--color-lightest-pink); }
        .info-card-container > *:nth-child(6n+6) { background-color: var(--color-ashy-pink); color: var(--color-text-dark); }
        .info-card-container > *:nth-child(6n+6) .info-card-name { color: var(--color-darkest-plum); }
        .info-card-container > *:nth-child(6n+6) .info-card-count { color: var(--color-darkest-plum); }

        /* --- Tombol Umum --- */
        button, a.button {
            padding: 10px 20px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            border: none;
            border-radius: 8px;
            background-color: var(--color-medium-rose);
            color: var(--color-lightest-pink);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: background-color 0.3s, transform 0.2s;
        }
        button:hover, a.button:hover {
            background-color: var(--color-darkest-plum);
            transform: translateY(-2px);
        }

        /* --- Form Umum --- */
        .form-group { margin-bottom: 1.5em; }
        .form-group label { display: block; margin-bottom: 0.7em; color: var(--color-darkest-plum); font-weight: 600; font-size: 0.9em; }
        input[type="text"], input[type="email"], input[type="password"], input[type="number"], select {
            width: 100%;
            padding: 10px 12px;
            box-sizing: border-box;
            border-radius: 8px;
            border: 1px solid var(--color-ashy-pink);
            background-color: #fff;
        }

        /* --- CSS untuk Daftar Jadwal Guru --- */
        .jadwal-list {
            margin-top: 1.5em;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .jadwal-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            padding: 15px 20px;
            background-color: #fcfcfc;
            border: 1px solid var(--color-lightest-pink);
            border-radius: 10px;
            transition: box-shadow 0.2s ease;
        }
        .jadwal-item:hover { box-shadow: 0 4px 12px var(--shadow-color); }
        .jadwal-item.empty {
            justify-content: center;
            flex-direction: column;
            text-align: center;
            color: #888;
            padding: 30px;
        }
        .jadwal-info { display: flex; flex-direction: column; gap: 5px; }
        .jadwal-info .jam { font-size: 0.9em; color: #555; font-weight: 600; }
        .jadwal-info .jam i { margin-right: 5px; color: var(--color-medium-rose); }
        .jadwal-info .mapel { font-size: 1.2em; font-weight: 700; color: var(--color-darkest-plum); }
        .jadwal-info .kelas { font-size: 1em; color: #333; }
        .jadwal-aksi .button { padding: 8px 15px; font-size: 0.9em; margin: 0; }

        /* --- CSS untuk Daftar Siswa (Halaman Absensi) --- */
        .siswa-list { display: flex; flex-direction: column; }
        .siswa-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 10px;
            border-bottom: 1px solid var(--color-lightest-pink);
        }
        .siswa-item:last-child { border-bottom: none; }
        .siswa-item.header {
            font-weight: 700;
            color: var(--color-darkest-plum);
            border-bottom: 2px solid var(--color-ashy-pink);
            padding-bottom: 10px;
        }
        .siswa-nama { flex: 2; display: flex; flex-direction: column; }
        .siswa-nama small { font-size: 0.85em; color: #888; }
        .siswa-status { flex: 1; display: flex; justify-content: flex-end; }
        .status-radio { display: flex; gap: 5px; }
        .status-radio input[type="radio"] { display: none; }
        .status-radio label {
            display: inline-block;
            padding: 5px 10px;
            width: 25px;
            text-align: center;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            background-color: #f0f0f0;
            color: #777;
            transition: all 0.2s ease;
        }
        .status-radio input[type="radio"]:checked + label { color: var(--color-white); }
        /* Warna status */
        .status-radio input[value="H"]:checked + label { background-color: #28a745; }
        .status-radio input[value="S"]:checked + label { background-color: #ffc107; }
        .status-radio input[value="I"]:checked + label { background-color: #17a2b8; }
        .status-radio input[value="A"]:checked + label { background-color: #dc3545; }
    </style>
</head>
<body>
    
    <?php
    // Muat sidebar yang sesuai
    if ($sidebar_file && file_exists($sidebar_file)) {
        require_once $sidebar_file;
    }
    ?>

    <main class="main-content">
        <?php 
        // Muat konten halaman (view) yang ditentukan oleh controller
        if (isset($view_to_load) && file_exists($view_to_load)) {
            require_once $view_to_load;
        } else {
            // Fallback jika view tidak ditemukan
            echo "<div class='card'><h2>Error</h2><p>File view tidak dapat dimuat.</p></div>"; 
        }
        ?>
    </main>

</body>
</html>
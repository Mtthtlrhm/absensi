<?php
// app/controllers/StudentController.php
require_once __DIR__ . '/../../core/Database.php';

/**
 * Fungsi utama untuk halaman "Kelola Siswa".
 */
function handle_manage_students() {
    // Penjaga: Pastikan pengguna sudah login dan adalah admin.
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
        header('Location: index.php'); // Arahkan ke dashboard jika bukan admin
        exit;
    }

    $pdo = get_pdo_connection();
    $class_id = $_GET['class_id'] ?? null;
    $viewData = []; // Variabel untuk menampung data yang akan dikirim ke view

    try {
        if ($class_id && is_numeric($class_id)) {
            // Jika ada class_id di URL, ambil data kelas dan siswanya.
            $stmt_class = $pdo->prepare("SELECT * FROM classes WHERE id = ?");
            $stmt_class->execute([$class_id]);
            $viewData['current_class'] = $stmt_class->fetch();

            if (!$viewData['current_class']) {
                 die("Error: Kelas dengan ID {$class_id} tidak ditemukan.");
            }

            $stmt_students = $pdo->prepare("SELECT id, name, nisn FROM students WHERE class_id = ? ORDER BY name ASC");
            $stmt_students->execute([$class_id]);
            $viewData['students'] = $stmt_students->fetchAll(PDO::FETCH_ASSOC);

        } else {
            // Jika tidak ada class_id, tampilkan daftar semua kelas beserta jumlah siswanya.
            $query_classes = "
                SELECT c.id, c.name, COUNT(s.id) as student_count
                FROM classes c
                LEFT JOIN students s ON c.id = s.class_id
                GROUP BY c.id, c.name
                ORDER BY c.name ASC
            ";
            $viewData['classes'] = $pdo->query($query_classes)->fetchAll(PDO::FETCH_ASSOC);

            // Ambil data 5 siswa terakhir yang didaftarkan.
            $stmt_recent = $pdo->query("
                SELECT s.name as student_name, s.nisn, c.name as class_name, s.created_at
                FROM students s
                JOIN classes c ON s.class_id = c.id
                ORDER BY s.created_at DESC
                LIMIT 5
            ");
            $viewData['recent_students'] = $stmt_recent->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
         die("Database Error saat mengelola siswa: " . $e->getMessage());
    }

    // =============================================================
    // BAGIAN PENTING: Pastikan $view selalu didefinisikan di sini
    // =============================================================
    $title = "Manajemen Siswa";
    $view = __DIR__ . '/../views/student_management.php'; // Path ke file konten

    // Ekstrak variabel agar bisa diakses di dalam file view
    extract($viewData);

    // Panggil kerangka layout utama.
    require_once __DIR__ . '/../views/layouts/main.php';
}

/**
 * Menangani halaman penambahan siswa (scanner wajah dan form).
 */
function handle_add_student() {
    // Penjaga: Pastikan pengguna sudah login dan adalah admin.
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
        header('Location: index.php');
        exit;
    }
    // ... (Logika AJAX & POST tidak berubah) ...

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       // ... Logika POST ...
    } else {
        // Bagian untuk menampilkan halaman scanner saat pertama kali diakses (method GET).
        $title = "Daftarkan Siswa Baru";
        $view = __DIR__ . '/../views/student_form.php'; // Path ke konten scanner
        // Panggil kerangka layout utama
        require_once __DIR__ . '/../views/layouts/main.php';
    }
}

/**
 * Menangani halaman edit siswa.
 */
function handle_edit_student() {
    // Penjaga: Pastikan pengguna sudah login dan adalah admin.
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
        header('Location: index.php');
        exit;
    }
    
    $pdo = get_pdo_connection();
    $viewData = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       // ... Logika POST update ...
    } else {
        // Logika untuk GET (menampilkan form edit)
        $id = $_GET['id'] ?? null;
        if (!$id) { header('Location: ?action=manage_students'); exit; }

        try {
            $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
            $stmt->execute([$id]);
            $viewData['student'] = $stmt->fetch();
            if (!$viewData['student']) { die("Siswa tidak ditemukan."); }
            $viewData['classes'] = $pdo->query("SELECT id, name FROM classes ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { die("Database Error: " . $e->getMessage()); }

        // =============================================================
        // BAGIAN PENTING: Pastikan $view didefinisikan di sini
        // =============================================================
        $title = "Edit Data Siswa";
        $view = __DIR__ . '/../views/edit_student_form.php';
        extract($viewData);
        require_once __DIR__ . '/../views/layouts/main.php'; // Panggil layout utama
    }
}

/**
 * Menangani penghapusan siswa.
 */
function handle_delete_student() {
    // Penjaga: Pastikan pengguna sudah login dan adalah admin.
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
        header('Location: index.php');
        exit;
    }
    // ... (Logika hapus siswa tidak berubah) ...
}
// ... (Fungsi handle_manage_students, handle_add_student, dll. tetap ada) ...

/**
 * Menampilkan daftar semua siswa yang terdaftar.
 */
function handle_list_all_students() {
    // Penjaga: Hanya admin
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
        header('Location: index.php');
        exit;
    }

    $pdo = get_pdo_connection();
    $viewData = [];

    try {
        // Query untuk mengambil data siswa, nama kelas, dan nama orang tua
        $query = "
            SELECT
                s.id,
                s.name AS student_name,
                s.nisn,
                s.photo,
                c.name AS class_name,
                p.name AS parent_name
            FROM students s
            LEFT JOIN classes c ON s.class_id = c.id
            LEFT JOIN parents p ON s.id = p.student_id
            ORDER BY c.name ASC, s.name ASC
        ";
        $stmt = $pdo->query($query);
        $viewData['all_students'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        die("Database Error saat mengambil daftar siswa: " . $e->getMessage());
    }

    // Panggil layout utama
    $title = "Daftar Semua Siswa";
    $view = __DIR__ . '/../views/list_students.php'; // File view baru
    extract($viewData);
    require_once __DIR__ . '/../views/layouts/main.php';
}
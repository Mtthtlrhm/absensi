<?php
// public/index.php

session_start();

// Logika time-out sesi Anda (Sudah Benar)
if (isset($_SESSION['last_activity'])) {
    $timeout_duration = 1800; // 30 menit
    if ((time() - $_SESSION['last_activity']) > $timeout_duration) {
        session_unset();
        session_destroy();
        header('Location: ?action=login&status=session_expired');
        exit;
    }
}
$_SESSION['last_activity'] = time();

// Memuat autoloader (Sudah Benar)
require_once __DIR__ . '/../vendor/autoload.php';

// --- Array Rute (Diperbarui) ---
$routes = [
    // Rute Home (Sudah Benar)
    'home'              => ['file' => __DIR__ . '/../app/controllers/DashboardController.php', 'handler' => 'handle_home_dispatcher'],
    
    // Rute Auth (Sudah Benar)
    'login'             => ['file' => __DIR__ . '/../app/controllers/AuthController.php', 'handler' => 'handle_login'],
    'logout'            => ['file' => __DIR__ . '/../app/controllers/AuthController.php', 'handler' => 'handle_logout'],
    
    // Rute CRUD Siswa (Sudah Benar)
    'manage_students'   => ['file' => __DIR__ . '/../app/controllers/StudentController.php', 'handler' => 'handle_manage_students'],
    'add_student'       => ['file' => __DIR__ . '/../app/controllers/StudentController.php', 'handler' => 'handle_add_student'],
    'edit_student'      => ['file' => __DIR__ . '/../app/controllers/StudentController.php', 'handler' => 'handle_edit_student'],
    'delete_student'    => ['file' => __DIR__ . '/../app/controllers/StudentController.php', 'handler' => 'handle_delete_student'],
    'list_all_students' => ['file' => __DIR__ . '/../app/controllers/StudentController.php', 'handler' => 'handle_list_all_students'],

    // Rute CRUD Kelas (Sudah Benar)
    'manage_classes'    => ['file' => __DIR__ . '/../app/controllers/ClassController.php', 'handler' => 'handle_manage_classes'],
    'delete_class'      => ['file' => __DIR__ . '/../app/controllers/ClassController.php', 'handler' => 'handle_delete_class'],

    // Rute CRUD Guru (Sudah Benar)
    'manage_teachers'   => ['file' => __DIR__ . '/../app/controllers/TeacherController.php', 'handler' => 'handle_manage_teachers'],
    'edit_teacher'      => ['file' => __DIR__ . '/../app/controllers/TeacherController.php', 'handler' => 'handle_edit_teacher'],
    'delete_teacher'    => ['file' => __DIR__ . '/../app/controllers/TeacherController.php', 'handler' => 'handle_delete_teacher'],
    
    // Rute Absensi (Diperbarui)
    'record_attendance' => ['file' => __DIR__ . '/../app/controllers/AttendanceController.php', 'handler' => 'handle_record_attendance'],
    
    // --- RUTE BARU UNTUK GURU ---
    'mulai_absensi'     => ['file' => __DIR__ . '/../app/controllers/AttendanceController.php', 'handler' => 'handle_mulai_absensi_page'],
    'simpan_absensi'    => ['file' => __DIR__ . '/../app/controllers/AttendanceController.php', 'handler' => 'handle_simpan_absensi'],
];

/**
 * Fungsi Dispatcher Home (Sudah Benar)
 */
function handle_home_dispatcher() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ?action=login');
        exit;
    }

    if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
        handle_admin_dashboard(); // Fungsi dari DashboardController.php
    } else {
        handle_teacher_dashboard(); // Fungsi dari DashboardController.php
    }
}

/**
 * Handler 404 (Sudah Benar)
 */
function handle_not_found() {
    http_response_code(404);
    $page_title = '404 Tidak Ditemukan';
    $view_to_load = __DIR__ . '/../app/views/errors/404.php'; // Asumsi Anda punya view 404
    // Jika tidak punya, ganti dengan echo:
    // echo "<h1>404 Not Found</h1><p>Maaf, halaman yang kamu cari tidak ditemukan.</p>";
    require __DIR__ . '/../app/views/main.php';
}

/**
 * LOGIKA UTAMA ROUTER (Sudah Benar)
 */
$action = $_GET['action'] ?? 'home';

if (isset($routes[$action])) {
    $route = $routes[$action];
    
    if ($route['file']) { 
        require_once $route['file']; 
    }

    if (function_exists($route['handler'])) {
        $handlerFunction = $route['handler'];
        $handlerFunction();
    } else {
        handle_not_found();
    }
} else {
    handle_not_found();
}
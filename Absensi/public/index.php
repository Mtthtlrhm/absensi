<?php
session_start();

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

require_once __DIR__ . '/../vendor/autoload.php';

$routes = [
    // --- RUTE 'home' SEKARANG DIARAHKAN KE CONTROLLER BARU ---
    'home'            => ['file' => __DIR__ . '/../app/controllers/DashboardController.php', 'handler' => 'handle_home_dispatcher'],
    
    'login'           => ['file' => __DIR__ . '/../app/controllers/AuthController.php', 'handler' => 'handle_login'],
    'logout'          => ['file' => __DIR__ . '/../app/controllers/AuthController.php', 'handler' => 'handle_logout'],
    
    // Rute CRUD Siswa
    'manage_students' => ['file' => __DIR__ . '/../app/controllers/StudentController.php', 'handler' => 'handle_manage_students'],
    'add_student'     => ['file' => __DIR__ . '/../app/controllers/StudentController.php', 'handler' => 'handle_add_student'],
    'edit_student'    => ['file' => __DIR__ . '/../app/controllers/StudentController.php', 'handler' => 'handle_edit_student'],
    'delete_student'  => ['file' => __DIR__ . '/../app/controllers/StudentController.php', 'handler' => 'handle_delete_student'],
    'list_all_students' => ['file' => __DIR__ . '/../app/controllers/StudentController.php', 'handler' => 'handle_list_all_students'],

    // Rute CRUD Kelas
    'manage_classes'  => ['file' => __DIR__ . '/../app/controllers/ClassController.php', 'handler' => 'handle_manage_classes'],
    'delete_class'    => ['file' => __DIR__ . '/../app/controllers/ClassController.php', 'handler' => 'handle_delete_class'],

    // Rute CRUD Guru
    'manage_teachers' => ['file' => __DIR__ . '/../app/controllers/TeacherController.php', 'handler' => 'handle_manage_teachers'],
    'edit_teacher'    => ['file' => __DIR__ . '/../app/controllers/TeacherController.php', 'handler' => 'handle_edit_teacher'],
    'delete_teacher'  => ['file' => __DIR__ . '/../app/controllers/TeacherController.php', 'handler' => 'handle_delete_teacher'],
    
    // Rute API
    'record_attendance' => ['file' => __DIR__ . '/../app/controllers/AttendanceController.php', 'handler' => 'handle_record_attendance']
];

/**
 * FUNGSI INI MENGGANTIKAN handle_home() LAMA.
 * Tugasnya hanya memeriksa peran dan memanggil controller yang sesuai.
 */
function handle_home_dispatcher() {
    // Penjaga: Jika belum login, paksa ke halaman login.
    if (!isset($_SESSION['user_id'])) {
        header('Location: ?action=login');
        exit;
    }

    // Panggil controller dashboard yang benar
    if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
        handle_admin_dashboard(); // Fungsi dari DashboardController.php
    } else {
        handle_teacher_dashboard(); // Fungsi dari DashboardController.php
    }
}

/**
 * Handler untuk halaman tidak ditemukan (404).
 */
function handle_not_found() {
    http_response_code(404);
    echo "<h1>404 Not Found</h1><p>Maaf, halaman yang kamu cari tidak ditemukan.</p>";
}

/**
 * LOGIKA UTAMA ROUTER
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
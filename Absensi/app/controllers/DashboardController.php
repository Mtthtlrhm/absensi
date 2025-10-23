<?php
// app/controllers/DashboardController.php
require_once __DIR__ . '/../../core/Database.php';

/**
 * Menyiapkan dan menampilkan dashboard untuk Admin.
 */
function handle_admin_dashboard() {
    $pdo = get_pdo_connection();
    $viewData = [];
    
    try { 
        // Ambil data statistik
        $viewData['studentCount'] = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
        $viewData['teacherCount'] = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'teacher'")->fetchColumn();
        $viewData['classCount'] = $pdo->query("SELECT COUNT(*) FROM classes")->fetchColumn();
    } catch (PDOException $e) {
        $viewData['studentCount'] = $viewData['teacherCount'] = $viewData['classCount'] = 'Error';
        error_log("Dashboard stats query failed: " . $e->getMessage()); // Catat error ke log
    }

    // Tampilkan melalui layout utama
    $title = "Dashboard Admin";
    $view = __DIR__ . '/../views/pages/dashboard_admin.php'; // Path ke konten
    extract($viewData);
    require_once __DIR__ . '/../views/layouts/main.php'; // Panggil kerangka
}

/**
 * Menyiapkan dan menampilkan dashboard untuk Guru.
 */
function handle_teacher_dashboard() {
    $viewData = []; // Tidak ada data khusus untuk saat ini

    // Tampilkan melalui layout utama
    $title = "Dashboard Guru";
    $view = __DIR__ . '/../views/pages/dashboard_teacher.php'; // Path ke konten
    extract($viewData);
    require_once __DIR__ . '/../views/layouts/main.php'; // Panggil kerangka
}
<?php
// app/controllers/ClassController.php
require_once __DIR__ . '/../../core/Database.php';

function handle_manage_classes() {
    // Penjaga Keamanan
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
        header('Location: index.php');
        exit;
    }

    $pdo = get_pdo_connection();
    $viewData = [];

    // Proses POST (Tidak Berubah)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // ... (Logika tambah kelas) ...
        // Pastikan redirect setelah POST berhasil
        header('Location: ?action=manage_classes&status=add_success');
        exit;
    }

    // Ambil daftar kelas (Tidak Berubah)
    try {
        $stmt = $pdo->query("SELECT id, name, academic_year FROM classes ORDER BY name ASC");
        $viewData['classes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) { /* ... Error handling ... */ }

    // =============================================================
    // BAGIAN PENTING: Panggil layout utama
    // =============================================================
    $title = "Manajemen Kelas";
    $view = __DIR__ . '/../views/class_management_form.php'; // Path ke file konten
    extract($viewData);
    require_once __DIR__ . '/../views/layouts/main.php'; // Panggil kerangka layout
}

function handle_delete_class() {
    // Penjaga Keamanan
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
        header('Location: index.php');
        exit;
    }
    // ... (Logika hapus kelas tidak berubah) ...
    // Pastikan redirect setelah selesai
    header('Location: ?action=manage_classes&status=delete_success');
    exit;
}
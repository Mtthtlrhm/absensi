<?php
// app/controllers/TeacherController.php
require_once __DIR__ . '/../../core/Database.php';

function handle_manage_teachers() {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
        header('Location: index.php');
        exit;
    }

    $pdo = get_pdo_connection();
    $viewData = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $nuptk = $_POST['nuptk'] ?? null;
        $password = $_POST['password'] ?? '';

        if (!empty($name) && !empty($email) && !empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                die("Error: Email sudah terdaftar.");
            } else {
                $sql = "INSERT INTO users (name, email, nuptk, password, role) VALUES (?, ?, ?, ?, 'teacher')";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$name, $email, $nuptk, $hashed_password]);
                header('Location: ?action=manage_teachers&status=add_success');
                exit;
            }
        }
    }

    $stmt = $pdo->query("SELECT id, name, email, nuptk FROM users WHERE role = 'teacher' ORDER BY name ASC");
    $viewData['teachers'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $title = "Manajemen Guru";
    $view = __DIR__ . '/../views/teacher_management_form.php';
    extract($viewData);
    
    // =================================================================
    // PATH DIPERBAIKI DI SINI (hanya naik satu level)
    // =================================================================
    require_once __DIR__ . '/../views/layouts/main.php';
}

function handle_edit_teacher() {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
        header('Location: index.php');
        exit;
    }

    $pdo = get_pdo_connection();
    $viewData = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'] ?? null;
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $nuptk = $_POST['nuptk'] ?? null;
        $password = $_POST['password'] ?? '';

        if (!empty($id) && !empty($name) && !empty($email)) {
            try {
                if (!empty($password)) {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $sql = "UPDATE users SET name = ?, email = ?, nuptk = ?, password = ? WHERE id = ? AND role = 'teacher'";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$name, $email, $nuptk, $hashed_password, $id]);
                } else {
                    $sql = "UPDATE users SET name = ?, email = ?, nuptk = ? WHERE id = ? AND role = 'teacher'";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$name, $email, $nuptk, $id]);
                }
                header('Location: ?action=manage_teachers&status=edit_success');
                exit;
            } catch (PDOException $e) {
                die("Database Error: " . $e->getMessage());
            }
        }
    }

    $id = $_GET['id'] ?? null;
    if (!$id) { header('Location: ?action=manage_teachers'); exit; }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND role = 'teacher'");
    $stmt->execute([$id]);
    $viewData['teacher'] = $stmt->fetch();
    
    if (!$viewData['teacher']) { die("Guru tidak ditemukan."); }

    $title = "Edit Data Guru";
    $view = __DIR__ . '/../views/edit_teacher_form.php';
    extract($viewData);

    // =================================================================
    // PATH DIPERBAIKI DI SINI JUGA (hanya naik satu level)
    // =================================================================
    require_once __DIR__ . '/../views/layouts/main.php';
}

function handle_delete_teacher() {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
        header('Location: index.php');
        exit;
    }
    $id = $_GET['id'] ?? null;
    if ($id && is_numeric($id)) {
        try {
            $pdo = get_pdo_connection();
            $pdo->beginTransaction();
            $pdo->prepare("UPDATE classes SET teacher_id = NULL WHERE teacher_id = ?")->execute([$id]);
            $pdo->prepare("DELETE FROM users WHERE id = ? AND role = 'teacher'")->execute([$id]);
            $pdo->commit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            die("Database Error: " . $e->getMessage());
        }
    }
    header('Location: ?action=manage_teachers&status=delete_success');
    exit;
}
<?php

require_once __DIR__ . '/../../core/Database.php';

function handle_login() {

    if (isset($_SESSION['user_id'])) {
        header('Location: index.php');
        exit;
    }

    $error_message = null;


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $error_message = "Email dan password wajib diisi.";
        } else {
            try {
                $pdo = get_pdo_connection();
                $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch();

                if ($user && password_verify($password, $user['password'])) {

                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_role'] = $user['role'];
                    $_SESSION['last_activity'] = time();
                    
                    header('Location: index.php');
                    exit;
                } else {
                    
                    $error_message = "Email atau password yang Anda masukkan salah.";
                }
            } catch (PDOException $e) {
                $error_message = "Terjadi masalah dengan koneksi database.";
            }
        }
    }
    require_once __DIR__ . '/../views/login_form.php';
}

function handle_logout() {

    session_unset();
    
    session_destroy();
    
    header('Location: ?action=login');
    exit;
}
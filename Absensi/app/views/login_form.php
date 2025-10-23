<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Absensi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            margin: 0;
            /* Latar belakang foto sekolah */
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('/images/sekolahh-bg.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Ini adalah satu-satunya kotak "frosted glass" */
        .login-container {
            width: 420px; /* Sedikit lebih lebar */
            padding: 2.5em 3em;
            background: rgba(0, 0, 0, 0.0); /* Background gelap transparan */
            backdrop-filter: blur(5px); /* Efek blur "seperti sisi kiri" */
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2); /* Border putih transparan */
            text-align: center; /* Konten di tengah */
            color: white; /* Semua teks di dalam jadi putih */
        }
        h2 {
            font-size: 2.2em;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 1em;
            color: #e0e0e0; /* Putih pudar */
            margin-bottom: 2.5em;
        }
        .form-group {
            margin-bottom: 1.5em;
            text-align: left; /* Label dan input rata kiri */
        }
        .form-group label {
            display: block;
            margin-bottom: 0.7em;
            color: white; /* Label putih pudar */
            font-weight: 600;
            font-size: 0.9em;
        }
        .input-field {
            width: 100%;
            padding: 12px 15px;
            box-sizing: border-box;
            border-radius: 10px;
            /* Input field semi-transparan */
            background: rgba(255, 255, 255, 0.1); 
            border: 1px solid rgba(255, 255, 255, 0.4);
            color: black; /* Teks yang diketik jadi putih */
            font-size: 1em;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .input-field::placeholder { /* Untuk browser tertentu */
             color: #ccc;
        }
        .input-field:focus {
            outline: none;
            border-color: white; /* Border putih solid saat focus */
            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.3);
        }

        /* Tombol Putih Solid agar Kontras */
        button.btn-primary {
            width: 100%;
            padding: 14px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            border: none;
            border-radius: 10px;
            transition: all 0.3s;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            height: 48px;
            background-color: #456882; /* Tombol putih solid */
            color: white; /* Teks tombol biru gelap */
            margin-top: 1.5em;
        }
        button.btn-primary:hover {
            background-color: #234C6A; /* Efek hover */
        }
        button:disabled { background-color: #95a5a6; cursor: not-allowed; }

        /* Style Pesan Error (dibuat terang agar kontras) */
        .error-message { padding: 1em; border-radius: 8px; margin-bottom: 1.5em; font-size: 0.9em; text-align: center; }
        .error-login { margin-bottom: 1em;  color: #F90716; }
        .error-session { background-color: #fff3cd; border: 1px solid #ffeeba; color: #856404; }

        /* Spinner (tetap putih) */
        .spinner { width: 20px; height: 20px; border: 3px solid rgba(0, 0, 0, 0.3); border-top-color: #2c3e50; border-radius: 50%; animation: spin 1s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body>
    
    <div class="login-container">
        
        <h2>Selamat Datang di Absensi</h2>
        <p class="subtitle">Silahkan login untuk melanjutkan.</p>

        <?php 
        if (isset($_GET['status']) && $_GET['status'] === 'session_expired') {
            echo "<div class='error-message error-session'>Sesi Anda telah berakhir.</div>";
        }
        if (isset($error_message)): ?>
            <div class='error-message error-login'><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <form action="?action=login" method="post" id="loginForm">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="input-field" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="input-field" required>
            </div>
            
            <button type="submit" id="loginButton" class="btn-primary">
                <span class="button-text">Login</span>
            </button>
        </form>
    </div>

    <script>
        // JavaScript untuk loading spinner
        const loginForm = document.getElementById('loginForm');
        const loginButton = document.getElementById('loginButton');
        const buttonText = document.querySelector('.button-text');

        loginForm.addEventListener('submit', function(e) {
            if (loginForm.checkValidity()) {
                loginButton.disabled = true;
                if (buttonText) {
                    buttonText.style.display = 'none';
                }
                const spinner = document.createElement('div');
                spinner.className = 'spinner';
                loginButton.appendChild(spinner);
            }
        });
    </script>
</body>
</html>
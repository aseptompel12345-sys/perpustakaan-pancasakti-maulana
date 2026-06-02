<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Perpustakaan Pancasakti | Log in</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">

    <style>
        body.login-page {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), 
                        url('foto/background.png') no-repeat center center fixed;
            background-size: cover;
            backdrop-filter: blur(5px);
            padding-bottom: 50px; 
            height: auto;
        }
        .login-box {
            margin-top: 50px; 
            margin-bottom: 50px;
        }
        .login-logo {
            margin-bottom: 10px;
        }
        .login-logo img {
            margin-bottom: -15px; 
        }
        .login-logo b {
            color: white;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.8);
            font-size: 2.5rem;
        }
        .card {
            border-radius: 15px;
            overflow: hidden;
            border: none;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 15px 35px rgba(0,0,0,0.4) !important;
        }
        .forgot-link {
            font-size: 0.9rem;
            color: #6c757d;
            transition: 0.3s;
        }
        .forgot-link:hover {
            color: #28a745;
            text-decoration: none;
        }
        .text-miring {
            font-style: italic;
        }
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo text-center">
        <a href="#">
            <img src="foto/Logo.png" alt="Pancasakti Logo" height="160" width="180">
            <br>
            <b>Pancasakti</b>
        </a>
    </div>
    
    <div class="card card-outline card-success">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Silakan masuk untuk mengakses Perpustakaan</p>

            <form action="login_proses.php" method="post">
                <div class="input-group mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Username" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text" id="togglePassword" style="cursor: pointer;">
                            <span class="fas fa-eye" id="eyeIcon"></span>
                        </div>
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="icheck-success">
                            <input type="checkbox" id="remember">
                            <label for="remember">Ingat Saya</label>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <button type="submit" name="login" class="btn btn-success btn-block shadow">
                            <b>MASUK</b>
                        </button>
                    </div>
                </div>
            </form>

            <div class="social-auth-links text-center mb-4">
                <p class="text-muted small text-miring"><i><b>-- BELUM DAFTAR? SILAHKAN PILIN PENDAFTARAN --<b><i></p>

                <a href="daftar_anggota_siswa_from.php" class="btn btn-block btn-info shadow-sm">
                    <i class="fas fa-user-plus mr-2"></i> Daftar Anggota Sebagai Siswa
                </a>
                <a href="daftar_anggota_guru_from.php" class="btn btn-block btn-info shadow-sm mt-2">
                    <i class="fas fa-user-plus mr-2"></i> Daftar Anggota Sebagai Guru
                </a>
                <a href="daftar_admin_from.php" class="btn btn-block btn-outline-danger shadow-sm mt-2">
                    <i class="fas fa-user-shield mr-2"></i> Daftar Sebagai Admin
                </a>
            </div>

            <p class="mb-1 text-center">
                <a href="forgot-password.html" class="forgot-link">
                    <i class="fas fa-question-circle mr-1"></i> Lupa Password?
                </a>
            </p>
        </div>
    </div>
</div>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    const eyeIcon = document.querySelector('#eyeIcon');

    togglePassword.addEventListener('click', function () {
        // Alihkan tipe input
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        
        // Alihkan ikon mata
        eyeIcon.classList.toggle('fa-eye-slash');
        eyeIcon.classList.toggle('fa-eye');
    });
</script>

</body>
</html>
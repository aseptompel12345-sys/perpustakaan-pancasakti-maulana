<?php
session_start();
// Cek apakah data profil sudah diisi di session
if (!isset($_SESSION['temp_admin_data'])) {
    echo "<script>alert('Silahkan isi profil admin terlebih dahulu!'); window.location='../../daftar_admin_from.php';</script>";
    exit();
}
$data = $_SESSION['temp_admin_data'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buat Akun Admin | Perpustakaan Pancasakti</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">

    <style>
        body.login-page {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                        url('../../foto/background.png') no-repeat center center fixed;
            background-size: cover;
            backdrop-filter: blur(5px);
            display: block !important; 
            height: auto !important;
            min-height: 100vh;
            padding-top: 50px;
            padding-bottom: 50px;
            margin: 0;
        }

        .login-box {
            width: 480px;
            margin: 0 auto;
        }

        .login-logo b {
            color: white;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.8);
            font-size: 2.5rem;
            display: block;
        }

        .card {
            border-radius: 20px !important; 
            border: none;
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 15px 35px rgba(0,0,0,0.4) !important;
        }

        .login-card-body {
            border-radius: 20px !important;
            padding: 30px !important;
        }

        .info-admin-box {
            background: #f0f7ff;
            border-radius: 15px;
            padding: 20px;
            border-left: 6px solid #007bff;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
        }

        .admin-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 15px;
            border: 2px solid #007bff;
        }

        .btn-primary {
            border-radius: 10px;
            padding: 12px;
            font-weight: bold;
            background: #007bff;
            border: none;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }

        .form-control {
            border-radius: 10px;
            height: 45px;
        }
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo text-center mb-4">
        <img src="../../foto/Logo.png" alt="Logo" width="180px" height="160">
        <br>
        <b>Pancasakti</b>
    </div>

    <div class="card">
        <div class="card-body login-card-body">
            <h4 class="text-center font-weight-bold mb-4">Langkah Terakhir</h4>
            
            <div class="info-admin-box">
                <img src="../../FOTOS/foto_admin/<?php echo $data['Foto']; ?>" class="admin-avatar">
                <div>
                    <h6 class="mb-0 font-weight-bold"><?php echo $data['Nama_lengkap']; ?></h6>
                    <small class="text-muted"><?php echo $data['Jabatan']; ?></small>
                </div>
            </div>

            <form action="../akun_admin_proses.php" method="post">
                <div class="form-group mb-3">
                    <label><i class="fas fa-user-shield mr-1"></i> Username Admin</label>
                    <input type="text" name="username" class="form-control" placeholder="Buat username admin..." required>
                </div>
                
                <div class="form-group mb-4">
                    <label><i class="fas fa-lock mr-1"></i> Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password kuat..." required>
                        <div class="input-group-append">
                            <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                <i class="fas fa-eye" id="eyeIcon"></i>
                            </span>
                        </div>
                    </div>
                    <small class="text-danger">*Gunakan kombinasi huruf dan angka.</small>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="submit" name="simpan_admin" class="btn btn-primary btn-block shadow">
                            AKTIFKAN AKUN ADMIN <i class="fas fa-check-circle ml-1"></i>
                        </button>
                    </div>
                </div>
            </form>

            <div class="text-center mt-4">
                <a href="../../login_from.php" class="text-muted small">Batal dan kembali ke Login</a>
            </div>
        </div>
    </div>
</div>

<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    const eyeIcon = document.querySelector('#eyeIcon');

    togglePassword.addEventListener('click', function (e) {
        // Alihkan tipe input antara password dan text
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        
        // Alihkan ikon mata (buka/tutup)
        eyeIcon.classList.toggle('fa-eye-slash');
    });
</script>
</body>
</html>
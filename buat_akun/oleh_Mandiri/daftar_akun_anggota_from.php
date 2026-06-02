<?php
session_start();

// Cek apakah ada data session dari tahap profil sebelumnya
// Kita cek salah satu, apakah itu session siswa atau guru
if (isset($_SESSION['temp_siswa'])) {
    $data = $_SESSION['temp_siswa'];
    $tipe = "Siswa";
    $nama = $data['Nama_siswa']; // Sesuaikan dengan key di $_POST pendaftaran siswa
    $identitas = "NISN: " . $data['NISN'];
    $warna_tema = "card-success"; // Hijau untuk siswa
    $btn_tema = "btn-success";
} elseif (isset($_SESSION['temp_guru'])) {
    $data = $_SESSION['temp_guru'];
    $tipe = "Guru";
    $nama = $data['Nama_guru']; // Sesuaikan dengan key di $_POST pendaftaran guru
    $identitas = "NIP: " . $data['NIP'];
    $warna_tema = "card-success"; // Tetap hijau agar konsisten dengan pendaftaran guru mandiri
    $btn_tema = "btn-success";
} else {
    // Jika tidak ada session sama sekali, tendang balik ke login
    echo "<script>alert('Sesi berakhir atau data profil belum diisi!'); window.location='login_from.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buat Akun | Perpustakaan Pancasakti</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../../dist/css/adminlte.min.css">

    <style>
    body.login-page {
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), 
                    url('../../../foto/background.png') no-repeat center center fixed;
        background-size: cover;
        backdrop-filter: blur(5px);
        /* Ganti flex menjadi block agar scroll berfungsi normal */
        display: block !important; 
        height: auto !important;
        min-height: 100vh;
        /* Tambah jarak atas dan bawah yang cukup banyak */
        padding-top: 80px;
        padding-bottom: 80px;
        margin: 0;
    }

    .login-box {
        width: 500px;
        margin: 0 auto; /* Tengah secara horizontal */
    }

    .card {
        /* Kembalikan sudut tumpul */
        border-radius: 20px !important; 
        border: none;
        background: rgba(255, 255, 255, 0.96);
        box-shadow: 0 20px 50px rgba(0,0,0,0.5) !important;
        /* Hapus overflow hidden agar radius terlihat jelas di AdminLTE */
        overflow: visible !important; 
    }

    /* Pastikan bagian dalam kartu juga ikut tumpul */
    .login-card-body {
        border-radius: 20px !important;
    }

    .login-logo b {
        color: white;
        text-shadow: 2px 2px 8px rgba(0,0,0,0.8);
        font-size: 2.5rem;
        display: block;
    }

    .form-control-lg {
        border-radius: 10px;
        font-size: 1rem;
    }

    .info-user-box {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 15px;
        border-left: 5px solid #28a745;
        margin-bottom: 20px;
    }

    .btn-custom {
        border-radius: 10px;
        padding: 12px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Merapikan lengkungan pada bagian icon input */
    .input-group-text {
        border-radius: 0 10px 10px 0 !important;
        border-left: none;
    }
    
    .form-control-lg {
        border-right: none;
    }
</style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo text-center mb-4">
        <img src="../../../foto/Logo.png" alt="Logo" width="180px" height="160">
        <br>
        <b>Pancasakti</b>
    </div>

    <div class="card <?php echo $warna_tema; ?> card-outline">
        <div class="card-body login-card-body p-4">
            <h3 class="text-center font-weight-bold mb-1">Langkah Terakhir</h3>
            <p class="text-center text-muted mb-4">Tentukan kredensial login Anda</p>

            <div class="info-user-box">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <i class="fas fa-user-circle fa-3x text-success"></i>
                    </div>
                    <div>
                        <small class="text-uppercase text-muted" style="font-size: 10px; font-weight: bold;">Mendaftarkan Akun <?php echo $tipe; ?>:</small>
                        <h5 class="mb-0 font-weight-bold text-dark"><?php echo strtoupper($nama); ?></h5>
                        <small class="text-success font-weight-bold"><?php echo $identitas; ?></small>
                    </div>
                </div>
            </div>

            <form action="../../akun_proses.php" method="post">
                <div class="form-group mb-3">
                    <label>Username</label>
                    <div class="input-group">
                        <input type="text" name="username" class="form-control form-control-lg" placeholder="Pilih Username Unik" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-at"></span></div>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label>Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control form-control-lg" placeholder="Minimal 6 Karakter" required>
                        <div class="input-group-append" style="cursor: pointer;" onclick="togglePassword()">
                            <div class="input-group-text"><span class="fas fa-eye" id="toggleIcon"></span></div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info border-0 shadow-sm mb-4" style="border-radius: 10px; font-size: 13px;">
                    <i class="fas fa-info-circle mr-1"></i> <b>Penting:</b> Ingat baik-baik username dan password Anda untuk masuk ke perpustakaan.
                </div>

                <button type="submit" name="simpan_mandiri" class="btn <?php echo $btn_tema; ?> btn-block btn-custom shadow">
                    Selesaikan Pendaftaran <i class="fas fa-check-circle ml-1"></i>
                </button>
                
                <div class="text-center mt-3">
                    <a href="../../../login_from.php" class="text-muted small">Batal dan kembali ke halaman utama</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="../../../plugins/jquery/jquery.min.js"></script>
<script src="../../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
function togglePassword() {
    const passwordField = document.getElementById('password');
    const icon = document.getElementById('toggleIcon');
    if (passwordField.type === "password") {
        passwordField.type = "text";
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = "password";
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
</body>
</html>
<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Cek apakah session data kelas sudah ada dari proses sebelumnya
if (!isset($_SESSION['temp_kelas'])) {
    echo "<script>alert('Silahkan isi profil kelas terlebih dahulu!'); window.location='index_Admin.php?page=anggota_kelas_from';</script>";
    exit();
}
$data = $_SESSION['temp_kelas'];
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-user-plus mr-2 text-primary"></i>Buat Akun untuk Kelas :</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                
                <div class="info-box shadow-sm mb-3 bg-white border-left border-primary">
                    <span class="info-box-icon bg-primary"><i class="fas fa-school"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-muted">Mendaftarkan Akun Untuk:</span>
                        <h4 class="info-box-number font-weight-bold mb-0"><?php echo strtoupper($data['Nama_kelas']); ?></h4>
                        <span class="text-sm text-primary">Wali Kelas: <?php echo $data['Wali_kelas']; ?></span>
                    </div>
                </div>

                <div class="card card-primary card-outline shadow">
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bold">
                            <i class="fas fa-lock mr-2 text-primary"></i> Tentukan Kredensial Login Kelas
                        </h3>
                    </div>
                    
                    <form action="buat_akun/akun_proses.php" method="POST">
                        <div class="card-body">
                            <p class="text-muted mb-4">Silahkan tentukan username dan password yang akan digunakan oleh <b>Penanggung Jawab Kelas</b> untuk login.</p>

                            <div class="form-group">
                                <label for="username">Username Kelas</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" name="username" class="form-control form-control-lg" 
                                        placeholder="Masukan Username Kelas (Contoh: kls_10pplg)" required autocomplete="off">
                                </div>
                                <small class="text-muted">*Username ini akan digunakan secara kolektif oleh pengurus kelas.</small>
                            </div>      

                            <div class="form-group">
                                <label for="password">Password</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    </div>
                                    <input type="password" name="password" id="password" class="form-control form-control-lg" 
                                        placeholder="Masukan Password Minimal 6 Karakter" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text" style="cursor: pointer;" onclick="togglePassword()">
                                            <i class="fas fa-eye text-muted" id="toggleIcon"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="callout callout-info mt-3 bg-light border-left border-info">
                                <h6 class="font-weight-bold text-info"><i class="fas fa-exclamation-circle mr-2"></i> Perhatian:</h6>
                                <p class="text-sm mb-0">Pastikan Anda mencatat username dan password ini. Akun kelas ini akan mengelola peminjaman buku paket secara massal.</p>
                            </div>
                        </div>

                        <div class="card-footer bg-transparent py-3">
                            <div class="row">
                                <div class="col-md-6 mb-2 mb-md-0">
                                    <a href="index_Admin.php?page=anggota_kelas_from" class="btn btn-default btn-block">
                                        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Profil
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" name="simpan_final" class="btn btn-primary btn-block shadow-sm">
                                        <i class="fas fa-check-circle mr-1"></i> SELESAIKAN PENDAFTARAN
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Fungsi untuk melihat password (sama dengan fitur di siswa/guru)
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
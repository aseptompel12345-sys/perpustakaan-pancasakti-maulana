<?php
include '../../config/koneksi.php'; 

// Bersihkan session temp lain agar tidak bentrok
unset($_SESSION['temp_guru']);
unset($_SESSION['temp_siswa']);
unset($_SESSION['temp_admin_data']);


if (isset($_POST['lanjut_akun'])) {
    // 1. Ambil Data dari Form & Bersihkan Karakter Aneh
    $nama_kelas     = mysqli_real_escape_string($db, $_POST['nama_kelas']);
    $wali_kelas     = mysqli_real_escape_string($db, $_POST['wali_kelas']);
    $jumlah_siswa   = mysqli_real_escape_string($db, $_POST['jumlah_siswa']);
    $pj_kelas       = mysqli_real_escape_string($db, $_POST['pj_kelas']);

    // 2. Menampung data ke Session temp_kelas
    // Nama key di dalam array ini harus sama dengan yang nanti dipanggil di akun_proses.php
    $_SESSION['temp_kelas'] = [
        'Nama_kelas'            => $nama_kelas,
        'Wali_kelas'            => $wali_kelas,
        'Jumlah_siswa'          => $jumlah_siswa,
        'Penanggung_jawab'      => $pj_kelas
    ];

    session_write_close(); 

    // 3. Arahkan ke halaman buat akun (sesuaikan parameter page-nya)
    echo "<script>
            alert('Data Kelas Disimpan Sementara. Silahkan buat username dan password untuk kelas $nama_kelas!'); 
            window.location='../../index_Admin.php?page=akun_kelas';
          </script>";
    exit();
}
?>
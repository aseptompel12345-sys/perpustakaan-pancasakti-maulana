<?php
include '../../config/koneksi.php';

// Bersihkan session temp lain agar tidak bentrok
unset($_SESSION['temp_guru']);
unset($_SESSION['temp_kelas']);
unset($_SESSION['temp_admin_data']);


if (isset($_POST['simpan'])) {
    // 1. Ambil Data dari Form (Gunakan mysqli_real_escape_string untuk keamanan)
    $nisn = mysqli_real_escape_string($db, $_POST['NISN']);
    $nama = mysqli_real_escape_string($db, $_POST['Nama_siswa']);

    // 2. Logika Upload Foto
    $foto_nama = $_FILES['Foto']['name'];
    $foto_tmp  = $_FILES['Foto']['tmp_name'];
    
    if (!empty($foto_nama)) {
        // Nama file unik agar tidak tertimpa
        $ekstensi = pathinfo($foto_nama, PATHINFO_EXTENSION);
        $nama_foto_baru = date('dmYHis') . "_" . $nisn . "." . $ekstensi;
        $path = "../../FOTOS/foto_siswa/" . $nama_foto_baru; 
        
        move_uploaded_file($foto_tmp, $path);
    } else {
        $nama_foto_baru = 'default.jpg'; 
    }

    // 3. Menampung data ke Session (KUNCI ARRAY DISAMAKAN DENGAN AKUN_PROSES.PHP)
    // Penting: Gunakan 'NISN', 'Nama_siswa', dll (sesuai variabel di query insert Anda)
    $_SESSION['temp_siswa'] = [
        'NISN'          => $nisn,
        'Nama_siswa'    => $nama,
        'Jenis_kelamin' => $_POST['Jenis_kelamin'],
        'Agama'         => $_POST['Agama'],
        'Kelas'         => $_POST['Kelas'],
        'Jurusan'       => $_POST['Jurusan'],
        'Alamat'        => $_POST['Alamat'],
        'No_tlp'        => $_POST['No_tlp'],
        'Foto'          => $nama_foto_baru 
    ];

    // 4. Logika Pengalihan (Redirect)
    // Cek apakah ada Admin yang sedang login
    if (isset($_SESSION['Id_admin'])) {
        // Jika Admin: Masuk ke halaman buat akun di Dashboard
        echo "<script>
                alert('Profil Siswa Disimpan. Lanjutkan buat akun!'); 
                window.location='../../index_Admin.php?page=akun_siswa';
              </script>";
    } else {
        // Jika Mandiri (Bukan Admin): Lari ke file pendaftaran akun di luar
        echo "<script>
                alert('Data Profil Berhasil Diisi. Silahkan tentukan Username & Password!'); 
                window.location='../../buat_akun//oleh_Mandiri/daftar_akun_anggota_from.php';
              </script>";
    }
    exit();
}
?>
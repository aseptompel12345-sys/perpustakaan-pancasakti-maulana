<?php
include '../../config/koneksi.php';

// Bersihkan session temp lain agar tidak bentrok
unset($_SESSION['temp_kelas']);
unset($_SESSION['temp_siswa']);
unset($_SESSION['temp_admin_data']); 

if (isset($_POST['simpan'])) {
    // 1. Ambil Data dari Form (Gunakan mysqli_real_escape_string)
    $nip    = mysqli_real_escape_string($db, $_POST['NIP']);
    $nama   = mysqli_real_escape_string($db, $_POST['Nama_guru']);

    // 2. Logika Upload Foto
    $foto_nama = $_FILES['Foto']['name'];
    $foto_tmp  = $_FILES['Foto']['tmp_name'];
    
    if (!empty($foto_nama)) {
        // Nama file unik: Tanggal_NIP.ekstensi
        $ekstensi = pathinfo($foto_nama, PATHINFO_EXTENSION);
        $nama_foto_baru = date('dmYHis') . "_" . $nip . "." . $ekstensi;
        $path = "../../FOTOS/foto_guru/" . $nama_foto_baru; 
        
        move_uploaded_file($foto_tmp, $path);
    } else {
        $nama_foto_baru = 'default.jpg'; 
    }

    // 3. Menampung data ke Session 
    // PENTING: Kunci (Key) harus CAPITAL (NIP, Nama_guru, dll) agar terbaca di akun_proses.php
    $_SESSION['temp_guru'] = [
        'NIP'           => $nip,
        'Nama_guru'     => $nama,
        'Jenis_kelamin' => $_POST['Jenis_kelamin'],
        'Agama'         => $_POST['Agama'],
        'Alamat'        => $_POST['Alamat'],
        'No_tlp'        => $_POST['No_tlp'],
        'Foto'          => $nama_foto_baru 
    ];

    // 4. Logika Pengalihan (Redirect)
    if (isset($_SESSION['Id_admin'])) {
        // Jika sedang login sebagai ADMIN
        echo "<script>
                alert('Profil Guru Disimpan. Lanjutkan buat akun!'); 
                window.location='../../index_Admin.php?page=akun_guru';
              </script>";
    } else {
        // Jika PENDAFTARAN MANDIRI (di luar)
        // Gunakan path 2 tingkat keluar untuk mencari file daftar akun
        echo "<script>
                alert('Data Profil Guru Berhasil Diisi. Silahkan tentukan Username & Password!'); 
                window.location='../../buat_akun//oleh_Mandiri/daftar_akun_anggota_from.php';
              </script>";
    }
    exit();
}
?>
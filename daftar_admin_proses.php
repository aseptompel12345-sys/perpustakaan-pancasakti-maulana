<?php
include 'config/koneksi.php'; 

// Bersihkan session temp lain
unset($_SESSION['temp_guru'], $_SESSION['temp_siswa'], $_SESSION['temp_kelas']);

if (isset($_POST['simpan'])) {
    // 1. Ambil Data (DISESUAIKAN DENGAN atribut 'name' DI FORM ANDA)
    $nama    = mysqli_real_escape_string($db, $_POST['nama_lengkap']); // tadinya Nama_admin
    $jk      = mysqli_real_escape_string($db, $_POST['Jenis_kelamin']);
    $agama   = mysqli_real_escape_string($db, $_POST['Agama']);
    $alamat  = mysqli_real_escape_string($db, $_POST['alamat']);       // tadinya Alamat
    $no_tlp  = mysqli_real_escape_string($db, $_POST['no_tlp']);       // tadinya No_tlp
    $jabatan = mysqli_real_escape_string($db, $_POST['jabatan']);      // tadinya Jabatan

    // 2. Logika Upload Foto
    $foto_nama = $_FILES['Foto']['name'];
    $foto_tmp  = $_FILES['Foto']['tmp_name'];
    
    if (!empty($foto_nama)) {
        $ekstensi = pathinfo($foto_nama, PATHINFO_EXTENSION);
        $nama_foto_baru = "ADM_" . date('dmYHis') . "." . $ekstensi;
        $path = "FOTOS/foto_admin/" . $nama_foto_baru; 
        move_uploaded_file($foto_tmp, $path);
    } else {
        $nama_foto_baru = 'default_admin.jpg'; 
    }

    // 3. Simpan ke Session
    // Pastikan kunci array ini yang dipanggil di halaman 'Langkah Terakhir'
    $_SESSION['temp_admin_data'] = [
        'Nama_lengkap'  => $nama,    
        'Jenis_kelamin' => $jk,      
        'Agama'         => $agama,   
        'Alamat'        => $alamat,  
        'No_tlp'        => $no_tlp,  
        'Jabatan'       => $jabatan, 
        'Foto'          => $nama_foto_baru, 
        'Tgl_daftar'    => date('Y-m-d')
    ];

    echo "<script>
            alert('Data Profil Admin Berhasil Disimpan!'); 
            window.location.href='buat_akun/oleh_Mandiri/daftar_akun_admin_from.php';
          </script>";
    exit();
}
?>
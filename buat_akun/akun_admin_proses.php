<?php
// Hubungkan ke file koneksi Anda
include '../config/koneksi.php'; // Di sini sudah otomatis ada session_start() durasi 1 bulan

if (isset($_POST['simpan_admin'])) {
    // 1. Pastikan data profil di SESSION masih ada
    if (!isset($_SESSION['temp_admin_data'])) {
        echo "<script>alert('Sesi pendaftaran habis, silakan isi profil kembali.'); window.location.href='../daftar_admin_from.php';</script>";
        exit();
    }
    $data_profil = $_SESSION['temp_admin_data'];

    // 2. Ambil data akun dari FORM (Username & Password)
    $username = mysqli_real_escape_string($db, $_POST['username']);
    
    // --- FITUR CEK DUPLIKASI USERNAME ---
    $cek_user = mysqli_query($db, "SELECT Username FROM users WHERE Username = '$username'");
    if (mysqli_num_rows($cek_user) > 0) {
        echo "<script>
                alert('Maaf, username [$username] sudah digunakan. Silakan gunakan username lain!'); 
                window.history.back();
              </script>";
        exit(); // Berhenti, tidak lanjut simpan ke database
    }
    // ------------------------------------

    // Gunakan password_hash agar aman
    $password_input = $_POST['password'];
    $password_aman  = password_hash($password_input, PASSWORD_DEFAULT);

    // 3. MULAI PROSES INSERT KE 2 TABEL
    
    // Langkah A: Simpan ke tabel 'users' (Gunakan Huruf Kapital Sesuai Database Anda)
    $query_user = mysqli_query($db, "INSERT INTO users (Username, Password, Role) 
                                     VALUES ('$username', '$password_aman', 'admin')");

    if ($query_user) {
        // Ambil ID yang baru saja dibuat di tabel users
        $id_user_baru = mysqli_insert_id($db);

        // Langkah B: Simpan ke tabel 'admin'
        $sql_admin = "INSERT INTO admin (Id_user, Nama_lengkap, Jenis_kelamin, Agama, Alamat, No_tlp, Jabatan, Foto, Tgl_daftar, Status) 
                      VALUES (
                        '$id_user_baru', 
                        '{$data_profil['Nama_lengkap']}', 
                        '{$data_profil['Jenis_kelamin']}', 
                        '{$data_profil['Agama']}', 
                        '{$data_profil['Alamat']}', 
                        '{$data_profil['No_tlp']}', 
                        '{$data_profil['Jabatan']}', 
                        '{$data_profil['Foto']}', 
                        '{$data_profil['Tgl_daftar']}', 
                        'Tidak Aktif'
                      )";
        
        $query_admin = mysqli_query($db, $sql_admin);

        if ($query_admin) {
            // BERSIH-BERSIH: Jika semua berhasil, hapus session sementara
            unset($_SESSION['temp_admin_data']);

            echo "<script>
                    alert('Selamat! Akun Admin Anda telah AKTIF.'); 
                    window.location.href='../login_from.php';
                  </script>";
        } else {
            // Jika gagal di tabel admin, hapus user yang terlanjur dibuat agar data tidak "sampah"
            mysqli_query($db, "DELETE FROM users WHERE Id_user = '$id_user_baru'");
            echo "Gagal menyimpan profil: " . mysqli_error($db);
        }
    } else {
        echo "Gagal membuat akun login: " . mysqli_error($db);
    }
} else {
    header("Location: ../daftar_admin_from.php");
}
?>
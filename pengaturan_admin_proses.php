<?php
include "config/koneksi.php";

// Pastikan sesi admin valid sebelum memproses data
if (!isset($_SESSION['id_user'])) {
    header("Location: login_from.php");
    exit();
}

$id_user_login = $_SESSION['id_user'];

if (isset($_POST['proses_update_total'])) {
    // 1. Ambil & Amankan Input Data Profil dari Form Sebelah Kiri
    $nama      = mysqli_real_escape_string($db, $_POST['Nama_lengkap']);
    $jk        = mysqli_real_escape_string($db, $_POST['Jenis_kelamin']);
    $agama     = mysqli_real_escape_string($db, $_POST['Agama']);
    $no_tlp    = mysqli_real_escape_string($db, $_POST['No_tlp']);
    $jabatan   = mysqli_real_escape_string($db, $_POST['Jabatan']);
    $alamat    = mysqli_real_escape_string($db, $_POST['Alamat']);
    
    // 2. Ambil & Amankan Input Data Akun dari Form Sebelah Kanan
    $username   = mysqli_real_escape_string($db, $_POST['Username']);
    $pwd_lama   = $_POST['password_lama'];
    $pwd_baru   = $_POST['password_baru'];
    $konfirmasi = $_POST['konfirmasi_password'];

    // 3. Validasi Duplikasi Username
    $cek_user = mysqli_query($db, "SELECT * FROM users WHERE Username = '$username' AND Id_user != '$id_user_login'");
    if (mysqli_num_rows($cek_user) > 0) {
        echo "<script>
            alert('Gagal! Username tersebut sudah digunakan oleh orang lain.');
            window.location.href = 'index_Admin.php?page=pengaturan_admin';
        </script>";
        exit();
    }

    // 4. Jalankan Query Dasar: Update data akun dan profil utama
    mysqli_query($db, "UPDATE users SET Username = '$username' WHERE Id_user = '$id_user_login'");
    mysqli_query($db, "UPDATE admin SET 
                        Nama_lengkap = '$nama', 
                        Jenis_kelamin = '$jk', 
                        Agama = '$agama', 
                        No_tlp = '$no_tlp', 
                        Jabatan = '$jabatan', 
                        Alamat = '$alamat' 
                      WHERE Id_user = '$id_user_login'");

    // Perbarui session nama agar display di sidebar berubah secara real-time
    $_SESSION['nama_user'] = $nama;

    // 5. Logika Proses Upload Foto Profil Baru (Gaya WhatsApp)
    if ($_FILES['Foto']['name'] != '') {
        $nama_file = time() . '_' . $_FILES['Foto']['name'];
        $tmp_file  = $_FILES['Foto']['tmp_name'];
        $path      = "FOTOS/foto_admin/" . $nama_file; // Jalur penyimpanan disesuaikan sejajar

        if (move_uploaded_file($tmp_file, $path)) {
            // Ambil nama foto lama untuk dihapus dari penyimpanan fisik komputer
            $q_foto_lama = mysqli_query($db, "SELECT Foto FROM admin WHERE Id_user = '$id_user_login'");
            $d_foto_lama = mysqli_fetch_assoc($q_foto_lama);
            if (!empty($d_foto_lama['Foto']) && file_exists("FOTOS/foto_admin/" . $d_foto_lama['Foto'])) {
                unlink("FOTOS/foto_admin/" . $d_foto_lama['Foto']);
            }
            
            // Simpan nama file baru ke database dan perbarui session gambar di sidebar
            mysqli_query($db, "UPDATE admin SET Foto = '$nama_file' WHERE Id_user = '$id_user_login'");
            $_SESSION['foto_user'] = $nama_file;
        }
    }

    // 6. Protokol Keamanan Kata Sandi (Hanya berjalan jika password lama diketik)
    if (!empty($pwd_lama)) {
        // Ambil password terenkripsi yang ada di database saat ini
        $q_pwd = mysqli_query($db, "SELECT Password FROM users WHERE Id_user = '$id_user_login'");
        $d_pwd = mysqli_fetch_assoc($q_pwd);

        // LANGKAH 1: Verifikasi kecocokan password lama dengan password_verify
        if (password_verify($pwd_lama, $d_pwd['Password'])) {
            
            // LANGKAH 2: Pastikan password baru dan konfirmasinya sinkron/sama
            if (!empty($pwd_baru) && $pwd_baru === $konfirmasi) {
                // Enkripsi kata sandi baru dengan aman sebelum masuk ke database
                $password_secure = password_hash($pwd_baru, PASSWORD_DEFAULT);
                mysqli_query($db, "UPDATE users SET Password = '$password_secure' WHERE Id_user = '$id_user_login'");
            } else {
                echo "<script>
                    alert('Data Profil Berhasil Disimpan! Namun GANTI PASSWORD GAGAL karena password baru dan konfirmasi tidak cocok.');
                    window.location.href = 'index_Admin.php?page=pengaturan_admin';
                </script>";
                exit();
            }

        } else {
            echo "<script>
                alert('Data Profil Berhasil Disimpan! Namun GANTI PASSWORD GAGAL karena password lama yang Anda masukkan salah.');
                window.location.href = 'index_Admin.php?page=pengaturan_admin';
            </script>";
            exit();
        }
    }

    // Sukses Total jika berhasil melewati semua filter di atas
    echo "<script>
        alert('Luar Biasa! Seluruh pembaruan data profil dan akun Anda berhasil disimpan.');
        window.location.href = 'index_Admin.php?page=pengaturan_admin';
    </script>";
    exit();
}
?>
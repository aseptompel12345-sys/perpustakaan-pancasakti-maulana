<?php
include 'config/koneksi.php'; // Mengaktifkan session & koneksi database

// 1. Ambil data identitas dari session sebelum dihapus
$id_user = $_SESSION['id_user'];
$role    = $_SESSION['role'];

// 2. Cek Role untuk menentukan tabel mana yang akan diupdate statusnya
if ($role == 'admin') {
    // Jika Admin, update kolom Status di tabel 'admin'
    mysqli_query($db, "UPDATE admin SET Status = 'Tidak Aktif' WHERE Id_user = '$id_user'");
} else if ($role == 'anggota') {
    // Jika Anggota, update kolom Status di tabel 'anggota'
    mysqli_query($db, "UPDATE anggota SET Status = 'Tidak Aktif' WHERE Id_user = '$id_user'");
}

// 3. Proses pembersihan session (Logout standar)
$_SESSION = [];
session_unset();
session_destroy();

// 4. Hapus cookie browser agar benar-benar bersih
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 5. Kembali ke halaman login
echo "<script>
        alert('Anda telah keluar. Status akun sekarang: Tidak Aktif.');
        window.location.href='login_from.php';
      </script>";
exit();
?>
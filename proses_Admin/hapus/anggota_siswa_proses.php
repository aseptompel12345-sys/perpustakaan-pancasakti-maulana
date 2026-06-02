<?php
// 1. Ambil ID dari URL
$id_siswa = mysqli_real_escape_string($db, $_GET['id_siswa']);

// 2. Ambil data penting (Id_anggota dan Foto) sebelum data dihapus
$cari_data = mysqli_query($db, "SELECT Id_anggota, Foto FROM anggota_siswa WHERE Id_siswa = '$id_siswa'");
$data_siswa = mysqli_fetch_array($cari_data);

if ($data_siswa) {
    $id_anggota = $data_siswa['Id_anggota'];
    $nama_foto  = $data_siswa['Foto'];

    // --- LOGIKA CEK STATUS PINJAM ---
    $cek_pinjam = mysqli_query($db, "SELECT Id_peminjaman FROM peminjaman 
                                     WHERE Id_anggota = '$id_anggota' 
                                     AND Status IN ('Dipinjam', 'Sebagian')");

    if (mysqli_num_rows($cek_pinjam) > 0) {
        echo "<script>
                alert('Gagal Hapus! Anggota masih memiliki buku yang belum dikembalikan.'); 
                window.location='index_Admin.php?page=anggota_siswa';
              </script>";
        return; 
    }

    // 3. Ambil Id_user
    $cari_anggota = mysqli_query($db, "SELECT Id_user FROM anggota WHERE Id_anggota = '$id_anggota'");
    $data_anggota = mysqli_fetch_array($cari_anggota);
    $id_user = $data_anggota['Id_user'];

    // --- HAPUS RIWAYAT TRANSAKSI DULU (Agar tidak error Foreign Key) ---
    mysqli_query($db, "DELETE FROM kunjungan WHERE Id_anggota = '$id_anggota'");
    mysqli_query($db, "DELETE FROM pengembalian WHERE Id_anggota = '$id_anggota'");
    mysqli_query($db, "DELETE FROM peminjaman WHERE Id_anggota = '$id_anggota' AND Status = 'Selesai'");

    // 4. PROSES HAPUS UTAMA (Anak -> Induk)
    $hapus_siswa = mysqli_query($db, "DELETE FROM anggota_siswa WHERE Id_siswa = '$id_siswa'");
    $hapus_anggota = mysqli_query($db, "DELETE FROM anggota WHERE Id_anggota = '$id_anggota'");
    $hapus_user = mysqli_query($db, "DELETE FROM users WHERE Id_user = '$id_user'");

    // 5. JIKA SEMUA DATA DI DATABASE BERHASIL DIHAPUS, BARU HAPUS FOTO
    if ($hapus_user) {
        
        // --- POSISI BARU: HAPUS FOTO DI SINI ---
        if (!empty($nama_foto) && $nama_foto != 'default.jpg') {
            if (file_exists("FOTOS/foto_siswa/$nama_foto")) {
                unlink("FOTOS/foto_siswa/$nama_foto");
            }
        }
        // ---------------------------------------

        echo "<script>
                alert('Data Profil, Akun, dan File Foto Berhasil Dihapus!'); 
                window.location='index_Admin.php?page=anggota_siswa';
              </script>";
    } else {
        echo "<script>
                alert('Terjadi kesalahan saat menghapus data!'); 
                window.location='index_Admin.php?page=anggota_siswa';
              </script>";
    }
} else {
    echo "<script>
            alert('Data tidak ditemukan!'); 
            window.location='index_Admin.php?page=anggota_siswa';
          </script>";
}
?>
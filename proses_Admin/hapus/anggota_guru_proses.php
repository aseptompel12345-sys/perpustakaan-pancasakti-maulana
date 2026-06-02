<?php
// 1. Ambil ID dari URL
$id_guru = $_GET['id_guru'];

// 2. Ambil data penting sebelum dihapus
$cari_data = mysqli_query($db, "SELECT Id_anggota, Foto FROM anggota_guru WHERE Id_guru = '$id_guru'");
$data_guru = mysqli_fetch_array($cari_data);

if ($data_guru) {
    $id_anggota = $data_guru['Id_anggota'];
    $nama_foto  = $data_guru['Foto'];

    // --- (HAL 1) TAMBAHAN: CEK STATUS PINJAM ---
    $cek_pinjam = mysqli_query($db, "SELECT Id_peminjaman FROM peminjaman 
                                     WHERE Id_anggota = '$id_anggota' 
                                     AND Status IN ('Dipinjam', 'Sebagian')");

    if (mysqli_num_rows($cek_pinjam) > 0) {
        echo "<script>
                alert('Gagal Hapus! Guru masih memiliki buku yang belum dikembalikan.'); 
                window.location='index_Admin.php?page=anggota_guru';
              </script>";
        return; // Menghentikan proses tanpa mematikan index agar tidak stuck
    }
    // --- AKHIR TAMBAHAN CEK ---

    // 3. Ambil Id_user
    $cari_anggota = mysqli_query($db, "SELECT Id_user FROM anggota WHERE Id_anggota = '$id_anggota'");
    $data_anggota = mysqli_fetch_array($cari_anggota);
    $id_user = $data_anggota['Id_user'];

    // --- (HAL 2) TAMBAHAN: HAPUS RIWAYAT TRANSAKSI ---
    mysqli_query($db, "DELETE FROM kunjungan WHERE Id_anggota = '$id_anggota'");
    mysqli_query($db, "DELETE FROM pengembalian WHERE Id_anggota = '$id_anggota'");
    mysqli_query($db, "DELETE FROM peminjaman WHERE Id_anggota = '$id_anggota' AND Status = 'Selesai'");
    // --- AKHIR TAMBAHAN HAPUS ---

    // 5. PROSES HAPUS BERUNTUN (Sesuai kode asli)
    $hapus_guru = mysqli_query($db, "DELETE FROM anggota_guru WHERE Id_guru = '$id_guru'");
    $hapus_anggota = mysqli_query($db, "DELETE FROM anggota WHERE Id_anggota = '$id_anggota'");
    $hapus_user = mysqli_query($db, "DELETE FROM users WHERE Id_user = '$id_user'");

    if ($hapus_user) {
        
        // --- (HAL 3) PINDAH POSISI: HAPUS FOTO DI SINI ---
        if (!empty($nama_foto) && $nama_foto != 'default.jpg') {
            if (file_exists("FOTOS/foto_guru/$nama_foto")) {
                unlink("FOTOS/foto_guru/$nama_foto");
            }
        }

        echo "<script>
                alert('Data Profil, Akun, dan Riwayat Berhasil Dihapus!'); 
                window.location='index_Admin.php?page=anggota_guru';
              </script>";
    } else {
        echo "<script>
                alert('Terjadi kesalahan saat menghapus data!'); 
                window.location='index_Admin.php?page=anggota_guru';
              </script>";
    }
} else {
    echo "<script>
            alert('Data tidak ditemukan!'); 
            window.location='index_Admin.php?page=anggota_guru';
          </script>";
}
?>
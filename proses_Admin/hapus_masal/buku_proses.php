<?php
include "../../config/koneksi.php";

if (isset($_POST['confirm_delete_buku'])) {
    // 1. Ambil & Amankan Input
    $tahun_terbit   = mysqli_real_escape_string($db, $_POST['tahun_terbit']);
    $bidang_buku    = mysqli_real_escape_string($db, $_POST['bidang_buku']);
    $lokasi_rak     = mysqli_real_escape_string($db, $_POST['lokasi_rak']);
    $password_input = $_POST['password_konfirmasi'];
    
    $id_user_log = $_SESSION['Id_admin'];

    // 2. Verifikasi Password Admin
    $query_verifikasi = "SELECT users.Password FROM admin 
                         INNER JOIN users ON admin.Id_user = users.Id_user 
                         WHERE admin.Id_user = '$id_user_log'";
    $cek_admin = mysqli_query($db, $query_verifikasi);
    $data_admin = mysqli_fetch_array($cek_admin);

    if (!$data_admin || !password_verify($password_input, $data_admin['Password'])) {
        echo "<script>alert('Password Salah! Verifikasi admin gagal.'); window.location='../../index_Admin.php?page=buku';</script>";
        exit;
    }

    // 3. Susun Filter (Tanda kurung sangat penting di sini)
    $conditions = [];
    if (!empty($tahun_terbit)) { $conditions[] = "Tahun_terbit = '$tahun_terbit'"; }
    if (!empty($bidang_buku))  { $conditions[] = "Bidang_buku = '$bidang_buku'"; }
    if (!empty($lokasi_rak))   { $conditions[] = "Lokasi_rak = '$lokasi_rak'"; }

    if (empty($conditions)) {
        echo "<script>alert('Pilih minimal satu filter!'); window.location='../../index_Admin.php?page=buku';</script>";
        exit;
    }

    $where_filter = implode(" AND ", $conditions);

    // 4. TAHAP KARANTINA KETAT
    $id_siap_hapus = [];
    $list_foto = [];
    
    // Perbandingan dilakukan secara eksplisit sebagai angka (CAST)
    $sql_cari = "SELECT Id_buku, Foto, Stok_awal_buku, Stok_buku_tersedia FROM buku 
                 WHERE ($where_filter) 
                 AND CAST(Stok_awal_buku AS UNSIGNED) = CAST(Stok_buku_tersedia AS UNSIGNED)";
    
    $cari_buku = mysqli_query($db, $sql_cari);

    while ($row = mysqli_fetch_array($cari_buku)) {
        // Double Check di PHP: Hanya masukkan ID jika stok benar-benar utuh
        if ((int)$row['Stok_awal_buku'] === (int)$row['Stok_buku_tersedia']) {
            $id_siap_hapus[] = $row['Id_buku'];
            if (!empty($row['Foto'])) {
                $list_foto[] = $row['Foto'];
            }
        }
    }

    // 5. VALIDASI AKHIR HASIL KARANTINA
    if (empty($id_siap_hapus)) {
        echo "<script>
                alert('Gagal! Tidak ditemukan buku yang cocok atau semua buku dalam kriteria ini sedang dipinjam oleh siswa.'); 
                window.location='../../index_Admin.php?page=buku';
              </script>";
        exit;
    }

    // Ubah array ID menjadi string untuk query (Misal: 4, 12, 15)
    $string_ids = implode(",", $id_siap_hapus);

    // 6. EKSEKUSI PENGHAPUSAN BERTAHAP
    // Menghapus hanya transaksi milik ID yang sudah dikarantina aman
    mysqli_query($db, "DELETE FROM pengembalian WHERE Id_buku IN ($string_ids)");
    mysqli_query($db, "DELETE FROM peminjaman WHERE Id_buku IN ($string_ids)");

    // Hapus data utama buku
    $query_hapus = mysqli_query($db, "DELETE FROM buku WHERE Id_buku IN ($string_ids)");
    $jumlah_terhapus = mysqli_affected_rows($db);

    // 7. PEMBERSIHAN FILE FOTO
    if ($query_hapus && $jumlah_terhapus > 0) {
        foreach ($list_foto as $nama_foto) {
            $path = "../../FOTOS/foto_sampul_buku/" . $nama_foto;
            if (file_exists($path)) {
                unlink($path);
            }
        }

        echo "<script>
                alert('Berhasil! $jumlah_terhapus data buku yang sudah tidak dipinjam telah dihapus beserta riwayatnya.'); 
                window.location='../../index_Admin.php?page=buku';
              </script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat menghapus data.'); window.location='../../index_Admin.php?page=buku';</script>";
    }
} else {
    header("location:../../index_Admin.php?page=buku");
}
?>
<?php
// Pastikan koneksi disertakan, sesuaikan path jika file ini berada di folder proses
include "../../config/koneksi.php";

// 1. Ambil ID dari URL dan amankan
$id = mysqli_real_escape_string($db, $_GET['id']);

// 2. Ambil data buku untuk pengecekan stok dan foto
$query_cek = mysqli_query($db, "SELECT Judul, Foto, Stok_awal_buku, Stok_buku_tersedia FROM buku WHERE Id_buku = '$id'");
$data_buku = mysqli_fetch_array($query_cek);

if (!$data_buku) {
    echo "<script>alert('Data buku tidak ditemukan!'); window.location='index_Admin.php?page=buku';</script>";
    exit;
}

// 3. KEAMANAN KETAT: Cek apakah buku sedang dipinjam
// Paksa ke tipe integer untuk perbandingan yang akurat
if ((int)$data_buku['Stok_awal_buku'] !== (int)$data_buku['Stok_buku_tersedia']) {
    $sedang_dipinjam = (int)$data_buku['Stok_awal_buku'] - (int)$data_buku['Stok_buku_tersedia'];
    echo "<script>
            alert('Gagal Hapus! Buku [ " . $data_buku['Judul'] . " ] masih dipinjam sebanyak $sedang_dipinjam buku. Pastikan semua buku sudah kembali.'); 
            window.location='index_Admin.php?page=buku';
          </script>";
    exit;
}

// 4. BERSIHKAN TRANSAKSI (Hapus riwayat yang sudah selesai/sebagian)
// Agar tidak terjadi Foreign Key Error
mysqli_query($db, "DELETE FROM pengembalian WHERE Id_buku = '$id'");
mysqli_query($db, "DELETE FROM peminjaman WHERE Id_buku = '$id'");

// 5. HAPUS DATA DARI DATABASE
$query_hapus = mysqli_query($db, "DELETE FROM buku WHERE Id_buku = '$id'");

if ($query_hapus) {
    // 6. HAPUS FOTO FISIK (Hanya jika data di database berhasil dihapus)
    $nama_foto = $data_buku['Foto'];
    if (!empty($nama_foto)) {
        $path_foto = "FOTOS/foto_sampul_buku/$nama_foto";
        if (file_exists($path_foto)) {
            unlink($path_foto);
        }
    }

    echo "<script>
            alert('Data Buku dan Riwayat Transaksinya Berhasil Dihapus!'); 
            window.location='index_Admin.php?page=buku';
          </script>";
} else {
    echo "<script>
            alert('Gagal Menghapus Data di Database!'); 
            window.location='index_Admin.php?page=buku';
          </script>";
}
?>
<?php
// 1. Ambil ID dari URL
$id = $_GET['id'];

// 2. Query Hapus
// Pastikan nama kolom 'Id_kunjungan' sesuai dengan database kamu (perhatikan huruf besar/kecilnya)
$query_hapus = mysqli_query($db, "DELETE FROM kunjungan WHERE Id_kunjungan = '$id'");

if ($query_hapus) {
    // Jika berhasil, arahkan kembali ke tabel transaksi dengan notifikasi sukses
    echo "<script>
        alert('Data Kunjungan Berhasil Dihapus!');
        window.location.href='?page=transaksi_kunjungan';
    </script>";
} else {
    // Jika gagal
    echo "<script>
        alert('Gagal menghapus data: " . mysqli_error($db) . "');
        window.location.href='?page=transaksi_kunjungan';
    </script>";
}
?>
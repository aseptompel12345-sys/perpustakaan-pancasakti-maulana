<?php
// Pastikan file ini menerima $db dari index_Admin.php
if (isset($_GET['id'])) {
    $id_hapus = mysqli_real_escape_string($db, $_GET['id']);

    // 1. Ambil data dengan nama kolom yang benar: 'Jumlah'
    $sql_cek = "SELECT * FROM peminjaman WHERE Id_peminjaman = '$id_hapus'";
    $query_cek = mysqli_query($db, $sql_cek);
    
    if (mysqli_num_rows($query_cek) > 0) {
        $data = mysqli_fetch_assoc($query_cek);
        
        $status_sekarang = $data['Status'];
        $id_buku         = $data['Id_buku'];
        $jumlah_pinjam   = $data['Jumlah']; // Sesuaikan ke 'Jumlah'
        $sisa            = $data['Sisa_pinjam'];

        if ($status_sekarang == 'Selesai' && $sisa == 0) {
            // Hapus Riwayat
            mysqli_query($db, "DELETE FROM pengembalian WHERE Id_peminjaman = '$id_hapus'");
            $hapus = mysqli_query($db, "DELETE FROM peminjaman WHERE Id_peminjaman = '$id_hapus'");
            
            if($hapus) {
                echo "<script>alert('Pembersihan data selesai berhasil!'); window.location.href='index_Admin.php?page=transaksi_pinjam';</script>";
            }
        } 
        else if ($status_sekarang == 'Dipinjam') {
            // Salah Input - Gunakan 'Stok_buku_tersedia'
            $update_stok = mysqli_query($db, "UPDATE buku SET Stok_buku_tersedia = Stok_buku_tersedia + $jumlah_pinjam WHERE Id_buku = '$id_buku'");
            
            if ($update_stok) {
                $hapus = mysqli_query($db, "DELETE FROM peminjaman WHERE Id_peminjaman = '$id_hapus'");
                echo "<script>alert('Data salah input dihapus & stok telah dikembalikan ke rak!'); window.location.href='index_Admin.php?page=transaksi_pinjam';</script>";
            } else {
                // Jika stok gagal update, tampilkan error spesifik agar tahu kolom mana yang salah lagi
                echo "<script>alert('Gagal update stok: " . mysqli_error($db) . "'); window.history.back();</script>";
            }
        } 
        else {
            echo "<script>alert('Status Sebagian tidak bisa dihapus!'); window.location.href='index_Admin.php?page=transaksi_pinjam';</script>";
        }
    } else {
        echo "<script>alert('Data tidak ditemukan!'); window.location.href='index_Admin.php?page=transaksi_pinjam';</script>";
    }
}
?>
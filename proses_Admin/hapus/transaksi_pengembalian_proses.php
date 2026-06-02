<?php
include "../../config/koneksi.php";

if (isset($_GET['id']) && isset($_GET['alasan'])) {
    $id_pengembalian = mysqli_real_escape_string($db, $_GET['id']);
    $alasan = $_GET['alasan']; 

    // 1. Ambil data detail sebelum dihapus (PENTING: Ambil Id_buku dan Jml_kembali juga)
    $query_cari = mysqli_query($db, "SELECT Id_peminjaman, Id_buku, Jml_kembali FROM pengembalian WHERE Id_pengembalian = '$id_pengembalian'");
    $data_kembali = mysqli_fetch_assoc($query_cari);

    if ($data_kembali) {
        $id_peminjaman = $data_kembali['Id_peminjaman'];
        $id_buku       = $data_kembali['Id_buku'];
        $jml_kembali   = $data_kembali['Jml_kembali'];

        // 2. Hapus data pengembalian
        if (mysqli_query($db, "DELETE FROM pengembalian WHERE Id_pengembalian = '$id_pengembalian'")) {
            
            if ($alasan == 'koreksi') {
                // A. KEMBALIKAN STOK BUKU KE RAK (Karena dianggap belum kembali)
                mysqli_query($db, "UPDATE buku SET Stok_buku_tersedia = Stok_buku_tersedia - $jml_kembali WHERE Id_buku = '$id_buku'");

                // B. KEMBALIKAN NILAI Sisa_pinjam DI TABEL PEMINJAMAN
                mysqli_query($db, "UPDATE peminjaman SET Sisa_pinjam = Sisa_pinjam + $jml_kembali WHERE Id_peminjaman = '$id_peminjaman'");

                // C. UPDATE STATUS PEMINJAMAN
                $cek_sisa = mysqli_query($db, "SELECT SUM(Jml_kembali) as total FROM pengembalian WHERE Id_peminjaman = '$id_peminjaman'");
                $res_sisa = mysqli_fetch_assoc($cek_sisa);
                $total_kembali = ($res_sisa['total']) ? $res_sisa['total'] : 0;

                $q_pinjam = mysqli_query($db, "SELECT Jumlah FROM peminjaman WHERE Id_peminjaman = '$id_peminjaman'");
                $d_pinjam = mysqli_fetch_assoc($q_pinjam);
                $jml_awal = $d_pinjam['Jumlah'];

                if ($total_kembali == 0) {
                    $status_baru = "Dipinjam";
                } elseif ($total_kembali < $jml_awal) {
                    $status_baru = "Sebagian";
                } else {
                    $status_baru = "Selesai";
                }

                mysqli_query($db, "UPDATE peminjaman SET Status = '$status_baru' WHERE Id_peminjaman = '$id_peminjaman'");
                $pesan = "Koreksi Berhasil! Stok buku & Sisa pinjam telah dikembalikan ke posisi semula.";
            } else {
                $pesan = "Data dihapus (Arsip). Riwayat stok & peminjaman tidak berubah.";
            }

            echo "<script>alert('$pesan'); window.location='index_Admin.php?page=transaksi_pengembalian';</script>";
        }
    }
}
?>
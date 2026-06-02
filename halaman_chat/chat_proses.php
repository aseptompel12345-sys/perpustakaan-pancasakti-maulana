<?php
include "../config/koneksi.php";

if (isset($_POST['id']) && isset($_POST['aksi'])) {
    $id    = mysqli_real_escape_string($db, $_POST['id']);
    $aksi  = $_POST['aksi']; 
    $tabel = $_POST['tabel']; 
    
    $nama_admin = $_SESSION['nama_user']; 

    if ($aksi == 'izinkan') {
        if ($tabel == 'Kunjungan') {
            $query = "UPDATE kunjungan SET Admin_pemberi_izin = '$nama_admin' WHERE Id_kunjungan = '$id'";
        } 
        elseif ($tabel == 'Peminjaman') {
            $query = "UPDATE peminjaman SET Admin_pemberi_izin = '$nama_admin' WHERE Id_peminjaman = '$id'";
        } 
        elseif ($tabel == 'Pengembalian') {
            // ================================================================
            // LOGIKA KHUSUS PENGEMBALIAN: UPDATE STOK & SISA PINJAM
            // ================================================================
            
            // 1. Ambil detail data pengembalian yang akan di-izinkan
            $q_detail = mysqli_query($db, "SELECT * FROM pengembalian WHERE Id_pengembalian = '$id'");
            $dt = mysqli_fetch_assoc($q_detail);
            
            if ($dt) {
                $jml_kembali   = $dt['Jml_kembali'];
                $id_buku       = $dt['Id_buku'];
                $id_peminjaman = $dt['Id_peminjaman'];

                // 2. Update status izin di tabel pengembalian
                mysqli_query($db, "UPDATE pengembalian SET Admin_pemberi_izin = '$nama_admin' WHERE Id_pengembalian = '$id'");

                // 3. Kembalikan stok ke tabel buku
                mysqli_query($db, "UPDATE buku SET Stok_buku_tersedia = Stok_buku_tersedia + $jml_kembali WHERE Id_buku = '$id_buku'");

                // 4. Kurangi sisa pinjam di tabel peminjaman
                mysqli_query($db, "UPDATE peminjaman SET Sisa_pinjam = Sisa_pinjam - $jml_kembali WHERE Id_peminjaman = '$id_peminjaman'");

                // 5. Cek sisa pinjam untuk update status (Selesai/Sebagian)
                $q_cek_sisa = mysqli_query($db, "SELECT Sisa_pinjam FROM peminjaman WHERE Id_peminjaman = '$id_peminjaman'");
                $r_sisa = mysqli_fetch_assoc($q_cek_sisa);
                
                $status_baru = ($r_sisa['Sisa_pinjam'] <= 0) ? "Selesai" : "Sebagian";
                mysqli_query($db, "UPDATE peminjaman SET Status = '$status_baru' WHERE Id_peminjaman = '$id_peminjaman'");
                
                // Set query sukses agar if di bawah tetap jalan
                $query = "SELECT 1"; 
            }
        }
    } else {
        // Logika Tolak/Hapus (Tetap seperti kode lama Anda)
        if ($tabel == 'Kunjungan') {
            $query = "DELETE FROM kunjungan WHERE Id_kunjungan = '$id'";
        } elseif ($tabel == 'Peminjaman') {
            $query = "DELETE FROM peminjaman WHERE Id_peminjaman = '$id'";
        } elseif ($tabel == 'Pengembalian') {
            $query = "DELETE FROM pengembalian WHERE Id_pengembalian = '$id'";
        }
    }

    if (isset($query) && mysqli_query($db, $query)) {
        $tabel_asal = strtolower($tabel);
        mysqli_query($db, "DELETE FROM notif_penerima WHERE id_transaksi = '$id' AND tabel_asal = '$tabel_asal'");
        echo "success";
    } else {
        echo "error: " . mysqli_error($db);
    }
} else {
    echo "Data tidak lengkap";
}
?>
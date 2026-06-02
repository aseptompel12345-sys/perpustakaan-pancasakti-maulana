<?php
include "../../config/koneksi.php";

if (isset($_POST['simpan'])) {
    // 1. Ambil Data dari Form (Sesuai name di form)
    $id_peminjaman   = mysqli_real_escape_string($db, $_POST['Id_peminjaman']);
    $foto_peminjam   = mysqli_real_escape_string($db, $_POST['Foto_peminjam']);
    $id_anggota      = mysqli_real_escape_string($db, $_POST['Id_anggota']);
    $nama_peminjam   = mysqli_real_escape_string($db, $_POST['Nama_peminjam']);
    $detail_identitas= mysqli_real_escape_string($db, $_POST['Detail_identitas']);
    $jenis_anggota   = mysqli_real_escape_string($db, $_POST['Jenis_anggota']);
    $foto_buku       = mysqli_real_escape_string($db, $_POST['Foto_buku']);
    $id_buku         = mysqli_real_escape_string($db, $_POST['Id_buku']);
    $judul_buku      = mysqli_real_escape_string($db, $_POST['Judul_buku']);
    $lokasi_rak      = mysqli_real_escape_string($db, $_POST['Lokasi_rak']);
    $tgl_kembali     = mysqli_real_escape_string($db, $_POST['Tgl_kembali']);
    $jml_kembali     = mysqli_real_escape_string($db, $_POST['Jml_kembali']);
    $denda           = mysqli_real_escape_string($db, $_POST['Denda']);
    $status_kembali  = mysqli_real_escape_string($db, $_POST['Status']);

    // ================================================================
    // LOGIKA PERIZINAN: AMBIL NAMA ADMIN DARI SESSION
    // ================================================================
    $admin_pengizin = $_SESSION['nama_user']; // Sesuaikan dengan key session login Anda

    // 2. Ambil Jumlah Pinjam Awal dari tabel peminjaman
    $q_pinjam = mysqli_query($db, "SELECT Jumlah FROM peminjaman WHERE Id_peminjaman = '$id_peminjaman'");
    $d_pinjam = mysqli_fetch_assoc($q_pinjam);
    $jml_awal = $d_pinjam['Jumlah'];

    // 3. Hitung Total yang sudah kembali (termasuk inputan sekarang)
    $q_total = mysqli_query($db, "SELECT SUM(Jml_kembali) as total FROM pengembalian WHERE Id_peminjaman = '$id_peminjaman'");
    $d_total = mysqli_fetch_assoc($q_total);
    $total_sebelumnya = ($d_total['total']) ? $d_total['total'] : 0;
    
    $total_masuk_akhir = $total_sebelumnya + $jml_kembali;

    // 4. Simpan ke Tabel Pengembalian (Menambahkan kolom Admin_pemberi_izin)
    $sql_ins = "INSERT INTO pengembalian (
                    Id_peminjaman, Foto_peminjam, Id_anggota, Nama_peminjam, 
                    Detail_identitas, Jenis_anggota, Foto_buku, Id_buku, 
                    Judul_buku, Lokasi_rak, Tgl_kembali, Jml_kembali, Denda, Status, Admin_pemberi_izin
                ) VALUES (
                    '$id_peminjaman', '$foto_peminjam', '$id_anggota', '$nama_peminjam', 
                    '$detail_identitas', '$jenis_anggota', '$foto_buku', '$id_buku', 
                    '$judul_buku', '$lokasi_rak', '$tgl_kembali', '$jml_kembali', '$denda', '$status_kembali', '$admin_pengizin'
                )";

    if (mysqli_query($db, $sql_ins)) {
        // A. Kembalikan stok ke tabel buku
        mysqli_query($db, "UPDATE buku SET Stok_buku_tersedia = Stok_buku_tersedia + $jml_kembali WHERE Id_buku = '$id_buku'");

        // B. Update Sisa_pinjam di tabel peminjaman
        mysqli_query($db, "UPDATE peminjaman SET Sisa_pinjam = Sisa_pinjam - $jml_kembali WHERE Id_peminjaman = '$id_peminjaman'");

        // C. Update Status di tabel Peminjaman berdasarkan sisa pinjam terbaru
        $q_cek_sisa = mysqli_query($db, "SELECT Sisa_pinjam FROM peminjaman WHERE Id_peminjaman = '$id_peminjaman'");
        $d_sisa = mysqli_fetch_assoc($q_cek_sisa);

        $status_pinjam_baru = ($d_sisa['Sisa_pinjam'] <= 0) ? "Selesai" : "Sebagian";
        mysqli_query($db, "UPDATE peminjaman SET Status = '$status_pinjam_baru' WHERE Id_peminjaman = '$id_peminjaman'");

        echo "<script>alert('Data Pengembalian Berhasil Disimpan oleh Admin $admin_pengizin!'); window.location='../../index_Admin.php?page=transaksi_pengembalian';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($db) . "'); window.history.back();</script>";
    }
}
?>
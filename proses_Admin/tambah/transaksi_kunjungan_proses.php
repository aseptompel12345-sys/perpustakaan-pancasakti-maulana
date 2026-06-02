<?php
include "../../config/koneksi.php";

if (isset($_POST['simpan'])) {
    // 1. Ambil data dari form
    $Tgl_kunjungan   = $_POST['Tgl_kunjungan'];
    $Jam_kunjungan   = $_POST['Jam_kunjungan'];
    $Id_anggota      = $_POST['Id_anggota'];
    $Jenis_anggota   = $_POST['Jenis_anggota'];
    $Nama_pengunjung = $_POST['Nama_pengunjung'];
    $Detail_identitas= $_POST['Detail_identitas'];
    $Keperluan       = $_POST['keperluan'];
    $Foto_kunjungan  = $_POST['Foto_kunjungan'];

    // 2. AMBIL NAMA ADMIN DARI SESSION
    // Sesuaikan 'nama_lengkap' dengan nama session login admin kamu
    $Admin_pemberi   = $_SESSION['nama_user']; 

    // 3. Masukkan ke tabel (Tambahkan kolom Admin_pemberi_izin di akhir)
    $query = "INSERT INTO kunjungan (
                Tgl_kunjungan, 
                Jam_kunjungan, 
                Id_anggota, 
                Jenis_anggota, 
                Nama_pengunjung, 
                Detail_identitas, 
                Keperluan, 
                Foto_kunjungan, 
                Admin_pemberi_izin
              ) VALUES (
                '$Tgl_kunjungan', 
                '$Jam_kunjungan', 
                '$Id_anggota', 
                '$Jenis_anggota', 
                '$Nama_pengunjung', 
                '$Detail_identitas', 
                '$Keperluan', 
                '$Foto_kunjungan', 
                '$Admin_pemberi'
              )";

    $simpan = mysqli_query($db, $query);

    if ($simpan) {
        echo "<script>alert('Data Kunjungan Berhasil Disimpan!'); window.location='../../index_Admin.php?page=transaksi_kunjungan';</script>";
    } else {
        echo "<script>alert('Gagal Menyimpan Data: " . mysqli_error($db) . "'); window.history.back();</script>";
    }
}
?>
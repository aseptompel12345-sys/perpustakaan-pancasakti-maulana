<?php
include "../../config/koneksi.php";

if (isset($_POST['simpan_kunjungan'])) {
    $Id_anggota = mysqli_real_escape_string($db, $_POST['Id_anggota']);

    // ================================================================
    // LOGIKA ANTI-SPAM: Cek apakah ada transaksi yang masih menggantung
    // ================================================================
    $cek_antrean = mysqli_query($db, "SELECT Id_kunjungan FROM kunjungan 
                                      WHERE Id_anggota = '$Id_anggota' 
                                      AND Admin_pemberi_izin = 'Menunggu Izin'");

    if (mysqli_num_rows($cek_antrean) > 0) {
        // Jika ditemukan data gantung, hentikan proses dan beri peringatan
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<body></body>";
        echo "<script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Akses Dibatasi',
                    text: 'Anda sudah mengirim permintaan kunjungan. Harap tunggu verifikasi Admin sebelum membuat transaksi baru.',
                    confirmButtonColor: '#f39c12'
                }).then(() => {
                    window.location='../../index_anggota.php?anggota=transaksi_kunjungan';
                });
              </script>";
        exit(); 
    }

    // ================================================================
    // PROSES LANJUTAN (Jika tidak ada antrean menggantung)
    // ================================================================
    $Tgl_kunjungan   = $_POST['Tgl_kunjungan'];
    $Jam_kunjungan   = $_POST['Jam_kunjungan'];
    $Nama_pengunjung = $_POST['Nama_pengunjung'];
    $Jenis_anggota   = $_POST['Jenis_anggota'];
    $Detail_identitas= $_POST['Detail_identitas'];
    $Keperluan       = $_POST['keperluan'];
    $Foto_kunjungan  = $_POST['Foto_kunjungan'];
    $Admin_pemberi_izin = "Menunggu Izin"; 

    $query = "INSERT INTO kunjungan (
                Tgl_kunjungan, Jam_kunjungan, Id_anggota, Jenis_anggota, 
                Nama_pengunjung, Detail_identitas, Keperluan, Foto_kunjungan, Admin_pemberi_izin
              ) 
              VALUES (
                '$Tgl_kunjungan', '$Jam_kunjungan', '$Id_anggota', '$Jenis_anggota', 
                '$Nama_pengunjung', '$Detail_identitas', '$Keperluan', '$Foto_kunjungan', '$Admin_pemberi_izin'
              )";

    if (mysqli_query($db, $query)) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<body></body>";
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil Terkirim',
                    text: 'Data Anda sudah masuk antrean. Silakan temui Admin untuk mendapatkan izin.',
                    confirmButtonColor: '#28a745'
                }).then(() => {
                    window.location='../../index_anggota.php?anggota=transaksi_kunjungan';
                });
              </script>";
    } else {
        echo "<script>
                alert('Gagal: " . mysqli_error($db) . "'); 
                window.history.back();
              </script>";
    }
}
?>
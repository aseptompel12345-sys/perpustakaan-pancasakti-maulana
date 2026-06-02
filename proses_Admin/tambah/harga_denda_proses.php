<?php
include "../../config/koneksi.php";

if (isset($_POST['simpan_denda'])) {
    // 1. Tangkap data nilai denda dari form dashboard
    $nilai_denda = (int)$_POST['Nilai'];
    $tgl_diatur  = date('Y-m-d'); // Mengambil tanggal hari ini secara otomatis

    // Keamanan tambahan: Pastikan nilai denda tidak minus
    if ($nilai_denda < 0) {
        echo "<script>
                alert('Gagal! Nilai denda tidak boleh kurang dari 0.');
                window.history.back();
              </script>";
        exit();
    }

    // 2. Jalankan Query INSERT ke tabel denda sesuai struktur kolom Anda
    // Id_denda tidak perlu dimasukkan karena biasanya berstatus Auto Increment
    $query = "INSERT INTO denda (Nilai, Tgl_diatur) VALUES ('$nilai_denda', '$tgl_diatur')";
    
    $eksekusi = mysqli_query($db, $query);

    if ($eksekusi) {
        // Tampilan alert sukses jika berhasil disimpan
        echo "<script>
                alert('Berhasil! Tarif denda perpustakaan diperbarui menjadi Rp " . number_format($nilai_denda, 0, ',', '.') . " per hari.');
                window.location.href='../../index_Admin.php'; 
              </script>";
    } else {
        // Tampilan alert jika database error
        echo "<script>
                alert('Gagal memperbarui denda: " . mysqli_error($db) . "');
                window.history.back();
              </script>";
    }
} else {
    // Jika mencoba akses langsung tanpa tekan tombol simpan
    echo "<script>window.location.href='../../index_Admin.php';</script>";
}
?>
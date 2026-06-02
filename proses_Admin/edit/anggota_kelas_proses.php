<?php
include '../../config/koneksi.php';

if (isset($_POST['update'])) {
    // Ambil ID Kelas (Pastikan nama 'Id_kelas' sesuai dengan name di input hidden form edit)
    $id_kelas = mysqli_real_escape_string($db, $_POST['Id_kelas']);
    
    // Ambil data lainnya dari form
    $nama_kelas       = mysqli_real_escape_string($db, $_POST['Nama_kelas']);
    $wali_kelas       = mysqli_real_escape_string($db, $_POST['Wali_kelas']);
    $jumlah_siswa     = mysqli_real_escape_string($db, $_POST['Jumlah_siswa']); 
    $penanggung_jawab = mysqli_real_escape_string($db, $_POST['Penanggung_jawab']);

    // KARENA TIDAK ADA FOTO, LANGSUNG JALANKAN QUERY UPDATE
    $query = "UPDATE anggota_kelas SET  
                Nama_kelas       = '$nama_kelas', 
                Wali_kelas       = '$wali_kelas',
                Jumlah_siswa     = '$jumlah_siswa',
                Penanggung_jawab = '$penanggung_jawab'
              WHERE Id_kelas     = '$id_kelas'";

    // Eksekusi Query
    if (mysqli_query($db, $query)) {
        echo "<script>alert('Data Kelas Berhasil Diperbarui!'); window.location='../../index_Admin.php?page=anggota_kelas';</script>";
    } else {
        // Jika gagal, tampilkan errornya
        echo "<script>alert('Gagal Memperbarui: " . mysqli_error($db) . "'); window.history.back();</script>";
    }
} else {
    // Jika mencoba akses file ini tanpa melalui form
    header("location:../../index_Admin.php?page=anggota_kelas");
}
?>
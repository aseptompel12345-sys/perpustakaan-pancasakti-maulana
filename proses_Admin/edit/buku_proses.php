<?php
include '../../config/koneksi.php';

if (isset($_POST['update'])) {
    $id            = $_POST['Id_buku'];
    
    // Gunakan real_escape_string untuk Judul, Pengarang, dan Penerbit (rawan tanda petik)
    $isbn          = mysqli_real_escape_string($db, $_POST['ISBN']);
    $judul         = mysqli_real_escape_string($db, $_POST['Judul']);
    $pengarang     = mysqli_real_escape_string($db, $_POST['Pengarang']);
    $penerbit      = mysqli_real_escape_string($db, $_POST['Penerbit']);
    $tahun_terbit  = $_POST['Tahun_terbit'];
    $bidang_buku   = $_POST['Bidang_buku'];
    $lokasi_rak    = $_POST['Rak_buku'];
    $stok_awal     = $_POST['Stok_awal_buku'];
    
    // Stok tersedia ikut berubah jika stok awal diubah
    $stok_tersedia = $stok_awal; 

    // --- CEK APAKAH ADA UPLOAD FOTO SAMPUL BARU ---
    if ($_FILES['Foto']['name'] != "") {
        $nama_file = $_FILES['Foto']['name'];
        $tmp_file  = $_FILES['Foto']['tmp_name'];
        $ekstensi  = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
        $foto_baru = date('dmYHis') . '_' . uniqid() . '.' . $ekstensi;
        $path      = "../../FOTOS/foto_sampul_buku/" . $foto_baru;

        if (move_uploaded_file($tmp_file, $path)) {
            // 1. Ambil data foto lama untuk dihapus
            $tampil = mysqli_query($db, "SELECT Foto FROM buku WHERE Id_buku='$id'");
            $lama = mysqli_fetch_array($tampil);
            
            // Hapus file fisik jika ada
            if ($lama['Foto'] != "" && file_exists("../../FOTOS/foto_sampul_buku/".$lama['Foto'])) {
                unlink("../../FOTOS/foto_sampul_buku/".$lama['Foto']);
            }

            // 2. Query Update TERMASUK foto baru
            $query = "UPDATE buku SET 
                        Foto='$foto_baru', 
                        ISBN='$isbn', 
                        Judul='$judul', 
                        Pengarang='$pengarang', 
                        Penerbit='$penerbit', 
                        Tahun_terbit='$tahun_terbit', 
                        Bidang_buku='$bidang_buku', 
                        Lokasi_rak='$lokasi_rak', 
                        Stok_awal_buku='$stok_awal', 
                        Stok_buku_tersedia='$stok_tersedia' 
                      WHERE Id_buku='$id'";
        }
    } else {
        // --- 3. QUERY UPDATE TANPA MENGGANTI FOTO ---
        // Kolom 'Foto' tidak disentuh agar gambar lama tidak hilang
        $query = "UPDATE buku SET 
                    ISBN='$isbn', 
                    Judul='$judul', 
                    Pengarang='$pengarang', 
                    Penerbit='$penerbit', 
                    Tahun_terbit='$tahun_terbit', 
                    Bidang_buku='$bidang_buku', 
                    Lokasi_rak='$lokasi_rak', 
                    Stok_awal_buku='$stok_awal', 
                    Stok_buku_tersedia='$stok_tersedia' 
                  WHERE Id_buku='$id'";
    }

    // Eksekusi Query
    if (mysqli_query($db, $query)) {
        echo "<script>alert('Data Buku Berhasil Diperbarui!'); window.location='../../index_Admin.php?page=buku';</script>";
    } else {
        // Tampilkan pesan error jika gagal (sangat membantu saat debugging)
        echo "<script>alert('Gagal Memperbarui: " . mysqli_error($db) . "'); window.history.back();</script>";
    }
}
?>
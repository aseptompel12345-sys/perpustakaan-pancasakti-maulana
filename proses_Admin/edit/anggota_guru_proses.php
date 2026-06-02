<?php
include '../../config/koneksi.php';

if (isset($_POST['update'])) {
    // Ambil ID
    $id_guru          = $_POST['Id_guru'];
    
    // Gunakan mysqli_real_escape_string agar aman dari karakter aneh/simbol
    $nip              = mysqli_real_escape_string($db, $_POST['NIP']);
    $nama_guru        = mysqli_real_escape_string($db, $_POST['Nama_guru']);
    $jenis_kelamin     = $_POST['Jenis_kelamin'];
    $agama             = $_POST['Agama'];
    $alamat            = mysqli_real_escape_string($db, $_POST['Alamat']); 
    $no_tlp            = mysqli_real_escape_string($db, $_POST['No_tlp']);

    // Cek apakah ada upload foto baru
    if ($_FILES['Foto']['name'] != "") {
        $nama_file = $_FILES['Foto']['name'];
        $tmp_file  = $_FILES['Foto']['tmp_name'];
        $ekstensi  = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
        $foto_baru = date('dmYHis') . '_' . uniqid() . '.' . $ekstensi;
        $path      = "../../FOTOS/foto_guru/" . $foto_baru;

        if (move_uploaded_file($tmp_file, $path)) {
            // 1. Cari nama foto lama untuk dihapus
            $tampil = mysqli_query($db, "SELECT Foto FROM anggota_guru WHERE Id_guru='$id_guru'");
            $lama = mysqli_fetch_array($tampil);
            
            // Hapus file fisik jika bukan foto default
            if ($lama['Foto'] != "" && file_exists("../../FOTOS/foto_guru/".$lama['Foto'])) {
                unlink("../../FOTOS/foto_guru/".$lama['Foto']);
            }

            // 2. Query dengan ganti foto
            $query = "UPDATE anggota_guru SET 
                        Foto='$foto_baru', 
                        NIP='$nip', 
                        Nama_guru='$nama_guru', 
                        Jenis_kelamin='$jenis_kelamin', 
                        Agama='$agama', 
                        Alamat='$alamat', 
                        No_tlp='$no_tlp'
                      WHERE Id_guru='$id_guru'";
        }
    } else {
        // 3. Query TANPA ganti foto (Kolom Foto tidak ikut di-update)
        $query = "UPDATE anggota_guru SET 
                    NIP='$nip', 
                    Nama_guru='$nama_guru', 
                    Jenis_kelamin='$jenis_kelamin', 
                    Agama='$agama', 
                    Alamat='$alamat', 
                    No_tlp='$no_tlp'
                  WHERE Id_guru='$id_guru'";
    }

    if (mysqli_query($db, $query)) {
        echo "<script>alert('Data Berhasil Diperbarui!'); window.location='../../index_Admin.php?page=anggota_guru';</script>";
    } else {
        echo "<script>alert('Gagal Memperbarui: " . mysqli_error($db) . "'); window.history.back();</script>";
    }
}
?>
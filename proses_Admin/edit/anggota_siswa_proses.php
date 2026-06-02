<?php
include '../../config/koneksi.php';

if (isset($_POST['update'])) {
    $id_anggota        = $_POST['Id_anggota'];
    $id_siswa          = $_POST['Id_siswa'];
    
    // Gunakan real_escape_string untuk mencegah error jika ada tanda petik (') pada nama/alamat
    $nisn              = mysqli_real_escape_string($db, $_POST['NISN']);
    $nama_siswa        = mysqli_real_escape_string($db, $_POST['Nama_siswa']);
    $jenis_kelamin     = $_POST['Jenis_kelamin'];
    $agama             = $_POST['Agama'];
    $kelas             = $_POST['Kelas'];
    $jurusan           = $_POST['Jurusan'];
    $alamat            = mysqli_real_escape_string($db, $_POST['Alamat']); 
    $no_tlp            = mysqli_real_escape_string($db, $_POST['No_tlp']);

    // --- CEK APAKAH ADA UPLOAD FOTO BARU ---
    if ($_FILES['Foto']['name'] != "") {
        $nama_file = $_FILES['Foto']['name'];
        $tmp_file  = $_FILES['Foto']['tmp_name'];
        $ekstensi  = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
        $foto_baru = date('dmYHis') . '_' . uniqid() . '.' . $ekstensi;
        $path      = "../../FOTOS/foto_siswa/" . $foto_baru;

        if (move_uploaded_file($tmp_file, $path)) {
            // 1. Ambil data foto lama untuk dihapus dari folder
            $tampil = mysqli_query($db, "SELECT Foto FROM anggota_siswa WHERE Id_siswa='$id_siswa'");
            $lama = mysqli_fetch_array($tampil);
            
            // Hapus file fisik jika ada (dan bukan file default jika kamu pakai default)
            if ($lama['Foto'] != "" && file_exists("../../FOTOS/foto_siswa/".$lama['Foto'])) {
                unlink("../../FOTOS/foto_siswa/".$lama['Foto']);
            }

            // 2. Query Update TERMASUK foto baru
            $query = "UPDATE anggota_siswa SET 
                        Foto='$foto_baru', 
                        NISN='$nisn', 
                        Nama_siswa='$nama_siswa', 
                        Jenis_kelamin='$jenis_kelamin', 
                        Agama='$agama', 
                        Kelas='$kelas', 
                        Jurusan='$jurusan', 
                        Alamat='$alamat', 
                        No_tlp='$no_tlp'
                      WHERE Id_siswa='$id_siswa'";
        }
    } else {
        // --- 3. QUERY UPDATE TANPA MENGGANTI FOTO ---
        // Perhatikan: Kolom 'Foto' tidak disebutkan agar data di DB tidak berubah
        $query = "UPDATE anggota_siswa SET 
                    NISN='$nisn', 
                    Nama_siswa='$nama_siswa', 
                    Jenis_kelamin='$jenis_kelamin', 
                    Agama='$agama', 
                    Kelas='$kelas', 
                    Jurusan='$jurusan', 
                    Alamat='$alamat', 
                    No_tlp='$no_tlp'
                  WHERE Id_siswa='$id_siswa'";
    }

    // Eksekusi Query
    if (mysqli_query($db, $query)) {
        echo "<script>alert('Data Siswa Berhasil Diperbarui!'); window.location='../../index_Admin.php?page=anggota_siswa';</script>";
    } else {
        // Tampilkan pesan error spesifik jika gagal
        echo "<script>alert('Gagal Memperbarui: " . mysqli_error($db) . "'); window.history.back();</script>";
    }
}
?>
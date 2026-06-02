<?php
// Pastikan jumlah ../ sesuai dengan kedalaman folder file ini
include "../../config/koneksi.php"; 

if (isset($_POST['id']) && isset($_POST['tipe'])) {
    $id = mysqli_real_escape_string($db, $_POST['id']);
    $tipe = $_POST['tipe'];
    $response = array();

    if ($tipe == "Siswa") {
        $query = mysqli_query($db, "SELECT Nama_siswa, Foto, Kelas, Jurusan, No_tlp FROM anggota_siswa WHERE Id_anggota = '$id'");
        $data = mysqli_fetch_assoc($query);
        
        if ($data) {
            $response['nama'] = $data['Nama_siswa'];
            $response['foto'] = "FOTOS/foto_siswa/" . $data['Foto'];
            $response['info_kamuflase'] = "Kelas: " . $data['Kelas'] . "\nJurusan: " . $data['Jurusan'] . "\nNo. Tlp: " . $data['No_tlp'];
        }
    } 
    elseif ($tipe == "Guru") {
        $query = mysqli_query($db, "SELECT Nama_guru, Foto, No_tlp FROM anggota_guru WHERE Id_anggota = '$id'");
        $data = mysqli_fetch_assoc($query);
        
        if ($data) {
            $response['nama'] = $data['Nama_guru'];
            $response['foto'] = "FOTOS/foto_guru/" . $data['Foto'];
            $response['info_kamuflase'] = "Jabatan: Guru / Staf Pengajar\nNo. Tlp: " . $data['No_tlp'];
        }
    } 
    else { 
        $query = mysqli_query($db, "SELECT Nama_kelas, Wali_kelas, Penanggung_jawab FROM anggota_kelas WHERE Id_anggota = '$id'");
        $data = mysqli_fetch_assoc($query);
        
        if ($data) {
            $response['nama'] = $data['Nama_kelas'];
            $response['foto'] = "foto/Logo.png"; 
            $response['info_kamuflase'] = "Wali Kelas: " . $data['Wali_kelas'] . "\nPenanggung Jawab: " . $data['Penanggung_jawab'];
        }
    }

    // Jika data tidak ditemukan sama sekali
    if (empty($response)) {
        $response['nama'] = "Data Tidak Ditemukan";
        $response['foto'] = "foto/no-image.png";
        $response['info_kamuflase'] = "-";
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>
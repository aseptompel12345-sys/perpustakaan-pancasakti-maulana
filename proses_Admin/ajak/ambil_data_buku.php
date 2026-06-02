<?php
// Pastikan jumlah ../ benar untuk sampai ke root folder (naik 3 tingkat)
include "../../config/koneksi.php"; 

if (isset($_POST['id'])) {
    $id = mysqli_real_escape_string($db, $_POST['id']);
    $response = array();

    // Query disesuaikan dengan foto struktur tabel buku
    // Menggunakan kolom 'Judul' dan 'Lokasi_rak'
    $sql = "SELECT Judul, Foto, Lokasi_rak FROM buku WHERE Id_buku = '$id'";
    $query = mysqli_query($db, $sql);
    $data = mysqli_fetch_assoc($query);

    if ($data) {
        $response['judul'] = $data['Judul']; // Menggunakan kolom 'Judul'
        $response['rak']   = $data['Lokasi_rak']; // Menggunakan kolom 'Lokasi_rak'
        
        // Alamat folder gambar disesuaikan menjadi FOTOS/foto_sampul_buku/
        if (!empty($data['Foto'])) {
            $response['foto'] = "FOTOS/foto_sampul_buku/" . $data['Foto'];
        } else {
            // Gambar cadangan jika kolom Foto di database kosong
            $response['foto'] = "foto/no-book.png"; 
        }
    } else {
        // Jika ID Buku tidak ditemukan
        $response['judul'] = "Buku Tidak Ditemukan";
        $response['rak']   = "-";
        $response['foto']  = "foto/no-book.png";
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>
<?php
// JANGAN ADA SPASI DI ATAS TAG PHP INI
ob_start(); 
include "../../config/koneksi.php"; 

header('Content-Type: application/json');

if (isset($_POST['id'])) {
    $id = mysqli_real_escape_string($db, $_POST['id']);
    
    // 1. Ambil Nilai Denda
    $query_denda = mysqli_query($db, "SELECT Nilai FROM denda ORDER BY Id_denda DESC LIMIT 1");
    $data_denda  = mysqli_fetch_assoc($query_denda);
    $harga_denda_saat_ini = ($data_denda) ? $data_denda['Nilai'] : 0;

    // 2. Query data peminjaman
    $sql = "SELECT p.*, 
            (SELECT SUM(Jml_kembali) FROM pengembalian WHERE Id_peminjaman = p.Id_peminjaman) as total_masuk
            FROM peminjaman p 
            WHERE p.Id_peminjaman = '$id'";
            
    $query = mysqli_query($db, $sql);
    $data = mysqli_fetch_assoc($query);

    if ($data) {
        $sudah_kembali = ($data['total_masuk'] != null) ? $data['total_masuk'] : 0;
        $sisa_buku = $data['Jumlah'] - $sudah_kembali;

        if ($sisa_buku <= 0) {
            $response = ['status' => 'lunas'];
        } else {
            // --- PERBAIKAN FINAL PATH FOTO ---
            // Kita hapus path tambahan karena data di DB sudah lengkap
            // Cukup kirimkan data asli dari kolom Foto_peminjam dan Foto_buku

            $response = [
                'status'        => 'success',
                'id_anggota'    => $data['Id_anggota'],
                'nama'          => $data['Nama_peminjam'],
                'jenis'         => $data['Jenis_anggota'],
                'identitas'     => $data['Detail_identitas'],
                
                // Gunakan data langsung dari DB karena sudah mengandung path lengkap
                'foto_peminjam' => $data['Foto_peminjam'],
                
                'id_buku'       => $data['Id_buku'],
                'judul'         => $data['Judul_buku'],
                'rak'           => $data['Lokasi_rak'],
                
                // Gunakan data langsung dari DB karena sudah mengandung path lengkap
                'foto_buku'     => $data['Foto_buku'],
                
                'jml_awal'      => $data['Jumlah'],
                'sisa'          => $sisa_buku,
                'tgl_jatuh_tempo'   => $data['Tgl_jatuh_tempo'],
                'denda_perhari' => $harga_denda_saat_ini
            ];
        }
    } else {
        $response = ['status' => 'error'];
    }

    ob_clean();
    echo json_encode($response);
    exit;
}
?>
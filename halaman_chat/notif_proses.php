<?php
include "../config/koneksi.php";

$id_admin_login = $_SESSION['Id_admin']; 
$tgl_sekarang   = date('Y-m-d');

$query = "
    SELECT Id_kunjungan AS id, Nama_pengunjung AS nama, 'Kunjungan' AS asal, Keperluan AS info_1, '' AS info_2, '' AS info_3
    FROM kunjungan 
    WHERE Admin_pemberi_izin = 'Menunggu Izin' 
    AND Id_kunjungan NOT IN (SELECT id_transaksi FROM notif_penerima WHERE id_admin = '$id_admin_login' AND tabel_asal = 'kunjungan')
    
    UNION
    
    SELECT Id_peminjaman AS id, Nama_peminjam AS nama, 'Peminjaman' AS asal, Judul_buku AS info_1, Jumlah AS info_2, '' AS info_3
    FROM peminjaman 
    WHERE Admin_pemberi_izin = 'Menunggu Izin' 
    AND Id_peminjaman NOT IN (SELECT id_transaksi FROM notif_penerima WHERE id_admin = '$id_admin_login' AND tabel_asal = 'peminjaman')
    
    UNION
    
    SELECT Id_pengembalian AS id, Nama_peminjam AS nama, 'Pengembalian' AS asal, Judul_buku AS info_1, Jml_kembali AS info_2, Denda AS info_3
    FROM pengembalian 
    WHERE Admin_pemberi_izin = 'Menunggu Izin' 
    AND Id_pengembalian NOT IN (SELECT id_transaksi FROM notif_penerima WHERE id_admin = '$id_admin_login' AND tabel_asal = 'pengembalian')

    UNION

    SELECT Id_peminjaman AS id, Nama_peminjam AS nama, 'Peringatan' AS asal, Judul_buku AS info_1, Sisa_pinjam AS info_2, Tgl_jatuh_tempo AS info_3
    FROM peminjaman
    WHERE (Status = 'Dipinjam' OR Status = 'Sebagian')
    AND Tgl_jatuh_tempo < '$tgl_sekarang'
    AND Id_peminjaman NOT IN (SELECT id_transaksi FROM notif_penerima WHERE id_admin = '$id_admin_login' AND tabel_asal = 'peringatan')
";

$result = mysqli_query($db, $query);
$data_notif = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $tipe = $row['asal'];
        $pesan_teks = "";

        if ($tipe == 'Kunjungan') {
            $pesan_teks = "Keperluan: " . $row['info_1'];
        } 
        elseif ($tipe == 'Peminjaman') {
            $pesan_teks = "Judul Buku: " . $row['info_1'] . "<br>" . 
                          "Jumlah Pinjam: " . $row['info_2']. " Buku.";
        } 
        elseif ($tipe == 'Pengembalian') {
            $pesan_teks = "Judul Buku: " . $row['info_1'] . "<br>" . 
                          "Jumlah Kembali: " . $row['info_2']. " Buku.<br>" . 
                          "Denda: Rp " . number_format($row['info_3'], 0, ',', '.');
        }
        /* ISI PESAN UNTUK NOTIFIKASI KUNING */
        elseif ($tipe == 'Peringatan') {
            // Mengubah format tanggal jatuh tempo ke d-m-Y agar lebih mudah dibaca admin
            $tgl_format = date('d-m-Y', strtotime($row['info_3']));
            $pesan_teks = "MELEWATI JATUH TEMPO!<br>" .
                          "Buku: " . $row['info_1'] . "<br>" .
                          "Sisa: " . $row['info_2'] . " Buku (Jatuh Tempo: " . $tgl_format . ")";
        }

        $data_notif[] = [
            'id' => $row['id'],
            'nama' => $row['nama'], 
            'keperluan' => $pesan_teks, 
            'tipe' => $tipe
        ];
    }
    echo json_encode(['ada_data' => true, 'data' => $data_notif]);
} else {
    echo json_encode(['ada_data' => false]);
}
?>
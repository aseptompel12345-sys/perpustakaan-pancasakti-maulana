<?php
include "../config/koneksi.php";

if(isset($_POST['id']) && isset($_POST['tipe'])) {
    $id_trans   = mysqli_real_escape_string($db, $_POST['id']);
    
    // GUNAKAN TRIM UNTUK MEMBUANG SPASI TERSEMBUNYI, LALU UBAH KE HURUF KECIL
    $tabel_asal = strtolower(trim($_POST['tipe']));
    
    $id_admin   = $_SESSION['Id_admin'];

    // Cek dulu apakah sudah ada agar tidak duplikat
    $cek = mysqli_query($db, "SELECT * FROM notif_penerima 
                              WHERE id_transaksi = '$id_trans' 
                              AND tabel_asal = '$tabel_asal' 
                              AND id_admin = '$id_admin'");
    
    if(mysqli_num_rows($cek) == 0) {
        mysqli_query($db, "INSERT INTO notif_penerima (id_transaksi, tabel_asal, id_admin) 
                           VALUES ('$id_trans', '$tabel_asal', '$id_admin')");
    }
}
?>
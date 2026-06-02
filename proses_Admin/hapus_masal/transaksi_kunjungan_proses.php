<?php
// Hubungkan ke file koneksi (sesuaikan pathnya)
include "../../config/koneksi.php";

if (isset($_POST['confirm_delete_kunjungan'])) {
    // 1. Ambil data dari Form
    $tgl_mulai   = $_POST['tgl_mulai'];
    $tgl_selesai = $_POST['tgl_selesai'];
    $jam_mulai   = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];
    $jenis       = mysqli_real_escape_string($db, $_POST['jenis_anggota']);
    $keperluan   = mysqli_real_escape_string($db, $_POST['keperluan']);
    
    $password_input = $_POST['password_konfirmasi'];
    $id_user_log    = $_SESSION['Id_admin']; // Pastikan session ini sesuai dengan saat login admin

    // 2. Verifikasi Password Admin (Keamanan)
    $query_admin = mysqli_query($db, "SELECT users.Password FROM admin 
                                      JOIN users ON admin.Id_user = users.Id_user 
                                      WHERE admin.Id_user = '$id_user_log'");
    $data_admin = mysqli_fetch_array($query_admin);

    if (!$data_admin || !password_verify($password_input, $data_admin['Password'])) {
        echo "<script>alert('Password Salah! Verifikasi gagal.'); window.location='../../index_Admin.php?page=transaksi_kunjungan';</script>";
        exit;
    }

    // 3. Susun Query Filter (Logika Karantina)
    $conditions = [];

    // Filter Tanggal (Range)
    if (!empty($tgl_mulai) && !empty($tgl_selesai)) {
        $conditions[] = "Tgl_kunjungan BETWEEN '$tgl_mulai' AND '$tgl_selesai'";
    }
    
    // Filter Jam (Range)
    if (!empty($jam_mulai) && !empty($jam_selesai)) {
        $conditions[] = "Jam_kunjungan BETWEEN '$jam_mulai' AND '$jam_selesai'";
    }

    // Filter Jenis Anggota
    if (!empty($jenis)) {
        $conditions[] = "Jenis_anggota = '$jenis'";
    }

    // Filter Keperluan
    if (!empty($keperluan)) {
        $conditions[] = "Keperluan = '$keperluan'";
    }

    // Jika admin tidak mengisi filter sama sekali, cegah hapus total secara tidak sengaja
    if (empty($conditions)) {
        echo "<script>alert('Gagal! Silakan pilih minimal satu filter untuk menghapus.'); window.location='../../index_Admin.php?page=transaksi_kunjungan';</script>";
        exit;
    }

    $where_sql = implode(" AND ", $conditions);

    // 4. Proses Identifikasi (Karantina ID)
    $id_karantina = [];
    $cari_kunjungan = mysqli_query($db, "SELECT Id_kunjungan FROM kunjungan WHERE $where_sql");

    while ($row = mysqli_fetch_array($cari_kunjungan)) {
        $id_karantina[] = $row['Id_kunjungan'];
    }

    // 5. Eksekusi Penghapusan
    if (!empty($id_karantina)) {
        $list_id = implode(",", $id_karantina);
        
        $query_hapus = mysqli_query($db, "DELETE FROM kunjungan WHERE Id_kunjungan IN ($list_id)");

        if ($query_hapus) {
            $jumlah = count($id_karantina);
            echo "<script>
                    alert('Berhasil! $jumlah data riwayat kunjungan telah dibersihkan.'); 
                    window.location='../../index_Admin.php?page=transaksi_kunjungan';
                  </script>";
        } else {
            echo "<script>alert('Gagal menghapus data dari database.'); window.location='../../index_Admin.php?page=transaksi_kunjungan';</script>";
        }
    } else {
        echo "<script>alert('Tidak ada data kunjungan yang cocok dengan filter tersebut.'); window.location='../../index_Admin.php?page=transaksi_kunjungan';</script>";
    }
}
?>
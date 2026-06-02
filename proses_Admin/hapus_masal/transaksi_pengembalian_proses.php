<?php
include "../../config/koneksi.php";

if (isset($_POST['confirm_delete_pengembalian'])) {
    // 1. Ambil data dari Form
    $tgl_mulai   = $_POST['tgl_kembali_mulai'];
    $tgl_selesai = $_POST['tgl_kembali_selesai'];
    $denda_min   = $_POST['denda_min'];
    $denda_max   = $_POST['denda_max'];
    $jenis       = mysqli_real_escape_string($db, $_POST['jenis_anggota']);
    $status_form = mysqli_real_escape_string($db, $_POST['status']); // Nilai: Selesai
    
    $password_input = $_POST['password_konfirmasi'];
    $id_user_log    = $_SESSION['Id_admin'];

    // 2. Verifikasi Password Admin
    $query_admin = mysqli_query($db, "SELECT users.Password FROM admin 
                                      JOIN users ON admin.Id_user = users.Id_user 
                                      WHERE admin.Id_user = '$id_user_log'");
    $data_admin = mysqli_fetch_array($query_admin);

    if (!$data_admin || !password_verify($password_input, $data_admin['Password'])) {
        echo "<script>alert('Password Salah! Verifikasi gagal.'); window.location='../../index_Admin.php?page=transaksi_pengembalian';</script>";
        exit;
    }

    // 3. Susun Query Filter untuk MENCARI pemicu (Harus ada data Selesai di range ini)
    $conditions = [];
    $conditions[] = "Status = '$status_form'"; // PENGUNCI: Hanya Id_peminjaman yang sudah punya baris 'Selesai'

    if (!empty($tgl_mulai) && !empty($tgl_selesai)) {
        $conditions[] = "Tgl_kembali BETWEEN '$tgl_mulai' AND '$tgl_selesai'";
    }
    
    if ($denda_min !== '' && $denda_max !== '') {
        $conditions[] = "Denda BETWEEN '$denda_min' AND '$denda_max'";
    }

    if (!empty($jenis)) {
        $conditions[] = "Jenis_anggota = '$jenis'";
    }

    $where_sql = implode(" AND ", $conditions);

    // 4. Proses Karantina ID Peminjaman
    $id_peminjaman_karantina = [];
    // Query ini hanya mengambil Id_peminjaman yang MEMILIKI setidaknya satu baris berstatus 'Selesai' sesuai filter
    $cari_induk = mysqli_query($db, "SELECT DISTINCT Id_peminjaman FROM pengembalian WHERE $where_sql");

    while ($row = mysqli_fetch_array($cari_induk)) {
        $id_peminjaman_karantina[] = $row['Id_peminjaman'];
    }

    // 5. Eksekusi Penghapusan
    if (!empty($id_peminjaman_karantina)) {
        $list_id_induk = implode(",", $id_peminjaman_karantina);
        
        // Final Check: Hapus semua riwayat (Sebagian & Selesai) 
        // HANYA untuk Id_peminjaman yang sudah terbukti punya status 'Selesai' di langkah 4.
        $query_hapus = mysqli_query($db, "DELETE FROM pengembalian WHERE Id_peminjaman IN ($list_id_induk)");

        if ($query_hapus) {
            echo "<script>
                    alert('Maintenance Berhasil! Riwayat pengembalian (Sebagian & Selesai) dari transaksi yang sudah tuntas telah dibersihkan.'); 
                    window.location='../../index_Admin.php?page=transaksi_pengembalian';
                  </script>";
        } else {
            echo "<script>alert('Gagal menghapus data pengembalian.'); window.location='../../index_Admin.php?page=transaksi_pengembalian';</script>";
        }
    } else {
        // Jika filter hanya mengenai data 'Sebagian' tanpa ada 'Selesai'-nya, maka id_peminjaman_karantina akan kosong
        echo "<script>alert('Tidak ada riwayat pengembalian SELESAI yang ditemukan dalam filter ini. Data SEBAGIAN tetap aman.'); window.location='../../index_Admin.php?page=transaksi_pengembalian';</script>";
    }
}
?>
<?php
include "../../config/koneksi.php";

if (isset($_POST['confirm_delete_peminjaman'])) {
    // 1. Ambil data dari Form
    $tgl_mulai   = $_POST['tgl_pinjam_mulai'];
    $tgl_selesai = $_POST['tgl_pinjam_selesai'];
    $jenis       = mysqli_real_escape_string($db, $_POST['jenis_anggota']);
    $status      = mysqli_real_escape_string($db, $_POST['status']); // Nilai: Selesai
    
    $password_input = $_POST['password_konfirmasi'];
    $id_user_log    = $_SESSION['Id_admin'];

    // 2. Verifikasi Password Admin
    $query_admin = mysqli_query($db, "SELECT users.Password FROM admin 
                                      JOIN users ON admin.Id_user = users.Id_user 
                                      WHERE admin.Id_user = '$id_user_log'");
    $data_admin = mysqli_fetch_array($query_admin);

    if (!$data_admin || !password_verify($password_input, $data_admin['Password'])) {
        echo "<script>alert('Password Salah!'); window.location='../../index_Admin.php?page=transaksi_pinjamn';</script>";
        exit;
    }

    // 3. Susun Query Filter (Karantina ID)
    $conditions = [];
    $conditions[] = "Status = '$status'"; // Harus Selesai
    $conditions[] = "Sisa_pinjam = 0";    // --- PENGAMAN BARU: Sisa pinjam harus nol ---

    if (!empty($tgl_mulai) && !empty($tgl_selesai)) {
        $conditions[] = "Tgl_pinjam BETWEEN '$tgl_mulai' AND '$tgl_selesai'";
    }

    if (!empty($jenis)) {
        $conditions[] = "Jenis_anggota = '$jenis'";
    }

    $where_sql = implode(" AND ", $conditions);

    // 4. Proses Karantina ID
    $id_karantina = [];
    $cari_pinjam = mysqli_query($db, "SELECT Id_peminjaman FROM peminjaman WHERE $where_sql");

    while ($row = mysqli_fetch_array($cari_pinjam)) {
        $id_karantina[] = $row['Id_peminjaman'];
    }

    // 5. Eksekusi Penghapusan Beruntun
    if (!empty($id_karantina)) {
        $list_id = implode(",", $id_karantina);
        
        // Hapus anak (pengembalian) dulu
        mysqli_query($db, "DELETE FROM pengembalian WHERE Id_peminjaman IN ($list_id)");

        // Hapus induk (peminjaman)
        $query_final = mysqli_query($db, "DELETE FROM peminjaman WHERE Id_peminjaman IN ($list_id)");

        if ($query_final) {
            $jumlah = count($id_karantina);
            echo "<script>
                    alert('Berhasil! $jumlah data peminjaman dengan Sisa Pinjam 0 telah dihapus.'); 
                    window.location='../../index_Admin.php?page=transaksi_pinjam';
                  </script>";
        } else {
            echo "<script>alert('Gagal menghapus data!'); window.location='../../index_Admin.php?page=transaksi_pinjam';</script>";
        }
    } else {
        echo "<script>alert('Tidak ada data SELESAI dengan SISA PINJAM 0 yang cocok dengan filter.'); window.location='../../index_Admin.php?page=transaksi_pinjam';</script>";
    }
}
?>
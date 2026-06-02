<?php
include "../../config/koneksi.php";

if (isset($_POST['confirm_delete_siswa'])) {
    $kelas          = mysqli_real_escape_string($db, $_POST['kelas']);
    $jurusan        = mysqli_real_escape_string($db, $_POST['jurusan']);
    $password_input = $_POST['password_konfirmasi'];
    $id_user_log    = $_SESSION['Id_admin'];

    // 1. Verifikasi Password Admin
    $query_admin = mysqli_query($db, "SELECT users.Password FROM admin 
                                      JOIN users ON admin.Id_user = users.Id_user 
                                      WHERE admin.Id_user = '$id_user_log'");
    $data_admin = mysqli_fetch_array($query_admin);

    if (!$data_admin || !password_verify($password_input, $data_admin['Password'])) {
        echo "<script>alert('Password Salah!'); window.location='../../index_Admin.php?page=anggota_siswa';</script>";
        exit;
    }

    // 2. Susun Filter
    $conditions = [];
    if (!empty($kelas))   { $conditions[] = "Kelas = '$kelas'"; }
    if (!empty($jurusan)) { $conditions[] = "Jurusan LIKE '%$jurusan%'"; }
    $where_sql = implode(" AND ", $conditions);

    // 3. Identifikasi Siswa Siap Hapus (Hanya yang tidak punya pinjaman aktif)
    $id_siswa_hapus = [];
    $id_anggota_hapus = [];
    $id_user_hapus = [];
    $list_foto = [];

    $cari_siswa = mysqli_query($db, "SELECT s.Id_siswa, s.Id_anggota, s.Foto, a.Id_user 
                                     FROM anggota_siswa s
                                     JOIN anggota a ON s.Id_anggota = a.Id_anggota
                                     WHERE $where_sql");

    while ($row = mysqli_fetch_array($cari_siswa)) {
        $id_anggota = $row['Id_anggota'];
        
        $cek_pinjam = mysqli_query($db, "SELECT Id_peminjaman FROM peminjaman 
                                         WHERE Id_anggota = '$id_anggota' 
                                         AND Status IN ('Dipinjam', 'Sebagian')");
        
        if (mysqli_num_rows($cek_pinjam) == 0) {
            $id_siswa_hapus[]   = $row['Id_siswa'];
            $id_anggota_hapus[] = $row['Id_anggota'];
            $id_user_hapus[]    = $row['Id_user'];
            if (!empty($row['Foto']) && $row['Foto'] != 'default.jpg') {
                $list_foto[] = $row['Foto'];
            }
        }
    }

    // 4. EKSEKUSI PENGHAPUSAN BERUNTUN
    if (!empty($id_siswa_hapus)) {
        $str_siswa   = implode(",", $id_siswa_hapus);
        $str_anggota = implode("','", $id_anggota_hapus);
        $str_user    = implode(",", $id_user_hapus);

        // A. HAPUS RIWAYAT KUNJUNGAN (Solusi untuk Error yang kamu alami)
        mysqli_query($db, "DELETE FROM kunjungan WHERE Id_anggota IN ('$str_anggota')");

        // B. Hapus riwayat transaksi lainnya
        mysqli_query($db, "DELETE FROM pengembalian WHERE Id_anggota IN ('$str_anggota')");
        mysqli_query($db, "DELETE FROM peminjaman WHERE Id_anggota IN ('$str_anggota') AND Status = 'Selesai'");

        // C. Hapus Data Utama (Siswa -> Anggota -> Users)
        mysqli_query($db, "DELETE FROM anggota_siswa WHERE Id_siswa IN ($str_siswa)");
        mysqli_query($db, "DELETE FROM anggota WHERE Id_anggota IN ('$str_anggota')");
        $query_final = mysqli_query($db, "DELETE FROM users WHERE Id_user IN ($str_user)");

        if ($query_final) {
            foreach ($list_foto as $foto) {
                $path = "../../FOTOS/foto_siswa/" . $foto;
                if (file_exists($path)) { unlink($path); }
            }
            $berhasil = count($id_siswa_hapus);
            echo "<script>alert('Berhasil! $berhasil data siswa, riwayat transaksi, dan riwayat kunjungan telah dihapus.'); window.location='../../index_Admin.php?page=anggota_siswa';</script>";
        }
    } else {
        echo "<script>alert('Gagal! Tidak ada data yang bisa dihapus karena masih ada pinjaman aktif.'); window.location='../../index_Admin.php?page=anggota_siswa';</script>";
    }
}
?>
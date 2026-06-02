<?php
// 1. Ambil ID dari URL dengan parameter 'id_kelas'
$id_kelas = isset($_GET['id_kelas']) ? mysqli_real_escape_string($db, $_GET['id_kelas']) : '';

if ($id_kelas == '') {
    echo "<script>alert('ID Kelas tidak ditemukan!'); window.location='index_Admin.php?page=anggota_kelas';</script>";
    return; // Gunakan return agar index tidak mati (stuck)
}

// 2. Cari Id_anggota dan Id_user terlebih dahulu sebelum dihapus
$query_cari = mysqli_query($db, "SELECT anggota_kelas.Id_anggota, anggota.Id_user 
                                 FROM anggota_kelas 
                                 JOIN anggota ON anggota_kelas.Id_anggota = anggota.Id_anggota 
                                 WHERE anggota_kelas.Id_kelas = '$id_kelas'");

$data_lengkap = mysqli_fetch_array($query_cari);

if ($data_lengkap) {
    $id_anggota = $data_lengkap['Id_anggota'];
    $id_user    = $data_lengkap['Id_user'];

    // --- TAMBAHAN: CEK STATUS PINJAMAN ---
    $cek_pinjam = mysqli_query($db, "SELECT Id_peminjaman FROM peminjaman 
                                     WHERE Id_anggota = '$id_anggota' 
                                     AND Status IN ('Dipinjam', 'Sebagian')");

    if (mysqli_num_rows($cek_pinjam) > 0) {
        echo "<script>
                alert('Gagal Hapus! Akun Kelas masih memiliki buku yang belum dikembalikan.'); 
                window.location='index_Admin.php?page=anggota_kelas';
              </script>";
        return; // Menghentikan proses tanpa mematikan index agar tidak stuck
    }
    // --- AKHIR TAMBAHAN CEK ---

    // --- TAMBAHAN: HAPUS RIWAYAT TRANSAKSI & KUNJUNGAN ---
    mysqli_query($db, "DELETE FROM kunjungan WHERE Id_anggota = '$id_anggota'");
    mysqli_query($db, "DELETE FROM pengembalian WHERE Id_anggota = '$id_anggota'");
    mysqli_query($db, "DELETE FROM peminjaman WHERE Id_anggota = '$id_anggota' AND Status = 'Selesai'");
    // --- AKHIR TAMBAHAN HAPUS ---

    // 3. PROSES HAPUS BERANTAI (Sesuai kode asli)
    $hapus_kelas = mysqli_query($db, "DELETE FROM anggota_kelas WHERE Id_kelas = '$id_kelas'");
    $hapus_anggota = mysqli_query($db, "DELETE FROM anggota WHERE Id_anggota = '$id_anggota'");
    $hapus_user = mysqli_query($db, "DELETE FROM users WHERE Id_user = '$id_user'");

    if ($hapus_user) {
        echo "<script>
                alert('Data Kelas, Akun, dan Seluruh Riwayat Berhasil Dihapus!'); 
                window.location='index_Admin.php?page=anggota_kelas';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus akun: " . mysqli_error($db) . "'); 
                window.location='index_Admin.php?page=anggota_kelas';
              </script>";
    }
} else {
    echo "<script>
            alert('Data tidak ditemukan di database!'); 
            window.location='index_Admin.php?page=anggota_kelas';
          </script>";
}
?>
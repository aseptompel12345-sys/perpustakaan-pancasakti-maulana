<?php
include "../../config/koneksi.php";

if (isset($_POST['simpan'])) {
    // 1. TANGKAP DATA DARI FORM (Identitas Peminjam)
    $id_anggota      = mysqli_real_escape_string($db, $_POST['Id_anggota']);
    $jenis_anggota   = $_POST['Jenis_anggota'];
    $foto_peminjam   = $_POST['Foto_peminjam']; 
    $nama_peminjam   = mysqli_real_escape_string($db, $_POST['Nama_peminjam']);
    $detail_id       = mysqli_real_escape_string($db, $_POST['Detail_identitas']);
    
    // 2. TANGKAP DATA DARI FORM (Detail Buku & Waktu)
    $id_buku         = mysqli_real_escape_string($db, $_POST['Id_buku']);
    $foto_buku       = $_POST['Foto_buku'];
    $judul_buku      = mysqli_real_escape_string($db, $_POST['Judul_buku']);
    $lokasi_rak      = mysqli_real_escape_string($db, $_POST['Lokasi_rak']);
    $jumlah_pinjam   = (int)$_POST['Jumlah'];
    
    $tgl_pinjam      = $_POST['Tgl_pinjam'];
    $tgl_jatuh_tempo = $_POST['Tgl_jatuh_tempo'];
    $status          = "Dipinjam"; 

    // ================================================================
    // LOGIKA PERIZINAN OTOMATIS: AMBIL NAMA ADMIN DARI SESSION
    // ================================================================
    $admin_pengizin = $_SESSION['nama_user']; // Mengambil nama admin yang sedang login

    // 3. LOGIKA PEMBATASAN (Maksimal 3 Buku untuk Siswa & Guru)
    if ($jenis_anggota == "Siswa" || $jenis_anggota == "Guru") {
        
        // Hitung total buku yang sedang dipinjam (status 'Dipinjam')
        $sql_cek = "SELECT SUM(Jumlah) as total FROM peminjaman 
                    WHERE Id_anggota = '$id_anggota' AND Status = 'Dipinjam'";
        $query_cek = mysqli_query($db, $sql_cek);
        $data_cek = mysqli_fetch_assoc($query_cek);
        $total_pinjam = $data_cek['total'] ?? 0;

        if ($total_pinjam >= 3) {
            echo "<script>
                    alert('Gagal! Anggota ($jenis_anggota) sudah meminjam $total_pinjam buku. Maksimal adalah 3 buku.');
                    window.location.href='../../index_Admin.php?page=transaksi_pinjam_from';
                  </script>";
            exit;
        }
        
        // Paksa jumlah menjadi 1 untuk individu
        $jumlah_pinjam = 1;
    }

    // 4. CEK STOK REAL-TIME
    $sql_stok_real = "SELECT (Stok_awal_buku - IFNULL((SELECT SUM(Jumlah) FROM peminjaman WHERE Id_buku = b.Id_buku AND Status = 'Dipinjam'), 0)) AS stok_asli 
                      FROM buku b WHERE Id_buku = '$id_buku'";
    $cek_stok = mysqli_query($db, $sql_stok_real);
    $r_stok = mysqli_fetch_assoc($cek_stok);
    
    if ($r_stok['stok_asli'] < $jumlah_pinjam) {
        echo "<script>
                alert('Gagal! Stok buku tidak mencukupi secara real-time. Sisa: ".$r_stok['stok_asli']."');
                window.history.back();
              </script>";
        exit;
    }

    // 5. EKSEKUSI INSERT KE TABEL PEMINJAMAN (Tambah kolom Admin_pemberi_izin)
    $query_insert = "INSERT INTO peminjaman (
        Id_anggota, Jenis_anggota, Foto_peminjam, Nama_peminjam, Detail_identitas,
        Id_buku, Foto_buku, Judul_buku, Lokasi_rak, 
        Tgl_pinjam, Tgl_jatuh_tempo, Jumlah, Sisa_pinjam, Status, Admin_pemberi_izin
    ) VALUES (
        '$id_anggota', '$jenis_anggota', '$foto_peminjam', '$nama_peminjam', '$detail_id',
        '$id_buku', '$foto_buku', '$judul_buku', '$lokasi_rak', 
        '$tgl_pinjam', '$tgl_jatuh_tempo', '$jumlah_pinjam', '$jumlah_pinjam', '$status', '$admin_pengizin'
    )";

    if (mysqli_query($db, $query_insert)) {
        // Kurangi Stok di tabel buku secara permanen
        mysqli_query($db, "UPDATE buku SET Stok_buku_tersedia = Stok_buku_tersedia - $jumlah_pinjam WHERE Id_buku = '$id_buku'");

        echo "<script>
                alert('Berhasil! Transaksi peminjaman telah disahkan oleh $admin_pengizin.');
                window.location.href='../../index_Admin.php?page=transaksi_pinjam';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menyimpan data: " . mysqli_error($db) . "');
                window.history.back();
              </script>";
    }
}
?>
<?php
include "../../config/koneksi.php";

if (isset($_POST['ajukan'])) {
    $id_anggota = $_SESSION['Id_anggota'];
    
    // ================================================================
    // LOGIKA 1: ANTI-SPAM (Maksimal 1 antrean 'Menunggu Izin')
    // ================================================================
    $cek_antrean = mysqli_query($db, "SELECT Id_peminjaman FROM peminjaman 
                                      WHERE Id_anggota = '$id_anggota' 
                                      AND Admin_pemberi_izin = 'Menunggu Izin'");

    if (mysqli_num_rows($cek_antrean) > 0) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<body></body>";
        echo "<script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Permintaan Tertunda',
                    text: 'Anda masih memiliki satu pengajuan yang menunggu verifikasi Admin.',
                    confirmButtonColor: '#f39c12'
                }).then(() => {
                    window.location.href='../../index_anggota.php?anggota=transaksi_pinjam_kembali';
                });
              </script>";
        exit(); 
    }

    // ================================================================
    // LOGIKA 2: BATAS TRANSAKSI (Maksimal 3 Baris Data Status 'Dipinjam')
    // ================================================================
    // Kita gunakan COUNT(*) bukan SUM(Jumlah) agar yang dihitung adalah jumlah transaksinya
    $sql_cek_status = "SELECT COUNT(*) as total_transaksi FROM peminjaman 
                       WHERE Id_anggota = '$id_anggota' AND Status = 'Dipinjam'";
    $query_cek_status = mysqli_query($db, $sql_cek_status);
    $data_cek = mysqli_fetch_assoc($query_cek_status);
    $total_transaksi = $data_cek['total_transaksi'] ?? 0;

    if ($total_transaksi >= 3) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<body></body>";
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Batas Pinjam Tercapai',
                    text: 'Anda sudah memiliki $total_transaksi transaksi dengan status Dipinjam. Silakan kembalikan salah satu buku terlebih dahulu.',
                    confirmButtonColor: '#d33'
                }).then(() => {
                    window.location.href='../../index_anggota.php?anggota=transaksi_pinjam_kembali';
                });
              </script>";
        exit();
    }

    // ================================================================
    // PROSES LANJUTAN (Ambil Detail Anggota)
    // ================================================================
    $sql_user = mysqli_query($db, "SELECT a.Jenis_anggota, 
                                   s.Nama_siswa, g.Nama_guru, k.Nama_kelas, 
                                   s.Foto as Foto_s, g.Foto as Foto_g,
                                   s.Kelas, s.Jurusan, g.No_tlp, g.Alamat
                                   FROM anggota a
                                   LEFT JOIN anggota_siswa s ON a.Id_anggota = s.Id_anggota
                                   LEFT JOIN anggota_guru g ON a.Id_anggota = g.Id_anggota
                                   LEFT JOIN anggota_kelas k ON a.Id_anggota = k.Id_anggota
                                   WHERE a.Id_anggota = '$id_anggota'");
    
    $u = mysqli_fetch_assoc($sql_user);
    $nama_peminjam = $u['Nama_siswa'] ?? $u['Nama_guru'] ?? $u['Nama_kelas'];
    $jenis_agt     = $u['Jenis_anggota'];
    
    // Logika Foto & Identitas (Sama seperti sebelumnya)
    if ($jenis_agt == "Siswa" && !empty($u['Foto_s'])) {
        $foto_peminjam = "FOTOS/foto_siswa/" . $u['Foto_s'];
    } elseif ($jenis_agt == "Guru" && !empty($u['Foto_g'])) {
        $foto_peminjam = "FOTOS/foto_guru/" . $u['Foto_g'];
    } else {
        $foto_peminjam = "foto/logo.png";
    }
    
    if($jenis_agt == "Siswa") {
        $detail_id = "Kelas: " . $u['Kelas'] . " | Jurusan: " . $u['Jurusan'];
    } elseif($jenis_agt == "Guru") {
        $detail_id = "No.Tlp: " . $u['No_tlp'] . " | Alamat: " . $u['Alamat'];
    } else {
        $detail_id = "Anggota Kelas: " . $u['Nama_kelas'];
    }

    // Ambil Data Buku dari Form
    $id_buku         = mysqli_real_escape_string($db, $_POST['Id_buku']);
    $judul_buku      = mysqli_real_escape_string($db, $_POST['Judul_buku']);
    $foto_buku       = $_POST['Foto_buku'];
    $lokasi_rak      = $_POST['Lokasi_rak'];
    $jumlah          = (int)$_POST['Jumlah']; // Di sini jumlah bisa banyak (khusus kelas)
    $tgl_pinjam      = $_POST['Tgl_pinjam'];
    $tgl_jt          = $_POST['Tgl_jatuh_tempo'];

    // INSERT DATA
    $query = "INSERT INTO peminjaman (
                Id_anggota, Jenis_anggota, Foto_peminjam, Nama_peminjam, Detail_identitas,
                Id_buku, Foto_buku, Judul_buku, Lokasi_rak, 
                Tgl_pinjam, Tgl_jatuh_tempo, Jumlah, Sisa_pinjam, Status, Admin_pemberi_izin
              ) VALUES (
                '$id_anggota', '$jenis_agt', '$foto_peminjam', '$nama_peminjam', '$detail_id',
                '$id_buku', '$foto_buku', '$judul_buku', '$lokasi_rak',
                '$tgl_pinjam', '$tgl_jt', '$jumlah', '$jumlah', 'Dipinjam', 'Menunggu Izin'
              )";

    if (mysqli_query($db, $query)) {
        // Kurangi Stok Buku
        mysqli_query($db, "UPDATE buku SET Stok_buku_tersedia = Stok_buku_tersedia - $jumlah WHERE Id_buku = '$id_buku'");
        
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<body></body>";
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil Diajukan',
                    text: 'Data peminjaman buku $judul_buku berhasil dikirim ke Admin.',
                    confirmButtonColor: '#28a745'
                }).then(() => {
                    window.location.href='../../index_anggota.php?anggota=transaksi_pinjam_kembali';
                });
              </script>";
    } else {
        echo "Error: " . mysqli_error($db);
    }
}
?>
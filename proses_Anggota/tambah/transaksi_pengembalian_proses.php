<?php
include "../../config/koneksi.php";

if (isset($_POST['proses_kembali'])) {
    // 1. Ambil data dari form & session
    $id_anggota    = $_SESSION['Id_anggota']; // Kita gunakan ini untuk kunci pembatasan
    $id_peminjaman = mysqli_real_escape_string($db, $_POST['Id_peminjaman']);
    $jml_kembali   = (int)$_POST['Jml_kembali'];
    $denda         = mysqli_real_escape_string($db, $_POST['Denda']);
    $status_form   = mysqli_real_escape_string($db, $_POST['Status']); 
    $tgl_kembali   = date('Y-m-d');

    // 2. Ambil data asli dari tabel peminjaman
    $q_data = mysqli_query($db, "SELECT * FROM peminjaman WHERE Id_peminjaman = '$id_peminjaman'");
    $d = mysqli_fetch_assoc($q_data);

    if ($d) {
        // ================================================================
        // LOGIKA PERBAIKAN: PEMBATASAN GLOBAL PER ANGGOTA
        // ================================================================
        // Cek apakah anggota ini punya pengajuan pengembalian APAPUN 
        // yang statusnya masih "Menunggu Izin"
        $cek_global = mysqli_query($db, "SELECT Id_pengembalian FROM pengembalian 
                                         WHERE Id_anggota = '$id_anggota' 
                                         AND Admin_pemberi_izin = 'Menunggu Izin'");
        
        if (mysqli_num_rows($cek_global) > 0) {
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
            echo "<body><script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'Permintaan Tertunda',
                        text: 'Anda masih memiliki satu pengajuan pengembalian yang menunggu verifikasi Admin. Selesaikan itu dulu sebelum mengajukan pengembalian buku lain.',
                        confirmButtonColor: '#f39c12'
                    }).then(() => {
                        window.location.href='../../index_anggota.php?anggota=transaksi_pinjam_kembali';
                    });
                  </script></body>";
            exit(); 
        }

        // 3. Jika tidak ada antrean, baru simpan ke tabel pengembalian
        $sql_ins = "INSERT INTO pengembalian (
                        Id_peminjaman, Foto_peminjam, Id_anggota, Nama_peminjam, 
                        Detail_identitas, Jenis_anggota, Foto_buku, Id_buku, 
                        Judul_buku, Lokasi_rak, Tgl_kembali, Jml_kembali, Denda, Status, Admin_pemberi_izin
                    ) VALUES (
                        '$id_peminjaman', '{$d['Foto_peminjam']}', '{$id_anggota}', '{$d['Nama_peminjam']}', 
                        '{$d['Detail_identitas']}', '{$d['Jenis_anggota']}', '{$d['Foto_buku']}', '{$d['Id_buku']}', 
                        '{$d['Judul_buku']}', '{$d['Lokasi_rak']}', '$tgl_kembali', '$jml_kembali', '$denda', '$status_form', 'Menunggu Izin'
                    )";

        if (mysqli_query($db, $sql_ins)) {
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
            echo "<body><script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil Diajukan',
                        text: 'Data pengembalian berhasil dikirim. Silakan serahkan buku ke Admin.',
                        confirmButtonColor: '#28a745'
                    }).then(() => {
                        window.location.href='../../index_anggota.php?anggota=transaksi_pinjam_kembali';
                    });
                  </script></body>";
        } else {
            echo "Error Database: " . mysqli_error($db);
        }
    }
}
?>
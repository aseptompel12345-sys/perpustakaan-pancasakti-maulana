<?php
    include "../config/koneksi.php";
    
    $id = $_GET['id'];
    $asal = $_GET['asal'] ?? 'kunjungan'; 

    if ($asal == 'peminjaman') {
        // Data dari Tabel Peminjaman
        $q = mysqli_query($db, "SELECT * FROM peminjaman WHERE Id_peminjaman = '$id'");
        $d = mysqli_fetch_array($q);
        
        $nama      = $d['Nama_peminjam'];
        $foto      = $d['Foto_peminjam'];
        $jenis     = $d['Jenis_anggota'];
        $deskripsi = "Meminjam Buku: <b>" . $d['Judul_buku'] . "</b> (" . $d['Jumlah'] . " buku)";
        $tipe_izin = "Peminjaman";

    } elseif ($asal == 'pengembalian') {
        // Data dari Tabel Pengembalian
        $q = mysqli_query($db, "SELECT * FROM pengembalian WHERE Id_pengembalian = '$id'");
        $d = mysqli_fetch_array($q);
        
        $nama      = $d['Nama_peminjam'];
        $foto      = $d['Foto_peminjam'];
        $jenis     = $d['Jenis_anggota'];
        $deskripsi = "Mengembalikan Buku: <b>" . $d['Judul_buku'] . "</b><br>Tgl Kembali: " . $d['Tgl_kembali'] . "</b><br>Jml Dikembalikan: " . $d['Jml_kembali'] . "</b><br>Jml Denda: Rp." . number_format($d['Denda'], 0, ',', '.');
        $tipe_izin = "Pengembalian";

    } else {
        // Data dari Tabel Kunjungan
        $q = mysqli_query($db, "SELECT * FROM kunjungan WHERE Id_kunjungan = '$id'");
        $d = mysqli_fetch_array($q);
        
        $nama      = $d['Nama_pengunjung'];
        $foto      = $d['Foto_kunjungan'];
        $jenis     = $d['Jenis_anggota'];
        $deskripsi = "Keperluan: " . $d['Keperluan'];
        $tipe_izin = "Kunjungan";
    }
?>

<div class="d-flex align-items-center mb-3 border-bottom pb-2">
    <button class="btn btn-sm btn-light mr-2" onclick="kembaliKeDaftar(event)"><i class="fas fa-arrow-left"></i></button>
    <span class="font-weight-bold">Proses Perizinan <?php echo $tipe_izin; ?></span>
</div>

<div class="text-center mb-3">
    <img src="<?php echo (!empty($foto)) ? $foto : 'foto/logo.png'; ?>" class="img-circle border shadow-sm" style="width: 70px; height: 70px; object-fit: cover;">
    <h5 class="mt-2 mb-0" style="font-size: 1.1rem;"><?php echo $nama; ?></h5>
    <span class="badge badge-info shadow-sm"><?php echo $jenis; ?></span>
</div>

<div class="bg-light p-2 rounded mb-3" style="font-size: 0.9rem;">
    <strong>Detail Pengajuan:</strong><br>
    <span class="text-muted"><?php echo $deskripsi; ?></span>
</div>

<div class="row">
    <div class="col-6">
        <button onclick="prosesAksiIzin('<?php echo $id; ?>', 'izinkan', '<?php echo $tipe_izin; ?>')" class="btn btn-block btn-success btn-sm">Izinkan</button>
    </div>
    <div class="col-6">
        <button onclick="prosesAksiIzin('<?php echo $id; ?>', 'tolak', '<?php echo $tipe_izin; ?>')" class="btn btn-block btn-danger btn-sm">Tolak</button>
    </div>
</div>
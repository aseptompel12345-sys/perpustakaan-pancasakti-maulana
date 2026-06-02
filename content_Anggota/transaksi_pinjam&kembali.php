<?php
    if (!isset($db)) {
        include "config/koneksi.php"; 
    }
    
    // Ambil ID Anggota dari Session
    $id_agt = $_SESSION['Id_anggota'];

    // 1. QUERY PEMINJAMAN AKTIF (Buku yang belum lunas dikembalikan)
    $query_pinjam = mysqli_query($db, "SELECT * FROM peminjaman 
                                      WHERE Id_anggota = '$id_agt' 
                                      AND Status != 'Selesai' 
                                      ORDER BY Id_peminjaman DESC");

    // 2. QUERY RIWAYAT PENGEMBALIAN (Buku yang sudah pernah dikembalikan)
    $query_kembali = mysqli_query($db, "SELECT * FROM pengembalian 
                                       WHERE Id_anggota = '$id_agt' 
                                       ORDER BY Id_pengembalian DESC");
?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-exchange-alt text-primary"></i> Sirkulasi Buku Saya</h1>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        
        <div class="row">
            <div class="col-md-12">
                <div class="card card-warning card-outline">
                    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center w-100">
                        <h3 class="card-title text-warning font-weight-bold mb-3 mb-md-0 text-center text-md-left">
                            <i class="fas fa-hourglass-half mr-1"></i> Peminjaman Sedang Berjalan
                        </h3>
                        <!-- Menggunakan ml-md-auto agar pada layar medium/besar tombol otomatis terdorong ke paling kanan -->
                        <div class="card-tools d-flex justify-content-center justify-content-md-end ml-md-auto">
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalTambahPinjaman">
                                <i class="fas fa-plus"></i> Tambah Pinjaman
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <table id="tabelPeminjaman" class="table table-bordered table-striped table-hover align-middle">
                            <thead>
                                <tr class="text-center bg-light">
                                    <th>No</th>
                                    <th>Foto Buku</th>
                                    <th>Detail Buku</th>
                                    <th>Jml Dipinjam</th>
                                    <th>Tanggal Transaksi</th>
                                    <th>Status</th>
                                    <th>Izin Admin</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $no = 1;
                                    while($data = mysqli_fetch_array($query_pinjam)) { 
                                        $warna_status = ($data['Status'] == 'Dipinjam') ? 'warning' : 'success';
                                ?>
                                <tr>
                                    <td class="text-center align-middle"><?php echo $no++; ?></td>

                                    <td class="text-center align-middle">
                                        <img src="<?php echo (!empty($data['Foto_buku'])) ? $data['Foto_buku'] : 'dist/img/no-book.png'; ?>" >
                                    </td>

                                    <td class="align-middle text-center">
                                        <div class="font-weight-bold">
                                            <?php echo $data['Judul_buku']; ?>
                                        </div>
                                        <div class="text-muted small">
                                            <i class="fas fa-map-marker-alt text-success"></i> Lokasi Rak: <b><?php echo $data['Lokasi_rak']; ?></b>
                                        </div>
                                    </td>

                                    <td class="text-center align-middle">
                                        <span class="badge badge-danger shadow-sm px-3 py-2 font-weight-bold">
                                            <?php echo $data['Sisa_pinjam']; ?> <small>Buku</small>
                                        </span>
                                    </td>

                                    <td class="align-middle">
                                        <div class="d-flex flex-column">
                                            <small class="text-muted mb-1">
                                                <i class="fas fa-calendar-alt text-success mr-1"></i> Pinjam: <b><?php echo $data['Tgl_pinjam']; ?></b>
                                            </small>
                                            <small class="text-muted">
                                                <i class="fas fa-clock text-danger mr-1"></i> Tempo: <b class="text-danger"><?php echo $data['Tgl_jatuh_tempo']; ?></b>
                                            </small>
                                        </div>
                                    </td>

                                    <td class="text-center align-middle">
                                        <span class="badge badge-<?php echo $warna_status; ?> shadow-sm p-2 w-100"><?php echo $data['Status']; ?></span>
                                    </td>

                                    <td class="text-center align-middle">
                                        <?php if ($data['Admin_pemberi_izin'] == 'Menunggu Izin') : ?>
                                            <span class="text-muted small"><i><i class="fas fa-hourglass-half mr-1"></i> Menunggu...</i></span>
                                        <?php else : ?>
                                            <!-- Tambahkan properti inline CSS agar teks terpotong rapi dengan titik-titik -->
                                            <span class="badge badge-success p-2" 
                                                style="display: inline-block; max-width: 100px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; vertical-align: middle;" 
                                                title="<?php echo $data['Admin_pemberi_izin']; ?>">
                                                <i class="fas fa-user-check mr-1"></i> <?php echo $data['Admin_pemberi_izin']; ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center align-middle">
                                        <?php 
                                            // Logika Cek: Apakah peminjaman ini sudah diizinkan admin?
                                            // Jika belum (masih Menunggu Izin), maka tombol di-disable
                                            $is_disabled = ($data['Admin_pemberi_izin'] == 'Menunggu Izin') ? 'disabled style="opacity: 0.5; cursor: not-allowed;"' : '';
                                            $title_hint = ($data['Admin_pemberi_izin'] == 'Menunggu Izin') ? 'title="Tunggu izin pinjam admin"' : 'title="Kembalikan Buku"';
                                        ?>

                                        <button type="button" 
                                                class="btn btn-info btn-sm btn-kembali-modal" 
                                                data-id="<?php echo $data['Id_peminjaman']; ?>"
                                                <?php echo $is_disabled; ?>
                                                <?php echo $title_hint; ?>>
                                            <i class="fas fa-undo"></i>
                                        </button>                    
                                    </td>


                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <br>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-success card-outline">
                    <div class="card-header">
                        <h3 class="card-title text-success font-weight-bold">
                            <i class="fas fa-check-circle mr-1"></i> Riwayat Buku Dikembalikan
                        </h3>
                    </div>
                    <div class="card-body">
                        <table id="tabelPengembalian" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Foto Buku</th>
                                    <th>Detail Buku</th>
                                    <th>Tgl Kembali</th>
                                    <th>Jml Kembali</th>
                                    <th>Denda</th>
                                    <th>Status</th>
                                    <th>Izin Admin</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $no2 = 1;
                                    while($data2 = mysqli_fetch_array($query_kembali)) { 
                                        $warna_status = ($data2['Status'] == 'Selesai') ? 'success' : 'primary';
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $no2++; ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="<?php echo (!empty($data2['Foto_buku'])) ? $data2['Foto_buku'] : 'dist/img/no-book.png'; ?>" >
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="font-weight-bold">
                                            <?php echo $data2['Judul_buku']; ?>
                                        </div>
                                        <div class="text-muted small">
                                            <i class="fas fa-map-marker-alt text-success"></i> Lokasi Rak: <b><?php echo $data2['Lokasi_rak']; ?></b>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <i class="fas fa-calendar-alt text-success mr-1"></i> Kembali: <b><?php echo $data2['Tgl_kembali']; ?>
                                        </td>
                                    <td class="text-center align-middle">
                                        <span class="badge badge-success shadow-sm px-3 py-2 font-weight-bold">
                                            <?php echo $data2['Jml_kembali']; ?> <small>Buku</small>
                                        </span>
                                    </td>
                                    <td class="text-center text-danger">
                                        <b>Rp. </b><?php echo number_format($data2['Denda'], 0, ',', '.'); ?>
                                        
                                    </td>
                                    <td class="text-center align-middle">
                                        <span class="badge badge-<?php echo $warna_status; ?> shadow-sm p-2 w-100"><?php echo $data2['Status']; ?></span>
                                    </td>
                                    <td class="text-center align-middle">
                                        <?php if ($data2['Admin_pemberi_izin'] == 'Menunggu Izin') : ?>
                                            <span class="text-muted small"><i><i class="fas fa-hourglass-half mr-1"></i> Menunggu...</i></span>
                                        <?php else : ?>
                                            <!-- Tambahkan properti inline CSS agar teks terpotong rapi dengan titik-titik -->
                                            <span class="badge badge-success p-2" 
                                                style="display: inline-block; max-width: 100px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; vertical-align: middle;" 
                                                title="<?php echo $data2['Admin_pemberi_izin']; ?>">
                                                <i class="fas fa-user-check mr-1"></i> <?php echo $data2['Admin_pemberi_izin']; ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- Frommodal data Peminjaman -->
<div class="modal fade" id="modalTambahPinjaman" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-book mr-2"></i> Form Pengajuan Peminjaman Buku</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="proses_Anggota/tambah/transaksi_pinjam_proses.php" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label>Waktu Pinjam & Jatuh Tempo</label>
                                <div class="row">
                                    <div class="col-6">
                                        <input type="text" class="form-control text-center" name="Tgl_pinjam" value="<?php echo date('Y-m-d'); ?>" readonly>
                                    </div>
                                    <div class="col-6">
                                        <input type="text" class="form-control text-center text-danger font-weight-bold" name="Tgl_jatuh_tempo" 
                                            value="<?php echo ($_SESSION['jenis_anggota'] === 'Kelas') ? date('Y-m-d') : date('Y-m-d', strtotime('+7 days')); ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Cari & Pilih Buku</label>
                                <select class="form-control select2" name="Id_buku" id="Id_buku_pilih_agt" required style="width: 100%;">
                                    <option value="">-- Ketik Judul atau ID Buku --</option>
                                    <?php
                                        $sql_buku = mysqli_query($db, "SELECT b.Id_buku, b.Judul, 
                                                    (b.Stok_awal_buku - IFNULL((SELECT SUM(p.Jumlah) FROM peminjaman p WHERE p.Id_buku = b.Id_buku AND p.Status = 'Dipinjam'), 0)) AS stok_asli 
                                                    FROM buku b HAVING stok_asli > 0");
                                        while($b = mysqli_fetch_array($sql_buku)){
                                            echo "<option value='".$b['Id_buku']."'>".$b['Id_buku']." - ".$b['Judul']." (Sisa: ".$b['stok_asli'].")</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12" id="box-info-buku-agt" style="display:none;">
                            <div class="callout callout-info bg-light">
                                <div class="row">
                                    <div class="col-4">
                                        <img id="tampil_foto_buku_agt" src="" class="img-thumbnail" style="width: 100%; height: 180px; object-fit: cover;">
                                        <input type="hidden" name="Foto_buku" id="val_foto_buku_db_agt">
                                    </div>
                                    <div class="col-8">
                                        <label class="small mb-0">Judul Buku:</label>
                                        <input type="text" name="Judul_buku" id="val_judul_buku_agt" class="form-control mb-2 bg-white" readonly>
                                        
                                        <label class="small mb-0">Lokasi Rak:</label>
                                        <input type="text" name="Lokasi_rak" id="val_rak_buku_agt" class="form-control mb-2 bg-white" readonly>
                                        
                                        <div class="col-6">
                                            <label class="small mb-0">Jumlah Pinjam:</label>
                                            <input type="number" name="Jumlah" id="input_jumlah_pinjam" value="1" min="1" max="1" class="form-control border-primary">
                                        </div>
                                        <small class="text-muted">*Siswa/Guru maksimal pinjam 1 buku per judul.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" name="ajukan" class="btn btn-primary" id="btn-ajukan-pinjam" disabled>
                        <i class="fas fa-paper-plane"></i> Ajukan Peminjaman
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- From modal data Pengembalian -->
<div class="modal fade" id="modalKonfirmasiKembali" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-info">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-white"><i class="fas fa-check-double mr-2"></i> Konfirmasi Pengembalian Buku</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="proses_Anggota/tambah/transaksi_pengembalian_proses.php" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <!-- Data Buku -->
                        <div class="col-md-5 border-right text-center">
                            <label class="text-muted">Buku yang Dikembalikan:</label>
                            <br>
                            <img id="m_foto_buku" src="" class="img-thumbnail mb-2 shadow-sm" style="height: 180px; object-fit: cover;">
                            <h5 id="m_judul_buku" class="font-weight-bold text-info"></h5>
                            <p class="badge badge-secondary" id="m_lokasi_rak"></p>
                            
                            <hr>
                            <div class="form-group">
                                <label>Denda Keterlambatan</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-danger text-white">Rp</span>
                                    </div>
                                    <input type="text" id="m_denda" name="Denda" class="form-control font-weight-bold text-danger" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Data Transaksi -->
                        <div class="col-md-7">
                            <input type="hidden" name="Id_peminjaman" id="m_id_peminjaman">
                            <input type="hidden" name="Id_buku" id="m_id_buku">
                            
                            <div class="form-group">
                                <label>Tanggal Pengembalian</label>
                                <input type="text" class="form-control" value="<?php echo date('d-m-Y'); ?>" readonly>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Jumlah Pinjam</label>
                                        <input type="text" id="m_jml_awal" class="form-control text-center bg-light" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Sisa Belum Kembali</label>
                                        <input type="text" id="m_sisa" class="form-control text-center bg-light font-weight-bold text-primary" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="text-success">Jumlah yang Akan Dikembalikan Sekarang</label>
                                <input type="number" name="Jml_kembali" id="m_jml_kembali" class="form-control form-control-lg text-center border-success font-weight-bold" required>
                                <small class="text-muted">*Pastikan jumlah fisik buku sesuai.</small>
                            </div>

                            <div class="form-group">
                                <label>Status Pengembalian Otomatis</label>
                                <input type="text" name="Status" id="m_status" class="form-control font-weight-bold bg-light" readonly>
                                <small class="text-muted" id="m_status_info"></small>
                            </div>

                            <div class="alert alert-warning p-2">
                                <small><i class="fas fa-info-circle"></i> Setelah menekan tombol proses, status akan menjadi <b>"Menunggu Izin Admin"</b>. Silahkan serahkan buku ke petugas.</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" name="proses_kembali" class="btn btn-info">
                        <i class="fas fa-paper-plane mr-1"></i> Proses Pengembalian
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
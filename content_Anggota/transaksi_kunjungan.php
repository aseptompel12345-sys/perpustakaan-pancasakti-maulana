<?php
    if (!isset($db)) {
        include "config/koneksi.php"; 
    }
    
    // Ambil ID Anggota dari Session
    $id_agt = $_SESSION['Id_anggota'];

    // Query Utama Tabel Riwayat - KHUSUS milik anggota yang login
    $query_tabel = mysqli_query($db, "SELECT * FROM kunjungan WHERE Id_anggota = '$id_agt' ORDER BY Id_kunjungan DESC");
?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-walking text-primary"></i> Riwayat Kunjungan Saya</h1>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Data Kehadiran di Perpustakaan</h3>
                        <div class="card-tools">
                             <button type= "button" class="btn btn-success" data-toggle="modal" data-target="#modalTambahKunjungan">
                                <i class="fas fa-plus"></i> Tambah Kunjungan
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="tabelKunjungan" class="table table-bordered table-striped table-hover table-custom">
                            <thead>
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>ID Kunjungan</th>
                                    <th>Foto</th> 
                                    <th>Tanggal & Jam</th> 
                                    <th>Nama Pengunjung</th> 
                                    <th>Jenis</th> 
                                    <th>Keperluan</th>
                                    <th>Izin Admin</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $no = 1;
                                    while($data = mysqli_fetch_array($query_tabel)) { 
                                        $ja = $data['Jenis_anggota'];
                                        $warna_badge = ($ja == 'Siswa') ? 'info' : (($ja == 'Guru') ? 'warning' : 'success');
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++; ?></td>
                                    <td class="text-center"><small class="badge badge-secondary"><?php echo $data['Id_kunjungan']; ?></small></td>
                                    <td class="text-center">
                                        <?php 
                                            $path_foto = $data['Foto_kunjungan']; 
                                            $sumber_gambar = (!empty($path_foto)) ? $path_foto : "dist/img/avatar5.png";
                                        ?>
                                        <img src="<?php echo $sumber_gambar; ?>" 
                                            class="img-circle border shadow-sm" style="width: 40px; height: 40px;"
                                            onerror="this.onerror=null; this.src='dist/img/avatar5.png';">
                                    </td>
                                   <td class="text-center">
                                        <span class="font-weight-bold"><?php echo $data['Tgl_kunjungan']; ?></span><br>
                                        <small class="text-muted"><?php echo $data['Jam_kunjungan']; ?></small>
                                    </td>
                                    <td class="text-center"><?php echo $data['Nama_pengunjung']; ?></td>
                                    <td class="text-center">
                                        <span class="badge badge-<?php echo $warna_badge; ?>"><?php echo $ja; ?></span>
                                    </td>
                                    <td><?php echo $data['Keperluan']; ?></td>
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

<!-- Form modal tambah -->
<div class="modal fade" id="modalTambahKunjungan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-walking"></i> Catat Kehadiran Hari Ini</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="proses_Anggota/tambah/transaksi_kunjungan_proses.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="Id_anggota" value="<?php echo $_SESSION['Id_anggota']; ?>">
                    <input type="hidden" name="Tgl_kunjungan" value="<?php echo date('Y-m-d'); ?>">
                    <input type="hidden" name="Jam_kunjungan" value="<?php echo date('H:i:s'); ?>">
                    
                    <div id="data-otomatis-anggota" style="display:none;">
                        <input type="hidden" name="Nama_pengunjung" id="auto_nama">
                        <input type="hidden" name="Jenis_anggota" id="auto_jenis">
                        <input type="hidden" name="Detail_identitas" id="auto_detail">
                        <input type="hidden" name="Foto_kunjungan" id="auto_foto">
                    </div>

                    <div class="text-center mb-3">
                        <p>Halo <b><?php echo $_SESSION['nama_user']; ?></b>, silakan pilih keperluan kunjungan Anda hari ini.</p>
                        <hr>
                    </div>

                    <div class="form-group">
                        <label>Apa keperluan Anda hari ini?</label>
                        <select class="form-control form-control-lg border-success" name="keperluan" required>
                            <option value="">-- Pilih Keperluan --</option>
                            <option value="Membaca">Membaca</option>
                            <option value="Berdiskusi">Berdiskusi</option>
                            <option value="Mengerjakan Tugas">Mengerjakan Tugas</option>
                            <option value="Meminjam Buku">Meminjam Buku</option>
                            <option value="Mengembalikan Buku">Mengembalikan Buku</option>
                            <option value="Lain-lain">Lain-lain</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" name="simpan_kunjungan" class="btn btn-success">
                        <i class="fas fa-check-circle"></i> Simpan Kehadiran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
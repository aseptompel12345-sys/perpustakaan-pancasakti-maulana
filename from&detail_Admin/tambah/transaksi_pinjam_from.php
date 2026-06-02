<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-plus-circle text-success"></i> Input Transaksi Peminjaman</h1>
            </div>
        </div>
    </div>                  
</section>

<section class="content">
    <div class="container-fluid">  
        <form action="proses_Admin/tambah/transaksi_pinjam_proses.php" method="POST">
            <div class="row">
                
                <div class="col-md-6">
                    <div class="card card-success card-outline">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold">1. Data Peminjam</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Waktu Pinjam & Jatuh Tempo</label>
                                <div class="row">
                                    <div class="col-6">
                                        <!-- Tanggal Pinjam Hari Ini -->
                                        <input type="text" class="form-control text-center" name="Tgl_pinjam" id="tgl_pinjam_id" value="<?php echo date('Y-m-d'); ?>" readonly>
                                    </div>
                                    <div class="col-6">
                                        <!-- Ditambahkan id="tgl_jatuh_tempo_id" & value bawaan awal di-set default 7 hari -->
                                        <input type="text" class="form-control text-center text-danger font-weight-bold" name="Tgl_jatuh_tempo" id="tgl_jatuh_tempo_id" value="<?php echo date('Y-m-d', strtotime('+7 days')); ?>" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Pilih ID Anggota (Peminjam)</label>
                                <select class="form-control select2 select2-success" name="Id_anggota" id="Id_anggota_pilih" required style="width: 100%;">
                                    <option value=""></option> 
                                    <?php
                                        include "../config/koneksi.php";
                                        $sql_agt = "SELECT a.Id_anggota, a.Jenis_anggota, s.Nama_siswa, g.Nama_guru, k.Nama_kelas
                                                    FROM anggota a
                                                    LEFT JOIN anggota_siswa s ON a.Id_anggota = s.Id_anggota
                                                    LEFT JOIN anggota_guru g ON a.Id_anggota = g.Id_anggota
                                                    LEFT JOIN anggota_kelas k ON a.Id_anggota = k.Id_anggota
                                                    ORDER BY a.Id_anggota ASC";
                                        $agt = mysqli_query($db, $sql_agt);
                                        while($r = mysqli_fetch_array($agt)){
                                            $nama_tampil = $r['Nama_siswa'] ?? $r['Nama_guru'] ?? $r['Nama_kelas'];
                                            echo "<option value='".$r['Id_anggota']."' data-tipe='".$r['Jenis_anggota']."'>
                                                    ".$r['Id_anggota']." - ".$nama_tampil." [".$r['Jenis_anggota']."]
                                                </option>";
                                        }
                                    ?>
                                </select>
                            </div>

                            <div id="box-info-pintar" style="display:none;" class="callout callout-success bg-light shadow-sm">
                                <input type="hidden" name="Jenis_anggota" id="val_jenis">
                                <input type="hidden" name="Foto_peminjam" id="val_foto_db"> 
                                <div class="row">
                                    <div class="col-4 text-center">
                                        <label>Foto Peminjam</label><br>
                                        <img id="tampil_foto" src="" class="img-thumbnail" style="width: 100%; height: 130px; object-fit: cover; border-radius: 8px;">
                                    </div>
                                    <div class="col-8">
                                        <label class="mb-0 small">Nama Peminjam:</label>
                                        <input type="text" name="Nama_peminjam" id="val_nama" class="form-control form-control-sm bg-white mb-2" readonly>
                                        <label class="mb-0 small">Detail Identitas:</label>
                                        <textarea name="Detail_identitas" id="val_detail" class="form-control form-control-sm bg-white" readonly rows="3"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div id="identitas-kosong" class="text-center p-3 border" style="border-style: dashed !important; border-radius: 10px; color: #bbb;">
                                <small>Identitas peminjam akan muncul di sini</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold">2. Data Buku</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Pilih ID Buku</label>
                                <select class="form-control select2" name="Id_buku" id="Id_buku_pilih" required style="width: 100%;">
                                    <option value=""></option>
                                    <?php
                                        $sql_buku_ready = "SELECT b.Id_buku, b.Judul, 
                                                        (b.Stok_awal_buku - IFNULL((SELECT SUM(p.Jumlah) FROM peminjaman p WHERE p.Id_buku = b.Id_buku AND p.Status = 'Dipinjam'), 0)) AS stok_asli 
                                                        FROM buku b 
                                                        HAVING stok_asli > 0 
                                                        ORDER BY b.Id_buku ASC";
                                        
                                        $res_buku = mysqli_query($db, $sql_buku_ready);
                                        while($b = mysqli_fetch_array($res_buku)){
                                            echo "<option value='".$b['Id_buku']."'>".$b['Id_buku']." - ".$b['Judul']." (Sisa: ".$b['stok_asli'].")</option>";
                                        }
                                    ?>
                                </select>
                            </div>

                            <div id="box-info-buku" style="display:none;" class="callout callout-info bg-light shadow-sm">
                                <input type="hidden" name="Foto_buku" id="val_foto_buku_db">
                                <div class="row">
                                    <div class="col-4 text-center">
                                        <label>Sampul Buku</label><br>
                                        <img id="tampil_foto_buku" src="" class="img-thumbnail" style="width: 100%; height: 150px; object-fit: cover;">
                                    </div>
                                    <div class="col-8">
                                        <label class="mb-0 small">Judul Buku:</label>
                                        <input type="text" name="Judul_buku" id="val_judul_buku" class="form-control form-control-sm bg-white mb-2" readonly>

                                        <label class="mb-0 small">Lokasi Rak:</label>
                                        <input type="text" name="Lokasi_rak" id="val_rak_buku" class="form-control form-control-sm bg-white" readonly>

                                        <div class="col-6">
                                            <label class="mb-0 small">Jumlah:</label>
                                            <input type="number" name="Jumlah" id="val_jumlah_buku" value="1" min="1" class="form-control form-control-sm border-primary">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
            <div class="card-footer">
                <button type="submit" name="simpan" class="btn btn-success" id="btn-save" disabled>
                    <i class="fas fa-save"></i> Simpan Transaksi
                </button>
                <a href="index_Admin.php?page=transaksi_pinjam" class="btn btn-danger float-right">Kembali</a>
            </div>
        </form>
    </div>
</section>

<!-- ================================================================ -->
<!-- JAVASCRIPT LOGIKA DINAMIS JATUH TEMPO (TARUH DI BAGIAN PALING BAWAH FORM) -->
<!-- ================================================================ -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Jalankan logika ini setiap kali Admin memilih / mengubah Anggota Peminjam
    $('#Id_anggota_pilih').on('change', function() {
        // Ambil tipe data dari atribut data-tipe pada option yang dipilih (Siswa / Guru / Kelas)
        var tipeAnggota = $(this).find(':selected').data('tipe');
        var tglPinjam = $('#tgl_pinjam_id').val(); // Mengambil nilai YYYY-MM-DD hari ini
        
        if (tipeAnggota === "Kelas") {
            // LOGIKA GURU: Jika rombongan Kelas, tanggal jatuh tempo diubah ke HARI ITU JUGA
            $('#tgl_jatuh_tempo_id').val(tglPinjam);
        } else {
            // Jika Siswa atau Guru, hitung otomatis +7 hari kedepan memakai JavaScript Date
            var date = new Date(tglPinjam);
            date.setDate(date.getDate() + 7);
            
            // Format kembali hasil penambahan hari ke YYYY-MM-DD
            var yyyy = date.getFullYear();
            var mm = String(date.getMonth() + 1).padStart(2, '0'); // Bulan dimulai dari 0
            var dd = String(date.getDate()).padStart(2, '0');
            
            var tglJatuhTempo7Hari = yyyy + '-' + mm + '-' + dd;
            $('#tgl_jatuh_tempo_id').val(tglJatuhTempo7Hari);
        }
    });
});
</script>
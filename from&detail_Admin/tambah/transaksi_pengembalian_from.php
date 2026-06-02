<?php 
    $id_otomatis = isset($_GET['id_p']) ? $_GET['id_p'] : '';
?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-plus-circle text-success"></i> Input Transaksi Pengembalian</h1>
            </div>
        </div>
    </div>                  
</section>

<section class="content">
    <div class="container-fluid">  
        <div class="card card-success card-outline">
            <form action="proses_Admin/tambah/transaksi_pengembalian_proses.php" method="POST">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tanggal Kembali (Hari Ini)</label>
                                <input type="text" class="form-control text-center font-weight-bold" name="Tgl_kembali" value="<?php echo date('Y-m-d'); ?>" readonly>
                            </div>

                            <div class="form-group">
                                <label>Pilih ID Peminjaman</label>
                                <select class="form-control select2 select2-info" name="Id_peminjaman" id="Id_peminjaman_pilih" required style="width: 100%;">
                                    <option value=""></option> 
                                    <?php
                                        include "../config/koneksi.php";
                                        // Menampilkan transaksi yang belum selesai/lunas
                                        $sql_pinjam = "SELECT Id_peminjaman, Nama_peminjam, Judul_buku 
                                                       FROM peminjaman 
                                                       WHERE Status != 'Selesai' 
                                                       ORDER BY Id_peminjaman DESC";
                                        $query_pinjam = mysqli_query($db, $sql_pinjam);
                                        while($rp = mysqli_fetch_array($query_pinjam)){
                                            echo "<option value='".$rp['Id_peminjaman']."'>
                                                    ".$rp['Id_peminjaman']." - ".$rp['Nama_peminjam']." [".$rp['Judul_buku']."]
                                                  </option>";
                                        }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label id="label_denda">Denda Keterlambatan</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-danger text-white">Rp</span>
                                    </div>
                                    <input type="number" name="Denda" id="denda" class="form-control font-weight-bold text-danger" value="0" readonly>
                                </div>
                                <small class="text-muted">*Denda dihitung otomatis berdasarkan tgl jatuh tempo.</small>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div id="box-info-pinjaman" style="display:none;">
                                <div class="callout callout-info bg-light shadow-sm">
                                    <h5><i class="fas fa-search text-info"></i> Detail Transaksi Ditemukan</h5>
                                    <hr>
                                    
                                    <input type="hidden" name="Id_anggota" id="id_anggota">
                                    <input type="hidden" name="Jenis_anggota" id="jenis_anggota">
                                    <input type="hidden" name="Foto_peminjam" id="val_foto_peminjam">
                                    <input type="hidden" name="Id_buku" id="id_buku">
                                    <input type="hidden" name="Foto_buku" id="val_foto_buku">
                                    <input type="hidden" name="Lokasi_rak" id="val_lokasi_rak">

                                    <div class="row">
                                        <div class="col-sm-6 border-right">
                                            <label class="text-info"><i class="fas fa-user"></i> Data Peminjam</label>
                                            <div class="d-flex align-items-start mb-2">
                                                <img id="tampil_foto_peminjam" src="" class="img-thumbnail mr-2" style="width: 80px; height: 80px; object-fit: cover;">
                                                <div>
                                                    <input type="text" name="Nama_peminjam" id="nama_peminjam" class="form-control form-control-sm bg-white mb-1" readonly>
                                                    <textarea name="Detail_identitas" id="identitas_peminjam" class="form-control form-control-sm bg-white" readonly rows="2"></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <label class="text-info"><i class="fas fa-book"></i> Data Buku</label>
                                            <div class="d-flex align-items-start">
                                                <img id="tampil_foto_buku" src="" class="img-thumbnail mr-2" style="width: 60px; height: 80px; object-fit: cover;">
                                                <div>
                                                    <input type="text" name="Judul_buku" id="judul_buku" class="form-control form-control-sm bg-white mb-1" readonly>
                                                    <input type="text" name="Lokasi_rak" id="lokasi_rak" class="form-control form-control-sm bg-white font-weight-bold text-success" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label>Jml Pinjam Awal</label>
                                            <input type="number" id="jml_pinjam_awal" class="form-control text-center bg-light" readonly>
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Sisa Belum Kembali</label>
                                            <input type="number" id="sisa_buku" class="form-control text-center bg-light font-weight-bold text-primary" readonly>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="text-danger">Jumlah Dikembalikan</label>
                                            <input type="number" name="Jml_kembali" id="jml_kembali" class="form-control text-center border-danger font-weight-bold" required>
                                        </div>
                                    </div>
                                    <div class="form-group mt-3">
                                        <label>Status Pengembalian</label>
                                        <select name="Status" id="status_kembali" class="form-control font-weight-bold">
                                            <option value="Selesai">Selesai (Lunas)</option>
                                            <option value="Sebagian">Sebagian (Cicil)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="identitas-kosong" class="text-center p-5 border" style="border-style: dashed !important; border-radius: 10px; color: #bbb;">
                                <i class="fas fa-exchange-alt fa-5x mb-3"></i>
                                <p>Silakan pilih <b>ID Peminjaman</b> untuk memproses pengembalian buku.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    <button type="submit" name="simpan" class="btn btn-success" id="btn-save-kembali" disabled>
                        <i class="fas fa-check-circle"></i> Proses Pengembalian
                    </button>
                    <a href="index_Admin.php?page=transaksi_pengembalian" class="btn btn-danger float-right">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var initHalaman = function() {
        if (typeof jQuery === 'undefined') {
            setTimeout(initHalaman, 50);
            return;
        }

        // 1. Inisialisasi Select2
        var selectPeminjaman = $('#Id_peminjaman_pilih').select2({
            theme: 'bootstrap4',
            placeholder: "Cari ID Peminjaman..."
        });

        // --- MULAI KODE PERBAIKAN ---
        // 2. Logika Otomatis: Cek apakah ada ID dari URL
        var idDariUrl = "<?php echo $id_otomatis; ?>";
        if(idDariUrl !== "") {
            // Set nilai di Select2 secara otomatis
            selectPeminjaman.val(idDariUrl).trigger('change');
            
            // Panggil fungsi AJAX secara otomatis
            if (typeof ambilDataPinjaman === "function") {
                ambilDataPinjaman(idDariUrl);
                $('#btn-save-kembali').prop('disabled', false);
            }
        }
        // --- SELESAI KODE PERBAIKAN ---

        // 3. Event normal saat pilih ID secara manual (tetap ada)
        $('#Id_peminjaman_pilih').on('change', function() {
            var id = $(this).val();
            if(id != "" && id != null){
                if (typeof ambilDataPinjaman === "function") {
                    ambilDataPinjaman(id);
                    $('#btn-save-kembali').prop('disabled', false);
                }
            } else {
                $('#box-info-pinjaman').hide();
                $('#identitas-kosong').show();
                $('#btn-save-kembali').prop('disabled', true);
            }
        });

        // 4. Logika perhitungan status lunas/sebagian
        $(document).on('input', '#jml_kembali', function() {
            var sisa = parseInt($('#sisa_buku').val()) || 0;
            var kembali = parseInt($(this).val()) || 0;
            
            if (kembali > sisa) {
                alert('Jumlah kembali tidak boleh melebihi sisa buku!');
                $(this).val(sisa);
                kembali = sisa;
            }

            if(kembali < sisa) {
                $('#status_kembali').val('Sebagian');
            } else {
                $('#status_kembali').val('Selesai');
            }
        });
    };
    initHalaman();
});
</script>
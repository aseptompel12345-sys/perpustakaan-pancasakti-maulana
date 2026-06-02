<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-plus-circle text-success"></i> Input Transaksi Kunjungan</h1>
            </div>
        </div>
    </div>                  
</section>

<section class="content">
    <div class="container-fluid">  
        <div class="card card-success card-outline">
            <form action="proses_Admin/tambah/transaksi_kunjungan_proses.php" method="POST">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Tanggal & Jam Berkunjung</label>
                                <div class="row">
                                    <div class="col-6">
                                        <input type="text" class="form-control text-center" name="Tgl_kunjungan" value="<?php echo date('Y-m-d'); ?>" readonly>
                                    </div>
                                    <div class="col-6">
                                        <input type="text" class="form-control text-center" name="Jam_kunjungan" value="<?php echo date('H:i:s'); ?>" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="keperluan">Keperluan</label>
                                <select class="form-control" name="keperluan" id="keperluan" required>
                                    <option value="Membaca">Membaca</option>
                                    <option value="Berdiskusi">Berdiskusi</option>
                                    <option value="Mengerjakan Tugas">Mengerjakan Tugas</option>
                                    <option value="Meminjam Buku">Meminjam Buku</option>
                                    <option value="Mengembalikan Buku">Mengembalikan Buku</option>
                                    <option value="Lain-lain">Lain-lain</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Pilih ID Anggota</label>
                                <select class="form-control select2 select2-success" name="Id_anggota" id="Id_anggota_pilih" required style="width: 100%;">
                                    <option value=""></option> 
                                    <?php
                                        include "../config/koneksi.php";
                                        $sql_agt = "SELECT a.Id_anggota, a.Jenis_anggota, 
                                                        s.Nama_siswa, g.Nama_guru, k.Nama_kelas
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
                        </div>

                        <div class="col-md-7">
                            <div id="box-info-pintar" style="display:none;">
                                <div class="callout callout-success bg-light shadow-sm">
                                    <h5><i class="fas fa-user-check text-success"></i> Data Terdeteksi</h5>
                                    <hr>
                                    <input type="hidden" name="Jenis_anggota" id="val_jenis">
                                    <input type="hidden" name="Foto_kunjungan" id="val_foto_db">
                                    
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <label>Nama Pengunjung</label>
                                                <input type="text" name="Nama_pengunjung" id="val_nama" class="form-control bg-white" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label>Detail Identitas</label>
                                                <textarea name="Detail_identitas" id="val_detail" class="form-control bg-white" readonly rows="3"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 text-center">
                                            <label>Foto Pengunjung</label><br>
                                            <img id="tampil_foto" src="" class="img-thumbnail shadow-sm" 
                                                style="width: 280px; height: 176px; object-fit: cover; object-position: center; display: block; margin: 0 auto; border-radius: 10px;">                                   
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="identitas-kosong" class="text-center p-5 border" style="border-style: dashed !important; border-radius: 10px; color: #bbb;">
                                    <i class="fas fa-id-badge fa-5x mb-3"></i>
                                    <p>Silakan pilih ID Anggota untuk memvalidasi identitas pengunjung.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" name="simpan" class="btn btn-success" id="btn-save" disabled>
                            <i class="fas fa-save"></i> Simpan Transaksi
                        </button>
                        <a href="index_Admin.php?page=transaksi_kunjungan" class="btn btn-danger float-right">Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
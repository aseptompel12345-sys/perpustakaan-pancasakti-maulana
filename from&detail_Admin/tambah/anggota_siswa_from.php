<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="card-title"><i class="ion ion-person-add text-success"></i> Input Data Anggota Siswa</h1>
            </div>
        </div>
    </div>                  
</section>

<section class="content">
    <div class="container-fluid">  
        <div class="row">
            <div class="col-md-12">
                <div class="card card-success card-outline">
                    
                    <form action="proses_Admin/tambah/anggota_siswa_proses.php" method="POST" enctype="multipart/form-data">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="NISN">NISN</label>
                                        <input type="number" class="form-control" name="NISN" id="NISN" placeholder="Masukkan Nomor NISN" required>
                                    </div>
                                    <div class="form-group">                    
                                        <label for="Nama_siswa">Nama Siswa</label>
                                        <input type="text" class="form-control" name="Nama_siswa" id="Nama_siswa" placeholder="Masukkan Nama Lengkap" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Jenis Kelamin</label>
                                        <div class="btn-group-toggle d-flex" data-toggle="buttons">
                                            <label class="btn btn-outline-primary flex-fill border mr-1">
                                                <input type="radio" name="Jenis_kelamin" value="Laki-laki" required autocomplete="off">
                                                <i class="fas fa-mars"></i> Laki-Laki
                                            </label>
                                            <label class="btn btn-outline-danger flex-fill border">
                                                <input type="radio" name="Jenis_kelamin" value="Perempuan" autocomplete="off">
                                                <i class="fas fa-venus"></i> Perempuan
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Agama">Agama</label>
                                        <select class="form-control" name="Agama" id="Agama" required>
                                            <option value="Islam">Islam</option>
                                            <option value="Kristen">Kristen</option>
                                            <option value="Katolik">Katolik</option>
                                            <option value="Budha">Budha</option>
                                            <option value="Hindu">Hindu</option>
                                            <option value="Konghucu">Konghucu</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Kelas</label>
                                        <div class="btn-group-toggle d-flex" data-toggle="buttons">
                                            <label class="btn btn-outline-success flex-fill border mr-1">
                                                <input type="radio" name="Kelas" value="10" required autocomplete="off"> 10
                                            </label>
                                            <label class="btn btn-outline-success flex-fill border mr-1">
                                                <input type="radio" name="Kelas" value="11" autocomplete="off"> 11
                                            </label>
                                            <label class="btn btn-outline-success flex-fill border">
                                                <input type="radio" name="Kelas" value="12" autocomplete="off"> 12
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Jurusan">Jurusan</label>
                                        <input type="text" class="form-control" name="Jurusan" id="Jurusan" placeholder="Masukkan Nama Jurusan yang di Pilih" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="Alamat">Alamat Rumah</label>
                                        <input type="text" class="form-control" name="Alamat" id="Alamat" placeholder="Masukkan Alamat Rumah" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="No_tlp">Nomor HP</label>
                                        <input type="number" class="form-control" name="No_tlp" id="No_tlp" placeholder="Masukkan Nomor Handphone yang Aktif" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="Foto">Foto Siswa</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="Foto" id="Foto" accept="image/*" capture="environment">
                                                <label class="custom-file-label" for="Foto">Masukan Foto Wajah Sebagai Identitas</label>
                                            </div>
                                            <div class="input-group-append tombol-kamera-hp"> 
                                                <span class="input-group-text bg-primary text-white">
                                                    <i class="fas fa-camera"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <small class="text-muted">Klik ikon kamera untuk memotret via HP.</small>
                                    </div>

                                    <div class="mt-2 text-center">
                                        <img id="preview" src="#" alt="Pratinjau Foto" style="display:none; max-width: 200px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.2); margin: 0 auto;">
                                    </div> 
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" name="simpan" class="btn btn-success">
                                <i class="fas fa-save"></i> Lanjutkan Buat Akun
                            </button>
                            <a href="index_Admin.php?page=anggota_siswa" class="btn btn-danger float-right">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>  
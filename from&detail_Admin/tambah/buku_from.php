<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="card-title"><i class="fas fa-plus-circle text-success"></i> Input Data Buku</h1>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-success card-outline">
                    
                    <form action="proses_Admin/tambah/buku_proses.php" method="POST" enctype="multipart/form-data">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ISBN">ISBN</label>
                                        <input type="number" class="form-control" name="ISBN" id="ISBN" placeholder="Masukkan Nomor ISBN Buku" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="Judul">Nama Buku</label>
                                        <input type="text" class="form-control" name="Judul" id="Judul" placeholder="Masukkan Nama Buku" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="Pengarang">Pengarang</label>
                                        <input type="text" class="form-control" name="Pengarang" id="Pengarang" placeholder="Masukkan Nama Penulis Buku" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="Penerbit">Penerbit</label>
                                        <input type="text" class="form-control" name="Penerbit" id="Penerbit" placeholder="Masukkan Nama Penerbit Buku" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="Tahun_terbit">Tahun Terbit</label>
                                        <input type="number" class="form-control" name="Tahun_terbit" id="Tahun_terbit" placeholder="Masukkan ahun Terbit Buku"  required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Bidang_buku">Bidang Buku</label>
                                        <select class="form-control" name="Bidang_buku" id="Bidang_buku" required>
                                            <option value="Pendidikan">Pendidikan</option>
                                            <option value="Fiksi">Fiksi</option>
                                            <option value="Nonfiksi">Nonfiksi</option>
                                            <option value="Lain-lain">Lain-lain</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="Rak_buku">Lokasi Rak Buku</label>
                                        <select class="form-control" name="Rak_buku" id="Rak_buku" required>
                                            <option value="Mapel Umum">Mapel Umum</option>
                                            <option value="Mapel Produktif Teknik Pesawat Udara">Mapel Produktif Teknik Pesawat Udara</option>
                                            <option value="Mapel Produktif Otomotif">Mapel Produktif Otomotif</option>
                                            <option value="Mapel Produktif Akuntansi dan Lembaga Keuangan">Mapel Produktif Akuntansi dan Lembaga Keuangan</option>
                                            <option value="Mapel Produktif PPLG">Mapel Produktif PPLG</option>
                                            <option value="Mapel Produktif TKJ">Mapel Produktif TKJ</option>
                                            <option value="Mapel Produktif Teknik Logistik">Mapel Produktif Teknik Logistik</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="Stok_awal_buku">Stok Awal Buku</label>
                                        <input type="number" class="form-control" name="Stok_awal_buku" id="Stok_awal_buku" placeholder="Masukan Jumlah Awal Buku" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="Foto">Foto Sampul Buku</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="Foto" id="Foto" accept="image/*" capture="environment">
                                                <label class="custom-file-label" for="Foto">Masukan Foto Sampul Buku</label>
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
                                <i class="fas fa-save"></i> Simpan Data Buku
                            </button>
                            <a href="index_Admin.php?page=buku" class="btn btn-danger float-right">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
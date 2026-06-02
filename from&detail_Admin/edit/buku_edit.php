<?php
    // Ambil ID dari URL
    $id_buku = isset($_GET['id']) ? mysqli_real_escape_string($db, $_GET['id']) : '';

    if (empty($id_buku)) {
        echo "<script>alert('ID tidak ditemukan!'); window.location='index_Admin.php?page=buku';</script>";
        exit;
    }

    // Ambil data buku berdasarkan ID
    $query = mysqli_query($db, "SELECT * FROM buku WHERE Id_buku = '$id_buku'");

    // Cek apakah data benar-benar ada di database
    if (mysqli_num_rows($query) <1) {
        echo "<script>alert('Data tidak ditemukan di database'); window.location='index_Admin.php?page=buku';</script>";
        exit;
    }

    $data = mysqli_fetch_array($query);
?>

<section class="content-header">
    <div class="container-fluid">
        <h1>
            <i class="fas fa-edit text-warning"></i> 
            Form Edit Data Buku : 
            <span class="text-warning" style="font-family: 'Georgia', serif; font-style: italic; font-weight: bold;">
                <?php echo $data['Judul']; ?>
            </span>
        </h1>   
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card card-warning card-outline">
            <form action="proses_Admin/edit/buku_proses.php" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="hidden" name="Id_buku" value="<?php echo $data['Id_buku']; ?>">
                            
                            <div class="form-group">
                                <label>ISBN</label>
                                <input type="number" class="form-control" name="ISBN" value="<?php echo $data['ISBN']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Judul Buku</label>
                                <input type="text" class="form-control" name="Judul" value="<?php echo $data['Judul']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Pengarang</label>
                                <input type="text" class="form-control" name="Pengarang" value="<?php echo $data['Pengarang']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Penerbit</label>
                                <input type="text" class="form-control" name="Penerbit" value="<?php echo $data['Penerbit']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Tahun Terbit</label>
                                <input type="number" class="form-control" name="Tahun_terbit" value="<?php echo $data['Tahun_terbit']; ?>" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Bidang Buku</label>
                                <select class="form-control" name="Bidang_buku" required>
                                    <option value="Pendidikan" <?php echo ($data['Bidang_buku'] == 'Pendidikan') ? 'selected' : ''; ?>>Pendidikan</option>
                                    <option value="Fiksi" <?php echo ($data['Bidang_buku'] == 'Fiksi') ? 'selected' : ''; ?>>Fiksi</option>
                                    <option value="Nonfiksi" <?php echo ($data['Bidang_buku'] == 'Nonfiksi') ? 'selected' : ''; ?>>Nonfiksi</option>
                                    <option value="Lain-lain" <?php echo ($data['Bidang_buku'] == 'Lain-lain') ? 'selected' : ''; ?>>Lain-lain</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Lokasi Rak Buku</label>
                                <select class="form-control" name="Rak_buku" required>
                                    <option value="Mapel Umum" <?php echo ($data['Lokasi_rak'] == 'Mapel Umum') ? 'selected' : ''; ?>>Mapel Umum</option>
                                    <option value="Mapel Produktif Teknik Pesawat Udara" <?php echo ($data['Lokasi_rak'] == 'Mapel Produktif Teknik Pesawat Udara') ? 'selected' : ''; ?>>Mapel Produktif Teknik Pesawat Udara</option>
                                    <option value="Mapel Produktif Otomotif" <?php echo ($data['Lokasi_rak'] == 'Mapel Produktif Otomotif') ? 'selected' : ''; ?>>Mapel Produktif Otomotif</option>
                                    <option value="Mapel Produktif Akuntansi dan Lembaga Keuangan" <?php echo ($data['Lokasi_rak'] == 'Mapel Produktif Akuntansi dan Lembaga Keuangan') ? 'selected' : ''; ?>>Mapel Produktif Akuntansi dan Lembaga Keuangan</option>
                                    <option value="Mapel Produktif PPLG" <?php echo ($data['Lokasi_rak'] == 'PendidMapel Produktif PPLGikan') ? 'selected' : ''; ?>>Mapel Produktif PPLG</option>
                                    <option value="Mapel Produktif TKJ" <?php echo ($data['Lokasi_rak'] == 'PendidiMapel Produktif TKJkan') ? 'selected' : ''; ?>>Mapel Produktif TKJ</option>
                                    <option value="Mapel Produktif Teknik Logistik" <?php echo ($data['Lokasi_rak'] == 'Mapel Produktif Teknik Logistik') ? 'selected' : ''; ?>>Mapel Produktif Teknik Logistik</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Stok Awal Buku</label>
                                <input type="number" class="form-control" name="Stok_awal_buku" value="<?php echo $data['Stok_awal_buku']; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Foto Sampul Saat Ini</label><br>
                                <img src="FOTOS/foto_sampul_buku/<?php echo $data['Foto']; ?>" width="120" class="img-thumbnail mb-2">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="Foto" id="Foto">
                                    <label class="custom-file-label">Ganti foto (Kosongkan jika tidak diganti)</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" name="update" class="btn btn-warning">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="index_Admin.php?page=buku" class="btn btn-danger float-right">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</section>
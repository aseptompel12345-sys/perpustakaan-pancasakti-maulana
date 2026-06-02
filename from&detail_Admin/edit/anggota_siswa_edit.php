<?php
    // Ambil ID dari URL
    $id_siswa = isset($_GET['id']) ? mysqli_real_escape_string($db, $_GET['id']) : '';

    if (empty($id_siswa)) {
        echo "<script>alert('ID tidak ditemukan!'); window.location='index_Admin.php?page=anggota_siswa';</script>";
        exit;
    }

    // Ambil data anggota siswa berdasarkan ID
    $query = mysqli_query($db, "SELECT * FROM anggota_siswa WHERE Id_siswa = '$id_siswa'");

     // Cek apakah data benar-benar ada di database
    if (mysqli_num_rows($query) <1) {
        echo "<script>alert('Data tidak ditemukan di database'); window.location='index_Admin.php?page=anggota_siswa';</script>";
        exit;
    }

    $data = mysqli_fetch_array($query);
?>

<section class="content-header">
    <div class="container-fluid">
        <h1>
            <i class="fas fa-edit text-warning"></i> 
            Form Edit Data Anggota Siswa : 
            <span class="text-warning" style="font-family: 'Georgia', serif; font-style: italic; font-weight: bold;">
                <?php echo $data['Nama_siswa']; ?>
            </span>
        </h1>   
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card card-warning card-outline">
            <form action="proses_Admin/edit/anggota_siswa_proses.php" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="hidden" name="Id_siswa" value="<?php echo $data['Id_siswa']; ?>">
                            
                            <div class="form-group">
                                <label>NISN</label>
                                <input type="number" class="form-control" name="NISN" value="<?php echo $data['NISN']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Nama Siswa</label>
                                <input type="text" class="form-control" name="Nama_siswa" value="<?php echo $data['Nama_siswa']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Jenis Kelamin</label>
                                <div class="btn-group-toggle d-flex" data-toggle="buttons">
                                    <label class="btn btn-outline-primary flex-fill border mr-1 <?php echo ($data['Jenis_kelamin'] == 'Laki-laki') ? 'active' : ''; ?>">
                                        <input type="radio" name="Jenis_kelamin" value="Laki-laki" <?php echo ($data['Jenis_kelamin'] == 'Laki-laki') ? 'checked' : ''; ?> required autocomplete="off">
                                         <i class="fas fa-mars"></i> Laki-Laki
                                    </label>
                                    <label class="btn btn-outline-danger flex-fill border <?php echo ($data['Jenis_kelamin'] == 'Perempuan') ? 'active' : ''; ?>">
                                        <input type="radio" name="Jenis_kelamin" value="Perempuan" <?php echo ($data['Jenis_kelamin'] == 'Perempuan') ? 'checked' : ''; ?> autocomplete="off">
                                        <i class="fas fa-venus"></i> Perempuan
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Agama</label>
                                <select class="form-control" name="Agama" required>
                                    <option value="Islam" <?php echo ($data['Agama'] == 'Islam') ? 'selected' : ''; ?>>Islam</option>
                                    <option value="Kristen" <?php echo ($data['Agama'] == 'Kristen') ? 'selected' : ''; ?>>Kristen</option>
                                    <option value="Katolik" <?php echo ($data['Agama'] == 'Katolik') ? 'selected' : ''; ?>>Katolik</option>
                                    <option value="Budha" <?php echo ($data['Agama'] == 'Budha') ? 'selected' : ''; ?>>Budha</option>
                                    <option value="Hindu" <?php echo ($data['Agama'] == 'Hindu') ? 'selected' : ''; ?>>Hindu</option>
                                    <option value="Konghucu" <?php echo ($data['Agama'] == 'Konghucu') ? 'selected' : ''; ?>>Konghucu</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Kelas</label>
                                <div class="btn-group-toggle d-flex" data-toggle="buttons">
                                    <label class="btn btn-outline-success flex-fill border mr-1 <?php echo ($data['Kelas'] == '10') ? 'active' : ''; ?>">
                                        <input type="radio" name="Kelas" value="10" <?php echo ($data['Kelas'] == '10') ? 'checked' : ''; ?> required autocomplete="off"> 10
                                    </label>
                                    <label class="btn btn-outline-success flex-fill border mr-1 <?php echo ($data['Kelas'] == '11') ? 'active' : ''; ?>">
                                        <input type="radio" name="Kelas" value="11" <?php echo ($data['Kelas'] == '11') ? 'checked' : ''; ?> autocomplete="off"> 11
                                    </label>
                                    <label class="btn btn-outline-success flex-fill border <?php echo ($data['Kelas'] == '12') ? 'active' : ''; ?>">
                                        <input type="radio" name="Kelas" value="12" <?php echo ($data['Kelas'] == '12') ? 'checked' : ''; ?> autocomplete="off"> 12
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jurusan</label>
                                <input type="text" class="form-control" name="Jurusan" value="<?php echo $data['Jurusan']; ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Alamat Rumah</label>
                                <input type="text" class="form-control" name="Alamat" value="<?php echo $data['Alamat']; ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Nomer HP</label>
                                <input type="number" class="form-control" name="No_tlp" value="<?php echo $data['No_tlp']; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Foto Siswa Saat Ini</label><br>
                                <img src="FOTOS/foto_siswa/<?php echo $data['Foto']; ?>" width="120" class="img-thumbnail mb-2">
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
                    <a href="index_Admin.php?page=anggota_siswa" class="btn btn-danger float-right">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</section>
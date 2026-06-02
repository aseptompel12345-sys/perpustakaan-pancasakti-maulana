<?php
    // Ambil ID dari URL
    $id_guru = isset($_GET['id']) ? mysqli_real_escape_string($db, $_GET['id']) : '';

    if (empty($id_guru)) {
        echo "<script>alert('ID tidak ditemukan!'); window.location='index_Admin.php?page=anggota_guru';</script>";
        exit;
    }

    // Ambil data anggota guru berdasarkan ID
    $query = mysqli_query($db, "SELECT * FROM anggota_guru WHERE Id_guru = '$id_guru'");

    // Cek apakah data benar-benar ada di database
    if (mysqli_num_rows($query) <1) {
        echo "<script>alert('Data tidak ditemukan di database'); window.location='index_Admin.php?page=anggota_guru';</script>";
        exit;
    }

    $data = mysqli_fetch_array($query);
?>

<section class="content-header">
    <div class="container-fluid">
        <h1>
            <i class="fas fa-edit text-warning"></i> 
            Form Edit Data Anggota Guru : 
            <span class="text-warning" style="font-family: 'Georgia', serif; font-style: italic; font-weight: bold;">
                <?php echo $data['Nama_guru']; ?>
            </span>
        </h1>   
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card card-warning card-outline">
            <form action="proses_Admin/edit/anggota_guru_proses.php" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="hidden" name="Id_guru" value="<?php echo $data['Id_guru']; ?>">
                            
                            <div class="form-group">
                                <label>NIP</label>
                                <input type="number" class="form-control" name="NIP" value="<?php echo $data['NIP']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Nama Guru</label>
                                <input type="text" class="form-control" name="Nama_guru" value="<?php echo $data['Nama_guru']; ?>" required>
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
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Alamat Rumah</label>
                                <input type="text" class="form-control" name="Alamat" value="<?php echo $data['Alamat']; ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Nomer HP</label>
                                <input type="number" class="form-control" name="No_tlp" value="<?php echo $data['No_tlp']; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Foto Guru Saat Ini</label><br>
                                <img src="FOTOS/foto_guru/<?php echo $data['Foto']; ?>" width="120" class="img-thumbnail mb-2">
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
                    <a href="index_Admin.php?page=anggota_guru" class="btn btn-danger float-right">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</section>
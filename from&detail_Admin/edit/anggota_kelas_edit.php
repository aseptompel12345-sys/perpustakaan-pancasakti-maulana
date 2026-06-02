<?php
    // Ambil ID dari URL
    $id_kelas = isset($_GET['id']) ? mysqli_real_escape_string($db, $_GET['id']) : '';

    if (empty($id_kelas)) {
        echo "<script>alert('ID tidak ditemukan!'); window.location='index_Admin.php?page=anggota_kelas';</script>";
        exit;
    }

    // Ambil data anggota kelas berdasarkan ID
    $query = mysqli_query($db, "SELECT * FROM anggota_kelas WHERE Id_kelas = '$id_kelas'");

    // Cek apakah data benar-benar ada di database
    if (mysqli_num_rows($query) <1) {
        echo "<script>alert('Data tidak ditemukan di database'); window.location='index_Admin.php?page=anggota_kelas';</script>";
        exit;
    }

    $data = mysqli_fetch_array($query);
?>

<section class="content-header">
    <div class="container-fluid">
        <h1>
            <i class="fas fa-edit text-warning"></i> 
            Form Edit Data Anggota kelas : 
            <span class="text-warning" style="font-family: 'Georgia', serif; font-style: italic; font-weight: bold;">
                <?php echo $data['Nama_kelas']; ?>
            </span>
        </h1>   
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card card-warning card-outline">
            <form action="proses_Admin/edit/anggota_kelas_proses.php" method="POST">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="hidden" name="Id_kelas" value="<?php echo $data['Id_kelas']; ?>">
                            
                            <div class="form-group">
                                <label>Nama Kelas</label>
                                <input type="text" class="form-control" name="Nama_kelas" value="<?php echo $data['Nama_kelas']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Nama Wali kelas</label>
                                <input type="text" class="form-control" name="Wali_kelas" value="<?php echo $data['Wali_kelas']; ?>" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jumlah Siswa</label>
                                <input type="number" class="form-control" name="Jumlah_siswa" value="<?php echo $data['Jumlah_siswa']; ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Penanggung Jawab (siswa)</label>
                                <input type="text" class="form-control" name="Penanggung_jawab" value="<?php echo $data['Penanggung_jawab']; ?>" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" name="update" class="btn btn-warning">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="index_Admin.php?page=anggota_kelas" class="btn btn-danger float-right">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</section>
<?php
// Mengambil ID User dari Sesi Login Admin
$id_user_login = $_SESSION['id_user'];

// Query join tabel admin dan users untuk menarik data lengkap secara spesifik
$query_adm = mysqli_query($db, "SELECT a.*, u.Username FROM admin a JOIN users u ON a.Id_user = u.Id_user WHERE a.Id_user = '$id_user_login'");
$data_adm = mysqli_fetch_assoc($query_adm);
?>

<style>
    .wadah-foto {
        position: relative;
        width: 140px;
        height: 140px;
        margin: 0 auto;
    }
    .foto-bundar {
        width: 140px;
        height: 140px;
        object-fit: cover;
        border: 4px solid #3c8dbc;
    }
    .tombol-kamera {
        position: absolute;
        bottom: 0;
        right: 5px;
        background: #3c8dbc;
        color: white;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: 3px solid #fff;
        transition: transform 0.2s;
    }
    .tombol-kamera:hover {
        transform: scale(1.1);
        background: #22609c;
        color: #fff;
    }
</style>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-user-cog mr-2 text-info"></i> Pengaturan Profil & Akun</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        
        <form action="pengaturan_admin_proses.php" method="POST" enctype="multipart/form-data">
            <div class="row">
                
                <div class="col-md-6">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold text-primary">
                                <i class="fas fa-id-card mr-2"></i> Menu Profil
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group text-center mb-4">
                                <div class="wadah-foto">
                                    <img src="FOTOS/foto_admin/<?php echo !empty($data_adm['Foto']) ? $data_adm['Foto'] : 'avatar5.png'; ?>" 
                                        id="preview" class="img-circle foto-bundar elevation-2">
                                    
                                    <label for="Foto" class="tombol-kamera shadow">
                                        <i class="fas fa-camera"></i>
                                    </label>
                                </div>
                                <input type="file" name="Foto" id="Foto" style="display: none;" accept="image/*">
                            </div>

                            <hr style="border-top: 1px solid #e9ecef;" class="mb-4">

                            <div class="form-group row align-items-center">
                                <label class="col-sm-4 col-form-label">Nama Lengkap</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="Nama_lengkap" value="<?php echo $data_adm['Nama_lengkap']; ?>" required>
                                </div>
                            </div>

                            <div class="form-group row align-items-center">
                                <label class="col-sm-4 col-form-label">Jenis Kelamin</label>
                                <div class="col-sm-8">
                                    <select class="form-control" name="Jenis_kelamin" required>
                                        <option value="Laki-laki" <?php echo ($data_adm['Jenis_kelamin'] == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                                        <option value="Perempuan" <?php echo ($data_adm['Jenis_kelamin'] == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group row align-items-center">
                                <label class="col-sm-4 col-form-label">Agama</label>
                                <div class="col-sm-8">
                                    <select class="form-control" name="Agama" required>
                                        <option value="Islam" <?php echo ($data_adm['Agama'] == 'Islam') ? 'selected' : ''; ?>>Islam</option>
                                        <option value="Kristen" <?php echo ($data_adm['Agama'] == 'Kristen') ? 'selected' : ''; ?>>Kristen</option>
                                        <option value="Katolik" <?php echo ($data_adm['Agama'] == 'Katolik') ? 'selected' : ''; ?>>Katolik</option>
                                        <option value="Budha" <?php echo ($data_adm['Agama'] == 'Budha') ? 'selected' : ''; ?>>Budha</option>
                                        <option value="Hindu" <?php echo ($data_adm['Agama'] == 'Hindu') ? 'selected' : ''; ?>>Hindu</option>
                                        <option value="Konghucu" <?php echo ($data_adm['Agama'] == 'Konghucu') ? 'selected' : ''; ?>>Konghucu</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row align-items-center">
                                <label class="col-sm-4 col-form-label">No. Telepon</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="No_tlp" value="<?php echo $data_adm['No_tlp']; ?>" required>
                                </div>
                            </div>

                            <div class="form-group row align-items-center">
                                <label class="col-sm-4 col-form-label">Jabatan</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="Jabatan" value="<?php echo $data_adm['Jabatan']; ?>" required>
                                </div>
                            </div>

                            <div class="form-group row align-items-start">
                                <label class="col-sm-4 col-form-label pt-1">Alamat Lengkap</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" name="Alamat" rows="3" required><?php echo $data_adm['Alamat']; ?></textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-warning card-outline d-flex flex-column justify-content-between" style="min-height: 673px;">
                        <div class="card-body">
                            <h3 class="card-title font-weight-bold text-warning mb-3" style="float:none;">
                                <i class="fas fa-shield-alt mr-2"></i> Menu Akun & Keamanan
                            </h3>
                            
                            <div class="alert alert-warning alert-dismissible border-0 shadow-sm mt-2">
                                <h5><i class="icon fas fa-shield-alt"></i> Protokol Keamanan Sandi</h5>
                                <small>Username dapat langsung diubah. Namun untuk mengubah kata sandi, Anda wajib memasukkan Kata Sandi Lama Anda saat ini dengan benar demi perlindungan privasi data.</small>
                            </div>

                            <div class="form-group mt-3">
                                <label>Username Sistem</label>
                                <input type="text" class="form-control" name="Username" value="<?php echo $data_adm['Username']; ?>" required>
                            </div>
                            
                            <hr style="border-top: 1px dashed #cccccc;" class="my-4">

                            <div class="form-group">
                                <label class="text-danger">Kata Sandi Saat Ini (Password Lama)</label>
                                <input type="password" class="form-control border-danger" name="password_lama" placeholder="Ketik kata sandi lama jika ingin mengganti kata sandi">
                                <small class="text-muted">*Kosongkan form kata sandi di bawah jika Anda hanya berniat mengubah data profil atau username saja.</small>
                            </div>

                            <div class="form-group">
                                <label>Kata Sandi Baru</label>
                                <input type="password" class="form-control" name="password_baru" placeholder="Masukkan kata sandi baru">
                            </div>

                            <div class="form-group">
                                <label>Konfirmasi Kata Sandi Baru</label>
                                <input type="password" class="form-control" name="konfirmasi_password" placeholder="Ulangi kata sandi baru">
                            </div>
                        </div>

                        <div class="card-footer bg-white border-top-0 pt-0">
                            <button type="submit" name="proses_update_total" class="btn btn-success btn-block btn-lg shadow mb-3">
                                <i class="fas fa-check-circle mr-2"></i> <b>Simpan Seluruh Perubahan</b>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </form>

        <div class="row pb-5 mt-2">
            <div class="col-md-6">
                <div class="card card-secondary bg-light shadow-sm">
                    <div class="card-body p-3 d-flex justify-content-between align-items-center">
                        <a href="?page=menu" class="btn btn-secondary font-weight-bold px-4">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </a>
                        
                        <a href="logout.php" class="btn btn-outline-danger font-weight-bold px-4" 
                           onclick="return confirm('Apakah Anda yakin ingin keluar dan menutup sesi sistem informasi perpustakaan ini?')">
                            <i class="fas fa-sign-out-alt mr-2"></i> Keluar Aplikasi (Log out)
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
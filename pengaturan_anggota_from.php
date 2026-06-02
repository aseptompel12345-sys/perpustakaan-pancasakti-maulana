<?php
// 1. Identifikasi Sesi Anggota yang Sedang Login
$id_user_login  = $_SESSION['id_user'];
$jenis_anggota  = $_SESSION['jenis_anggota']; // Bernilai: 'Siswa', 'Guru', atau 'Kelas'
$id_agt_session = $_SESSION['Id_anggota'];

// 2. Tarik Data Akun User (Sama untuk semua jenis anggota)
$query_user = mysqli_query($db, "SELECT Username FROM users WHERE Id_user = '$id_user_login'");
$data_user  = mysqli_fetch_assoc($query_user);

// 3. Tarik Data Profil Berdasarkan Jenis Anggota Menggunakan Percabangan IF
if ($jenis_anggota == 'Siswa') {
    $query_profil = mysqli_query($db, "SELECT * FROM anggota_siswa WHERE Id_anggota = '$id_agt_session'");
    $data_profil  = mysqli_fetch_assoc($query_profil);
    $nama_display = $data_profil['Nama_siswa'];
    $foto_display = !empty($data_profil['Foto']) ? "FOTOS/foto_siswa/" . $data_profil['Foto'] : "avatar5.png";
} else if ($jenis_anggota == 'Guru') {
    $query_profil = mysqli_query($db, "SELECT * FROM anggota_guru WHERE Id_anggota = '$id_agt_session'");
    $data_profil  = mysqli_fetch_assoc($query_profil);
    $nama_display = $data_profil['Nama_guru'];
    $foto_display = !empty($data_profil['Foto']) ? "FOTOS/foto_guru/" . $data_profil['Foto'] : "avatar5.png";
} else if ($jenis_anggota == 'Kelas') {
    $query_profil = mysqli_query($db, "SELECT * FROM anggota_kelas WHERE Id_anggota = '$id_agt_session'");
    $data_profil  = mysqli_fetch_assoc($query_profil);
    $nama_display = $data_profil['Nama_kelas'];
    $foto_display = "foto/Logo.png"; // Default gambar instansi untuk kelas
}
?>

<style>
    .wadah-foto { position: relative; width: 140px; height: 140px; margin: 0 auto; }
    .foto-bundar { width: 140px; height: 140px; object-fit: cover; border: 4px solid #28a745; }
    .tombol-kamera {
        position: absolute; bottom: 0; right: 5px; background: #28a745; color: white;
        width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center;
        justify-content: center; cursor: pointer; border: 3px solid #fff; transition: transform 0.2s;
    }
    .tombol-kamera:hover { transform: scale(1.1); background: #1e7e34; color: #fff; }
</style>

<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0 text-dark"><i class="fas fa-sliders-h mr-2 text-success"></i> Pengaturan Akun Saya</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        
        <form action="pengaturan_anggota_proses.php" method="POST" enctype="multipart/form-data">
            <div class="row">
                
                <div class="col-md-6">
                    <div class="card card-success card-outline">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold text-success">
                                <i class="fas fa-user mr-2"></i> Profil Data <?php echo $jenis_anggota; ?>
                            </h3>
                        </div>
                        <div class="card-body">
                            
                            <div class="form-group text-center mb-4">
                                <div class="wadah-foto mb-2">
                                    <img src="<?php echo $foto_display; ?>" id="preview" class="img-circle foto-bundar elevation-2">
                                    
                                    <div class="custom-file" style="display: none;">
                                        <input type="file" name="Foto" class="custom-file-input" id="Foto" accept="image/*">
                                    </div>

                                    <?php if ($jenis_anggota != 'Kelas') : ?>
                                        <label for="Foto" class="tombol-kamera shadow">
                                            <i class="fas fa-camera"></i>
                                        </label>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <hr class="mb-4">

                            <?php if ($jenis_anggota == 'Siswa') : ?>
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-4 col-form-label">NISN</label>
                                    <div class="col-sm-8"><input type="number" class="form-control" name="NISN" value="<?php echo $data_profil['NISN']; ?>" required></div>
                                </div>
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-4 col-form-label">Nama Siswa</label>
                                    <div class="col-sm-8"><input type="text" class="form-control" name="Nama_siswa" value="<?php echo $data_profil['Nama_siswa']; ?>" required></div>
                                </div>
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-4 col-form-label">Jenis Kelamin</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="Jenis_kelamin" required>
                                            <option value="Laki-laki" <?php echo ($data_profil['Jenis_kelamin'] == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                                            <option value="Perempuan" <?php echo ($data_profil['Jenis_kelamin'] == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-4 col-form-label">Agama</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="Agama" required>
                                            <option value="Islam" <?php echo ($data_profil['Agama'] == 'Islam') ? 'selected' : ''; ?>>Islam</option>
                                            <option value="Kristen" <?php echo ($data_profil['Agama'] == 'Kristen') ? 'selected' : ''; ?>>Kristen</option>
                                            <option value="Katolik" <?php echo ($data_profil['Agama'] == 'Katolik') ? 'selected' : ''; ?>>Katolik</option>
                                            <option value="Budha" <?php echo ($data_profil['Agama'] == 'Budha') ? 'selected' : ''; ?>>Budha</option>
                                            <option value="Hindu" <?php echo ($data_profil['Agama'] == 'Hindu') ? 'selected' : ''; ?>>Hindu</option>
                                            <option value="Konghucu" <?php echo ($data_profil['Agama'] == 'Konghucu') ? 'selected' : ''; ?>>Konghucu</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-4 col-form-label">Kelas</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="Kelas" required>
                                            <option value="10" <?php echo ($data_profil['Kelas'] == '10') ? 'selected' : ''; ?>>10</option>
                                            <option value="11" <?php echo ($data_profil['Kelas'] == '11') ? 'selected' : ''; ?>>11</option>
                                            <option value="12" <?php echo ($data_profil['Kelas'] == '12') ? 'selected' : ''; ?>>12</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-4 col-form-label">Jurusan</label>
                                    <div class="col-sm-8"><input type="text" class="form-control" name="Jurusan" value="<?php echo $data_profil['Jurusan']; ?>" required></div>
                                </div>
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-4 col-form-label">No. Telepon</label>
                                    <div class="col-sm-8"><input type="number" class="form-control" name="No_tlp" value="<?php echo $data_profil['No_tlp']; ?>" required></div>
                                </div>
                                <div class="form-group row align-items-start">
                                    <label class="col-sm-4 col-form-label pt-1">Alamat Rumah</label>
                                    <div class="col-sm-8"><textarea class="form-control" name="Alamat" rows="2" required><?php echo $data_profil['Alamat']; ?></textarea></div>
                                </div>

                            <?php elseif ($jenis_anggota == 'Guru') : ?>
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-4 col-form-label">NIP</label>
                                    <div class="col-sm-8"><input type="number" class="form-control" name="NIP" value="<?php echo $data_profil['NIP']; ?>" required></div>
                                </div>
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-4 col-form-label">Nama Guru</label>
                                    <div class="col-sm-8"><input type="text" class="form-control" name="Nama_guru" value="<?php echo $data_profil['Nama_guru']; ?>" required></div>
                                </div>
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-4 col-form-label">Jenis Kelamin</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="Jenis_kelamin" required>
                                            <option value="Laki-laki" <?php echo ($data_profil['Jenis_kelamin'] == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                                            <option value="Perempuan" <?php echo ($data_profil['Jenis_kelamin'] == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-4 col-form-label">Agama</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="Agama" required>
                                            <option value="Islam" <?php echo ($data_profil['Agama'] == 'Islam') ? 'selected' : ''; ?>>Islam</option>
                                            <option value="Kristen" <?php echo ($data_profil['Agama'] == 'Kristen') ? 'selected' : ''; ?>>Kristen</option>
                                            <option value="Katolik" <?php echo ($data_profil['Agama'] == 'Katolik') ? 'selected' : ''; ?>>Katolik</option>
                                            <option value="Budha" <?php echo ($data_profil['Agama'] == 'Budha') ? 'selected' : ''; ?>>Budha</option>
                                            <option value="Hindu" <?php echo ($data_profil['Agama'] == 'Hindu') ? 'selected' : ''; ?>>Hindu</option>
                                            <option value="Konghucu" <?php echo ($data_profil['Agama'] == 'Konghucu') ? 'selected' : ''; ?>>Konghucu</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-4 col-form-label">No. Telepon</label>
                                    <div class="col-sm-8"><input type="number" class="form-control" name="No_tlp" value="<?php echo $data_profil['No_tlp']; ?>" required></div>
                                </div>
                                <div class="form-group row align-items-start">
                                    <label class="col-sm-4 col-form-label pt-1">Alamat Rumah</label>
                                    <div class="col-sm-8"><textarea class="form-control" name="Alamat" rows="3" required><?php echo $data_profil['Alamat']; ?></textarea></div>
                                </div>

                            <?php elseif ($jenis_anggota == 'Kelas') : ?>
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-4 col-form-label">Nama Kelas</label>
                                    <div class="col-sm-8"><input type="text" class="form-control" name="Nama_kelas" value="<?php echo $data_profil['Nama_kelas']; ?>" required></div>
                                </div>
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-4 col-form-label">Nama Wali Kelas</label>
                                    <div class="col-sm-8"><input type="text" class="form-control" name="Wali_kelas" value="<?php echo $data_profil['Wali_kelas']; ?>" required></div>
                                </div>
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-4 col-form-label">Jumlah Siswa</label>
                                    <div class="col-sm-8"><input type="number" class="form-control" name="Jumlah_siswa" value="<?php echo $data_profil['Jumlah_siswa']; ?>" required></div>
                                </div>
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-4 col-form-label">Penanggung Jawab</label>
                                    <div class="col-sm-8"><input type="text" class="form-control" name="Penanggung_jawab" value="<?php echo $data_profil['Penanggung_jawab']; ?>" required></div>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-warning card-outline d-flex flex-column justify-content-between" style="min-height: 593px;">
                        <div class="card-body">
                            <h3 class="card-title font-weight-bold text-warning mb-3" style="float:none;">
                                <i class="fas fa-key mr-2"></i> Kredensial Keamanan Akun
                            </h3>
                            
                            <div class="alert alert-warning alert-dismissible border-0 shadow-sm mt-2">
                                <h5><i class="icon fas fa-shield-alt"></i> Protokol Keamanan Sandi</h5>
                                <small>Username dapat langsung diubah. Namun untuk mengubah kata sandi, Anda wajib memasukkan Kata Sandi Lama Anda saat ini dengan benar demi perlindungan privasi data.</small>
                            </div>

                            <div class="form-group mt-3">
                                <label>Username Anggota</label>
                                <input type="text" class="form-control" name="Username" value="<?php echo $data_user['Username']; ?>" required>
                            </div>
                            
                            <hr style="border-top: 1px dashed #cccccc;" class="my-4">

                            <div class="form-group">
                                <label class="text-danger">Kata Sandi Sekarang (Password Lama)</label>
                                <input type="password" class="form-control border-danger" name="password_lama" placeholder="Ketik sandi lama jika hendak mengubah kata sandi">
                                <small class="text-muted">*Kosongkan form sandi jika Anda tidak ingin merubah sandi login.</small>
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
                            <button type="submit" name="proses_update_anggota" class="btn btn-success btn-block btn-lg shadow mb-3">
                                <i class="fas fa-check-circle mr-2"></i> <b>Simpan Pembaruan Anggota</b>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </form>

        <div class="row pb-5 mt-2">
            <div class="col-md-12">
                <div class="card card-secondary bg-light shadow-sm">
                    <div class="card-body p-3 d-flex justify-content-between align-items-center">
                        <a href="?page=menu" class="btn btn-secondary font-weight-bold px-4">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Menu Utama
                        </a>
                        <a href="logout.php" class="btn btn-outline-danger font-weight-bold px-4" 
                           onclick="return confirm('Apakah Anda yakin ingin keluar dari sistem?')">
                            <i class="fas fa-sign-out-alt mr-2"></i> Keluar (Log out)
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
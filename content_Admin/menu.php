<?php
// ==================================================================
// 1. QUERY SQL BAWAAN & TAMBAHAN UNTUK HITUNG TOTAL KAS DENDA
// ==================================================================
// Query bawaan Anda untuk tarif denda aktif
$query_denda = mysqli_query($db, "SELECT Nilai FROM denda ORDER BY Id_denda DESC LIMIT 1");
$data_denda = mysqli_fetch_assoc($query_denda);
$harga_denda_saat_ini = $data_denda['Nilai'] ?? 0;

// TAMBAHAN: Query menjumlahkan seluruh isi kolom Denda di tabel pengembalian
// Catatan: Pastikan nama tabel database Anda adalah 'pengembalian' dan nama kolomnya 'Denda'
$query_kas = mysqli_query($db, "SELECT SUM(Denda) as total_kas FROM pengembalian");
$data_kas  = mysqli_fetch_assoc($query_kas);
$total_kas = $data_kas['total_kas'] ?? 0; 
?>

<div class="content-header animate__animated animate__fadeIn">
    <div class="container-fluid">
        <div class="row mb-3 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold"><i class="fas fa-desktop text-primary mr-2"></i>Dashboard Admin</h1>
            </div>
            <div class="col-sm-6 text-right d-none d-sm-block">
                <span class="badge badge-light p-2 text-md shadow-sm"><i class="far fa-clock text-primary mr-1"></i> <?php echo date('d M Y'); ?></span>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card card-primary card-outline bg-gradient-white shadow">
                    <div class="card-body py-4">
                        <div class="d-flex align-items-center flex-wrap">
                            <div class="mr-4 mb-2 mb-md-0 text-primary" style="font-size: 3.5rem;">
                                <i class="fas fa-grin-stars animate__animated animate__bounceIn"></i>
                            </div>
                            <div>
                                <h3 class="font-weight-bold text-dark">Selamat Datang, <?php echo $_SESSION['nama_user']; ?>!</h3>
                                <p class="text-muted mb-0 lead text-md">Anda masuk sebagai <span class="badge badge-primary py-1 px-2"><?php echo strtoupper($_SESSION['role']); ?></span> Perpustakaan <b>SMKN 1 Kertajati</b>. Kelola sirkulasi buku dan pantau denda dengan mudah.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            
            <div class="col-md-4">
                <div class="card card-primary card-outline shadow-sm">
                    <div class="card-body box-profile">
                        <div class="text-center mb-3">
                            <img src="FOTOS/foto_admin/<?php echo $_SESSION['foto_user']; ?>" 
                                class="profile-user-img img-fluid img-circle shadow" 
                                style="width: 110px; height: 110px; object-fit: cover; border: 3px solid #007bff;"
                                alt="User profile picture">
                        </div>

                        <h4 class="profile-username text-center font-weight-bold text-truncate" style="letter-spacing: 0.5px;">
                            <?php echo $_SESSION['nama_user']; ?>
                        </h4>

                        <div class="text-center mb-3">
                            <span class="badge badge-info py-1 px-3 shadow-sm text-xs">
                                <i class="fas fa-user-shield mr-1"></i> <?php echo strtoupper($_SESSION['role']); ?>
                            </span>
                        </div>

                        <hr class="my-3">

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item border-0 py-2">
                                <i class="fas fa-check-circle text-primary mr-2"></i> <b>Status Akun</b> 
                                <span class="float-right badge badge-success">Aktif</span>
                            </li>
                            <li class="list-group-item border-0 py-2">
                                <i class="fas fa-school text-primary mr-2"></i> <b>Instansi</b> 
                                <span class="float-right text-muted font-italic text-sm">SMKN 1 Kertajati</span>
                            </li>
                        </ul>

                        <a href="?page=pengaturan_admin" class="btn btn-primary btn-block shadow-sm btn-sm font-weight-bold">
                            <i class="fas fa-user mr-1"></i> LIHAT PROFIL SAYA
                        </a>
                    </div>
                </div>

                <div class="card card-dark shadow-sm">
                    <div class="card-header py-2">
                        <h3 class="card-title text-sm"><i class="fas fa-university mr-2"></i>Tentang Sistem</h3>
                    </div>
                    <div class="card-body py-3 text-sm">
                        <p class="text-muted mb-0">
                            Sistem Informasi Perpustakaan SMKN 1 Kertajati dirancang untuk memudahkan manajemen data buku, verifikasi pengajuan anggota, serta otomasi denda keterlambatan secara transparan.
                        </p>
                    </div>
                </div>
            </div> 
            
            <div class="col-md-8">
                <div class="row">
                    
                    <div class="col-sm-6">
                        <div class="small-box bg-gradient-warning shadow-sm">
                            <div class="inner text-white">
                                <h3 class="font-weight-bold">Rp <?php echo number_format($harga_denda_saat_ini, 0, ',', '.'); ?></h3>
                                <p class="mb-1 font-weight-bold">Tarif Denda Aktif (Per Hari)</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-coins text-white-50"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-sm-6">
                        <div class="info-box shadow-sm mb-3" style="min-height: 103px;">
                            <span class="info-box-icon bg-gradient-success elevation-1"><i class="fas fa-wallet"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text font-weight-bold text-muted">Total Kas Perpus (Denda)</span>
                                <span class="info-box-number text-lg text-dark">
                                    Rp <?php echo number_format($total_kas, 0, ',', '.'); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="card card-danger card-outline shadow-sm">
                            <div class="card-header py-2">
                                <h3 class="card-title text-dark font-weight-bold text-sm"><i class="fas fa-edit mr-2 text-danger"></i>Perbarui Tarif Denda Per Hari</h3>
                            </div>
                            <form action="proses_Admin/tambah/harga_denda_proses.php" method="POST">
                                <div class="card-body py-3">
                                    <div class="form-group mb-0">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-danger text-white border-danger font-weight-bold">Rp</span>
                                            </div>
                                            <input type="number" name="Nilai" class="form-control form-control-lg font-weight-bold text-danger" value="<?php echo $harga_denda_saat_ini; ?>" min="0" required placeholder="Contoh: 500">
                                            <div class="input-group-append">
                                                <button type="submit" name="simpan_denda" class="btn btn-danger font-weight-bold px-4">
                                                    <i class="fas fa-save mr-1"></i> Simpan
                                                </button>
                                            </div>
                                        </div>
                                        <span class="text-muted text-xs mt-2 d-block"><i class="fas fa-info-circle mr-1 text-info"></i>Mengubah kolom <code>Nilai</code> pada tabel denda. Perubahan langsung berimbas pada kalkulasi otomatis pengembalian anggota.</span>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card card-outline card-secondary shadow-sm">
                    <div class="card-header py-2">
                        <h3 class="card-title text-sm font-weight-bold text-muted"><i class="fas fa-images mr-2 text-secondary"></i>Dokumentasi Ruang Perpustakaan</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-7 mb-3 mb-md-0">
                                <div class="position-relative shadow-sm rounded overflow-hidden" style="height: 220px;">
                                    <img src="foto/Foto_perpus1.jpeg" class="w-100 h-100 img-fluid" style="object-fit: cover;" alt="Foto Utama Perpustakaan">
                                    <div class="position-absolute bg-dark p-2 text-white text-xs opacity-75" style="bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.5);">
                                        <i class="fas fa-camera mr-1"></i> Ruang Baca & Sirkulasi Utama SMKN 1 Kertajati
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-5">
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <img src="foto/Foto_perpus2.jpeg" class="img-fluid rounded shadow-sm w-100" alt="Foto 2" style="height: 105px; object-fit: cover;">
                                    </div>
                                    <div class="col-12">
                                        <img src="foto/Foto_perpus3.jpeg" class="img-fluid rounded shadow-sm w-100" alt="Foto 3" style="height: 105px; object-fit: cover;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="blockquote-footer mt-4 text-center font-italic text-md text-dark font-weight-bold" style="border-top: 1px dashed #dee2e6; padding-top: 15px;">
                            "Buku adalah jendela dunia, mari budayakan membaca di SMKN 1 Kertajati."
                        </div>
                    </div>
                </div>

            </div>
         </div> 
    </div>
</section>
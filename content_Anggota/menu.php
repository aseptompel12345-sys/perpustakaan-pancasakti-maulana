<?php
// 1. KONEKSI & AMBIL DATA: Mengambil Tarif Denda Terakhir dari tabel denda
include_once "config/koneksi.php"; // Pastikan path koneksi Anda benar

$query_denda = mysqli_query($db, "SELECT Nilai FROM denda ORDER BY Id_denda DESC LIMIT 1");
$data_denda = mysqli_fetch_assoc($query_denda);
$harga_denda_saat_ini = $data_denda['Nilai'] ?? 0;

// 2. HITUNG STATISTIK ANGGOTA SECARA OTOMATIS
$id_anggota_login = $_SESSION['Id_anggota'];

// A. Total Semua Peminjaman Aktif/Selesai
$q_total_pinjam = mysqli_query($db, "SELECT COUNT(*) as total FROM peminjaman WHERE Id_anggota = '$id_anggota_login'");
$d_total_pinjam = mysqli_fetch_assoc($q_total_pinjam);

// B. Total Buku yang Masih Dipinjam (Sisa_pinjam > 0 atau Status != 'Selesai')
$q_masih_pinjam = mysqli_query($db, "SELECT COUNT(*) as total FROM peminjaman WHERE Id_anggota = '$id_anggota_login' AND Status != 'Selesai'");
$d_masih_pinjam = mysqli_fetch_assoc($q_masih_pinjam);

// C. Total Transaksi yang Sudah Selesai/Lunas
$q_selesai_pinjam = mysqli_query($db, "SELECT COUNT(*) as total FROM peminjaman WHERE Id_anggota = '$id_anggota_login' AND Status = 'Selesai'");
$d_selesai_pinjam = mysqli_fetch_assoc($q_selesai_pinjam);
?>

<div class="content-header animate__animated animate__fadeIn">
    <div class="container-fluid">
        <div class="row mb-3 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold"><i class="fas fa-desktop text-success mr-2"></i>Dashboard Anggota</h1>
            </div>
            <div class="col-sm-6 text-right d-none d-sm-block">
                <span class="badge badge-light p-2 text-md shadow-sm"><i class="far fa-clock text-success mr-1"></i> <?php echo date('d M Y'); ?></span>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
    
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card card-success card-outline bg-gradient-white shadow">
                    <div class="card-body py-4">
                        <div class="d-flex align-items-center flex-wrap">
                            <div class="mr-4 mb-2 mb-md-0 text-success" style="font-size: 3.5rem;">
                                <i class="fas fa-grin-stars animate__animated animate__bounceIn"></i>
                            </div>
                            <div>
                                <h3 class="font-weight-bold text-dark">Selamat Datang, <?php echo $_SESSION['nama_user']; ?>!</h3>
                                <p class="text-muted mb-0 lead text-md">Anda masuk sebagai <span class="badge badge-success py-1 px-2">ANGGOTA (<?php echo strtoupper($_SESSION['jenis_anggota'] ?? 'Siswa'); ?>)</span> Perpustakaan <b>SMKN 1 Kertajati</b>. Cari buku, pantau riwayat pinjaman, dan kembalikan buku tepat waktu.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card card-success card-outline shadow-sm">
                    <div class="card-body box-profile">
                        <div class="text-center mb-3">
                            <?php
                                $jenis = $_SESSION['jenis_anggota'];
                                $id_agt = $_SESSION['Id_anggota'];
                                $folder = "FOTOS/";
                                
                                // 1. Tentukan Path Foto
                                if ($jenis == 'Kelas') {
                                    // Khusus Kelas langsung arahkan ke Logo Sekolah
                                    $full_path = "foto/Logo.png"; 
                                } else {
                                    if (!empty($_SESSION['foto_user'])) {
                                        // Jika user punya foto di database
                                        $sub_folder = ($jenis == 'Siswa') ? "foto_siswa/" : "foto_guru/";
                                        $full_path = $folder . $sub_folder . $_SESSION['foto_user'];
                                    } else {
                                        // Jika foto kosong, ambil Gender dari database untuk tentukan Avatar
                                        if ($jenis == 'Siswa') {
                                            $q_jk = mysqli_query($db, "SELECT Jenis_kelamin FROM anggota_siswa WHERE Id_anggota = '$id_agt'");
                                        } else {
                                            $q_jk = mysqli_query($db, "SELECT Jenis_kelamin FROM anggota_guru WHERE Id_anggota = '$id_agt'");
                                        }
                                        
                                        $d_jk = mysqli_fetch_assoc($q_jk);
                                        $gender = $d_jk['Jenis_kelamin'];
                                        
                                        // Pilih Avatar berdasarkan Gender
                                        $full_path = ($gender == 'Perempuan') ? "dist/img/avatar3.png" : "dist/img/avatar5.png";
                                    }
                                }
                            ?>
                            <img src="<?php echo $full_path; ?>" 
                                class="profile-user-img img-fluid img-circle shadow" 
                                style="width: 110px; height: 110px; object-fit: cover; border: 3px solid #28a745;"
                                alt="User profile picture">
                                
                        </div>

                        <h4 class="profile-username text-center font-weight-bold text-truncate" style="letter-spacing: 0.5px;">
                            <?php echo $_SESSION['nama_user']; ?>
                        </h4>

                        <div class="text-center mb-3">
                            <span class="badge badge-success py-1 px-3 shadow-sm text-xs">
                                <i class="fas fa-user-shield mr-1"></i> <?php echo strtoupper($_SESSION['role']); ?>
                            </span>
                        </div>

                        <hr class="my-3">

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item border-0 py-2">
                                <i class="fas fa-check-circle text-success mr-2"></i> <b>Status Anggota</b> 
                                <span class="float-right badge badge-success">Aktif</span>
                            </li>
                            <li class="list-group-item border-0 py-2">
                                <i class="fas fa-school text-success mr-2"></i> <b>Instansi</b> 
                                <span class="float-right text-muted font-italic text-sm">SMKN 1 Kertajati</span>
                            </li>
                        </ul>

                        <a href="?anggota=pengaturan_anggota" class="btn btn-success btn-block shadow-sm btn-sm font-weight-bold">
                            <i class="fas fa-user mr-1"></i> LIHAT PROFIL SAYA
                        </a>
                    </div>
                </div>

                <div class="card card-dark shadow-sm">
                    <div class="card-header py-2 bg-dark">
                        <h3 class="card-title text-sm"><i class="fas fa-info-circle mr-2"></i>Informasi Denda</h3>
                    </div>
                    <div class="card-body py-3 text-sm">
                        <p class="text-muted mb-0">
                            Keterlambatan pengembalian buku dikenakan denda sesuai tarif aktif. Harap perhatikan tanggal jatuh tempo pada kartu/aplikasi peminjaman Anda untuk menghindari akumulasi denda.
                        </p>
                    </div>
                </div>
            </div> 

            <div class="col-md-8">
                
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div class="small-box bg-gradient-danger shadow-sm">
                            <div class="inner text-white">
                                <h3 class="font-weight-bold">Rp <?php echo number_format($harga_denda_saat_ini, 0, ',', '.'); ?></h3>
                                <p class="mb-1 font-weight-bold">Tarif Denda Keterlambatan (Per Hari)</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-calculator text-white-50"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-sm-6">
                        <div class="info-box shadow-sm mb-3" style="min-height: 103px;">
                            <span class="info-box-icon bg-gradient-success elevation-1"><i class="fas fa-calendar-check"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text font-weight-bold text-muted">Tanggal Server</span>
                                <span class="info-box-number text-lg text-dark"><?php echo date('d F Y'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-1">
                    <div class="col-md-4 col-6">
                        <div class="description-block border-right text-center py-2 bg-white rounded shadow-sm mb-3">
                            <h5 class="description-header text-primary font-weight-bold text-lg"><?php echo $d_total_pinjam['total']; ?></h5>
                            <span class="description-text text-muted text-xs">TOTAL PINJAMAN</span>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="description-block border-right text-center py-2 bg-white rounded shadow-sm mb-3">
                            <h5 class="description-header text-warning font-weight-bold text-lg"><?php echo $d_masih_pinjam['total']; ?></h5>
                            <span class="description-text text-muted text-xs">BELUM KEMBALI</span>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="description-block text-center py-2 bg-white rounded shadow-sm mb-3">
                            <h5 class="description-header text-success font-weight-bold text-lg"><?php echo $d_selesai_pinjam['total']; ?></h5>
                            <span class="description-text text-muted text-xs">TRANSAKSI SELESAI</span>
                        </div>
                    </div>
                </div>

                <div class="card card-outline card-secondary shadow-sm mt-2">
                    <div class="card-header py-2">
                        <h3 class="card-title text-sm font-weight-bold text-muted"><i class="fas fa-images mr-2 text-secondary"></i>Fasilitas Perpustakaan</h3>
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
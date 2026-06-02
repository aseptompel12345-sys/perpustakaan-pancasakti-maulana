<style>
    /* Mengunci tampilan agar konsisten di semua ukuran layar */
    .custom-box {
        position: relative;
        display: block;
        border-radius: 12px;
        margin-bottom: 20px;
        color: #fff !important;
        min-height: 200px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .custom-box .inner {
        padding: 15px;
        padding-right: 90px;
        text-align: left !important;
    }

    .custom-box h3 {
        font-size: 1.8rem;
        font-weight: bold;
        margin: 0 0 5px 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .custom-box .icon-static {
        position: absolute;
        top: 55px; 
        right: 15px;
        z-index: 0;
        transition: all 0.3s linear;
    }

    /* Silahkan atur font-size di sini untuk mencoba-coba ukuran ikon */
    .custom-box .icon-static i {
        font-size: 85px; 
        color: rgba(0, 0, 0, 0.15);
    }

    .custom-box .aksi-btn {
        position: absolute;
        top: 8px;
        right: 8px;
        z-index: 20;
    }

    .custom-box .garis-pendek {
        border-top: 1px solid rgba(255,255,255,0.4);
        margin: 10px 0;
        width: 65%;
    }

    .custom-box .small-box-footer {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        background: rgba(0,0,0,0.1);
        padding: 4px 0;
        text-align: center;
        color: rgba(255,255,255,0.8) !important;
        text-decoration: none;
        z-index: 10;
    }

    .custom-box:hover .icon-static i {
        transform: scale(1.1);
    }

    /* Perbaikan khusus layar HP untuk tombol tambah */
    @media (max-width: 576px) {
        .btn-mobile-full {
            width: 100%;
            margin-top: 10px;
            text-align: center;
        }
        .header-mobile-center {
            text-align: center;
        }
    }
</style>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6 col-12 header-mobile-center">
                <h1 class="m-0"><i class="fas fa-users text-info"></i> Daftar Anggota Kelas</h1>
            </div>
            <div class="col-sm-6 col-12 text-sm-right">
                <a href="?page=anggota_kelas_from" class="btn btn-success shadow-sm btn-mobile-full">
                    <i class="fas fa-plus"></i> Tambah Anggota
                </a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <?php
                $query = mysqli_query($db, "SELECT * FROM anggota_kelas ORDER BY Id_kelas DESC");
                $warna_box = ['bg-info', 'bg-success', 'bg-warning', 'bg-danger', 'bg-primary', 'bg-secondary'];
                $i = 0;

                if (mysqli_num_rows($query) > 0) {
                    while ($d = mysqli_fetch_array($query)) {
                        $warna = $warna_box[$i % count($warna_box)];
            ?>
            
            <div class="col-lg-4 col-md-6 col-12 mb-3">
                <div class="custom-box <?php echo $warna; ?>">
                    
                    <div class="aksi-btn">
                        <a href="?page=anggota_kelas_edit&id=<?php echo $d['Id_kelas']; ?>" class="text-white mr-2" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="?page=anggota_kelas_hapus&id_kelas=<?php echo $d['Id_kelas']; ?>" class="text-white" title="Hapus" onclick="return confirm('Yakin ingin menghapus kelas ini?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>

                    <div class="inner">
                        <h3><?php echo $d['Nama_kelas']; ?></h3>
                        <p class="mb-0"><strong>Wali:</strong> <?php echo $d['Wali_kelas']; ?></p>
                        
                        <div class="garis-pendek"></div>
                        
                        <div style="font-size: 0.95rem;">
                            <p class="mb-1"><i class="fas fa-user-check fa-fw"></i> PJ: <?php echo $d['Penanggung_jawab']; ?></p>
                            <p class="mb-0"><i class="fas fa-users fa-fw "></i> Total: <?php echo $d['Jumlah_siswa']; ?> Siswa</p>
                        </div>
                    </div>

                    <div class="icon-static">
                        <i class="fas fa-users"></i>
                    </div>
                    
                    <a href="?page=transaksi_pinjam&id=<?php echo $d['Id_kelas']; ?>" class="small-box-footer">
                        Riwayat Pinjaman Kelas <i class="fas fa-arrow-circle-right ml-1"></i>
                    </a>
                </div>
            </div>

            <?php 
                    $i++;
                } 
                } else {
                    echo "<div class='col-12'><div class='alert alert-info border-left shadow-sm'>Belum ada data kelas.</div></div>";
                }
            ?>
        </div>
    </div>
</section>
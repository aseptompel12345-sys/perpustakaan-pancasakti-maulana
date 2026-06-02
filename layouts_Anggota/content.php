<div class="content-wrapper">
    <section class="content pt-3">
        <div class="container-fluid">
            <?php
                $anggota = isset($_GET['anggota']) ? $_GET['anggota'] : 'menu'; 

                switch ($anggota) {
                    case 'buku':
                        include 'content_Anggota/buku.php';
                        break;
                    case 'transaksi_kunjungan':
                        include 'content_Anggota/transaksi_kunjungan.php';
                        break;
                    case 'transaksi_pinjam_kembali':
                        include 'content_Anggota/transaksi_pinjam&kembali.php';
                        break;
                    case 'transaksi_kunjungan_from':
                        include 'from&detail_Anggota/tambah/transaksi_kunjungan_from.php';
                        break;
                    case 'transaksi_pinjam&kembali_from':
                        include 'from&detail_Anggota/tambah/transaksi_pinjam&kembali_from.php';
                        break;
                    case 'transaksi_kembali':
                        include 'proses_Anggota/tambah/transaksi_pengembalian_proses.php';
                        break;
                    case 'pengaturan_anggota':
                        include 'pengaturan_anggota_from.php';
                        break;
                    default:
                        include 'content_Anggota/menu.php';
                }
            ?>
        </div>
    </section>
</div>
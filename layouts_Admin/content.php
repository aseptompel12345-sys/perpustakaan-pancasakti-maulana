<div class="content-wrapper">
    <section class="content pt-3">
        <div class="container-fluid">

            <?php
                // Ganti baris $page lama dengan dua baris ini
                $page = isset($_GET['page']) ? $_GET['page'] : 'menu'; 
                $aksi = isset($_GET['aksi']) ? $_GET['aksi'] : ''; 

                switch ($page) {
                    case 'buku':
                    include 'content_Admin/buku.php';
                    break;
                    case 'anggota_siswa':
                    include 'content_Admin/anggota_siswa.php';
                    break;
                    case 'anggota_guru':
                    include 'content_Admin/anggota_guru.php';
                    break;
                    case 'anggota_kelas':
                    include 'content_Admin/anggota_kelas.php';
                    break;
                    case 'transaksi_kunjungan':
                    include 'content_Admin/transaksi_kunjungan.php';
                    break;
                    case 'transaksi_pinjam':
                    include 'content_Admin/transaksi_pinjam.php';
                    break;
                    case 'transaksi_pengembalian':
                    include 'content_Admin/transaksi_pengembalian.php';
                    break;
                    case 'buku_from':
                    include 'from&detail_Admin/tambah/buku_from.php';
                    break;
                    case 'buku_edit':
                    include 'from&detail_Admin/edit/buku_edit.php';
                    break;
                    case 'buku_hapus':
                    include 'proses_Admin/hapus/buku_proses.php';
                    break;
                    case 'anggota_siswa_from':
                    include 'from&detail_Admin/tambah/anggota_siswa_from.php';
                    break;
                    case 'anggota_siswa_edit':
                    include 'from&detail_Admin/edit/anggota_siswa_edit.php';
                    break;
                    case 'anggota_siswa_hapus':
                    include 'proses_Admin/hapus/anggota_siswa_proses.php';
                    break;
                    case 'anggota_guru_from':
                    include 'from&detail_Admin/tambah/anggota_guru_from.php';
                    break;
                    case 'anggota_guru_edit':
                    include 'from&detail_Admin/edit/anggota_guru_edit.php';
                    break;
                    case 'anggota_guru_hapus':
                    include 'proses_Admin/hapus/anggota_guru_proses.php';
                    break;
                    case 'anggota_kelas_from':
                    include 'from&detail_Admin/tambah/anggota_kelas_from.php';
                    break;
                    case 'anggota_kelas_edit':
                    include 'from&detail_Admin/edit/anggota_kelas_edit.php';
                    break;
                    case 'anggota_kelas_hapus':
                    include 'proses_Admin/hapus/anggota_kelas_proses.php';
                    break;
                    case 'transaksi_kunjungan_from':
                    include 'from&detail_Admin/tambah/transaksi_kunjungan_from.php';
                    break;
                    case 'transaksi_kunjungan_edit':
                    include 'from&detail_Admin/edit/transaksi_kunjungan_edit.php';
                    break;
                    case 'transaksi_kunjungan_hapus':
                    include 'proses_Admin/hapus/transaksi_kunjungan_proses.php';
                    break;
                    case 'transaksi_pengembalian_from':
                    include 'from&detail_Admin/tambah/transaksi_pengembalian_from.php';
                    break;
                    case 'transaksi_pengembalian_edit':
                    include 'from&detail_Admin/edit/transaksi_pengembalian_edit.php';
                    break;
                    case 'transaksi_pengembalian_hapus':
                    include 'proses_Admin/hapus/transaksi_pengembalian_proses.php';
                    break;
                    case 'transaksi_pinjam_from':
                    include 'from&detail_Admin/tambah/transaksi_pinjam_from.php';
                    break;
                    case 'transaksi_pinjam_edit':
                    include 'from&detail_Admin/edit/transaksi_pinjam_edit.php';
                    break;
                    case 'transaksi_pinjam_hapus':
                    include 'proses_Admin/hapus/transaksi_pinjam_proses.php';
                    break;
                    case 'akun_siswa':
                    include 'buat_akun/oleh_Admin/akun_siswa_from.php';
                    break;
                    case 'akun_guru':
                    include 'buat_akun/oleh_Admin/akun_guru_from.php';
                    break;
                    case 'akun_kelas':
                    include 'buat_akun/oleh_Admin/akun_kelas_from.php';
                    break;
                    case 'pengaturan_admin':
                    include 'pengaturan_admin_from.php';
                    break;
                    default:
                    include 'content_Admin/menu.php';
                }
            ?>

        </div>
    </section>
</div> 
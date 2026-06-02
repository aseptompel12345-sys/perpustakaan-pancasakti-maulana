<?php
    include "config/koneksi.php";

    // 1. Jika TIDAK ada status_login, langsung tendang ke login (Tanpa ampun)
    if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
        header("Location: login_from.php");
        exit();
    }

    // 2. Jika dia login tapi BUKAN admin (misal anggota iseng nembak URL admin)
    if ($_SESSION['role'] !== 'admin') {
        echo "<script>alert('Akses Ditolak! Anda bukan Admin.'); window.location.href='login_from.php';</script>";
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Sekolah SMKN 1 Kertajati</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">  
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">

    <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/toastr/toastr.min.css">

<style>
  /* 1. AREA PENCARIAN (Search Bar) - Desktop */
  .dataTables_filter {
    float: right;
    margin-bottom: 20px;
  }

  .dataTables_filter .d-flex {
    display: flex !important;
    align-items: center;
    justify-content: flex-end;
  }

  .dataTables_filter input {
    height: 38px !important;
    border-radius: 4px 0 0 4px !important;
    border: 1.5px solid #007bff;
    margin-left: 0 !important;
    transition: width 0.4s ease-in-out, border-color 0.4s; 
  }

  @media (min-width: 769px) {
    .dataTables_filter input {
      width: 150px !important;
    }
    .dataTables_filter input:focus {
      width: 300px !important;
    }
  }

  .dataTables_filter input:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 5px rgba(0,123,255,0.2);
  }

  .dataTables_filter .btn-primary {
    height: 38px !important;
    border-radius: 0 4px 4px 0 !important;
    padding: 0 15px;
    border-left: none;
  }

  /*  PERBAIKAN SEARCH DI HP (AdminLTE + DataTables) */
  @media (max-width: 768px){

    .dataTables_wrapper .dataTables_filter {
      float: none !important;
      width: 100% !important;
      text-align: center !important;
    }

    .dataTables_wrapper .dataTables_filter label {
      width: 100% !important;
      display: flex !important;
      justify-content: center !important;
      align-items: center !important;
      gap: 6px;
      margin: 0 auto !important;
    }

    /*  Ukuran awal kecil */
    .dataTables_wrapper .dataTables_filter input {
      width: 140px !important;
      transition: width 0.4s ease-in-out;
    }

    /*  Membesar hanya saat diklik / focus */
    .dataTables_wrapper .dataTables_filter input:focus {
      width: 260px !important;
    }

    .dataTables_wrapper .dataTables_filter .btn-primary {
      width: 45px !important;
      padding: 0 !important;
      display: flex !important;
      align-items: center;
      justify-content: center;
    }

  }

  /* 2. TOMBOL AKSI (Edit & Hapus) - Tetap */
  .btn-group-aksi {
    display: flex;
    gap: 12px;
    justify-content: center;
  }


  /* 3. PENGATURAN LAYAR HP - VERSI CSS GRID (ANTI POTONG) */
  @media (max-width: 768px) {

    .justify-content-between {
      justify-content: center !important;
      flex-direction: column !important;
      gap: 15px !important;
      align-items: center !important;
    }
  }


  /* 4. MERAPIKAN TABEL - Tetap */
  #tabelBuku th, #tabelBuku td {
    vertical-align: middle !important;
    white-space: nowrap;
  }

  #tabelBuku td:nth-child(4) {
    white-space: normal !important;
    min-width: 250px;
  }

  .dataTables_scrollBody {
    border-bottom: 1px solid #dee2e6;
  }


  /* 5. PENGATURAN FOTO - Tetap */
  #tabelBuku td:first-child, 
  #tabelSiswa td:first-child, 
  #tabelGuru td:first-child,
  #tabelKunjungan td:first-child,
  #tabelPinjam td:first-child,
  #tabelPengembalian td:first-child {
    width: 120px !important;
    min-width: 120px !important;
    max-width: 120px !important;
    text-align: center;
  }

  #tabelBuku td img, 
  #tabelSiswa td img, 
  #tabelGuru td img,
  #tabelKunjungan td img,
  #tabelPinjam td img,
  #tabelPengembalian td img {
    width: 100px !important;
    height: 120px !important;
    object-fit: cover;
    border-radius: 6px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    transition: transform 0.3s ease;
  }

  #tabelBuku td img:hover, 
  #tabelSiswa td img:hover, 
  #tabelGuru td img:hover,
  #tabelKunjungan td img:hover,
  #tabelPinjam td img:hover,
  #tabelPengembalian td img:hover {
    transform: scale(1.1);
    position: relative;
    z-index: 999;
    box-shadow: 0 8px 20px rgba(0,0,0,0.3);
  }


  /* 6. LOGIKA TOMBOL KAMERA - Tetap */
  @media (min-width: 992px) {
    .tombol-kamera-hp { display: none !important; }
    .custom-file-input ~ .custom-file-label::after { border-radius: 0 4px 4px 0 !important; }
  }

  @media (max-width: 991px) {
    .tombol-kamera-hp { display: flex !important; }
  }

  /* 1. Pertegas Border Kotak Utama Select2 */
  .select2-container--bootstrap4 .select2-selection--single {
      border: 2px solid #ced4da !important; /* Gunakan 2px agar lebih tebal */
      height: calc(2.25rem + 2px) !important;
      background-color: #fff !important;
  }

  /* 2. Beri Warna Hijau saat Diklik agar Admin Tahu sedang Aktif */
  .select2-container--bootstrap4.select2-container--focus .select2-selection {
      border-color: #28a745 !important;
      box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25) !important;
  }

  /* 3. PERBAIKAN UTAMA: Kotak Input Pencarian di dalam Dropdown */
  /* Ini yang bikin kotak search kamu terlihat "polos" tadi */
  .select2-search--dropdown .select2-search__field {
      border: 1px solid #007bff !important; /* Beri border biru */
      background-color: #f8f9fa !important; /* Beri warna background abu muda */
      border-radius: 4px !important;
      padding: 8px !important;
  }

  /* 4. Pertegas teks placeholder */
  .select2-container--bootstrap4 .select2-selection--single .select2-selection__placeholder {
      color: #495057 !important; /* Lebih gelap dari sebelumnya */
      font-weight: bold !important;
  }

</style>

</head>                                                                                                                                                                                                                     
<body class="hold-transition sidebar-mini layout-fixed">                          
  <div class="wrapper">    
    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__shake" src="foto/Logo.png" alt="Pancasakti Logo" height="200" width="200">
      <h1>Pancasakti</h1>
    </div>

    <!-- Navbar -->
    <?php
      include "layouts_Admin/navbar.php";
    ?>
    <!-- /.navbar -->

    <!-- Sidebar -->
    <?php
      include "layouts_Admin/sidebar.php";
    ?>
    <!-- /.sidebar -->

  </div>
 
  <!-- Content Wrapper. Contains page content -->
  <?php
    include "layouts_Admin/content.php";
  ?>
  <!-- /.content -->

  <!-- From Modal Data FIlter CEtak -->
  <div class="modal fade" id="modalFilterTanggal" tabindex="-1" role="dialog" aria-labelledby="labelModalFilter" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-primary text-white py-2">
                <h5 class="modal-title font-weight-bold text-sm" id="labelModalFilter">
                    <i class="fas fa-filter mr-1"></i> Syarat Cetak & Ekspor Data
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body py-3">
                <input type="hidden" id="action-type-trigger">
                <input type="hidden" id="current-active-table">
                
                <!-- ========================================================================= -->
                <!-- FILTER TABEL TRANSAKSI (KUNJUNGAN, PINJAM, PENGEMBALIAN) -->
                <!-- ========================================================================= -->
                <div id="filter-group-transaksi" class="filter-section d-none">
                    <p class="text-xs text-muted font-weight-bold mb-2 text-uppercase text-primary">Filter Laporan Transaksi</p>
                    
                    <!-- Rentang Tanggal (Untuk Semua Transaksi) -->
                    <div class="row">
                        <div class="col-6 form-group mb-2">
                            <label class="text-xs font-weight-bold text-muted">Dari Tanggal :</label>
                            <input type="date" id="min-date" class="form-control filter-input">
                        </div>
                        <div class="col-6 form-group mb-2">
                            <label class="text-xs font-weight-bold text-muted">Sampai Tanggal :</label>
                            <input type="date" id="max-date" class="form-control filter-input">
                        </div>
                    </div>

                    <!-- Jenis Anggota (Untuk Semua Transaksi) -->
                    <div class="form-group mb-2">
                        <label class="text-xs font-weight-bold text-muted" for="jenis-anggota">Jenis Anggota :</label>
                        <select class="form-control filter-input" id="jenis-anggota">
                            <option value="">-- Semua Jenis Anggota --</option>
                            <option value="Siswa">Siswa</option>
                            <option value="Guru">Guru</option>
                            <option value="Kelas">Kelas</option>
                        </select>
                    </div>

                    <!-- Khusus Inputan Denda (Hanya Akan Diperlihatkan via JS jika Tabel Pengembalian Aktif) -->
                    <div id="sub-filter-denda" class="d-none">
                        <hr class="my-2">
                        <p class="text-xs text-muted font-weight-bold mb-2 text-uppercase text-danger">Filter Nominal Denda</p>
                        <div class="row">
                            <div class="col-6 form-group mb-0">
                                <label class="text-xs font-weight-bold text-muted">Denda Minimal (Rp) :</label>
                                <input type="number" id="denda-start" class="form-control filter-input" placeholder="Contoh: 0">
                            </div>
                            <div class="col-6 form-group mb-0">
                                <label class="text-xs font-weight-bold text-muted">Denda Maksimal (Rp) :</label>
                                <input type="number" id="denda-end" class="form-control filter-input" placeholder="Contoh: 5000">
                            </div>
                        </div>
                    </div>
                </div>

                <div id="filter-group-buku" class="filter-section d-none">
                    <p class="text-xs text-muted font-weight-bold mb-2 text-uppercase text-primary">Filter Spesifikasi Buku</p>
                    <div class="row">
                        <div class="col-6 form-group mb-2">
                            <label class="text-xs font-weight-bold text-muted">ID Buku (Dari) :</label>
                            <input type="number" id="buku-id-start" class="form-control filter-input" placeholder="Contoh: 1">
                        </div>
                        <div class="col-6 form-group mb-2">
                            <label class="text-xs font-weight-bold text-muted">ID Buku (Sampai) :</label>
                            <input type="number" id="buku-id-end" class="form-control filter-input" placeholder="Contoh: 50">
                        </div>
                        <div class="col-6 form-group mb-2">
                            <label class="text-xs font-weight-bold text-muted">Thn Terbit (Dari) :</label>
                            <input type="number" id="buku-thn-start" class="form-control filter-input" placeholder="2020">
                        </div>
                        <div class="col-6 form-group mb-2">
                            <label class="text-xs font-weight-bold text-muted">Thn Terbit (Sampai) :</label>
                            <input type="number" id="buku-thn-end" class="form-control filter-input" placeholder="2026">
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <label class="text-xs font-weight-bold text-muted" for="buku-bidang">Bidang Buku :</label>
                        <select class="form-control filter-input" name="Bidang_buku" id="buku-bidang">
                            <option value="">-- Pilih Bidang --</option>
                            <option value="Pendidikan">Pendidikan</option>
                            <option value="Fiksi">Fiksi</option>
                            <option value="Nonfiksi">Nonfiksi</option>
                            <option value="Lain-lain">Lain-lain</option>
                        </select>
                    </div>
                    <div class="form-group mb-0">
                        <label class="text-xs font-weight-bold text-muted" for="buku_rak">Lokasi Rak Buku</label>
                        <select class="form-control filter-input" name="Rak_buku" id="buku_rak">
                            <option value="">-- Pilih Lokasi Rak --</option>
                            <option value="Mapel Umum">Mapel Umum</option>
                            <option value="Mapel Produktif Teknik Pesawat Udara">Mapel Produktif Teknik Pesawat Udara</option>
                            <option value="Mapel Produktif Otomotif">Mapel Produktif Otomotif</option>
                            <option value="Mapel Produktif Akuntansi dan Lembaga Keuangan">Mapel Produktif Akuntansi dan Lembaga Keuangan</option>
                            <option value="Mapel Produktif PPLG">Mapel Produktif PPLG</option>
                            <option value="Mapel Produktif TKJ">Mapel Produktif TKJ</option>
                            <option value="Mapel Produktif Teknik Logistik">Mapel Produktif Teknik Logistik</option>
                        </select>
                    </div>
                </div>

                <div id="filter-group-siswa" class="filter-section d-none">
                    <p class="text-xs text-muted font-weight-bold mb-2 text-uppercase text-primary">Filter Kelas & Jurusan</p>
                    <div class="form-group mb-0">
                        <label class="text-xs font-weight-bold text-muted" for="kelas">Kelas :</label>
                        <select class="form-control filter-input" name="Kelas" id="siswa-kelas">
                            <option value="">-- Pilih Kelas --</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                        </select>
                    </div>
                    <div class="form-group mb-0">
                        <label class="text-xs font-weight-bold text-muted">Jurusan :</label>
                        <input type="text" id="siswa-jurusan" class="form-control filter-input" placeholder="Contoh: PPLG">
                    </div>
                </div>

                <div id="filter-group-guru" class="filter-section d-none">
                    <p class="text-xs text-muted font-weight-bold mb-2 text-uppercase text-primary">Filter ID Rentang Guru</p>
                    <div class="row">
                        <div class="col-6 form-group mb-0">
                            <label class="text-xs font-weight-bold text-muted">ID Guru (Dari) :</label>
                            <input type="number" id="guru-id-start" class="form-control filter-input" placeholder="Contoh: 1">
                        </div>
                        <div class="col-6 form-group mb-0">
                            <label class="text-xs font-weight-bold text-muted">ID Guru (Sampai) :</label>
                            <input type="number" id="guru-id-end" class="form-control filter-input" placeholder="Contoh: 10">
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" id="btn-reset-filter" class="btn btn-sm btn-secondary font-weight-bold">
                    <i class="fas fa-sync-alt"></i> Reset Filter
                </button>
                <button type="button" id="btn-terapkan-filter" class="btn btn-sm btn-primary font-weight-bold px-4">
                    <i class="fas fa-save"></i> Terapkan Filter
                </button>
            </div>
        </div>
    </div>
</div>


  <script src="plugins/jquery/jquery.min.js"></script>
  <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
  <script>$.widget.bridge('uibutton', $.ui.button)</script>
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script src="plugins/chart.js/Chart.min.js"></script>
  <script src="plugins/select2/js/select2.full.min.js"></script>

  <script src="plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
  <script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
  <script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
  <script src="plugins/jszip/jszip.min.js"></script>
  <script src="plugins/pdfmake/pdfmake.min.js"></script>
  <script src="plugins/pdfmake/vfs_fonts.js"></script>
  <script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
  <script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
  <script src="dist/js/adminlte.js"></script>
  <script src="plugins/toastr/toastr.min.js"></script>

  <script>
        // ================================================================
        // A. FUNGSI GLOBAL (Diletakkan di luar agar bisa dipanggil file lain)
        // ================================================================

        function ambilDataPinjaman(id) {
        if (id !== "" && id !== null) {
            $.ajax({
                url: 'proses_Admin/ajak/ambil_data_pinjaman.php',
                type: 'POST',
                data: { id: id },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        // 1. ISI DATA ANGGOTA (Input Hidden & Teks)
                        $('#id_anggota').val(response.id_anggota);
                        $('#nama_peminjam').val(response.nama);
                        $('#jenis_anggota').val(response.jenis); 
                        $('#identitas_peminjam').val(response.identitas);
                        
                        $('#tampil_foto_peminjam').attr('src', response.foto_peminjam); 
                        $('#val_foto_peminjam').val(response.foto_peminjam);           

                        // 3. ISI DATA BUKU (Input Hidden & Teks)
                        $('#id_buku').val(response.id_buku);
                        $('#judul_buku').val(response.judul);
                        
                        $('#lokasi_rak').val(response.rak); 
                        $('#val_lokasi_rak').val(response.rak); 
                        
                        $('#tampil_foto_buku').attr('src', response.foto_buku); 
                        $('#val_foto_buku').val(response.foto_buku);           
                        
                        $('#jml_pinjam_awal').val(response.jml_awal);
                        $('#sisa_buku').val(response.sisa);
                        
                        $('#jml_kembali').val(response.sisa).attr('max', response.sisa);
                        
                        hitungDenda(response.tgl_jatuh_tempo, response.denda_perhari);
                        
                        $('#identitas-kosong').hide();
                        $('#box-info-pinjaman').fadeIn();
                        $('#btn-save-kembali').prop('disabled', false);

                    } else if (response.status === 'lunas') {
                        Swal.fire({
                            icon: 'info',
                            title: 'Sudah Lunas',
                            text: 'Transaksi ini sudah lunas dikembalikan sebelumnya.'
                        });
                        $('#box-info-pinjaman').hide();
                        $('#identitas-kosong').show();
                    } else {
                        alert("Data tidak ditemukan atau terjadi kesalahan server.");
                    }
                }
            });
        }
        }

        function hitungDenda(tgl_jatuh_tempo, denda_perhari) {
            console.log("=== DEBUG HITUNG DENDA ===");
            console.log("Teks Jatuh Tempo Dari DB:", tgl_jatuh_tempo);
            console.log("Tarif Denda Dari DB:", denda_perhari);

            var tgl_skrg = new Date();
            tgl_skrg.setHours(0, 0, 0, 0);
            
            var denda_per_hari_angka = parseInt(denda_perhari) || 0;

            if (!tgl_jatuh_tempo) {
                console.error("Error: Variabel tgl_jatuh_tempo kosong atau undefined!");
                $('#denda').val(0);
                $('#label_denda').text("Denda (Data Tanggal Error)");
                return;
            }

            var format_tgl_aman = tgl_jatuh_tempo.replace(/-/g, "/");
            var tgl_tempo = new Date(format_tgl_aman);
            tgl_tempo.setHours(0, 0, 0, 0);
            
            console.log("Tanggal Sekarang (Parsed):", tgl_skrg.toString());
            console.log("Tanggal Batas Tempo (Parsed):", tgl_tempo.toString());

            if (tgl_skrg.getTime() > tgl_tempo.getTime()) {
                var selisih_ms = tgl_skrg.getTime() - tgl_tempo.getTime();
                var selisih_hari = Math.floor(selisih_ms / (1000 * 60 * 60 * 24));
                
                var total_denda = selisih_hari * denda_per_hari_angka;
                
                console.log("Terlambat:", selisih_hari, "Hari");
                console.log("Total Denda Kalkulasi:", total_denda);

                $('#denda').val(total_denda);
                $('#label_denda').text("Denda Terlambat (" + selisih_hari + " Hari)"); 
            } else {
                console.log("Status: Tepat waktu / Belum jatuh tempo.");
                $('#denda').val(0);
                $('#label_denda').text("Denda");
            }
            console.log("==========================");
        }

        function cekValidasiTombol() {
            var idAnggota = $('#Id_anggota_pilih').val();
            var idBuku = $('#Id_buku_pilih').val();
            
            if ($('#Id_buku_pilih').length > 0) {
                if (idAnggota != "" && idAnggota != null && idBuku != "" && idBuku != null) {
                    $('#btn-save').prop('disabled', false);
                } else {
                    $('#btn-save').prop('disabled', true);
                }
            } else {
                if (idAnggota != "" && idAnggota != null) {
                    $('#btn-save').prop('disabled', false);
                } else {
                    $('#btn-save').prop('disabled', true);
                }
            }
        }

        // Catatan notifikasi agar awet selama halaman tidak di-refresh
        var notifSudahTampil = []; 

        function tampilkanToastrNotif(id, nama, keperluan, tipe) {
            let kunciNotif = id + tipe;
            if (notifSudahTampil.includes(kunciNotif)) {
                return; 
            }
            
            notifSudahTampil.push(kunciNotif);

            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "15000",
                "extendedTimeOut": "5000",
                "preventDuplicates": true
            };

            let tombolHTML = '';
            // Tombol Izinkan/Tolak HANYA muncul jika tipenya BUKAN 'Peringatan'
            if (tipe !== 'Peringatan') {
                tombolHTML = `
                    <div class="mt-2 text-right">
                        <button class="btn btn-xs btn-light text-success" onclick="prosesAksiIzin('${id}', 'izinkan', '${tipe}')">
                            <i class="fas fa-check"></i>
                        </button>
                        <button class="btn btn-xs btn-light text-danger" onclick="prosesAksiIzin('${id}', 'tolak', '${tipe}')">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
            }

            // Menyusun isi pesan html
            let pesan = `<b>${nama}</b><br><i>${keperluan}</i>${tombolHTML}`;
            
            // LOGIKA PEMILIHAN WARNA & JUDUL TOAST
            if (tipe === 'Peminjaman') { 
                toastr.success(pesan, "Izin Pinjam Baru"); 
            } else if (tipe === 'Pengembalian') { 
                toastr.error(pesan, "Izin Kembali Baru"); 
            } else if (tipe === 'Peringatan') {
                // Menggunakan TOASTR WARNING untuk warna KUNING JINGGA sesuai request
                toastr.warning(pesan, "Peringatan Jatuh Tempo");
            } else { 
                toastr.info(pesan, "Izin Kunjungan Baru"); 
            }

            // KIRIM LOG KE DATABASE AGAR NOTIFIKASI TIDAK MUNCUL LAGI SETELAH REFRESH
            $.ajax({
                url: 'halaman_chat/penerima_notif.php',
                type: 'POST',
                data: { id: id, tipe: tipe },
                success: function(response) {
                    // Log berhasil masuk ke tabel notif_penerima
                    console.log('Notif ' + tipe + ' dengan ID ' + id + ' berhasil dicatat.');
                }
            });
        }


        // ================================================================
        // B. LOGIKA EVENT & INISIALISASI (JQuery Document Ready Utama)
        // ================================================================
        $(function () {
            
            // === 0. LOGIKA CEK NOTIFIKASI TOASTR ===
            function cekNotifikasiLoop() {
                $.ajax({
                    url: 'halaman_chat/notif_proses.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function(res) {
                        if (res.ada_data) {
                            res.data.forEach(function(item) {
                                // Mengamankan nilai tipe: mengambil dari item.tipe atau fallback ke item.asal
                                let tipeData = item.tipe || item.asal || '';
                                
                                // Pastikan variabel tipeData dilempar dengan benar ke parameter ke-4
                                tampilkanToastrNotif(item.id, item.nama, item.keperluan, tipeData);
                            });
                        }
                    },
                    complete: function() {
                        setTimeout(cekNotifikasiLoop, 15000); 
                    }
                });
            }

            // Jalankan loop notifikasi langsung
            cekNotifikasiLoop();

            // === 1. INISIALISASI SELECT2 ===
            $('.select2').select2({
                theme: 'bootstrap4',
                placeholder: "--- Ketik Nama atau ID Anggota di sini ---",
                allowClear: true,
                width: '100%',
                language: {
                    noResults: function() {
                        return "Data tidak ditemukan... Periksa ejaan Anda";
                    }
                }
            }).on('select2:open', function() {
                document.querySelector('.select2-search__field').focus();
            });

            // =========================================================================
            // 2. RUNNING DATATABLES CONFIGURATION
            // =========================================================================
            $(".table").each(function () {
                var currentTableId = "#" + $(this).attr('id');
                
                if (
                    currentTableId === "#tabelBuku" ||
                    currentTableId === "#tabelSiswa" ||
                    currentTableId === "#tabelGuru" ||
                    currentTableId === "#tabelKunjungan" ||
                    currentTableId === "#tabelPinjam" ||
                    currentTableId === "#tabelPengembalian"
                ) {
                    var table = $(currentTableId).DataTable({
                        "responsive": false,
                        "scrollX": true,
                        "lengthChange": true,
                        "autoWidth": false,
                        "buttons": [
                            {
                                text: '<i class="fas fa-filter"></i> Filter Cetak',
                                className: 'btn btn-warning mr-1 text-white',
                                action: function (e, dt, node, config) {
                                    $('.filter-section').addClass('d-none');
                                    $('#sub-filter-denda').addClass('d-none'); // Sembunyikan denda secara default
                                    $('.filter-input').val('');
                                    $('#current-active-table').val(currentTableId);
                                    
                                    if (currentTableId === "#tabelBuku") {
                                        $('#filter-group-buku').removeClass('d-none');
                                    } else if (currentTableId === "#tabelSiswa") {
                                        $('#filter-group-siswa').removeClass('d-none');
                                    } else if (currentTableId === "#tabelGuru") {
                                        $('#filter-group-guru').removeClass('d-none');
                                    } else if (
                                        currentTableId === "#tabelKunjungan" ||
                                        currentTableId === "#tabelPinjam" ||
                                        currentTableId === "#tabelPengembalian"
                                    ) {
                                        $('#filter-group-transaksi').removeClass('d-none');
                                        
                                        // Tampilkan input tambahan denda KHUSUS tabel pengembalian
                                        if (currentTableId === "#tabelPengembalian") {
                                            $('#sub-filter-denda').removeClass('d-none');
                                        }
                                    }
                                    $('#modalFilterTanggal').modal('show');
                                }
                            },
                            {
                                extend: 'excel',
                                text: '<i class="fas fa-file-excel"></i> Excel',
                                className: 'btn btn-info mr-1',
                                exportOptions: {
                                    columns: ':visible:not(.no-export):not(.no-excel)'
                                }
                            },
                            {
                                extend: 'pdf',
                                text: '<i class="fas fa-file-pdf"></i> PDF',
                                className: 'btn btn-danger mr-1',
                                exportOptions: {
                                    columns: ':visible:not(.no-export):not(.no-pdf)'
                                }
                            },
                            {
                                extend: 'print',
                                text: '<i class="fas fa-print"></i> Print',
                                className: 'btn btn-secondary',
                                exportOptions: {
                                    columns: ':visible:not(.no-export)',
                                    stripHtml: false
                                },
                                customize: function (win) {
                                    $(win.document.body).find('table img').css({
                                        'width': '100px',
                                        'height': '120px',
                                        'object-fit': 'cover'
                                    });
                                }
                            }
                        ],
                        "language": {
                            "search": "",
                            "searchPlaceholder": "Cari data " + currentTableId.replace('#tabel', '') + "...",
                            "lengthMenu": "Tampilkan _MENU_ baris data :",
                            "info": "Menampilkan _START_ sampai _END_ baris data dari _TOTAL_ data",
                            "paginate": {
                                "next": "Next",
                                "previous": "Previous"
                            }
                        }
                    });

                    // Memindahkan tombol ekspor ke kontainer eksternal
                    table.buttons().container().appendTo('#tempat-tombol-export');
                    
                    // Merapikan tampilan kolom pencarian default DataTables
                    var filterContainer = $(currentTableId + '_filter');
                    filterContainer.contents().filter(function () {
                        return this.nodeType === 3;
                    }).remove();
                    
                    var inputSearch = filterContainer.find('input').addClass('form-control');
                    inputSearch.after('<button class="btn btn-primary" type="button" style="height: 38px; margin-left: 5px;"><i class="fas fa-search"></i></button>');
                    filterContainer.addClass('d-flex justify-content-end align-items-center');
                }
            });

            // =========================================================================
            // 3. LOGIKA FILTER CUSTOM DATATABLES (MODAL)
            // =========================================================================
            $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                    var activeTableId = $('#current-active-table').val();
                    var currentTableId = "#" + settings.sTableId;
                    
                    if (activeTableId !== currentTableId) {
                        return true;
                    }

                    // --- 3.A FILTER TABEL BUKU ---
                    if (currentTableId === "#tabelBuku") {
                        var idBuku = parseFloat(data[1]) || 0;
                        var thnTerbit = parseFloat(data[6]) || 0;
                        var bidang = data[7] || "";
                        var rak = data[8] || "";
                        
                        var idStart = parseFloat($('#buku-id-start').val());
                        var idEnd = parseFloat($('#buku-id-end').val());
                        var thnStart = parseFloat($('#buku-thn-start').val());
                        var thnEnd = parseFloat($('#buku-thn-end').val());
                        var fBidang = $('#buku-bidang').val();
                        var fRak = $('#buku_rak').val();

                        if (!isNaN(idStart) && idBuku < idStart) return false;
                        if (!isNaN(idEnd) && idBuku > idEnd) return false;
                        if (!isNaN(thnStart) && thnTerbit < thnStart) return false;
                        if (!isNaN(thnEnd) && thnTerbit > thnEnd) return false;
                        if (fBidang !== "" && bidang.toLowerCase().indexOf(fBidang.toLowerCase()) === -1) return false;
                        if (fRak !== "" && rak.toLowerCase().indexOf(fRak.toLowerCase()) === -1) return false;
                        return true;
                    }

                    // --- 3.B FILTER TABEL SISWA ---
                    if (currentTableId === "#tabelSiswa") {
                        var kelas = data[7] || "";
                        var jurusan = data[8] || "";
                        var fKelas = $('#siswa-kelas').val();
                        var fJurusan = $('#siswa-jurusan').val();

                        if (fKelas !== "" && kelas.toLowerCase().indexOf(fKelas.toLowerCase()) === -1) return false;
                        if (fJurusan !== "" && jurusan.toLowerCase().indexOf(fJurusan.toLowerCase()) === -1) return false;
                        return true;
                    }

                    // --- 3.C FILTER TABEL GURU ---
                    if (currentTableId === "#tabelGuru") {
                        var idGuru = parseFloat(data[2]) || 0;
                        var idGuruStart = parseFloat($('#guru-id-start').val());
                        var idGuruEnd = parseFloat($('#guru-id-end').val());

                        if (!isNaN(idGuruStart) && idGuru < idGuruStart) return false;
                        if (!isNaN(idGuruEnd) && idGuru > idGuruEnd) return false;
                        return true;
                    }

                    // --- 3.D FILTER TABEL TRANSAKSI (KUNJUNGAN, PINJAM, PENGEMBALIAN) ---
                    if (
                        currentTableId === "#tabelKunjungan" ||
                        currentTableId === "#tabelPinjam" ||
                        currentTableId === "#tabelPengembalian"
                    ) {
                        // A. Penarikan Data dari Baris Tabel (Sesuai Konfigurasi Indeks Anda)
                        var rawDate = "";
                        if (currentTableId === "#tabelKunjungan") rawDate = data[3];
                        if (currentTableId === "#tabelPinjam") rawDate = data[3];
                        if (currentTableId === "#tabelPengembalian") rawDate = data[13];
                        
                        // Jenis Anggota (Berdasarkan hitungan indeks ke-6 dari Anda)
                        var jenisAnggotaBaris = data[6] || "";
                        
                        // Denda (Berdasarkan hitungan indeks ke-15 khusus pengembalian)
                        var dendaBaris = 0;
                        if (currentTableId === "#tabelPengembalian" && data[15]) {
                            // 1. Hapus semua karakter kecuali angka (titik ribuan dan Rp akan terhapus total)
                            var angkaBersih = data[15].replace(/[^0-9]/g, ""); 
                            
                            // 2. Ubah string angka murni menjadi tipe data float/integer
                            dendaBaris = parseFloat(angkaBersih) || 0;
                        }

                        // B. Pengambilan Nilai dari Input Filter Modal
                        var minDate = $('#min-date').val();
                        var maxDate = $('#max-date').val();
                        var fJenisAnggota = $('#jenis-anggota').val();
                        var dendaStart = parseFloat($('#denda-start').val());
                        var dendaEnd = parseFloat($('#denda-end').val());

                        // C. Jalankan Sistem Filter Rentang Tanggal
                        if (rawDate) {
                            var match = rawDate.match(/(\d{4}-\d{2}-\d{2})/);
                            if (match) {
                                var rowDate = match[1];
                                if (minDate !== "" && rowDate < minDate) return false;
                                if (maxDate !== "" && rowDate > maxDate) return false;
                            }
                        }

                        // D. Jalankan Sistem Filter Jenis Anggota (Siswa, Guru, Kelas)
                        if (fJenisAnggota !== "") {
                            if (jenisAnggotaBaris.toLowerCase().trim() !== fJenisAnggota.toLowerCase().trim()) {
                                return false;
                            }
                        }

                        // E. Jalankan Sistem Filter Nominal Rentang Denda (Khusus Pengembalian)
                        if (currentTableId === "#tabelPengembalian") {
                            if (!isNaN(dendaStart) && dendaBaris < dendaStart) return false;
                            if (!isNaN(dendaEnd) && dendaBaris > dendaEnd) return false;
                        }

                        return true; // Lolos seluruh filter, baris ditampilkan
                    }

                    return true;
                }
            );

            // =========================================================================
            // 4. ACTION TRIGGER UNTUK TOMBOL MODAL
            // =========================================================================
            $(document).ready(function() {
                // Jalankan draw() ulang saat filter diterapkan
                $('#btn-terapkan-filter').on('click', function () {
                    var activeTableId = $('#current-active-table').val();
                    if (activeTableId) {
                        $(activeTableId).DataTable().draw();
                    }
                    $('#modalFilterTanggal').modal('hide');
                });

                // Reset isi inputan dan draw() ulang ke posisi default awal saat direset
                $('#btn-reset-filter').on('click', function () {
                    $('.filter-input').val('');
                    var activeTableId = $('#current-active-table').val();
                    if (activeTableId) {
                        $(activeTableId).DataTable().draw();
                    }
                    $('#modalFilterTanggal').modal('hide');
                });
            });

            // === 5. LOGIKA PREVIEW FOTO ===
            $(document).on('change', '#Foto', function(e) {
                var fileName = e.target.files[0].name;
                $(this).next('.custom-file-label').html(fileName);
                const [file] = this.files;
                if (file) {
                    const preview = document.getElementById('preview');
                    if(preview) {
                        preview.src = URL.createObjectURL(file);
                        preview.style.display = 'block';
                    }
                }
            });

            // === 6. FIX TOMBOL TOGGLE ===
            $('.btn-group-toggle label').on('click', function() {
                $(this).addClass('active').siblings().removeClass('active');
            });

            // === 7. LOGIKA AJAX GLOBAL ANGGOTA ===
            $(document).on('change', '#Id_anggota_pilih', function() {
                var id = $(this).val();
                var tipe = $(this).find(':selected').data('tipe');

                if (id != "") {
                    $.ajax({
                        url: 'proses_Admin/ajak/ambil_data_anggota.php',
                        type: 'POST',
                        data: {id: id, tipe: tipe},
                        dataType: 'text',
                        success: function(data) {
                            try {
                                var JSONdata = JSON.parse(data.substring(data.indexOf('{')));
                                if (JSONdata.nama) {
                                    $('#identitas-kosong').hide();
                                    $('#box-info-pintar').fadeIn();
                                    
                                    $('#val_jenis').val(tipe);
                                    $('#val_nama').val(JSONdata.nama);
                                    $('#val_detail').val(JSONdata.info_kamuflase);
                                    $('#val_foto_db').val(JSONdata.foto);
                                    $('#tampil_foto').attr('src', JSONdata.foto);

                                    var inputJumlah = $('input[name="Jumlah"]');
                                    if (inputJumlah.length > 0) { 
                                        if (tipe == "Kelas") {
                                            inputJumlah.prop('readonly', false).removeClass('bg-light').val(1);
                                        } else {
                                            inputJumlah.prop('readonly', true).addClass('bg-light').val(1);
                                        }
                                    }
                                    cekValidasiTombol();
                                }
                            } catch (e) {
                                console.error("Gagal memproses JSON Anggota: " + e);
                            }
                        }
                    });
                } else {
                    $('#box-info-pintar').hide();
                    $('#identitas-kosong').show();
                    cekValidasiTombol();
                }
            });
            
            // === 8. LOGIKA AJAX GLOBAL BUKU ===
            $(document).on('change', '#Id_buku_pilih', function() {
                var id_buku = $(this).val();

                if (id_buku != "") {
                    $.ajax({
                        url: 'proses_Admin/ajak/ambil_data_buku.php',
                        type: 'POST',
                        data: {id: id_buku},
                        dataType: 'text',
                        success: function(data) {
                            try {
                                var JSONdata = JSON.parse(data.substring(data.indexOf('{')));
                                if (JSONdata.judul) {
                                    $('#box-info-buku').fadeIn();
                                    $('#val_judul_buku').val(JSONdata.judul);
                                    $('#val_rak_buku').val(JSONdata.rak);
                                    $('#val_foto_buku_db').val(JSONdata.foto);
                                    $('#tampil_foto_buku').attr('src', JSONdata.foto);
                                    
                                    cekValidasiTombol();
                                }
                            } catch (e) {
                                console.error("Gagal memproses JSON Buku: " + e);
                            }
                        }
                    });
                } else {
                    $('#box-info-buku').hide();
                    cekValidasiTombol();
                }
            });
        }); // <--- PENUTUP UTAMA JQUERY DOCUMENT READY (SELESAI)
</script>
</body>
</html>
                                                                                                                                                                                                                                                                                                                                           
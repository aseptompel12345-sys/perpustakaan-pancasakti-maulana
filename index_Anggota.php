<?php
    include "config/koneksi.php";

    // 1. Proteksi Login
    if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
        header("Location: login_from.php");
        exit();
    }

    // 2. Proteksi Role (Hanya Role Anggota yang boleh masuk)
    if ($_SESSION['role'] !== 'anggota') {
        echo "<script>alert('Akses Ditolak! Anda bukan Anggota.'); window.location.href='login_from.php';</script>";
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Digital | SMKN 1 Kertajati</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    
    <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <style>
    /* ==========================================================
      CSS PREMIUM UNTUK ANGGOTA (IDENTIK DENGAN ADMIN)
      ========================================================== */
    
    /* 1. AREA PENCARIAN (Search Bar) - Desktop */
    .dataTables_filter {
      float: right;
      margin-bottom: 25px; /* DITAMBAH: Jarak bawah agar tidak mepet tabel */
      margin-top: 10px;    /* DITAMBAH: Jarak atas */
    }

    .dataTables_filter label {
      display: flex !important;
      align-items: center;
      justify-content: flex-end;
      gap: 0; /* Menghilangkan gap default label */
    }

    .dataTables_filter input {
      height: 38px !important;
      border-radius: 4px 0 0 4px !important; /* Melengkung kiri saja */
      border: 1.5px solid #007bff;
      margin-left: 0 !important;
      padding: 10px 15px !important;
      transition: width 0.4s ease-in-out, border-color 0.4s; 
    }

    @media (min-width: 769px) {
      .dataTables_filter input {
        width: 200px !important; /* Ukuran default sedikit lebih lebar */
      }
      .dataTables_filter input:focus {
        width: 350px !important; /* Melebar saat diklik */
      }
    }

    .dataTables_filter input:focus {
      border-color: #007bff;
      outline: none;
      box-shadow: 0 0 8px rgba(0,123,255,0.25);
    }

    /* Tombol Search Biru (Kaca Pembesar) */
    .dataTables_filter .btn-primary {
      height: 38px !important;
      border-radius: 0 4px 4px 0 !important; /* Melengkung kanan saja */
      padding: 0 15px;
      border-left: none;
      display: flex;
      align-items: center;
      justify-content: center;
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

    /* 3. PENGATURAN FOTO TABEL (VERSI ADMIN: ANTI POTONG & ZOOM) */
    /* Menyamakan kolom pertama agar ukuran konsisten */
    #tabelKunjungan td:first-child, 
    #tabelPeminjaman td:first-child,
    #tabelPengembalian td:first-child {
      width: 120px !important;
      min-width: 120px !important;
      max-width: 120px !important;
      text-align: center;
    }

    #tabelKunjungan td img, 
    #tabelPeminjaman td img,
    #tabelPengembalian td img {
      width: 100px !important;   /* Sedikit lebih besar agar jelas */
      height: 120px !important;
      object-fit: cover;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.15);
      transition: transform 0.3s ease;
    }

    #tabelKunjungan td img:hover, 
    #tabelPeminjaman td img:hover,
    #tabelPengembalian td img:hover {
      transform: scale(1.15); /* Efek zoom saat kursor di atas foto */
      position: relative;
      z-index: 999;           /* Supaya tidak tertutup baris lain */
      box-shadow: 0 8px 25px rgba(0,0,0,0.4);
    }

    /* 4. MERAPIKAN TABEL AGAR TIDAK BERANTAKAN */
    .table td, .table th {
      vertical-align: middle !important;
    }

    /* 5. SELECT2 PREMIUM (SAMA SEPERTI ADMIN) */
    .select2-container--bootstrap4 .select2-selection--single {
        border: 2px solid #ced4da !important;
        height: calc(2.25rem + 2px) !important;
    }

    .select2-container--bootstrap4.select2-container--focus .select2-selection {
        border-color: #28a745 !important; /* Warna hijau saat aktif */
    }

    .select2-search--dropdown .select2-search__field {
        border: 1px solid #007bff !important;
        background-color: #f8f9fa !important;
        border-radius: 4px !important;
        padding: 8px !important;
    }

    /* 6. STYLE KATALOG BUKU (DASHBOARD) */
    .card-buku {
      transition: all 0.3s ease;
      border: none;
      border-radius: 15px;
    }
    .card-buku:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 30px rgba(0,0,0,0.2) !important;
    }
    .img-sampul { height: 260px; object-fit: cover; }
    
    .wrapper { overflow-x: hidden; }
  </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__shake" src="foto/Logo.png" alt="Logo" height="200" width="200">
      <h1>Pancasakti</h1>
    </div>

    <?php include "layouts_Anggota/navbar.php"; ?>
    <?php include "layouts_Anggota/sidebar.php"; ?>
  </div>
  
  <?php include "layouts_Anggota/content.php"; ?>

  <script src="plugins/jquery/jquery.min.js"></script>
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="dist/js/adminlte.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
  <script src="plugins/select2/js/select2.full.min.js"></script>

  <script>
    $(function () {
      // === A. LOGIKA DATATABLES & SELECT2 (KODE ASLI ANDA) ===
      $('.select2bs4').select2({ theme: 'bootstrap4', placeholder: '--- Cari Judul Buku ---' });

      $(".table-custom, #tabelKunjungan, #tabelPeminjaman, #tabelPengembalian").each(function() {
          var currentTableId = "#" + $(this).attr('id');
          var table = $(this).DataTable({
            "responsive": false, 
            "scrollX": true,
            "lengthChange": true, 
            "autoWidth": false,
            "buttons": [
                { extend: 'excel', text: '<i class="fas fa-file-excel"></i> Excel', className: 'btn btn-info btn-sm mr-1' },
                { extend: 'pdf', text: '<i class="fas fa-file-pdf"></i> PDF', className: 'btn btn-danger btn-sm mr-1' },
                { extend: 'print', text: '<i class="fas fa-print"></i> Print', className: 'btn btn-secondary btn-sm' }
            ],
            "language": {
                "search": "",
                "searchPlaceholder": "Cari Riwayat " + currentTableId.replace('#tabel', '') + "...",
                "lengthMenu": "Tampil _MENU_",
                "info": "Data _START_ - _END_ dari _TOTAL_",
                "paginate": { "next": "Next", "previous": "Prev" }
            }
          });

          table.buttons().container().appendTo('#tempat-tombol-export');
          var filterContainer = $(currentTableId + '_filter');
          filterContainer.contents().filter(function() { return this.nodeType === 3; }).remove();
          var inputSearch = filterContainer.find('input').addClass('form-control');
          inputSearch.after('<button class="btn btn-primary" type="button" style="height: 38px; margin-left: 5px;"><i class="fas fa-search"></i></button>');
          filterContainer.addClass('d-flex justify-content-end align-items-center');
      });

      // === B. LOGIKA AMBIL DATA ANGGOTA OTOMATIS (AJAX) ===
      var idAgt = "<?php echo isset($_SESSION['Id_anggota']) ? $_SESSION['Id_anggota'] : ''; ?>";
      var tipeAgt = "<?php echo isset($_SESSION['jenis_anggota']) ? $_SESSION['jenis_anggota'] : ''; ?>";

      if (idAgt !== "") {
          $.ajax({
              url: 'proses_Admin/ajak/ambil_data_anggota.php',
              type: 'POST',
              data: { id: idAgt, tipe: tipeAgt },
              dataType: 'json',
              success: function(response) {
                  $('#auto_nama').val(response.nama);
                  $('#auto_jenis').val(tipeAgt);
                  $('#auto_detail').val(response.info_kamuflase);
                  $('#auto_foto').val(response.foto);
                  console.log("Data profil berhasil dimuat.");
              }
          });
      }

      // === C. LOGIKA AJAX AMBIL DATA BUKU ===
      $(document).on('change', '#Id_buku_pilih_agt', function() {
          var id_buku = $(this).val();
          // Ambil variabel tipeAgt yang sudah didefinisikan di bagian B
          var tipeAgt = "<?php echo $_SESSION['jenis_anggota']; ?>"; 

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
                              $('#box-info-buku-agt').fadeIn();
                              $('#val_judul_buku_agt').val(JSONdata.judul);
                              $('#val_rak_buku_agt').val(JSONdata.rak);
                              $('#val_foto_buku_db_agt').val(JSONdata.foto);
                              $('#tampil_foto_buku_agt').attr('src', JSONdata.foto);
                              
                              // --- LOGIKA KUNCI JUMLAH ---
                              if (tipeAgt === "Kelas") {
                                  // Jika Kelas, boleh edit jumlah dan tidak ada batas max 1
                                  $('#input_jumlah_pinjam').prop('readonly', false);
                                  $('#input_jumlah_pinjam').attr('max', ''); 
                                  console.log("Akses Kelas: Jumlah pinjam boleh diubah.");
                              } else {
                                  // Jika Siswa/Guru, kunci di angka 1
                                  $('#input_jumlah_pinjam').val(1);
                                  $('#input_jumlah_pinjam').prop('readonly', true);
                                  $('#input_jumlah_pinjam').attr('max', 1);
                                  console.log("Akses Individu: Jumlah pinjam dikunci ke 1.");
                              }

                              $('#btn-ajukan-pinjam').prop('disabled', false);
                          }
                      } catch (e) {
                          console.error("Gagal memproses data buku: " + e);
                      }
                  }
              });
          } else {
              $('#box-info-buku-agt').hide();
              $('#btn-ajukan-pinjam').prop('disabled', true);
          }
      });

      // === D. LOGIKA AJAX AMBIL DATA PEMINJAMAN (SUDAH DISELARASKAN) ===
      $(document).on('click', '.btn-kembali-modal', function() {
          var idPeminjaman = $(this).data('id');

          $.ajax({
              url: 'proses_Admin/ajak/ambil_data_pinjaman.php', 
              type: 'POST',
              data: { id: idPeminjaman },
              dataType: 'json',
              success: function(response) {
                  if(response.status == 'success') {
                      $('#m_id_peminjaman').val(idPeminjaman);
                      $('#m_id_buku').val(response.id_buku);
                      $('#m_judul_buku').text(response.judul);
                      $('#m_lokasi_rak').html('<i class="fas fa-archive"></i> ' + response.rak);
                      $('#m_foto_buku').attr('src', response.foto_buku || 'dist/img/no-book.png');
                      
                      $('#m_jml_awal').val(response.jml_awal);
                      $('#m_sisa').val(response.sisa);
                      $('#m_jml_kembali').val(response.sisa); 
                      $('#m_jml_kembali').attr('max', response.sisa);

                      // --- SET STATUS AWAL ---
                      $('#m_status').val('Selesai');
                      $('#m_status').addClass('text-success').removeClass('text-warning');
                      $('#m_status_info').text('*Buku akan lunas dikembalikan.');

                      // =========================================================
                      // PINAL SINKRONISASI HITUNG DENDA DASHBOARD ANGGOTA
                      // =========================================================
                      var tgl_jatuh_tempo = response.tgl_jatuh_tempo; 
                      var denda_perhari   = parseInt(response.denda_perhari) || 0;

                      // Pengaman jika tanggal dari DB kosong atau bernilai default sistem
                      if (!tgl_jatuh_tempo || tgl_jatuh_tempo === "0000-00-00" || tgl_jatuh_tempo === "null") {
                          $('#m_denda').val(0);
                      } else {
                          // Ambil tanggal hari ini murni (Jam 00:00:00)
                          var tgl_skrg = new Date();
                          tgl_skrg.setHours(0, 0, 0, 0);

                          // Ubah format strip (-) jadi slash (/) agar aman di semua browser
                          var format_tgl_aman = tgl_jatuh_tempo.replace(/-/g, "/");
                          var tgl_tempo = new Date(format_tgl_aman);
                          tgl_tempo.setHours(0, 0, 0, 0);

                          // Cek jika hari ini telah melewati batas jatuh tempo
                          if (tgl_skrg.getTime() > tgl_tempo.getTime()) {
                              var selisih_ms = tgl_skrg.getTime() - tgl_tempo.getTime();
                              var selisih_hari = Math.floor(selisih_ms / (1000 * 60 * 60 * 24));
                              var total_denda = selisih_hari * denda_perhari;
                              
                              $('#m_denda').val(total_denda);
                              console.log("Anggota Terlambat: " + selisih_hari + " Hari. Denda: Rp " + total_denda);
                          } else {
                              $('#m_denda').val(0);
                          }
                      }

                      $('#modalKonfirmasiKembali').modal('show');
                  }
              }
          });
      });

      // --- LOGIKA OTOMATIS GANTI STATUS SAAT INPUT DIKETIK ---
      $(document).on('input', '#m_jml_kembali', function() {
          var sisa = parseInt($('#m_sisa').val()) || 0;
          var kembali = parseInt($(this).val()) || 0;
          
          // Validasi agar tidak melebihi sisa
          if (kembali > sisa) {
              Swal.fire('Peringatan', 'Jumlah kembali tidak boleh melebihi sisa pinjaman!', 'warning');
              $(this).val(sisa);
              kembali = sisa;
          }

          // Cek Status: Sebagian atau Selesai
          if(kembali < sisa && kembali > 0) {
              $('#m_status').val('Sebagian');
              $('#m_status').addClass('text-warning').removeClass('text-success');
              $('#m_status_info').html('<i class="fas fa-exclamation-triangle"></i> Masih ada sisa buku yang dipinjam.');
          } else if (kembali == sisa) {
              $('#m_status').val('Selesai');
              $('#m_status').addClass('text-success').removeClass('text-warning');
              $('#m_status_info').html('<i class="fas fa-check-circle"></i> Semua buku akan dikembalikan (Lunas).');
          } else {
              $('#m_status').val('Data Tidak Valid');
              $('#m_status').removeClass('text-success text-warning');
          }
      });
    });
</script>
</body>
</html>
<?php
    if (!isset($db)) {
        include "../config/koneksi.php"; 
    }

    // --- 1. LOGIKA PERIODE 3 BULANAN & TAHUN ---
    $bulan_sekarang = date('n'); 
    $tahun_sekarang = date('Y');

    if ($bulan_sekarang >= 1 && $bulan_sekarang <= 3) {
        $mulai = 1; $selesai = 3; $nama_periode = "Periode 1 (Jan - Mar)";
    } elseif ($bulan_sekarang >= 4 && $bulan_sekarang <= 6) {
        $mulai = 4; $selesai = 6; $nama_periode = "Periode 2 (Apr - Jun)";
    } elseif ($bulan_sekarang >= 7 && $bulan_sekarang <= 9) {
        $mulai = 7; $selesai = 9; $nama_periode = "Periode 3 (Jul - Sep)";
    } else {
        $mulai = 10; $selesai = 12; $nama_periode = "Periode 4 (Okt - Des)";
    }

    // Filter SQL untuk statistik periode aktif
    $filter_periode = " AND MONTH(Tgl_kunjungan) BETWEEN $mulai AND $selesai AND YEAR(Tgl_kunjungan) = '$tahun_sekarang'";

    // Query Statistik Utama (Periode Berjalan)
    $jml_siswa = mysqli_num_rows(mysqli_query($db, "SELECT * FROM kunjungan WHERE Jenis_anggota='Siswa' $filter_periode"));
    $jml_guru  = mysqli_num_rows(mysqli_query($db, "SELECT * FROM kunjungan WHERE Jenis_anggota='Guru' $filter_periode"));
    $jml_kelas = mysqli_num_rows(mysqli_query($db, "SELECT * FROM kunjungan WHERE Jenis_anggota='Kelas' $filter_periode"));

    // Fungsi Top 10 yang diperbaiki
    function getTop10($db, $jenis, $filter) {
        $labels = []; $data = [];
        // Gunakan Nama_pengunjung untuk semua kategori sesuai permintaan kamu
        $query = mysqli_query($db, "SELECT Nama_pengunjung as nama, COUNT(*) as jml 
                                    FROM kunjungan 
                                    WHERE Jenis_anggota='$jenis' $filter 
                                    GROUP BY Nama_pengunjung 
                                    ORDER BY jml DESC LIMIT 10");
        while($r = mysqli_fetch_array($query)){ 
            $labels[] = $r['nama']; 
            $data[] = (int)$r['jml']; 
        }
        return ['labels' => $labels, 'data' => $data];
    }

    $top_siswa = getTop10($db, 'Siswa', $filter_periode);
    $top_guru  = getTop10($db, 'Guru', $filter_periode);
    $top_kelas = getTop10($db, 'Kelas', $filter_periode);

    // Query Utama Tabel Riwayat (Semua Data)
    $query_tabel = mysqli_query($db, "SELECT * FROM kunjungan ORDER BY (Admin_pemberi_izin = 'Menunggu Izin') DESC, Id_kunjungan DESC");
?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-exchange-alt text-primary"></i> Daftar Transaksi Kunjungan</h1>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header d-flex flex-column flex-md-row align-items-md-center">

                        <!-- JUDUL -->
                        <h3 class="card-title flex-grow-1 text-left mb-2 mb-md-0">
                            <i class="fas fa-chart-pie mr-1"></i>
                            Statistik Pengunjung :
                            <span class="text-info font-weight-bold ml-md-2">
                                <?php echo $nama_periode; ?> <span class='text-secondary'> || </span> <?php echo $tahun_sekarang; ?>
                            </span>
                        </h3>

                        <!-- IKON & TOMBOL -->
                        <div class="card-tools d-flex align-items-center justify-content-center justify-content-md-end">

                            <button type="button" class="btn btn-sm btn-outline-primary mr-2 d-none" id="btn-reset-chart" onclick="resetCharts()">
                                <i class="fas fa-sync-alt"></i>
                            </button>

                            <button class="btn btn-tool mr-2" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>

                            <!-- INI PENTING supaya tab Donut jalan -->
                            <ul class="nav nav-pills ml-auto d-inline-flex">
                                <li class="nav-item">
                                    <a class="nav-link active btn-sm" href="#revenue-chart" data-toggle="tab">Bar</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link btn-sm" href="#sales-chart" data-toggle="tab">Donut</a>
                                </li>
                            </ul>

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content p-0">
                            <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px;">
                                <canvas id="revenue-chart-canvas" height="300"></canvas>
                            </div>
                            <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
                                <canvas id="sales-chart-canvas" height="300"></canvas>
                            </div>
                        </div>
                        <p class="text-muted small mt-2">* Klik pada batang grafik untuk melihat 10 besar pengunjung terajin.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header border-0">
                        <h3 class="card-title">Riwayat Seluruh Kunjungan Anggota</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <div id="tempat-tombol-export"></div>

                            <div class="d-flex" style="gap: 10px;">
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalHapusTransaksiKunjungan">
                                    <i class="fas fa-trash"></i> Delete All
                                </button>
    
                                <a href="?page=transaksi_kunjungan_from" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Tambah Transaksi
                                </a>
                            </div>
                        </div>

                        <table id="tabelKunjungan" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="no-export">No</th>
                                    <th>ID Kunjungan</th>
                                    <th class="no-excel no-pdf">Foto Pengunjung</th> 
                                    <th>Tanggal & Jam kunjungan</th> 
                                    <th>ID Anggota</th>
                                    <th>Nama Pengunjung</th> 
                                    <th>Jenis</th> 
                                    <th>Identitas (Detail)</th> 
                                    <th>Keperluan</th>
                                    <th class="no-export">Izin Admin</th>
                                    <th class="no-export">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $no = 1;
                                    while($data = mysqli_fetch_array($query_tabel)) { 
                                        $ja = $data['Jenis_anggota'];
                                        $warna_badge = ($ja == 'Siswa') ? 'info' : (($ja == 'Guru') ? 'warning' : 'success');
                                ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $data['Id_kunjungan']; ?></td>
                                    <td class="text-center">
                                        <?php 
                                            $path_foto = $data['Foto_kunjungan']; 
                                            $sumber_gambar = (!empty($path_foto)) ? $path_foto : "dist/img/avatar5.png";
                                        ?>
                                        <img src="<?php echo $sumber_gambar; ?>" 
                                            class="img-circle border shadow-sm" 
                                            onerror="this.onerror=null; this.src='dist/img/avatar5.png';">
                                    </td>
                                   <td class="text-center">
                                        <span class="font-weight-bold"><?php echo $data['Tgl_kunjungan']; ?></span><br>
                                        <small class="text-muted"><?php echo $data['Jam_kunjungan']; ?></small>
                                    </td>
                                    <td class="text-center"><?php echo $data['Id_anggota']; ?></td>
                                    <td><?php echo $data['Nama_pengunjung']; ?></td>
                                    <td class="text-center">
                                        <span class="badge badge-<?php echo $warna_badge; ?>"><?php echo $ja; ?></span>
                                    </td>
                                    <td><small><?php echo $data['Detail_identitas']; ?></small></td>
                                    <td><?php echo $data['Keperluan']; ?></td>
                                    <td class="text-center align-middle">
                                        <?php if ($data['Admin_pemberi_izin'] == 'Menunggu Izin') : ?>
                                            <span class="text-muted small"><i><i class="fas fa-hourglass-half mr-1"></i> Menunggu...</i></span>
                                        <?php else : ?>
                                            <!-- Tambahkan properti inline CSS agar teks terpotong rapi dengan titik-titik -->
                                            <span class="badge badge-success p-2" 
                                                style="display: inline-block; max-width: 100px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; vertical-align: middle;" 
                                                title="<?php echo $data['Admin_pemberi_izin']; ?>">
                                                <i class="fas fa-user-check mr-1"></i> <?php echo $data['Admin_pemberi_izin']; ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center align-middle">
                                        <a href="?page=transaksi_kunjungan_hapus&id=<?php echo $data['Id_kunjungan']; ?>" 
                                            class="btn btn-danger btn-sm hapus-link">
                                            <i class="fas fa-trash"></i>
                                        </a>                             
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- From Filter Delete All -->
<div class="modal fade" id="modalHapusTransaksiKunjungan">
  <div class="modal-dialog modal-lg"> <div class="modal-content">
      <div class="modal-header bg-danger">
        <h4 class="modal-title"><i class="fas fa-trash-alt"></i> Filter Delete's Data Kunjungan</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="proses_Admin/hapus_masal/transaksi_kunjungan_proses.php" method="POST">
        <div class="modal-body">
          <p class="text-muted">Gunakan filter ini untuk membersihkan riwayat kunjungan yang sudah kadaluarsa.</p>
          
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Dari Tanggal :</label>
                <input type="date" name="tgl_mulai" class="form-control">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Sampai Tanggal :</label>
                <input type="date" name="tgl_selesai" class="form-control">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Dari Jam :</label>
                <input type="time" name="jam_mulai" class="form-control">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Sampai Jam :</label>
                <input type="time" name="jam_selesai" class="form-control">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Jenis Anggota :</label>
                <select name="jenis_anggota" class="form-control">
                  <option value="">-- Pilih Jenis Anggota --</option>
                  <option value="Siswa">Siswa</option>
                  <option value="Guru">Guru</option>
                  <option value="Kelas">Kelas</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Keperluan :</label>
                <select name="keperluan" class="form-control">
                    <option value="">-- Pilih Keperluan --</option>
                    <option value="Membaca">Membaca</option>
                    <option value="Berdiskusi">Berdiskusi</option>
                    <option value="Mengerjakan Tugas">Mengerjakan Tugas</option>
                    <option value="Meminjam Buku">Meminjam Buku</option>
                    <option value="Mengembalikan Buku">Mengembalikan Buku</option>
                    <option value="Lain-lain">Lain-lain</option>
                </select>
              </div>
            </div>
          </div>

          <hr>
          <div class="form-group">
            <label class="text-danger"><i>Konfirmasi Password Admin :</i></label>
            <input type="password" name="password_konfirmasi" class="form-control" required placeholder="Masukkan password akun admin Anda untuk verifikasi">
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="submit" name="confirm_delete_kunjungan" class="btn btn-danger" onclick="return confirm('Data kunjungan yang dipilih akan dihapus permanen. Lanjutkan?')">Mulai Delete Data</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Semua kode jQuery dimasukkan ke dalam blok ini agar simbol '$' sudah dikenali
        $(function () {
            'use strict'
            
            // --- BAGIAN 1: INISIALISASI GRAFIK ---
            var barCtx = $('#revenue-chart-canvas').get(0).getContext('2d');
            var donutCtx = $('#sales-chart-canvas').get(0).getContext('2d');
            var judulTeks = $('#judul-statistik');
            var btnReset = $('#btn-reset-chart');

            var sepuluhWarna = [
                '#17a2b8', '#ffc107', '#28a745', '#dc3545', '#6610f2', 
                '#e83e8c', '#fd7e14', '#20c997', '#007bff', '#6c757d'
            ];

            var dataUtama = {
                labels: ['Siswa', 'Guru', 'Kelas'],
                datasets: [{
                    data: [<?php echo (int)$jml_siswa; ?>, <?php echo (int)$jml_guru; ?>, <?php echo (int)$jml_kelas; ?>],
                    backgroundColor: ['#17a2b8', '#ffc107', '#28a745']
                }]
            };

            var dataDetail = {
                'Siswa': { labels: <?php echo json_encode($top_siswa['labels']); ?>, data: <?php echo json_encode($top_siswa['data']); ?>, color: '#17a2b8' },
                'Guru':  { labels: <?php echo json_encode($top_guru['labels']); ?>, data: <?php echo json_encode($top_guru['data']); ?>, color: '#ffc107' },
                'Kelas': { labels: <?php echo json_encode($top_kelas['labels']); ?>, data: <?php echo json_encode($top_kelas['data']); ?>, color: '#28a745' }
            };

            var chartBatang = new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: dataUtama.labels,
                    datasets: [{ label: 'Jumlah', data: dataUtama.datasets[0].data, backgroundColor: dataUtama.datasets[0].backgroundColor }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false, legend: { display: false },
                    onClick: function(e, items) { updateKeDetail(items); },
                    scales: { yAxes: [{ ticks: { beginAtZero: true, stepSize: 1 } }] }
                }
            });

            var chartDonut = new Chart(donutCtx, {
                type: 'doughnut',
                data: {
                    labels: dataUtama.labels,
                    datasets: [{ data: dataUtama.datasets[0].data, backgroundColor: dataUtama.datasets[0].backgroundColor }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    onClick: function(e, items) { updateKeDetail(items); }
                }
            });

            function updateKeDetail(items) {
                if (items.length > 0) {
                    var index = items[0]._index;
                    var kategori = chartBatang.data.labels[index]; 
                    if (dataDetail[kategori]) {
                        judulTeks.text(kategori + " Terajin ");
                        btnReset.removeClass('d-none');
                        chartBatang.data.labels = dataDetail[kategori].labels;
                        chartBatang.data.datasets[0].data = dataDetail[kategori].data;
                        chartBatang.data.datasets[0].backgroundColor = dataDetail[kategori].color;
                        chartBatang.update();
                        chartDonut.data.labels = dataDetail[kategori].labels;
                        chartDonut.data.datasets[0].data = dataDetail[kategori].data;
                        chartDonut.data.datasets[0].backgroundColor = sepuluhWarna; 
                        chartDonut.update();
                    }
                }
            }

            window.resetCharts = function() {
                judulTeks.text("Statistik Pengunjung : ");
                btnReset.addClass('d-none');
                chartBatang.data.labels = dataUtama.labels;
                chartBatang.data.datasets[0].data = dataUtama.datasets[0].data;
                chartBatang.data.datasets[0].backgroundColor = dataUtama.datasets[0].backgroundColor;
                chartBatang.update();
                chartDonut.data.labels = dataUtama.labels;
                chartDonut.data.datasets[0].data = dataUtama.datasets[0].data;
                chartDonut.data.datasets[0].backgroundColor = dataUtama.datasets[0].backgroundColor;
                chartDonut.update();
            }

            // --- BAGIAN 2: LOGIKA KONFIRMASI HAPUS (Sudah Digabung) ---
            // Kita taruh di sini agar simbol '$' pasti terbaca oleh browser
            $(document).on('click', '.hapus-link', function(e) {
                e.preventDefault(); 
                const href = $(this).attr('href');

                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Data kunjungan ini akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true 
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = href;
                    }
                });
            });
        });
    });
</script>
<?php
    if (!isset($db)) {
        include "../config/koneksi.php"; 
    }

    // --- 1. LOGIKA PERIODE & TAHUN ---
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

    // Filter menggunakan Tgl_kembali sesuai struktur tabelmu
    $filter_periode = " AND MONTH(Tgl_kembali) BETWEEN $mulai AND $selesai AND YEAR(Tgl_kembali) = '$tahun_sekarang'";

    // --- 2. QUERY STATISTIK PENGEMBALIAN (Dari Tabel pengembalian) ---
    $jml_siswa = mysqli_num_rows(mysqli_query($db, "SELECT * FROM pengembalian WHERE Jenis_anggota='Siswa' $filter_periode"));
    $jml_guru  = mysqli_num_rows(mysqli_query($db, "SELECT * FROM pengembalian WHERE Jenis_anggota='Guru' $filter_periode"));
    $jml_kelas = mysqli_num_rows(mysqli_query($db, "SELECT * FROM pengembalian WHERE Jenis_anggota='Kelas' $filter_periode"));

    function getTopPengembali($db, $jenis, $filter) {
        $labels = []; $data = [];
        $query = mysqli_query($db, "SELECT Nama_peminjam as nama, COUNT(*) as jml 
                                    FROM pengembalian 
                                    WHERE Jenis_anggota='$jenis' $filter 
                                    GROUP BY Nama_peminjam 
                                    ORDER BY jml DESC LIMIT 10");
        if($query){
            while($r = mysqli_fetch_array($query)){ 
                $labels[] = $r['nama']; 
                $data[] = (int)$r['jml']; 
            }
        }
        return ['labels' => $labels, 'data' => $data];
    }

    $top_siswa = getTopPengembali($db, 'Siswa', $filter_periode);
    $top_guru  = getTopPengembali($db, 'Guru', $filter_periode);
    $top_kelas = getTopPengembali($db, 'Kelas', $filter_periode);

    // Query Utama Tabel Riwayat
    $query_tabel = mysqli_query($db, "SELECT * FROM pengembalian ORDER BY (Admin_pemberi_izin = 'Menunggu Izin') DESC, Id_pengembalian DESC");
?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-exchange-alt text-info"></i> Daftar Transaksi Pengembalian</h1>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header d-flex flex-column flex-md-row align-items-md-center">
                        <h3 class="card-title flex-grow-1 text-left mb-2 mb-md-0" id="judul-statistik">
                            <i class="fas fa-chart-pie mr-1"></i>
                            Statistik Pengembalian :
                            <span class="text-info font-weight-bold ml-md-2">
                                <?php echo $nama_periode; ?> || <?php echo $tahun_sekarang; ?>
                            </span>
                        </h3>
                        <div class="card-tools d-flex align-items-center">
                            <button type="button" class="btn btn-sm btn-outline-info mr-2 d-none" id="btn-reset-chart" onclick="resetCharts()">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                            <button class="btn btn-tool mr-2" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <ul class="nav nav-pills ml-auto d-inline-flex">
                                <li class="nav-item"><a class="nav-link active btn-sm" href="#revenue-chart" data-toggle="tab">Bar</a></li>
                                <li class="nav-item"><a class="nav-link btn-sm" href="#sales-chart" data-toggle="tab">Donut</a></li>
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
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-info">
                    <div class="card-header border-0">
                        <h3 class="card-title">Riwayat Seluruh Pengembalian Buku</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <div id="tempat-tombol-export"></div>
                            
                            <div class="d-flex" style="gap: 10px";>
                                 <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalHapusTransaksiPengembalian">
                                    <i class="fas fa-trash"></i> Delete All
                                </button>

                                <a href="?page=transaksi_pengembalian_from" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Tambah Transaksi
                                </a>
                            </div>
                        </div>

                        <table id="tabelPengembalian" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr class="text-center">
                                    <th class="no-export">No</th>
                                    <th>ID Kembali</th>
                                    <th>ID Pinjam</th>
                                    <th class="no-excel no-pdf">Foto Peminjam</th>
                                    <th class="no-export">ID Anggota</th>
                                    <th>Nama Peminjam</th>
                                    <th>Jenis Anggota</th>
                                    <th class="no-export">Detail Identitas</th>
                                    <th class="no-excel no-pdf">Foto Buku</th>
                                    <th class="no-export">ID Buku</th>
                                    <th>Judul Buku</th>
                                    <th class="no-export">Lokasi Rak</th>
                                    <th>Jml Buku Kembali</th>
                                    <th>Tanggal Kembali</th>
                                    <th class="no-export">Status</th>
                                    <th>Denda</th>
                                    <th class="no-export">Izin Admin</th>
                                    <th class="no-export">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $no = 1;
                                    while($data = mysqli_fetch_array($query_tabel)) { 
                                        // Logika Warna Badge Jenis Anggota
                                        $ja = $data['Jenis_anggota'];
                                        $warna_ja = ($ja == 'Siswa') ? 'info' : (($ja == 'Guru') ? 'warning' : 'success');
                                        
                                        // Logika Warna Badge Status (Selesai atau Sebagian)
                                        $status = $data['Status'];
                                        $warna_status = ($status == 'Selesai') ? 'success' : 'primary';
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++; ?></td>
                                    <td class="text-center"><small class="badge badge-secondary"><?php echo $data['Id_pengembalian']; ?></small></td>
                                    <td class="text-center"><small class="badge badge-outline-dark"><?php echo $data['Id_peminjaman']; ?></small></td>
                                    
                                    <td class="text-center">
                                        <?php 
                                            $foto_p = $data['Foto_peminjam'];
                                            $jenis = $data['Jenis_anggota'];
                                            $cadangan = "dist/img/avatar5.png";
                                            
                                            // 1. Cek apakah kolom foto di database tidak kosong
                                            if (!empty($foto_p)) {
                                                // 2. Cek apakah di database sudah ada path foldernya atau belum
                                                if (strpos($foto_p, 'FOTOS/') === false && strpos($foto_p, 'foto/') === false) {
                                                    // Jika belum ada path, kita tempelkan berdasarkan Jenis_anggota
                                                    if ($jenis == 'Siswa') {
                                                        $sumber_p = "FOTOS/foto_siswa/" . $foto_p;
                                                    } elseif ($jenis == 'Guru') {
                                                        $sumber_p = "FOTOS/foto_guru/" . $foto_p;
                                                    } elseif ($jenis == 'Kelas') {
                                                        $sumber_p = "foto/logo.jpg";
                                                    } else {
                                                        $sumber_p = $cadangan;
                                                    }
                                                } else {
                                                    // Jika di database sudah tersimpan path lengkapnya
                                                    $sumber_p = $foto_p;
                                                }
                                            } else {
                                                // 3. Jika kolom di database kosong, gunakan cadangan avatar5
                                                $sumber_p = $cadangan; 
                                            }
                                        ?>
                                        <img src="<?php echo $sumber_p; ?>"
                                            class="img-circle border shadow-sm" 
                                            onerror="this.onerror=null; this.src='<?php echo $cadangan; ?>';">
                                    </td>
                                    
                                    <td class="text-center"><?php echo $data['Id_anggota']; ?></td>
                                    <td class="font-weight-bold"><?php echo $data['Nama_peminjam']; ?></td>
                                    
                                    <td class="text-center">
                                        <span class="badge badge-<?php echo $warna_ja; ?>"><?php echo $ja; ?></span>
                                    </td>
                                    
                                    <td><small><?php echo $data['Detail_identitas']; ?></small></td>
                                    
                                    <td class="text-center">
                                        <?php 
                                            $foto_b = $data['Foto_buku'];
                                            if (!empty($foto_b)) {
                                                // Cek apakah sudah ada folder 'FOTOS' di dalam teks database
                                                if (strpos($foto_b, 'FOTOS/') === false) {
                                                    $sumber_b = "FOTOS/foto_sampul_buku/" . $foto_b;
                                                } else {
                                                    $sumber_b = $foto_b;
                                                }
                                            } else {
                                                $sumber_b = "foto/buku.jpg";
                                            }
                                        ?>
                                        <img src="<?php echo $sumber_b; ?>" 
                                            class="border shadow-sm" 
                                            onerror="this.onerror=null; this.src='foto/buku.jpg';">
                                    </td>
                                    
                                    <td class="text-center"><?php echo $data['Id_buku']; ?></td>
                                    <td><?php echo $data['Judul_buku']; ?></td>
                                    <td class="text-center text-success"><i class="fas fa-map-marker-alt"></i> <?php echo $data['Lokasi_rak']; ?></td>
                                    
                                    <td class="text-center font-weight-bold"><?php echo $data['Jml_kembali']; ?></td>
                                    <td class="text-center"><?php echo $data['Tgl_kembali']; ?></td>
                                    
                                    <td class="text-center">
                                        <span class="badge badge-<?php echo $warna_status; ?>"><?php echo $status; ?></span>
                                    </td>

                                    <td class="text-center align-middle text-danger"><b>Rp. </b><?php echo number_format($data['Denda'], 0, ',', '.'); ?></td>

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
                                    
                                    <td class="text-center">
                                        <a href="?page=transaksi_pengembalian_hapus&id=<?php echo $data['Id_pengembalian']; ?>" 
                                        class="btn btn-danger btn-sm hapus-link" title="Hapus Riwayat">
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
<div class="modal fade" id="modalHapusTransaksiPengembalian">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-danger">
        <h4 class="modal-title"><i class="fas fa-trash-alt"></i> Filter Delete's Data Pengembalian</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="proses_Admin/hapus_masal/transaksi_pengembalian_proses.php" method="POST">
        <div class="modal-body">
          <p class="text-muted">Gunakan filter ini untuk membersihkan riwayat pengembalian yang sudah selesai.</p>
          
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Dari Tanggal Kembali :</label>
                <input type="date" name="tgl_kembali_mulai" class="form-control">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Sampai Tanggal Kembali :</label>
                <input type="date" name="tgl_kembali_selesai" class="form-control">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Denda Minimal (Rp) :</label>
                <input type="number" name="denda_min" class="form-control" placeholder="0">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Denda Maksimal (Rp) :</label>
                <input type="number" name="denda_max" class="form-control" placeholder="100000">
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
                <label>Status Transaksi :</label>
                <select class="form-control" disabled>
                  <option>Hanya Status: Selesai</option>
                </select>
                <input type="hidden" name="status" value="Selesai">
                <small class="text-muted">* Filter ini hanya menghapus data yang sudah tuntas.</small>
              </div>
            </div>
          </div>

          <hr>
          <div class="form-group">
            <label class="text-danger"><i>Konfirmasi Password Admin :</i></label>
            <input type="password" name="password_konfirmasi" class="form-control" required placeholder="Masukkan password akun admin anda untuk verifikasi">
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="submit" name="confirm_delete_pengembalian" class="btn btn-danger" onclick="return confirm('PENTING: Data pengembalian dan catatan denda akan dihapus permanen. Lanjutkan?')">Mulai Delete Data</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        $(function () {
            'use strict'
            var barCtx = $('#revenue-chart-canvas').get(0).getContext('2d');
            var donutCtx = $('#sales-chart-canvas').get(0).getContext('2d');
            var judulTeks = $('#judul-statistik');
            var btnReset = $('#btn-reset-chart');

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
                        judulTeks.text(kategori + " Terajin Mengembalikan");
                        btnReset.removeClass('d-none');
                        chartBatang.data.labels = dataDetail[kategori].labels;
                        chartBatang.data.datasets[0].data = dataDetail[kategori].data;
                        chartBatang.data.datasets[0].backgroundColor = dataDetail[kategori].color;
                        chartBatang.update();
                        chartDonut.data.labels = dataDetail[kategori].labels;
                        chartDonut.data.datasets[0].data = dataDetail[kategori].data;
                        chartDonut.update();
                    }
                }
            }

            window.resetCharts = function() {
                judulTeks.text("Statistik Pengembalian : ");
                btnReset.addClass('d-none');
                chartBatang.data.labels = dataUtama.labels;
                chartBatang.data.datasets[0].data = dataUtama.datasets[0].data;
                chartBatang.data.datasets[0].backgroundColor = dataUtama.datasets[0].backgroundColor;
                chartBatang.update();
                chartDonut.data.labels = dataUtama.labels;
                chartDonut.data.datasets[0].data = dataUtama.datasets[0].data;
                chartDonut.update();
            }

            $(document).on('click', '.hapus-link', function(e) {
                e.preventDefault(); 
                const href = $(this).attr('href');

                Swal.fire({
                    title: 'Konfirmasi Penghapusan',
                    text: "Pilih alasan Anda menghapus data ini:",
                    icon: 'question',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: 'Salah Input (Reset Status)',
                    denyButtonText: 'Perawatan Data (Tetap Lunas)',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    denyButtonColor: '#17a2b8',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Jika pilih Salah Input, kirim parameter alasan=koreksi
                        window.location.href = href + "&alasan=koreksi";
                    } else if (result.isDenied) {
                        // Jika pilih Perawatan Data, kirim parameter alasan=arsip
                        window.location.href = href + "&alasan=arsip";
                    }
                });
            });
        });
    });
</script>
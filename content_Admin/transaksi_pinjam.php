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

    // Filter SQL (Pastikan nama kolom Tgl_pinjam benar di DB)
    $filter_periode = " AND MONTH(Tgl_pinjam) BETWEEN $mulai AND $selesai AND YEAR(Tgl_pinjam) = '$tahun_sekarang'";

    // --- 2. QUERY STATISTIK (SAMA DENGAN KUNJUNGAN) ---
    // Jika error di sini, pastikan kolom 'Jenis_anggota' ada di tabel peminjaman
    $jml_siswa = mysqli_num_rows(mysqli_query($db, "SELECT * FROM peminjaman WHERE Jenis_anggota='Siswa' $filter_periode"));
    $jml_guru  = mysqli_num_rows(mysqli_query($db, "SELECT * FROM peminjaman WHERE Jenis_anggota='Guru' $filter_periode"));
    $jml_kelas = mysqli_num_rows(mysqli_query($db, "SELECT * FROM peminjaman WHERE Jenis_anggota='Kelas' $filter_periode"));

    // Fungsi Top 10 (Disamakan dengan getTop10 Kunjungan)
    function getTopPeminjam($db, $jenis, $filter) {
        $labels = []; $data = [];
        // Gunakan Nama_peminjam (sesuai upgrade tabelmu)
        $query = mysqli_query($db, "SELECT Nama_peminjam as nama, COUNT(*) as jml 
                                    FROM peminjaman 
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

    $top_siswa = getTopPeminjam($db, 'Siswa', $filter_periode);
    $top_guru  = getTopPeminjam($db, 'Guru', $filter_periode);
    $top_kelas = getTopPeminjam($db, 'Kelas', $filter_periode);

    $query_tabel = mysqli_query($db, "SELECT * FROM peminjaman WHERE Status != 'Selesai' ORDER BY (Admin_pemberi_izin = 'Menunggu Izin') DESC, Id_peminjaman DESC");
?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-exchange-alt text-success"></i> Daftar Transaksi Peminjaman</h1>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-success card-outline">
                    <div class="card-header d-flex flex-column flex-md-row align-items-md-center">
                        <h3 class="card-title flex-grow-1 text-left mb-2 mb-md-0" id="judul-statistik">
                            <i class="fas fa-chart-pie mr-1"></i>
                            Statistik Peminjaman :
                            <span class="text-success font-weight-bold ml-md-2">
                                <?php echo $nama_periode; ?> || <?php echo $tahun_sekarang; ?>
                            </span>
                        </h3>
                        <div class="card-tools d-flex align-items-center">
                            <button type="button" class="btn btn-sm btn-outline-success mr-2 d-none" id="btn-reset-chart" onclick="resetCharts()">
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
                <div class="card card-success">
                    <div class="card-header border-0">
                        <h3 class="card-title">Riwayat Seluruh Peminjaman Buku</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <div id="tempat-tombol-export"></div>

                            <div class="d-flex" style="gap: 10px";>
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalHapusTransaksiPinjam">
                                    <i class="fas fa-trash"></i> Delete All
                                </button>

                                <a href="?page=transaksi_pinjam_from" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Tambah Transaksi
                                </a>
                            </div>

                            </div>

                        <table id="tabelPinjam" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th class="no-export">No</th>
                                <th>ID Pinjaman</th>
                                <th class="no-excel no-pdf">Foto Peminjam</th>
                                <th>Tgl Pinjam & Jth Tmp</th>
                                <th>ID Anggota</th>
                                <th>Nama Peminjam</th>
                                <th>Jenis Anggota</th>
                                <th class="no-export">Identitas (Detail)</th>
                                <th class="no-excel no-pdf">Foto Buku</th>
                                <th>ID Buku</th>
                                <th>Judul Buku</th>
                                <th class="no-export">Rak</th>
                                <th>Jml Buku Dipinjam</th>
                                <th class="no-export">Status Buku</th>
                                <th class="no-export">Izin Admin</th>
                                <th class="no-export">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $no = 1;
                                if($query_tabel) {
                                    while($data = mysqli_fetch_array($query_tabel)) { 
                                        $ja = $data['Jenis_anggota'];
                                        $warna_badge = ($ja == 'Siswa') ? 'info' : (($ja == 'Guru') ? 'warning' : 'success');
                                        $status = $data['Status'];
                                        $badge_status = ($status == 'Dipinjam') ? 'warning' : 'success';
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $no++; ?></td>
                                <td class="text-center"><small class="badge badge-secondary"><?php echo $data['Id_peminjaman']; ?></small></td>
                                
                                <td class="text-center">
                                    <?php 
                                        $foto_p = $data['Foto_peminjam']; 
                                        $sumber_p = (!empty($foto_p)) ? $foto_p : "dist/img/avatar5.png";
                                    ?>
                                    <img src="<?php echo $sumber_p; ?>"
                                        class="img-circle border shadow-sm" 
                                        onerror="this.onerror=null; this.src='dist/img/avatar5.png';">
                                </td>

                                <td class="text-center">
                                    <small class="d-block"><b>Pinjam:</b> <?php echo $data['Tgl_pinjam']; ?></small>
                                    <small class="d-block text-danger"><b>Jatuh Tempo:</b> <?php echo $data['Tgl_jatuh_tempo']; ?></small>
                                </td>

                                <td class="text-center"><?php echo $data['Id_anggota']; ?></td>
                                <td class="font-weight-bold"><?php echo $data['Nama_peminjam']; ?></td>
                                
                                <td class="text-center">
                                    <span class="badge badge-<?php echo $warna_badge; ?>"><?php echo $ja; ?></span>
                                </td>

                                <td><small><?php echo $data['Detail_identitas']; ?></small></td>

                                <td class="text-center">
                                    <?php 
                                        $foto_b = $data['Foto_buku']; 
                                        $sumber_b = (!empty($foto_b)) ? $foto_b : "dist/img/no-book.png";
                                    ?>
                                    <img src="<?php echo $sumber_b; ?>" 
                                        class="border shadow-sm" 
                                        onerror="this.onerror=null; this.src='dist/img/no-book.png';">
                                </td>

                                <td class="text-center"><?php echo $data['Id_buku']; ?></td>
                                <td><?php echo $data['Judul_buku']; ?></td>
                                <td class="text-center text-success"><i class="fas fa-map-marker-alt"></i> <?php echo $data['Lokasi_rak']; ?></td>
                                <td><b><?php echo $data['Jumlah']; ?> (Awl Pinjam) </b> / <?php echo $data['Sisa_pinjam']; ?> (Blm Kembali) </td>

                                <td class="text-center align-middle">
                                    <span class="badge badge-<?php echo $badge_status; ?> p-2 shadow-sm"><?php echo $status; ?></span>
                                </td>

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
                                    <div class="btn-group-aksi">
                                        <a href="?page=transaksi_pengembalian_from&id_p=<?php echo $data['Id_peminjaman']; ?>" 
                                        class="btn btn-info btn-sm" title="Proses Pengembalian">
                                            <i class="fas fa-undo"></i>
                                        </a>
                                        
                                        <a href="?page=transaksi_pinjam_hapus&id=<?php echo $data['Id_peminjaman']; ?>" 
                                        class="btn btn-danger btn-sm hapus-link">
                                            <i class="fas fa-trash"></i>
                                        </a>                             
                                    </div>
                                </td>
                            </tr>
                            <?php 
                                    } 
                                } 
                            ?>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- From Filter Delete All -->
<div class="modal fade" id="modalHapusTransaksiPinjam">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-danger">
        <h4 class="modal-title"><i class="fas fa-trash-alt"></i> Filter Delet's Data Peminjaman</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="proses_Admin/hapus_masal/transaksi_pinjam_proses.php" method="POST">
        <div class="modal-body">
          <p class="text-muted">Gunakan filter ini untuk membersihkan riwayat peminjaman yang sudah selesai.</p>
          
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Dari Tanggal Pinjam :</label>
                <input type="date" name="tgl_pinjam_mulai" class="form-control">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Sampai Tanggal Pinjam :</label>
                <input type="date" name="tgl_pinjam_selesai" class="form-control">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Jenis Anggota :</label>
                <select name="jenis_anggota" class="form-control">
                  <option value="">-- Pilih Jenis Anggots --</option>
                  <option value="Siswa">Siswa</option>
                  <option value="Guru">Guru</option>
                  <option value="Kelas">Kelas</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Status Transaksi :</label>
                <select name="status_label" class="form-control" disabled>
                  <option value="Selesai">Hanya Status: Selesai / Kembali</option>
                </select>
                <input type="hidden" name="status" value="Selesai">
                <small class="text-info">* Data dengan status "Dipinjam" tidak akan muncul dalam filter ini.</small>
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
          <button type="submit" name="confirm_delete_peminjaman" class="btn btn-danger" onclick="return confirm('Hanya data dengan status SELESAI yang akan dihapus. Yakin?')">Mulai Delete Data</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        $(function () {
            'use strict'
            
            // Tutup preloader secara paksa saat script mulai dibaca
            if ($('.preloader').length) { $('.preloader').fadeOut(); }

            var barCtx = $('#revenue-chart-canvas').get(0).getContext('2d');
            var donutCtx = $('#sales-chart-canvas').get(0).getContext('2d');
            var judulTeks = $('#judul-statistik');
            var btnReset = $('#btn-reset-chart');

            var dataUtama = {
                labels: ['Siswa', 'Guru', 'Kelas'],
                datasets: [{
                    data: [<?php echo (int)$jml_siswa; ?>, <?php echo (int)$jml_guru; ?>, <?php echo (int)$jml_kelas; ?>],
                    backgroundColor: ['#28a745', '#ffc107', '#17a2b8']
                }]
            };

            var dataDetail = {
                'Siswa': { labels: <?php echo json_encode($top_siswa['labels']); ?>, data: <?php echo json_encode($top_siswa['data']); ?>, color: '#28a745' },
                'Guru':  { labels: <?php echo json_encode($top_guru['labels']); ?>, data: <?php echo json_encode($top_guru['data']); ?>, color: '#ffc107' },
                'Kelas': { labels: <?php echo json_encode($top_kelas['labels']); ?>, data: <?php echo json_encode($top_kelas['data']); ?>, color: '#17a2b8' }
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
                        chartDonut.update();
                    }
                }
            }

            window.resetCharts = function() {
                judulTeks.text("Statistik Peminjaman : ");
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
                    title: 'Yakin hapus?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Batal'
                }).then((result) => { if (result.isConfirmed) { window.location.href = href; } });
            });
        });
    });
</script>
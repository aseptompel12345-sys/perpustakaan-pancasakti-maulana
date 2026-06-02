<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1><i class="fas fa-book text-info"></i> Daftar Buku</h1>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card card-info card-outline">
          <div class="card-body">
            <table id="tabelBuku" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th class="no-excel no-pdf">Foto Buku</th>
                  <th class="no-export">ID Buku</th>
                  <th>ISBN</th>
                  <th>Judul Buku</th>
                  <th>Pengarang</th>
                  <th>Penerbit</th> 
                  <th>Tahun Terbit</th>
                  <th>Bidang Buku</th>
                  <th>Lokasi Rak</th>
                  <th>Stok Awal Buku</th>
                  <th>Stok Buku Tersedia</th>
                  <th class="no-export">Aksi</th>
                </tr>
              </thead> 
              <tbody>
                <?php
                // QUERY REAL-TIME: Menghitung sisa stok berdasarkan transaksi yang statusnya masih 'Dipinjam'
                $sql_realtime = "SELECT *, 
                                (Stok_awal_buku - IFNULL((SELECT SUM(Jumlah) FROM peminjaman WHERE Id_buku = buku.Id_buku AND Status = 'Dipinjam'), 0)) AS stok_asli 
                                FROM buku 
                                ORDER BY Id_buku ASC";

                $query = mysqli_query($db, $sql_realtime);

                if (!$query) {
                    echo "<tr><td colspan='12' class='text-center text-danger'>Error: " . mysqli_error($db) . "</td></tr>";
                } else {
                    while ($data = mysqli_fetch_array($query)) {
                        // Kita gunakan alias 'stok_asli' untuk menentukan warna badge dan angka yang tampil
                        $stok_tampil = $data['stok_asli'];
                ?>
                <tr>
                    <td class="text-center">
                        <?php if (!empty($data['Foto'])): ?>
                            <img src="FOTOS/foto_sampul_buku/<?php echo $data['Foto']; ?>" style="width: 50px; height: 70px; object-fit: cover;">
                        <?php else: ?>
                            <i class="fas fa-book fa-3x text-secondary"></i>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $data['Id_buku']; ?></td>
                    <td><?php echo $data['ISBN']; ?></td>
                    <td><?php echo $data['Judul']; ?></td>
                    <td><?php echo $data['Pengarang']; ?></td>
                    <td><?php echo $data['Penerbit']; ?></td>
                    <td><?php echo $data['Tahun_terbit']; ?></td>
                    <td><span class="badge badge-info"><?php echo $data['Bidang_buku']; ?></span></td>
                    <td><?php echo $data['Lokasi_rak']; ?></td>
                    <td><?php echo $data['Stok_awal_buku']; ?></td>
                    <td class="text-center">
                        <span class="badge badge-success">
                            <?php echo $data['Stok_buku_tersedia']; ?>
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="btn-group-aksi">
                            <a href="?page=buku_edit&id=<?php echo $data['Id_buku']; ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="?page=buku_hapus&id=<?php echo $data['Id_buku']; ?>" 
                              class="btn btn-danger btn-sm" 
                              onclick="return confirm('Apakah Anda yakin ingin menghapus buku <?php echo $data['Judul']; ?>?')">
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
          <div class="card-footer">                           
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">

              <div class="d-flex" style="gap: 10px;">
                <a href="?page=buku_from" class="btn btn-success">
                  <i class="fas fa-plus"></i> Tambah Buku
                </a>
                
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalHapusBuku">
                  <i class="fas fa-trash"></i> Delete All
                </button>
              </div>

               <div id="tempat-tombol-export" class="d-flex" style="gap: 5px;"></div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- From Filter Delete All -->
<div class="modal fade" id="modalHapusBuku">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger">
        <h4 class="modal-title"><i class="fas fa-trash-alt"></i> Filter Delete's Data Buku</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="proses_Admin/hapus_masal/buku_proses.php" method="POST">
        <div class="modal-body">
          <p class="text-muted">Isi salah satu atau lebih kolom di bawah untuk menentukan data yang akan dihapus.</p>
          
          <div class="form-group">
            <label>Tahun Terbit :</label>
            <input type="number" name="tahun_terbit" class="form-control" placeholder="Contoh: 2020">
          </div>

          <div class="form-group">
            <label>Bidang Buku :</label>
            <select name="bidang_buku" class="form-control">
              <option value="">-- Pilih Bidang Buku --</option>
              <option value="Pendidikan">Pendidikan</option>
              <option value="Fiksi">Fiksi</option>
              <option value="Nonfiksi">Nonfiksi</option>
              <option value="Lain-lain">Lain-lain</option>
            </select>
          </div>

          <div class="form-group">
            <label>Lokasi Rak :</label>
            <select name="lokasi_rak" class="form-control">
              <option value="">-- Pilih Lokasi Rak Buku --</option>
              <option value="Mapel Umum">Mapel Umum</option>
              <option value="Mapel Produktif Teknik Pesawat Udara">Mapel Produktif Teknik Pesawat Udara</option>
              <option value="Mapel Produktif Otomotif">Mapel Produktif Otomotif</option>
              <option value="Mapel Produktif Akuntansi dan Lembaga Keuangan">Mapel Produktif Akuntansi dan Lembaga Keuangan</option>
              <option value="Mapel Produktif PPLG">Mapel Produktif PPLG</option>
              <option value="Mapel Produktif TKJ">Mapel Produktif TKJ</option>
              <option value="Mapel Produktif Teknik Logistik">Mapel Produktif Teknik Logistik</option>
            </select>
          </div>

          <hr>
          <div class="form-group">
            <label class="text-danger"><i>Konfirmasi Password Admin :</i></label>
            <input type="password" name="password_konfirmasi" class="form-control" required placeholder="Masukkan password akun admin Anda untuk verifikasi">
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="submit" name="confirm_delete_buku" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin? Data yang dihapus tidak dapat dipulihkan!')">Mulai Delete Data</button>
        </div>
      </form>
    </div>
  </div>
</div>


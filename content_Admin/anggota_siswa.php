<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1><i class="fas fa-users text-info"></i> Daftar Anggota Siswa</h1>
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
            <table id="tabelSiswa" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th class="no-excel no-pdf">Foto Siswa</th>
                  <th class="no-export">ID Anggota</th>
                  <th>ID Siswa</th>
                  <th>NISN</th>
                  <th>Nama Siswa</th>
                  <th>Jenis Kelamin</th>
                  <th>Agama</th> 
                  <th>Kelas</th>
                  <th>Jurusan</th>
                  <th>Alamat</th>
                  <th>No.Handphone</th>   
                  <th class="no-export">Aksi</th>
                </tr>
              </thead> 
              <tbody>
                <?php
                  // Menggunakan $db sesuai file koneksi.php Anda
                  $query = mysqli_query($db, "SELECT * FROM anggota_siswa INNER JOIN anggota ON anggota_siswa.Id_anggota = anggota.Id_anggota
                  ORDER BY Id_siswa ASC");

                  if (!$query) {
                      echo "<tr><td colspan='12' class='text-center text-danger'>Error: " . mysqli_error($db) . "</td></tr>";
                  } else {
                      while ($data = mysqli_fetch_array($query)) {
                ?>
                <tr>
                  <td class="text-center">
                    <?php if (!empty($data['Foto'])): ?>
                      <img src="fOTOS/foto_siswa/<?php echo $data['Foto']; ?>">
                    <?php else: ?>
                      <i class="fas fa-book fa-3x text-secondary"></i>
                    <?php endif; ?>
                  </td>
                  <td><?php echo $data['Id_anggota']; ?></td>
                  <td><?php echo $data['Id_siswa']; ?></td>
                  <td><?php echo $data['NISN']; ?></td>
                  <td><?php echo $data['Nama_siswa']; ?></td>
                  <td><?php echo $data['Jenis_kelamin']; ?></td>
                  <td><?php echo $data['Agama']; ?></td>
                  <td><?php echo $data['Kelas']; ?></td>
                  <td><?php echo $data['Jurusan']; ?></td>
                  <td><?php echo $data['Alamat']; ?></td>
                  <td><?php echo $data['No_tlp']; ?></td>
                  <td class="text-center">
                    <div class="btn-group-aksi">
                      <a href="?page=anggota_siswa_edit&id=<?php echo $data['Id_siswa']; ?>" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i>
                      </a>
                      <a href="?page=anggota_siswa_hapus&id_siswa=<?php echo $data['Id_siswa']; ?>" 
                        class="btn btn-danger btn-sm" 
                        onclick="return confirm('Apakah Anda yakin ingin menghapus status keanggotaan <?php echo $data['Nama_siswa']; ?>?')">
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
                <a href="?page=anggota_siswa_from" class="btn btn-success">
                  <i class="fas fa-plus"></i> Tambah Anggota
                </a>

                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalHapusAnggotaSiswa">
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


<!-- From FIlter Delete All -->
<div class="modal fade" id="modalHapusAnggotaSiswa">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger">
        <h4 class="modal-title"><i class="fas fa-trash-alt"></i> Filter Delete's Data Anggota Siswa</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="proses_Admin/hapus_masal/anggota_siswa_proses.php" method="POST">
        <div class="modal-body">
          <p class="text-muted">Hapus data siswa berdasarkan kriteria spesifik di bawah ini.</p>
          
          <div class="form-group">
            <label>Kelas :</label>
            <select name="kelas" class="form-control">
              <option value="">-- Pilih Kelas --</option>
              <option value="10">Kelas 10</option>
              <option value="11">Kelas 11</option>
              <option value="12">Kelas 12</option>
            </select>
          </div>

          <div class="form-group">
            <label>Jurusan :</label>
            <input type="text" name="jurusan" class="form-control" placeholder="Contoh: RPL, TKJ, atau Akuntansi">
          </div>

          <hr>
          <div class="form-group">
            <label class="text-danger"><i>Konfirmasi Password Admin :</i></label>
            <input type="password" name="password_konfirmasi" class="form-control" required placeholder="Masukkan password akun admin Anda untuk verifikasi">
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="submit" name="confirm_delete_siswa" class="btn btn-danger" onclick="return confirm('PENTING: Menghapus data siswa juga akan menghapus akun login mereka. Lanjutkan?')">Mulai Delete Data</button>
        </div>
      </form>
    </div>
  </div>
</div>

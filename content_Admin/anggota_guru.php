  <section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1><i class="fas fa-users text-info"></i> Daftar Anggota Guru</h1>
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
            <table id="tabelGuru" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th class="no-excel no-pdf">Foto Guru</th>
                  <th class="no-export">ID Anggota</th>
                  <th>ID Guru</th>
                  <th>NIP</th>
                  <th>Nama Guru</th>
                  <th>Jenis Kelamin</th>
                  <th>Agama</th> 
                  <th>Alamat</th>
                  <th>No.Handphone</th>   
                  <th class="no-export">Aksi</th>
                </tr>
              </thead> 
              <tbody>
                <?php
                  // Menggunakan $db sesuai file koneksi.php Anda
                  $query = mysqli_query($db, "SELECT * FROM anggota_guru INNER JOIN anggota ON anggota_guru.Id_anggota = anggota.Id_anggota
                  ORDER BY Id_guru ASC");

                  if (!$query) {
                      echo "<tr><td colspan='12' class='text-center text-danger'>Error: " . mysqli_error($db) . "</td></tr>";
                  } else {
                      while ($data = mysqli_fetch_array($query)) {
                ?>
                <tr>
                  <td class="text-center">
                    <?php if (!empty($data['Foto'])): ?>
                      <img src="FOTOS/foto_guru/<?php echo $data['Foto']; ?>">
                    <?php else: ?>
                      <i class="fas fa-book fa-3x text-secondary"></i>
                    <?php endif; ?>
                  </td>
                  <td><?php echo $data['Id_anggota']; ?></td>
                  <td><?php echo $data['Id_guru']; ?></td>
                  <td><?php echo $data['NIP']; ?></td>
                  <td><?php echo $data['Nama_guru']; ?></td>
                  <td><?php echo $data['Jenis_kelamin']; ?></td>
                  <td><?php echo $data['Agama']; ?></td>
                  <td><?php echo $data['Alamat']; ?></td>
                  <td><?php echo $data['No_tlp']; ?></td>
                  <td class="text-center">
                    <div class="btn-group-aksi">
                      <a href="?page=anggota_guru_edit&id=<?php echo $data['Id_guru']; ?>" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i>
                      </a>
                      <a href="?page=anggota_guru_hapus&id_guru=<?php echo $data['Id_guru']; ?>" 
                        class="btn btn-danger btn-sm" 
                        onclick="return confirm('Apakah Anda yakin ingin menghapus status keanggotaan <?php echo $data['Nama_guru']; ?>?')">
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
            <div class="d-flex flex-wrap justify-content-between mb-3"
            >
              <a href="?page=anggota_guru_from" class="btn btn-success">
                <i class="fas fa-plus"></i> Tambah Anggota
              </a>

              <div id="tempat-tombol-export" class="d-flex" style="gap: 5px;"></div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
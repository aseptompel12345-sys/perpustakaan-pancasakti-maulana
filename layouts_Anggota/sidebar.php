<aside class="main-sidebar sidebar-dark-primary elevation-4 d-flex flex-column" style="height: 100vh;">
    <a href="index3.html" class="brand-link">
      <img src="foto/Logo.png" alt="Pancasakti Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Pancasakti</span>
    </a>

    <div class="sidebar flex-grow-1 d-flex flex-column" style="overflow-y: auto;">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center" style="border-bottom: 1px solid #4f5962;">
          <div class="image">
              <?php
              $jenis = $_SESSION['jenis_anggota'];
              $id_agt = $_SESSION['Id_anggota'];
              $folder = "FOTOS/";
              
              // Tentukan Path Foto
              if ($jenis == 'Kelas') {
                  $full_path = "foto/Logo.png"; 
              } else {
                  if (!empty($_SESSION['foto_user'])) {
                      $sub_folder = ($jenis == 'Siswa') ? "foto_siswa/" : "foto_guru/";
                      $full_path = $folder . $sub_folder . $_SESSION['foto_user'];
                  } else {
                      if ($jenis == 'Siswa') {
                          $q_jk = mysqli_query($db, "SELECT Jenis_kelamin FROM anggota_siswa WHERE Id_anggota = '$id_agt'");
                      } else {
                          $q_jk = mysqli_query($db, "SELECT Jenis_kelamin FROM anggota_guru WHERE Id_anggota = '$id_agt'");
                      }
                      
                      $d_jk = mysqli_fetch_assoc($q_jk);
                      $gender = $d_jk['Jenis_kelamin'];
                      
                      $full_path = ($gender == 'Perempuan') ? "dist/img/avatar3.png" : "dist/img/avatar5.png";
                  }
              }
              ?>
              <img src="<?php echo $full_path; ?>" 
                  class="img-circle elevation-2" 
                  alt="User Image" 
                  style="width: 40px; height: 40px; object-fit: cover; border: 2px solid #21bd36;">
          </div>
          
          <div class="info ml-2" style="overflow: hidden; flex: 1;">
              <p class="mb-0 text-white font-weight-bold" 
                style="font-size: 0.95rem; line-height: 1.2; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 140px;" 
                title="<?php echo $_SESSION['nama_user']; ?>">
                  <?php echo $_SESSION['nama_user']; ?>
              </p>
              
              <div class="d-flex align-items-center mt-1">
                  <span class="badge badge-success" style="font-size: 0.6rem; padding: 2px 5px; opacity: 0.9;">
                      <i class="fas fa-user-check mr-1"></i> <?php echo strtoupper($_SESSION['role']); ?> (<?php echo strtoupper($jenis); ?>)
                  </span>
              </div>
          </div>
      </div>

      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          
          <li class="nav-item">
            <a href="?anggota=menu" class="nav-link <?php echo (!isset($_GET['anggota']) || $_GET['anggota'] == 'menu') ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-home"></i>
              <p>Home</p>
            </a>
          </li>       

          <li class="nav-item">
            <a href="?anggota=buku" class="nav-link <?php echo ($_GET['anggota'] ?? '') == 'buku' ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-book"></i>
              <p>Daftar Buku</p>
            </a>
          </li>
           
          <li class="nav-item <?php echo (in_array($_GET['anggota'] ?? '', ['transaksi_kunjungan', 'transaksi_pinjam_kembali'])) ? 'menu-open' : ''; ?>">
            <a href="#" class="nav-link <?php echo (in_array($_GET['anggota'] ?? '', ['transaksi_kunjungan', 'transaksi_pinjam_kembali'])) ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-exchange-alt"></i>
              <p>
                Daftar Transaksi
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="?anggota=transaksi_kunjungan" class="nav-link <?php echo ($_GET['anggota'] ?? '') == 'transaksi_kunjungan' ? 'active' : ''; ?>">
                  <i class="fas fa-walking nav-icon"></i>
                  <p>Daftar Kunjungan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="?anggota=transaksi_pinjam_kembali" class="nav-link <?php echo ($_GET['anggota'] ?? '') == 'transaksi_pinjam_kembali' ? 'active' : ''; ?>">
                  <i class="fas fa-book-open nav-icon"></i>
                  <p>Daftar Pinjam & Kembali</p>
                </a>
              </li>
            </ul>
          </li>

        </ul>
      </nav>
    </div>
    <div class="sidebar-custom mt-auto p-2 border-top style-border" style="border-color: #4f5962 !important; background: #343a40;">
      <ul class="nav nav-pills nav-sidebar flex-column">
        <li class="nav-item">
          <a href="?anggota=pengaturan_anggota" class="nav-link <?php echo ($_GET['anggota'] ?? '') == 'pengaturan_anggota' ? 'active bg-warning text-dark' : 'text-white'; ?>">
            <i class="nav-icon fas fa-cogs"></i>
            <p><b>Pengaturan Akun</b></p>
          </a>
        </li>
      </ul>
    </div>
</aside>
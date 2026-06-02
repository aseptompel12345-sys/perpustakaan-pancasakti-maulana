<aside class="main-sidebar sidebar-dark-primary elevation-4 d-flex flex-column" style="height: 100vh;">
  <a href="index_Admin.php" class="brand-link">
    <img src="foto/Logo.png" alt="Pancasakti Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">Pancasakti</span>
  </a>

  <div class="sidebar flex-grow-1 d-flex flex-column" style="overflow-y: auto;">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center" style="border-bottom: 1px solid #4f5962;">
      <div class="image">
          <img src="FOTOS/foto_admin/<?php echo $_SESSION['foto_user']; ?>" 
              class="img-circle elevation-2" 
              alt="User Image" 
              style="width: 40px; height: 40px; object-fit: cover; border: 2px solid #3c8dbc;">
      </div>
      <div class="info ml-2">
          <p class="mb-0 text-white font-weight-bold" 
             style="font-size: 0.95rem; line-height: 1.2; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 140px;" 
             title="<?php echo $_SESSION['nama_user']; ?>">
              <?php echo $_SESSION['nama_user']; ?>
          </p>
          <span class="badge badge-info" style="font-size: 0.65rem; padding: 2px 6px; letter-spacing: 0.5px;">
              <i class="fas fa-id-badge mr-1"></i> <?php echo strtoupper($_SESSION['role']); ?>
          </span>
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
          <a href="?page=menu" class="nav-link <?php echo (!isset($_GET['page']) || $_GET['page'] == 'menu') ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-home"></i>
            <p>Home</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="?page=buku" class="nav-link <?php echo ($_GET['page'] ?? '') == 'buku' ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-book"></i>
            <p>Daftar Buku</p>
          </a>
        </li>
        <li class="nav-item <?php echo (in_array($_GET['page'] ?? '', ['anggota_siswa', 'anggota_guru', 'anggota_kelas'])) ? 'menu-open' : ''; ?>">
          <a href="#" class="nav-link <?php echo (in_array($_GET['page'] ?? '', ['anggota_siswa', 'anggota_guru', 'anggota_kelas'])) ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-users"></i>
            <p>
              Daftar Anggota
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="?page=anggota_siswa" class="nav-link <?php echo ($_GET['page'] ?? '') == 'anggota_siswa' ? 'active' : ''; ?>">
                <i class="fas fa-user-graduate nav-icon"></i>
                <p>Siswa</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="?page=anggota_guru" class="nav-link <?php echo ($_GET['page'] ?? '') == 'anggota_guru' ? 'active' : ''; ?>">
                <i class="fas fa-chalkboard-teacher nav-icon"></i>
                <p>Guru</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="?page=anggota_kelas" class="nav-link <?php echo ($_GET['page'] ?? '') == 'anggota_kelas' ? 'active' : ''; ?>">
                <i class="fas fa-landmark nav-icon"></i>
                <p>Kelas</p>
              </a>
            </li>
          </ul>
          </li>
        <li class="nav-item <?php echo (in_array($_GET['page'] ?? '', ['transaksi_kunjungan', 'transaksi_pinjam', 'transaksi_pengembalian'])) ? 'menu-open' : ''; ?>">
          <a href="#" class="nav-link <?php echo (in_array($_GET['page'] ?? '', ['transaksi_kunjungan', 'transaksi_pinjam', 'transaksi_pengembalian'])) ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-exchange-alt"></i>
            <p>
              Daftar Transaksi
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="?page=transaksi_kunjungan" class="nav-link <?php echo ($_GET['page'] ?? '') == 'transaksi_kunjungan' ? 'active' : ''; ?>">
                <i class="fas fa-walking nav-icon"></i>
                <p>Daftar Kunjungan</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="?page=transaksi_pinjam" class="nav-link <?php echo ($_GET['page'] ?? '') == 'transaksi_pinjam' ? 'active' : ''; ?>">
                <i class="fas fa-book-open nav-icon"></i>
                <p>Daftar Peminjaman</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="?page=transaksi_pengembalian" class="nav-link <?php echo ($_GET['page'] ?? '') == 'transaksi_pengenbalian' ? 'active' : ''; ?>">
                <i class="fas fa-undo nav-icon"></i>
                <p>Daftar Pengembalian</p>
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
          <a href="?page=pengaturan_admin" class="nav-link <?php echo ($_GET['page'] ?? '') == 'pengaturan_admin' ? 'active bg-warning text-dark' : 'text-white'; ?>">
            <i class="nav-icon fas fa-cogs"></i>
            <p><b>Pengaturan Akun</b></p>
          </a>
        </li>
      </ul>
    </div>
</aside>
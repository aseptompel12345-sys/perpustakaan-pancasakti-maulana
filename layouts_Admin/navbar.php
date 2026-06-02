<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="index3.html" class="nav-link">Home</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="#" class="nav-link">Contact</a>
    </li>
  </ul>    

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <!-- Navbar Search -->
    <li class="nav-item">
      <a class="nav-link" data-widget="navbar-search" href="#" role="button">
        <i class="fas fa-search"></i>
      </a>
      <div class="navbar-search-block">
        <form class="form-inline">
          <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
              <button class="btn btn-navbar" type="submit">
                <i class="fas fa-search"></i>
              </button>
              <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
        </form>
      </div>
    </li>

    <!-- Messages Dropdown Menu -->
   <li class="nav-item dropdown">
  <?php
    // 1. QUERY UNION: Menggabungkan 3 tabel yang berstatus 'Menunggu Izin'
    $sql_union = "
        SELECT Id_kunjungan as id, Nama_pengunjung as nama, Jenis_anggota as jenis, Foto_kunjungan as foto, Jam_kunjungan as jam, 'kunjungan' as asal, Keperluan as info
        FROM kunjungan WHERE Admin_pemberi_izin = 'Menunggu Izin'
        UNION
        SELECT Id_peminjaman as id, Nama_peminjam as nama, Jenis_anggota as jenis, Foto_peminjam as foto, Tgl_pinjam as jam, 'peminjaman' as asal, Judul_buku as info
        FROM peminjaman WHERE Admin_pemberi_izin = 'Menunggu Izin'
        UNION
        SELECT Id_pengembalian as id, Nama_peminjam as nama, Jenis_anggota as jenis, Foto_peminjam as foto, Tgl_kembali as jam, 'pengembalian' as asal, Judul_buku as info
        FROM pengembalian WHERE Admin_pemberi_izin = 'Menunggu Izin'
        ORDER BY jam DESC";

    $q_list = mysqli_query($db, $sql_union);
    $total_notif = mysqli_num_rows($q_list);
  ?>
  
  <a class="nav-link" data-toggle="dropdown" href="#">
    <i class="far fa-bell"></i>
    <?php if($total_notif > 0): ?>
      <span class="badge badge-warning navbar-badge"><?php echo $total_notif; ?></span>
    <?php endif; ?>
  </a>
  
  <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="notif-container" style="min-width: 320px;">
    
    <div id="notif-list-content">
        <span class="dropdown-item dropdown-header"><?php echo $total_notif; ?> Permintaan Izin</span>
        <div class="dropdown-divider"></div>

        <?php 
        if($total_notif > 0) {
          while($row = mysqli_fetch_array($q_list)) {
            // Logika Foto: Jika kosong pakai avatar default
            $foto_path = (!empty($row['foto'])) ? $row['foto'] : "dist/img/avatar5.png";
            
            // Logika Warna Bintang & Label Berdasarkan Asal Tabel
            if($row['asal'] == 'peminjaman') {
                $warna_bintang = "text-success"; // Hijau untuk Pinjam
                $label_info = "Pinjam: " . $row['info'];
            } else if($row['asal'] == 'pengembalian') {
                $warna_bintang = "text-danger"; // Merah untuk Kembali
                $label_info = "Kembali: " . $row['info'];
            } else {
                $warna_bintang = "text-primary"; // Biru untuk Kunjungan
                $label_info = "Keperluan: " . $row['info'];
            }
        ?>
          <a href="javascript:void(0)" class="dropdown-item" onclick="bukaDetailIzin('<?php echo $row['id']; ?>', '<?php echo $row['asal']; ?>', event)">
            <div class="media" style="position: relative;"> 
              <img src="<?php echo $foto_path; ?>" class="img-size-50 mr-3 img-circle border shadow-sm" style="width:50px; height:50px; object-fit:cover;">
              <div class="media-body">
                <h3 class="dropdown-item-title font-weight-bold" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 170px; padding-right: 20px;">
                  <?php echo $row['nama']; ?>
                </h3>
                
                <span class="text-sm <?php echo $warna_bintang; ?>" style="position: absolute; right: 0; top: 0;">
                    <i class="fas fa-star"></i>
                </span>

                <p class="text-sm m-0"><?php echo $label_info; ?></p>
                <p class="text-sm text-muted m-0"><i class="far fa-clock mr-1"></i> <?php echo $row['jam']; ?></p>
              </div>
            </div>
          </a>
          <div class="dropdown-divider"></div>
        <?php 
          } 
        } else {
          echo '<a href="#" class="dropdown-item text-center">Tidak ada antrean</a>';
        }
        ?>
        <a href="?page=transaksi" class="dropdown-item dropdown-footer text-primary">Lihat Semua Antrean</a>
    </div>

    <div id="notif-detail-content" style="display:none; padding: 15px;"></div>
  </div>
</li>

<script>
// 1. FUNGSI BUKA DETAIL (Sudah mendukung parameter 'asal')
function bukaDetailIzin(id, asal, event) {
    if(event) {
        event.preventDefault();
        event.stopPropagation();
    }

    const listContent = document.getElementById('notif-list-content');
    const detailBox = document.getElementById('notif-detail-content');

    listContent.style.opacity = '0';
    
    setTimeout(() => {
        listContent.style.display = 'none';
        detailBox.style.display = 'block';
        detailBox.style.opacity = '1';
        detailBox.classList.add('fade-in'); 
        detailBox.innerHTML = '<div class="text-center p-4"><i class="fas fa-spinner fa-spin"></i> Memuat Detail...</div>';

        // Mengirim id dan asal ke chat.php
        fetch('halaman_chat/chat.php?id=' + id + '&asal=' + asal)
            .then(response => response.text())
            .then(data => {
                detailBox.innerHTML = data;
            });
    }, 200);
}

// 2. FUNGSI KEMBALI (Tombol Undo di dalam chat.php)
function kembaliKeDaftar(event) {
    if(event) {
        event.preventDefault();
        event.stopPropagation();
    }
    
    const listContent = document.getElementById('notif-list-content');
    const detailBox = document.getElementById('notif-detail-content');

    detailBox.style.opacity = '0';

    setTimeout(() => {
        detailBox.style.display = 'none';
        detailBox.style.opacity = '1';
        
        listContent.style.display = 'block';
        listContent.style.opacity = '1';
        listContent.classList.add('fade-in');
    }, 200);
}

// 3. FUNGSI PROSES (Izinkan / Tolak)
function prosesAksiIzin(id, aksi, tabel, event) {
    // Tambahkan stopPropagation agar dropdown tidak tertutup
    if(event) {
        event.preventDefault();
        event.stopPropagation();
    }

    const detailBox = document.getElementById('notif-detail-content');
    detailBox.innerHTML = '<div class="text-center p-4"><i class="fas fa-circle-notch fa-spin"></i> Memproses...</div>';

    fetch('halaman_chat/chat_proses.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${id}&aksi=${aksi}&tabel=${tabel}`
    })
    .then(response => response.text())
    .then(hasil => {
        if (hasil.trim() === "success") {
            detailBox.innerHTML = '<div class="text-center p-4 text-success"><i class="fas fa-check-circle"></i> Berhasil!</div>';
            setTimeout(() => {
                location.reload(); // Refresh lonceng agar data hilang dari antrean
            }, 800);
        } else {
            alert("Error: " + hasil);
            bukaDetailIzin(id, tabel); // Muat ulang detail jika gagal
        }
    });
}

// Mencegah menu lonceng tertutup saat area dalamnya diklik
$(document).on('click', '#notif-container', function (e) {
    e.stopPropagation();
});
</script>
    
  </ul>
</nav>
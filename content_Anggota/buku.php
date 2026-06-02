<?php
if (!isset($db)) {
    include "config/koneksi.php";
}

$sql_realtime = "SELECT *, 
                (Stok_awal_buku - IFNULL((SELECT SUM(Jumlah) FROM peminjaman WHERE Id_buku = buku.Id_buku AND Status = 'Dipinjam'), 0)) AS stok_asli 
                FROM buku 
                ORDER BY Id_buku ASC";

$query_buku = mysqli_query($db, $sql_realtime);

if (!$query_buku) {
    echo "<div class='alert alert-danger'>Gagal mengambil data: " . mysqli_error($db) . "</div>";
    exit;
}
?>

<style>
    /* Mengatur ukuran kolom agar lebih kecil (5 buku per baris di layar besar) */
    @media (min-width: 1200px) {
        .custom-col { flex: 0 0 20%; max-width: 20%; }
    }

    .card-katalog {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        border: none;
        background: #000; /* Dasar hitam untuk efek overlay */
        height: 320px; /* Ukuran foto lebih proporsional */
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .card-katalog:hover {
        transform: scale(1.03);
        box-shadow: 0 15px 30px rgba(0,0,0,0.3);
    }

    .img-katalog {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.9;
        transition: opacity 0.3s, transform 0.5s;
    }

    .card-katalog:hover .img-katalog {
        opacity: 0.6;
        transform: scale(1.1);
    }

    /* Overlay Data di Atas Gambar */
    .overlay-data {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 20px;
        background: linear-gradient(transparent, rgba(0,0,0,0.9));
        color: white;
        transform: translateY(40px); /* Tersembunyi sedikit */
        transition: all 0.3s ease;
    }

    .card-katalog:hover .overlay-data {
        transform: translateY(0);
    }

    .judul-overlay {
        font-size: 1rem;
        font-weight: bold;
        margin-bottom: 5px;
        line-height: 1.2;
        text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
    }

    .info-overlay {
        font-size: 0.8rem;
        opacity: 0.9;
        display: flex;
        justify-content: space-between;
    }

    .badge-stok-katalog {
        position: absolute;
        top: 10px;
        left: 10px;
        font-size: 0.7rem;
        padding: 4px 10px;
        border-radius: 50px;
        z-index: 5;
        font-weight: bold;
    }

    .search-modern {
        border-radius: 50px;
        background: #f1f3f5;
        border: none;
        padding: 12px 25px;
    }
</style>

<div class="row mb-4 pt-2">
    <div class="col-md-6">
        <h2><i class="fas fa-book text-info"></i> Daftar Buku</h2>  
      </div>
</div>

<div class="row" id="containerBuku">
    <?php 
    if(mysqli_num_rows($query_buku) > 0) {
        while($row = mysqli_fetch_array($query_buku)) { 
            $stok = $row['stok_asli']; 
            $badge_color = ($stok > 0) ? 'bg-success' : 'bg-danger';
    ?>
    <div class="col-lg-3 col-md-4 col-6 mb-4 custom-col item-buku">
        <div class="card card-katalog shadow-sm">
            <div class="badge-stok-katalog text-white <?php echo $badge_color; ?>">
                Stok: <?php echo $stok; ?>
            </div>
            
            <?php if (!empty($row['Foto'])): ?>
                <img src="FOTOS/foto_sampul_buku/<?php echo $row['Foto']; ?>" class="img-katalog" alt="Cover">
            <?php else: ?>
                <div class="d-flex align-items-center justify-content-center bg-secondary h-100">
                    <i class="fas fa-book fa-3x text-light opacity-50"></i>
                </div>
            <?php endif; ?>
            
            <div class="overlay-data">
                <div class="judul-overlay"><?php echo $row['Judul']; ?></div>
                <div class="info-overlay border-top pt-2 mt-1">
                    <span><i class="fas fa-map-marker-alt mr-1"></i> Rak <?php echo $row['Lokasi_rak']; ?></span>
                </div>
            </div>
        </div>
    </div>
    <?php 
        } 
    } else {
        echo "<div class='col-12 text-center'><p class='text-muted'>Belum ada data buku.</p></div>";
    }
    ?>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const input = document.getElementById("cariBuku");
    input.addEventListener("keyup", function() {
        const filter = this.value.toLowerCase();
        const cards = document.querySelectorAll(".item-buku");
        cards.forEach(card => {
            const title = card.innerText.toLowerCase();
            card.style.display = title.includes(filter) ? "" : "none";
        });
    });
});
</script>
<?php
// Keluar 2 tingkat untuk menemukan folder config dari folder proses_Admin/tambah/
include '../../config/koneksi.php'; 

if (isset($_POST['simpan'])) {
    
    // 1. Ambil data dari form
    $isbn          = $_POST['ISBN'];
    $judul         = $_POST['Judul'];
    $pengarang     = $_POST['Pengarang'];
    $penerbit      = $_POST['Penerbit'];
    $tahun_terbit  = $_POST['Tahun_terbit'];
    $bidang_buku   = $_POST['Bidang_buku'];
    $lokasi_rak    = $_POST['Rak_buku']; 
    $stok_awal     = $_POST['Stok_awal_buku'];
    $stok_tersedia = $stok_awal;

    // 2. Proses Upload Foto
    $foto_db = ''; 
    if (isset($_FILES['Foto']) && $_FILES['Foto']['error'] == 0) {
        $nama_file      = $_FILES['Foto']['name'];
        $tmp_file       = $_FILES['Foto']['tmp_name'];
        $ekstensi       = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
        
        // Nama file unik berdasarkan waktu
        $nama_file_baru = date('dmYHis') . '_' . uniqid() . '.' . $ekstensi;
        
        // Simpan ke folder root project
        $path = "../../FOTOS/foto_sampul_buku/" . $nama_file_baru; 

        if (move_uploaded_file($tmp_file, $path)) {
            $foto_db = $nama_file_baru; 
        }
    }

    // 3. Persiapan Query INSERT
    $query = "INSERT INTO buku (Foto, ISBN, Judul, Pengarang, Penerbit, Tahun_terbit, Bidang_buku, Lokasi_rak, Stok_awal_buku, Stok_buku_tersedia) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($db, $query);

    // 4. Bind parameter (8 string 's', 2 integer 'i')
    mysqli_stmt_bind_param($stmt, "ssssssssii", 
        $foto_db, $isbn, $judul, $pengarang, $penerbit, $tahun_terbit, 
        $bidang_buku, $lokasi_rak, $stok_awal, $stok_tersedia);

    // 5. Eksekusi dan Pop-up Alert
    if (mysqli_stmt_execute($stmt)) {
        // Pop-up Berhasil
        echo "<script>
                alert('Data Berhasil Disimpan!'); 
                window.location='../../index_Admin.php?page=buku';
              </script>";
        exit();
    } else {
        // Pop-up Gagal (Menampilkan pesan error dari database)
        $error = mysqli_error($db);
        echo "<script>
                alert('Data Gagal Disimpan! Error: " . addslashes($error) . "'); 
                window.location='../../index_Admin.php?page=buku_from';
              </script>";
        exit();
    }

} else {
    // Jika mencoba akses file ini tanpa klik tombol simpan
    header("Location: ../../index_Admin.php?page=buku_from");
    exit();
}
?>
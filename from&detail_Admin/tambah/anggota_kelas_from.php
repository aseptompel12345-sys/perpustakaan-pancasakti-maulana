<section class="content-header">
    <div class="container-fluid">
        <h1><i class="ion ion-person-add text-success"></i> Input Data Anggota Kelas</h1>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card card-success card-outline">
            <form action="proses_Admin/tambah/anggota_kelas_proses.php" method="POST">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Kelas</label>
                                <input type="text" class="form-control" name="nama_kelas" placeholder="Masukkan Nama Kelas" required>
                            </div>
                            <div class="form-group">
                                <label>Nama Wali Kelas</label>
                                <input type="text" class="form-control" name="wali_kelas" placeholder="Masukkan Nama Guru Wali" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jumlah Siswa</label>
                                <input type="number" class="form-control" name="jumlah_siswa" placeholder="Masukan Jumlah Murid dalam Kelas" required>
                            </div>
                            <div class="form-group">
                                <label>Penanggung Jawab (Siswa)</label>
                                <input type="text" class="form-control" name="pj_kelas" placeholder="NMasukkan ama Ketua Kelas / Nama Peminjam Buku" required>
                                <small class="text-muted">*Bisa diisi lebih dari 1 nama (Pisahkan dengan koma)</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" name="lanjut_akun" class="btn btn-success">
                        <i class="fas fa-save"></i> Lanjutan Buat Akun
                    </button>
                    <a href="index_Admin.php?page=anggota_kelas" class="btn btn-danger float-right">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</section>
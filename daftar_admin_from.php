<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pendaftaran Admin | Pancasakti</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">

    <style>
        body.login-page {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                        url('foto/background.png') no-repeat center center fixed;
            background-size: cover;
            backdrop-filter: blur(5px);
            padding-bottom: 50px; 
            height: auto;
        }

        .register-box {
            width: 880px; 
            margin: 50px auto;
        }
        
        @media (max-width: 992px) { .register-box { width: 90%; } }
        @media (max-width: 576px) { .register-box { width: 95%; } }

        /* --- HEADER LOGO + JUDUL --- */
        .header-brand {
            display: flex;
            align-items: center; /* center vertikal */
            justify-content: center;
            gap: 15px;
            text-decoration: none;
        }

        /* Logo (fix ukuran selalu) */
        .header-brand img {
            width: 180px;
            height: 160px;
            object-fit: contain;
            display: block;
        }

        /* Judul */
        .login-logo b {
            color: white;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.8);
            font-size: 3rem;
            line-height: 1;
            margin: 0;
            display: block;
            position: relative;
            top: 12px;
        }

        .login-logo {
            display: flex;
            align-items: center;
        }

        /* Tablet & Laptop */
        @media (min-width: 768px) {
            .header-brand {
                flex-direction: row; /* sejajar horizontal */
                gap: 9px;
            }

            .login-logo b {
                font-size: 4rem;
            }
        }

        /* HP */
        @media (max-width: 767px) {
            .header-brand {
                flex-direction: column; /* turun ke bawah */
                gap: 3px;
            }

            /* HAPUS override size supaya tidak mengecil */
            .header-brand img {
                width: 180px;
                height: 160px;
            }

            .login-logo b {
                margin-top: -20px;
            }
        }

        /* PERBAIKAN: Sudut tumpul agar sama dengan pendaftaran anggota */
        .card {
            border-radius: 20px !important; /* Nilai tumpul disesuaikan */
            border: none;
            background: rgba(255, 255, 255, 0.96);
            box-shadow: 0 20px 50px rgba(0,0,0,0.5) !important;
            overflow: hidden; /* Memastikan isi kartu tidak keluar dari sudut tumpul */
        }

        .btn-primary {
            background-color: #001f3f;
            border-color: #001f3f;
            border-radius: 8px;
        }
        .btn-primary:hover {
            background-color: #001122;
        }

        .form-control {
            border-radius: 8px;
        }

        #form-profil-admin {
            display: none;
        }
    </style>
</head>
<body class="hold-transition login-page">
<div class="register-box">
    
    <div class="mb-4 text-center">
        <a href="#" class="header-brand">
            
            <img src="foto/Logo.png" alt="Pancasakti Logo" class="logo">
            
            <div class="login-logo">
                <b>Pancasakti</b>
            </div>

        </a>
    </div>

    <div class="card card-outline card-primary shadow-lg">
        <div class="card-body register-card-body">
            
            <div id="section-verifikasi">
                <p class="login-box-msg"><b>Otoritas Admin Diperlukan</b><br>Silakan masukkan kode khusus untuk mendaftar sebagai pustakawan.</p>
                <div class="input-group mb-3">
                    <input type="password" id="input-kode" class="form-control" placeholder="Masukkan Kode Otoritas...">
                    <div class="input-group-append">
                        <div class="input-group-text" style="border-radius: 0 8px 8px 0;">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <button type="button" onclick="cekKode()" class="btn btn-primary btn-block">Verifikasi Kode</button>
                <a href="login_from.php" class="btn btn-link btn-block btn-sm text-muted mt-2">Bukan Admin? Kembali ke Login</a>
            </div>

            <form id="form-profil-admin" action="daftar_admin_proses.php" method="post" enctype="multipart/form-data">
                <h4 class="text-center text-navy mb-3"><b>Pendaftaran Profil Admin</b></h4>
                <hr>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Lengkap Admin</label>
                            <input type="text" name="nama_lengkap" class="form-control" placeholder="Masukan nama lengkap" required>
                        </div>
                        <div class="form-group">
                            <label>Jenis Kelamin</label>
                            <div class="btn-group-toggle d-flex" data-toggle="buttons">
                                <label class="btn btn-outline-primary flex-fill mr-1" style="border-radius: 8px;">
                                    <input type="radio" name="Jenis_kelamin" value="Laki-laki" required> <i class="fas fa-mars"></i> Laki-Laki
                                </label>
                                <label class="btn btn-outline-danger flex-fill" style="border-radius: 8px;">
                                    <input type="radio" name="Jenis_kelamin" value="Perempuan"> <i class="fas fa-venus"></i> Perempuan
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Agama">Agama</label>
                            <select class="form-control" name="Agama" required>
                                <option value="Islam">Islam</option>
                                <option value="Kristen">Kristen</option>
                                <option value="Katolik">Katolik</option>
                                <option value="Budha">Budha</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Konghucu">Konghucu</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nomor HP Aktif (WhatsApp)</label>
                            <input type="number" name="no_tlp" class="form-control" placeholder="08xxxxx" required>
                        </div>
                        <div class="form-group">
                            <label>Jabatan</label>
                            <input type="text" name="jabatan" class="form-control" placeholder="Masukan nama jabatan di perpustakaan" required>
                        </div>
                         <div class="form-group mb-3">
                            <label for="Foto">Foto Identitas (Wajah)</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="Foto" id="Foto" accept="image/*" onchange="updateLabel(this)">
                                    <label class="custom-file-label" for="Foto" style="border-radius: 8px;">Pilih Foto...</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Alamat Lengkap Rumah</label>
                    <textarea name="alamat" class="form-control" rows="3" placeholder="Masukan alamat lengkap rumah anda..." required></textarea>
                </div>
                <div class="mt-4">
                    <button type="submit" name="simpan" class="btn btn-primary btn-block mt-3">
                        LANJUTKAN BUAT AKUN <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                    <a href="login_from.php" class="btn btn-link btn-block btn-sm text-muted mt-2">Sudah punya akun? Login di sini</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Fungsi untuk mengupdate label file input saat foto dipilih
    function updateLabel(input) {
        var fileName = input.files[0].name;
        $(input).next('.custom-file-label').html(fileName);
    }

    function cekKode() {
        const kodeInput = $('#input-kode').val();
        const kodeBenar = "1"; // Silakan ganti kodenya di sini

        if (kodeInput === kodeBenar) {
            Swal.fire({
                icon: 'success',
                title: 'Kode Benar!',
                text: 'Silakan lengkapi profil admin Anda.',
                showConfirmButton: false,
                timer: 1500
            });
            
            $('#section-verifikasi').fadeOut(500, function() {
                $('#form-profil-admin').fadeIn(500);
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Kode Salah!',
                text: 'Anda tidak memiliki otoritas untuk mendaftar sebagai Admin.',
                showCancelButton: true,
                confirmButtonText: 'Coba Lagi',
                cancelButtonText: 'Kembali ke Login',
                confirmButtonColor: '#001f3f',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.cancel) {
                    window.location.href = 'login_from.php';
                }
            });
        }
    }
</script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pendaftaran Guru | Perpustakaan Pancasakti</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">

    <style>
        body.login-page {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), 
                        url('foto/background.png') no-repeat center center fixed;
            background-size: cover;
            backdrop-filter: blur(5px);
            padding-bottom: 50px; 
            height: auto;
        }

        /* Lebar Kertas Form */
        .register-box {
            width: 880px; 
            margin: 50px auto;
        }
        
        /* Responsivitas Lebar Kertas */
        @media (max-width: 992px) {
            .register-box { width: 90%; }
        }
        @media (max-width: 576px) {
            .register-box { width: 95%; }
        }

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
            top: 10px;
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

        /* --- MODEL KERTAS FORM --- */
        .card {
            border-radius: 15px;
            overflow: hidden;
            border: none;
            background: rgba(255, 255, 255, 0.96);
            box-shadow: 0 20px 50px rgba(0,0,0,0.5) !important;
        }
        
        .form-control {
            border-radius: 8px;
        }

        .btn-group-toggle .btn {
            border-radius: 8px !important;
            margin-bottom: 5px;
        }

        .register-card-body {
            padding: 30px;
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
    
    <div class="card card-outline card-success shadow-lg">
        <div class="card-body register-card-body p-4">
            <h3 class="text-center font-weight-bold text-success mb-3">Pendaftaran Anggota Guru</h3>
            <p class="login-box-msg text-muted mb-4">Lengkapi data diri Bapak/Ibu Guru untuk melanjutkan ke tahap buat akun</p>

            <form action="proses_Admin/tambah/anggota_guru_proses.php" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 border-right pr-md-4">
                        <div class="form-group mb-3">
                            <label for="NIP">NIP</label>
                            <input type="number" class="form-control" name="NIP" placeholder="Masukkan Nomor NIP" required>
                        </div>
                        <div class="form-group mb-3">                    
                            <label for="Nama_guru">Nama Lengkap Guru</label>
                            <input type="text" class="form-control" name="Nama_guru" placeholder="Masukkan Nama Lengkap Beserta Gelar" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Jenis Kelamin</label>
                            <div class="btn-group-toggle d-flex" data-toggle="buttons">
                                <label class="btn btn-outline-primary flex-fill mr-1">
                                    <input type="radio" name="Jenis_kelamin" value="Laki-laki" required> <i class="fas fa-mars"></i> Laki-Laki
                                </label>
                                <label class="btn btn-outline-danger flex-fill">
                                    <input type="radio" name="Jenis_kelamin" value="Perempuan"> <i class="fas fa-venus"></i> Perempuan
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 pl-md-4">
                        <div class="form-group mb-3">
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
                        <div class="form-group mb-3">
                            <label for="No_tlp">Nomor HP Aktif (WhatsApp)</label>
                            <input type="number" class="form-control" name="No_tlp" placeholder="08xxxx" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="Foto">Foto Identitas (Wajah)</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="Foto" id="Foto" accept="image/*">
                                    <label class="custom-file-label" for="Foto">Pilih Foto...</label>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <img id="previewFoto" src="#" alt="Pratinjau" style="display:none; max-width: 100%; height: 130px; object-fit: cover; border-radius: 10px; border: 2px solid #28a745;">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="Alamat">Alamat Rumah Lengkap</label>
                            <textarea class="form-control" name="Alamat" rows="2" placeholder="Masukkan alamat lengkap rumah Bapak/Ibu Guru saat ini..." required></textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" name="simpan" class="btn btn-success btn-block shadow">
                        <b>LANJUTKAN BUAT AKUN <i class="fas fa-arrow-right ml-1"></i></b>
                    </button>
                    <a href="login_from.php" class="btn btn-link btn-block btn-sm text-muted mt-2">Sudah punya akun? Login di sini</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>

<script>
    // Menampilkan nama file di label input
    $(".custom-file-input").on("change", function() {
      var fileName = $(this).val().split("\\").pop();
      $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
      
      // Menampilkan pratinjau gambar secara langsung
      const file = this.files[0];
      if (file) {
        let reader = new FileReader();
        reader.onload = function(event) {
          $("#previewFoto").attr("src", event.target.result).show();
        };
        reader.readAsDataURL(file);
      }
    });
</script>

</body>
</html>
<?php
include '../config/koneksi.php'; // Pastikan path koneksi benar

if (isset($_POST['simpan_final']) || isset($_POST['simpan_mandiri'])) {
    
    // 1. Ambil Data Akun dari Form
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // 2. Cek apakah Username sudah terdaftar
    $cek_user = mysqli_query($db, "SELECT username FROM users WHERE username = '$username'");
    if (mysqli_num_rows($cek_user) > 0) {
        echo "<script>alert('Gagal! Username sudah digunakan. Silahkan cari yang lain.'); window.history.back();</script>";
        exit();
    }

    // 3. Deteksi Tipe Anggota & Ambil Data Session
    if (isset($_SESSION['temp_siswa'])) {
        $data = $_SESSION['temp_siswa'];
        $jenis_agt = 'Siswa';
        $target_page = 'anggota_siswa';
    } elseif (isset($_SESSION['temp_guru'])) {
        $data = $_SESSION['temp_guru'];
        $jenis_agt = 'Guru';
        $target_page = 'anggota_guru';
    } elseif (isset($_SESSION['temp_kelas'])) {
        $data = $_SESSION['temp_kelas'];
        $jenis_agt = 'Kelas';
        $target_page = 'anggota_kelas';
    } else {
        // Jika session hilang, tentukan arah balik (ke admin atau ke login mandiri)
        $url_balik = isset($_SESSION['Id_admin']) ? '../../index_Admin.php' : '../../login_from.php';
        echo "<script>alert('Sesi kadaluarsa! Silahkan isi profil kembali.'); window.location='$url_balik';</script>";
        exit();
    }

    // --- PROSES INSERT DATABASE ---

    // A. Simpan ke Tabel Users (Role 'anggota')
    $insert_user = mysqli_query($db, "INSERT INTO users (username, password, role) VALUES ('$username', '$password', 'anggota')");
    $id_user = mysqli_insert_id($db);

    if ($insert_user) {
        // B. Simpan ke Tabel Anggota (Induk)
        $tgl_daftar = date('Y-m-d');
        $insert_anggota = mysqli_query($db, "INSERT INTO anggota (Id_user, Jenis_anggota, Tgl_daftar, Status) VALUES ('$id_user', '$jenis_agt', '$tgl_daftar', 'Tidak Aktif')");
        $id_anggota = mysqli_insert_id($db);

        // C. Simpan ke Tabel Detail (Siswa / Guru / Kelas)
        if ($jenis_agt == 'Siswa') {
            $query_final = "INSERT INTO anggota_siswa (Id_anggota, NISN, Nama_siswa, Jenis_kelamin, Agama, Kelas, Jurusan, Alamat, No_tlp, Foto) 
                            VALUES ('$id_anggota', '{$data['NISN']}', '{$data['Nama_siswa']}', '{$data['Jenis_kelamin']}', '{$data['Agama']}', '{$data['Kelas']}', '{$data['Jurusan']}', '{$data['Alamat']}', '{$data['No_tlp']}', '{$data['Foto']}')";
        } elseif ($jenis_agt == 'Guru') {
            $query_final = "INSERT INTO anggota_guru (Id_anggota, NIP, Nama_guru, Jenis_kelamin, Agama, Alamat, No_tlp, Foto) 
                            VALUES ('$id_anggota', '{$data['NIP']}', '{$data['Nama_guru']}', '{$data['Jenis_kelamin']}', '{$data['Agama']}', '{$data['Alamat']}', '{$data['No_tlp']}', '{$data['Foto']}')";
        } elseif ($jenis_agt == 'Kelas') {
            $query_final = "INSERT INTO anggota_kelas (Id_anggota, Nama_kelas, Wali_kelas, Jumlah_siswa, Penanggung_jawab) 
                            VALUES ('$id_anggota', '{$data['Nama_kelas']}', '{$data['Wali_kelas']}', '{$data['Jumlah_siswa']}', '{$data['Penanggung_jawab']}')";
        }

        $eksekusi_final = mysqli_query($db, $query_final);

        if ($eksekusi_final) {
            // Hapus session temp
            unset($_SESSION['temp_siswa'], $_SESSION['temp_guru'], $_SESSION['temp_kelas']);
            
            // --- LOGIKA PENGALIHAN (REDIRECT) ---
            if (isset($_SESSION['Id_admin'])) {
                // JIKA ADMIN yang mendaftarkan
                echo "<script>
                        alert('Berhasil! Akun " . $jenis_agt . " telah dibuat oleh Admin.'); 
                        window.location='../index_Admin.php?page=" . $target_page . "';
                      </script>";
            } else {
                // JIKA PENDAFTARAN MANDIRI
                echo "<script>
                        alert('Pendaftaran Berhasil! Silahkan login dengan akun yang baru dibuat.'); 
                        window.location='../login_from.php';
                      </script>";
            }
        } else {
            echo "<script>alert('Gagal menyimpan detail anggota!'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Gagal membuat akun login!'); window.history.back();</script>";
    }
}
?>
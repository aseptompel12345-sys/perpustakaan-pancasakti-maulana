<?php
include "config/koneksi.php"; // Letak folder config sejajar

if (!isset($_SESSION['id_user'])) {
    header("Location: login_from.php");
    exit();
}

$id_user_login  = $_SESSION['id_user'];
$jenis_anggota  = $_SESSION['jenis_anggota'];
$id_agt_session = $_SESSION['Id_anggota'];

if (isset($_POST['proses_update_anggota'])) {
    // 1. Amankan Data Akun Utama (Username)
    $username = mysqli_real_escape_string($db, $_POST['Username']);
    $pwd_lama  = $_POST['password_lama'];
    $pwd_baru  = $_POST['password_baru'];
    $konfirmasi = $_POST['konfirmasi_password'];

    // 2. Cek Duplikasi Username
    $cek_user = mysqli_query($db, "SELECT * FROM users WHERE Username = '$username' AND Id_user != '$id_user_login'");
    if (mysqli_num_rows($cek_user) > 0) {
        echo "<script>alert('Gagal! Username sudah dipakai anggota lain.'); window.location.href='index_Anggota.php?page=pengaturan_anggota';</script>";
        exit();
    }

    // Update Username di tabel users
    mysqli_query($db, "UPDATE users SET Username = '$username' WHERE Id_user = '$id_user_login'");

    // =========================================================================
    // 3. FASE UPDATE DATA BERDASARKAN JENIS ANGGOTA (SISWA / GURU / KELAS)
    // =========================================================================
    
    if ($jenis_anggota == 'Siswa') {
        $nisn   = mysqli_real_escape_string($db, $_POST['NISN']);
        $nama   = mysqli_real_escape_string($db, $_POST['Nama_siswa']);
        $jk     = mysqli_real_escape_string($db, $_POST['Jenis_kelamin']);
        $agama  = mysqli_real_escape_string($db, $_POST['Agama']);
        $kelas  = mysqli_real_escape_string($db, $_POST['Kelas']);
        $jurusan = mysqli_real_escape_string($db, $_POST['Jurusan']);
        $no_tlp = mysqli_real_escape_string($db, $_POST['No_tlp']);
        $alamat = mysqli_real_escape_string($db, $_POST['Alamat']);

        mysqli_query($db, "UPDATE anggota_siswa SET NISN='$nisn', Nama_siswa='$nama', Jenis_kelamin='$jk', Agama='$agama', Kelas='$kelas', Jurusan='$jurusan', No_tlp='$no_tlp', Alamat='$alamat' WHERE Id_anggota='$id_agt_session'");
        $_SESSION['nama_user'] = $nama;

        // Proses File Foto Siswa
        if ($_FILES['Foto']['name'] != '') {
            $nama_file = time() . '_' . $_FILES['Foto']['name'];
            if (move_uploaded_file($_FILES['Foto']['tmp_name'], "FOTOS/foto_siswa/" . $nama_file)) {
                $q_f = mysqli_query($db, "SELECT Foto FROM anggota_siswa WHERE Id_anggota='$id_agt_session'");
                $d_f = mysqli_fetch_assoc($q_f);
                if(!empty($d_f['Foto']) && file_exists("FOTOS/foto_siswa/".$d_f['Foto'])) { unlink("FOTOS/foto_siswa/".$d_f['Foto']); }
                
                mysqli_query($db, "UPDATE anggota_siswa SET Foto='$nama_file' WHERE Id_anggota='$id_agt_session'");
                $_SESSION['foto_user'] = $nama_file;
            }
        }

    } else if ($jenis_anggota == 'Guru') {
        $nip    = mysqli_real_escape_string($db, $_POST['NIP']);
        $nama   = mysqli_real_escape_string($db, $_POST['Nama_guru']);
        $jk     = mysqli_real_escape_string($db, $_POST['Jenis_kelamin']);
        $agama  = mysqli_real_escape_string($db, $_POST['Agama']);
        $no_tlp = mysqli_real_escape_string($db, $_POST['No_tlp']);
        $alamat = mysqli_real_escape_string($db, $_POST['Alamat']);

        mysqli_query($db, "UPDATE anggota_guru SET NIP='$nip', Nama_guru='$nama', Jenis_kelamin='$jk', Agama='$agama', No_tlp='$no_tlp', Alamat='$alamat' WHERE Id_anggota='$id_agt_session'");
        $_SESSION['nama_user'] = $nama;

        // Proses File Foto Guru
        if ($_FILES['Foto']['name'] != '') {
            $nama_file = time() . '_' . $_FILES['Foto']['name'];
            if (move_uploaded_file($_FILES['Foto']['tmp_name'], "FOTOS/foto_guru/" . $nama_file)) {
                $q_f = mysqli_query($db, "SELECT Foto FROM anggota_guru WHERE Id_anggota='$id_agt_session'");
                $d_f = mysqli_fetch_assoc($q_f);
                if(!empty($d_f['Foto']) && file_exists("FOTOS/foto_guru/".$d_f['Foto'])) { unlink("FOTOS/foto_guru/".$d_f['Foto']); }
                
                mysqli_query($db, "UPDATE anggota_guru SET Foto='$nama_file' WHERE Id_anggota='$id_agt_session'");
                $_SESSION['foto_user'] = $nama_file;
            }
        }

    } else if ($jenis_anggota == 'Kelas') {
        $nama_k = mysqli_real_escape_string($db, $_POST['Nama_kelas']);
        $wali   = mysqli_real_escape_string($db, $_POST['Wali_kelas']);
        $jml    = mysqli_real_escape_string($db, $_POST['Jumlah_siswa']);
        $pj     = mysqli_real_escape_string($db, $_POST['Penanggung_jawab']);

        mysqli_query($db, "UPDATE anggota_kelas SET Nama_kelas='$nama_k', Wali_kelas='$wali', Jumlah_siswa='$jml', Penanggung_jawab='$pj' WHERE Id_anggota='$id_agt_session'");
        $_SESSION['nama_user'] = $nama_k;
        // Catatan: Entitas Kelas tidak memproses file gambar sesuai kesepakatan
    }

    // =========================================================================
    // 4. PROTOKOL VALIDASI PASSWORD SECURE
    // =========================================================================
    if (!empty($pwd_lama)) {
        $q_pwd = mysqli_query($db, "SELECT Password FROM users WHERE Id_user = '$id_user_login'");
        $d_pwd = mysqli_fetch_assoc($q_pwd);

        if (password_verify($pwd_lama, $d_pwd['Password'])) {
            if (!empty($pwd_baru) && $pwd_baru === $konfirmasi) {
                $password_secure = password_hash($pwd_baru, PASSWORD_DEFAULT);
                mysqli_query($db, "UPDATE users SET Password = '$password_secure' WHERE Id_user = '$id_user_login'");
            } else {
                echo "<script>alert('Profil Terupdate! Namun kata sandi baru tidak cocok dengan konfirmasi.'); window.location.href='index_Anggota.php?page=pengaturan_anggota';</script>";
                exit();
            }
        } else {
            echo "<script>alert('Profil Terupdate! Namun gagal ganti sandi karena password lama salah.'); window.location.href='index_Anggota.php?page=pengaturan_anggota';</script>";
            exit();
        }
    }

    echo "<script>alert('Sukses! Seluruh data akun profil anggota berhasil diperbarui.'); window.location.href='index_Anggota.php?page=pengaturan_anggota';</script>";
    exit();
}
?>
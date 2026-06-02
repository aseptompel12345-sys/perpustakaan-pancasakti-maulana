<?php
include 'config/koneksi.php';

if (isset($_POST['login'])) {
    $username_input = mysqli_real_escape_string($db, $_POST['username']);
    $password_input = $_POST['password'];

    $query_user = mysqli_query($db, "SELECT * FROM users WHERE Username = '$username_input'");
    
    if (mysqli_num_rows($query_user) === 1) {
        $row = mysqli_fetch_assoc($query_user);

        if (password_verify($password_input, $row['Password'])) {
            
            $_SESSION['id_user']      = $row['Id_user'];
            $_SESSION['role']         = $row['Role'];
            $_SESSION['status_login'] = true;
            
            $id_u = $row['Id_user'];

            if ($row['Role'] == 'admin') {
                mysqli_query($db, "UPDATE admin SET Status = 'Aktif' WHERE Id_user = '$id_u'");
            } else if ($row['Role'] == 'anggota') {
                mysqli_query($db, "UPDATE anggota SET Status = 'Aktif' WHERE Id_user = '$id_u'");
            }

            if ($row['Role'] == 'admin') {
                $q_admin = mysqli_query($db, "SELECT Nama_lengkap, Foto FROM admin WHERE Id_user = '$id_u'");
                $d_admin = mysqli_fetch_assoc($q_admin);

                $_SESSION['Id_admin']  = $id_u;
                $_SESSION['nama_user'] = $d_admin['Nama_lengkap'];
                $_SESSION['foto_user'] = $d_admin['Foto'];
                header("Location: index_Admin.php");

            } else if ($row['Role'] == 'anggota') {
                $q_agt = mysqli_query($db, "SELECT Id_anggota, Jenis_anggota FROM anggota WHERE Id_user = '$id_u'");
                $d_agt = mysqli_fetch_assoc($q_agt);
                $id_agt = $d_agt['Id_anggota'];
                $jenis  = $d_agt['Jenis_anggota'];

                $_SESSION['Id_anggota']    = $id_agt;
                $_SESSION['jenis_anggota'] = $jenis;

                if ($jenis == 'Siswa') {
                    $q_p = mysqli_query($db, "SELECT Nama_siswa as nama, Foto FROM anggota_siswa WHERE Id_anggota = '$id_agt'");
                    $d_p = mysqli_fetch_assoc($q_p);
                    $_SESSION['foto_user'] = $d_p['Foto'];
                } else if ($jenis == 'Guru') {
                    $q_p = mysqli_query($db, "SELECT Nama_guru as nama, Foto FROM anggota_guru WHERE Id_anggota = '$id_agt'");
                    $d_p = mysqli_fetch_assoc($q_p);
                    $_SESSION['foto_user'] = $d_p['Foto'];
                } else {
                    // UNTUK KELAS: Hapus kolom 'Foto' dari SELECT karena tidak ada di tabelnya
                    $q_p = mysqli_query($db, "SELECT Nama_kelas as nama FROM anggota_kelas WHERE Id_anggota = '$id_agt'");
                    $d_p = mysqli_fetch_assoc($q_p);
                    // Kita set kosong agar sidebar tahu harus pakai Logo.png
                    $_SESSION['foto_user'] = ""; 
                }

                $_SESSION['nama_user'] = $d_p['nama'];
                header("Location: index_Anggota.php");
            }
            exit();

        } else {
            echo "<script>alert('Password Salah!'); window.location.href='login_from.php';</script>";
        }
    } else {
        echo "<script>alert('Username tidak ditemukan!'); window.location.href='login_from.php';</script>";
    }
}
?>
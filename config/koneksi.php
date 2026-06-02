<?php
    // 1. Atur durasi sesi agar awet 1 bulan (30 hari)
    $durasi_sebulan = 30 * 24 * 60 * 60; // dalam detik
    ini_set('session.gc_maxlifetime', $durasi_sebulan);
    session_set_cookie_params($durasi_sebulan);

    // 2. Jalankan session secara otomatis jika belum berjalan
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // 3. Set default timezone
    date_default_timezone_set("ASIA/JAKARTA");

    // 4. Parameter koneksi database
    $server    = "localhost";
    $username  = "root";
    $password  = "";
    $database  = "simulasi_uji_com";

    // 5. Koneksi database
    $db = mysqli_connect($server, $username, $password, $database);

    // 6. Cek koneksi
    if (!$db) {
        die('Koneksi Database Gagal : '. mysqli_connect_error());
    }
?>
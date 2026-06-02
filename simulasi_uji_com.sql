-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 29, 2026 at 06:14 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simulasi_uji_com`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `Id_admin` int(11) NOT NULL,
  `Id_user` int(11) NOT NULL,
  `Nama_lengkap` varchar(100) NOT NULL,
  `Jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `Agama` varchar(100) NOT NULL,
  `Alamat` text NOT NULL,
  `No_tlp` varchar(100) NOT NULL,
  `Jabatan` varchar(100) NOT NULL,
  `Foto` varchar(100) NOT NULL,
  `Tgl_daftar` date NOT NULL,
  `Status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`Id_admin`, `Id_user`, `Nama_lengkap`, `Jenis_kelamin`, `Agama`, `Alamat`, `No_tlp`, `Jabatan`, `Foto`, `Tgl_daftar`, `Status`) VALUES
(1, 1, 'Maulana Ibrahim Muvik', 'Laki-laki', 'Islam', 'Ds.Kertawinangun, Blok.Desa, RT.001 RW.001', '085566778899', 'Developer', '1780024972_download.jfif', '2026-05-29', 'Aktif');

-- --------------------------------------------------------

--
-- Table structure for table `anggota`
--

CREATE TABLE `anggota` (
  `Id_anggota` int(11) NOT NULL,
  `Id_user` int(11) NOT NULL,
  `Jenis_anggota` enum('Siswa','Guru','Kelas') NOT NULL,
  `Tgl_daftar` date NOT NULL,
  `Status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `anggota`
--

INSERT INTO `anggota` (`Id_anggota`, `Id_user`, `Jenis_anggota`, `Tgl_daftar`, `Status`) VALUES
(1, 2, 'Siswa', '2026-05-29', 'Tidak Aktif'),
(2, 3, 'Guru', '2026-05-29', 'Tidak Aktif'),
(3, 4, 'Kelas', '2026-05-29', 'Aktif');

-- --------------------------------------------------------

--
-- Table structure for table `anggota_guru`
--

CREATE TABLE `anggota_guru` (
  `Id_guru` int(11) NOT NULL,
  `Id_anggota` int(11) NOT NULL,
  `NIP` varchar(30) NOT NULL,
  `Nama_guru` varchar(200) NOT NULL,
  `Jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `Agama` varchar(500) NOT NULL,
  `Alamat` text NOT NULL,
  `No_tlp` varchar(100) NOT NULL,
  `Foto` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `anggota_guru`
--

INSERT INTO `anggota_guru` (`Id_guru`, `Id_anggota`, `NIP`, `Nama_guru`, `Jenis_kelamin`, `Agama`, `Alamat`, `No_tlp`, `Foto`) VALUES
(1, 2, '1122334455667', 'Roy', 'Laki-laki', 'Islam', 'Desa. Bantarjati', '0866554433221', '29052026103133_1122334455667.jfif');

-- --------------------------------------------------------

--
-- Table structure for table `anggota_kelas`
--

CREATE TABLE `anggota_kelas` (
  `Id_kelas` int(11) NOT NULL,
  `Id_anggota` int(11) NOT NULL,
  `Nama_kelas` varchar(50) NOT NULL,
  `Wali_kelas` varchar(100) NOT NULL,
  `Jumlah_siswa` int(11) NOT NULL,
  `Penanggung_jawab` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `anggota_kelas`
--

INSERT INTO `anggota_kelas` (`Id_kelas`, `Id_anggota`, `Nama_kelas`, `Wali_kelas`, `Jumlah_siswa`, `Penanggung_jawab`) VALUES
(1, 3, '11 PPLG', 'Pak. Jajang Wahidin Spd.i', 32, 'Galih, Hafid');

-- --------------------------------------------------------

--
-- Table structure for table `anggota_siswa`
--

CREATE TABLE `anggota_siswa` (
  `Id_siswa` int(11) NOT NULL,
  `Id_anggota` int(11) NOT NULL,
  `NISN` varchar(20) NOT NULL,
  `Nama_siswa` varchar(100) NOT NULL,
  `Jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `Agama` varchar(20) NOT NULL,
  `Kelas` int(11) NOT NULL,
  `Jurusan` varchar(20) NOT NULL,
  `Alamat` text NOT NULL,
  `No_tlp` varchar(100) NOT NULL,
  `Foto` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `anggota_siswa`
--

INSERT INTO `anggota_siswa` (`Id_siswa`, `Id_anggota`, `NISN`, `Nama_siswa`, `Jenis_kelamin`, `Agama`, `Kelas`, `Jurusan`, `Alamat`, `No_tlp`, `Foto`) VALUES
(1, 1, '9988776655443', 'Maulana', 'Laki-laki', 'Islam', 11, 'PPLG', 'Desa. Kertawinangun 1', '085566778899', '29052026102653_9988776655443.png');

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `Foto` varchar(50) NOT NULL,
  `Id_buku` int(11) NOT NULL,
  `ISBN` varchar(30) NOT NULL,
  `Judul` varchar(150) NOT NULL,
  `Pengarang` varchar(100) NOT NULL,
  `Penerbit` varchar(100) NOT NULL,
  `Tahun_terbit` year(4) NOT NULL,
  `Bidang_buku` enum('Pendidikan','Fiksi','Nonfiksi','Lain-lain') NOT NULL,
  `Lokasi_rak` varchar(500) NOT NULL,
  `Stok_awal_buku` int(11) NOT NULL,
  `Stok_buku_tersedia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`Foto`, `Id_buku`, `ISBN`, `Judul`, `Pengarang`, `Penerbit`, `Tahun_terbit`, `Bidang_buku`, `Lokasi_rak`, `Stok_awal_buku`, `Stok_buku_tersedia`) VALUES
('29052026103544_6a1909909afad.webp', 1, '7979483823947', 'Astronomi', 'Maulana', 'Maulana', '2000', 'Pendidikan', 'Mapel Umum', 200, 168),
('29052026103744_6a190a0803264.jfif', 2, '88975897289', 'Laut Bercerita', 'Maulana', 'Maulana', '0000', 'Fiksi', 'Mapel Umum', 200, 185),
('29052026104031_6a190aafd3df7.webp', 3, '78558765578', 'Top Places to  View Mount Fuji', 'Maulana', 'Maulana', '2002', 'Nonfiksi', 'Mapel Umum', 200, 199);

-- --------------------------------------------------------

--
-- Table structure for table `denda`
--

CREATE TABLE `denda` (
  `Id_denda` int(11) NOT NULL,
  `Nilai` int(11) NOT NULL,
  `Tgl_diatur` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `denda`
--

INSERT INTO `denda` (`Id_denda`, `Nilai`, `Tgl_diatur`) VALUES
(1, 2000, '2026-05-29');

-- --------------------------------------------------------

--
-- Table structure for table `kunjungan`
--

CREATE TABLE `kunjungan` (
  `Id_kunjungan` int(11) NOT NULL,
  `Tgl_kunjungan` date NOT NULL,
  `Jam_kunjungan` time NOT NULL,
  `Foto_kunjungan` text NOT NULL,
  `Id_anggota` int(11) NOT NULL,
  `Nama_pengunjung` varchar(190) NOT NULL,
  `Jenis_anggota` enum('Siswa','Guru','Kelas') NOT NULL,
  `Detail_identitas` text NOT NULL,
  `Keperluan` varchar(100) NOT NULL,
  `Admin_pemberi_izin` varchar(10000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kunjungan`
--

INSERT INTO `kunjungan` (`Id_kunjungan`, `Tgl_kunjungan`, `Jam_kunjungan`, `Foto_kunjungan`, `Id_anggota`, `Nama_pengunjung`, `Jenis_anggota`, `Detail_identitas`, `Keperluan`, `Admin_pemberi_izin`) VALUES
(1, '2026-05-29', '10:41:32', 'FOTOS/foto_siswa/29052026102653_9988776655443.png', 1, 'Maulana', 'Siswa', 'Kelas: 11\r\nJurusan: PPLG\r\nNo. Tlp: 085566778899', 'Membaca', 'Maulana Ibrahim Muvik'),
(2, '2026-05-29', '10:45:48', 'FOTOS/foto_guru/29052026103133_1122334455667.jfif', 2, 'Roy', 'Guru', 'Jabatan: Guru / Staf Pengajar\r\nNo. Tlp: 0866554433221', 'Berdiskusi', 'Maulana Ibrahim Muvik'),
(3, '2026-05-29', '10:47:37', 'foto/Logo.png', 3, '11 PPLG', 'Kelas', 'Wali Kelas: Pak. Jajang Wahidin Spd.i\r\nPenanggung Jawab: Galih, Hafid', 'Mengerjakan Tugas', 'Maulana Ibrahim Muvik');

-- --------------------------------------------------------

--
-- Table structure for table `notif_penerima`
--

CREATE TABLE `notif_penerima` (
  `id_notif_penerima` int(11) NOT NULL,
  `id_transaksi` int(11) DEFAULT NULL,
  `tabel_asal` varchar(100) DEFAULT NULL,
  `id_admin` int(11) DEFAULT NULL,
  `waktu_terima` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `Id_peminjaman` int(11) NOT NULL,
  `Foto_peminjam` varchar(10000) NOT NULL,
  `Id_anggota` int(11) NOT NULL,
  `Nama_peminjam` varchar(100) NOT NULL,
  `Detail_identitas` text NOT NULL,
  `Jenis_anggota` varchar(100) NOT NULL,
  `Foto_buku` varchar(1900) NOT NULL,
  `Id_buku` int(11) NOT NULL,
  `Judul_buku` varchar(100) NOT NULL,
  `Lokasi_rak` varchar(100) NOT NULL,
  `Jumlah` int(11) NOT NULL,
  `Sisa_pinjam` int(11) NOT NULL,
  `Tgl_pinjam` date NOT NULL,
  `Tgl_jatuh_tempo` date NOT NULL,
  `Status` varchar(100) NOT NULL,
  `Admin_pemberi_izin` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`Id_peminjaman`, `Foto_peminjam`, `Id_anggota`, `Nama_peminjam`, `Detail_identitas`, `Jenis_anggota`, `Foto_buku`, `Id_buku`, `Judul_buku`, `Lokasi_rak`, `Jumlah`, `Sisa_pinjam`, `Tgl_pinjam`, `Tgl_jatuh_tempo`, `Status`, `Admin_pemberi_izin`) VALUES
(1, 'FOTOS/foto_siswa/29052026102653_9988776655443.png', 1, 'Maulana', 'Kelas: 11 | Jurusan: PPLG', 'Siswa', 'FOTOS/foto_sampul_buku/29052026103544_6a1909909afad.webp', 1, 'Astronomi', 'Mapel Umum', 1, 0, '2026-05-29', '2026-06-05', 'Selesai', 'Maulana Ibrahim Muvik'),
(2, 'FOTOS/foto_siswa/29052026102653_9988776655443.png', 1, 'Maulana', 'Kelas: 11 | Jurusan: PPLG', 'Siswa', 'FOTOS/foto_sampul_buku/29052026103744_6a190a0803264.jfif', 2, 'Laut Bercerita', 'Mapel Umum', 1, 1, '2026-05-29', '2026-06-05', 'Dipinjam', 'Maulana Ibrahim Muvik'),
(3, 'FOTOS/foto_guru/29052026103133_1122334455667.jfif', 2, 'Roy', 'No.Tlp: 0866554433221 | Alamat: Desa. Bantarjati', 'Guru', 'FOTOS/foto_sampul_buku/29052026104031_6a190aafd3df7.webp', 3, 'Top Places to  View Mount Fuji', 'Mapel Umum', 1, 1, '2026-05-29', '2026-06-05', 'Dipinjam', 'Maulana Ibrahim Muvik'),
(4, 'foto/logo.png', 3, '11 PPLG', 'Anggota Kelas: 11 PPLG', 'Kelas', 'FOTOS/foto_sampul_buku/29052026103544_6a1909909afad.webp', 1, 'Astronomi', 'Mapel Umum', 32, 32, '2026-05-29', '2026-05-29', 'Dipinjam', 'Maulana Ibrahim Muvik'),
(5, 'foto/logo.png', 3, '11 PPLG', 'Anggota Kelas: 11 PPLG', 'Kelas', 'FOTOS/foto_sampul_buku/29052026103744_6a190a0803264.jfif', 2, 'Laut Bercerita', 'Mapel Umum', 32, 14, '2026-05-29', '2026-05-29', 'Sebagian', 'Maulana Ibrahim Muvik'),
(6, 'foto/logo.png', 3, '11 PPLG', 'Anggota Kelas: 11 PPLG', 'Kelas', 'FOTOS/foto_sampul_buku/29052026104031_6a190aafd3df7.webp', 3, 'Top Places to  View Mount Fuji', 'Mapel Umum', 32, 0, '2026-05-29', '2026-05-29', 'Selesai', 'Maulana Ibrahim Muvik');

-- --------------------------------------------------------

--
-- Table structure for table `pengembalian`
--

CREATE TABLE `pengembalian` (
  `Id_pengembalian` int(11) NOT NULL,
  `Id_peminjaman` int(11) NOT NULL,
  `Foto_peminjam` varchar(10000) NOT NULL,
  `Id_anggota` int(11) NOT NULL,
  `Nama_peminjam` varchar(100) NOT NULL,
  `Detail_identitas` text NOT NULL,
  `Jenis_anggota` varchar(100) NOT NULL,
  `Foto_buku` varchar(200) NOT NULL,
  `Id_buku` varchar(100) NOT NULL,
  `Judul_buku` varchar(100) NOT NULL,
  `Lokasi_rak` varchar(100) NOT NULL,
  `Tgl_kembali` date NOT NULL,
  `Jml_kembali` int(11) NOT NULL,
  `Denda` int(11) NOT NULL,
  `Status` varchar(100) NOT NULL,
  `Admin_pemberi_izin` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengembalian`
--

INSERT INTO `pengembalian` (`Id_pengembalian`, `Id_peminjaman`, `Foto_peminjam`, `Id_anggota`, `Nama_peminjam`, `Detail_identitas`, `Jenis_anggota`, `Foto_buku`, `Id_buku`, `Judul_buku`, `Lokasi_rak`, `Tgl_kembali`, `Jml_kembali`, `Denda`, `Status`, `Admin_pemberi_izin`) VALUES
(1, 1, 'FOTOS/foto_siswa/29052026102653_9988776655443.png', 1, 'Maulana', 'Kelas: 11 | Jurusan: PPLG', 'Siswa', 'FOTOS/foto_sampul_buku/29052026103544_6a1909909afad.webp', '1', 'Astronomi', 'Mapel Umum', '2026-05-29', 1, 0, 'Selesai', 'Maulana Ibrahim Muvik'),
(2, 6, 'foto/logo.png', 3, '11 PPLG', 'Anggota Kelas: 11 PPLG', 'Kelas', 'FOTOS/foto_sampul_buku/29052026104031_6a190aafd3df7.webp', '3', 'Top Places to  View Mount Fuji', 'Mapel Umum', '2026-05-29', 32, 0, 'Selesai', 'Maulana Ibrahim Muvik'),
(3, 5, 'foto/logo.png', 3, '11 PPLG', 'Anggota Kelas: 11 PPLG', 'Kelas', 'FOTOS/foto_sampul_buku/29052026103744_6a190a0803264.jfif', '2', 'Laut Bercerita', 'Mapel Umum', '2026-05-29', 18, 0, 'Sebagian', 'Maulana Ibrahim Muvik');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `Id_user` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(200) NOT NULL,
  `Role` enum('admin','anggota') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`Id_user`, `Username`, `Password`, `Role`) VALUES
(1, 'Maulana Ibrahim Muvik', '$2y$10$fZczA37XxZfM0ERUNDKkd.iXnaCuWrUWtwQZqt0drrjn9jzJnRcrC', 'admin'),
(2, 'Maulana', '$2y$10$StLmqUh3E5B1eJEU3xtDjuF11bVX81Ztqm8Vp.Kec4HnM5XdMen.i', 'anggota'),
(3, 'Roy', '$2y$10$/XWuH9ptWbRd1158zbEjKuxMdePzEdLqTMfi3GP9VgmNQNWyAgms.', 'anggota'),
(4, '11 PPLG', '$2y$10$ecb8AUYVDnShyEsAfj4i5.JOfd/gtB.GXdYlQqa53GM/QUoef60py', 'anggota');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`Id_admin`),
  ADD KEY `admin_ibfk_1` (`Id_user`);

--
-- Indexes for table `anggota`
--
ALTER TABLE `anggota`
  ADD PRIMARY KEY (`Id_anggota`),
  ADD KEY `anggota_ibfk_1` (`Id_user`);

--
-- Indexes for table `anggota_guru`
--
ALTER TABLE `anggota_guru`
  ADD PRIMARY KEY (`Id_guru`),
  ADD KEY `Id_anggota` (`Id_anggota`);

--
-- Indexes for table `anggota_kelas`
--
ALTER TABLE `anggota_kelas`
  ADD PRIMARY KEY (`Id_kelas`),
  ADD KEY `Id_anggota` (`Id_anggota`);

--
-- Indexes for table `anggota_siswa`
--
ALTER TABLE `anggota_siswa`
  ADD PRIMARY KEY (`Id_siswa`),
  ADD KEY `Id_anggota` (`Id_anggota`);

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`Id_buku`);

--
-- Indexes for table `denda`
--
ALTER TABLE `denda`
  ADD PRIMARY KEY (`Id_denda`);

--
-- Indexes for table `kunjungan`
--
ALTER TABLE `kunjungan`
  ADD PRIMARY KEY (`Id_kunjungan`),
  ADD KEY `Id_anggota` (`Id_anggota`);

--
-- Indexes for table `notif_penerima`
--
ALTER TABLE `notif_penerima`
  ADD PRIMARY KEY (`id_notif_penerima`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`Id_peminjaman`),
  ADD KEY `Id_anggota` (`Id_anggota`),
  ADD KEY `Id_buku` (`Id_buku`);

--
-- Indexes for table `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD PRIMARY KEY (`Id_pengembalian`),
  ADD KEY `Id_peminjaman` (`Id_peminjaman`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`Id_user`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `Id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `anggota`
--
ALTER TABLE `anggota`
  MODIFY `Id_anggota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `anggota_guru`
--
ALTER TABLE `anggota_guru`
  MODIFY `Id_guru` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `anggota_kelas`
--
ALTER TABLE `anggota_kelas`
  MODIFY `Id_kelas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `anggota_siswa`
--
ALTER TABLE `anggota_siswa`
  MODIFY `Id_siswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `Id_buku` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `denda`
--
ALTER TABLE `denda`
  MODIFY `Id_denda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `kunjungan`
--
ALTER TABLE `kunjungan`
  MODIFY `Id_kunjungan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notif_penerima`
--
ALTER TABLE `notif_penerima`
  MODIFY `id_notif_penerima` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `Id_peminjaman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pengembalian`
--
ALTER TABLE `pengembalian`
  MODIFY `Id_pengembalian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `Id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`Id_user`) REFERENCES `users` (`Id_user`) ON DELETE CASCADE;

--
-- Constraints for table `anggota`
--
ALTER TABLE `anggota`
  ADD CONSTRAINT `anggota_ibfk_1` FOREIGN KEY (`Id_user`) REFERENCES `users` (`Id_user`) ON DELETE CASCADE;

--
-- Constraints for table `anggota_guru`
--
ALTER TABLE `anggota_guru`
  ADD CONSTRAINT `anggota_guru_ibfk_1` FOREIGN KEY (`Id_anggota`) REFERENCES `anggota` (`Id_anggota`);

--
-- Constraints for table `anggota_kelas`
--
ALTER TABLE `anggota_kelas`
  ADD CONSTRAINT `anggota_kelas_ibfk_1` FOREIGN KEY (`Id_anggota`) REFERENCES `anggota` (`Id_anggota`);

--
-- Constraints for table `anggota_siswa`
--
ALTER TABLE `anggota_siswa`
  ADD CONSTRAINT `anggota_siswa_ibfk_1` FOREIGN KEY (`Id_anggota`) REFERENCES `anggota` (`Id_anggota`);

--
-- Constraints for table `kunjungan`
--
ALTER TABLE `kunjungan`
  ADD CONSTRAINT `kunjungan_ibfk_1` FOREIGN KEY (`Id_anggota`) REFERENCES `anggota` (`Id_anggota`);

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`Id_anggota`) REFERENCES `anggota` (`Id_anggota`),
  ADD CONSTRAINT `peminjaman_ibfk_2` FOREIGN KEY (`Id_buku`) REFERENCES `buku` (`Id_buku`);

--
-- Constraints for table `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD CONSTRAINT `pengembalian_ibfk_1` FOREIGN KEY (`Id_peminjaman`) REFERENCES `peminjaman` (`Id_peminjaman`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

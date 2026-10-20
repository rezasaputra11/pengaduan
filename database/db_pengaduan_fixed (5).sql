-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 30, 2026 at 05:30 AM
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
-- Database: `db_pengaduan_fixed`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(5) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_admin` varchar(50) NOT NULL,
  `gmail` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`, `nama_admin`, `gmail`) VALUES
(1, 'admin01', 'admin123', 'Andi', 'andi@gmail.com'),
(2, 'admin02', 'admin456', 'Budi', 'budi@gmail.com'),
(3, 'operator01', 'operator123', 'Citra', 'citra@gmail.com'),
(4, 'operator02', 'operator456', 'Deni', 'deni@gmail.com'),
(5, 'admin03', 'admin789', 'Eka', 'eka@gmail.com'),
(6, 'operator03', 'operator789', 'Fajar', 'fajar@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `aspirasi`
--

CREATE TABLE `aspirasi` (
  `id_aspirasi` int(5) NOT NULL,
  `id_pelaporan` int(5) NOT NULL,
  `id_kategori` int(5) NOT NULL,
  `status` enum('Menunggu','Proses','Selesai') NOT NULL DEFAULT 'Menunggu',
  `feedback` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `aspirasi`
--

INSERT INTO `aspirasi` (`id_aspirasi`, `id_pelaporan`, `id_kategori`, `status`, `feedback`) VALUES
(1, 1, 1, 'Menunggu', 'Akan segera diperiksa'),
(2, 2, 2, 'Proses', 'Sedang dibersihkan'),
(3, 3, 3, 'Selesai', 'Sudah ditangani'),
(4, 4, 4, 'Proses', 'Sedang diproses'),
(5, 5, 5, 'Selesai', 'Sudah dibersihkan'),
(6, 6, 1, 'Menunggu', 'Menunggu perbaikan');

-- --------------------------------------------------------

--
-- Table structure for table `input_aspirasi`
--

CREATE TABLE `input_aspirasi` (
  `id_pelaporan` int(5) NOT NULL,
  `nis` int(10) NOT NULL,
  `id_kategori` int(5) NOT NULL,
  `lokasi` varchar(50) NOT NULL,
  `ket` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `input_aspirasi`
--

INSERT INTO `input_aspirasi` (`id_pelaporan`, `nis`, `id_kategori`, `lokasi`, `ket`) VALUES
(1, 100001, 1, 'Lab RPL', 'Komputer mengalami black screen'),
(2, 100002, 2, 'Toilet', 'Toilet kurang bersih'),
(3, 100003, 3, 'Parkiran', 'Keamanan parkiran kurang'),
(4, 100004, 4, 'Ruang TU', 'Pelayanan terlalu lama'),
(5, 100005, 5, 'Halaman', 'Banyak sampah berserakan'),
(6, 1234, 1, 'Kelas XI RPL', 'Kursi mengalami kerusakan');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(5) NOT NULL,
  `ket_kategori` varchar(30) NOT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `prioritas` varchar(20) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `ket_kategori`, `keterangan`, `prioritas`, `status`) VALUES
(1, 'Fasilitas', 'Kerusakan fasilitas sekolah', 'Tinggi', 'Aktif'),
(2, 'Kebersihan', 'Masalah kebersihan', 'Sedang', 'Aktif'),
(3, 'Keamanan', 'Masalah keamanan sekolah', 'Tinggi', 'Aktif'),
(4, 'Pelayanan', 'Masalah pelayanan sekolah', 'Sedang', 'Aktif'),
(5, 'Lingkungan', 'Masalah lingkungan sekolah', 'Sedang', 'Aktif'),
(6, 'Lainnya', 'Pengaduan lainnya', 'Rendah', 'Aktif');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `nis` int(10) NOT NULL,
  `nama_siswa` varchar(50) NOT NULL,
  `kelas` varchar(10) NOT NULL,
  `jurusan` varchar(30) NOT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`nis`, `nama_siswa`, `kelas`, `jurusan`, `no_hp`, `password`) VALUES
(1234, 'herry', 'XI RPL 3', 'RPL', '00000000', '1234'),
(22222, 'ahmad', 'XI RPL 3', 'RPL', '999999999', '22222'),
(34542, 'abdul', 'xii 2', 'akl', '00000000', '34542'),
(100001, 'Rizky', 'XI RPL 1', 'RPL', '081234567001', '100001'),
(100002, 'Dimas', 'XI RPL 1', 'RPL', '081234567002', '100002'),
(100003, 'Aldi', 'XI RPL 2', 'RPL', '081234567003', '100003'),
(100004, 'Fahmi', 'XI RPL 2', 'RPL', '081234567004', '100004'),
(100005, 'Raka', 'XI RPL 3', 'RPL', '081234567005', '100005'),
(100006, 'Bayu', 'XI RPL 3', 'RPL', '081234567006', '100006');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `aspirasi`
--
ALTER TABLE `aspirasi`
  ADD PRIMARY KEY (`id_aspirasi`),
  ADD KEY `id_pelaporan` (`id_pelaporan`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indexes for table `input_aspirasi`
--
ALTER TABLE `input_aspirasi`
  ADD PRIMARY KEY (`id_pelaporan`),
  ADD KEY `nis` (`nis`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`nis`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `aspirasi`
--
ALTER TABLE `aspirasi`
  MODIFY `id_aspirasi` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `input_aspirasi`
--
ALTER TABLE `input_aspirasi`
  MODIFY `id_pelaporan` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aspirasi`
--
ALTER TABLE `aspirasi`
  ADD CONSTRAINT `aspirasi_ibfk_1` FOREIGN KEY (`id_pelaporan`) REFERENCES `input_aspirasi` (`id_pelaporan`),
  ADD CONSTRAINT `aspirasi_ibfk_2` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`);

--
-- Constraints for table `input_aspirasi`
--
ALTER TABLE `input_aspirasi`
  ADD CONSTRAINT `input_aspirasi_ibfk_1` FOREIGN KEY (`nis`) REFERENCES `siswa` (`nis`),
  ADD CONSTRAINT `input_aspirasi_ibfk_2` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

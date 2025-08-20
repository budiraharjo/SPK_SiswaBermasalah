-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 20, 2025 at 04:13 PM
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
-- Database: `spk_smp2mlp`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_batas_masalah`
--

CREATE TABLE `tb_batas_masalah` (
  `id_batas` int(10) UNSIGNED NOT NULL,
  `metode` enum('SAW','WP') NOT NULL,
  `batas_min` decimal(10,4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_batas_masalah`
--

INSERT INTO `tb_batas_masalah` (`id_batas`, `metode`, `batas_min`, `created_at`) VALUES
(2, 'WP', 0.2000, '2025-08-13 04:32:53'),
(3, 'SAW', 0.2000, '2025-08-13 04:34:26');

-- --------------------------------------------------------

--
-- Table structure for table `tb_hasil_saw`
--

CREATE TABLE `tb_hasil_saw` (
  `id_hasil` int(10) UNSIGNED NOT NULL,
  `id_siswa` int(10) UNSIGNED NOT NULL,
  `nilai_akhir` decimal(12,6) NOT NULL,
  `periode` varchar(20) DEFAULT NULL,
  `peringkat` int(11) NOT NULL,
  `status` enum('bermasalah','tidak_bermasalah') NOT NULL,
  `id_user` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_hasil_saw`
--

INSERT INTO `tb_hasil_saw` (`id_hasil`, `id_siswa`, `nilai_akhir`, `periode`, `peringkat`, `status`, `id_user`, `created_at`) VALUES
(109, 2, 1.000000, '2024/2025', 1, 'tidak_bermasalah', 1, '2025-08-18 20:56:51'),
(110, 1, 0.700000, '2024/2025', 2, 'tidak_bermasalah', 1, '2025-08-18 20:56:51'),
(111, 6, 0.592000, '2024/2025', 3, 'tidak_bermasalah', 1, '2025-08-18 20:56:51'),
(112, 3, 0.477500, '2024/2025', 4, 'tidak_bermasalah', 1, '2025-08-18 20:56:51'),
(113, 5, 0.360000, '2024/2025', 5, 'tidak_bermasalah', 1, '2025-08-18 20:56:51');

-- --------------------------------------------------------

--
-- Table structure for table `tb_hasil_wp`
--

CREATE TABLE `tb_hasil_wp` (
  `id_hasil` int(10) UNSIGNED NOT NULL,
  `id_siswa` int(10) UNSIGNED NOT NULL,
  `nilai_akhir` decimal(20,10) NOT NULL,
  `periode` varchar(20) DEFAULT NULL,
  `peringkat` int(11) NOT NULL,
  `status` enum('bermasalah','tidak_bermasalah') NOT NULL,
  `id_user` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_hasil_wp`
--

INSERT INTO `tb_hasil_wp` (`id_hasil`, `id_siswa`, `nilai_akhir`, `periode`, `peringkat`, `status`, `id_user`, `created_at`) VALUES
(59, 2, 0.3582430000, '2024/2025', 1, 'tidak_bermasalah', 1, '2025-08-18 20:56:49'),
(60, 1, 0.2309010000, '2024/2025', 2, 'tidak_bermasalah', 1, '2025-08-18 20:56:49'),
(61, 6, 0.1818680000, '2024/2025', 3, 'bermasalah', 1, '2025-08-18 20:56:49'),
(62, 3, 0.1371280000, '2024/2025', 4, 'bermasalah', 1, '2025-08-18 20:56:49'),
(63, 5, 0.0918600000, '2024/2025', 5, 'bermasalah', 1, '2025-08-18 20:56:49');

-- --------------------------------------------------------

--
-- Table structure for table `tb_kriteria`
--

CREATE TABLE `tb_kriteria` (
  `id_kriteria` int(10) UNSIGNED NOT NULL,
  `kode` varchar(10) NOT NULL,
  `nama_kriteria` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_kriteria`
--

INSERT INTO `tb_kriteria` (`id_kriteria`, `kode`, `nama_kriteria`, `created_at`) VALUES
(1, 'C1', 'Nilai Rata-rata Raport', '2025-08-12 00:38:54'),
(2, 'C2', 'Peringkat Kelas', '2025-08-12 00:38:54'),
(3, 'C3', 'Sikap & Kedisiplinan', '2025-08-12 00:38:54'),
(4, 'C4', 'Keikutsertaan Organisasi', '2025-08-12 00:38:54');

-- --------------------------------------------------------

--
-- Table structure for table `tb_kriteria_metode`
--

CREATE TABLE `tb_kriteria_metode` (
  `id_k_metode` int(10) UNSIGNED NOT NULL,
  `id_kriteria` int(10) UNSIGNED NOT NULL,
  `metode` enum('SAW','WP') NOT NULL,
  `bobot` decimal(5,4) NOT NULL DEFAULT 0.0000,
  `sifat` enum('benefit','cost') NOT NULL,
  `urutan` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_kriteria_metode`
--

INSERT INTO `tb_kriteria_metode` (`id_k_metode`, `id_kriteria`, `metode`, `bobot`, `sifat`, `urutan`, `created_at`) VALUES
(1, 1, 'SAW', 0.4000, 'benefit', 1, '2025-08-12 00:38:54'),
(2, 2, 'SAW', 0.3000, 'cost', 2, '2025-08-12 00:38:54'),
(3, 3, 'SAW', 0.2000, 'benefit', 3, '2025-08-12 00:38:54'),
(4, 4, 'SAW', 0.1000, 'benefit', 4, '2025-08-12 00:38:54'),
(5, 1, 'WP', 0.4000, 'benefit', 1, '2025-08-12 00:38:54'),
(6, 2, 'WP', 0.3000, 'cost', 2, '2025-08-12 00:38:54'),
(7, 3, 'WP', 0.2000, 'benefit', 3, '2025-08-12 00:38:54'),
(8, 4, 'WP', 0.1000, 'benefit', 4, '2025-08-12 00:38:54');

-- --------------------------------------------------------

--
-- Table structure for table `tb_nilai`
--

CREATE TABLE `tb_nilai` (
  `id_nilai` int(10) UNSIGNED NOT NULL,
  `id_siswa` int(10) UNSIGNED NOT NULL,
  `id_k_metode` int(10) UNSIGNED NOT NULL,
  `periode` varchar(20) NOT NULL,
  `nilai` decimal(10,2) NOT NULL,
  `sumber` enum('input_manual','import') DEFAULT 'input_manual',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_nilai`
--

INSERT INTO `tb_nilai` (`id_nilai`, `id_siswa`, `id_k_metode`, `periode`, `nilai`, `sumber`, `created_at`) VALUES
(25, 1, 1, '2024/2025', 85.00, 'import', '2025-08-14 01:58:09'),
(26, 1, 2, '2024/2025', 3.00, 'import', '2025-08-14 01:58:09'),
(27, 1, 3, '2024/2025', 4.00, 'import', '2025-08-14 01:58:09'),
(28, 1, 4, '2024/2025', 5.00, 'import', '2025-08-14 01:58:09'),
(29, 2, 1, '2024/2025', 100.00, 'import', '2025-08-14 01:58:09'),
(30, 2, 2, '2024/2025', 1.00, 'import', '2025-08-14 01:58:09'),
(31, 2, 3, '2024/2025', 5.00, 'import', '2025-08-14 01:58:09'),
(32, 2, 4, '2024/2025', 5.00, 'import', '2025-08-14 01:58:09'),
(33, 3, 1, '2024/2025', 70.00, 'import', '2025-08-14 01:58:09'),
(34, 3, 2, '2024/2025', 8.00, 'import', '2025-08-14 01:58:09'),
(35, 3, 3, '2024/2025', 3.00, 'import', '2025-08-14 01:58:09'),
(36, 3, 4, '2024/2025', 2.00, 'import', '2025-08-14 01:58:09'),
(37, 5, 1, '2024/2025', 60.00, 'import', '2025-08-14 01:58:09'),
(38, 5, 2, '2024/2025', 15.00, 'import', '2025-08-14 01:58:09'),
(39, 5, 3, '2024/2025', 2.00, 'import', '2025-08-14 01:58:09'),
(40, 5, 4, '2024/2025', 1.00, 'import', '2025-08-14 01:58:09'),
(41, 6, 1, '2024/2025', 78.00, 'import', '2025-08-14 01:58:09'),
(42, 6, 2, '2024/2025', 5.00, 'import', '2025-08-14 01:58:09'),
(43, 6, 3, '2024/2025', 4.00, 'import', '2025-08-14 01:58:09'),
(44, 6, 4, '2024/2025', 3.00, 'import', '2025-08-14 01:58:09'),
(45, 1, 5, '2024/2025', 85.00, 'import', '2025-08-14 02:53:17'),
(46, 1, 6, '2024/2025', 3.00, 'import', '2025-08-14 02:53:17'),
(47, 1, 7, '2024/2025', 4.00, 'import', '2025-08-14 02:53:17'),
(48, 1, 8, '2024/2025', 5.00, 'import', '2025-08-14 02:53:17'),
(49, 2, 5, '2024/2025', 100.00, 'import', '2025-08-14 02:53:17'),
(50, 2, 6, '2024/2025', 1.00, 'import', '2025-08-14 02:53:17'),
(51, 2, 7, '2024/2025', 5.00, 'import', '2025-08-14 02:53:17'),
(52, 2, 8, '2024/2025', 5.00, 'import', '2025-08-14 02:53:17'),
(53, 3, 5, '2024/2025', 70.00, 'import', '2025-08-14 02:53:17'),
(54, 3, 6, '2024/2025', 8.00, 'import', '2025-08-14 02:53:17'),
(55, 3, 7, '2024/2025', 3.00, 'import', '2025-08-14 02:53:17'),
(56, 3, 8, '2024/2025', 2.00, 'import', '2025-08-14 02:53:17'),
(57, 5, 5, '2024/2025', 60.00, 'import', '2025-08-14 02:53:17'),
(58, 5, 6, '2024/2025', 15.00, 'import', '2025-08-14 02:53:17'),
(59, 5, 7, '2024/2025', 2.00, 'import', '2025-08-14 02:53:17'),
(60, 5, 8, '2024/2025', 1.00, 'import', '2025-08-14 02:53:17'),
(61, 6, 5, '2024/2025', 78.00, 'import', '2025-08-14 02:53:17'),
(62, 6, 6, '2024/2025', 5.00, 'import', '2025-08-14 02:53:17'),
(63, 6, 7, '2024/2025', 4.00, 'import', '2025-08-14 02:53:17'),
(64, 6, 8, '2024/2025', 3.00, 'import', '2025-08-14 02:53:17');

-- --------------------------------------------------------

--
-- Table structure for table `tb_perbandingan`
--

CREATE TABLE `tb_perbandingan` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_siswa` int(10) UNSIGNED NOT NULL,
  `nilai_saw` decimal(12,6) NOT NULL,
  `peringkat_saw` int(11) NOT NULL,
  `nilai_wp` decimal(20,10) NOT NULL,
  `peringkat_wp` int(11) NOT NULL,
  `selisih` decimal(20,10) GENERATED ALWAYS AS (`nilai_saw` - `nilai_wp`) VIRTUAL,
  `periode` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_perbandingan`
--

INSERT INTO `tb_perbandingan` (`id`, `id_siswa`, `nilai_saw`, `peringkat_saw`, `nilai_wp`, `peringkat_wp`, `periode`, `created_at`) VALUES
(96, 2, 1.000000, 1, 0.3582430000, 1, '2024/2025', '2025-08-20 11:12:50'),
(97, 1, 0.700000, 2, 0.2309010000, 2, '2024/2025', '2025-08-20 11:12:50'),
(98, 6, 0.592000, 3, 0.1818680000, 3, '2024/2025', '2025-08-20 11:12:50'),
(99, 3, 0.477500, 4, 0.1371280000, 4, '2024/2025', '2025-08-20 11:12:50'),
(100, 5, 0.360000, 5, 0.0918600000, 5, '2024/2025', '2025-08-20 11:12:50');

-- --------------------------------------------------------

--
-- Table structure for table `tb_periode`
--

CREATE TABLE `tb_periode` (
  `id_periode` int(10) UNSIGNED NOT NULL,
  `periode` varchar(20) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_periode`
--

INSERT INTO `tb_periode` (`id_periode`, `periode`, `is_active`, `created_at`) VALUES
(1, '2024/2025', 1, '2025-08-12 04:00:36'),
(3, '2023/2024', 0, '2025-08-12 04:01:37');

-- --------------------------------------------------------

--
-- Table structure for table `tb_siswa`
--

CREATE TABLE `tb_siswa` (
  `id_siswa` int(10) UNSIGNED NOT NULL,
  `nisn` varchar(20) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `kelas` varchar(20) DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') DEFAULT NULL,
  `tahun_ajaran` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_siswa`
--

INSERT INTO `tb_siswa` (`id_siswa`, `nisn`, `nama`, `kelas`, `jenis_kelamin`, `tahun_ajaran`, `created_at`, `updated_at`) VALUES
(1, '001', 'Andi Pratama', '9A', 'Laki-laki', '2024/2025', '2025-08-12 00:38:54', NULL),
(2, '002', 'Beni Santoso', '9A', 'Laki-laki', '2024/2025', '2025-08-12 00:38:54', NULL),
(3, '003', 'Citra Lestari', '9B', 'Perempuan', '2024/2025', '2025-08-12 00:38:54', NULL),
(5, '004', 'Dedi Firmansyah', '9E', 'Laki-laki', '2024/2025', '2025-08-14 01:47:04', '2025-08-14 01:47:04'),
(6, '005', 'Eka Purnama', '9E', 'Perempuan', '2024/2025', '2025-08-14 01:47:27', '2025-08-14 01:47:27');

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `id_user` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `role` enum('Admin','Guru_BK','Wali_Kelas') NOT NULL DEFAULT 'Guru_BK',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`id_user`, `username`, `password_hash`, `nama`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Admin', 'Admin', '2025-08-10 06:39:43', NULL),
(2, 'guru', '77e69c137812518e359196bb2f5e9bb9', 'Guru', 'Wali_Kelas', '2025-08-10 18:15:12', NULL),
(3, 'gurubk', '2ac04ec7fa4d34385573011704636f6c', 'Guru BK', 'Guru_BK', '2025-08-10 18:23:16', '2025-08-10 18:27:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_batas_masalah`
--
ALTER TABLE `tb_batas_masalah`
  ADD PRIMARY KEY (`id_batas`);

--
-- Indexes for table `tb_hasil_saw`
--
ALTER TABLE `tb_hasil_saw`
  ADD PRIMARY KEY (`id_hasil`),
  ADD KEY `id_siswa` (`id_siswa`);

--
-- Indexes for table `tb_hasil_wp`
--
ALTER TABLE `tb_hasil_wp`
  ADD PRIMARY KEY (`id_hasil`),
  ADD KEY `id_siswa` (`id_siswa`);

--
-- Indexes for table `tb_kriteria`
--
ALTER TABLE `tb_kriteria`
  ADD PRIMARY KEY (`id_kriteria`);

--
-- Indexes for table `tb_kriteria_metode`
--
ALTER TABLE `tb_kriteria_metode`
  ADD PRIMARY KEY (`id_k_metode`),
  ADD KEY `id_kriteria` (`id_kriteria`);

--
-- Indexes for table `tb_nilai`
--
ALTER TABLE `tb_nilai`
  ADD PRIMARY KEY (`id_nilai`),
  ADD KEY `id_siswa` (`id_siswa`),
  ADD KEY `idx_id_k_metode` (`id_k_metode`);

--
-- Indexes for table `tb_perbandingan`
--
ALTER TABLE `tb_perbandingan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_siswa` (`id_siswa`);

--
-- Indexes for table `tb_periode`
--
ALTER TABLE `tb_periode`
  ADD PRIMARY KEY (`id_periode`);

--
-- Indexes for table `tb_siswa`
--
ALTER TABLE `tb_siswa`
  ADD PRIMARY KEY (`id_siswa`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_batas_masalah`
--
ALTER TABLE `tb_batas_masalah`
  MODIFY `id_batas` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_hasil_saw`
--
ALTER TABLE `tb_hasil_saw`
  MODIFY `id_hasil` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT for table `tb_hasil_wp`
--
ALTER TABLE `tb_hasil_wp`
  MODIFY `id_hasil` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `tb_kriteria`
--
ALTER TABLE `tb_kriteria`
  MODIFY `id_kriteria` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tb_kriteria_metode`
--
ALTER TABLE `tb_kriteria_metode`
  MODIFY `id_k_metode` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `tb_nilai`
--
ALTER TABLE `tb_nilai`
  MODIFY `id_nilai` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `tb_perbandingan`
--
ALTER TABLE `tb_perbandingan`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `tb_periode`
--
ALTER TABLE `tb_periode`
  MODIFY `id_periode` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_siswa`
--
ALTER TABLE `tb_siswa`
  MODIFY `id_siswa` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id_user` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_hasil_saw`
--
ALTER TABLE `tb_hasil_saw`
  ADD CONSTRAINT `tb_hasil_saw_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `tb_siswa` (`id_siswa`) ON DELETE CASCADE;

--
-- Constraints for table `tb_hasil_wp`
--
ALTER TABLE `tb_hasil_wp`
  ADD CONSTRAINT `tb_hasil_wp_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `tb_siswa` (`id_siswa`) ON DELETE CASCADE;

--
-- Constraints for table `tb_kriteria_metode`
--
ALTER TABLE `tb_kriteria_metode`
  ADD CONSTRAINT `tb_kriteria_metode_ibfk_1` FOREIGN KEY (`id_kriteria`) REFERENCES `tb_kriteria` (`id_kriteria`) ON DELETE CASCADE;

--
-- Constraints for table `tb_nilai`
--
ALTER TABLE `tb_nilai`
  ADD CONSTRAINT `fk_tb_nilai_k_metode` FOREIGN KEY (`id_k_metode`) REFERENCES `tb_kriteria_metode` (`id_k_metode`) ON DELETE CASCADE,
  ADD CONSTRAINT `tb_nilai_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `tb_siswa` (`id_siswa`) ON DELETE CASCADE;

--
-- Constraints for table `tb_perbandingan`
--
ALTER TABLE `tb_perbandingan`
  ADD CONSTRAINT `tb_perbandingan_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `tb_siswa` (`id_siswa`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

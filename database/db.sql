-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 24, 2024 at 03:20 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laundry`
--

-- --------------------------------------------------------

--
-- Table structure for table `auth`
--

CREATE TABLE `auth` (
  `token` varchar(255) NOT NULL,
  `id_user` int(11) NOT NULL,
  `expires` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_detail_transaksi`
--

CREATE TABLE `tb_detail_transaksi` (
  `id` int(11) NOT NULL,
  `id_transaksi` int(11) NOT NULL,
  `id_paket` int(11) NOT NULL,
  `qty` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_detail_transaksi`
--

INSERT INTO `tb_detail_transaksi` (`id`, `id_transaksi`, `id_paket`, `qty`) VALUES
(1, 1, 2, 2),
(12, 8, 5, 3),
(13, 9, 5, 4),
(14, 10, 2, 4),
(15, 11, 13, 3),
(16, 11, 16, 1),
(17, 12, 2, 10),
(18, 13, 6, 15),
(19, 14, 20, 2),
(20, 14, 17, 5),
(21, 14, 18, 1),
(22, 15, 5, 3),
(23, 15, 6, 1),
(24, 15, 1, 6),
(25, 15, 2, 2),
(27, 17, 6, 10),
(28, 18, 1, 6),
(29, 18, 4, 5),
(30, 18, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tb_member`
--

CREATE TABLE `tb_member` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `tlp` varchar(15) NOT NULL,
  `token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_member`
--

INSERT INTO `tb_member` (`id`, `nama`, `alamat`, `jenis_kelamin`, `tlp`, `token`) VALUES
(6, 'putri', 'Jln. Mawar', 'P', '62913356712', 'OtOHA'),
(7, 'gilang ', 'Jln Galang', 'L', '62913356712', 'brt2GvteHq'),
(8, 'roger', 'Jln. sambo', 'L', '628123213212', '1ofeImQEIB'),
(9, 'dayu', 'Jln lampung', 'P', '628123213212', 'hq0K5FMKB7'),
(10, 'reinhad', 'Jln Mawar', 'L', '6266666666', 'xrQBsx9qqC'),
(11, 'james', 'jl James', 'L', '628123213212', 'Om4ujx0Oan');

-- --------------------------------------------------------

--
-- Table structure for table `tb_outlet`
--

CREATE TABLE `tb_outlet` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `tlp` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_outlet`
--

INSERT INTO `tb_outlet` (`id`, `nama`, `alamat`, `tlp`) VALUES
(1, 'Outlet Andy', 'Jln. Mekar', '628133767872'),
(3, 'Outlet Umar', 'Jln Umarisya', '628123213212'),
(4, 'Outlet Intan', 'Jln. Honmas', '62832456712'),
(5, 'Outlet Bangli', 'Jln. Tembuku', '628918232123');

-- --------------------------------------------------------

--
-- Table structure for table `tb_paket`
--

CREATE TABLE `tb_paket` (
  `id` int(11) NOT NULL,
  `id_outlet` int(11) NOT NULL,
  `jenis` enum('kiloan','selimut','bed_cover','kaos','lain') NOT NULL,
  `nama_paket` varchar(100) NOT NULL,
  `harga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_paket`
--

INSERT INTO `tb_paket` (`id`, `id_outlet`, `jenis`, `nama_paket`, `harga`) VALUES
(1, 1, 'kaos', 'Kaos', 6000),
(2, 1, 'selimut', 'Selimut', 10000),
(4, 1, 'lain', 'Lain', 15000),
(5, 1, 'kiloan', 'Kiloan', 10000),
(6, 1, 'bed_cover', 'Seprai', 12000),
(12, 3, 'kaos', 'Kaos', 5000),
(13, 3, 'selimut', 'Selimut', 10000),
(14, 3, 'lain', 'Lain', 15000),
(15, 3, 'kiloan', 'Kiloan', 10000),
(16, 3, 'bed_cover', 'Seprai', 12000),
(17, 4, 'kaos', 'Kaos', 5000),
(18, 4, 'selimut', 'Selimut', 10000),
(19, 4, 'lain', 'Lain', 15000),
(20, 4, 'kiloan', 'Kiloan', 10000),
(21, 4, 'bed_cover', 'Seprai', 12000);

-- --------------------------------------------------------

--
-- Table structure for table `tb_transaksi`
--

CREATE TABLE `tb_transaksi` (
  `id` int(11) NOT NULL,
  `id_outlet` int(11) NOT NULL,
  `kode_invoice` varchar(100) NOT NULL,
  `id_member` int(11) NOT NULL,
  `tgl` datetime NOT NULL,
  `batas_waktu` datetime NOT NULL,
  `tgl_bayar` datetime DEFAULT NULL,
  `biaya_tambahan` int(11) NOT NULL,
  `diskon` float NOT NULL,
  `pajak` float NOT NULL,
  `status` enum('baru','proses','selesai','diambil') NOT NULL DEFAULT 'baru',
  `dibayar` enum('dibayar','belum_dibayar') NOT NULL DEFAULT 'belum_dibayar',
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_transaksi`
--

INSERT INTO `tb_transaksi` (`id`, `id_outlet`, `kode_invoice`, `id_member`, `tgl`, `batas_waktu`, `tgl_bayar`, `biaya_tambahan`, `diskon`, `pajak`, `status`, `dibayar`, `id_user`) VALUES
(1, 1, 'INV001-24000KIO1', 7, '2024-01-22 00:00:00', '2024-01-26 00:00:00', '2024-01-30 08:54:50', 5000, 0, 0.075, 'diambil', 'dibayar', 1),
(8, 1, 'INV001-247RPMQGJ', 6, '2024-01-25 00:00:00', '2024-01-24 00:00:00', NULL, 5000, 0, 0.075, 'baru', 'belum_dibayar', 1),
(9, 1, 'INV001-245DZ3CPB', 11, '2024-01-25 22:04:01', '2024-01-28 22:04:01', '2024-01-25 22:04:59', 5000, 0, 0.075, 'selesai', 'dibayar', 1),
(10, 1, 'INV001-242MDE5HE', 7, '2024-01-25 00:00:00', '2024-01-24 00:00:00', NULL, 2500, 0, 0.075, 'baru', 'belum_dibayar', 1),
(11, 3, 'INV003-24LSYPJMG', 11, '2024-01-25 22:04:51', '2024-01-28 22:04:51', '2024-01-25 22:05:09', 15000, 0, 0.075, 'proses', 'dibayar', 1),
(12, 1, 'INV001-24ICNSRDP', 11, '2024-01-25 22:06:10', '2024-01-28 22:06:10', '2024-01-25 22:06:44', 0, 0, 0.075, 'proses', 'dibayar', 1),
(13, 1, 'INV001-246B8J0RE', 11, '2024-01-25 22:07:08', '2024-01-28 22:07:08', NULL, 20000, 0.1, 0.075, 'baru', 'belum_dibayar', 1),
(14, 4, 'INV004-24LPHFYMK', 9, '2024-01-30 08:59:06', '2024-02-02 08:59:06', NULL, 0, 0, 0.075, 'baru', 'belum_dibayar', 7),
(15, 1, 'INV001-244E0IKE4', 11, '2024-01-30 22:03:33', '2024-02-02 22:03:33', '2024-01-30 22:03:39', 15000, 0.1, 0.075, 'diambil', 'dibayar', 1),
(17, 1, 'INV001-24UK1FFDS', 11, '2024-01-30 22:21:37', '2024-02-02 22:21:37', '2024-01-30 22:21:45', 10000, 0.1, 0.075, 'selesai', 'dibayar', 1),
(18, 1, 'INV001-24A0H48I5', 8, '2024-01-31 21:28:18', '2024-02-03 21:28:18', '2024-02-06 20:39:11', 10000, 0, 0.075, 'proses', 'dibayar', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` text NOT NULL,
  `id_outlet` int(11) NOT NULL,
  `role` enum('admin','kasir','owner') NOT NULL DEFAULT 'kasir'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`id`, `email`, `nama`, `username`, `password`, `id_outlet`, `role`) VALUES
(1, 'wkomangadi44@gmail.com', 'Isla', 'totallynotisla', '$2y$10$LYKMUoEF5vO1qB9yqT6uYueonp1xCnFe9ltDN4EzWQ.Y9cZyg3O0K', 1, 'admin'),
(2, 'pekalongan@gmail.co.id', 'Rihan Sirmawan', 'rihan123', '$2y$10$hHrGeS0R/Zcm/rE7YtorJO7VVWYhH/GSeEBLnrVv8in5W8d1l850i', 1, 'kasir'),
(3, 'mangadirpl@gmail.com', 'Komang Adi Wirawan', 'adidi', '$2y$10$ic6IVTHLGqs5w/lDD0oOpuJnE6G5Gng6G/7FhEcvIzg97JfEJDUzO', 1, 'owner'),
(4, 'shigemoto@laundryina.com', 'Shigemoto Itsuki', 'shigemoto', '$2y$10$XsbaRbQtL2FHV5LEXaUDy.c4L1xDvvEJAvay8P0ok3arzwYsqmnta', 3, 'admin'),
(5, 'andre@laundryina.com', 'James Andre', 'andre', '$2y$10$oEg32ixtHXaDXN6NeZq/ZOHP8FierlEsBLqhnXSixXLb7H.NO/Ja2', 3, 'kasir'),
(6, 'charles@laundryina.com', 'Charles Kim', 'charles01', '$2y$10$mcxJTLsNY0bXKGCIos1Q0eRdVKQ1.jmFVW9VxUmJwjEpwRdWufLOS', 4, 'admin'),
(7, 'ayunda@laundryina.com', 'Ayunda Mika', 'ayunda', '$2y$10$rru1Nf5pxsFTRMJTrNHO3eDcHtl9FgfomqtgjuwPzxEWtt8hZPqia', 4, 'kasir');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auth`
--
ALTER TABLE `auth`
  ADD PRIMARY KEY (`token`),
  ADD KEY `fk_auth_user` (`id_user`);

--
-- Indexes for table `tb_detail_transaksi`
--
ALTER TABLE `tb_detail_transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_paket` (`id_paket`),
  ADD KEY `FK_transaksi` (`id_transaksi`);

--
-- Indexes for table `tb_member`
--
ALTER TABLE `tb_member`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_outlet`
--
ALTER TABLE `tb_outlet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_paket`
--
ALTER TABLE `tb_paket`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_paket_outlet` (`id_outlet`);

--
-- Indexes for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_member` (`id_member`),
  ADD KEY `FK_outlet` (`id_outlet`),
  ADD KEY `FK_user` (`id_user`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_user_outlet` (`id_outlet`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_detail_transaksi`
--
ALTER TABLE `tb_detail_transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `tb_member`
--
ALTER TABLE `tb_member`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tb_outlet`
--
ALTER TABLE `tb_outlet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tb_paket`
--
ALTER TABLE `tb_paket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth`
--
ALTER TABLE `auth`
  ADD CONSTRAINT `fk_auth_user` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id`);

--
-- Constraints for table `tb_detail_transaksi`
--
ALTER TABLE `tb_detail_transaksi`
  ADD CONSTRAINT `FK_paket` FOREIGN KEY (`id_paket`) REFERENCES `tb_paket` (`id`),
  ADD CONSTRAINT `FK_transaksi` FOREIGN KEY (`id_transaksi`) REFERENCES `tb_transaksi` (`id`);

--
-- Constraints for table `tb_paket`
--
ALTER TABLE `tb_paket`
  ADD CONSTRAINT `FK_paket_outlet` FOREIGN KEY (`id_outlet`) REFERENCES `tb_outlet` (`id`);

--
-- Constraints for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD CONSTRAINT `FK_member` FOREIGN KEY (`id_member`) REFERENCES `tb_member` (`id`),
  ADD CONSTRAINT `FK_outlet` FOREIGN KEY (`id_outlet`) REFERENCES `tb_outlet` (`id`),
  ADD CONSTRAINT `FK_user` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id`);

--
-- Constraints for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD CONSTRAINT `FK_user_outlet` FOREIGN KEY (`id_outlet`) REFERENCES `tb_outlet` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

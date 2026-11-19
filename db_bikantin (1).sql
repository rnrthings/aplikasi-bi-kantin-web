-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2026 at 09:35 AM
-- Server version: 10.4.32-MariaDB-log
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_bikantin`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `id_detail` int(11) NOT NULL,
  `id_transaksi` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL CHECK (`jumlah` > 0),
  `harga_saat_transaksi` decimal(12,2) NOT NULL CHECK (`harga_saat_transaksi` >= 0),
  `subtotal` decimal(12,2) NOT NULL CHECK (`subtotal` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`id_detail`, `id_transaksi`, `id_menu`, `jumlah`, `harga_saat_transaksi`, `subtotal`) VALUES
(1, 1, 101, 1, 15000.00, 15000.00),
(2, 1, 103, 2, 5000.00, 10000.00),
(3, 2, 103, 1, 5000.00, 5000.00),
(4, 3, 201, 1, 12000.00, 12000.00),
(5, 3, 203, 1, 5000.00, 5000.00),
(6, 4, 301, 1, 14000.00, 14000.00),
(7, 5, 101, 2, 15000.00, 30000.00),
(8, 6, 202, 1, 13000.00, 13000.00),
(9, 6, 204, 1, 4000.00, 4000.00),
(10, 7, 301, 1, 14000.00, 14000.00),
(11, 7, 303, 1, 6000.00, 6000.00),
(12, 8, 101, 1, 15000.00, 15000.00),
(13, 9, 102, 1, 12000.00, 12000.00),
(14, 9, 104, 1, 6000.00, 6000.00),
(15, 10, 201, 1, 12000.00, 12000.00),
(16, 11, 301, 2, 14000.00, 28000.00),
(17, 12, 101, 1, 15000.00, 15000.00),
(18, 13, 201, 1, 12000.00, 12000.00),
(19, 14, 101, 1, 15000.00, 15000.00),
(20, 15, 301, 1, 14000.00, 14000.00),
(21, 15, 304, 1, 7000.00, 7000.00),
(22, 16, 202, 1, 13000.00, 13000.00),
(23, 17, 101, 3, 15000.00, 45000.00),
(24, 18, 301, 1, 14000.00, 14000.00),
(25, 18, 305, 2, 5000.00, 10000.00),
(26, 19, 102, 3, 12000.00, 36000.00),
(27, 19, 105, 1, 2000.00, 2000.00),
(28, 20, 102, 1, 12000.00, 12000.00),
(29, 20, 307, 1, 4000.00, 4000.00);

-- --------------------------------------------------------

--
-- Table structure for table `kantin`
--

CREATE TABLE `kantin` (
  `id_kantin` int(11) NOT NULL,
  `nama_kantin` varchar(100) NOT NULL,
  `penanggung_jawab` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kantin`
--

INSERT INTO `kantin` (`id_kantin`, `nama_kantin`, `penanggung_jawab`) VALUES
(1, 'Kantin Bu Sari', 'Sari Wulandari'),
(2, 'Kantin Pak Dedi', 'Dedi Kurniawan'),
(3, 'Kantin RPL', 'Ahmad Fauzi');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id_menu` int(11) NOT NULL,
  `id_kantin` int(11) NOT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `deskripsi` varchar(255) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `kategori` varchar(30) NOT NULL,
  `harga` decimal(12,2) NOT NULL CHECK (`harga` >= 0),
  `stok` int(11) NOT NULL DEFAULT 0 CHECK (`stok` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id_menu`, `id_kantin`, `nama_menu`, `deskripsi`, `gambar`, `kategori`, `harga`, `stok`) VALUES
(101, 1, 'Nasi Goreng', NULL, 'menu/6Eq9LB87n1Q4a8KYD6rRftVRTD2m2Y1XurgM4ajB.jpg', 'Makanan', 15000.00, 20),
(102, 1, 'Mie Ayam', NULL, 'menu/3DVb1d5ttY7Opr8rcFdqBzMebgnjJotxCAfa40id.jpg', 'Makanan', 12000.00, 11),
(103, 1, 'Es Teh', NULL, 'menu/XwydRtWnJuh4JQKctApMk9ubdDFnIH926c2NYdkz.jpg', 'Minuman', 5000.00, 50),
(104, 1, 'Es Jeruk', NULL, 'menu/QvZZfzO8AwOAr2zauMiY0lJE4ALsvKQcAVwrMZnu.jpg', 'Minuman', 6000.00, 40),
(105, 1, 'Gorengan', NULL, 'menu/mYK5ihnsJjzy0jikIhVHcGQv9hAt5Zb8BTP38ait.jpg', 'Makanan', 2000.00, 7),
(201, 2, 'Mie Ayam', NULL, 'menu/89Qy2DeB46Tmhy145KzrcVv2ZAnnaxrXu1R1rfYg.jpg', 'Makanan', 12000.00, 20),
(202, 2, 'Bakso', NULL, 'menu/S29CqqgfOMg40wtJBoY0IH8Wt6cx6oMKsnFeP9Yb.jpg', 'Makanan', 13000.00, 25),
(203, 2, 'Es Teh', NULL, 'menu/2WczsmsCeh6QTE44F0wjWQH1Ght3u6B8vJ3O2fIc.jpg', 'Minuman', 5000.00, 30),
(204, 2, 'Teh Panas', NULL, 'menu/9OLxPI1HdqspsHx6UObCaHs0gBrzQECkY3p70nOM.jpg', 'Minuman', 4000.00, 30),
(205, 2, 'Kerupuk', NULL, 'menu/GqhULOP72rVmvyC6L53qbgS1ThCzbyOoJbd95oEv.jpg', 'Makanan', 1000.00, 100),
(301, 3, 'Nasi Goreng', NULL, 'menu/GkxfRb1WOGH55t4vJXlyF9qcqmO1SxxpQfcIRVbX.jpg', 'Makanan', 14000.00, 15),
(302, 3, 'Roti Bakar', NULL, 'menu/UaqpO6QbobzlGGga2co5ngSNKuynVW9TPnVZCBag.jpg', 'Makanan', 10000.00, 20),
(303, 3, 'Kopi', NULL, 'menu/WG80j2eSwd9dahbuB6RXVLugOLCoY9tVMT3yBNrW.jpg', 'Minuman', 6000.00, 25),
(304, 3, 'Susu Coklat', NULL, 'menu/2Kj6oWM7tpwjqwOTRs5PDMVqr1WaJGQql2gW4ceF.jpg', 'Minuman', 7000.00, 6),
(305, 3, 'Pisang Goreng', NULL, 'menu/1t7JyZOMy4B3h8K1uuICtknj6cOaHj8yYdzLhYME.jpg', 'Makanan', 5000.00, 30),
(306, 1, 'Nasi Goreng Spesial', NULL, 'menu/Tk3PrrGG5351mRkLmyW2BX27uzhjBT6lG49ScmXt.jpg', 'Makanan', 15000.00, 20),
(307, 1, 'Es Teh Manis', NULL, 'menu/R2byrWVTdqOq9PnZ8GCm47kG9Iu0cW5NffYPjuxg.jpg', 'Minuman', 4000.00, 19);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2026_10_22_044206_create_bikantin_tables', 1),
(2, '2026_10_22_044207_harden_users_and_pesanan_tables', 2),
(3, '2026_10_22_044730_switch_to_transaksi_schema', 3),
(4, '2026_10_26_044409_add_deskripsi_to_menu_table', 4),
(5, '2026_10_26_045329_add_gambar_to_menu_table', 5),
(6, '2026_11_05_051249_add_akun_fields_and_kasir_role_to_users_table', 6),
(7, '2026_11_05_051250_add_status_bayar_to_transaksi_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `nis` varchar(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `kelas` varchar(20) NOT NULL,
  `jurusan` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`nis`, `nama`, `kelas`, `jurusan`) VALUES
('2024101', 'Budi Santoso', 'XI RPL 1', 'RPL'),
('2024102', 'Siti Aminah', 'XI DKV 1', 'DKV'),
('2024103', 'Andi Prasetyo', 'XI RPL 2', 'RPL'),
('2024104', 'Rina Wati', 'XI RPL 1', 'RPL'),
('2024105', 'Dewi Lestari', 'XI DKV 1', 'DKV'),
('2024106', 'Fajar Nugraha', 'XI RPL 2', 'RPL'),
('2024107', 'Putri Ayu', 'XI RPL 1', 'RPL'),
('2024108', 'Rizky Ramadhan', 'XI TKJ 1', 'TKJ'),
('2024109', 'Nur Halimah', 'XI RPL 2', 'RPL'),
('2024110', 'Agus Salim', 'XI DKV 2', 'DKV');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `nis` varchar(10) NOT NULL,
  `id_kantin` int(11) NOT NULL,
  `tanggal` datetime NOT NULL DEFAULT current_timestamp(),
  `metode_bayar` varchar(20) NOT NULL CHECK (`metode_bayar` in ('Tunai','QRIS')),
  `status_bayar` varchar(20) NOT NULL DEFAULT 'Belum Lunas',
  `status` varchar(30) NOT NULL DEFAULT 'Menunggu Konfirmasi'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `nis`, `id_kantin`, `tanggal`, `metode_bayar`, `status_bayar`, `status`) VALUES
(1, '2024101', 1, '2026-09-01 07:15:00', 'QRIS', 'Lunas', 'Selesai'),
(2, '2024102', 1, '2026-09-01 07:20:00', 'Tunai', 'Lunas', 'Selesai'),
(3, '2024101', 2, '2026-09-01 09:30:00', 'QRIS', 'Lunas', 'Selesai'),
(4, '2024103', 3, '2026-09-02 07:10:00', 'Tunai', 'Lunas', 'Selesai'),
(5, '2024104', 1, '2026-09-02 08:00:00', 'QRIS', 'Lunas', 'Selesai'),
(6, '2024105', 2, '2026-09-02 09:45:00', 'Tunai', 'Lunas', 'Selesai'),
(7, '2024106', 3, '2026-09-02 12:00:00', 'QRIS', 'Lunas', 'Selesai'),
(8, '2024101', 1, '2026-09-03 07:05:00', 'Tunai', 'Lunas', 'Selesai'),
(9, '2024107', 1, '2026-09-03 07:40:00', 'QRIS', 'Lunas', 'Selesai'),
(10, '2024108', 2, '2026-09-03 09:15:00', 'Tunai', 'Lunas', 'Selesai'),
(11, '2024109', 3, '2026-09-03 12:10:00', 'QRIS', 'Lunas', 'Selesai'),
(12, '2024103', 1, '2026-09-04 07:25:00', 'Tunai', 'Lunas', 'Selesai'),
(13, '2024104', 2, '2026-09-04 08:10:00', 'QRIS', 'Lunas', 'Selesai'),
(14, '2024106', 1, '2026-09-04 09:00:00', 'Tunai', 'Lunas', 'Selesai'),
(15, '2024101', 3, '2026-09-04 12:20:00', 'QRIS', 'Lunas', 'Selesai'),
(16, '2024107', 2, '2026-09-05 07:30:00', 'Tunai', 'Lunas', 'Selesai'),
(17, '2024109', 1, '2026-09-05 08:40:00', 'QRIS', 'Lunas', 'Selesai'),
(18, '2024103', 3, '2026-09-05 12:05:00', 'Tunai', 'Lunas', 'Selesai'),
(19, '2024101', 1, '2026-10-26 04:59:54', 'QRIS', 'Lunas', 'Selesai'),
(20, '2024101', 1, '2026-11-05 16:57:01', 'QRIS', 'Lunas', 'Siap Diambil');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','siswa','kasir') NOT NULL,
  `nis` varchar(10) DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `nama`, `email`, `password`, `role`, `nis`, `aktif`, `deleted_at`) VALUES
(1, 'admin_kantin', NULL, NULL, '$2y$12$s6r8pOOBIK.7nr7MjGdaE.99w/33zhlFmBX0uU12toGAnRJIuYhN2', 'admin', NULL, 1, NULL),
(2, 'budi_siswa', NULL, NULL, '$2y$12$H4I06Kh59PE7dMX2ByrRd.0B3VgtEfcQ35bmgMYxFjNR/Q7tIMHaK', 'siswa', '2024101', 1, NULL),
(3, 'kasir_kantin', 'Kasir Kantin', NULL, '$2y$12$v7GDp/heXcJQavmQ6NoPGOw/pom0vM3l8Mo.q3TSMYcACuQrGkaLy', 'kasir', NULL, 1, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_transaksi` (`id_transaksi`),
  ADD KEY `id_menu` (`id_menu`);

--
-- Indexes for table `kantin`
--
ALTER TABLE `kantin`
  ADD PRIMARY KEY (`id_kantin`),
  ADD UNIQUE KEY `nama_kantin` (`nama_kantin`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id_menu`),
  ADD KEY `id_kantin` (`id_kantin`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`nis`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `nis` (`nis`),
  ADD KEY `id_kantin` (`id_kantin`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_nis_unique` (`nis`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `kantin`
--
ALTER TABLE `kantin`
  MODIFY `id_kantin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=308;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD CONSTRAINT `detail_transaksi_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`),
  ADD CONSTRAINT `detail_transaksi_ibfk_2` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id_menu`);

--
-- Constraints for table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`id_kantin`) REFERENCES `kantin` (`id_kantin`);

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`nis`) REFERENCES `siswa` (`nis`),
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`id_kantin`) REFERENCES `kantin` (`id_kantin`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_nis_foreign` FOREIGN KEY (`nis`) REFERENCES `siswa` (`nis`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

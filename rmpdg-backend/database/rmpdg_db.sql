-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 22, 2026 at 02:31 PM
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
-- Database: `rmpdg_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('superadmin','staff') NOT NULL DEFAULT 'staff',
  `is_aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `nama`, `email`, `password_hash`, `role`, `is_aktif`) VALUES
(1, 'Administrator', 'admin@rmpadang.com', '$2y$10$ts.ZrLpNj35wtYHiR.CX0uzKNTIkmksOA9L.qicFW9jsVgxiaUd/u', 'superadmin', 1);

-- --------------------------------------------------------

--
-- Table structure for table `kategori_menu`
--

CREATE TABLE `kategori_menu` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kategori_menu`
--

INSERT INTO `kategori_menu` (`id`, `nama`) VALUES
(2, 'Lauk'),
(1, 'Makanan Utama'),
(4, 'Minuman'),
(3, 'Sayur');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(10) UNSIGNED NOT NULL,
  `slug` varchar(80) NOT NULL,
  `kategori_id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(150) NOT NULL,
  `harga` int(10) UNSIGNED NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `berat` varchar(30) DEFAULT NULL,
  `kalori` varchar(30) DEFAULT NULL,
  `tingkat_pedas` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 CHECK (`tingkat_pedas` between 0 and 5),
  `rating` decimal(2,1) DEFAULT 0.0 CHECK (`rating` between 0 and 5),
  `jumlah_ulasan` int(10) UNSIGNED DEFAULT 0,
  `gambar_utama` varchar(255) DEFAULT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `slug`, `kategori_id`, `nama`, `harga`, `deskripsi`, `berat`, `kalori`, `tingkat_pedas`, `rating`, `jumlah_ulasan`, `gambar_utama`, `status`) VALUES
(1, 'rendang', 1, 'Rendang Daging', 25000, 'Daging sapi pilihan dimasak perlahan dengan bumbu rempah-rempah khas Padang hingga empuk dan meresap sempurna.', '± 150 gram', '320 kkal', 4, 4.8, 125, 'img/071114000_1522751934-Resep-Rendang-Ayam-Kering.jpg', 'aktif'),
(2, 'ayam-pop', 2, 'Ayam Pop', 20000, 'Ayam kampung goreng khas Padang yang gurih dan empuk.', '± 180 gram', '250 kkal', 1, 4.7, 89, 'img/451c3a2537616f5d5d06750972b5458e.jpg', 'aktif'),
(3, 'dendeng-balado', 2, 'Dendeng Balado', 28000, 'Dendeng sapi dengan sambal balado pedas manis yang menggugah selera.', '± 120 gram', '290 kkal', 4, 4.9, 110, 'img/ac649842c150745962b88a60234d9091.jpg', 'aktif'),
(4, 'gulai-singkong', 3, 'Gulai Daun Singkong', 15000, 'Daun singkong dimasak gulai santan khas Padang dengan cita rasa gurih.', '± 150 gram', '180 kkal', 2, 4.6, 65, 'img/d3f1a9ad74bb9ce6050ae1f6ee87763a.jpg', 'aktif'),
(5, 'es-jeruk', 4, 'Es Jeruk', 8000, 'Minuman segar pelepas dahaga dari perasan jeruk segar.', '± 250 ml', '90 kkal', 0, 4.5, 45, 'img/30825f62038ff446435a521c0237f561.jpg', 'aktif'),
(6, 'gulai-ayam', 1, 'Gulai Ayam', 22000, 'Ayam dimasak gulai santan kuning khas Minang, kaya rempah dan gurih.', '± 200 gram', '310 kkal', 3, 4.7, 78, 'img/ef425d5c417882455b49f2948c032384.jpg', 'aktif');

-- --------------------------------------------------------

--
-- Table structure for table `menu_tag`
--

CREATE TABLE `menu_tag` (
  `menu_id` int(10) UNSIGNED NOT NULL,
  `tag_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menu_thumbnail`
--

CREATE TABLE `menu_thumbnail` (
  `id` int(10) UNSIGNED NOT NULL,
  `menu_id` int(10) UNSIGNED NOT NULL,
  `url_gambar` varchar(255) NOT NULL,
  `urutan` tinyint(3) UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `paket_catering`
--

CREATE TABLE `paket_catering` (
  `id` int(10) UNSIGNED NOT NULL,
  `kode_paket` varchar(5) NOT NULL,
  `nama_paket` varchar(100) NOT NULL,
  `porsi` varchar(30) NOT NULL,
  `harga` int(10) UNSIGNED NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `paket_catering`
--

INSERT INTO `paket_catering` (`id`, `kode_paket`, `nama_paket`, `porsi`, `harga`, `gambar`, `deskripsi`) VALUES
(1, 'A', 'Paket A', '50 Porsi', 1500000, 'img/diskon20_.jpg', NULL),
(2, 'B', 'Paket B', '100 Porsi', 2800000, 'img/30825f62038ff446435a521c0237f561.jpg', NULL),
(3, 'C', 'Paket C', '150 Porsi', 4000000, 'img/d3f1a9ad74bb9ce6050ae1f6ee87763a.jpg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `kontak` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL DEFAULT '',
  `needs_password_setup` tinyint(1) NOT NULL DEFAULT 0,
  `google_id` varchar(50) DEFAULT NULL,
  `foto_url` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `kontak` (`kontak`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pesanan_catering`
--

CREATE TABLE `pesanan_catering` (
  `id` int(10) UNSIGNED NOT NULL,
  `kode_pesanan` varchar(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `telepon` varchar(20) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `tanggal_acara` date NOT NULL,
  `jenis_acara` varchar(50) DEFAULT NULL,
  `jumlah_tamu` smallint(5) UNSIGNED DEFAULT NULL,
  `paket_id` int(10) UNSIGNED NOT NULL,
  `bukti_bayar` varchar(255) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `status` enum('pending','dikonfirmasi','selesai','dibatalkan') NOT NULL DEFAULT 'pending',
  `waktu_daftar` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pesanan_menu`
--

CREATE TABLE `pesanan_menu` (
  `id` int(10) UNSIGNED NOT NULL,
  `kode_pesanan` varchar(10) NOT NULL,
  `nama_pemesan` varchar(100) NOT NULL,
  `telepon` varchar(20) NOT NULL,
  `order_type` enum('dinein','delivery','takeaway') NOT NULL,
  `lokasi` varchar(255) NOT NULL,
  `subtotal` int(10) UNSIGNED NOT NULL,
  `pajak` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `total` int(10) UNSIGNED NOT NULL,
  `metode_bayar` enum('QRIS','Transfer BCA') NOT NULL,
  `bukti_bayar` varchar(255) NOT NULL,
  `catatan` text DEFAULT NULL,
  `status` enum('pending','diproses','selesai','dibatalkan') NOT NULL DEFAULT 'pending',
  `waktu_daftar` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pesanan_menu_item`
--

CREATE TABLE `pesanan_menu_item` (
  `id` int(10) UNSIGNED NOT NULL,
  `pesanan_id` int(10) UNSIGNED NOT NULL,
  `menu_id` int(10) UNSIGNED NOT NULL,
  `qty` smallint(5) UNSIGNED NOT NULL DEFAULT 1,
  `harga_satuan` int(10) UNSIGNED NOT NULL,
  `subtotal` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pesan_kontak`
--

CREATE TABLE `pesan_kontak` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `kontak_whatsapp` varchar(20) NOT NULL,
  `subjek` varchar(100) DEFAULT NULL,
  `isi_pesan` text NOT NULL,
  `dibaca` tinyint(1) NOT NULL DEFAULT 0,
  `waktu` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `promosi`
--

CREATE TABLE `promosi` (
  `id` int(10) UNSIGNED NOT NULL,
  `judul` varchar(100) NOT NULL,
  `sub_judul` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `diskon_persen` tinyint(3) UNSIGNED DEFAULT NULL CHECK (`diskon_persen` between 0 and 100),
  `warna_tema` enum('red','gold','maroon') DEFAULT 'red',
  `tanggal_mulai` date NOT NULL,
  `tanggal_akhir` date NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ;

-- --------------------------------------------------------

--
-- Table structure for table `reservasi`
--

CREATE TABLE `reservasi` (
  `id` int(10) UNSIGNED NOT NULL,
  `kode_reservasi` varchar(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `telepon` varchar(20) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `jam` time NOT NULL,
  `jumlah_tamu` smallint(5) UNSIGNED NOT NULL,
  `jenis_acara` varchar(50) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `status` enum('pending','dikonfirmasi','selesai','dibatalkan') NOT NULL DEFAULT 'pending',
  `waktu_daftar` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `status_log`
--

CREATE TABLE `status_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `referensi_tabel` enum('reservasi','pesanan_menu','pesanan_catering') NOT NULL,
  `referensi_id` int(10) UNSIGNED NOT NULL,
  `status_lama` varchar(30) DEFAULT NULL,
  `status_baru` varchar(30) NOT NULL,
  `diubah_oleh` int(10) UNSIGNED DEFAULT NULL,
  `waktu_ubah` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscriber_newsletter`
--

CREATE TABLE `subscriber_newsletter` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(150) NOT NULL,
  `tanggal_daftar` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

CREATE TABLE `tag` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `kategori_menu`
--
ALTER TABLE `kategori_menu`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama` (`nama`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_menu_kategori` (`kategori_id`),
  ADD KEY `idx_menu_status` (`status`);

--
-- Indexes for table `menu_tag`
--
ALTER TABLE `menu_tag`
  ADD PRIMARY KEY (`menu_id`,`tag_id`),
  ADD KEY `fk_menutag_tag` (`tag_id`);

--
-- Indexes for table `menu_thumbnail`
--
ALTER TABLE `menu_thumbnail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_thumb_menu` (`menu_id`);

--
-- Indexes for table `paket_catering`
--
ALTER TABLE `paket_catering`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_paket` (`kode_paket`);

--
-- Indexes for table `pesanan_catering`
--
ALTER TABLE `pesanan_catering`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_pesanan` (`kode_pesanan`),
  ADD KEY `fk_pesancat_paket` (`paket_id`),
  ADD KEY `idx_pesanan_cat_status` (`status`);

--
-- Indexes for table `pesanan_menu`
--
ALTER TABLE `pesanan_menu`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_pesanan` (`kode_pesanan`),
  ADD KEY `idx_pesanan_menu_status` (`status`);

--
-- Indexes for table `pesanan_menu_item`
--
ALTER TABLE `pesanan_menu_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_item_pesanan` (`pesanan_id`),
  ADD KEY `fk_item_menu` (`menu_id`);

--
-- Indexes for table `pesan_kontak`
--
ALTER TABLE `pesan_kontak`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pesan_kontak_dibaca` (`dibaca`);

--
-- Indexes for table `promosi`
--
ALTER TABLE `promosi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_promosi_status` (`status`);

--
-- Indexes for table `reservasi`
--
ALTER TABLE `reservasi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_reservasi` (`kode_reservasi`),
  ADD KEY `idx_reservasi_status` (`status`),
  ADD KEY `idx_reservasi_tanggal` (`tanggal`);

--
-- Indexes for table `status_log`
--
ALTER TABLE `status_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_log_admin` (`diubah_oleh`);

--
-- Indexes for table `subscriber_newsletter`
--
ALTER TABLE `subscriber_newsletter`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama` (`nama`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `kategori_menu`
--
ALTER TABLE `kategori_menu`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `menu_thumbnail`
--
ALTER TABLE `menu_thumbnail`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `paket_catering`
--
ALTER TABLE `paket_catering`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pesanan_catering`
--
ALTER TABLE `pesanan_catering`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pesanan_menu`
--
ALTER TABLE `pesanan_menu`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pesanan_menu_item`
--
ALTER TABLE `pesanan_menu_item`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pesan_kontak`
--
ALTER TABLE `pesan_kontak`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `promosi`
--
ALTER TABLE `promosi`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reservasi`
--
ALTER TABLE `reservasi`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `status_log`
--
ALTER TABLE `status_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscriber_newsletter`
--
ALTER TABLE `subscriber_newsletter`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tag`
--
ALTER TABLE `tag`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `fk_menu_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `kategori_menu` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `menu_tag`
--
ALTER TABLE `menu_tag`
  ADD CONSTRAINT `fk_menutag_menu` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_menutag_tag` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `menu_thumbnail`
--
ALTER TABLE `menu_thumbnail`
  ADD CONSTRAINT `fk_thumb_menu` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pesanan_catering`
--
ALTER TABLE `pesanan_catering`
  ADD CONSTRAINT `fk_pesancat_paket` FOREIGN KEY (`paket_id`) REFERENCES `paket_catering` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `pesanan_menu_item`
--
ALTER TABLE `pesanan_menu_item`
  ADD CONSTRAINT `fk_item_menu` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_item_pesanan` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan_menu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `status_log`
--
ALTER TABLE `status_log`
  ADD CONSTRAINT `fk_log_admin` FOREIGN KEY (`diubah_oleh`) REFERENCES `admin` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

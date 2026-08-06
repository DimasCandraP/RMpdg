-- ============================================================
-- SEEDER: Data Menu Lengkap - Resto Pesona Kapau (Tanpa Tambahan)
-- ============================================================

USE `rmpdg_db`;

-- Disable foreign key checks sementara
SET FOREIGN_KEY_CHECKS = 0;

-- Hapus data kategori & menu
DELETE FROM `menu_thumbnail`;
DELETE FROM `menu_tag`;
DELETE FROM `pesanan_menu_item`;
DELETE FROM `menu`;
DELETE FROM `kategori_menu`;

-- Reset AUTO_INCREMENT
ALTER TABLE `kategori_menu` AUTO_INCREMENT = 1;
ALTER TABLE `menu` AUTO_INCREMENT = 1;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- ------------------------------------------------------------
-- 1. Insert Kategori Menu Utama
-- ------------------------------------------------------------
INSERT INTO `kategori_menu` (`id`, `nama`) VALUES
(1, 'Paket Nasi Box'),
(2, 'Paket Nasi Bungkus'),
(3, 'Lauk'),
(4, 'Sayur'),
(5, 'Minuman');

-- ------------------------------------------------------------
-- 2. Insert Semua Item Menu (Tanpa En-Dash yang Merusak Encoding)
-- ------------------------------------------------------------
INSERT INTO `menu` (`slug`, `kategori_id`, `nama`, `harga`, `deskripsi`, `berat`, `kalori`, `tingkat_pedas`, `rating`, `jumlah_ulasan`, `gambar_utama`, `status`) VALUES

-- ===== PAKET NASI BOX (kategori_id = 1) =====
('paket-1', 1, 'Paket 1 - Nasi Box',
 30000,
 'Nasi, Sayur Kapau, Daun Singkong dan Sambal + Buah. Lauk: Rendang Daging Sapi, Pergedel/Telur dan Kerupuk.',
 '± 300 gram', '580 kkal', 4, 4.9, 210,
 'img/071114000_1522751934-Resep-Rendang-Ayam-Kering.jpg', 'aktif'),

('paket-2', 1, 'Paket 2 - Nasi Box',
 28000,
 'Nasi, Sayur Kapau, Daun Singkong dan Sambal. Lauk: Rendang Daging Sapi, Pergedel/Telur dan Kerupuk.',
 '± 280 gram', '550 kkal', 4, 4.8, 185,
 'img/071114000_1522751934-Resep-Rendang-Ayam-Kering.jpg', 'aktif'),

('paket-3', 1, 'Paket 3 - Nasi Box',
 25000,
 'Nasi, Sayur Kapau, Daun Singkong dan Sambal. Lauk: Rendang Daging Sapi dan Kerupuk.',
 '± 260 gram', '510 kkal', 4, 4.8, 162,
 'img/071114000_1522751934-Resep-Rendang-Ayam-Kering.jpg', 'aktif'),

('paket-4', 1, 'Paket 4 - Nasi Box',
 27000,
 'Nasi, Sayur Kapau, Daun Singkong dan Sambal + Buah. Lauk: Ayam Seperempat Rendang, Pergedel/Telur + Kerupuk.',
 '± 280 gram', '520 kkal', 4, 4.7, 148,
 'img/ef425d5c417882455b49f2948c032384.jpg', 'aktif'),

('paket-5', 1, 'Paket 5 - Nasi Box',
 25000,
 'Nasi, Sayur Kapau, Daun Singkong dan Sambal. Lauk: Ayam Seperempat Masak Rendang, Pergedel/Telur + Kerupuk.',
 '± 260 gram', '490 kkal', 4, 4.7, 130,
 'img/ef425d5c417882455b49f2948c032384.jpg', 'aktif'),

('paket-6', 1, 'Paket 6 - Nasi Box',
 20000,
 'Nasi, Sayur Kapau, Daun Singkong dan Sambal. Lauk: Ayam Seperempat Masak Rendang dan Kerupuk.',
 '± 240 gram', '460 kkal', 3, 4.6, 118,
 'img/ef425d5c417882455b49f2948c032384.jpg', 'aktif'),

('paket-7', 1, 'Paket 7 - Nasi Box',
 19000,
 'Nasi, Sayur Kapau, Daun Singkong dan Sambal. Lauk: Ayam Paha Masak Rendang dan Kerupuk.',
 '± 230 gram', '440 kkal', 3, 4.6, 105,
 'img/451c3a2537616f5d5d06750972b5458e.jpg', 'aktif'),

('paket-8', 1, 'Paket 8 - Nasi Box',
 18000,
 'Nasi, Sayur Kapau, Daun Singkong dan Sambal. Lauk: Ayam Paha Goreng + Kerupuk.',
 '± 220 gram', '420 kkal', 2, 4.5, 98,
 'img/451c3a2537616f5d5d06750972b5458e.jpg', 'aktif'),

('paket-9', 1, 'Paket 9 - Nasi Box',
 16000,
 'Nasi, Sayur Kapau, Daun Singkong dan Sambal. Lauk: Ayam Paha Goreng.',
 '± 200 gram', '390 kkal', 2, 4.5, 87,
 'img/451c3a2537616f5d5d06750972b5458e.jpg', 'aktif'),

('paket-10', 1, 'Paket 10 - Nasi Box (Pelajar)',
 15000,
 'Nasi, Sayur Kapau, Daun Singkong dan Sambal. Lauk: Ayam Paha Goreng. Khusus Santri/Pelajar/Mahasiswa.',
 '± 200 gram', '380 kkal', 2, 4.5, 75,
 'img/451c3a2537616f5d5d06750972b5458e.jpg', 'aktif'),

-- ===== PAKET NASI BUNGKUS (kategori_id = 2) =====
('paket-a', 2, 'Paket A - Nasi Bungkus',
 15000,
 'Nasi, Sayur dan Sambal + Rendang Ayam Paha. Harga dengan nasi Rp15.000 / tanpa nasi Rp12.000.',
 '± 220 gram', '430 kkal', 4, 4.8, 143,
 'img/071114000_1522751934-Resep-Rendang-Ayam-Kering.jpg', 'aktif'),

('paket-b', 2, 'Paket B - Nasi Bungkus',
 13000,
 'Nasi, Sayur dan Sambal + Ayam Paha Goreng. Harga dengan nasi Rp13.000 / tanpa nasi Rp11.000.',
 '± 200 gram', '400 kkal', 2, 4.7, 128,
 'img/451c3a2537616f5d5d06750972b5458e.jpg', 'aktif'),

('paket-c', 2, 'Paket C - Nasi Bungkus (Promo)',
 10000,
 'Nasi, Sayur dan Sambal + Ayam Sepotong Goreng / Telur Ayam Ceplok. Harga dengan nasi Rp10.000 / tanpa nasi Rp9.000.',
 '± 180 gram', '360 kkal', 2, 4.6, 115,
 'img/cf3db3f294804090c83eb546c88da565.jpg', 'aktif'),

('paket-d', 2, 'Paket D - Nasi Bungkus',
 9000,
 'Nasi, Sayur dan Sambal + Telur Ayam Gulai. Harga dengan nasi Rp9.000 / tanpa nasi Rp8.000. Tambahan Tempe/Tahu kecil Rp1.000 / besar Rp2.000.',
 '± 170 gram', '320 kkal', 2, 4.5, 99,
 'img/cf3db3f294804090c83eb546c88da565.jpg', 'aktif'),

('syrofoam', 2, 'Kemasan Syrofoam',
 2000,
 'Tambahan kemasan syrofoam untuk nasi bungkus agar lebih praktis dibawa.',
 '-', '-', 0, 4.0, 30,
 'img/30825f62038ff446435a521c0237f561.jpg', 'aktif'),

-- ===== LAUK SATUAN (kategori_id = 3) =====
('rendang', 3, 'Rendang Daging Sapi',
 25000,
 'Daging sapi pilihan dimasak perlahan dengan bumbu rempah-rempah khas Padang hingga empuk dan meresap sempurna.',
 '± 150 gram', '320 kkal', 4, 4.9, 250,
 'img/071114000_1522751934-Resep-Rendang-Ayam-Kering.jpg', 'aktif'),

('ayam-rendang', 3, 'Ayam Masak Rendang',
 20000,
 'Ayam paha dimasak dengan bumbu rendang khas Padang yang kaya rempah, pedas dan gurih.',
 '± 150 gram', '290 kkal', 4, 4.8, 178,
 'img/071114000_1522751934-Resep-Rendang-Ayam-Kering.jpg', 'aktif'),

('ayam-goreng', 3, 'Ayam Paha Goreng',
 15000,
 'Ayam paha goreng krispi dengan bumbu khas Padang yang gurih dan renyah.',
 '± 130 gram', '250 kkal', 1, 4.7, 165,
 'img/451c3a2537616f5d5d06750972b5458e.jpg', 'aktif'),

('ayam-pop', 3, 'Ayam Pop',
 20000,
 'Ayam kampung goreng khas Padang yang gurih, empuk, disajikan dengan sambal khas yang manis gurih.',
 '± 120 gram', '240 kkal', 1, 4.7, 98,
 'img/451c3a2537616f5d5d06750972b5458e.jpg', 'aktif'),

('dendeng-balado', 3, 'Dendeng Balado',
 28000,
 'Dendeng sapi dengan sambal balado pedas manis yang menggugah selera, renyah di luar lembut di dalam.',
 '± 100 gram', '290 kkal', 5, 4.9, 142,
 'img/ac649842c150745962b88a60234d9091.jpg', 'aktif'),

('gulai-ayam', 3, 'Gulai Ayam',
 22000,
 'Ayam dimasak gulai santan kuning khas Minang, kaya rempah-rempah khas Padang dan sangat gurih.',
 '± 150 gram', '280 kkal', 3, 4.7, 95,
 'img/ef425d5c417882455b49f2948c032384.jpg', 'aktif'),

('pergedel', 3, 'Pergedel / Telur',
 5000,
 'Pergedel kentang atau telur goreng/gulai sebagai lauk tambahan pelengkap nasi.',
 '± 80 gram', '150 kkal', 1, 4.4, 88,
 'img/cf3db3f294804090c83eb546c88da565.jpg', 'aktif'),

('telur-gulai', 3, 'Telur Ayam Gulai',
 8000,
 'Telur ayam dimasak dengan kuah gulai santan kuning khas Padang, gurih dan lezat.',
 '± 100 gram', '200 kkal', 2, 4.5, 72,
 'img/cf3db3f294804090c83eb546c88da565.jpg', 'aktif'),

('tempe-tahu-kecil', 3, 'Tempe / Tahu (Kecil)',
 1000,
 'Tempe atau tahu goreng ukuran kecil sebagai lauk pelengkap.',
 '± 50 gram', '80 kkal', 0, 4.3, 55,
 'img/cf3db3f294804090c83eb546c88da565.jpg', 'aktif'),

('tempe-tahu-besar', 3, 'Tempe / Tahu (Besar)',
 2000,
 'Tempe atau tahu goreng ukuran besar sebagai lauk pelengkap.',
 '± 100 gram', '150 kkal', 0, 4.3, 48,
 'img/cf3db3f294804090c83eb546c88da565.jpg', 'aktif'),

('kerupuk', 3, 'Kerupuk',
 1000,
 'Kerupuk renyah sebagai pelengkap makan, cocok dipadukan dengan lauk khas Padang.',
 '± 20 gram', '60 kkal', 0, 4.2, 42,
 'img/30825f62038ff446435a521c0237f561.jpg', 'aktif'),

-- ===== SAYUR (kategori_id = 4) =====
('sayur-kapau', 4, 'Sayur Kapau',
 5000,
 'Sayur khas Kapau Padang dengan kuah santan kuning berisi berbagai sayuran segar dan bumbu rempah.',
 '± 200 gram', '160 kkal', 2, 4.7, 92,
 'img/d3f1a9ad74bb9ce6050ae1f6ee87763a.jpg', 'aktif'),

('gulai-singkong', 4, 'Gulai Daun Singkong',
 5000,
 'Daun singkong dimasak gulai santan khas Padang dengan cita rasa gurih dan bumbu rempah pilihan.',
 '± 180 gram', '180 kkal', 2, 4.6, 80,
 'img/d3f1a9ad74bb9ce6050ae1f6ee87763a.jpg', 'aktif'),

-- ===== MINUMAN (kategori_id = 5) =====
('es-jeruk', 5, 'Es Jeruk',
 8000,
 'Minuman perasan jeruk manis asli yang segar dan dingin, pas sebagai penutup hidangan pedas.',
 '± 300 ml', '90 kkal', 0, 4.8, 110,
 'img/30825f62038ff446435a521c0237f561.jpg', 'aktif');

SELECT k.nama AS Kategori, COUNT(m.id) AS `Jumlah Menu`
FROM kategori_menu k
LEFT JOIN menu m ON m.kategori_id = k.id
GROUP BY k.id, k.nama;

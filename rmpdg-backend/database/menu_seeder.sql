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

<<<<<<< HEAD
('kerupuk', 3, 'Kerupuk',
=======
 ('kerupuk', 3, 'Kerupuk',
>>>>>>> e44a34234917649369ce51e435e784885e0328b6
 1000,
 'Kerupuk renyah sebagai pelengkap makan, cocok dipadukan dengan lauk khas Padang.',
 '± 20 gram', '60 kkal', 0, 4.2, 42,
 'img/30825f62038ff446435a521c0237f561.jpg', 'aktif'),

<<<<<<< HEAD
=======
('rendang-sapi-bukittinggi', 3, 'Rendang Sapi Bukit Tinggi',
 28000,
 'Daging sapi olahan khas Bukittinggi dimasak dengan bumbu rempah-rempah otentik hingga meresap sempurna dan berwarna pekat gurih nikmat.',
 '± 150 gram', '330 kkal', 4, 4.9, 195,
 'img/IMG_6946.PNG', 'aktif'),

('limpa-sapi-kalio', 3, 'Limpa Sapi Kalio',
 22000,
 'Limpa sapi empuk yang dimasak kuah kalio gurih berrempah khas Pesona Kapau.',
 '± 130 gram', '270 kkal', 3, 4.7, 112,
 'img/IMG_6946.PNG', 'aktif'),

('tunjang-sapi', 3, 'Tunjang Sapi (Kikil)',
 26000,
 'Kikil / tunjang sapi pilihan yang empuk dan kenyal, disajikan dalam kuah gulai santan kuning khas Padang.',
 '± 180 gram', '310 kkal', 3, 4.9, 230,
 'img/IMG_6947.PNG', 'aktif'),

('asam-padeh-gajebo', 3, 'Asam Padeh Gajebo',
 27000,
 'Daging gajebo gurih dengan kuah asam padeh yang segar dan pedas mantap khas Minang.',
 '± 150 gram', '340 kkal', 4, 4.9, 205,
 'img/IMG_6947.PNG', 'aktif'),

('hati-sapi-kalio', 3, 'Hati Sapi Kalio',
 22000,
 'Hati sapi olahan bertekstur lembut disiram kuah kalio kental nan gurih kaya rempah.',
 '± 140 gram', '280 kkal', 3, 4.7, 104,
 'img/IMG_6947.PNG', 'aktif'),

('dendeng-kering', 3, 'Dendeng Kering',
 28000,
 'Irisan daging sapi tipis yang digoreng hingga garing dan renyah beraroma gurih rempah khas Padang.',
 '± 100 gram', '290 kkal', 2, 4.8, 168,
 'img/IMG_6948.PNG', 'aktif'),

('paru-kering', 3, 'Paru Kering',
 24000,
 'Paru sapi goreng renyah dan gurih, pas menjadi lauk pendamping santapan nasi Padang.',
 '± 110 gram', '270 kkal', 2, 4.8, 175,
 'img/IMG_6948.PNG', 'aktif'),

('dendeng-cabai-merah', 3, 'Dendeng Cabai Merah',
 28000,
 'Dendeng sapi goreng disajikan dengan baluran sambal cabai merah pecah yang pedas nikmat.',
 '± 120 gram', '300 kkal', 5, 4.9, 188,
 'img/IMG_6948.PNG', 'aktif'),

('tambusu', 3, 'Tambusu (Usus Sapi Isi Telur)',
 27000,
 'Kami menyediakan menu olahan khas dari BUKIT TINGGI. TAMBUSU usus sapi yang di isi dengan telur. Salah satu makanan otentik dari bukit tinggi yang sudah terkenal, dipadukan dengan kuah gulai nikmat.',
 '± 180 gram', '350 kkal', 3, 5.0, 280,
 'img/IMG_6952.PNG', 'aktif'),

('gulai-banak', 3, 'Gulai Banak (Otak Sapi)',
 26000,
 'Gulai banak adalah gulai otak sapi yang di masak dengan kuah gulai asam pedas yang segar dan nikmat sebagai lauk pendamping nasi padang.',
 '± 160 gram', '290 kkal', 3, 4.9, 190,
 'img/IMG_6953.PNG', 'aktif'),

('cincang-kambing', 3, 'Cincang Kambing',
 30000,
 'menu olahan cincang kambing kami adalah menu otentik dari bukit tinggi, daging kambing yang di masak dengan bumbu kaya rempah serta kuah santan yang kental menjadikan rasa daging kambing semakin nikmat.',
 '± 200 gram', '380 kkal', 4, 4.9, 220,
 'img/IMG_6954.PNG', 'aktif'),

('sop-buntut-iga', 3, 'Sop Buntut dan Iga Sapi',
 35000,
 'Sop Buntut dan Iga sapi yang masak dengan bumbu kaya rempah dan teksture buntuk dan iga yang lembut disajikan dalam kondisi panas menjadikan menu makan anda semakin nikmat.',
 '± 350 gram', '420 kkal', 1, 5.0, 245,
 'img/IMG_6961.PNG', 'aktif'),

('ayam-kemangi', 3, 'Ayam Kemangi',
 20000,
 'Olahan ayam bumbu rempah kuning yang dipadukan dengan aroma daun kemangi segar yang harum dan gurih.',
 '± 150 gram', '270 kkal', 3, 4.8, 135,
 'img/IMG_6949.PNG', 'aktif'),

('ayam-goreng-bumbu', 3, 'Ayam Goreng Bumbu',
 18000,
 'Ayam goreng dengan taburan bumbu rempah goreng khas Padang yang gurih dan renyah.',
 '± 140 gram', '260 kkal', 1, 4.7, 140,
 'img/IMG_6949.PNG', 'aktif'),

('ayam-bakar', 3, 'Ayam Bakar Padang',
 20000,
 'Ayam pilihan yang dibakar dengan bumbu rempah padang hingga harum meresap ke dalam daging.',
 '± 150 gram', '280 kkal', 3, 4.8, 160,
 'img/IMG_6949.PNG', 'aktif'),

('ayam-cabai-merah', 3, 'Ayam Cabai Merah',
 20000,
 'Ayam potong goreng disajikan dengan baluran sambal cabai merah khas Minang yang pedas gurih.',
 '± 150 gram', '280 kkal', 4, 4.7, 125,
 'img/IMG_6949.PNG', 'aktif'),

('itik-lado-mudo', 3, 'Itik Lado Mudo',
 32000,
 'Kami menyediakan menu olahan bebek khas dari minang kabau. bebek yang di masak dengan cabai hijau. Dengan rasa yang istimewa yang akan memuaskan selera.',
 '± 200 gram', '360 kkal', 4, 4.9, 170,
 'img/IMG_6956.PNG', 'aktif'),

('kepala-ikan-kakap', 3, 'Kepala Ikan Kakap',
 75000,
 'spesial Ikan Kepala Kakap merah yang di masak kuah kuning asam khas pesona kapau, kuah yang segar di padukan dengan kepala ikan kakap yang gurih menjadi pembangkit selera makan anda.',
 '± 500 gram', '450 kkal', 3, 5.0, 310,
 'img/IMG_6950.PNG', 'aktif'),

('gulai-kakap-fillet', 3, 'Gulai Kakap Fillet',
 25000,
 'Daging fillet ikan kakap tanpa duri yang lembut dimasak dalam kuah gulai santan kuning gurih khas Minang.',
 '± 150 gram', '250 kkal', 2, 4.8, 120,
 'img/IMG_6957.PNG', 'aktif'),

('ikan-nila-bakar', 3, 'Ikan Nila Bakar',
 22000,
 'Ikan nila segar dibakar dengan baluran bumbu bumbu kuning padang yang manis gurih beraroma asap harum.',
 '± 200 gram', '280 kkal', 2, 4.7, 110,
 'img/IMG_6950.PNG', 'aktif'),

('ikan-gembung-goreng', 3, 'Ikan Gembung Goreng',
 18000,
 'Ikan gembung goreng garing renyah disajikan dengan taburan bumbu rempah gurih khas Padang.',
 '± 160 gram', '230 kkal', 1, 4.6, 95,
 'img/IMG_6950.PNG', 'aktif'),

('ikan-gembung-sambal-mentah', 3, 'Ikan Gembung Sambal Mentah',
 19000,
 'Ikan gembung goreng disajikan dengan irisan sambal mentah dabu-dabu khas yang pedas dan segar.',
 '± 160 gram', '240 kkal', 4, 4.7, 105,
 'img/IMG_6950.PNG', 'aktif'),

('ikan-gembung-pepes', 3, 'Ikan Gembung Pepes',
 20000,
 'Pepes ikan gembung bumbu rempah berbalut daun pisang yang dibakar aroma harum khas meresap sempurna.',
 '± 170 gram', '220 kkal', 3, 4.8, 115,
 'img/IMG_6950.PNG', 'aktif'),

('ikan-bilih-goreng', 3, 'Ikan Bilih Goreng',
 20000,
 'Ikan bilih khas Danau Singkarak Minang yang digoreng garing dan renyah gurih disajikan dengan sambal.',
 '± 100 gram', '210 kkal', 3, 4.9, 160,
 'img/IMG_6950.PNG', 'aktif'),

('ikan-lele-goreng', 3, 'Ikan Lele Goreng',
 15000,
 'Ikan lele goreng renyah bumbu rempah disajikan dengan kremesan dan sambal padang nikmat.',
 '± 150 gram', '220 kkal', 2, 4.6, 88,
 'img/IMG_6950.PNG', 'aktif'),

('cumi-sambal-merah', 3, 'Cumi Cumi Sambal Merah',
 25000,
 'Olahan cumi-cumi segar empuk dipadukan dengan kuah sambal merah pedas manis mantap.',
 '± 150 gram', '260 kkal', 4, 4.8, 145,
 'img/IMG_6957.PNG', 'aktif'),

('udang-sambal-merah', 3, 'Udang Sambal Merah',
 25000,
 'Udang segar dimasak bumbu sambal merah khas padang dengan rasa gurih pedas manis yang menggoda.',
 '± 140 gram', '250 kkal', 4, 4.8, 150,
 'img/IMG_6957.PNG', 'aktif'),

('telur-balado', 3, 'Telur Balado',
 7000,
 'Telur ayam bulat goreng dengan baluran sambal balado merah pedas manis gurih.',
 '± 90 gram', '180 kkal', 3, 4.7, 95,
 'img/IMG_6959.PNG', 'aktif'),

('telur-dadar', 3, 'Telur Dadar Padang',
 8000,
 'Telur dadar tebal khas Padang dengan campuran daun bawang dan rempah yang gurih renyah di luar lembut di dalam.',
 '± 110 gram', '210 kkal', 1, 4.8, 140,
 'img/IMG_6959.PNG', 'aktif'),

>>>>>>> e44a34234917649369ce51e435e784885e0328b6
-- ===== SAYUR (kategori_id = 4) =====
('sayur-kapau', 4, 'Sayur Kapau',
 5000,
 'Sayur khas Kapau Padang dengan kuah santan kuning berisi berbagai sayuran segar dan bumbu rempah.',
 '± 200 gram', '160 kkal', 2, 4.7, 92,
<<<<<<< HEAD
 'img/d3f1a9ad74bb9ce6050ae1f6ee87763a.jpg', 'aktif'),
=======
 'img/IMG_6958.PNG', 'aktif'),
>>>>>>> e44a34234917649369ce51e435e784885e0328b6

('gulai-singkong', 4, 'Gulai Daun Singkong',
 5000,
 'Daun singkong dimasak gulai santan khas Padang dengan cita rasa gurih dan bumbu rempah pilihan.',
 '± 180 gram', '180 kkal', 2, 4.6, 80,
 'img/d3f1a9ad74bb9ce6050ae1f6ee87763a.jpg', 'aktif'),

<<<<<<< HEAD
-- ===== MINUMAN (kategori_id = 5) =====
=======
('daun-ubi', 4, 'Daun Ubi / Singkong Rebus',
 4000,
 'Rebusan daun singkong/ubi segar pilihan, lembut dan hijau alami sebagai pendamping lauk nasi Padang.',
 '± 150 gram', '90 kkal', 0, 4.6, 65,
 'img/IMG_6958.PNG', 'aktif'),

('rendang-jengkol', 4, 'Rendang Jengkol',
 12000,
 'Jengkol empuk olahan khas dimasak bumbu rendang padang yang legit, gurih, dan pedas mantap.',
 '± 150 gram', '240 kkal', 4, 4.8, 130,
 'img/IMG_6958.PNG', 'aktif'),

('sayur-tauge', 4, 'Sayur Tauge Tahu',
 5000,
 'Tumis sayur tauge segar dengan tahu yang renyah dan gurih, pelengkap sempurna makanan padang.',
 '± 150 gram', '110 kkal', 1, 4.5, 58,
 'img/IMG_6958.PNG', 'aktif'),

('jengkol-cabai-hijau', 4, 'Jengkol Cabai Hijau',
 12000,
 'Olahan jengkol empuk ditumis dengan sambal cabai hijau mentah khas Minang yang pedas dan harum.',
 '± 150 gram', '230 kkal', 4, 4.8, 122,
 'img/IMG_6958.PNG', 'aktif'),

('sambal-merah', 4, 'Sambal Merah',
 3000,
 'Sambal merah khas Padang dari cabai merah pilihan yang ditumis minyak gurih beraroma khas.',
 '± 50 gram', '80 kkal', 4, 4.7, 85,
 'img/IMG_6958.PNG', 'aktif'),

('sambal-hijau', 4, 'Sambal Hijau',
 3000,
 'Sambal cabai hijau padang otentik dengan racikan tomat hijau dan rempah yang pedas gurih.',
 '± 50 gram', '70 kkal', 3, 4.8, 98,
 'img/IMG_6958.PNG', 'aktif'),

-- ===== MINUMAN (kategori_id = 5) =====
('teh-telur', 5, 'Teh Telur',
 12000,
 'Teh telur dan Kopi telur adalah minuman khas dari minang kabau yang dapat menjadi minuman yang meningkatkan stamina anda.',
 '± 250 ml', '180 kkal', 0, 4.9, 165,
 'img/IMG_6962.PNG', 'aktif'),

('kopi-telur', 5, 'Kopi Telur',
 14000,
 'Kopi dipadukan dengan kocokan telur khas Minangkabau yang kaya rasa dan dapat meningkatkan stamina anda.',
 '± 250 ml', '200 kkal', 0, 4.9, 140,
 'img/IMG_6962.PNG', 'aktif'),

('soda-strawberry', 5, 'Soda Strawberry',
 12000,
 'Minuman es soda segar yang menyegarkan badan anda untuk semangat beraktivitas.',
 '± 300 ml', '130 kkal', 0, 4.7, 88,
 'img/IMG_6963.PNG', 'aktif'),

('soda-gembira', 5, 'Soda Gembira',
 14000,
 'Minuman es soda segar dipadukan susu kental manis dan sirup yang menyegarkan badan anda untuk semangat beraktivitas.',
 '± 350 ml', '160 kkal', 0, 4.8, 102,
 'img/IMG_6963.PNG', 'aktif'),

('jus-alpukat', 5, 'Jus Alpukat',
 15000,
 'Jus buah alpukat segar alami pilihan disajikan dengan cocolan susu cokelat nikmat.',
 '± 350 ml', '190 kkal', 0, 4.9, 125,
 'img/IMG_6964.PNG', 'aktif'),

('jus-mangga', 5, 'Jus Mangga',
 15000,
 'Jus buah mangga manis dan segar alami kaya vitamin C.',
 '± 350 ml', '140 kkal', 0, 4.8, 110,
 'img/IMG_6964.PNG', 'aktif'),

('jus-sirsak', 5, 'Jus Sirsak',
 15000,
 'Jus buah sirsak dengan cita rasa asam manis yang sangat menyegarkan.',
 '± 350 ml', '130 kkal', 0, 4.7, 85,
 'img/IMG_6964.PNG', 'aktif'),

('jus-semangka', 5, 'Jus Semangka',
 15000,
 'Jus buah semangka segar yang mendinginkan dahaga.',
 '± 350 ml', '110 kkal', 0, 4.8, 90,
 'img/IMG_6964.PNG', 'aktif'),

('jus-stroberi', 5, 'Jus Stroberi',
 15000,
 'Jus buah stroberi manis asam segar kaya antioksidan.',
 '± 350 ml', '120 kkal', 0, 4.7, 80,
 'img/IMG_6964.PNG', 'aktif'),

('jus-melon', 5, 'Jus Melon',
 15000,
 'Jus buah melon manis harum dan menyegarkan.',
 '± 350 ml', '120 kkal', 0, 4.7, 75,
 'img/IMG_6964.PNG', 'aktif'),

('teh-tarik', 5, 'Teh Tarik',
 15000,
 'Teh tarik nikmat berbusa lembut paduan teh dan susu yang gurih manis pas.',
 '± 300 ml', '140 kkal', 0, 4.8, 115,
 'img/IMG_6965.PNG', 'aktif'),

('teh-manis', 5, 'Teh Manis',
 7000,
 'Teh manis segar disajikan dingin atau hangat pelengkap makan hidangan Minang.',
 '± 300 ml', '70 kkal', 0, 4.7, 130,
 'img/IMG_6965.PNG', 'aktif'),

('jeruk-peras', 5, 'Jeruk Peras',
 15000,
 'Perasan jeruk manis segar pilihan disajikan dingin dengan es yang kaya vitamin C.',
 '± 300 ml', '110 kkal', 0, 4.8, 105,
 'img/IMG_6965.PNG', 'aktif'),

('lemon-tea', 5, 'Lemon Tea',
 15000,
 'Minuman teh diseduh segar dipadukan perasan jeruk lemon asli yang asam manis dingin.',
 '± 300 ml', '90 kkal', 0, 4.8, 98,
 'img/IMG_6965.PNG', 'aktif'),

('kopi-hitam', 5, 'Kopi Hitam',
 8000,
 'Kopi hitam tradisional diseduh dengan cita rasa dan aroma kopi mantap.',
 '± 250 ml', '10 kkal', 0, 4.7, 85,
 'img/IMG_6965.PNG', 'aktif'),

('kopi-susu', 5, 'Kopi Susu',
 10000,
 'Paduan kopi hitam mantap dengan susu manis gurih yang memberikan kesegaran.',
 '± 250 ml', '110 kkal', 0, 4.8, 110,
 'img/IMG_6965.PNG', 'aktif'),

>>>>>>> e44a34234917649369ce51e435e784885e0328b6
('es-jeruk', 5, 'Es Jeruk',
 8000,
 'Minuman perasan jeruk manis asli yang segar dan dingin, pas sebagai penutup hidangan pedas.',
 '± 300 ml', '90 kkal', 0, 4.8, 110,
 'img/30825f62038ff446435a521c0237f561.jpg', 'aktif');

SELECT k.nama AS Kategori, COUNT(m.id) AS `Jumlah Menu`
FROM kategori_menu k
LEFT JOIN menu m ON m.kategori_id = k.id
GROUP BY k.id, k.nama;

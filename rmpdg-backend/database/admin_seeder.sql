-- =========================================================
-- SQL Seeder untuk Akun Admin RM Padang
-- Kredensial Admin Default:
-- Email   : admin@rmpadang.com
-- Password: Admin#123456
-- =========================================================

INSERT INTO `admin` (`id`, `nama`, `email`, `password_hash`, `role`, `is_aktif`) 
VALUES (1, 'Administrator', 'admin@rmpadang.com', '$2y$10$.J5zDnQUzt3TG6e4lqKjM.RAxIpffbhQCOhK5avUXB3/nooNSJ9EO', 'superadmin', 1)
ON DUPLICATE KEY UPDATE 
  `nama`          = VALUES(`nama`),
  `password_hash` = VALUES(`password_hash`),
  `role`          = VALUES(`role`),
  `is_aktif`      = VALUES(`is_aktif`);

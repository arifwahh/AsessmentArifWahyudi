SET NAMES utf8mb4;

DROP TABLE IF EXISTS `couriers`;

CREATE TABLE `couriers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `level` tinyint unsigned NOT NULL,
  `vehicle_type` varchar(20) NOT NULL,
  `vehicle_plate` varchar(15) NOT NULL,
  `city` varchar(100) DEFAULT NULL,
  `address` text,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `couriers_email_unique` (`email`),
  UNIQUE KEY `couriers_phone_unique` (`phone`),
  UNIQUE KEY `couriers_vehicle_plate_unique` (`vehicle_plate`),
  KEY `couriers_name_index` (`name`),
  KEY `couriers_level_index` (`level`),
  KEY `couriers_created_at_index` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `couriers` (`name`, `email`, `phone`, `level`, `vehicle_type`, `vehicle_plate`, `city`, `address`, `is_active`, `created_at`, `updated_at`) VALUES
('Budiono Hadi Agung', 'budiono.agung@example.com', '081200000001', 3, 'motorcycle', 'B 1234 XYZ', 'Bandung', 'Jl. Merdeka No. 1', 1, '2025-11-01 08:00:00', '2025-11-01 08:00:00'),
('Ahmad Fauzi', 'ahmad.fauzi@example.com', '081200000002', 1, 'motorcycle', 'B 2345 ABC', 'Jakarta', 'Jl. Sudirman No. 2', 1, '2025-11-05 09:15:00', '2025-11-05 09:15:00'),
('Siti Aminah', 'siti.aminah@example.com', '081200000003', 4, 'van', 'D 3456 DEF', 'Bandung', 'Jl. Asia Afrika No. 3', 1, '2025-11-12 10:30:00', '2025-11-12 10:30:00'),
('Rizky Pratama', 'rizky.pratama@example.com', '081200000004', 2, 'car', 'F 4567 GHI', 'Bogor', 'Jl. Pajajaran No. 4', 1, '2025-11-20 11:45:00', '2025-11-20 11:45:00'),
('Dewi Lestari', 'dewi.lestari@example.com', '081200000005', 5, 'truck', 'B 5678 JKL', 'Bekasi', 'Jl. Ahmad Yani No. 5', 1, '2025-12-01 07:00:00', '2025-12-01 07:00:00'),
('Agus Setiawan', 'agus.setiawan@example.com', '081200000006', 3, 'motorcycle', 'AB 6789 MNO', 'Yogyakarta', 'Jl. Malioboro No. 6', 1, '2025-12-10 08:20:00', '2025-12-10 08:20:00'),
('Nia Ramadhani', 'nia.ramadhani@example.com', '081200000007', 2, 'car', 'D 7890 PQR', 'Semarang', 'Jl. Pemuda No. 7', 0, '2025-12-18 09:40:00', '2025-12-18 09:40:00'),
('Bayu Nugroho', 'bayu.nugroho@example.com', '081200000008', 1, 'motorcycle', 'H 8901 STU', 'Solo', 'Jl. Slamet Riyadi No. 8', 1, '2026-01-03 10:00:00', '2026-01-03 10:00:00'),
('Putri Wulandari', 'putri.wulandari@example.com', '081200000009', 4, 'van', 'L 9012 VWX', 'Surabaya', 'Jl. Tunjungan No. 9', 1, '2026-01-11 11:20:00', '2026-01-11 11:20:00'),
('Hendra Gunawan', 'hendra.gunawan@example.com', '081200000010', 5, 'truck', 'B 1122 YZA', 'Tangerang', 'Jl. BSD Raya No. 10', 1, '2026-01-19 12:40:00', '2026-01-19 12:40:00'),
('Maya Sari', 'maya.sari@example.com', '081200000011', 3, 'car', 'F 2233 BCD', 'Depok', 'Jl. Margonda No. 11', 1, '2026-02-02 13:00:00', '2026-02-02 13:00:00'),
('Fajar Hidayat', 'fajar.hidayat@example.com', '081200000012', 2, 'motorcycle', 'Z 3344 EFG', 'Cimahi', 'Jl. Baros No. 12', 1, '2026-02-10 14:20:00', '2026-02-10 14:20:00'),
('Indah Permata', 'indah.permata@example.com', '081200000013', 1, 'motorcycle', 'AA 4455 HIJ', 'Purwokerto', 'Jl. Jenderal Soedirman No. 13', 1, '2026-02-18 15:40:00', '2026-02-18 15:40:00'),
('Doni Saputra', 'doni.saputra@example.com', '081200000014', 4, 'van', 'B 5566 KLM', 'Jakarta', 'Jl. Gatot Subroto No. 14', 0, '2026-03-04 16:00:00', '2026-03-04 16:00:00'),
('Lina Marlina', 'lina.marlina@example.com', '081200000015', 3, 'car', 'D 6677 NOP', 'Bandung', 'Jl. Buah Batu No. 15', 1, '2026-03-12 17:20:00', '2026-03-12 17:20:00'),
('Eko Prasetyo', 'eko.prasetyo@example.com', '081200000016', 5, 'truck', 'AB 7788 QRS', 'Surabaya', 'Jl. Darmo No. 16', 1, '2026-03-20 18:40:00', '2026-03-20 18:40:00'),
('Wulan Sari', 'wulan.sari@example.com', '081200000017', 2, 'motorcycle', 'F 8899 TUV', 'Bogor', 'Jl. Pahlawan No. 17', 1, '2026-04-01 07:00:00', '2026-04-01 07:00:00'),
('Arif Maulana', 'arif.maulana@example.com', '081200000018', 1, 'motorcycle', 'B 9900 WXY', 'Bekasi', 'Jl. Kalimalang No. 18', 1, '2026-04-09 08:20:00', '2026-04-09 08:20:00'),
('Sri Wahyuni', 'sri.wahyuni@example.com', '081200000019', 4, 'van', 'H 1011 ZAB', 'Semarang', 'Jl. Pandanaran No. 19', 1, '2026-04-17 09:40:00', '2026-04-17 09:40:00'),
('Joko Susilo', 'joko.susilo@example.com', '081200000020', 3, 'car', 'L 1213 CDE', 'Solo', 'Jl. Adi Sucipto No. 20', 0, '2026-05-05 10:00:00', '2026-05-05 10:00:00'),
('Rina Astuti', 'rina.astuti@example.com', '081200000021', 5, 'truck', 'B 1314 FGH', 'Tangerang', 'Jl. Raya Serpong No. 21', 1, '2026-05-13 11:20:00', '2026-05-13 11:20:00'),
('Dimas Aditya', 'dimas.aditya@example.com', '081200000022', 2, 'motorcycle', 'Z 1415 IJK', 'Depok', 'Jl. Cinere Raya No. 22', 1, '2026-05-21 12:40:00', '2026-05-21 12:40:00'),
('Fitri Handayani', 'fitri.handayani@example.com', '081200000023', 1, 'motorcycle', 'AA 1516 LMN', 'Cimahi', 'Jl. Cimahi Raya No. 23', 1, '2026-06-02 13:00:00', '2026-06-02 13:00:00'),
('Andi Wijaya', 'andi.wijaya@example.com', '081200000024', 4, 'car', 'D 1617 OPQ', 'Jakarta', 'Jl. Kebon Jeruk No. 24', 1, '2026-06-10 14:20:00', '2026-06-10 14:20:00'),
('Nur Aisyah', 'nur.aisyah@example.com', '081200000025', 3, 'van', 'F 1718 RST', 'Bandung', 'Jl. Setiabudi No. 25', 1, '2026-06-18 15:40:00', '2026-06-18 15:40:00');

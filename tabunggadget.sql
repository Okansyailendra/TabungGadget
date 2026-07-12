-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 13 Jan 2026 pada 18.55
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tabunggadget`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `laptops`
--

CREATE TABLE `laptops` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `cpu` varchar(100) DEFAULT NULL,
  `ram` int(11) DEFAULT NULL,
  `storage` varchar(50) DEFAULT NULL,
  `gpu` varchar(100) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `price_range` varchar(50) DEFAULT NULL,
  `release_year` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `laptops`
--

INSERT INTO `laptops` (`id`, `name`, `price`, `cpu`, `ram`, `storage`, `gpu`, `category`, `price_range`, `release_year`, `user_id`) VALUES
(1, 'Acer Aspire 3', 6500000, 'Ryzen 5 5500U', 8, '512GB SSD', 'Integrated', 'Office', 'Low Mid', 2022, NULL),
(2, 'ASUS Vivobook 14', 7200000, 'i3 1215U', 8, '512GB SSD', 'Integrated', 'Office', 'Low Mid', 2023, NULL),
(3, 'Lenovo Ideapad Slim 3', 6000000, 'Ryzen 3 5300U', 8, '256GB SSD', 'Integrated', 'Office', 'Entry', 2021, NULL),
(4, 'HP 14s', 6800000, 'i3 1115G4', 8, '512GB SSD', 'Integrated', 'Office', 'Low Mid', 2022, NULL),
(5, 'Dell Inspiron 14', 7800000, 'i5 1135G7', 8, '512GB SSD', 'Integrated', 'Office', 'Low Mid', 2021, NULL),
(6, 'ASUS TUF FX505', 9500000, 'Ryzen 5 3550H', 16, '512GB SSD', 'GTX 1650', 'Gaming', 'Mid', 2020, NULL),
(7, 'Acer Nitro 5', 12000000, 'i5 11400H', 16, '512GB SSD', 'RTX 3050', 'Gaming', 'Upper Mid', 2022, NULL),
(8, 'Lenovo Gaming 3', 10500000, 'Ryzen 5 5600H', 16, '512GB SSD', 'GTX 1650', 'Gaming', 'Mid', 2021, NULL),
(9, 'HP Victus', 14000000, 'i5 12450H', 16, '512GB SSD', 'RTX 3050', 'Gaming', 'Upper Mid', 2023, NULL),
(10, 'MSI GF63', 11000000, 'i5 10500H', 16, '512GB SSD', 'GTX 1650', 'Gaming', 'Mid', 2021, NULL),
(11, 'ASUS Vivobook Pro', 13000000, 'Ryzen 7 5800H', 16, '1TB SSD', 'RTX 3050', 'Editing', 'Upper Mid', 2022, NULL),
(12, 'Acer Swift X', 14500000, 'Ryzen 7 5800U', 16, '1TB SSD', 'RTX 3050', 'Editing', 'Upper Mid', 2022, NULL),
(13, 'Macbook Air M1', 13500000, 'M1', 8, '256GB SSD', 'Integrated', 'Editing', 'Upper Mid', 2020, NULL),
(14, 'Lenovo Legion 5', 15500000, 'Ryzen 7 5800H', 16, '1TB SSD', 'RTX 3060', 'Editing', 'High End', 2021, NULL),
(15, 'HP Omen 16', 17000000, 'i7 12700H', 16, '1TB SSD', 'RTX 3060', 'Editing', 'High End', 2023, NULL),
(16, 'ASUS E410', 4500000, 'Celeron N4020', 4, '128GB SSD', 'Integrated', 'Belajar', 'Entry', 2020, NULL),
(17, 'Acer Chromebook', 4800000, 'Celeron N4500', 4, '128GB SSD', 'Integrated', 'Belajar', 'Entry', 2021, NULL),
(18, 'Lenovo Ideapad 1', 5000000, 'Athlon Silver', 4, '256GB SSD', 'Integrated', 'Belajar', 'Entry', 2021, NULL),
(19, 'HP Chromebook', 4700000, 'Celeron N3350', 4, '64GB SSD', 'Integrated', 'Belajar', 'Entry', 2019, NULL),
(20, 'ASUS BR1100', 5200000, 'Celeron N4500', 8, '256GB SSD', 'Integrated', 'Belajar', 'Entry', 2022, NULL),
(21, 'ASUS Zenbook 14', 11500000, 'Ryzen 7 5700U', 16, '1TB SSD', 'Integrated', 'Multitasking', 'Upper Mid', 2021, NULL),
(22, 'ThinkBook 14', 12500000, 'i5 1240P', 16, '512GB SSD', 'Integrated', 'Multitasking', 'Upper Mid', 2023, NULL),
(23, 'HP Pavilion Plus', 11000000, 'Ryzen 5 5600H', 16, '512GB SSD', 'Integrated', 'Multitasking', 'Mid', 2022, NULL),
(24, 'Dell Vostro 14', 9800000, 'i5 1135G7', 16, '512GB SSD', 'Integrated', 'Multitasking', 'Mid', 2021, NULL),
(25, 'Acer Swift Go', 13500000, 'i7 1355U', 16, '1TB SSD', 'Integrated', 'Multitasking', 'Upper Mid', 2024, NULL),
(26, 'axioo pongo 725', 9500000, 'i7-12650H', 16, 'SSD', '2050 ', 'Gaming', NULL, 2024, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `savings_entries`
--

CREATE TABLE `savings_entries` (
  `id` int(11) NOT NULL,
  `goal_id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `entry_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `savings_goals`
--

CREATE TABLE `savings_goals` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `laptop_id` int(11) NOT NULL,
  `target_months` int(11) NOT NULL,
  `status` enum('active','completed','cancelled') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `savings_goals`
--

INSERT INTO `savings_goals` (`id`, `user_id`, `laptop_id`, `target_months`, `status`, `created_at`) VALUES
(2, 1, 14, 12, 'active', '2026-01-06 05:48:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'okan', '$2y$10$8MRJ5wr9uHGp2XN.hOQIAe80hIl5jaV4PmkMWZ/nIQxhLRq8sCd8G', '2026-01-06 03:56:10');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `laptops`
--
ALTER TABLE `laptops`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pencarian` (`price`,`category`);

--
-- Indeks untuk tabel `savings_entries`
--
ALTER TABLE `savings_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goal_id` (`goal_id`);

--
-- Indeks untuk tabel `savings_goals`
--
ALTER TABLE `savings_goals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `laptop_id` (`laptop_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `laptops`
--
ALTER TABLE `laptops`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT untuk tabel `savings_entries`
--
ALTER TABLE `savings_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `savings_goals`
--
ALTER TABLE `savings_goals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `savings_entries`
--
ALTER TABLE `savings_entries`
  ADD CONSTRAINT `savings_entries_ibfk_1` FOREIGN KEY (`goal_id`) REFERENCES `savings_goals` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `savings_goals`
--
ALTER TABLE `savings_goals`
  ADD CONSTRAINT `savings_goals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `savings_goals_ibfk_2` FOREIGN KEY (`laptop_id`) REFERENCES `laptops` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 24 Jun 2025 pada 06.28
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `xplorea`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `artist_commissions`
--

CREATE TABLE `artist_commissions` (
  `id` int(11) NOT NULL,
  `artist_id` int(11) NOT NULL,
  `order_item_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `percentage` decimal(5,2) NOT NULL COMMENT 'Persentase komisi untuk artist',
  `paid_status` tinyint(1) DEFAULT 0,
  `paid_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `artist_upgrade_requests`
--

CREATE TABLE `artist_upgrade_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `request_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Bisa berisi portfolio, identitas, dll' CHECK (json_valid(`request_data`)),
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `processed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `artist_upgrade_requests`
--

INSERT INTO `artist_upgrade_requests` (`id`, `user_id`, `request_data`, `status`, `admin_notes`, `requested_at`, `processed_at`) VALUES
(1, 16, '{\"fullname\":\"rizjnr\",\"art_categories\":\"Painting, Photography\",\"location\":\"indonesia\",\"phone\":\"628888016969\",\"artist_bio\":\"rizjnr\",\"artist_website\":\"alsdkfjas.com\"}', 'pending', NULL, '2025-06-17 03:15:11', NULL),
(2, 17, '{\"fullname\":\"jariski meidijuni\",\"art_categories\":\"Painting, Digital Art, Photography, Sculpture\",\"location\":\"indonesia\",\"phone\":\"628888016969\",\"artist_bio\":\"aku seorang kapiten\",\"artist_website\":\"alsdkfjas.com\"}', 'pending', NULL, '2025-06-17 12:28:33', NULL),
(3, 18, '{\"fullname\":\"publik\",\"art_categories\":\"Painting, Digital Art\",\"location\":\"indonesia\",\"phone\":\"628888016969\",\"artist_bio\":\"ini adalah akun publik\",\"artist_website\":\"alsdkfjas.com\"}', 'approved', 'alhamdulillah', '2025-06-17 12:36:27', '2025-06-17 12:36:59'),
(4, 19, '{\"fullname\":\"pawas hadi pawas\",\"art_categories\":\"Painting, Digital Art, Photography, Sculpture\",\"location\":\"indonesia\",\"phone\":\"628888016969\",\"artist_bio\":\"alsdkfj\",\"artist_website\":\"alsdkfjas.com\"}', 'rejected', 'minimal rank legend', '2025-06-17 23:11:09', '2025-06-17 23:11:59'),
(5, 21, '{\"fullname\":\"M. RIZKI JUNIARDI\",\"art_categories\":\"Photography\",\"location\":\"indonesia\",\"phone\":\"+628888016969\",\"artist_bio\":\"lasdkfjasldkfj\",\"artist_website\":\"alsdkfjas.com\"}', 'approved', 'hgvghb', '2025-06-22 00:59:17', '2025-06-22 01:01:22'),
(6, 26, '{\"fullname\":\"aan gagah\",\"art_categories\":\"Other\",\"location\":\"Pagutan\",\"phone\":\"kepo\",\"artist_bio\":\"hanya mahasiswa gabut \",\"artist_website\":\"\"}', 'rejected', 'konten ai dan tidak kreatif', '2025-06-22 06:30:04', '2025-06-22 06:31:48');

-- --------------------------------------------------------

--
-- Struktur dari tabel `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size_id` int(11) NOT NULL,
  `frame_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `max_attendees` int(11) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_by` int(11) DEFAULT NULL COMMENT 'Bisa admin atau artist',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `location`, `start_datetime`, `end_datetime`, `image_url`, `max_attendees`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Infornation', 'dies natalis informatika unram.', 'parkiran gedung D fakultas teknik universitas mataram', '2025-07-12 17:00:00', '2025-07-20 22:00:00', '1750385410_bb0c1ecd5e1e539681c0.jpg', 10000, 1, 10, '2025-06-19 18:10:10', '2025-06-21 05:45:59'),
(3, 'Staging Desire', 'Staging Desire (Panggung Kehendak) merupakan pameran kolaborasi antara seniman Nindityo Adipurnomo dan Imam Cahyo yang digelar di Galeri Salihara hingga 27 Juli 2025. Dalam seteleng ini, keduanya mengeksplorasi tema identitas dan tradisi dengan cara yang unik laiknya pertunjukan.', 'Jakarta', '2025-06-25 12:00:00', '2025-07-27 22:00:00', '1750512610_ead164135f9792c16f31.png', 0, 1, 12, '2025-06-21 05:30:10', '2025-06-21 13:32:55'),
(4, 'Jakarta Provoke', 'Event ini menghadirkan kolaborasi 19 artis seniman dengan 19 kurator kontemporer dalam satu panggung kreatif yang sama. Dilansir dari laman resmi eventfestid, lebih dari 19 karya seni dari berbagai medium seperti lukisan, fotografi, dan instalasi akan dipamerkan.', 'Jakarta', '2025-07-03 08:30:00', '2025-07-03 22:00:00', '1750513032_9b5a3741d0c1722a1818.jpg', 0, 1, 10, '2025-06-21 05:37:12', '2025-06-21 05:37:12'),
(6, 'NWSBa', 'asldfkja sdfklja sdlfka jsdfl k', 'ARBUD', '2025-06-23 17:57:00', '2025-06-24 17:57:00', '1750586284_0aeed8ec23c6e6f3dfc7.jpg', 1200, 0, 12, '2025-06-22 01:58:04', '2025-06-22 01:59:25'),
(8, 'empty', 'lasdfjkasldf asdfl kasd', 'dimana saja', '2025-06-23 11:10:00', '2025-06-24 11:10:00', '1750648222_5134e5961fbf62b1b415.jpg', 10000, 0, 25, '2025-06-22 19:10:22', '2025-06-22 19:10:25'),
(9, 'empty', 'asdfkjasdklf jasldkf jasl', 'dimana saja', '2025-06-27 21:46:00', '2025-06-30 21:46:00', '1750686399_fcdb3efb07def7670b03.jpg', 10000, 0, 10, '2025-06-23 05:46:39', '2025-06-23 06:52:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `event_attendees`
--

CREATE TABLE `event_attendees` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `payment_status` enum('pending','paid','cancelled') DEFAULT 'pending',
  `attended` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `event_attendees`
--

INSERT INTO `event_attendees` (`id`, `event_id`, `user_id`, `ticket_id`, `quantity`, `payment_status`, `attended`, `created_at`, `updated_at`) VALUES
(1, 1, 12, 1, 1, 'pending', 0, '2025-06-19 18:50:52', '2025-06-19 18:50:52'),
(2, 3, 21, 4, 10, 'pending', 0, '2025-06-22 00:55:15', '2025-06-22 00:55:15'),
(3, 4, 21, 5, 1, 'pending', 0, '2025-06-22 01:38:55', '2025-06-22 01:38:55'),
(4, 1, 10, 1, 1, 'pending', 0, '2025-06-22 01:39:24', '2025-06-22 01:39:24'),
(5, 3, 26, 4, 6, 'pending', 0, '2025-06-22 06:23:57', '2025-06-22 06:23:57'),
(6, 3, 10, 4, 5, 'pending', 0, '2025-06-23 05:55:03', '2025-06-23 05:55:03'),
(7, 9, 10, 10, 1, 'pending', 0, '2025-06-23 06:51:27', '2025-06-23 06:51:27'),
(8, 4, 19, 5, 1, 'pending', 0, '2025-06-23 07:08:41', '2025-06-23 07:08:41'),
(9, 4, 27, 5, 5, 'pending', 0, '2025-06-23 20:09:51', '2025-06-23 20:09:51');

-- --------------------------------------------------------

--
-- Struktur dari tabel `event_tickets`
--

CREATE TABLE `event_tickets` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `ticket_type` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity_available` int(11) NOT NULL,
  `sale_start` datetime DEFAULT NULL,
  `sale_end` datetime DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `event_tickets`
--

INSERT INTO `event_tickets` (`id`, `event_id`, `ticket_type`, `price`, `quantity_available`, `sale_start`, `sale_end`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'early bird', 50000.00, 3, '2025-06-12 17:00:00', '2025-06-24 22:00:00', 1, '2025-06-19 18:10:10', '2025-06-22 01:39:24'),
(2, 1, 'reguler', 75000.00, 100, '2025-07-01 17:00:00', '2025-07-15 22:00:00', 1, '2025-06-19 18:10:10', '2025-06-20 02:16:40'),
(4, 3, 'reguler', 500000.00, 79, '2025-05-25 12:00:00', '2025-07-01 12:00:00', 1, '2025-06-21 05:30:10', '2025-06-23 05:55:03'),
(5, 4, 'reguler', 600000.00, 993, '2025-06-15 12:00:00', '2025-06-30 12:00:00', 1, '2025-06-21 05:37:12', '2025-06-23 20:09:51'),
(7, 6, 'reguler', 20.00, 1200, '2025-06-20 17:57:00', '2025-06-23 17:57:00', 1, '2025-06-22 01:58:04', '2025-06-22 01:58:04'),
(9, 8, 'reguler', 10000.00, 100, '2025-06-20 11:10:00', '2025-06-23 11:10:00', 1, '2025-06-22 19:10:22', '2025-06-22 19:10:22'),
(10, 9, 'reguler', 10000.00, 99, '2025-06-16 21:46:00', '2025-06-24 21:46:00', 1, '2025-06-23 05:46:39', '2025-06-23 06:51:27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `forum_categories`
--

CREATE TABLE `forum_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `forum_categories`
--

INSERT INTO `forum_categories` (`id`, `name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Technology in Art', 'this category is for content that has relation with photography.', 1, '2025-06-19 05:51:26', '2025-06-19 07:25:57'),
(2, 'Art Marketing & Distribution', 'this category is for content that has relation with painting.', 1, '2025-06-19 05:51:26', '2025-06-19 07:26:12');

-- --------------------------------------------------------

--
-- Struktur dari tabel `forum_likes`
--

CREATE TABLE `forum_likes` (
  `id` int(11) NOT NULL,
  `reply_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `forum_replies`
--

CREATE TABLE `forum_replies` (
  `id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `is_best_answer` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `forum_replies`
--

INSERT INTO `forum_replies` (`id`, `topic_id`, `user_id`, `content`, `is_best_answer`, `created_at`, `updated_at`) VALUES
(3, 27, 10, 'astaga', 0, '2025-06-19 00:10:29', '2025-06-19 00:10:29'),
(4, 27, 10, 'lorem ipsum dolor sit amet?', 0, '2025-06-19 00:16:34', '2025-06-19 00:16:34'),
(5, 32, 13, 'apa artinya', 0, '2025-06-19 17:40:39', '2025-06-19 17:40:39'),
(6, 30, 13, 'kenapa perlu di manage?', 0, '2025-06-19 17:45:23', '2025-06-19 17:45:23'),
(7, 31, 13, 'agree', 0, '2025-06-19 17:45:53', '2025-06-19 17:45:53'),
(8, 30, 13, 'topik yang sangat underrated', 0, '2025-06-19 17:48:15', '2025-06-19 17:48:15'),
(9, 29, 13, 'saya sangat setuju dengan topik ini', 0, '2025-06-19 17:48:33', '2025-06-19 17:48:33'),
(10, 36, 21, 'hairfcvhjtyf', 0, '2025-06-22 00:57:40', '2025-06-22 00:57:40'),
(11, 36, 26, 'sok ye mu ris', 0, '2025-06-22 06:26:22', '2025-06-22 06:26:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `forum_topics`
--

CREATE TABLE `forum_topics` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `is_sticky` tinyint(1) DEFAULT 0,
  `is_closed` tinyint(1) DEFAULT 0,
  `view_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `forum_topics`
--

INSERT INTO `forum_topics` (`id`, `category_id`, `user_id`, `title`, `content`, `is_sticky`, `is_closed`, `view_count`, `created_at`, `updated_at`) VALUES
(27, 1, 13, 'Using AI for Digital Art Curation', 'How can AI be used to curate digital artworks? Are there any platforms or tools already doing this effectively?', 0, 0, 23, '2025-06-19 07:57:48', '2025-06-22 01:21:39'),
(28, 1, 14, 'Blockchain for Art Authentication', 'Can blockchain technology be reliably used to certify the authenticity of digital artworks? Any success stories?', 0, 0, 3, '2025-06-19 07:57:48', '2025-06-21 19:02:15'),
(29, 1, 16, 'Augmented Reality in Virtual Art Exhibitions', 'What AR tools or frameworks are suitable for creating immersive virtual art galleries?', 0, 0, 2, '2025-06-19 07:57:48', '2025-06-19 17:48:34'),
(30, 1, 17, 'Managing Metadata for Digital Art', 'What are the best practices for organizing metadata in digital art information systems?', 0, 0, 5, '2025-06-19 07:57:48', '2025-06-19 17:48:15'),
(31, 1, 18, 'Building a Recommendation System for Art Lovers', 'How feasible is it to implement a Netflix-style recommendation system for visual art platforms?', 0, 0, 2, '2025-06-19 07:57:48', '2025-06-19 17:45:53'),
(32, 2, 10, 'Soft Launch Strategy for an Art Marketplace', 'What should we prepare before doing a soft launch for an online art platform? Any tips or checklist?', 0, 0, 2, '2025-06-19 07:57:48', '2025-06-19 17:40:40'),
(33, 2, 13, 'Optimizing SEO for Digital Art Platforms', 'What are the most effective SEO strategies for increasing traffic to online art galleries?', 0, 0, 0, '2025-06-19 07:57:48', '2025-06-19 07:57:48'),
(34, 2, 14, 'Managing Social Media as an Emerging Artist', 'How can new artists use Instagram or TikTok more effectively to grow their audience and sales?', 0, 0, 0, '2025-06-19 07:57:48', '2025-06-19 07:57:48'),
(35, 2, 16, 'Integrating Payment Gateways into Art Platforms', 'Which payment gateways are recommended for artists in Southeast Asia? Any integration advice?', 0, 0, 0, '2025-06-19 07:57:48', '2025-06-19 07:57:48'),
(36, 2, 17, 'Tracking Art Sales Analytics in Real-Time', 'What tools or dashboards can we use to monitor digital artwork performance across marketplaces?', 0, 0, 4, '2025-06-19 07:57:48', '2025-06-22 06:26:22'),
(37, 1, 10, 'pengaruh MCGG dalam desain interface', 'apakah mcgg memiliki pengaruh yang signifikan terhadap desain interface?', 0, 0, 0, '2025-06-22 07:04:25', '2025-06-22 07:04:25');

-- --------------------------------------------------------

--
-- Struktur dari tabel `master_frames`
--

CREATE TABLE `master_frames` (
  `id` int(11) NOT NULL,
  `frame_name` varchar(100) NOT NULL,
  `price_adjustment` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `master_frames`
--

INSERT INTO `master_frames` (`id`, `frame_name`, `price_adjustment`) VALUES
(1, 'Print only', 0.00),
(2, 'Oak', 35000.00),
(3, 'White', 50000.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `master_sizes`
--

CREATE TABLE `master_sizes` (
  `id` int(11) NOT NULL,
  `size_name` varchar(100) NOT NULL,
  `size_description` varchar(255) DEFAULT NULL,
  `price_adjustment` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `master_sizes`
--

INSERT INTO `master_sizes` (`id`, `size_name`, `size_description`, `price_adjustment`) VALUES
(1, 'small', '21cm x 30cm, 8.3inches x 11,7 inches', 10000.00),
(2, 'medium', '42cm x 59cm, 16.5inches x 23.4inches', 15000.00),
(3, 'large', '59cm x 84cm, 23.4inches x 33.1inches', 20000.00),
(4, 'extra large', '70cm x 100cm, 27.6inches x 39.4inches', 25000.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','paid','shipped','delivered','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `session_id`, `total_amount`, `status`, `created_at`) VALUES
(1, NULL, '5671b8312aa0c9b4b7fc94c039013fbc', 5000.00, 'pending', '2025-06-13 02:50:54'),
(2, NULL, 'a0e23fa56ab248e712170c101c00bb76', 100000.00, 'pending', '2025-06-13 18:47:54'),
(3, 12, '868b4d085091db9581d38f336f96835a', 4000.00, 'pending', '2025-06-13 18:53:14'),
(4, 10, '70499bc2d43a10b351dcf4a4a9d37a3c', 20000.00, 'pending', '2025-06-17 23:09:21'),
(5, 12, '19183364d11434c4804f290ba57bf288', 25000.00, 'pending', '2025-06-17 23:31:21'),
(6, 10, 'e52e3174e3cee89ef61f87cce61dcc06', 300000.00, 'pending', '2025-06-19 00:18:18'),
(7, 10, '19f1a8d8b3120a91844ef3f3eb6f8132', 999999.00, 'pending', '2025-06-21 18:43:02'),
(8, 16, '2febc465f842509ca4e713897210babd', 3700000.00, 'pending', '2025-06-21 18:46:12'),
(9, 16, '2febc465f842509ca4e713897210babd', 899999.00, 'pending', '2025-06-21 18:46:45'),
(10, 21, '04fa08291c0494e58c7a00fa1687d0da', 899999.00, 'pending', '2025-06-22 01:18:54'),
(11, 26, '049827edbc3e4e9770a6c0b674416a44', 2000000.00, 'pending', '2025-06-22 06:22:47'),
(12, 19, '03333ec57ac2bc379d8fb119432524bb', 899999.00, 'pending', '2025-06-23 06:57:41'),
(13, 19, 'c96720657359a9b0dc6b6aa8e09075cb', 899999.00, 'pending', '2025-06-23 07:08:55'),
(14, 27, '4910ca0569939b3c5ded24842176d729', 1800000.00, 'pending', '2025-06-23 20:05:40'),
(15, 27, '852b4f4a409c6accf4f2272853240e66', 800000.00, 'pending', '2025-06-23 20:08:02');

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size_id` int(11) NOT NULL,
  `frame_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `status` enum('pending','accepted','rejected') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `size_id`, `frame_id`, `quantity`, `unit_price`, `status`) VALUES
(1, 2, 18, 36, 21, 1, 100000.00, 'pending'),
(2, 3, 22, 46, 29, 4, 1000.00, 'pending'),
(3, 4, 22, 46, 29, 1, 1000.00, 'pending'),
(4, 4, 17, 35, 20, 1, 5000.00, 'pending'),
(5, 4, 17, 35, 20, 1, 5000.00, 'pending'),
(6, 5, 17, 34, 20, 1, 5000.00, 'pending'),
(7, 5, 17, 34, 20, 1, 5000.00, 'rejected'),
(8, 6, 18, 36, 21, 3, 100000.00, 'rejected'),
(9, 7, 33, 84, 51, 1, 999999.00, 'pending'),
(10, 8, 20, 44, 27, 2, 1350000.00, 'rejected'),
(11, 8, 20, 44, 27, 2, 1350000.00, 'accepted'),
(12, 9, 32, 80, 50, 1, 899999.00, 'pending'),
(13, 10, 34, 85, 52, 1, 899999.00, 'pending'),
(14, 11, 30, 72, 48, 2, 1000000.00, 'pending'),
(15, 12, 34, 85, 52, 1, 899999.00, 'accepted'),
(16, 13, 34, 85, 52, 1, 899999.00, 'pending'),
(17, 14, 24, 55, 33, 1, 1800000.00, 'pending'),
(18, 15, 25, 56, 36, 1, 800000.00, 'pending');

-- --------------------------------------------------------

--
-- Struktur dari tabel `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `status` enum('pending','completed','failed','refunded') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `artist_id` int(11) DEFAULT NULL COMMENT 'NULL untuk produk platform, NOT NULL untuk produk artist',
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `base_price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `is_approved` tinyint(1) DEFAULT 0 COMMENT 'Untuk produk artist perlu approval',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `artist_id`, `title`, `description`, `base_price`, `image_url`, `is_approved`, `created_at`, `updated_at`) VALUES
(17, 10, 'burn', 'feel the burn', 1000000.00, '1750506908_8ffeb1b05ee04865f74e.jpg', 1, '2025-06-11 04:07:25', '2025-06-21 03:57:05'),
(18, 10, 'starring night', 'stare to your soul', 1200000.00, '1750506977_c1b5c3884a17894845bc.jpg', 1, '2025-06-11 04:08:15', '2025-06-21 03:56:17'),
(20, 10, 'Uncanny falley', '-', 1350000.00, '1750507010_47992f5e6f6fb5d41c91.jpg', 1, '2025-06-11 05:31:01', '2025-06-21 03:56:50'),
(22, 13, 'teh gelas', 'asldkfjaslkdfj', 1000.00, '1749697106_6b4e19ca2055fa9ad01b.jpg', 2, '2025-06-11 18:58:26', '2025-06-23 06:07:21'),
(23, 13, 'Girl with a pearl earring', 'Abstract geometric acrylic painting on canvas. Inspired by Johannes Vermeer\'s \'Girl with a Pearl Earring\'', 2000000.00, '1750507983_40a311e6dbf426e36fdb.jpg', 1, '2025-06-21 04:13:03', '2025-06-21 04:13:03'),
(24, 13, 'Mona Liza', 'mona lisa abstract style', 1800000.00, '1750508039_d4ebc6c6d0952c1f2671.jpg', 1, '2025-06-21 04:13:59', '2025-06-21 04:13:59'),
(25, 14, 'Minecraft creeper paintings', 'creebet 2x1', 800000.00, '1750508564_4bfa860e6123bd3d7a36.jpg', 1, '2025-06-21 04:22:44', '2025-06-21 04:22:44'),
(26, 14, 'Skull on Fire', 'minecraft skull on fire painting', 899000.00, '1750508614_32089503dcd28b85d7bf.jpg', 1, '2025-06-21 04:23:34', '2025-06-21 04:23:34'),
(27, 12, 'Baroque', 'In Minecraft, the painting depicting a cake, sunflower, and pottery is called \"Baroque\". The painting depicting a vase, or \"pottery\", is part of the \"Decorated Pot\" item.', 955000.00, '1750508775_a979c7c93fad7063b6e0.jpg', 1, '2025-06-21 04:26:15', '2025-06-23 06:07:07'),
(28, 14, 'Baroque', 'In Minecraft, the painting depicting a cake, sunflower, and pottery is called \"Baroque\". The painting depicting a vase, or \"pottery\", is part of the \"Decorated Pot\" item.', 955000.00, '1750508884_0b5becfe3c22bf577ca7.jpg', 1, '2025-06-21 04:28:04', '2025-06-21 04:31:10'),
(30, 17, 'Capturing urban landscapes in oil', 'artist stephen magsig\'s city lights paintings, which depict detroit\'s streetscapes at night', 1000000.00, '1750510003_82a39044ac52c6d56550.jpg', 1, '2025-06-21 04:46:43', '2025-06-21 05:00:02'),
(31, 17, '-', '-', 800000.00, '1750510067_3443614884ea61098b69.jpg', 1, '2025-06-21 04:47:47', '2025-06-21 04:59:59'),
(32, 18, 'cReEpy.', '-', 899999.00, '1750510697_76af7a0e4564691b5185.jpg', 1, '2025-06-21 04:58:17', '2025-06-21 04:59:57'),
(33, 18, '[Subject-001]', '-', 999999.00, '1750510724_bd1434342996e1b0b85b.jpg', 1, '2025-06-21 04:58:44', '2025-06-21 04:59:54'),
(34, 18, 'Capek.', '-', 899999.00, '1750510748_904537ae54d4349e2552.jpg', 1, '2025-06-21 04:59:08', '2025-06-21 04:59:52'),
(35, 18, 'smth', '-', 899999.00, '1750510769_481ab1edda40244a4a10.jpg', 1, '2025-06-21 04:59:29', '2025-06-21 04:59:50'),
(36, 21, 'NOMNOM', 'JFXGFCHV', 50000.00, '1750583353_327a0c2094d448ac3172.jpg', 2, '2025-06-22 01:09:13', '2025-06-23 20:02:31');

-- --------------------------------------------------------

--
-- Struktur dari tabel `product_frames`
--

CREATE TABLE `product_frames` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `master_frame_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `product_frames`
--

INSERT INTO `product_frames` (`id`, `product_id`, `master_frame_id`) VALUES
(20, 17, 1),
(21, 18, 1),
(22, 18, 2),
(26, 20, 1),
(27, 20, 2),
(29, 22, 1),
(30, 23, 1),
(31, 23, 2),
(32, 23, 3),
(33, 24, 1),
(34, 24, 2),
(35, 24, 3),
(36, 25, 1),
(37, 25, 2),
(38, 26, 1),
(39, 26, 2),
(40, 27, 1),
(41, 27, 2),
(42, 28, 1),
(43, 28, 2),
(47, 30, 1),
(48, 30, 2),
(49, 31, 1),
(50, 32, 1),
(51, 33, 1),
(52, 34, 1),
(53, 35, 1),
(54, 36, 1),
(55, 36, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `product_sizes`
--

CREATE TABLE `product_sizes` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `master_size_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `product_sizes`
--

INSERT INTO `product_sizes` (`id`, `product_id`, `master_size_id`) VALUES
(34, 17, 2),
(35, 17, 3),
(36, 18, 1),
(37, 18, 3),
(42, 20, 2),
(43, 20, 3),
(44, 20, 4),
(46, 22, 1),
(47, 22, 2),
(48, 23, 1),
(49, 23, 2),
(50, 23, 3),
(51, 23, 4),
(52, 24, 1),
(53, 24, 2),
(54, 24, 3),
(55, 24, 4),
(56, 25, 2),
(57, 25, 3),
(58, 26, 1),
(59, 26, 2),
(60, 26, 3),
(61, 27, 1),
(62, 27, 2),
(63, 27, 3),
(64, 27, 4),
(65, 28, 1),
(66, 28, 2),
(67, 28, 3),
(68, 28, 4),
(72, 30, 1),
(73, 30, 2),
(74, 30, 3),
(75, 30, 4),
(76, 31, 2),
(77, 31, 3),
(78, 32, 1),
(79, 32, 2),
(80, 32, 3),
(81, 32, 4),
(82, 33, 1),
(83, 33, 2),
(84, 33, 3),
(85, 34, 1),
(86, 34, 2),
(87, 34, 3),
(88, 35, 1),
(89, 35, 2),
(90, 35, 3),
(91, 35, 4),
(92, 36, 1),
(93, 36, 2),
(94, 36, 3),
(95, 36, 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tutorials`
--

CREATE TABLE `tutorials` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `artist_id` int(11) NOT NULL COMMENT 'Hanya artist yang bisa membuat tutorial',
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `video_url` varchar(255) NOT NULL,
  `thumbnail_url` varchar(255) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL COMMENT 'Durasi dalam detik',
  `difficulty_level` enum('beginner','intermediate','advanced') DEFAULT NULL,
  `is_free` tinyint(1) DEFAULT 0,
  `price` decimal(10,2) DEFAULT 0.00,
  `view_count` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tutorial_categories`
--

CREATE TABLE `tutorial_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tutorial_comments`
--

CREATE TABLE `tutorial_comments` (
  `id` int(11) NOT NULL,
  `tutorial_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tutorial_ratings`
--

CREATE TABLE `tutorial_ratings` (
  `id` int(11) NOT NULL,
  `tutorial_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL COMMENT '1-5',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `profile_url` varchar(500) DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'untuk membedakan user admin',
  `is_artist` tinyint(1) DEFAULT 0,
  `is_active` int(11) NOT NULL DEFAULT 1,
  `artist_profile_approved` tinyint(1) DEFAULT 0,
  `fullname` varchar(255) NOT NULL,
  `art_categories` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `phone` int(100) NOT NULL,
  `artist_bio` text DEFAULT NULL,
  `artist_website` varchar(255) DEFAULT NULL,
  `stripe_connect_id` varchar(255) DEFAULT NULL COMMENT 'Untuk pembayaran ke artist',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `profile_url`, `is_admin`, `is_artist`, `is_active`, `artist_profile_approved`, `fullname`, `art_categories`, `location`, `phone`, `artist_bio`, `artist_website`, `stripe_connect_id`, `created_at`, `updated_at`) VALUES
(10, 'ardih', 'm.rizkijuniardi24@gmail.com', '$2y$10$2cXI1WwJlvOsPHnHk9I4veZiptUS3B6dQP7YUSKIB3j4/ruSCZ1ES', 'uploads/profiles/1750577282_017f9adcd2807ccebda0.jpg', 0, 1, 1, 1, 'M. Rizki Juniardi', 'Painting', 'indonesia', 0, 'just a regular person with big ego.', 'alsdkfjas.com', NULL, '2025-06-10 19:22:29', '2025-06-22 06:34:10'),
(12, 'aancuek', 'tongkrongaan@gmail.com', '$2y$10$ngbjXYlvCkLg4pKMS37/uuwqAU7R5fD7JJMRkUzD8BVgWxfKoYAhO', 'uploads/profiles/1750603201_f34d5ab101f80ec36cf6.jpg', 1, 0, 1, 0, 'aaaaaaan', '', 'pagutan street', 0, 'orang gagah', NULL, NULL, '2025-06-11 03:50:07', '2025-06-22 14:40:01'),
(13, 'Abstract art', 'contoh@contoh.com', '$2y$10$rY18sYhyEg.D3HVk9Y3p1.r7m.jjKfQmKUKdvTV6PdirSScFJL3.W', 'uploads/profiles/1750507867_a0563d572677fd6a7f58.png', 0, 1, 1, 1, 'contoh contoh', 'Painting, Digital Art, Photography', 'indonesia', 2147483647, 'i only posting abstract and modern type art', 'alsdkfjas.com', NULL, '2025-06-12 02:35:08', '2025-06-21 12:16:43'),
(14, 'kkk', 'asdlfk@asdf.com', '$2y$10$KIB6SxsKa.z8jU0efZHIquyRMTgmcj/E26od6Ih32widCA0X9CO0m', 'uploads/profiles/1750509380_1c3eb4d88b67285d4ecd.jpg', 0, 1, 1, 1, 'Minecraft player', 'Photography, Sculpture', 'indonesia', 2147483647, 'I only post artworks related to minecraft only', 'alsdkfjas.com', NULL, '2025-06-13 19:24:00', '2025-06-21 12:36:20'),
(16, 'gmail', 'ardi@ardi.com', '$2y$10$R3Lx1s5CFpkIr.RDti7.meGH08i2CNKn4tKSaQUK3Q7WncyU5/wVS', NULL, 0, 1, 1, 1, 'rizjnr', 'Painting, Photography', 'indonesia', 2147483647, 'rizjnr', 'alsdkfjas.com', NULL, '2025-06-17 03:14:19', '2025-06-17 12:25:41'),
(17, 'jaris', 'jaris@gmail.com', '$2y$10$jWzSYb/7.meeuyHcLBv2dOiabQoa8K.f3qslDyrcD.QugqiX7R5tS', 'uploads/profiles/1750509829_3f67e4145209c60f4676.jpg', 0, 1, 1, 1, 'jariski meidijuni', 'Painting, Digital Art, Photography, Sculpture', 'indonesia', 2147483647, 'aku seorang kapiten', 'alsdkfjas.com', NULL, '2025-06-17 12:27:58', '2025-06-21 12:43:49'),
(18, 'Aska', 'asksa@gmail.com', '$2y$10$TBwIL/gd8pOKh9SbPhKoEuiZyJv2yT265fSwqVAhq1di9Y1oZ0py2', 'uploads/profiles/1750510444_87fbade751bd22e78cd0.jpg', 0, 1, 1, 1, 'Aqsha nayaka', 'Painting, Digital Art', 'indonesia', 2147483647, 'Aska is the username my best-friend gave me', 'alsdkfjas.com', NULL, '2025-06-17 12:36:00', '2025-06-21 12:54:04'),
(19, 'pawas', 'pawas@pawas.com', '$2y$10$ZIn/RcC9Lb3qum6rO/44K.xO6XnvMeM6aua9gi9Awykm51WdcwJNC', 'uploads/profiles/1750502767_4b80ccb9af1a4dea27da.png', 0, 0, 1, 0, 'pawas hadi pawas', 'Painting, Digital Art, Photography, Sculpture', 'indonesia', 2147483647, 'alsdkfj', 'alsdkfjas.com', NULL, '2025-06-17 23:10:41', '2025-06-21 10:46:07'),
(20, 'xclusivearrr', 'f1d02310074@student.unram.ac.id', '$2y$10$m8Dvr7E0cXGs9NDPNACx2OGQdQscXy2ssgjFluhOovgU6jXJgKZHi', 'uploads/profiles/1750576582_9e5243ce1199165bffb6.jpg', 0, 0, 1, 0, 'asdfghjkl', '', 'Indonesia', 0, '', NULL, NULL, '2025-06-21 23:12:26', '2025-06-22 07:16:22'),
(21, 'lala', 'eothokke@gmail.com', '$2y$10$aCuipSGUtVxag/5xMB.nzuXhjF2rPMd5F3DykKYzNS4s91hzTZjQW', 'uploads/profiles/1750583710_470d2c08dff183016824.jpg', 0, 0, 0, 0, 'M. RIZKI JUNIARDI', 'Photography', 'indonesia', 2147483647, 'lasdkfjasldkfj', 'alsdkfjas.com', NULL, '2025-06-22 00:52:58', '2025-06-22 02:54:42'),
(22, 'rara', 'nyamnyam@gmail.com', '$2y$10$4K/OKdS9ROtGytuz30scPuhGMqKzaMVJNGyIkKcrhesFAskFlfWCC', NULL, 0, 0, 1, 0, '', '', '', 0, NULL, NULL, NULL, '2025-06-22 01:22:55', '2025-06-22 09:22:55'),
(25, 'admin', 'admin@admin.com', '$2y$10$xlKoPzZ.2VRcYgVibK84/.9D5ZgPN2gifHnmC/ft.5dnUS8wysQvq', NULL, 1, 0, 1, 0, 'administrator', '', '', 2147483647, NULL, NULL, NULL, '2025-06-22 02:39:42', '2025-06-22 10:39:42'),
(26, 'aan gagah dari ardi', 'aangagah@gmail.com', '$2y$10$bp109TgrtGWdjcX0mKdyCOwL5nNxBaRG.UfGsyEWnhKpE.Y9Mv3GC', NULL, 0, 0, 1, 0, 'aan gagah', 'Other', 'Pagutan', 0, 'hanya mahasiswa gabut ', '', NULL, '2025-06-22 06:20:48', '2025-06-22 06:33:26'),
(27, 'pengguna', 'pengguna@pengguna.com', '$2y$10$7alXIgzoT6zwhhg1EtfzLureZl6j2SHzej0fi9RoDLUvoKdyYEJ8m', NULL, 0, 0, 1, 0, 'akun pengguna', '', 'indonesia', 0, 'ini akun pengguna', NULL, NULL, '2025-06-23 20:03:21', '2025-06-24 04:03:46');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_tutorial_access`
--

CREATE TABLE `user_tutorial_access` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tutorial_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL COMMENT 'NULL jika tutorial gratis',
  `access_granted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` timestamp NULL DEFAULT NULL COMMENT 'NULL untuk akses permanen'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `artist_commissions`
--
ALTER TABLE `artist_commissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `artist_id` (`artist_id`),
  ADD KEY `order_item_id` (`order_item_id`);

--
-- Indeks untuk tabel `artist_upgrade_requests`
--
ALTER TABLE `artist_upgrade_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `size_id` (`size_id`),
  ADD KEY `frame_id` (`frame_id`);

--
-- Indeks untuk tabel `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indeks untuk tabel `event_attendees`
--
ALTER TABLE `event_attendees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_attendee` (`event_id`,`user_id`,`ticket_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `ticket_id` (`ticket_id`);

--
-- Indeks untuk tabel `event_tickets`
--
ALTER TABLE `event_tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indeks untuk tabel `forum_categories`
--
ALTER TABLE `forum_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `forum_likes`
--
ALTER TABLE `forum_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_like` (`reply_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `forum_replies`
--
ALTER TABLE `forum_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `topic_id` (`topic_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `forum_topics`
--
ALTER TABLE `forum_topics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `master_frames`
--
ALTER TABLE `master_frames`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `master_sizes`
--
ALTER TABLE `master_sizes`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `size_id` (`size_id`),
  ADD KEY `frame_id` (`frame_id`);

--
-- Indeks untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `artist_id` (`artist_id`);

--
-- Indeks untuk tabel `product_frames`
--
ALTER TABLE `product_frames`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `master_frame_id` (`master_frame_id`);

--
-- Indeks untuk tabel `product_sizes`
--
ALTER TABLE `product_sizes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `master_size_id` (`master_size_id`);

--
-- Indeks untuk tabel `tutorials`
--
ALTER TABLE `tutorials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `artist_id` (`artist_id`);

--
-- Indeks untuk tabel `tutorial_categories`
--
ALTER TABLE `tutorial_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tutorial_comments`
--
ALTER TABLE `tutorial_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tutorial_id` (`tutorial_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `tutorial_ratings`
--
ALTER TABLE `tutorial_ratings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_rating` (`tutorial_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `user_tutorial_access`
--
ALTER TABLE `user_tutorial_access`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_access` (`user_id`,`tutorial_id`),
  ADD KEY `tutorial_id` (`tutorial_id`),
  ADD KEY `order_id` (`order_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `artist_commissions`
--
ALTER TABLE `artist_commissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `artist_upgrade_requests`
--
ALTER TABLE `artist_upgrade_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `event_attendees`
--
ALTER TABLE `event_attendees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `event_tickets`
--
ALTER TABLE `event_tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `forum_categories`
--
ALTER TABLE `forum_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `forum_likes`
--
ALTER TABLE `forum_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `forum_replies`
--
ALTER TABLE `forum_replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `forum_topics`
--
ALTER TABLE `forum_topics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT untuk tabel `master_frames`
--
ALTER TABLE `master_frames`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `master_sizes`
--
ALTER TABLE `master_sizes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT untuk tabel `product_frames`
--
ALTER TABLE `product_frames`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT untuk tabel `product_sizes`
--
ALTER TABLE `product_sizes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT untuk tabel `tutorials`
--
ALTER TABLE `tutorials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tutorial_categories`
--
ALTER TABLE `tutorial_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tutorial_comments`
--
ALTER TABLE `tutorial_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tutorial_ratings`
--
ALTER TABLE `tutorial_ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT untuk tabel `user_tutorial_access`
--
ALTER TABLE `user_tutorial_access`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `artist_commissions`
--
ALTER TABLE `artist_commissions`
  ADD CONSTRAINT `artist_commissions_ibfk_1` FOREIGN KEY (`artist_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `artist_commissions_ibfk_2` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`);

--
-- Ketidakleluasaan untuk tabel `artist_upgrade_requests`
--
ALTER TABLE `artist_upgrade_requests`
  ADD CONSTRAINT `artist_upgrade_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `cart_items_ibfk_3` FOREIGN KEY (`size_id`) REFERENCES `product_sizes` (`id`),
  ADD CONSTRAINT `cart_items_ibfk_4` FOREIGN KEY (`frame_id`) REFERENCES `product_frames` (`id`);

--
-- Ketidakleluasaan untuk tabel `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `event_attendees`
--
ALTER TABLE `event_attendees`
  ADD CONSTRAINT `event_attendees_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`),
  ADD CONSTRAINT `event_attendees_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `event_attendees_ibfk_3` FOREIGN KEY (`ticket_id`) REFERENCES `event_tickets` (`id`);

--
-- Ketidakleluasaan untuk tabel `event_tickets`
--
ALTER TABLE `event_tickets`
  ADD CONSTRAINT `event_tickets_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `forum_likes`
--
ALTER TABLE `forum_likes`
  ADD CONSTRAINT `forum_likes_ibfk_1` FOREIGN KEY (`reply_id`) REFERENCES `forum_replies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `forum_likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `forum_replies`
--
ALTER TABLE `forum_replies`
  ADD CONSTRAINT `forum_replies_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `forum_topics` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `forum_replies_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `forum_topics`
--
ALTER TABLE `forum_topics`
  ADD CONSTRAINT `forum_topics_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `forum_categories` (`id`),
  ADD CONSTRAINT `forum_topics_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `order_items_ibfk_3` FOREIGN KEY (`size_id`) REFERENCES `product_sizes` (`id`),
  ADD CONSTRAINT `order_items_ibfk_4` FOREIGN KEY (`frame_id`) REFERENCES `product_frames` (`id`);

--
-- Ketidakleluasaan untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Ketidakleluasaan untuk tabel `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`artist_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `product_frames`
--
ALTER TABLE `product_frames`
  ADD CONSTRAINT `master_frame_id` FOREIGN KEY (`master_frame_id`) REFERENCES `master_frames` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_frames_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `product_sizes`
--
ALTER TABLE `product_sizes`
  ADD CONSTRAINT `master_size_id` FOREIGN KEY (`master_size_id`) REFERENCES `master_sizes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_sizes_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tutorials`
--
ALTER TABLE `tutorials`
  ADD CONSTRAINT `tutorials_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `tutorial_categories` (`id`),
  ADD CONSTRAINT `tutorials_ibfk_2` FOREIGN KEY (`artist_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tutorial_comments`
--
ALTER TABLE `tutorial_comments`
  ADD CONSTRAINT `tutorial_comments_ibfk_1` FOREIGN KEY (`tutorial_id`) REFERENCES `tutorials` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tutorial_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tutorial_ratings`
--
ALTER TABLE `tutorial_ratings`
  ADD CONSTRAINT `tutorial_ratings_ibfk_1` FOREIGN KEY (`tutorial_id`) REFERENCES `tutorials` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tutorial_ratings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `user_tutorial_access`
--
ALTER TABLE `user_tutorial_access`
  ADD CONSTRAINT `user_tutorial_access_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_tutorial_access_ibfk_2` FOREIGN KEY (`tutorial_id`) REFERENCES `tutorials` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_tutorial_access_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

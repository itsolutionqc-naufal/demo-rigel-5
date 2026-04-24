-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Apr 21, 2026 at 01:37 AM
-- Server version: 5.7.39
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `demowebjalan_rigel`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','published') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `views` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `title`, `slug`, `excerpt`, `content`, `category`, `image`, `status`, `user_id`, `published_at`, `views`, `created_at`, `updated_at`) VALUES
(12, 'RIGEL COIN: Jadi Sub Seller Kini Lebih Mudah', 'rigel-coin-jadi-sub-seller-kini-lebih-mudah', 'Punya banyak kenalan spender memang menyenangkan, apalagi kalau kamu bisa dapat komisi dari setiap transaksi isi ulang di RIGEL COIN.\r\nSekarang kamu bisa mulai jadi Sub Seller dengan alur yang sederhana dan sistem yang sudah terintegrasi secara otomatis. Tidak perlu proses yang ribet — semua bisa kamu jalankan langsung dari website kami.\r\n\r\nDapatkan komisi sebesar 1% dari setiap transaksi yang berhasil, dan pantau semuanya secara real-time melalui website kami.', '<h2><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">Apa Itu Sub Seller di RIGEL COIN?</strong></h2><p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">Sub Seller adalah pengguna yang ikut menjual koin melalui sistem yang sudah kami sediakan.</span></p><p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">Setiap transaksi yang kamu lakukan tim kami akan memproses dan memperbarui statusnya di sistem secara transparan.</span></p><p><br></p><p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">Tidak perlu setup teknis, tidak perlu tools tambahan. Semua sudah tersedia dan siap digunakan.</span></p><p><br></p><h2><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">Cara Kerjanya</strong></h2><p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">Alurnya dibuat sederhana supaya kamu bisa langsung mulai:</span></p><ol><li><span style=\"background-color: transparent;\">1. Buka website </span><a href=\"https://agencyrigel.com\" rel=\"noopener noreferrer\" target=\"_blank\" style=\"background-color: transparent; color: rgb(17, 85, 204);\">https://agencyrigel.com</a><span style=\"background-color: transparent;\"> , kemudian login menggunakan akun personal yang telah kami siapkan khusus untukmu.</span></li><li><span style=\"background-color: transparent;\">2. Pilih menu COIN yang ada di bagian bawah halaman</span></li><li><span style=\"background-color: transparent;\">3, Pilih aplikasi yang ingin dituju</span></li><li><span style=\"background-color: transparent;\">4. Submit data yang diperlukan seperti : ID user yang beli koin, nickname, nominal top up dan upload bukti transfer jika sudah klik “kirim”</span></li><li><span style=\"background-color: transparent;\">5. Status transaksi diperbarui otomatis</span></li><li><span style=\"background-color: transparent;\">6. Komisi langsung tercatat di sistem</span></li></ol><p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">Semua proses bisa kamu pantau langsung tanpa harus cek manual atau bolak-balik tanya.</span></p><p><br></p><h2><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">Sistem yang Jelas dan Transparan</strong></h2><p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">Setiap transaksi yang kamu kirim akan tercatat di dashboard secara rapi.</span></p><p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">Kamu bisa melihat:</span></p><ul><li><span style=\"background-color: transparent;\">- status transaksi (diproses, berhasil, atau ditolak)</span></li><li><span style=\"background-color: transparent;\">- riwayat penjualan</span></li><li><span style=\"background-color: transparent;\">- jumlah komisi yang kamu dapatkan</span></li></ul><p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">Semua diperbarui secara real-time, jadi kamu selalu tahu perkembangan dari setiap transaksi.</span></p><p><br></p><h2><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">Kenapa Pakai Sistem Ini?</strong></h2><p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">Kami merancang sistem ini supaya kamu bisa fokus ke hasil, bukan ke proses yang ribet.</span></p><p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">Beberapa hal yang bisa kamu rasakan:</span></p><ul><li><span style=\"background-color: transparent;\">- Proses kirim transaksi cepat dan praktis</span></li><li><span style=\"background-color: transparent;\">- Status langsung diperbarui di sistem</span></li><li><span style=\"background-color: transparent;\">- Komisi dihitung otomatis tanpa perhitungan manual</span></li><li><span style=\"background-color: transparent;\">- Tidak perlu koordinasi berulang</span></li><li><span style=\"background-color: transparent;\">- Semua data tersimpan rapi dalam satu tempat</span></li></ul><p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">Dengan sistem ini, kamu bisa menjalankan penjualan dengan lebih terstruktur dan jelas.</span></p><p><br></p><p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">Sekarang semuanya sudah siap kamu jalankan.</span></p><p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">Manfaatkan sistem yang ada untuk mengelola transaksi dengan lebih rapi, pantau setiap perkembangan, dan maksimalkan potensi komisi dari setiap aktivitas yang kamu lakukan.</span></p>', 'Bisnis', 'uploads/articles/1774431087_Bercandalu0099 (3).png', 'published', 28, '2026-03-25 09:31:27', 23, '2026-03-25 09:31:27', '2026-04-11 11:26:20');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('agency-rigel-cache-2138ded1515f47a7e571fdb1169f9cdc', 'i:1;', 1776679481),
('agency-rigel-cache-2138ded1515f47a7e571fdb1169f9cdc:timer', 'i:1776679481;', 1776679481),
('agency-rigel-cache-2f30a166a896a365923266855edbf47d', 'i:1;', 1776679588),
('agency-rigel-cache-2f30a166a896a365923266855edbf47d:timer', 'i:1776679588;', 1776679588),
('agency-rigel-cache-7ec29f79f3367200306112e73ea003b0', 'i:1;', 1776679528),
('agency-rigel-cache-7ec29f79f3367200306112e73ea003b0:timer', 'i:1776679528;', 1776679528),
('agency-rigel-cache-a0585f7e2a38d896dc583c030f4a7f6c', 'i:1;', 1776680096),
('agency-rigel-cache-a0585f7e2a38d896dc583c030f4a7f6c:timer', 'i:1776680096;', 1776680096),
('agency-rigel-cache-naufal@gmail.com|2404:c0:ba03:1a0:88f1:cc22:6725:369', 'i:1;', 1776679482),
('agency-rigel-cache-naufal@gmail.com|2404:c0:ba03:1a0:88f1:cc22:6725:369:timer', 'i:1776679482;', 1776679482),
('agency-rigel-cache-payment_methods.active', 'O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:16:{i:0;O:24:\"App\\Models\\PaymentMethod\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:15:\"payment_methods\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:12:{s:2:\"id\";i:7;s:4:\"name\";s:17:\"Bank Central Asia\";s:4:\"type\";s:12:\"bank_account\";s:14:\"account_number\";s:10:\"8832209936\";s:14:\"account_holder\";s:13:\"Hardi Cahyadi\";s:12:\"qr_code_path\";N;s:4:\"nmid\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-03-25 10:26:06\";s:10:\"updated_at\";s:19:\"2026-03-25 10:26:06\";s:10:\"service_id\";i:9;s:7:\"user_id\";N;}s:11:\"\0*\0original\";a:12:{s:2:\"id\";i:7;s:4:\"name\";s:17:\"Bank Central Asia\";s:4:\"type\";s:12:\"bank_account\";s:14:\"account_number\";s:10:\"8832209936\";s:14:\"account_holder\";s:13:\"Hardi Cahyadi\";s:12:\"qr_code_path\";N;s:4:\"nmid\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-03-25 10:26:06\";s:10:\"updated_at\";s:19:\"2026-03-25 10:26:06\";s:10:\"service_id\";i:9;s:7:\"user_id\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"service_id\";i:1;s:7:\"user_id\";i:2;s:4:\"name\";i:3;s:4:\"type\";i:4;s:14:\"account_number\";i:5;s:14:\"account_holder\";i:6;s:12:\"qr_code_path\";i:7;s:4:\"nmid\";i:8;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:1;O:24:\"App\\Models\\PaymentMethod\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:15:\"payment_methods\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:12:{s:2:\"id\";i:8;s:4:\"name\";s:21:\"RIGELCOIN TOKO MAINAN\";s:4:\"type\";s:4:\"qris\";s:14:\"account_number\";N;s:14:\"account_holder\";N;s:12:\"qr_code_path\";s:73:\"uploads/images/qris/1774409226_WhatsApp Image 2026-03-25 at 10.22.50.jpeg\";s:4:\"nmid\";s:15:\"ID1025457669691\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-03-25 10:27:06\";s:10:\"updated_at\";s:19:\"2026-03-25 10:27:06\";s:10:\"service_id\";i:9;s:7:\"user_id\";N;}s:11:\"\0*\0original\";a:12:{s:2:\"id\";i:8;s:4:\"name\";s:21:\"RIGELCOIN TOKO MAINAN\";s:4:\"type\";s:4:\"qris\";s:14:\"account_number\";N;s:14:\"account_holder\";N;s:12:\"qr_code_path\";s:73:\"uploads/images/qris/1774409226_WhatsApp Image 2026-03-25 at 10.22.50.jpeg\";s:4:\"nmid\";s:15:\"ID1025457669691\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-03-25 10:27:06\";s:10:\"updated_at\";s:19:\"2026-03-25 10:27:06\";s:10:\"service_id\";i:9;s:7:\"user_id\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"service_id\";i:1;s:7:\"user_id\";i:2;s:4:\"name\";i:3;s:4:\"type\";i:4;s:14:\"account_number\";i:5;s:14:\"account_holder\";i:6;s:12:\"qr_code_path\";i:7;s:4:\"nmid\";i:8;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:2;O:24:\"App\\Models\\PaymentMethod\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:15:\"payment_methods\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:12:{s:2:\"id\";i:9;s:4:\"name\";s:17:\"Bank Central Asia\";s:4:\"type\";s:12:\"bank_account\";s:14:\"account_number\";s:10:\"8831342528\";s:14:\"account_holder\";s:24:\"Nur Fitria Winda Lestari\";s:12:\"qr_code_path\";N;s:4:\"nmid\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-03-25 10:28:05\";s:10:\"updated_at\";s:19:\"2026-03-25 10:28:05\";s:10:\"service_id\";i:7;s:7:\"user_id\";N;}s:11:\"\0*\0original\";a:12:{s:2:\"id\";i:9;s:4:\"name\";s:17:\"Bank Central Asia\";s:4:\"type\";s:12:\"bank_account\";s:14:\"account_number\";s:10:\"8831342528\";s:14:\"account_holder\";s:24:\"Nur Fitria Winda Lestari\";s:12:\"qr_code_path\";N;s:4:\"nmid\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-03-25 10:28:05\";s:10:\"updated_at\";s:19:\"2026-03-25 10:28:05\";s:10:\"service_id\";i:7;s:7:\"user_id\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"service_id\";i:1;s:7:\"user_id\";i:2;s:4:\"name\";i:3;s:4:\"type\";i:4;s:14:\"account_number\";i:5;s:14:\"account_holder\";i:6;s:12:\"qr_code_path\";i:7;s:4:\"nmid\";i:8;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:3;O:24:\"App\\Models\\PaymentMethod\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:15:\"payment_methods\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:12:{s:2:\"id\";i:10;s:4:\"name\";s:14:\"RIGEL, KLP DUA\";s:4:\"type\";s:4:\"qris\";s:14:\"account_number\";N;s:14:\"account_holder\";N;s:12:\"qr_code_path\";s:73:\"uploads/images/qris/1774409316_WhatsApp Image 2026-03-25 at 10.26.29.jpeg\";s:4:\"nmid\";s:15:\"ID1024335294947\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-03-25 10:28:36\";s:10:\"updated_at\";s:19:\"2026-03-25 10:28:36\";s:10:\"service_id\";i:7;s:7:\"user_id\";N;}s:11:\"\0*\0original\";a:12:{s:2:\"id\";i:10;s:4:\"name\";s:14:\"RIGEL, KLP DUA\";s:4:\"type\";s:4:\"qris\";s:14:\"account_number\";N;s:14:\"account_holder\";N;s:12:\"qr_code_path\";s:73:\"uploads/images/qris/1774409316_WhatsApp Image 2026-03-25 at 10.26.29.jpeg\";s:4:\"nmid\";s:15:\"ID1024335294947\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-03-25 10:28:36\";s:10:\"updated_at\";s:19:\"2026-03-25 10:28:36\";s:10:\"service_id\";i:7;s:7:\"user_id\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"service_id\";i:1;s:7:\"user_id\";i:2;s:4:\"name\";i:3;s:4:\"type\";i:4;s:14:\"account_number\";i:5;s:14:\"account_holder\";i:6;s:12:\"qr_code_path\";i:7;s:4:\"nmid\";i:8;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:4;O:24:\"App\\Models\\PaymentMethod\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:15:\"payment_methods\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:12:{s:2:\"id\";i:11;s:4:\"name\";s:17:\"Bank Central Asia\";s:4:\"type\";s:12:\"bank_account\";s:14:\"account_number\";s:10:\"8831701757\";s:14:\"account_holder\";s:12:\"Siti Nurlela\";s:12:\"qr_code_path\";N;s:4:\"nmid\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-03-25 10:29:12\";s:10:\"updated_at\";s:19:\"2026-03-25 10:29:12\";s:10:\"service_id\";i:5;s:7:\"user_id\";N;}s:11:\"\0*\0original\";a:12:{s:2:\"id\";i:11;s:4:\"name\";s:17:\"Bank Central Asia\";s:4:\"type\";s:12:\"bank_account\";s:14:\"account_number\";s:10:\"8831701757\";s:14:\"account_holder\";s:12:\"Siti Nurlela\";s:12:\"qr_code_path\";N;s:4:\"nmid\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-03-25 10:29:12\";s:10:\"updated_at\";s:19:\"2026-03-25 10:29:12\";s:10:\"service_id\";i:5;s:7:\"user_id\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"service_id\";i:1;s:7:\"user_id\";i:2;s:4:\"name\";i:3;s:4:\"type\";i:4;s:14:\"account_number\";i:5;s:14:\"account_holder\";i:6;s:12:\"qr_code_path\";i:7;s:4:\"nmid\";i:8;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:5;O:24:\"App\\Models\\PaymentMethod\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:15:\"payment_methods\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:12:{s:2:\"id\";i:12;s:4:\"name\";s:5:\"RIGEL\";s:4:\"type\";s:4:\"qris\";s:14:\"account_number\";N;s:14:\"account_holder\";N;s:12:\"qr_code_path\";s:73:\"uploads/images/qris/1774409379_WhatsApp Image 2026-03-25 at 10.24.13.jpeg\";s:4:\"nmid\";s:15:\"ID1024333230752\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-03-25 10:29:39\";s:10:\"updated_at\";s:19:\"2026-03-25 10:29:39\";s:10:\"service_id\";i:5;s:7:\"user_id\";N;}s:11:\"\0*\0original\";a:12:{s:2:\"id\";i:12;s:4:\"name\";s:5:\"RIGEL\";s:4:\"type\";s:4:\"qris\";s:14:\"account_number\";N;s:14:\"account_holder\";N;s:12:\"qr_code_path\";s:73:\"uploads/images/qris/1774409379_WhatsApp Image 2026-03-25 at 10.24.13.jpeg\";s:4:\"nmid\";s:15:\"ID1024333230752\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-03-25 10:29:39\";s:10:\"updated_at\";s:19:\"2026-03-25 10:29:39\";s:10:\"service_id\";i:5;s:7:\"user_id\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"service_id\";i:1;s:7:\"user_id\";i:2;s:4:\"name\";i:3;s:4:\"type\";i:4;s:14:\"account_number\";i:5;s:14:\"account_holder\";i:6;s:12:\"qr_code_path\";i:7;s:4:\"nmid\";i:8;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:6;O:24:\"App\\Models\\PaymentMethod\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:15:\"payment_methods\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:12:{s:2:\"id\";i:13;s:4:\"name\";s:17:\"Bank Central Asia\";s:4:\"type\";s:12:\"bank_account\";s:14:\"account_number\";s:10:\"8831342528\";s:14:\"account_holder\";s:24:\"Nur Fitria Winda Lestari\";s:12:\"qr_code_path\";N;s:4:\"nmid\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-03-25 10:32:07\";s:10:\"updated_at\";s:19:\"2026-03-25 10:32:07\";s:10:\"service_id\";i:8;s:7:\"user_id\";N;}s:11:\"\0*\0original\";a:12:{s:2:\"id\";i:13;s:4:\"name\";s:17:\"Bank Central Asia\";s:4:\"type\";s:12:\"bank_account\";s:14:\"account_number\";s:10:\"8831342528\";s:14:\"account_holder\";s:24:\"Nur Fitria Winda Lestari\";s:12:\"qr_code_path\";N;s:4:\"nmid\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-03-25 10:32:07\";s:10:\"updated_at\";s:19:\"2026-03-25 10:32:07\";s:10:\"service_id\";i:8;s:7:\"user_id\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"service_id\";i:1;s:7:\"user_id\";i:2;s:4:\"name\";i:3;s:4:\"type\";i:4;s:14:\"account_number\";i:5;s:14:\"account_holder\";i:6;s:12:\"qr_code_path\";i:7;s:4:\"nmid\";i:8;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:7;O:24:\"App\\Models\\PaymentMethod\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:15:\"payment_methods\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:12:{s:2:\"id\";i:14;s:4:\"name\";s:14:\"RIGEL, KLP DUA\";s:4:\"type\";s:4:\"qris\";s:14:\"account_number\";N;s:14:\"account_holder\";N;s:12:\"qr_code_path\";s:73:\"uploads/images/qris/1774409556_WhatsApp Image 2026-03-25 at 10.24.40.jpeg\";s:4:\"nmid\";s:15:\"ID1024335294947\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-03-25 10:32:36\";s:10:\"updated_at\";s:19:\"2026-03-25 10:32:36\";s:10:\"service_id\";i:8;s:7:\"user_id\";N;}s:11:\"\0*\0original\";a:12:{s:2:\"id\";i:14;s:4:\"name\";s:14:\"RIGEL, KLP DUA\";s:4:\"type\";s:4:\"qris\";s:14:\"account_number\";N;s:14:\"account_holder\";N;s:12:\"qr_code_path\";s:73:\"uploads/images/qris/1774409556_WhatsApp Image 2026-03-25 at 10.24.40.jpeg\";s:4:\"nmid\";s:15:\"ID1024335294947\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-03-25 10:32:36\";s:10:\"updated_at\";s:19:\"2026-03-25 10:32:36\";s:10:\"service_id\";i:8;s:7:\"user_id\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"service_id\";i:1;s:7:\"user_id\";i:2;s:4:\"name\";i:3;s:4:\"type\";i:4;s:14:\"account_number\";i:5;s:14:\"account_holder\";i:6;s:12:\"qr_code_path\";i:7;s:4:\"nmid\";i:8;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:8;O:24:\"App\\Models\\PaymentMethod\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:15:\"payment_methods\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:12:{s:2:\"id\";i:15;s:4:\"name\";s:17:\"Bank Central Asia\";s:4:\"type\";s:12:\"bank_account\";s:14:\"account_number\";s:10:\"4870597610\";s:14:\"account_holder\";s:13:\"Hardi Cahyadi\";s:12:\"qr_code_path\";N;s:4:\"nmid\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-03-25 10:33:06\";s:10:\"updated_at\";s:19:\"2026-03-25 10:33:06\";s:10:\"service_id\";i:11;s:7:\"user_id\";N;}s:11:\"\0*\0original\";a:12:{s:2:\"id\";i:15;s:4:\"name\";s:17:\"Bank Central Asia\";s:4:\"type\";s:12:\"bank_account\";s:14:\"account_number\";s:10:\"4870597610\";s:14:\"account_holder\";s:13:\"Hardi Cahyadi\";s:12:\"qr_code_path\";N;s:4:\"nmid\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-03-25 10:33:06\";s:10:\"updated_at\";s:19:\"2026-03-25 10:33:06\";s:10:\"service_id\";i:11;s:7:\"user_id\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"service_id\";i:1;s:7:\"user_id\";i:2;s:4:\"name\";i:3;s:4:\"type\";i:4;s:14:\"account_number\";i:5;s:14:\"account_holder\";i:6;s:12:\"qr_code_path\";i:7;s:4:\"nmid\";i:8;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:9;O:24:\"App\\Models\\PaymentMethod\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:15:\"payment_methods\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:12:{s:2:\"id\";i:16;s:4:\"name\";s:7:\"Mandiri\";s:4:\"type\";s:12:\"bank_account\";s:14:\"account_number\";s:13:\"1190007508862\";s:14:\"account_holder\";s:13:\"Hardi Cahyadi\";s:12:\"qr_code_path\";N;s:4:\"nmid\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-03-25 10:33:32\";s:10:\"updated_at\";s:19:\"2026-03-25 10:33:32\";s:10:\"service_id\";i:11;s:7:\"user_id\";N;}s:11:\"\0*\0original\";a:12:{s:2:\"id\";i:16;s:4:\"name\";s:7:\"Mandiri\";s:4:\"type\";s:12:\"bank_account\";s:14:\"account_number\";s:13:\"1190007508862\";s:14:\"account_holder\";s:13:\"Hardi Cahyadi\";s:12:\"qr_code_path\";N;s:4:\"nmid\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-03-25 10:33:32\";s:10:\"updated_at\";s:19:\"2026-03-25 10:33:32\";s:10:\"service_id\";i:11;s:7:\"user_id\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"service_id\";i:1;s:7:\"user_id\";i:2;s:4:\"name\";i:3;s:4:\"type\";i:4;s:14:\"account_number\";i:5;s:14:\"account_holder\";i:6;s:12:\"qr_code_path\";i:7;s:4:\"nmid\";i:8;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:10;O:24:\"App\\Models\\PaymentMethod\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:15:\"payment_methods\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:12:{s:2:\"id\";i:17;s:4:\"name\";s:3:\"BRI\";s:4:\"type\";s:12:\"bank_account\";s:14:\"account_number\";s:15:\"078701020052536\";s:14:\"account_holder\";s:13:\"Hardi Cahyadi\";s:12:\"qr_code_path\";N;s:4:\"nmid\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-03-25 10:33:49\";s:10:\"updated_at\";s:19:\"2026-03-25 10:33:49\";s:10:\"service_id\";i:11;s:7:\"user_id\";N;}s:11:\"\0*\0original\";a:12:{s:2:\"id\";i:17;s:4:\"name\";s:3:\"BRI\";s:4:\"type\";s:12:\"bank_account\";s:14:\"account_number\";s:15:\"078701020052536\";s:14:\"account_holder\";s:13:\"Hardi Cahyadi\";s:12:\"qr_code_path\";N;s:4:\"nmid\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-03-25 10:33:49\";s:10:\"updated_at\";s:19:\"2026-03-25 10:33:49\";s:10:\"service_id\";i:11;s:7:\"user_id\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"service_id\";i:1;s:7:\"user_id\";i:2;s:4:\"name\";i:3;s:4:\"type\";i:4;s:14:\"account_number\";i:5;s:14:\"account_holder\";i:6;s:12:\"qr_code_path\";i:7;s:4:\"nmid\";i:8;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:11;O:24:\"App\\Models\\PaymentMethod\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:15:\"payment_methods\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:12:{s:2:\"id\";i:18;s:4:\"name\";s:13:\"RIGEL, PDMNGN\";s:4:\"type\";s:4:\"qris\";s:14:\"account_number\";N;s:14:\"account_holder\";N;s:12:\"qr_code_path\";s:73:\"uploads/images/qris/1774409671_WhatsApp Image 2026-03-25 at 10.24.51.jpeg\";s:4:\"nmid\";s:15:\"ID1024359973301\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-03-25 10:34:31\";s:10:\"updated_at\";s:19:\"2026-03-25 10:34:31\";s:10:\"service_id\";i:11;s:7:\"user_id\";N;}s:11:\"\0*\0original\";a:12:{s:2:\"id\";i:18;s:4:\"name\";s:13:\"RIGEL, PDMNGN\";s:4:\"type\";s:4:\"qris\";s:14:\"account_number\";N;s:14:\"account_holder\";N;s:12:\"qr_code_path\";s:73:\"uploads/images/qris/1774409671_WhatsApp Image 2026-03-25 at 10.24.51.jpeg\";s:4:\"nmid\";s:15:\"ID1024359973301\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-03-25 10:34:31\";s:10:\"updated_at\";s:19:\"2026-03-25 10:34:31\";s:10:\"service_id\";i:11;s:7:\"user_id\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"service_id\";i:1;s:7:\"user_id\";i:2;s:4:\"name\";i:3;s:4:\"type\";i:4;s:14:\"account_number\";i:5;s:14:\"account_holder\";i:6;s:12:\"qr_code_path\";i:7;s:4:\"nmid\";i:8;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:12;O:24:\"App\\Models\\PaymentMethod\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:15:\"payment_methods\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:12:{s:2:\"id\";i:19;s:4:\"name\";s:17:\"Bank Central Asia\";s:4:\"type\";s:12:\"bank_account\";s:14:\"account_number\";s:10:\"8832168709\";s:14:\"account_holder\";s:24:\"Nur Fitria Winda Lestari\";s:12:\"qr_code_path\";N;s:4:\"nmid\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-03-25 10:35:02\";s:10:\"updated_at\";s:19:\"2026-03-25 10:35:02\";s:10:\"service_id\";i:1;s:7:\"user_id\";N;}s:11:\"\0*\0original\";a:12:{s:2:\"id\";i:19;s:4:\"name\";s:17:\"Bank Central Asia\";s:4:\"type\";s:12:\"bank_account\";s:14:\"account_number\";s:10:\"8832168709\";s:14:\"account_holder\";s:24:\"Nur Fitria Winda Lestari\";s:12:\"qr_code_path\";N;s:4:\"nmid\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-03-25 10:35:02\";s:10:\"updated_at\";s:19:\"2026-03-25 10:35:02\";s:10:\"service_id\";i:1;s:7:\"user_id\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"service_id\";i:1;s:7:\"user_id\";i:2;s:4:\"name\";i:3;s:4:\"type\";i:4;s:14:\"account_number\";i:5;s:14:\"account_holder\";i:6;s:12:\"qr_code_path\";i:7;s:4:\"nmid\";i:8;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:13;O:24:\"App\\Models\\PaymentMethod\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:15:\"payment_methods\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:12:{s:2:\"id\";i:20;s:4:\"name\";s:19:\"RIGELSTOREID MAINAN\";s:4:\"type\";s:4:\"qris\";s:14:\"account_number\";N;s:14:\"account_holder\";N;s:12:\"qr_code_path\";s:73:\"uploads/images/qris/1774409731_WhatsApp Image 2026-03-25 at 10.28.28.jpeg\";s:4:\"nmid\";s:15:\"ID1025434098022\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-03-25 10:35:31\";s:10:\"updated_at\";s:19:\"2026-03-25 10:35:31\";s:10:\"service_id\";i:1;s:7:\"user_id\";N;}s:11:\"\0*\0original\";a:12:{s:2:\"id\";i:20;s:4:\"name\";s:19:\"RIGELSTOREID MAINAN\";s:4:\"type\";s:4:\"qris\";s:14:\"account_number\";N;s:14:\"account_holder\";N;s:12:\"qr_code_path\";s:73:\"uploads/images/qris/1774409731_WhatsApp Image 2026-03-25 at 10.28.28.jpeg\";s:4:\"nmid\";s:15:\"ID1025434098022\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-03-25 10:35:31\";s:10:\"updated_at\";s:19:\"2026-03-25 10:35:31\";s:10:\"service_id\";i:1;s:7:\"user_id\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"service_id\";i:1;s:7:\"user_id\";i:2;s:4:\"name\";i:3;s:4:\"type\";i:4;s:14:\"account_number\";i:5;s:14:\"account_holder\";i:6;s:12:\"qr_code_path\";i:7;s:4:\"nmid\";i:8;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:14;O:24:\"App\\Models\\PaymentMethod\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:15:\"payment_methods\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:12:{s:2:\"id\";i:21;s:4:\"name\";s:17:\"Bank Central Asia\";s:4:\"type\";s:12:\"bank_account\";s:14:\"account_number\";s:10:\"8831701757\";s:14:\"account_holder\";s:12:\"Siti Nurlela\";s:12:\"qr_code_path\";N;s:4:\"nmid\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-03-25 10:35:59\";s:10:\"updated_at\";s:19:\"2026-03-25 10:35:59\";s:10:\"service_id\";i:6;s:7:\"user_id\";N;}s:11:\"\0*\0original\";a:12:{s:2:\"id\";i:21;s:4:\"name\";s:17:\"Bank Central Asia\";s:4:\"type\";s:12:\"bank_account\";s:14:\"account_number\";s:10:\"8831701757\";s:14:\"account_holder\";s:12:\"Siti Nurlela\";s:12:\"qr_code_path\";N;s:4:\"nmid\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-03-25 10:35:59\";s:10:\"updated_at\";s:19:\"2026-03-25 10:35:59\";s:10:\"service_id\";i:6;s:7:\"user_id\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"service_id\";i:1;s:7:\"user_id\";i:2;s:4:\"name\";i:3;s:4:\"type\";i:4;s:14:\"account_number\";i:5;s:14:\"account_holder\";i:6;s:12:\"qr_code_path\";i:7;s:4:\"nmid\";i:8;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:15;O:24:\"App\\Models\\PaymentMethod\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:15:\"payment_methods\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:12:{s:2:\"id\";i:22;s:4:\"name\";s:11:\"RIGEL TopUP\";s:4:\"type\";s:4:\"qris\";s:14:\"account_number\";N;s:14:\"account_holder\";N;s:12:\"qr_code_path\";s:73:\"uploads/images/qris/1774409799_WhatsApp Image 2026-03-25 at 10.24.33.jpeg\";s:4:\"nmid\";s:15:\"ID1024326954418\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-03-25 10:36:39\";s:10:\"updated_at\";s:19:\"2026-03-25 10:36:39\";s:10:\"service_id\";i:6;s:7:\"user_id\";N;}s:11:\"\0*\0original\";a:12:{s:2:\"id\";i:22;s:4:\"name\";s:11:\"RIGEL TopUP\";s:4:\"type\";s:4:\"qris\";s:14:\"account_number\";N;s:14:\"account_holder\";N;s:12:\"qr_code_path\";s:73:\"uploads/images/qris/1774409799_WhatsApp Image 2026-03-25 at 10.24.33.jpeg\";s:4:\"nmid\";s:15:\"ID1024326954418\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-03-25 10:36:39\";s:10:\"updated_at\";s:19:\"2026-03-25 10:36:39\";s:10:\"service_id\";i:6;s:7:\"user_id\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:10:\"service_id\";i:1;s:7:\"user_id\";i:2;s:4:\"name\";i:3;s:4:\"type\";i:4;s:14:\"account_number\";i:5;s:14:\"account_holder\";i:6;s:12:\"qr_code_path\";i:7;s:4:\"nmid\";i:8;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 1776684158),
('agency-rigel-cache-reports.sales.metrics:3ca071169f5b3e27a4b7a4e7e4bdfe9432a033d6', 'a:7:{s:9:\"startDate\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2026-04-20 00:00:00.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:12:\"Asia/Jakarta\";}s:7:\"endDate\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2026-04-20 23:59:59.999999\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:12:\"Asia/Jakarta\";}s:7:\"summary\";O:8:\"stdClass\":3:{s:11:\"total_sales\";s:12:\"510300250.00\";s:16:\"total_commission\";s:11:\"10200500.00\";s:18:\"total_transactions\";i:6;}s:11:\"chartLabels\";a:24:{i:0;s:4:\"0:00\";i:1;s:4:\"1:00\";i:2;s:4:\"2:00\";i:3;s:4:\"3:00\";i:4;s:4:\"4:00\";i:5;s:4:\"5:00\";i:6;s:4:\"6:00\";i:7;s:4:\"7:00\";i:8;s:4:\"8:00\";i:9;s:4:\"9:00\";i:10;s:5:\"10:00\";i:11;s:5:\"11:00\";i:12;s:5:\"12:00\";i:13;s:5:\"13:00\";i:14;s:5:\"14:00\";i:15;s:5:\"15:00\";i:16;s:5:\"16:00\";i:17;s:5:\"17:00\";i:18;s:5:\"18:00\";i:19;s:5:\"19:00\";i:20;s:5:\"20:00\";i:21;s:5:\"21:00\";i:22;s:5:\"22:00\";i:23;s:5:\"23:00\";}s:14:\"chartSalesData\";a:24:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;d:505000000;i:11;d:225000;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;d:5075250;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;}s:19:\"chartCommissionData\";a:24:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;d:10000000;i:11;d:150000;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;d:50500;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;}s:21:\"chartTransactionsData\";a:24:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:2;i:11;i:2;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:2;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;}}', 1776682970),
('agency-rigel-cache-reports.sales.metrics:3f992793059adbba9856e78eaa73b22e8d612ca9', 'a:7:{s:9:\"startDate\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2026-04-20 00:00:00.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:12:\"Asia/Jakarta\";}s:7:\"endDate\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2026-04-20 23:59:59.999999\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:12:\"Asia/Jakarta\";}s:7:\"summary\";O:8:\"stdClass\":3:{s:11:\"total_sales\";s:12:\"505300250.00\";s:16:\"total_commission\";s:10:\"5200500.00\";s:18:\"total_transactions\";i:5;}s:11:\"chartLabels\";a:24:{i:0;s:4:\"0:00\";i:1;s:4:\"1:00\";i:2;s:4:\"2:00\";i:3;s:4:\"3:00\";i:4;s:4:\"4:00\";i:5;s:4:\"5:00\";i:6;s:4:\"6:00\";i:7;s:4:\"7:00\";i:8;s:4:\"8:00\";i:9;s:4:\"9:00\";i:10;s:5:\"10:00\";i:11;s:5:\"11:00\";i:12;s:5:\"12:00\";i:13;s:5:\"13:00\";i:14;s:5:\"14:00\";i:15;s:5:\"15:00\";i:16;s:5:\"16:00\";i:17;s:5:\"17:00\";i:18;s:5:\"18:00\";i:19;s:5:\"19:00\";i:20;s:5:\"20:00\";i:21;s:5:\"21:00\";i:22;s:5:\"22:00\";i:23;s:5:\"23:00\";}s:14:\"chartSalesData\";a:24:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;d:500000000;i:11;d:225000;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;d:5075250;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;}s:19:\"chartCommissionData\";a:24:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;d:5000000;i:11;d:150000;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;d:50500;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;}s:21:\"chartTransactionsData\";a:24:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:1;i:11;i:2;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:2;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;}}', 1776683888),
('agency-rigel-cache-reports.sales.metrics:8c25e36d79fa580c653b2fc8637e274d02724465', 'a:7:{s:9:\"startDate\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2026-04-01 00:00:00.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:12:\"Asia/Jakarta\";}s:7:\"endDate\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2026-04-30 23:59:59.999999\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:12:\"Asia/Jakarta\";}s:7:\"summary\";O:8:\"stdClass\":3:{s:11:\"total_sales\";s:12:\"517417250.00\";s:16:\"total_commission\";s:11:\"10272660.00\";s:18:\"total_transactions\";i:70;}s:11:\"chartLabels\";a:30:{i:0;s:6:\"01 Apr\";i:1;s:6:\"02 Apr\";i:2;s:6:\"03 Apr\";i:3;s:6:\"04 Apr\";i:4;s:6:\"05 Apr\";i:5;s:6:\"06 Apr\";i:6;s:6:\"07 Apr\";i:7;s:6:\"08 Apr\";i:8;s:6:\"09 Apr\";i:9;s:6:\"10 Apr\";i:10;s:6:\"11 Apr\";i:11;s:6:\"12 Apr\";i:12;s:6:\"13 Apr\";i:13;s:6:\"14 Apr\";i:14;s:6:\"15 Apr\";i:15;s:6:\"16 Apr\";i:16;s:6:\"17 Apr\";i:17;s:6:\"18 Apr\";i:18;s:6:\"19 Apr\";i:19;s:6:\"20 Apr\";i:20;s:6:\"21 Apr\";i:21;s:6:\"22 Apr\";i:22;s:6:\"23 Apr\";i:23;s:6:\"24 Apr\";i:24;s:6:\"25 Apr\";i:25;s:6:\"26 Apr\";i:26;s:6:\"27 Apr\";i:27;s:6:\"28 Apr\";i:28;s:6:\"29 Apr\";i:29;s:6:\"30 Apr\";}s:14:\"chartSalesData\";a:30:{i:0;i:0;i:1;s:8:\"10000.00\";i:2;i:0;i:3;i:0;i:4;i:0;i:5;s:8:\"21000.00\";i:6;s:10:\"2945000.00\";i:7;s:9:\"400000.00\";i:8;s:10:\"1680000.00\";i:9;s:9:\"980000.00\";i:10;s:9:\"740000.00\";i:11;s:9:\"130000.00\";i:12;s:9:\"110000.00\";i:13;s:9:\"101000.00\";i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;s:12:\"510300250.00\";i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;i:24;i:0;i:25;i:0;i:26;i:0;i:27;i:0;i:28;i:0;i:29;i:0;}s:19:\"chartCommissionData\";a:30:{i:0;i:0;i:1;s:6:\"100.00\";i:2;i:0;i:3;i:0;i:4;i:0;i:5;s:6:\"210.00\";i:6;s:8:\"29450.00\";i:7;s:7:\"4000.00\";i:8;s:8:\"16800.00\";i:9;s:7:\"9800.00\";i:10;s:7:\"7400.00\";i:11;s:7:\"1300.00\";i:12;s:7:\"1100.00\";i:13;s:7:\"2000.00\";i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;s:11:\"10200500.00\";i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;i:24;i:0;i:25;i:0;i:26;i:0;i:27;i:0;i:28;i:0;i:29;i:0;}s:21:\"chartTransactionsData\";a:30:{i:0;i:0;i:1;i:1;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:1;i:6;i:16;i:7;i:6;i:8;i:13;i:9;i:7;i:10;i:12;i:11;i:3;i:12;i:3;i:13;i:2;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:6;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;i:24;i:0;i:25;i:0;i:26;i:0;i:27;i:0;i:28;i:0;i:29;i:0;}}', 1776683710),
('agency-rigel-cache-tg_message_27', 's:17:\"TRX-69E5FA54B71AA\";', 1776765916);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `commissions`
--

CREATE TABLE `commissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `sale_transaction_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `period_date` date NOT NULL,
  `period_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `withdrawn` tinyint(1) NOT NULL DEFAULT '0',
  `withdrawal_transaction_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `commissions`
--

INSERT INTO `commissions` (`id`, `user_id`, `sale_transaction_id`, `amount`, `period_date`, `period_type`, `withdrawn`, `withdrawal_transaction_id`, `created_at`, `updated_at`) VALUES
(4, 27, 8, '100.00', '2026-04-02', 'daily', 0, NULL, '2026-04-02 05:49:06', '2026-04-02 05:49:06'),
(7, 24, 12, '210.00', '2026-04-06', 'daily', 0, NULL, '2026-04-06 12:03:18', '2026-04-06 12:03:18'),
(8, 24, 17, '300.00', '2026-04-07', 'daily', 0, NULL, '2026-04-07 04:47:15', '2026-04-07 04:47:15'),
(9, 17, 18, '300.00', '2026-04-07', 'daily', 0, NULL, '2026-04-07 06:18:50', '2026-04-07 06:18:50'),
(10, 23, 19, '300.00', '2026-04-07', 'daily', 0, NULL, '2026-04-07 06:53:05', '2026-04-07 06:53:05'),
(11, 33, 20, '2000.00', '2026-04-07', 'daily', 0, NULL, '2026-04-07 09:00:18', '2026-04-07 09:00:18'),
(12, 33, 21, '3000.00', '2026-04-07', 'daily', 0, NULL, '2026-04-07 09:00:35', '2026-04-07 09:00:35'),
(13, 33, 22, '2000.00', '2026-04-07', 'daily', 0, NULL, '2026-04-07 09:01:00', '2026-04-07 09:01:00'),
(14, 33, 23, '5000.00', '2026-04-07', 'daily', 0, NULL, '2026-04-07 09:01:18', '2026-04-07 09:01:18'),
(15, 33, 24, '5000.00', '2026-04-07', 'daily', 0, NULL, '2026-04-07 09:01:40', '2026-04-07 09:01:40'),
(16, 17, 25, '2250.00', '2026-04-07', 'daily', 0, NULL, '2026-04-07 10:56:43', '2026-04-07 10:56:43'),
(17, 23, 26, '100.00', '2026-04-07', 'daily', 0, NULL, '2026-04-07 13:10:52', '2026-04-07 13:10:52'),
(18, 17, 27, '300.00', '2026-04-07', 'daily', 0, NULL, '2026-04-07 13:27:52', '2026-04-07 13:27:52'),
(19, 33, 28, '5000.00', '2026-04-07', 'daily', 0, NULL, '2026-04-07 13:58:19', '2026-04-07 13:58:19'),
(20, 33, 29, '400.00', '2026-04-07', 'daily', 0, NULL, '2026-04-07 14:44:39', '2026-04-07 14:44:39'),
(21, 17, 30, '500.00', '2026-04-07', 'daily', 0, NULL, '2026-04-07 15:30:59', '2026-04-07 15:30:59'),
(22, 23, 31, '1500.00', '2026-04-07', 'daily', 0, NULL, '2026-04-07 16:38:48', '2026-04-07 16:38:48'),
(23, 23, 32, '1500.00', '2026-04-07', 'daily', 0, NULL, '2026-04-07 16:44:06', '2026-04-07 16:44:06'),
(24, 23, 33, '2000.00', '2026-04-08', 'daily', 0, NULL, '2026-04-08 01:29:38', '2026-04-08 01:29:38'),
(25, 23, 34, '800.00', '2026-04-08', 'daily', 0, NULL, '2026-04-08 04:15:48', '2026-04-08 04:15:48'),
(26, 23, 35, '300.00', '2026-04-08', 'daily', 0, NULL, '2026-04-08 11:46:52', '2026-04-08 11:46:52'),
(27, 14, 36, '500.00', '2026-04-08', 'daily', 0, NULL, '2026-04-08 13:53:49', '2026-04-08 13:53:49'),
(28, 33, 37, '300.00', '2026-04-08', 'daily', 0, NULL, '2026-04-08 16:08:39', '2026-04-08 16:08:39'),
(29, 23, 38, '100.00', '2026-04-08', 'daily', 0, NULL, '2026-04-08 16:11:53', '2026-04-08 16:11:53'),
(30, 17, 39, '300.00', '2026-04-09', 'daily', 0, NULL, '2026-04-09 00:39:19', '2026-04-09 00:39:19'),
(31, 23, 40, '800.00', '2026-04-09', 'daily', 0, NULL, '2026-04-09 01:33:40', '2026-04-09 01:33:40'),
(32, 33, 41, '300.00', '2026-04-09', 'daily', 0, NULL, '2026-04-09 05:53:21', '2026-04-09 05:53:21'),
(33, 23, 42, '300.00', '2026-04-09', 'daily', 0, NULL, '2026-04-09 06:53:52', '2026-04-09 06:53:52'),
(34, 14, 43, '500.00', '2026-04-09', 'daily', 0, NULL, '2026-04-09 11:10:57', '2026-04-09 11:10:57'),
(35, 33, 44, '300.00', '2026-04-09', 'daily', 0, NULL, '2026-04-09 12:17:51', '2026-04-09 12:17:51'),
(36, 17, 45, '2900.00', '2026-04-09', 'daily', 0, NULL, '2026-04-09 12:21:29', '2026-04-09 12:21:29'),
(37, 23, 46, '5000.00', '2026-04-09', 'daily', 0, NULL, '2026-04-09 12:34:44', '2026-04-09 12:34:44'),
(38, 17, 47, '1000.00', '2026-04-09', 'daily', 0, NULL, '2026-04-09 12:48:17', '2026-04-09 12:48:17'),
(39, 36, 48, '3400.00', '2026-04-09', 'daily', 1, NULL, '2026-04-09 13:09:33', '2026-04-13 00:33:30'),
(40, 33, 49, '900.00', '2026-04-09', 'daily', 0, NULL, '2026-04-09 13:18:40', '2026-04-09 13:18:40'),
(41, 33, 50, '1000.00', '2026-04-09', 'daily', 0, NULL, '2026-04-09 13:26:15', '2026-04-09 13:26:15'),
(42, 23, 51, '100.00', '2026-04-09', 'daily', 0, NULL, '2026-04-09 15:59:21', '2026-04-09 15:59:21'),
(43, 23, 52, '3000.00', '2026-04-10', 'daily', 0, NULL, '2026-04-09 17:41:46', '2026-04-09 17:41:46'),
(44, 23, 53, '500.00', '2026-04-10', 'daily', 0, NULL, '2026-04-09 17:52:35', '2026-04-09 17:52:35'),
(45, 17, 54, '1000.00', '2026-04-10', 'daily', 0, NULL, '2026-04-10 03:11:05', '2026-04-10 03:11:05'),
(46, 17, 55, '1000.00', '2026-04-10', 'daily', 0, NULL, '2026-04-10 05:22:20', '2026-04-10 05:22:20'),
(47, 33, 56, '300.00', '2026-04-10', 'daily', 0, NULL, '2026-04-10 12:06:09', '2026-04-10 12:06:09'),
(49, 33, 59, '3000.00', '2026-04-10', 'daily', 0, NULL, '2026-04-10 16:08:47', '2026-04-10 16:08:47'),
(50, 36, 60, '1000.00', '2026-04-11', 'daily', 1, NULL, '2026-04-11 01:25:04', '2026-04-13 00:33:30'),
(51, 24, 61, '200.00', '2026-04-11', 'daily', 0, NULL, '2026-04-11 04:34:50', '2026-04-11 04:34:50'),
(52, 33, 62, '700.00', '2026-04-11', 'daily', 0, NULL, '2026-04-11 07:15:39', '2026-04-11 07:15:39'),
(53, 14, 63, '500.00', '2026-04-11', 'daily', 0, NULL, '2026-04-11 09:06:14', '2026-04-11 09:06:14'),
(54, 17, 64, '2000.00', '2026-04-11', 'daily', 0, NULL, '2026-04-11 10:02:19', '2026-04-11 10:02:19'),
(55, 14, 58, '1000.00', '2026-04-11', 'daily', 0, NULL, '2026-04-11 10:20:43', '2026-04-11 10:20:43'),
(56, 14, 58, '1000.00', '2026-04-11', 'daily', 0, NULL, '2026-04-11 10:20:43', '2026-04-11 10:20:43'),
(57, 17, 65, '1000.00', '2026-04-11', 'daily', 0, NULL, '2026-04-11 13:29:02', '2026-04-11 13:29:02'),
(58, 23, 66, '100.00', '2026-04-11', 'daily', 0, NULL, '2026-04-11 13:38:36', '2026-04-11 13:38:36'),
(59, 33, 67, '500.00', '2026-04-11', 'daily', 0, NULL, '2026-04-11 13:45:40', '2026-04-11 13:45:40'),
(60, 33, 68, '500.00', '2026-04-11', 'daily', 0, NULL, '2026-04-11 14:02:08', '2026-04-11 14:02:08'),
(61, 23, 69, '100.00', '2026-04-11', 'daily', 0, NULL, '2026-04-11 14:34:26', '2026-04-11 14:34:26'),
(62, 33, 70, '500.00', '2026-04-11', 'daily', 0, NULL, '2026-04-11 14:40:20', '2026-04-11 14:40:20'),
(63, 23, 71, '300.00', '2026-04-11', 'daily', 0, NULL, '2026-04-11 16:09:06', '2026-04-11 16:09:06'),
(64, 36, 72, '500.00', '2026-04-12', 'daily', 1, NULL, '2026-04-12 08:24:39', '2026-04-13 00:33:30'),
(65, 36, 73, '500.00', '2026-04-12', 'daily', 1, NULL, '2026-04-12 11:15:20', '2026-04-13 00:33:30'),
(66, 23, 74, '300.00', '2026-04-12', 'daily', 0, NULL, '2026-04-12 12:00:55', '2026-04-12 12:00:55'),
(67, 36, 76, '100.00', '2026-04-13', 'daily', 0, NULL, '2026-04-13 11:04:40', '2026-04-13 11:04:40'),
(68, 17, 77, '500.00', '2026-04-13', 'daily', 0, NULL, '2026-04-13 13:11:38', '2026-04-13 13:11:38'),
(69, 17, 78, '500.00', '2026-04-13', 'daily', 0, NULL, '2026-04-13 13:40:36', '2026-04-13 13:40:36'),
(70, 26, 79, '1000.00', '2026-04-14', 'daily', 1, NULL, '2026-04-14 06:22:04', '2026-04-14 06:32:35'),
(71, 37, 83, '1000.00', '2026-04-14', 'daily', 1, NULL, '2026-04-14 09:17:00', '2026-04-14 09:26:19'),
(72, 43, 86, '5000000.00', '2026-04-20', 'daily', 0, NULL, '2026-04-20 03:34:44', '2026-04-20 03:43:30'),
(73, 44, 89, '5000000.00', '2026-04-20', 'daily', 0, NULL, '2026-04-20 03:54:39', '2026-04-20 03:57:34'),
(74, 37, 91, '75000.00', '2026-04-20', 'daily', 1, NULL, '2026-04-20 04:30:34', '2026-04-20 04:31:27'),
(75, 37, 91, '75000.00', '2026-04-20', 'daily', 0, NULL, '2026-04-20 04:31:27', '2026-04-20 04:31:27'),
(76, 38, 93, '25250.00', '2026-04-20', 'daily', 1, 94, '2026-04-20 10:05:48', '2026-04-20 10:09:01'),
(77, 38, 93, '6312.00', '2026-04-20', 'daily', 0, NULL, '2026-04-20 10:09:01', '2026-04-20 10:12:17'),
(78, 38, 93, '12625.00', '2026-04-20', 'daily', 0, NULL, '2026-04-20 10:09:59', '2026-04-20 10:12:17'),
(79, 38, 93, '6313.00', '2026-04-20', 'daily', 0, NULL, '2026-04-20 10:11:55', '2026-04-20 10:11:55');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `host_submissions`
--

CREATE TABLE `host_submissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_transaction_id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED DEFAULT NULL,
  `host_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nickname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `whatsapp_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `form_filled` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `host_submissions`
--

INSERT INTO `host_submissions` (`id`, `sale_transaction_id`, `service_id`, `host_id`, `nickname`, `whatsapp_number`, `form_filled`, `created_at`, `updated_at`) VALUES
(1, 13, 10, '21232327', 'Bella', '6281389741131', 0, '2026-04-06 13:10:37', '2026-04-06 13:10:37');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_08_14_170933_add_two_factor_columns_to_users_table', 1),
(5, '2026_01_29_065925_add_role_to_users_table', 1),
(6, '2026_01_30_041438_create_articles_table', 1),
(7, '2026_01_30_132436_create_services_table', 1),
(8, '2026_01_30_150842_add_image_column_to_services_table', 1),
(9, '2026_01_30_154356_create_transactions_table', 1),
(10, '2026_01_30_160000_create_sales_transactions_table', 1),
(11, '2026_01_30_160001_create_payment_methods_table', 1),
(12, '2026_01_30_160002_create_commissions_table', 1),
(13, '2026_01_31_031929_create_sale_transactions_table', 1),
(14, '2026_01_31_041015_add_withdrawn_to_commissions_table', 1),
(15, '2026_01_31_050933_create_settings_table', 1),
(16, '2026_01_31_060514_add_commission_amount_to_transactions_table', 1),
(17, '2026_01_31_072911_add_username_to_users_table', 1),
(18, '2026_01_31_075152_add_missing_columns_to_sale_transactions_table', 1),
(19, '2026_01_31_081528_add_nmid_to_payment_methods_table', 1),
(20, '2026_01_31_093100_add_withdrawal_fields_to_sale_transactions_table', 1),
(21, '2026_01_31_094719_update_commissions_foreign_key', 1),
(22, '2026_01_31_095956_add_proof_image_to_sale_transactions_table', 1),
(23, '2026_01_31_110320_create_notifications_table', 1),
(24, '2026_01_31_122410_add_process_status_to_sale_transactions', 1),
(25, '2026_02_05_034259_add_topup_fields_to_sale_transactions_table', 1),
(26, '2026_02_19_000001_add_performance_indexes', 1),
(27, '2026_02_19_043331_add_service_id_to_payment_methods_table', 1),
(28, '2026_02_19_050732_add_user_id_to_payment_methods_table', 1),
(29, '2026_02_19_052048_add_commission_rate_to_services_table', 1),
(30, '2026_02_19_052753_add_whatsapp_number_to_services_table', 1),
(31, '2026_02_23_135125_add_is_active_to_services_table', 1),
(32, '2026_02_24_162431_add_avatar_to_users_table', 1),
(33, '2026_03_02_165531_add_minimum_nominal_to_services_table', 2),
(34, '2026_03_03_090119_add_transaction_code_to_sale_transactions_table', 3),
(35, '2026_03_06_000001_create_host_submissions_table', 4),
(36, '2026_03_10_004232_add_telegram_chat_id_to_services_table', 5),
(37, '2026_03_10_005226_create_telegram_bots_table', 6),
(38, '2026_03_10_005306_add_telegram_bot_id_to_services_table', 6),
(39, '2026_04_13_000001_add_marketing_owner_id_to_users_table', 7),
(40, '2026_04_20_000001_add_withdrawal_transaction_id_to_commissions_table', 7),
(41, '2026_04_20_000002_add_more_performance_indexes', 7);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'info',
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `type`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
(84, 1, 'Transaksi Ditolak', 'Transaksi #27 ditolak oleh admin. Silakan hubungi admin untuk informasi lebih lanjut.', 'error', '{\"amount\": \"5000000.00\", \"new_status\": \"failed\", \"old_status\": \"pending\", \"transaction_id\": 27, \"commission_amount\": \"50000.00\"}', NULL, '2026-03-03 06:27:15', '2026-03-03 06:27:15'),
(102, 14, 'Selamat Datang di Rigel Agency!', 'Terima kasih telah bergabung dengan Rigel Agency. Mulai jual produk dan dapatkan komisi menarik!', 'success', '{\"type\":\"welcome\"}', NULL, '2026-03-25 02:56:08', '2026-03-25 02:56:08'),
(104, 16, 'Selamat Datang di Rigel Agency!', 'Terima kasih telah bergabung dengan Rigel Agency. Mulai jual produk dan dapatkan komisi menarik!', 'success', '{\"type\":\"welcome\"}', NULL, '2026-03-25 02:59:44', '2026-03-25 02:59:44'),
(105, 17, 'Selamat Datang di Rigel Agency!', 'Terima kasih telah bergabung dengan Rigel Agency. Mulai jual produk dan dapatkan komisi menarik!', 'success', '{\"type\":\"welcome\"}', NULL, '2026-03-25 03:01:08', '2026-03-25 03:01:08'),
(111, 23, 'Selamat Datang di Rigel Agency!', 'Terima kasih telah bergabung dengan Rigel Agency. Mulai jual produk dan dapatkan komisi menarik!', 'success', '{\"type\":\"welcome\"}', NULL, '2026-03-25 03:07:40', '2026-03-25 03:07:40'),
(112, 24, 'Selamat Datang di Rigel Agency!', 'Terima kasih telah bergabung dengan Rigel Agency. Mulai jual produk dan dapatkan komisi menarik!', 'success', '{\"type\":\"welcome\"}', NULL, '2026-03-25 03:09:03', '2026-03-25 03:09:03'),
(114, 26, 'Selamat Datang di Rigel Agency!', 'Terima kasih telah bergabung dengan Rigel Agency. Mulai jual produk dan dapatkan komisi menarik!', 'success', '{\"type\":\"welcome\"}', NULL, '2026-03-25 03:49:22', '2026-03-25 03:49:22'),
(115, 27, 'Selamat Datang di Rigel Agency!', 'Terima kasih telah bergabung dengan Rigel Agency. Mulai jual produk dan dapatkan komisi menarik!', 'success', '{\"type\":\"welcome\"}', NULL, '2026-03-25 06:02:49', '2026-03-25 06:02:49'),
(119, 26, 'Transaksi Berhasil', 'Transaksi #7 telah berhasil disetujui. Komisi sebesar Rp 10.000 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":7,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"1000000.00\",\"commission_amount\":\"10000.00\"}', NULL, '2026-04-01 08:10:39', '2026-04-01 08:10:39'),
(120, 26, 'Transaksi Ditolak', 'Transaksi #7 ditolak oleh admin. Silakan hubungi admin untuk informasi lebih lanjut.', 'error', '{\"transaction_id\":7,\"old_status\":\"success\",\"new_status\":\"failed\",\"amount\":\"1000000.00\",\"commission_amount\":\"10000.00\"}', NULL, '2026-04-01 08:15:40', '2026-04-01 08:15:40'),
(121, 27, 'Transaksi Berhasil', 'Transaksi #8 telah berhasil disetujui. Komisi sebesar Rp 100 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":8,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"10000.00\",\"commission_amount\":\"100.00\"}', NULL, '2026-04-02 05:49:06', '2026-04-02 05:49:06'),
(125, 24, 'Transaksi Ditolak', 'Transaksi #11 ditolak oleh admin. Silakan hubungi admin untuk informasi lebih lanjut.', 'error', '{\"transaction_id\":11,\"old_status\":\"pending\",\"new_status\":\"failed\",\"amount\":\"20000.00\",\"commission_amount\":\"200.00\"}', NULL, '2026-04-06 11:58:48', '2026-04-06 11:58:48'),
(126, 24, 'Transaksi Berhasil', 'Transaksi #12 telah berhasil disetujui. Komisi sebesar Rp 210 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":12,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"21000.00\",\"commission_amount\":\"210.00\"}', NULL, '2026-04-06 12:03:18', '2026-04-06 12:03:18'),
(128, 23, 'Transaksi Ditolak', 'Transaksi #13 ditolak oleh admin. Silakan hubungi admin untuk informasi lebih lanjut.', 'error', '{\"transaction_id\":13,\"old_status\":\"process\",\"new_status\":\"failed\",\"amount\":\"0.00\",\"commission_amount\":\"0.00\"}', NULL, '2026-04-06 15:04:35', '2026-04-06 15:04:35'),
(129, 24, 'Transaksi Berhasil', 'Transaksi #17 telah berhasil disetujui. Komisi sebesar Rp 300 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":17,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"30000.00\",\"commission_amount\":\"300.00\"}', NULL, '2026-04-07 04:47:15', '2026-04-07 04:47:15'),
(130, 17, 'Transaksi Berhasil', 'Transaksi #18 telah berhasil disetujui. Komisi sebesar Rp 300 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":18,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"30000.00\",\"commission_amount\":\"300.00\"}', NULL, '2026-04-07 06:18:50', '2026-04-07 06:18:50'),
(131, 23, 'Transaksi Berhasil', 'Transaksi #19 telah berhasil disetujui. Komisi sebesar Rp 300 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":19,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"30000.00\",\"commission_amount\":\"300.00\"}', NULL, '2026-04-07 06:53:05', '2026-04-07 06:53:05'),
(132, 33, 'Selamat Datang di Rigel Agency!', 'Terima kasih telah bergabung dengan Rigel Agency. Mulai jual produk dan dapatkan komisi menarik!', 'success', '{\"type\":\"welcome\"}', NULL, '2026-04-07 08:58:38', '2026-04-07 08:58:38'),
(133, 33, 'Transaksi Berhasil', 'Transaksi #20 telah berhasil disetujui. Komisi sebesar Rp 2.000 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":20,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"200000.00\",\"commission_amount\":\"2000.00\"}', NULL, '2026-04-07 09:00:18', '2026-04-07 09:00:18'),
(134, 33, 'Transaksi Berhasil', 'Transaksi #21 telah berhasil disetujui. Komisi sebesar Rp 3.000 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":21,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"300000.00\",\"commission_amount\":\"3000.00\"}', NULL, '2026-04-07 09:00:35', '2026-04-07 09:00:35'),
(135, 33, 'Transaksi Berhasil', 'Transaksi #22 telah berhasil disetujui. Komisi sebesar Rp 2.000 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":22,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"200000.00\",\"commission_amount\":\"2000.00\"}', NULL, '2026-04-07 09:01:00', '2026-04-07 09:01:00'),
(136, 33, 'Transaksi Berhasil', 'Transaksi #23 telah berhasil disetujui. Komisi sebesar Rp 5.000 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":23,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"500000.00\",\"commission_amount\":\"5000.00\"}', NULL, '2026-04-07 09:01:18', '2026-04-07 09:01:18'),
(137, 33, 'Transaksi Berhasil', 'Transaksi #24 telah berhasil disetujui. Komisi sebesar Rp 5.000 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":24,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"500000.00\",\"commission_amount\":\"5000.00\"}', NULL, '2026-04-07 09:01:40', '2026-04-07 09:01:40'),
(138, 17, 'Transaksi Berhasil', 'Transaksi #25 telah berhasil disetujui. Komisi sebesar Rp 2.250 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":25,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"225000.00\",\"commission_amount\":\"2250.00\"}', NULL, '2026-04-07 10:56:43', '2026-04-07 10:56:43'),
(139, 23, 'Transaksi Berhasil', 'Transaksi #26 telah berhasil disetujui. Komisi sebesar Rp 100 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":26,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"10000.00\",\"commission_amount\":\"100.00\"}', NULL, '2026-04-07 13:10:52', '2026-04-07 13:10:52'),
(140, 17, 'Transaksi Berhasil', 'Transaksi #27 telah berhasil disetujui. Komisi sebesar Rp 300 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":27,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"30000.00\",\"commission_amount\":\"300.00\"}', NULL, '2026-04-07 13:27:52', '2026-04-07 13:27:52'),
(141, 33, 'Transaksi Berhasil', 'Transaksi #28 telah berhasil disetujui. Komisi sebesar Rp 5.000 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":28,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"500000.00\",\"commission_amount\":\"5000.00\"}', NULL, '2026-04-07 13:58:19', '2026-04-07 13:58:19'),
(142, 33, 'Transaksi Berhasil', 'Transaksi #29 telah berhasil disetujui. Komisi sebesar Rp 400 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":29,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"40000.00\",\"commission_amount\":\"400.00\"}', NULL, '2026-04-07 14:44:39', '2026-04-07 14:44:39'),
(143, 17, 'Transaksi Berhasil', 'Transaksi #30 telah berhasil disetujui. Komisi sebesar Rp 500 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":30,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"50000.00\",\"commission_amount\":\"500.00\"}', NULL, '2026-04-07 15:30:59', '2026-04-07 15:30:59'),
(144, 23, 'Transaksi Berhasil', 'Transaksi #31 telah berhasil disetujui. Komisi sebesar Rp 1.500 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":31,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"150000.00\",\"commission_amount\":\"1500.00\"}', NULL, '2026-04-07 16:38:48', '2026-04-07 16:38:48'),
(145, 23, 'Transaksi Berhasil', 'Transaksi #32 telah berhasil disetujui. Komisi sebesar Rp 1.500 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":32,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"150000.00\",\"commission_amount\":\"1500.00\"}', NULL, '2026-04-07 16:44:06', '2026-04-07 16:44:06'),
(146, 23, 'Transaksi Berhasil', 'Transaksi #33 telah berhasil disetujui. Komisi sebesar Rp 2.000 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":33,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"200000.00\",\"commission_amount\":\"2000.00\"}', NULL, '2026-04-08 01:29:38', '2026-04-08 01:29:38'),
(147, 34, 'Selamat Datang di Rigel Agency!', 'Terima kasih telah bergabung dengan Rigel Agency. Mulai jual produk dan dapatkan komisi menarik!', 'success', '{\"type\":\"welcome\"}', '2026-04-08 02:15:08', '2026-04-08 02:12:27', '2026-04-08 02:15:08'),
(148, 23, 'Transaksi Berhasil', 'Transaksi #34 telah berhasil disetujui. Komisi sebesar Rp 800 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":34,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"80000.00\",\"commission_amount\":\"800.00\"}', NULL, '2026-04-08 04:15:48', '2026-04-08 04:15:48'),
(149, 23, 'Transaksi Berhasil', 'Transaksi #35 telah berhasil disetujui. Komisi sebesar Rp 300 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":35,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"30000.00\",\"commission_amount\":\"300.00\"}', NULL, '2026-04-08 11:46:52', '2026-04-08 11:46:52'),
(150, 14, 'Transaksi Berhasil', 'Transaksi #36 telah berhasil disetujui. Komisi sebesar Rp 500 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":36,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"50000.00\",\"commission_amount\":\"500.00\"}', NULL, '2026-04-08 13:53:49', '2026-04-08 13:53:49'),
(151, 33, 'Transaksi Berhasil', 'Transaksi #37 telah berhasil disetujui. Komisi sebesar Rp 300 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":37,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"30000.00\",\"commission_amount\":\"300.00\"}', NULL, '2026-04-08 16:08:39', '2026-04-08 16:08:39'),
(152, 23, 'Transaksi Berhasil', 'Transaksi #38 telah berhasil disetujui. Komisi sebesar Rp 100 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":38,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"10000.00\",\"commission_amount\":\"100.00\"}', NULL, '2026-04-08 16:11:53', '2026-04-08 16:11:53'),
(153, 17, 'Transaksi Berhasil', 'Transaksi #39 telah berhasil disetujui. Komisi sebesar Rp 300 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":39,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"30000.00\",\"commission_amount\":\"300.00\"}', NULL, '2026-04-09 00:39:19', '2026-04-09 00:39:19'),
(154, 23, 'Transaksi Berhasil', 'Transaksi #40 telah berhasil disetujui. Komisi sebesar Rp 800 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":40,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"80000.00\",\"commission_amount\":\"800.00\"}', NULL, '2026-04-09 01:33:40', '2026-04-09 01:33:40'),
(156, 33, 'Transaksi Berhasil', 'Transaksi #41 telah berhasil disetujui. Komisi sebesar Rp 300 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":41,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"30000.00\",\"commission_amount\":\"300.00\"}', NULL, '2026-04-09 05:53:21', '2026-04-09 05:53:21'),
(157, 23, 'Transaksi Berhasil', 'Transaksi #42 telah berhasil disetujui. Komisi sebesar Rp 300 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":42,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"30000.00\",\"commission_amount\":\"300.00\"}', NULL, '2026-04-09 06:53:52', '2026-04-09 06:53:52'),
(158, 14, 'Transaksi Berhasil', 'Transaksi #43 telah berhasil disetujui. Komisi sebesar Rp 500 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":43,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"50000.00\",\"commission_amount\":\"500.00\"}', NULL, '2026-04-09 11:10:57', '2026-04-09 11:10:57'),
(159, 36, 'Selamat Datang di Rigel Agency!', 'Terima kasih telah bergabung dengan Rigel Agency. Mulai jual produk dan dapatkan komisi menarik!', 'success', '{\"type\":\"welcome\"}', NULL, '2026-04-09 11:47:10', '2026-04-09 11:47:10'),
(160, 33, 'Transaksi Berhasil', 'Transaksi #44 telah berhasil disetujui. Komisi sebesar Rp 300 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":44,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"30000.00\",\"commission_amount\":\"300.00\"}', NULL, '2026-04-09 12:17:52', '2026-04-09 12:17:52'),
(161, 17, 'Transaksi Berhasil', 'Transaksi #45 telah berhasil disetujui. Komisi sebesar Rp 2.900 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":45,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"290000.00\",\"commission_amount\":\"2900.00\"}', NULL, '2026-04-09 12:21:29', '2026-04-09 12:21:29'),
(162, 23, 'Transaksi Berhasil', 'Transaksi #46 telah berhasil disetujui. Komisi sebesar Rp 5.000 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":46,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"500000.00\",\"commission_amount\":\"5000.00\"}', NULL, '2026-04-09 12:34:44', '2026-04-09 12:34:44'),
(163, 17, 'Transaksi Berhasil', 'Transaksi #47 telah berhasil disetujui. Komisi sebesar Rp 1.000 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":47,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"100000.00\",\"commission_amount\":\"1000.00\"}', NULL, '2026-04-09 12:48:17', '2026-04-09 12:48:17'),
(164, 36, 'Transaksi Berhasil', 'Transaksi #48 telah berhasil disetujui. Komisi sebesar Rp 3.400 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":48,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"340000.00\",\"commission_amount\":\"3400.00\"}', NULL, '2026-04-09 13:09:33', '2026-04-09 13:09:33'),
(165, 33, 'Transaksi Berhasil', 'Transaksi #49 telah berhasil disetujui. Komisi sebesar Rp 900 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":49,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"90000.00\",\"commission_amount\":\"900.00\"}', NULL, '2026-04-09 13:18:40', '2026-04-09 13:18:40'),
(166, 33, 'Transaksi Berhasil', 'Transaksi #50 telah berhasil disetujui. Komisi sebesar Rp 1.000 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":50,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"100000.00\",\"commission_amount\":\"1000.00\"}', NULL, '2026-04-09 13:26:15', '2026-04-09 13:26:15'),
(167, 23, 'Transaksi Berhasil', 'Transaksi #51 telah berhasil disetujui. Komisi sebesar Rp 100 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":51,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"10000.00\",\"commission_amount\":\"100.00\"}', NULL, '2026-04-09 15:59:21', '2026-04-09 15:59:21'),
(168, 23, 'Transaksi Berhasil', 'Transaksi #52 telah berhasil disetujui. Komisi sebesar Rp 3.000 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":52,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"300000.00\",\"commission_amount\":\"3000.00\"}', NULL, '2026-04-09 17:41:46', '2026-04-09 17:41:46'),
(169, 23, 'Transaksi Berhasil', 'Transaksi #53 telah berhasil disetujui. Komisi sebesar Rp 500 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":53,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"50000.00\",\"commission_amount\":\"500.00\"}', NULL, '2026-04-09 17:52:35', '2026-04-09 17:52:35'),
(170, 17, 'Transaksi Berhasil', 'Transaksi #54 telah berhasil disetujui. Komisi sebesar Rp 1.000 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":54,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"100000.00\",\"commission_amount\":\"1000.00\"}', NULL, '2026-04-10 03:11:05', '2026-04-10 03:11:05'),
(171, 17, 'Transaksi Berhasil', 'Transaksi #55 telah berhasil disetujui. Komisi sebesar Rp 1.000 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":55,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"100000.00\",\"commission_amount\":\"1000.00\"}', NULL, '2026-04-10 05:22:20', '2026-04-10 05:22:20'),
(172, 33, 'Transaksi Berhasil', 'Transaksi #56 telah berhasil disetujui. Komisi sebesar Rp 300 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":56,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"30000.00\",\"commission_amount\":\"300.00\"}', NULL, '2026-04-10 12:06:09', '2026-04-10 12:06:09'),
(173, 14, 'Transaksi Berhasil', 'Transaksi #58 telah berhasil disetujui. Komisi sebesar Rp 1.000 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":58,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"100000.00\",\"commission_amount\":\"1000.00\"}', NULL, '2026-04-10 14:18:12', '2026-04-10 14:18:12'),
(174, 14, 'Transaksi Ditolak', 'Transaksi #58 ditolak oleh admin. Silakan hubungi admin untuk informasi lebih lanjut.', 'error', '{\"transaction_id\":58,\"old_status\":\"success\",\"new_status\":\"failed\",\"amount\":\"100000.00\",\"commission_amount\":\"1000.00\"}', NULL, '2026-04-10 14:18:17', '2026-04-10 14:18:17'),
(175, 14, 'Transaksi Ditolak', 'Transaksi #57 ditolak oleh admin. Silakan hubungi admin untuk informasi lebih lanjut.', 'error', '{\"transaction_id\":57,\"old_status\":\"pending\",\"new_status\":\"failed\",\"amount\":\"100000.00\",\"commission_amount\":\"1000.00\"}', NULL, '2026-04-10 14:18:32', '2026-04-10 14:18:32'),
(176, 33, 'Transaksi Ditolak', 'Transaksi #59 ditolak oleh admin. Silakan hubungi admin untuk informasi lebih lanjut.', 'error', '{\"transaction_id\":59,\"old_status\":\"pending\",\"new_status\":\"failed\",\"amount\":\"300000.00\",\"commission_amount\":\"3000.00\"}', NULL, '2026-04-10 16:07:58', '2026-04-10 16:07:58'),
(177, 33, 'Transaksi Berhasil', 'Transaksi #59 telah berhasil disetujui. Komisi sebesar Rp 3.000 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":59,\"old_status\":\"failed\",\"new_status\":\"success\",\"amount\":\"300000.00\",\"commission_amount\":\"3000.00\"}', NULL, '2026-04-10 16:08:47', '2026-04-10 16:08:47'),
(178, 36, 'Transaksi Berhasil', 'Transaksi #60 telah berhasil disetujui. Komisi sebesar Rp 1.000 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":60,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"100000.00\",\"commission_amount\":\"1000.00\"}', NULL, '2026-04-11 01:25:04', '2026-04-11 01:25:04'),
(179, 24, 'Transaksi Berhasil', 'Transaksi #61 telah berhasil disetujui. Komisi sebesar Rp 200 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":61,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"20000.00\",\"commission_amount\":\"200.00\"}', NULL, '2026-04-11 04:34:50', '2026-04-11 04:34:50'),
(180, 33, 'Transaksi Berhasil', 'Transaksi #62 telah berhasil disetujui. Komisi sebesar Rp 700 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":62,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"70000.00\",\"commission_amount\":\"700.00\"}', NULL, '2026-04-11 07:15:40', '2026-04-11 07:15:40'),
(181, 14, 'Transaksi Berhasil', 'Transaksi #63 telah berhasil disetujui. Komisi sebesar Rp 500 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":63,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"50000.00\",\"commission_amount\":\"500.00\"}', NULL, '2026-04-11 09:06:14', '2026-04-11 09:06:14'),
(182, 17, 'Transaksi Berhasil', 'Transaksi #64 telah berhasil disetujui. Komisi sebesar Rp 2.000 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":64,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"200000.00\",\"commission_amount\":\"2000.00\"}', NULL, '2026-04-11 10:02:19', '2026-04-11 10:02:19'),
(183, 14, 'Transaksi Berhasil', 'Transaksi #58 telah berhasil disetujui. Komisi sebesar Rp 1.000 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":58,\"old_status\":\"failed\",\"new_status\":\"success\",\"amount\":\"100000.00\",\"commission_amount\":\"1000.00\"}', NULL, '2026-04-11 10:20:43', '2026-04-11 10:20:43'),
(184, 14, 'Transaksi Berhasil', 'Transaksi #58 telah berhasil disetujui. Komisi sebesar Rp 1.000 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":58,\"old_status\":\"failed\",\"new_status\":\"success\",\"amount\":\"100000.00\",\"commission_amount\":\"1000.00\"}', NULL, '2026-04-11 10:20:43', '2026-04-11 10:20:43'),
(185, 17, 'Transaksi Berhasil', 'Transaksi #65 telah berhasil disetujui. Komisi sebesar Rp 1.000 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":65,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"100000.00\",\"commission_amount\":\"1000.00\"}', NULL, '2026-04-11 13:29:02', '2026-04-11 13:29:02'),
(186, 23, 'Transaksi Berhasil', 'Transaksi #66 telah berhasil disetujui. Komisi sebesar Rp 100 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":66,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"10000.00\",\"commission_amount\":\"100.00\"}', NULL, '2026-04-11 13:38:36', '2026-04-11 13:38:36'),
(187, 33, 'Transaksi Berhasil', 'Transaksi #67 telah berhasil disetujui. Komisi sebesar Rp 500 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":67,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"50000.00\",\"commission_amount\":\"500.00\"}', NULL, '2026-04-11 13:45:40', '2026-04-11 13:45:40'),
(188, 33, 'Transaksi Berhasil', 'Transaksi #68 telah berhasil disetujui. Komisi sebesar Rp 500 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":68,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"50000.00\",\"commission_amount\":\"500.00\"}', NULL, '2026-04-11 14:02:08', '2026-04-11 14:02:08'),
(189, 23, 'Transaksi Berhasil', 'Transaksi #69 telah berhasil disetujui. Komisi sebesar Rp 100 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":69,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"10000.00\",\"commission_amount\":\"100.00\"}', NULL, '2026-04-11 14:34:26', '2026-04-11 14:34:26'),
(190, 33, 'Transaksi Berhasil', 'Transaksi #70 telah berhasil disetujui. Komisi sebesar Rp 500 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":70,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"50000.00\",\"commission_amount\":\"500.00\"}', NULL, '2026-04-11 14:40:21', '2026-04-11 14:40:21'),
(191, 23, 'Transaksi Berhasil', 'Transaksi #71 telah berhasil disetujui. Komisi sebesar Rp 300 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":71,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"30000.00\",\"commission_amount\":\"300.00\"}', NULL, '2026-04-11 16:09:06', '2026-04-11 16:09:06'),
(192, 36, 'Transaksi Berhasil', 'Transaksi #72 telah berhasil disetujui. Komisi sebesar Rp 500 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":72,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"50000.00\",\"commission_amount\":\"500.00\"}', NULL, '2026-04-12 08:24:39', '2026-04-12 08:24:39'),
(193, 36, 'Transaksi Berhasil', 'Transaksi #73 telah berhasil disetujui. Komisi sebesar Rp 500 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":73,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"50000.00\",\"commission_amount\":\"500.00\"}', NULL, '2026-04-12 11:15:20', '2026-04-12 11:15:20'),
(194, 23, 'Transaksi Berhasil', 'Transaksi #74 telah berhasil disetujui. Komisi sebesar Rp 300 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":74,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"30000.00\",\"commission_amount\":\"300.00\"}', NULL, '2026-04-12 12:00:55', '2026-04-12 12:00:55'),
(195, 36, 'Transaksi Berhasil', 'Transaksi #76 telah berhasil disetujui. Komisi sebesar Rp 100 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":76,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"10000.00\",\"commission_amount\":\"100.00\"}', NULL, '2026-04-13 11:04:40', '2026-04-13 11:04:40'),
(196, 17, 'Transaksi Berhasil', 'Transaksi #77 telah berhasil disetujui. Komisi sebesar Rp 500 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":77,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"50000.00\",\"commission_amount\":\"500.00\"}', NULL, '2026-04-13 13:11:38', '2026-04-13 13:11:38'),
(197, 17, 'Transaksi Berhasil', 'Transaksi #78 telah berhasil disetujui. Komisi sebesar Rp 500 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":78,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"50000.00\",\"commission_amount\":\"500.00\"}', NULL, '2026-04-13 13:40:36', '2026-04-13 13:40:36'),
(198, 36, 'Transaksi Ditolak', 'Transaksi #75 ditolak oleh admin. Silakan hubungi admin untuk informasi lebih lanjut.', 'error', '{\"transaction_id\":75,\"old_status\":\"pending\",\"new_status\":\"failed\",\"amount\":\"5400.00\",\"commission_amount\":\"0.00\"}', NULL, '2026-04-14 06:15:29', '2026-04-14 06:15:29'),
(199, 26, 'Transaksi Berhasil', 'Transaksi #79 telah berhasil disetujui. Komisi sebesar Rp 1.000 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":79,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"100000.00\",\"commission_amount\":\"1000.00\"}', NULL, '2026-04-14 06:22:04', '2026-04-14 06:22:04'),
(200, 26, 'Transaksi Ditolak', 'Transaksi #80 ditolak oleh admin. Silakan hubungi admin untuk informasi lebih lanjut.', 'error', '{\"transaction_id\":80,\"old_status\":\"pending\",\"new_status\":\"failed\",\"amount\":\"1000.00\",\"commission_amount\":\"0.00\"}', NULL, '2026-04-14 06:23:30', '2026-04-14 06:23:30'),
(201, 26, 'Transaksi Ditolak', 'Transaksi #81 ditolak oleh admin. Silakan hubungi admin untuk informasi lebih lanjut.', 'error', '{\"transaction_id\":81,\"old_status\":\"pending\",\"new_status\":\"failed\",\"amount\":\"1000.00\",\"commission_amount\":\"0.00\"}', NULL, '2026-04-14 06:32:01', '2026-04-14 06:32:01'),
(202, 26, 'Transaksi Ditolak', 'Transaksi #82 ditolak oleh admin. Silakan hubungi admin untuk informasi lebih lanjut.', 'error', '{\"transaction_id\":82,\"old_status\":\"pending\",\"new_status\":\"failed\",\"amount\":\"1000.00\",\"commission_amount\":\"0.00\"}', NULL, '2026-04-14 06:34:28', '2026-04-14 06:34:28'),
(203, 37, 'Selamat Datang di Rigel Agency!', 'Terima kasih telah bergabung dengan Rigel Agency. Mulai jual produk dan dapatkan komisi menarik!', 'success', '{\"type\":\"welcome\"}', NULL, '2026-04-14 08:52:53', '2026-04-14 08:52:53'),
(204, 38, 'Selamat Datang di Rigel Agency!', 'Terima kasih telah bergabung dengan Rigel Agency. Mulai jual produk dan dapatkan komisi menarik!', 'success', '{\"type\":\"welcome\"}', NULL, '2026-04-14 09:17:00', '2026-04-14 09:17:00'),
(205, 37, 'Permintaan Penarikan Komisi', 'Permintaan penarikan komisi sebesar Rp 1.000 sedang diproses.', 'info', '{\"type\":\"withdrawal\",\"amount\":1000,\"status\":\"pending\"}', NULL, '2026-04-14 09:26:19', '2026-04-14 09:26:19'),
(206, 37, 'Penarikan Komisi Ditolak', 'Penarikan komisi sebesar Rp 1.000 ditolak. Silakan hubungi admin.', 'error', '{\"type\":\"withdrawal\",\"amount\":\"1000.00\",\"status\":\"failed\"}', NULL, '2026-04-15 09:10:32', '2026-04-15 09:10:32'),
(214, 43, 'Selamat Datang di Rigel Agency!', 'Terima kasih telah bergabung dengan Rigel Agency. Mulai jual produk dan dapatkan komisi menarik!', 'success', '{\"type\":\"welcome\"}', NULL, '2026-04-18 08:01:06', '2026-04-18 08:01:06'),
(215, 43, 'Transaksi Ditolak', 'Transaksi #85 ditolak oleh admin. Silakan hubungi admin untuk informasi lebih lanjut.', 'error', '{\"transaction_id\":85,\"old_status\":\"pending\",\"new_status\":\"failed\",\"amount\":\"10000000.00\",\"commission_amount\":\"100000.00\"}', NULL, '2026-04-20 03:33:45', '2026-04-20 03:33:45'),
(216, 43, 'Transaksi Berhasil', 'Transaksi #86 telah berhasil disetujui. Komisi sebesar Rp 5.000.000 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":86,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"500000000.00\",\"commission_amount\":\"5000000.00\"}', NULL, '2026-04-20 03:34:44', '2026-04-20 03:34:44'),
(217, 43, 'Penarikan Komisi Ditolak', 'Penarikan komisi sebesar Rp 5.000.000 ditolak. Silakan hubungi admin.', 'error', '{\"type\":\"withdrawal\",\"amount\":\"5000000.00\",\"status\":\"failed\"}', NULL, '2026-04-20 03:43:30', '2026-04-20 03:43:30'),
(218, 44, 'Selamat Datang di Rigel Agency!', 'Terima kasih telah bergabung dengan Rigel Agency. Mulai jual produk dan dapatkan komisi menarik!', 'success', '{\"type\":\"welcome\"}', NULL, '2026-04-20 03:54:39', '2026-04-20 03:54:39'),
(219, 44, 'Penarikan Komisi Ditolak', 'Penarikan komisi sebesar Rp 5.000.000 ditolak. Silakan hubungi admin.', 'error', '{\"type\":\"withdrawal\",\"amount\":\"5000000.00\",\"status\":\"failed\"}', NULL, '2026-04-20 03:57:34', '2026-04-20 03:57:34'),
(220, 37, 'Permintaan Penarikan Komisi', 'Permintaan penarikan komisi sebesar Rp 75.000 sedang diproses.', 'info', '{\"type\":\"withdrawal\",\"amount\":75000,\"status\":\"pending\"}', NULL, '2026-04-20 04:31:27', '2026-04-20 04:31:27'),
(221, 37, 'Penarikan Komisi Berhasil', 'Penarikan komisi sebesar Rp 75.000 telah berhasil diproses.', 'success', '{\"type\":\"withdrawal\",\"amount\":\"75000.00\",\"status\":\"success\"}', NULL, '2026-04-20 04:39:23', '2026-04-20 04:39:23'),
(222, 38, 'Transaksi Berhasil', 'Transaksi #93 telah berhasil disetujui. Komisi sebesar Rp 50.500 telah ditambahkan ke saldo Anda.', 'success', '{\"transaction_id\":93,\"old_status\":\"pending\",\"new_status\":\"success\",\"amount\":\"5050000.00\",\"commission_amount\":\"50500.00\"}', NULL, '2026-04-20 10:05:48', '2026-04-20 10:05:48'),
(223, 38, 'Penarikan Komisi Berhasil', 'Penarikan komisi sebesar Rp 25.250 telah berhasil diproses.', 'success', '{\"type\":\"withdrawal\",\"amount\":\"25250.00\",\"status\":\"success\"}', NULL, '2026-04-20 10:09:16', '2026-04-20 10:09:16'),
(224, 38, 'Penarikan Komisi Ditolak', 'Penarikan komisi sebesar Rp 6.312 ditolak. Silakan hubungi admin.', 'error', '{\"type\":\"withdrawal\",\"amount\":\"6312.00\",\"status\":\"failed\"}', NULL, '2026-04-20 10:10:18', '2026-04-20 10:10:18'),
(225, 38, 'Penarikan Komisi Ditolak', 'Penarikan komisi sebesar Rp 18.937 ditolak. Silakan hubungi admin.', 'error', '{\"type\":\"withdrawal\",\"amount\":\"18937.00\",\"status\":\"failed\"}', NULL, '2026-04-20 10:12:17', '2026-04-20 10:12:17');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_holder` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qr_code_path` text COLLATE utf8mb4_unicode_ci,
  `nmid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `service_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `name`, `type`, `account_number`, `account_holder`, `qr_code_path`, `nmid`, `is_active`, `created_at`, `updated_at`, `service_id`, `user_id`) VALUES
(7, 'Bank Central Asia', 'bank_account', '8832209936', 'Hardi Cahyadi', NULL, NULL, 1, '2026-03-25 03:26:06', '2026-03-25 03:26:06', 9, NULL),
(8, 'RIGELCOIN TOKO MAINAN', 'qris', NULL, NULL, 'uploads/images/qris/1774409226_WhatsApp Image 2026-03-25 at 10.22.50.jpeg', 'ID1025457669691', 1, '2026-03-25 03:27:06', '2026-03-25 03:27:06', 9, NULL),
(9, 'Bank Central Asia', 'bank_account', '8831342528', 'Nur Fitria Winda Lestari', NULL, NULL, 1, '2026-03-25 03:28:05', '2026-03-25 03:28:05', 7, NULL),
(10, 'RIGEL, KLP DUA', 'qris', NULL, NULL, 'uploads/images/qris/1774409316_WhatsApp Image 2026-03-25 at 10.26.29.jpeg', 'ID1024335294947', 1, '2026-03-25 03:28:36', '2026-03-25 03:28:36', 7, NULL),
(11, 'Bank Central Asia', 'bank_account', '8831701757', 'Siti Nurlela', NULL, NULL, 1, '2026-03-25 03:29:12', '2026-03-25 03:29:12', 5, NULL),
(12, 'RIGEL', 'qris', NULL, NULL, 'uploads/images/qris/1774409379_WhatsApp Image 2026-03-25 at 10.24.13.jpeg', 'ID1024333230752', 1, '2026-03-25 03:29:39', '2026-03-25 03:29:39', 5, NULL),
(13, 'Bank Central Asia', 'bank_account', '8831342528', 'Nur Fitria Winda Lestari', NULL, NULL, 1, '2026-03-25 03:32:07', '2026-03-25 03:32:07', 8, NULL),
(14, 'RIGEL, KLP DUA', 'qris', NULL, NULL, 'uploads/images/qris/1774409556_WhatsApp Image 2026-03-25 at 10.24.40.jpeg', 'ID1024335294947', 1, '2026-03-25 03:32:36', '2026-03-25 03:32:36', 8, NULL),
(15, 'Bank Central Asia', 'bank_account', '4870597610', 'Hardi Cahyadi', NULL, NULL, 1, '2026-03-25 03:33:06', '2026-03-25 03:33:06', 11, NULL),
(16, 'Mandiri', 'bank_account', '1190007508862', 'Hardi Cahyadi', NULL, NULL, 1, '2026-03-25 03:33:32', '2026-03-25 03:33:32', 11, NULL),
(17, 'BRI', 'bank_account', '078701020052536', 'Hardi Cahyadi', NULL, NULL, 1, '2026-03-25 03:33:49', '2026-03-25 03:33:49', 11, NULL),
(18, 'RIGEL, PDMNGN', 'qris', NULL, NULL, 'uploads/images/qris/1774409671_WhatsApp Image 2026-03-25 at 10.24.51.jpeg', 'ID1024359973301', 1, '2026-03-25 03:34:31', '2026-03-25 03:34:31', 11, NULL),
(19, 'Bank Central Asia', 'bank_account', '8832168709', 'Nur Fitria Winda Lestari', NULL, NULL, 1, '2026-03-25 03:35:02', '2026-03-25 03:35:02', 1, NULL),
(20, 'RIGELSTOREID MAINAN', 'qris', NULL, NULL, 'uploads/images/qris/1774409731_WhatsApp Image 2026-03-25 at 10.28.28.jpeg', 'ID1025434098022', 1, '2026-03-25 03:35:31', '2026-03-25 03:35:31', 1, NULL),
(21, 'Bank Central Asia', 'bank_account', '8831701757', 'Siti Nurlela', NULL, NULL, 1, '2026-03-25 03:35:59', '2026-03-25 03:35:59', 6, NULL),
(22, 'RIGEL TopUP', 'qris', NULL, NULL, 'uploads/images/qris/1774409799_WhatsApp Image 2026-03-25 at 10.24.33.jpeg', 'ID1024326954418', 1, '2026-03-25 03:36:39', '2026-03-25 03:36:39', 6, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sales_transactions`
--

CREATE TABLE `sales_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `commission_rate` decimal(5,2) NOT NULL DEFAULT '1.00',
  `commission_amount` decimal(15,2) NOT NULL,
  `status` enum('process','success','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'process',
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_transactions`
--

CREATE TABLE `sale_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `commission_rate` decimal(5,2) NOT NULL DEFAULT '0.00',
  `commission_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('pending','process','success','failed') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `transaction_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `proof_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id_input` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nickname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_date` timestamp NULL DEFAULT NULL,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sale_transactions`
--

INSERT INTO `sale_transactions` (`id`, `transaction_code`, `user_id`, `customer_name`, `customer_phone`, `amount`, `commission_rate`, `commission_amount`, `status`, `transaction_type`, `description`, `payment_method`, `payment_number`, `bank_name`, `account_number`, `account_name`, `whatsapp_number`, `address`, `proof_image`, `user_id_input`, `nickname`, `service_name`, `transaction_date`, `confirmed_at`, `completed_at`, `created_at`, `updated_at`, `deleted_at`, `admin_id`) VALUES
(2, 'TRX-69C37B3DD5F63', 27, '1093174054', 'Rigel', '5000.00', '1.00', '50.00', 'pending', 'topup', 'Top Up Hago - ID: 1093174054 - Nickname: Rigel', 'QRIS', 'ID1024335294947', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1774418744_WhatsApp Image 2026-03-25 at 13.05.16.jpeg', '1093174054', 'Rigel', 'Hago', NULL, NULL, NULL, '2026-03-25 06:05:49', '2026-03-25 06:05:49', NULL, NULL),
(7, 'TRX-69CCD2EF74C5D', 26, '1222', 'Layla', '1000000.00', '1.00', '10000.00', 'failed', 'topup', 'Top Up Layla - ID: 1222 - Nickname: Layla', 'QRIS', 'ID1024333230752', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775031022_WhatsApp Image 2026-03-31 at 10.28.38.jpeg', '1222', 'Layla', 'Layla', NULL, '2026-04-01 08:10:38', NULL, '2026-04-01 08:10:23', '2026-04-01 08:15:40', NULL, NULL),
(8, 'TRX-69CE023A6BE30', 27, '46627356', 'Rigel', '10000.00', '1.00', '100.00', 'success', 'topup', 'Top Up Sugo - ID: 46627356 - Nickname: Rigel', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775108666_WhatsApp Image 2026-04-02 at 12.43.57.jpeg', '46627356', 'Rigel', 'Sugo', NULL, '2026-04-02 05:49:06', '2026-04-02 05:49:06', '2026-04-02 05:44:26', '2026-04-02 05:49:06', NULL, NULL),
(11, 'TRX-69D3981F83F7E', 24, '19244639', '-', '20000.00', '1.00', '200.00', 'failed', 'topup', 'Top Up Honey Jar - ID: 19244639 - Nickname: -', 'QRIS', 'ID1025457669691', NULL, NULL, NULL, NULL, NULL, NULL, '19244639', '-', 'Honey Jar', NULL, NULL, '2026-04-06 11:58:48', '2026-04-06 11:25:19', '2026-04-06 11:58:48', NULL, NULL),
(12, 'TRX-69D3A044691BB', 24, '19244639', '-', '21000.00', '1.00', '210.00', 'success', 'topup', 'Top Up Honey Jar - ID: 19244639 - Nickname: -', 'QRIS', 'ID1025457669691', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775476804_IMG-20260406-WA0005.jpg', '19244639', '-', 'Honey Jar', NULL, '2026-04-06 12:03:17', '2026-04-06 12:03:17', '2026-04-06 12:00:04', '2026-04-06 12:03:17', NULL, NULL),
(13, 'HUNTER-69D3B0CDA046C059942242', 23, NULL, NULL, '0.00', '0.00', '0.00', 'failed', 'host_submit', 'Formulir: Belum', NULL, NULL, NULL, NULL, NULL, '6281389741131', NULL, NULL, '21232327', 'Bella', 'Honey Jar', NULL, NULL, NULL, '2026-04-06 13:10:37', '2026-04-06 15:04:35', NULL, NULL),
(17, 'TRX-69D48C059BB36', 24, '19244639', '-', '30000.00', '1.00', '300.00', 'success', 'topup', 'Top Up Honey Jar - ID: 19244639 - Nickname: -', 'QRIS', 'ID1025457669691', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775537157_IMG-20260407-WA0000.jpg', '19244639', '-', 'Honey Jar', NULL, '2026-04-07 04:47:15', '2026-04-07 04:47:15', '2026-04-07 04:45:57', '2026-04-07 04:47:15', NULL, NULL),
(18, 'TRX-69D4A14A0F439', 17, '51784950', 'Nina', '30000.00', '1.00', '300.00', 'success', 'topup', 'Top Up Sugo - ID: 51784950 - Nickname: Nina', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775542601_17755425458706019705569015981061.jpg', '51784950', 'Nina', 'Sugo', NULL, '2026-04-07 06:18:50', '2026-04-07 06:18:50', '2026-04-07 06:16:42', '2026-04-07 06:18:50', NULL, NULL),
(19, 'TRX-69D4A9987D6A9', 23, '19244639', 'VJ', '30000.00', '1.00', '300.00', 'success', 'topup', 'Top Up Honey Jar - ID: 19244639 - Nickname: VJ', 'QRIS', 'ID1025457669691', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775544728_1002362120.jpg', '19244639', 'VJ', 'Honey Jar', NULL, '2026-04-07 06:53:05', '2026-04-07 06:53:05', '2026-04-07 06:52:08', '2026-04-07 06:53:05', NULL, NULL),
(20, 'TRX-69D4C79820F8E', 33, '123', 'A', '200000.00', '1.00', '2000.00', 'success', 'topup', 'Top Up Sugo - ID: 123 - Nickname: A', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, NULL, '123', 'A', 'Sugo', NULL, '2026-04-07 09:00:18', '2026-04-07 09:00:18', '2026-04-07 09:00:08', '2026-04-07 09:00:18', NULL, NULL),
(21, 'TRX-69D4C7B0C95E1', 33, '456', 'B', '300000.00', '1.00', '3000.00', 'success', 'topup', 'Top Up Sugo - ID: 456 - Nickname: B', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, NULL, '456', 'B', 'Sugo', NULL, '2026-04-07 09:00:35', '2026-04-07 09:00:35', '2026-04-07 09:00:32', '2026-04-07 09:00:35', NULL, NULL),
(22, 'TRX-69D4C7C6C712E', 33, '789', 'C', '200000.00', '1.00', '2000.00', 'success', 'topup', 'Top Up Sugo - ID: 789 - Nickname: C', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, NULL, '789', 'C', 'Sugo', NULL, '2026-04-07 09:01:00', '2026-04-07 09:01:00', '2026-04-07 09:00:54', '2026-04-07 09:01:00', NULL, NULL),
(23, 'TRX-69D4C7DB8CBD8', 33, '159', 'D', '500000.00', '1.00', '5000.00', 'success', 'topup', 'Top Up Sugo - ID: 159 - Nickname: D', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, NULL, '159', 'D', 'Sugo', NULL, '2026-04-07 09:01:18', '2026-04-07 09:01:18', '2026-04-07 09:01:15', '2026-04-07 09:01:18', NULL, NULL),
(24, 'TRX-69D4C7F0AC04B', 33, '753', 'E', '500000.00', '1.00', '5000.00', 'success', 'topup', 'Top Up Sugo - ID: 753 - Nickname: E', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, NULL, '753', 'E', 'Sugo', NULL, '2026-04-07 09:01:40', '2026-04-07 09:01:40', '2026-04-07 09:01:36', '2026-04-07 09:01:40', NULL, NULL),
(25, 'TRX-69D4E281C8757', 17, '77492858', 'Ateujᴏʟᴀ𒆜⃝ʷᵒˡᶠ🪸', '225000.00', '1.00', '2250.00', 'success', 'topup', 'Top Up Sugo - ID: 77492858 - Nickname: Ateujᴏʟᴀ𒆜⃝ʷᵒˡᶠ🪸', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775559297_17755592643316773534530165866683.jpg', '77492858', 'Ateujᴏʟᴀ𒆜⃝ʷᵒˡᶠ🪸', 'Sugo', NULL, '2026-04-07 10:56:43', '2026-04-07 10:56:43', '2026-04-07 10:54:57', '2026-04-07 10:56:43', NULL, NULL),
(26, 'TRX-69D4FD21875EF', 23, '19252751', 'Ridzi', '10000.00', '1.00', '100.00', 'success', 'topup', 'Top Up Honey Jar - ID: 19252751 - Nickname: Ridzi', 'QRIS', 'ID1025457669691', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775566113_1002363290.jpg', '19252751', 'Ridzi', 'Honey Jar', NULL, '2026-04-07 13:10:52', '2026-04-07 13:10:52', '2026-04-07 12:48:33', '2026-04-07 13:10:52', NULL, NULL),
(27, 'TRX-69D505C798EF8', 17, '51784950', 'Nina', '30000.00', '1.00', '300.00', 'success', 'topup', 'Top Up Sugo - ID: 51784950 - Nickname: Nina', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775568327_Screenshot_20260407-202321_DANA.jpg', '51784950', 'Nina', 'Sugo', NULL, '2026-04-07 13:27:52', '2026-04-07 13:27:52', '2026-04-07 13:25:27', '2026-04-07 13:27:52', NULL, NULL),
(28, 'TRX-69D50A6371ED9', 33, '116396302', 'Aplikasi sugo', '500000.00', '1.00', '5000.00', 'success', 'topup', 'Top Up Sugo - ID: 116396302 - Nickname: Aplikasi sugo', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775569507_Screenshot_20260407-214226.png', '116396302', 'Aplikasi sugo', 'Sugo', NULL, '2026-04-07 13:58:19', '2026-04-07 13:58:19', '2026-04-07 13:45:07', '2026-04-07 13:58:19', NULL, NULL),
(29, 'TRX-69D51827367D7', 33, '213357750', 'Aplikasi sugo', '40000.00', '1.00', '400.00', 'success', 'topup', 'Top Up Sugo - ID: 213357750 - Nickname: Aplikasi sugo', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775573030_Screenshot_20260407-224204.png', '213357750', 'Aplikasi sugo', 'Sugo', NULL, '2026-04-07 14:44:39', '2026-04-07 14:44:39', '2026-04-07 14:43:51', '2026-04-07 14:44:39', NULL, NULL),
(30, 'TRX-69D523091BC84', 17, '30935021', 'Milk', '50000.00', '1.00', '500.00', 'success', 'topup', 'Top Up Sugo - ID: 30935021 - Nickname: Milk', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775575816_Screenshot_20260407-222903_DANA.jpg', '30935021', 'Milk', 'Sugo', NULL, '2026-04-07 15:30:59', '2026-04-07 15:30:59', '2026-04-07 15:30:17', '2026-04-07 15:30:59', NULL, NULL),
(31, 'TRX-69D532E0E65B9', 23, '17869115', 'Pria tampan', '150000.00', '1.00', '1500.00', 'success', 'topup', 'Top Up Honey Jar - ID: 17869115 - Nickname: Pria tampan', 'QRIS', 'ID1025457669691', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775579869_1002363711.jpg', '17869115', 'Pria tampan', 'Honey Jar', NULL, '2026-04-07 16:38:48', '2026-04-07 16:38:48', '2026-04-07 16:37:52', '2026-04-07 16:38:48', NULL, NULL),
(32, 'TRX-69D5341E85FB3', 23, '19151893', 'Pria tampan', '150000.00', '1.00', '1500.00', 'success', 'topup', 'Top Up Honey Jar - ID: 19151893 - Nickname: Pria tampan', 'QRIS', 'ID1025457669691', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775580189_1002363714.jpg', '19151893', 'Pria tampan', 'Honey Jar', NULL, '2026-04-07 16:44:06', '2026-04-07 16:44:06', '2026-04-07 16:43:10', '2026-04-07 16:44:06', NULL, NULL),
(33, 'TRX-69D5AECCCD92C', 23, '20958706', 'Jim juday', '200000.00', '1.00', '2000.00', 'success', 'topup', 'Top Up Honey Jar - ID: 20958706 - Nickname: Jim juday', 'QRIS', 'ID1025457669691', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775611596_1002363947.jpg', '20958706', 'Jim juday', 'Honey Jar', NULL, '2026-04-08 01:29:37', '2026-04-08 01:29:37', '2026-04-08 01:26:36', '2026-04-08 01:29:37', NULL, NULL),
(34, 'TRX-69D5D565CE240', 23, '17869115', 'Tal', '80000.00', '1.00', '800.00', 'success', 'topup', 'Top Up Honey Jar - ID: 17869115 - Nickname: Tal', 'QRIS', 'ID1025457669691', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775621477_1002364210.jpg', '17869115', 'Tal', 'Honey Jar', NULL, '2026-04-08 04:15:48', '2026-04-08 04:15:48', '2026-04-08 04:11:17', '2026-04-08 04:15:48', NULL, NULL),
(35, 'TRX-69D63F304719A', 23, '19244639', 'VJ', '30000.00', '1.00', '300.00', 'success', 'topup', 'Top Up Honey Jar - ID: 19244639 - Nickname: VJ', 'QRIS', 'ID1025457669691', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775648559_1002365219.jpg', '19244639', 'VJ', 'Honey Jar', NULL, '2026-04-08 11:46:52', '2026-04-08 11:46:52', '2026-04-08 11:42:40', '2026-04-08 11:46:52', NULL, NULL),
(36, 'TRX-69D65DAF28A0A', 14, '231987582', 'Inisial A', '50000.00', '1.00', '500.00', 'success', 'topup', 'Top Up Sugo - ID: 231987582 - Nickname: Inisial A', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775656366_200791.jpg', '231987582', 'Inisial A', 'Sugo', NULL, '2026-04-08 13:53:49', '2026-04-08 13:53:49', '2026-04-08 13:52:47', '2026-04-08 13:53:49', NULL, NULL),
(37, 'TRX-69D67D61D7CA3', 33, '110843652', 'Aplikasi sugo', '30000.00', '1.00', '300.00', 'success', 'topup', 'Top Up Sugo - ID: 110843652 - Nickname: Aplikasi sugo', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775664481_Screenshot_20260409-000500.png', '110843652', 'Aplikasi sugo', 'Sugo', NULL, '2026-04-08 16:08:39', '2026-04-08 16:08:39', '2026-04-08 16:08:01', '2026-04-08 16:08:39', NULL, NULL),
(38, 'TRX-69D67DF0322A0', 23, '22332392', 'Joanna', '10000.00', '1.00', '100.00', 'success', 'topup', 'Top Up Xena - ID: 22332392 - Nickname: Joanna', 'QRIS', 'ID1024326954418', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775664623_1002365658.jpg', '22332392', 'Joanna', 'Xena', NULL, '2026-04-08 16:11:53', '2026-04-08 16:11:53', '2026-04-08 16:10:24', '2026-04-08 16:11:53', NULL, NULL),
(39, 'TRX-69D6F0F580FE4', 17, '51784950', 'Nina', '30000.00', '1.00', '300.00', 'success', 'topup', 'Top Up Sugo - ID: 51784950 - Nickname: Nina', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775694068_17756940326097512282492164470283.jpg', '51784950', 'Nina', 'Sugo', NULL, '2026-04-09 00:39:19', '2026-04-09 00:39:46', '2026-04-09 00:21:09', '2026-04-09 00:39:46', NULL, NULL),
(40, 'TRX-69D701A7E654A', 23, '17869115', 'Tal', '80000.00', '1.00', '800.00', 'success', 'topup', 'Top Up Honey Jar - ID: 17869115 - Nickname: Tal', 'QRIS', 'ID1025457669691', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775698343_1002366136.jpg', '17869115', 'Tal', 'Honey Jar', NULL, '2026-04-09 01:33:40', '2026-04-09 01:33:40', '2026-04-09 01:32:23', '2026-04-09 01:33:40', NULL, NULL),
(41, 'TRX-69D73A2D90861', 33, '213357750', 'Aplikasi sugo', '30000.00', '1.00', '300.00', 'success', 'topup', 'Top Up Sugo - ID: 213357750 - Nickname: Aplikasi sugo', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775712812_Screenshot_20260409-132933.png', '213357750', 'Aplikasi sugo', 'Sugo', NULL, '2026-04-09 05:53:21', '2026-04-09 05:53:21', '2026-04-09 05:33:33', '2026-04-09 05:53:21', NULL, NULL),
(42, 'TRX-69D74CDA56478', 23, '19244639', 'VJ', '30000.00', '1.00', '300.00', 'success', 'topup', 'Top Up Honey Jar - ID: 19244639 - Nickname: VJ', 'QRIS', 'ID1025457669691', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775717594_1002366900.jpg', '19244639', 'VJ', 'Honey Jar', NULL, '2026-04-09 06:53:52', '2026-04-09 06:53:52', '2026-04-09 06:53:14', '2026-04-09 06:53:52', NULL, NULL),
(43, 'TRX-69D788A0E4AC9', 14, '231987582', 'Inisial A', '50000.00', '1.00', '500.00', 'success', 'topup', 'Top Up Sugo - ID: 231987582 - Nickname: Inisial A', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775732896_201722.jpg', '231987582', 'Inisial A', 'Sugo', NULL, '2026-04-09 11:10:57', '2026-04-09 11:10:57', '2026-04-09 11:08:16', '2026-04-09 11:10:57', NULL, NULL),
(44, 'TRX-69D7989B99BF0', 33, '213357750', 'Aplikasi sugo', '30000.00', '1.00', '300.00', 'success', 'topup', 'Top Up Sugo - ID: 213357750 - Nickname: Aplikasi sugo', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775736987_Screenshot_20260409-201419.png', '213357750', 'Aplikasi sugo', 'Sugo', NULL, '2026-04-09 12:17:51', '2026-04-09 12:17:51', '2026-04-09 12:16:27', '2026-04-09 12:17:51', NULL, NULL),
(45, 'TRX-69D799541CBD8', 17, '126181208', 'Michelle', '290000.00', '1.00', '2900.00', 'success', 'topup', 'Top Up Sugo - ID: 126181208 - Nickname: Michelle', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775737171_17757371432528927883753471653208.jpg', '126181208', 'Michelle', 'Sugo', NULL, '2026-04-09 12:21:29', '2026-04-09 12:21:29', '2026-04-09 12:19:32', '2026-04-09 12:21:29', NULL, NULL),
(46, 'TRX-69D79CB3DD353', 23, '20958706', 'Jim', '500000.00', '1.00', '5000.00', 'success', 'topup', 'Top Up Honey Jar - ID: 20958706 - Nickname: Jim', 'QRIS', 'ID1025457669691', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775738035_1002367755.jpg', '20958706', 'Jim', 'Honey Jar', NULL, '2026-04-09 12:34:44', '2026-04-09 12:34:44', '2026-04-09 12:33:55', '2026-04-09 12:34:44', NULL, NULL),
(47, 'TRX-69D79FAD42D0B', 17, '21476576', 'Guns', '100000.00', '1.00', '1000.00', 'success', 'topup', 'Top Up Sugo - ID: 21476576 - Nickname: Guns', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775738797_Screenshot_20260409-194523_DANA.jpg', '21476576', 'Guns', 'Sugo', NULL, '2026-04-09 12:48:17', '2026-04-09 12:48:17', '2026-04-09 12:46:37', '2026-04-09 12:48:17', NULL, NULL),
(48, 'TRX-69D7A46C2A5F8', 36, '116360182', 'Lc fany', '340000.00', '1.00', '3400.00', 'success', 'topup', 'Top Up Sugo - ID: 116360182 - Nickname: Lc fany', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775740011_46035.jpg', '116360182', 'Lc fany', 'Sugo', NULL, '2026-04-09 13:09:33', '2026-04-09 13:09:33', '2026-04-09 13:06:52', '2026-04-09 13:09:33', NULL, NULL),
(49, 'TRX-69D7A6B5EC8BC', 33, '11422560', 'Aplikasi sugo', '90000.00', '1.00', '900.00', 'success', 'topup', 'Top Up Sugo - ID: 11422560 - Nickname: Aplikasi sugo', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775740597_Screenshot_20260409-211337.png', '11422560', 'Aplikasi sugo', 'Sugo', NULL, '2026-04-09 13:18:40', '2026-04-09 13:18:40', '2026-04-09 13:16:37', '2026-04-09 13:18:40', NULL, NULL),
(50, 'TRX-69D7A8DD42D9B', 33, '11422560', 'Aplikasi sugo', '100000.00', '1.00', '1000.00', 'success', 'topup', 'Top Up Sugo - ID: 11422560 - Nickname: Aplikasi sugo', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775741148_Screenshot_20260409-212412.png', '11422560', 'Aplikasi sugo', 'Sugo', NULL, '2026-04-09 13:26:15', '2026-04-09 13:26:15', '2026-04-09 13:25:49', '2026-04-09 13:26:15', NULL, NULL),
(51, 'TRX-69D7CC8086732', 23, '19252751', 'Ridzi', '10000.00', '1.00', '100.00', 'success', 'topup', 'Top Up Honey Jar - ID: 19252751 - Nickname: Ridzi', 'QRIS', 'ID1025457669691', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775750272_1002368180.jpg', '19252751', 'Ridzi', 'Honey Jar', NULL, '2026-04-09 15:59:21', '2026-04-09 15:59:21', '2026-04-09 15:57:52', '2026-04-09 15:59:21', NULL, NULL),
(52, 'TRX-69D7E0E6D78E7', 23, '20958706', 'Jim juday', '300000.00', '1.00', '3000.00', 'success', 'topup', 'Top Up Honey Jar - ID: 20958706 - Nickname: Jim juday', 'QRIS', 'ID1025457669691', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775755494_1002368212.jpg', '20958706', 'Jim juday', 'Honey Jar', NULL, '2026-04-09 17:41:46', '2026-04-09 17:41:46', '2026-04-09 17:24:54', '2026-04-09 17:41:46', NULL, NULL),
(53, 'TRX-69D7E6D98D904', 23, '21778167', 'Cen', '50000.00', '1.00', '500.00', 'success', 'topup', 'Top Up Honey Jar - ID: 21778167 - Nickname: Cen', 'QRIS', 'ID1025457669691', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775757017_1002368238.jpg', '21778167', 'Cen', 'Honey Jar', NULL, '2026-04-09 17:52:35', '2026-04-09 17:52:35', '2026-04-09 17:50:17', '2026-04-09 17:52:35', NULL, NULL),
(54, 'TRX-69D869D19A3FE', 17, '126181208', 'Michelle', '100000.00', '1.00', '1000.00', 'success', 'topup', 'Top Up Sugo - ID: 126181208 - Nickname: Michelle', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775790545_Screenshot_20260410-100810_DANA.jpg', '126181208', 'Michelle', 'Sugo', NULL, '2026-04-10 03:11:05', '2026-04-10 03:11:05', '2026-04-10 03:09:05', '2026-04-10 03:11:05', NULL, NULL),
(55, 'TRX-69D887A1B9DCC', 17, '21476576', 'Guns', '100000.00', '1.00', '1000.00', 'success', 'topup', 'Top Up Sugo - ID: 21476576 - Nickname: Guns', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775798176_Screenshot_20260410-121434_DANA.jpg', '21476576', 'Guns', 'Sugo', NULL, '2026-04-10 05:22:20', '2026-04-10 05:22:20', '2026-04-10 05:16:17', '2026-04-10 05:22:20', NULL, NULL),
(56, 'TRX-69D8E6C8DE654', 33, '213357750', 'Aplikasi sugo', '30000.00', '1.00', '300.00', 'success', 'topup', 'Top Up Sugo - ID: 213357750 - Nickname: Aplikasi sugo', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775822535_Screenshot_20260410-195652.png', '213357750', 'Aplikasi sugo', 'Sugo', NULL, '2026-04-10 12:06:09', '2026-04-10 12:06:09', '2026-04-10 12:02:16', '2026-04-10 12:06:09', NULL, NULL),
(57, 'TRX-69D9062B47658', 14, '231987582', 'Inisial A', '100000.00', '1.00', '1000.00', 'failed', 'topup', 'Top Up Sugo - ID: 231987582 - Nickname: Inisial A', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775830570_203024.jpg', '231987582', 'Inisial A', 'Sugo', NULL, NULL, '2026-04-10 14:18:42', '2026-04-10 14:16:11', '2026-04-10 14:18:42', NULL, NULL),
(58, 'TRX-69D9063D00EA9', 14, '231987582', 'Inisial A', '100000.00', '1.00', '1000.00', 'success', 'topup', 'Top Up Sugo - ID: 231987582 - Nickname: Inisial A', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775830588_203024.jpg', '231987582', 'Inisial A', 'Sugo', NULL, '2026-04-10 14:18:12', '2026-04-11 10:20:43', '2026-04-10 14:16:29', '2026-04-11 10:20:43', NULL, NULL),
(59, 'TRX-69D92032CD0A5', 33, '116396302', 'Aplikasi sugo', '300000.00', '1.00', '3000.00', 'success', 'topup', 'Top Up Sugo - ID: 116396302 - Nickname: Aplikasi sugo', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775837234_Screenshot_20260411-000455.png', '116396302', 'Aplikasi sugo', 'Sugo', NULL, '2026-04-10 16:08:47', '2026-04-10 16:08:47', '2026-04-10 16:07:14', '2026-04-10 16:08:47', NULL, NULL),
(60, 'TRX-69D9A28B8C402', 36, '183346647', 'Zero', '100000.00', '1.00', '1000.00', 'success', 'topup', 'Top Up Sugo - ID: 183346647 - Nickname: Zero', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775870603_46425.jpg', '183346647', 'Zero', 'Sugo', NULL, '2026-04-11 01:25:04', '2026-04-11 01:25:04', '2026-04-11 01:23:23', '2026-04-11 01:25:04', NULL, NULL),
(61, 'TRX-69D9CF394DE39', 24, '19372183', '-', '20000.00', '1.00', '200.00', 'success', 'topup', 'Top Up Honey Jar - ID: 19372183 - Nickname: -', 'QRIS', 'ID1025457669691', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775882040_Screenshot_20260411_113247.jpg', '19372183', '-', 'Honey Jar', NULL, '2026-04-11 04:34:50', '2026-04-11 04:34:50', '2026-04-11 04:34:01', '2026-04-11 04:34:50', NULL, NULL),
(62, 'TRX-69D9F3530E552', 33, '11422560', 'Aplikasi sugo', '70000.00', '1.00', '700.00', 'success', 'topup', 'Top Up Sugo - ID: 11422560 - Nickname: Aplikasi sugo', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775891282_Screenshot_20260411-150621.png', '11422560', 'Aplikasi sugo', 'Sugo', NULL, '2026-04-11 07:15:39', '2026-04-11 07:15:39', '2026-04-11 07:08:03', '2026-04-11 07:15:39', NULL, NULL),
(63, 'TRX-69DA0E997EB2A', 14, '167157653', 'Claudia', '50000.00', '1.00', '500.00', 'success', 'topup', 'Top Up Sugo - ID: 167157653 - Nickname: Claudia', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775898265_203707.jpg', '167157653', 'Claudia', 'Sugo', NULL, '2026-04-11 09:06:14', '2026-04-11 09:06:14', '2026-04-11 09:04:25', '2026-04-11 09:06:14', NULL, NULL),
(64, 'TRX-69DA1BBFCFA1D', 17, '27437550', '😊😊', '200000.00', '1.00', '2000.00', 'success', 'topup', 'Top Up Sugo - ID: 27437550 - Nickname: 😊😊', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775901631_Screenshot_20260411-165809_DANA.jpg', '27437550', '😊😊', 'Sugo', NULL, '2026-04-11 10:02:25', '2026-04-11 10:02:25', '2026-04-11 10:00:31', '2026-04-11 10:02:25', NULL, NULL),
(65, 'TRX-69DA4C5465C2B', 17, '168793019', 'Ken', '100000.00', '1.00', '1000.00', 'success', 'topup', 'Top Up Sugo - ID: 168793019 - Nickname: Ken', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775914068_Screenshot_20260411-202614_DANA.jpg', '168793019', 'Ken', 'Sugo', NULL, '2026-04-11 13:29:24', '2026-04-11 13:29:24', '2026-04-11 13:27:48', '2026-04-11 13:29:24', NULL, NULL),
(66, 'TRX-69DA4E740D739', 23, '19252751', 'Ridzi', '10000.00', '1.00', '100.00', 'success', 'topup', 'Top Up Honey Jar - ID: 19252751 - Nickname: Ridzi', 'QRIS', 'ID1025457669691', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775914611_1002373027.jpg', '19252751', 'Ridzi', 'Honey Jar', NULL, '2026-04-11 13:38:36', '2026-04-11 13:38:36', '2026-04-11 13:36:52', '2026-04-11 13:38:36', NULL, NULL),
(67, 'TRX-69DA50446256B', 33, '11422560', 'Aplikasi sugo', '50000.00', '1.00', '500.00', 'success', 'topup', 'Top Up Sugo - ID: 11422560 - Nickname: Aplikasi sugo', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775915076_Screenshot_20260411-214225.png', '11422560', 'Aplikasi sugo', 'Sugo', NULL, '2026-04-11 13:45:40', '2026-04-11 13:45:40', '2026-04-11 13:44:36', '2026-04-11 13:45:40', NULL, NULL),
(68, 'TRX-69DA52560F82B', 33, '11422560', 'Aplikasi sugo', '50000.00', '1.00', '500.00', 'success', 'topup', 'Top Up Sugo - ID: 11422560 - Nickname: Aplikasi sugo', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775915605_Screenshot_20260411-215226.png', '11422560', 'Aplikasi sugo', 'Sugo', NULL, '2026-04-11 14:02:20', '2026-04-11 14:02:20', '2026-04-11 13:53:26', '2026-04-11 14:02:20', NULL, NULL),
(69, 'TRX-69DA593CA186D', 23, '19252751', 'Ridzi', '10000.00', '1.00', '100.00', 'success', 'topup', 'Top Up Honey Jar - ID: 19252751 - Nickname: Ridzi', 'QRIS', 'ID1025457669691', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775917372_1002373084.jpg', '19252751', 'Ridzi', 'Honey Jar', NULL, '2026-04-11 14:34:26', '2026-04-11 14:34:26', '2026-04-11 14:22:52', '2026-04-11 14:34:26', NULL, NULL),
(70, 'TRX-69DA5CBE63651', 33, '11422560', 'Aplikasi sugo', '50000.00', '1.00', '500.00', 'success', 'topup', 'Top Up Sugo - ID: 11422560 - Nickname: Aplikasi sugo', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775918270_Screenshot_20260411-223650.png', '11422560', 'Aplikasi sugo', 'Sugo', NULL, '2026-04-11 14:40:22', '2026-04-11 14:40:22', '2026-04-11 14:37:50', '2026-04-11 14:40:22', NULL, NULL),
(71, 'TRX-69DA71DEADC7A', 23, '19244639', 'VJ', '30000.00', '1.00', '300.00', 'success', 'topup', 'Top Up Honey Jar - ID: 19244639 - Nickname: VJ', 'QRIS', 'ID1025457669691', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775923678_1002373320.jpg', '19244639', 'VJ', 'Honey Jar', NULL, '2026-04-11 16:09:06', '2026-04-11 16:09:06', '2026-04-11 16:07:58', '2026-04-11 16:09:06', NULL, NULL),
(72, 'TRX-69DB569AC3FAB', 36, '183346647', 'Lc zero', '50000.00', '1.00', '500.00', 'success', 'topup', 'Top Up Sugo - ID: 183346647 - Nickname: Lc zero', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775982234_46923.jpg', '183346647', 'Lc zero', 'Sugo', NULL, '2026-04-12 08:24:39', '2026-04-12 08:24:39', '2026-04-12 08:23:54', '2026-04-12 08:24:39', NULL, NULL),
(73, 'TRX-69DB7DE34427D', 36, '116972446', '@', '50000.00', '1.00', '500.00', 'success', 'topup', 'Top Up Sugo - ID: 116972446 - Nickname: @', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775992290_46964.jpg', '116972446', '@', 'Sugo', NULL, '2026-04-12 11:15:20', '2026-04-12 11:15:20', '2026-04-12 11:11:31', '2026-04-12 11:15:20', NULL, NULL),
(74, 'TRX-69DB87D933276', 23, '19244639', 'VJ', '30000.00', '1.00', '300.00', 'success', 'topup', 'Top Up Honey Jar - ID: 19244639 - Nickname: VJ', 'QRIS', 'ID1025457669691', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1775994840_1002374424.jpg', '19244639', 'VJ', 'Honey Jar', NULL, '2026-04-12 12:00:55', '2026-04-12 12:00:55', '2026-04-12 11:54:01', '2026-04-12 12:00:55', NULL, NULL),
(75, NULL, 36, NULL, NULL, '5400.00', '0.00', '0.00', 'failed', 'withdrawal', 'Penarikan ke Gopay - 089528747960', 'Gopay', NULL, 'Gopay', '089528747960', 'Yanti', '085169878010', 'Lubuklinggau', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-13 00:33:29', '2026-04-14 06:15:29', NULL, NULL),
(76, 'TRX-69DCCD7426EDA', 36, '128934737', 'Amel', '10000.00', '1.00', '100.00', 'success', 'topup', 'Top Up Sugo - ID: 128934737 - Nickname: Amel', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1776078195_47352.jpg', '128934737', 'Amel', 'Sugo', NULL, '2026-04-13 11:04:40', '2026-04-13 11:04:40', '2026-04-13 11:03:16', '2026-04-13 11:04:40', NULL, NULL),
(77, 'TRX-69DCEB3BCDF96', 17, '188474829', 'Bryan', '50000.00', '1.00', '500.00', 'success', 'topup', 'Top Up Sugo - ID: 188474829 - Nickname: Bryan', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1776085819_Screenshot_20260413-200814_DANA.jpg', '188474829', 'Bryan', 'Sugo', NULL, '2026-04-13 13:11:38', '2026-04-13 13:11:38', '2026-04-13 13:10:19', '2026-04-13 13:11:38', NULL, NULL),
(78, 'TRX-69DCF22132C76', 17, '10459419', 'Dinda', '50000.00', '1.00', '500.00', 'success', 'topup', 'Top Up Sugo - ID: 10459419 - Nickname: Dinda', 'QRIS', 'ID1024359973301', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1776087584_Screenshot_20260413-203826_DANA.jpg', '10459419', 'Dinda', 'Sugo', NULL, '2026-04-13 13:40:36', '2026-04-13 13:40:36', '2026-04-13 13:39:45', '2026-04-13 13:40:36', NULL, NULL),
(79, 'TRX-69DDDCE93A521', 26, '123', 'tes', '100000.00', '1.00', '1000.00', 'success', 'topup', 'Top Up Voya - ID: 123 - Nickname: tes', 'Bank Central Asia', '8832168709', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1776147688_1000878078.jpg', '123', 'tes', 'Voya', NULL, '2026-04-14 06:22:04', '2026-04-14 06:22:04', '2026-04-14 06:21:29', '2026-04-14 06:22:04', NULL, NULL),
(80, NULL, 26, NULL, NULL, '1000.00', '0.00', '0.00', 'failed', 'withdrawal', 'Penarikan ke BCA - 12345678985', 'BCA', NULL, 'BCA', '12345678985', 'TESTING', '087738341453', 'lagi testing', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 06:22:42', '2026-04-14 06:23:29', NULL, NULL),
(81, NULL, 26, NULL, NULL, '1000.00', '0.00', '0.00', 'failed', 'withdrawal', 'Penarikan ke BCA - 123456789', 'BCA', NULL, 'BCA', '123456789', 'TESTING', '087738341453', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 06:24:11', '2026-04-14 06:32:00', NULL, NULL),
(82, NULL, 26, NULL, NULL, '1000.00', '0.00', '0.00', 'failed', 'withdrawal', 'Penarikan ke BCA - 123456789', 'BCA', NULL, 'BCA', '123456789', 'TESTING', '087738341453', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 06:32:35', '2026-04-14 06:34:28', NULL, NULL),
(83, NULL, 37, NULL, NULL, '1000.00', '100.00', '1000.00', 'success', 'user_onboarding_bonus', 'Bonus onboarding: user #38 (hamka@gmail.com)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 09:17:00', '2026-04-14 09:17:00', '2026-04-14 09:17:00', NULL, NULL),
(84, NULL, 37, NULL, NULL, '1000.00', '0.00', '0.00', 'failed', 'withdrawal', 'Penarikan ke esdrftgh - 345678965458', 'esdrftgh', NULL, 'esdrftgh', '345678965458', 'xdcfgvbhjn', '093893578', 'tfyguhf', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 09:26:19', '2026-04-15 09:10:32', NULL, NULL),
(85, 'TRX-69E59E7DC29F4', 43, '876543', 'iuytrewqwert', '10000000.00', '1.00', '100000.00', 'failed', 'topup', 'Top Up Voya - ID: 876543 - Nickname: iuytrewqwert', 'QRIS', 'ID1025434098022', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1776655997_logo-gmp.png', '876543', 'iuytrewqwert', 'Voya', NULL, NULL, NULL, '2026-04-20 03:33:17', '2026-04-20 03:33:45', NULL, NULL),
(86, 'TRX-69E59EBF78422', 43, '5678', 'gtuuuu', '500000000.00', '1.00', '5000000.00', 'success', 'topup', 'Top Up Voya - ID: 5678 - Nickname: gtuuuu', 'Bank Central Asia', '8832168709', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1776656063_wp8757565-formula-1-logo-wallpapers.jpg', '5678', 'gtuuuu', 'Voya', NULL, NULL, '2026-04-20 03:34:44', '2026-04-20 03:34:23', '2026-04-20 03:34:44', NULL, NULL),
(88, 'WD-OW26AGPNYM', 43, NULL, NULL, '5000000.00', '0.00', '0.00', 'failed', 'withdrawal', 'Penarikan ke kmjdsx - 890987654323456789', 'kmjdsx', NULL, 'kmjdsx', '890987654323456789', 'wertyuitre', '09876543234', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-20 03:43:30', '2026-04-20 03:38:00', '2026-04-20 03:43:30', NULL, NULL),
(89, 'SEED-U7OQM8NJLX', 44, 'seed', NULL, '5000000.00', '100.00', '5000000.00', 'success', 'user_onboarding_bonus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-20 03:54:39', '2026-04-20 03:54:39', '2026-04-20 03:54:39', NULL, NULL),
(90, 'WD-TG-FLVNQ80UTW', 44, NULL, NULL, '5000000.00', '0.00', '0.00', 'failed', 'withdrawal', 'Penarikan test via Telegram', 'BCA', NULL, 'BCA', '123', 'TG Test', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-20 03:57:34', '2026-04-20 03:54:39', '2026-04-20 03:57:34', NULL, NULL),
(91, 'ADJ-63F63DED23', 37, 'budi', NULL, '150000.00', '100.00', '150000.00', 'success', 'manual_adjustment', 'Penambahan saldo komisi manual (marketing withdraw test)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Saldo', NULL, '2026-04-20 04:30:34', '2026-04-20 04:30:34', '2026-04-20 04:30:34', '2026-04-20 04:30:34', NULL, NULL),
(92, 'WD-UCIASGMCMJ', 37, NULL, NULL, '75000.00', '0.00', '0.00', 'success', 'withdrawal', 'Penarikan ke sdfghjk - 5434567890', 'sdfghjk', NULL, 'sdfghjk', '5434567890', '2345678976', NULL, 'esdfgyhujikogd', NULL, NULL, NULL, NULL, NULL, '2026-04-20 04:39:23', '2026-04-20 04:39:23', '2026-04-20 04:31:27', '2026-04-20 04:39:23', NULL, NULL),
(93, 'TRX-69E5FA54B71AA', 38, '8765432', 'iuytrewqfghjk', '5050000.00', '1.00', '50500.00', 'success', 'topup', 'Top Up Layla - ID: 8765432 - Nickname: iuytrewqfghjk', 'QRIS', 'ID1024333230752', NULL, NULL, NULL, NULL, NULL, 'uploads/images/job-user/1776679508_wp8757565-formula-1-logo-wallpapers.jpg', '8765432', 'iuytrewqfghjk', 'Layla', NULL, NULL, '2026-04-20 10:05:48', '2026-04-20 10:05:08', '2026-04-20 10:05:48', NULL, NULL),
(94, 'WD-H1TBDLJBKP', 38, NULL, NULL, '25250.00', '0.00', '0.00', 'success', 'withdrawal', 'Penarikan ke dana - 23456789', 'dana', NULL, 'dana', '23456789', 'wertyuio', '09876543212345', NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-20 10:09:16', '2026-04-20 10:09:16', '2026-04-20 10:09:01', '2026-04-20 10:09:16', NULL, NULL),
(95, 'WD-LUSWWZVCNI', 38, NULL, NULL, '6312.00', '0.00', '0.00', 'failed', 'withdrawal', 'Penarikan ke dana - 9876543234567', 'dana', NULL, 'dana', '9876543234567', 'iuytrewertyui', '0976543211235678', 'iuytrewqdfghj', NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-20 10:10:18', '2026-04-20 10:09:59', '2026-04-20 10:10:18', NULL, NULL),
(96, 'WD-FL1PRBRAA1', 38, NULL, NULL, '18937.00', '0.00', '0.00', 'failed', 'withdrawal', 'Penarikan ke dana - 87654321234567', 'dana', NULL, 'dana', '87654321234567', '9876trewerty', '09876543223456', 'uiytrewq', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-20 10:11:55', '2026-04-20 10:12:17', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `commission_rate` decimal(5,2) NOT NULL DEFAULT '1.00' COMMENT 'Commission percentage for this service (e.g., 3.00 = 3%)',
  `whatsapp_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'WhatsApp number for this service (e.g., 6281234567890)',
  `telegram_chat_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telegram_bot_id` bigint(20) UNSIGNED DEFAULT NULL,
  `minimum_nominal` int(10) UNSIGNED NOT NULL DEFAULT '15000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `description`, `category`, `image`, `price`, `status`, `is_active`, `commission_rate`, `whatsapp_number`, `telegram_chat_id`, `telegram_bot_id`, `minimum_nominal`, `created_at`, `updated_at`) VALUES
(1, 'Voya', NULL, 'reseller_coin', 'uploads/images/application/1772444748_unnamed.png', NULL, 'active', 1, '1.00', '6285143480795', '8737945197', 1, 5000, '2026-03-02 09:45:48', '2026-03-25 04:00:52'),
(5, 'Layla', NULL, 'reseller_coin', 'uploads/images/application/1773091895_unnamed (2).png', NULL, 'active', 1, '1.00', '62885135416118', '8737945197', 1, 5000, '2026-03-09 21:31:35', '2026-04-01 06:54:00'),
(6, 'Xena', NULL, 'reseller_coin', 'uploads/images/application/1773106288_unnamed.webp', NULL, 'active', 1, '1.00', '6285143480795', '8737945197', 1, 5000, '2026-03-10 01:31:28', '2026-03-25 04:00:52'),
(7, 'Hago', NULL, 'reseller_coin', 'uploads/images/application/1773109740_hago-apk.png', NULL, 'active', 1, '1.00', '6285143480795', '7900002234', 3, 5000, '2026-03-10 02:29:00', '2026-04-01 07:27:52'),
(8, 'Papaya', NULL, 'reseller_coin', 'uploads/images/application/1773109947_papaya.png', NULL, 'active', 1, '1.00', '6285135416118', '7900002234', 3, 50000, '2026-03-10 02:32:27', '2026-03-25 04:00:52'),
(9, 'Honey Jar', NULL, 'reseller_coin', 'uploads/images/application/1773110312_Honey Jar.jpg', NULL, 'active', 1, '1.00', '62885135416118', '7900002234', 3, 10000, '2026-03-10 02:38:32', '2026-04-02 05:33:46'),
(10, 'Honey Jar', NULL, 'talent_hunter', 'uploads/images/application/1773112433_Honey Jar.jpg', NULL, 'active', 1, '1.00', '6285198640794', '8525309816', 4, 15000, '2026-03-10 03:13:53', '2026-03-25 03:23:28'),
(11, 'Sugo', NULL, 'reseller_coin', 'uploads/images/application/1773461671_sugo-app.webp', NULL, 'active', 1, '1.00', '6285124793989', '8586812807', 2, 10000, '2026-03-14 04:14:31', '2026-04-02 05:33:46');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('d7sVEEIggk4cUVyaJQDDMon4Oes4RuDTr7rIxWDh', NULL, '91.108.5.101', '', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiZXpmc0RHV2lja2wwRzI5eE9IRDdLcmJvQnJPWG8zU21CV1Q3OGxJMSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776679758),
('DDISf8NTNLfIhpeAn7f2q6skbh2fnxkIWFd4lm6f', 37, '2404:c0:ba03:1a0:88f1:cc22:6725:369', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiM2RwRm1pdWtheVE0OWpReUdjQUxqNUU2WWJmNTN2U3p6eDZSN084VCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Nzc6Imh0dHBzOi8vYWdlLWFic2VudC1yZXNjdWUtZmFudGFzdGljLnRyeWNsb3VkZmxhcmUuY29tL21hcmtldGluZy9yZXBvcnRzL3NhbGVzIjtzOjU6InJvdXRlIjtzOjIzOiJtYXJrZXRpbmcucmVwb3J0cy5zYWxlcyI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM3O30=', 1776683859),
('DxICt8RYNk2VXVlr7MJrE0CjqKI7kHhvMEhsj6sx', NULL, '91.108.5.101', '', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiQ1VOYVUyREluN2Y3aVhqcElzd0hQb3AxQlFLWENsS3NXYWVhcTIwQyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776679819),
('qSx3SPxp1bZq3mJbBYcEBNItRiaJ55RchNA8DCBX', NULL, '2404:c0:ba03:1a0:88f1:cc22:6725:369', 'curl/8.18.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZjFkWk9aQmN3UjA2d1pmOThRdzRRd1lXSldtbGNWenFPWXBqd1QxUyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NzA6Imh0dHBzOi8vYWdlLWFic2VudC1yZXNjdWUtZmFudGFzdGljLnRyeWNsb3VkZmxhcmUuY29tL3RlbGVncmFtL3dlYmhvb2siO3M6NToicm91dGUiO3M6MjE6InRlbGVncmFtLndlYmhvb2sucGluZyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776679346),
('sZBu3aPWY1vE2dHafmrHZ6KrfrXQD8GHsHEJQjwV', NULL, '2404:c0:ba03:1a0:88f1:cc22:6725:369', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoidEQ4dzJ1V2xsQWdXdHJWcWZrMW9saWgxZkx4Q3NBMDBkQkhDRjk0diI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776680009),
('TYnUelp0HWu4TqPkC2zrxK3Q2ALqE85ZJqWLkzRl', 1, '2404:c0:ba03:1a0:88f1:cc22:6725:369', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRncwVjdLOFEwZGFFdlhUQzVodmphU2QyV1BLY0RSVzQ2aWRDQktvSCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTA5OiJodHRwczovL2FnZS1hYnNlbnQtcmVzY3VlLWZhbnRhc3RpYy50cnljbG91ZGZsYXJlLmNvbS9yZXBvcnRzL3NhbGVzL2V4cG9ydC9wZGY/ZGF0ZT0yMDI2LTA0LTAxJnBlcmlvZD1tb250aGx5IjtzOjU6InJvdXRlIjtzOjIwOiJyZXBvcnRzLnNhbGVzLmV4cG9ydCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1776683709),
('ZwidGnZnXZiy0atis4kpj619jPC7VWi09DjnNJL7', NULL, '91.108.5.101', '', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiUHduOWR3ZlM2M2Zmd3lIQklWY1lPaEo2TjVoaHh0RHBpNGhaaGxFViI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776679969);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `type`, `group`, `created_at`, `updated_at`) VALUES
(1, 'whatsapp_number', '6282298461649', 'text', 'whatsapp', '2026-04-14 06:26:33', '2026-04-14 06:26:33'),
(2, 'whatsapp_message_template', 'Halo, saya membutuhkan bantuan dengan sistem Rigel Agency. Mohon bantuannya.', 'text', 'whatsapp', '2026-04-14 06:26:34', '2026-04-14 06:26:34'),
(3, 'whatsapp_wallet_template', 'Halo kak, mau nanya soal status pencairan saya', 'text', 'whatsapp', '2026-04-14 06:26:34', '2026-04-14 06:26:34'),
(4, 'whatsapp_job_template', 'Halo kak, mau nanya soal status transaksi saya', 'text', 'whatsapp', '2026-04-14 06:26:35', '2026-04-14 06:26:35');

-- --------------------------------------------------------

--
-- Table structure for table `telegram_bots`
--

CREATE TABLE `telegram_bots` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `chat_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `telegram_bots`
--

INSERT INTO `telegram_bots` (`id`, `name`, `username`, `token`, `chat_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Layla Bot', 'LaylaNotificationBot', '8679277444:AAG238dh_axeXf856duFX5-KsrLqHaWb9pY', '8601995666', 1, '2026-03-09 21:35:12', '2026-03-09 21:39:45'),
(2, 'Sugo Bot', 'RigelSugoNotifBot', '8396165707:AAFII1DRU17JBiDAXvwZeJkbvb2kFsi-q28', '8586812807', 1, '2026-03-10 02:15:06', '2026-03-10 02:15:06'),
(3, 'Timpagobot', 'Timpagobot', '8337408463:AAGYd-f02y4V16buMB39fLcSeS2EBLUwJA8', '7900002234', 1, '2026-03-10 02:28:04', '2026-03-10 02:28:04'),
(4, 'Talent Hunter Bot', 'RigelTalentHuntBot', '8491674444:AAGt1eywyxjahHwauonJzELaSVJabasH1aU', '8525309816', 1, '2026-03-10 03:10:27', '2026-03-10 03:10:27'),
(5, 'Rigel Payout Bot', 'RigelPayoutBot', '8508504609:AAHYqL5CnHSHOnRKQa9Le1XuGi_5_Yuz-h8', '6338471659', 1, '2026-04-15 09:44:14', '2026-04-15 09:44:14');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `amount` decimal(15,2) NOT NULL,
  `commission_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `marketing_owner_id` bigint(20) UNSIGNED DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Path to user avatar image',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `role`, `marketing_owner_id`, `avatar`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'admin', 'admin@gmail.com', 'admin', NULL, 'uploads/images/avatar/users/1772526154_user_avatar_1.webp', '2026-03-02 09:42:49', '$2y$12$3nelJ/cAYpGny39XcLsuP.sraS3xQ7AA4r9C1VfPQhbni4aYMNMMK', NULL, NULL, NULL, 'n6EgyCUbsaGI86RW7rtOpnG9vSjEnPvpX9Gj1JAe8QD3A6zs9wYhLGe0M2d9', '2026-03-02 09:42:50', '2026-03-03 08:22:34'),
(14, 'Ayra', NULL, 'ayra@rigelcoin.com', 'user', NULL, NULL, NULL, '$2y$12$HEVVTWjH9vytxZ8lFRQmL.1uEK11Ewf3DrbUVR46YxUg95Xddw5h.', NULL, NULL, NULL, NULL, '2026-03-25 02:56:08', '2026-03-25 07:18:04'),
(16, 'Isya marwah', 'isya', 'isya@rigelcoin.com', 'user', NULL, NULL, NULL, '$2y$12$4ubT7v5u8wg7vFxg0KCwvO0QVkYPpOjYw7VWYDdw3dhUf9HRORXzm', NULL, NULL, NULL, NULL, '2026-03-25 02:59:44', '2026-04-07 07:35:14'),
(17, 'Dinda CS', NULL, 'dindacs@rigelcoin.com', 'user', NULL, NULL, NULL, '$2y$12$t4GVIWDQDi2Ab9OEZb9YneFeNLpVgClGw7ngMs7th.0WQBRNIhKjG', NULL, NULL, NULL, NULL, '2026-03-25 03:01:01', '2026-03-25 03:01:01'),
(23, 'Joanna Kei', 'joanna', 'joanna@rigelcoin.com', 'user', NULL, 'uploads/images/avatar/1775766643_avatar_23.jpg', NULL, '$2y$12$FU7uHn8p99Et57CAlGpo6eaUl3.wuVC1yUkSbp..dnm.imXJEmWm2', NULL, NULL, NULL, NULL, '2026-03-25 03:07:40', '2026-04-09 20:31:05'),
(24, 'Ryn', NULL, 'ryn@rigelcoin.com', 'user', NULL, NULL, NULL, '$2y$12$WSHcEtvHEoi0QeTxVQoivOz18E7k58SoeMbpHoxJG6uJV93bXd.iu', NULL, NULL, NULL, NULL, '2026-03-25 03:09:03', '2026-03-25 03:09:03'),
(26, 'User', NULL, 'user@gmail.com', 'user', NULL, NULL, NULL, '$2y$12$iY18J0fA8qGdhRYunOd0XO1e5lWnlmDrw7lDZB3kK1m58G6uKVGn2', NULL, NULL, NULL, NULL, '2026-03-25 03:49:22', '2026-04-14 06:09:26'),
(27, 'Genoz', NULL, 'genoz@rigelcoin.com', 'user', NULL, NULL, NULL, '$2y$12$iGLzW1MXGscCEKMI4PK34OB2cxT4CQnEOzxgrz8Lw5ZJj49JxYheW', NULL, NULL, NULL, NULL, '2026-03-25 06:02:49', '2026-04-02 05:42:52'),
(28, 'Jessica', NULL, 'jessica@rigeladmin.com', 'admin', NULL, NULL, NULL, '$2y$12$LRaS2M65lBlirSeyEKa35Ow4pYYut9PA0HdW7DKm4jcLC3wixW1Su', NULL, NULL, NULL, NULL, '2026-03-25 07:07:04', '2026-03-25 07:07:04'),
(33, 'Lisna', NULL, 'lisna@rigelcoin.com', 'user', NULL, NULL, NULL, '$2y$12$ATmtO6D7wDsCZV6d5I4GmeXcd3j/9b9.90xM7.U.KpHfWsu8rC02a', NULL, NULL, NULL, NULL, '2026-04-07 08:58:37', '2026-04-07 08:58:37'),
(34, 'rizki@gmail.com', NULL, 'rizki@gmail.com', 'user', NULL, NULL, NULL, '$2y$12$V8RsZqOoupr6U7MHlV.bYumovguTV/33Nyca5eFesNmZkt/q6jpKO', NULL, NULL, NULL, NULL, '2026-04-08 02:12:27', '2026-04-08 02:12:27'),
(36, 'yanti', NULL, 'yanti@rigelcoin.com', 'user', NULL, NULL, NULL, '$2y$12$IRVoVXJrZmw8hMVHiiLCxuOs8Q390JSufKR.dxN4HIVjFdWJb/uSa', NULL, NULL, NULL, NULL, '2026-04-09 11:47:10', '2026-04-09 11:47:10'),
(37, 'budi', NULL, 'marketing@gmail.com', 'marketing', NULL, NULL, NULL, '$2y$12$nUArcNofdKXHZfgpMP/qZu/.GV3zFfTJtVte4Cykr76KMqTVXTL6S', NULL, NULL, NULL, NULL, '2026-04-14 08:52:53', '2026-04-14 08:52:53'),
(38, 'Naufal', NULL, 'hamka@gmail.com', 'user', 37, NULL, NULL, '$2y$12$iRo6vETCmsrcagATj66WjOc.M/1tCxQp2C86M3slKjhQo4jchmQru', NULL, NULL, NULL, NULL, '2026-04-14 09:17:00', '2026-04-20 10:04:18'),
(43, 'Naufal', NULL, 'naufal@gmail.com', 'user', 37, NULL, NULL, '$2y$12$QfJfzUlw9jVk0WOlU8KJQ.pgNMJjNp.mdGijkv3jQZitZiDRz4urG', NULL, NULL, NULL, NULL, '2026-04-18 08:01:06', '2026-04-18 08:01:06'),
(44, 'TG Test hqj9vw', 'tg_test_hqj9vw', 'tg_test_hqj9vw@example.test', 'marketing', NULL, NULL, NULL, '$2y$12$sABIjLIiGCcZq36ih1TknuMRtdXaF.cG85t.DenidHOWwRCfo.Mvu', NULL, NULL, NULL, NULL, '2026-04-20 03:54:39', '2026-04-20 03:54:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `articles_slug_unique` (`slug`),
  ADD KEY `articles_user_id_foreign` (`user_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `commissions`
--
ALTER TABLE `commissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id_period` (`user_id`,`period_date`),
  ADD KEY `idx_sale_transaction_id` (`sale_transaction_id`),
  ADD KEY `idx_withdrawn` (`withdrawn`),
  ADD KEY `idx_commissions_withdrawal_transaction_id` (`withdrawal_transaction_id`),
  ADD KEY `idx_commissions_user_withdrawn` (`user_id`,`withdrawn`),
  ADD KEY `idx_commissions_user_created_at` (`user_id`,`created_at`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `host_submissions`
--
ALTER TABLE `host_submissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `host_submissions_sale_transaction_id_unique` (`sale_transaction_id`),
  ADD KEY `host_submissions_service_id_index` (`service_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_is_active` (`is_active`),
  ADD KEY `payment_methods_service_id_foreign` (`service_id`),
  ADD KEY `payment_methods_user_id_foreign` (`user_id`);

--
-- Indexes for table `sales_transactions`
--
ALTER TABLE `sales_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_transactions_user_id_foreign` (`user_id`),
  ADD KEY `sales_transactions_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `sale_transactions`
--
ALTER TABLE `sale_transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sale_transactions_transaction_code_unique` (`transaction_code`),
  ADD KEY `sale_transactions_admin_id_foreign` (`admin_id`),
  ADD KEY `idx_user_id_status` (`user_id`,`status`),
  ADD KEY `idx_status_completed_at` (`status`,`completed_at`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_sale_transactions_type_created_at` (`transaction_type`,`created_at`),
  ADD KEY `idx_sale_transactions_user_type_created_at` (`user_id`,`transaction_type`,`created_at`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `services_is_active_index` (`is_active`),
  ADD KEY `services_telegram_bot_id_foreign` (`telegram_bot_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `telegram_bots`
--
ALTER TABLE `telegram_bots`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `telegram_bots_username_unique` (`username`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD KEY `idx_email_verified` (`email_verified_at`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `users_marketing_owner_id_foreign` (`marketing_owner_id`),
  ADD KEY `idx_users_marketing_owner_id` (`marketing_owner_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `commissions`
--
ALTER TABLE `commissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `host_submissions`
--
ALTER TABLE `host_submissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=226;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `sales_transactions`
--
ALTER TABLE `sales_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_transactions`
--
ALTER TABLE `sale_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `telegram_bots`
--
ALTER TABLE `telegram_bots`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `commissions`
--
ALTER TABLE `commissions`
  ADD CONSTRAINT `commissions_sale_transaction_id_foreign` FOREIGN KEY (`sale_transaction_id`) REFERENCES `sale_transactions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `commissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `commissions_withdrawal_transaction_id_foreign` FOREIGN KEY (`withdrawal_transaction_id`) REFERENCES `sale_transactions` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `host_submissions`
--
ALTER TABLE `host_submissions`
  ADD CONSTRAINT `host_submissions_sale_transaction_id_foreign` FOREIGN KEY (`sale_transaction_id`) REFERENCES `sale_transactions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `host_submissions_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD CONSTRAINT `payment_methods_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payment_methods_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sales_transactions`
--
ALTER TABLE `sales_transactions`
  ADD CONSTRAINT `sales_transactions_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sales_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sale_transactions`
--
ALTER TABLE `sale_transactions`
  ADD CONSTRAINT `sale_transactions_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sale_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_telegram_bot_id_foreign` FOREIGN KEY (`telegram_bot_id`) REFERENCES `telegram_bots` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_marketing_owner_id_foreign` FOREIGN KEY (`marketing_owner_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

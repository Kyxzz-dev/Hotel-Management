-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 22, 2026 at 06:36 PM
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
-- Database: `cuti_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cutis`
--

CREATE TABLE `cutis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `jenis_cuti` varchar(255) DEFAULT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `jumlah_hari` int(11) NOT NULL DEFAULT 1,
  `alasan` text NOT NULL,
  `status` enum('pending','disetujui','ditolak') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `last_day_of_work` date DEFAULT NULL,
  `first_day_of_work` date DEFAULT NULL,
  `entitlement` int(11) NOT NULL DEFAULT 0,
  `balance_before` int(11) NOT NULL DEFAULT 0,
  `request_day` int(11) NOT NULL DEFAULT 0,
  `balance_after` int(11) NOT NULL DEFAULT 0,
  `person_in_charge` varchar(255) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cutis`
--

INSERT INTO `cutis` (`id`, `user_id`, `jenis_cuti`, `tanggal_mulai`, `tanggal_selesai`, `jumlah_hari`, `alasan`, `status`, `created_at`, `updated_at`, `position`, `department`, `last_day_of_work`, `first_day_of_work`, `entitlement`, `balance_before`, `request_day`, `balance_after`, `person_in_charge`, `remarks`, `approved_at`, `rejected_at`) VALUES
(8, 14, 'Maternity', '2026-05-17', '2026-05-21', 5, 'Healing Boss', 'disetujui', '2026-05-16 11:01:46', '2026-05-16 11:04:21', 'Staff', 'Engineering', '2026-05-16', '2026-05-22', 12, 12, 5, 7, 'Agung', 'Jalan-Jalan', '2026-05-16 11:04:21', NULL),
(9, 14, 'Sick', '2026-05-17', '2026-05-17', 1, 'Berobat', 'disetujui', '2026-05-16 11:22:10', '2026-05-16 11:25:01', 'Staff', 'Engineering', '2026-05-16', '2026-05-18', 12, 7, 1, 6, 'Alip', 'Berobat', '2026-05-16 11:25:01', NULL),
(20, 14, 'Maternity', '2026-05-17', '2026-05-17', 1, 'Berobat', 'disetujui', '2026-05-17 09:27:00', '2026-05-18 09:20:40', 'Staff', 'Engineering', '2026-05-17', '2026-05-17', 12, 6, 1, 5, 'Teguh', 'Sakit', '2026-05-18 09:20:40', NULL),
(21, 14, 'Sick', '2026-05-17', '2026-05-17', 1, 'berobat', 'ditolak', '2026-05-17 09:30:54', '2026-05-18 09:20:54', 'Staff', 'Engineering', '2026-05-17', '2026-05-17', 12, 5, 1, 5, 'Teguh', 'berobat', NULL, '2026-05-18 09:20:54'),
(22, 14, 'Annual', '2026-05-17', '2026-05-17', 1, 'sakit', 'ditolak', '2026-05-17 09:35:33', '2026-05-18 09:20:50', 'Staff', 'Engineering', '2026-05-17', '2026-05-17', 12, 4, 1, 4, 'Teguh', 'sakit', NULL, '2026-05-18 09:20:50'),
(24, 14, 'Sick', '2026-05-18', '2026-05-19', 2, 'Sakit', 'ditolak', '2026-05-17 10:00:30', '2026-05-18 09:21:12', 'Staff', 'Engineering', '2026-05-16', '2026-05-20', 12, 3, 2, 3, 'Agung', 'Sakit', NULL, '2026-05-18 09:21:12'),
(25, 14, 'Annual', '2026-05-18', '2026-05-18', 1, 'Sakit', 'disetujui', '2026-05-18 09:07:53', '2026-05-18 09:21:07', 'Staff', 'Engineering', '2026-05-18', '2026-05-18', 12, 1, 1, 0, 'Agung', 'Sakit', '2026-05-18 09:21:07', NULL),
(26, 14, 'Maternity', '2026-05-18', '2026-05-18', 1, 'Berobat', 'ditolak', '2026-05-18 09:38:07', '2026-05-18 09:59:03', 'Staff', 'Engineering', '2026-05-18', '2026-05-18', 12, 4, 1, 4, 'Teguh', 'Berobat', NULL, '2026-05-18 09:59:03'),
(27, 14, 'Sick', '2026-05-19', '2026-05-19', 1, 'Berobat', 'disetujui', '2026-05-18 10:01:07', '2026-05-18 10:02:54', 'Staff', 'Engineering', '2026-05-19', '2026-05-19', 12, 4, 1, 3, 'Teguh', 'Berobat', '2026-05-18 10:02:54', NULL),
(28, 17, 'Sick', '2026-05-19', '2026-05-19', 1, 'sakit', 'ditolak', '2026-05-18 10:56:03', '2026-05-18 11:33:18', 'Staff', 'Security', '2026-05-19', '2026-05-19', 12, 12, 1, 12, 'Agung', 'sakit', NULL, '2026-05-18 11:33:18'),
(29, 17, 'Annual', '2026-05-19', '2026-05-21', 3, 'Healing', 'disetujui', '2026-05-18 11:31:46', '2026-05-18 11:33:24', 'Staff', 'Security', '2026-05-19', '2026-05-19', 4, 3, 3, 0, 'Teguh', 'Healing', '2026-05-18 11:33:24', NULL),
(30, 17, 'Annual', '2026-05-21', '2026-05-21', 1, 'Berobat', 'ditolak', '2026-05-21 03:05:59', '2026-05-21 03:20:53', 'Staff', 'Security', '2026-05-20', '2026-05-22', 12, 9, 1, 9, 'Teguh', 'Berobat', NULL, '2026-05-21 03:20:53'),
(31, 17, 'Annual', '2026-05-23', '2026-05-23', 1, 'Sakit', 'disetujui', '2026-05-21 03:09:25', '2026-05-21 03:20:51', 'Staff', 'Security', '2026-05-22', '2026-05-24', 12, 8, 1, 7, 'Agung', 'Sakit', '2026-05-21 03:20:51', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Sales & Marketing', 1, '2026-05-17 00:27:36', '2026-05-17 00:27:36'),
(2, 'Finance', 1, '2026-05-17 00:27:36', '2026-05-17 00:27:36'),
(3, 'Front Office', 1, '2026-05-17 00:27:36', '2026-05-17 00:27:36'),
(4, 'Food & Beverage Departement', 1, '2026-05-17 00:27:36', '2026-05-17 00:27:36'),
(5, 'Housekeeping', 1, '2026-05-17 00:27:36', '2026-05-17 00:27:36'),
(6, 'Engineering', 1, '2026-05-17 00:27:36', '2026-05-17 00:27:36'),
(7, 'Wellness', 1, '2026-05-17 00:27:36', '2026-05-17 00:27:36'),
(8, 'Security', 1, '2026-05-17 00:27:36', '2026-05-17 00:27:36'),
(9, 'People & Culture', 1, '2026-05-17 00:27:36', '2026-05-17 00:27:36');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_05_14_164824_add_role_to_users_table', 1),
(6, '2025_05_14_184926_create_pegawais_table', 1),
(7, '2025_05_14_202637_create_cutis_table', 1),
(8, '2026_05_16_000000_update_roles_to_four_role_workflow', 2),
(9, '2026_05_16_165651_add_manual_form_fields_to_cutis_table', 3),
(10, '2026_05_16_173306_add_jenis_cuti_and_jumlah_hari_to_cutis_table', 4),
(11, '2026_05_17_000000_add_position_department_to_users_table', 5),
(12, '2026_05_17_010000_create_departments_and_positions_tables', 6),
(13, '2026_05_17_020000_limit_positions_to_main_roles', 7),
(14, '2026_05_18_000000_add_jabatan_to_users_table', 8),
(15, '2026_05_18_030000_add_notification_seen_at_to_users_table', 9),
(16, '2026_05_19_000000_add_tanggal_masuk_to_users_table', 9);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pegawais`
--

CREATE TABLE `pegawais` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`id`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'HRD', 1, '2026-05-17 01:18:41', '2026-05-17 01:18:41'),
(2, 'Head Department', 1, '2026-05-17 01:18:41', '2026-05-17 01:18:41'),
(3, 'General Manager', 1, '2026-05-17 01:18:41', '2026-05-17 01:18:41'),
(4, 'Staff', 1, '2026-05-17 01:18:41', '2026-05-17 01:18:41');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `tanggal_masuk` date DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'admin',
  `position` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `jabatan` varchar(255) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `notification_seen_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `tanggal_masuk`, `email_verified_at`, `password`, `role`, `position`, `department`, `jabatan`, `tanggal_lahir`, `jenis_kelamin`, `remember_token`, `notification_seen_at`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@gmail.com', NULL, NULL, '$2y$10$uXeJhVwyOMIhym.sVIzaquiau6fwPkuFlf3LCpGvPeBA3P6TEwV5q', 'gm', 'General Manager', 'Finance', NULL, '1990-01-01', 'L', NULL, NULL, '2026-05-16 01:52:15', '2026-05-21 04:12:00'),
(10, 'Pablo', 'hrd@cuti.com', NULL, NULL, '$2y$10$pv22pd4PC.FaUUW242DRROzfMPQ8w98YbsT1Ag0Mhwp3dOq.7IWNK', 'hrd', 'HRD', 'People & Culture', NULL, '1990-01-01', 'L', NULL, NULL, '2026-05-16 09:28:15', '2026-05-21 04:11:30'),
(11, 'Berlin', 'destaauliaputri57@gmail.com', NULL, NULL, '$2y$10$17RScDEOfqVe.DRm/G5qIOUiuAugc4GiJq0eFCdrzr/abPzoUZjn.', 'head_department', 'Head Department', 'Housekeeping', NULL, '1990-01-01', 'L', NULL, NULL, '2026-05-16 09:28:15', '2026-05-21 04:25:46'),
(12, 'Tokyo', 'feriferdinann@gmail.com', NULL, NULL, '$2y$10$MPYl1kXpJJ87M1/1rmOSGeF/oKDn0L7DAMlbQGRYF44Jx/NzOKkiG', 'gm', 'General Manager', 'People & Culture', NULL, '1990-01-01', 'L', NULL, NULL, '2026-05-16 09:28:15', '2026-05-21 04:12:59'),
(14, 'Renaldy', 'aldy@gmail.com', NULL, NULL, '$2y$10$pJVjYG.hv3RCoQ.cVrMG8.zo2njVOVfu1Z/rQJtk2SHdrndtlJbmC', 'staff', 'Staff', 'Engineering', 'Supervisor', '2016-03-17', 'L', NULL, NULL, '2026-05-16 10:59:12', '2026-05-18 09:06:14'),
(16, 'Renaldy Syahputra', 'aldysyahputra@gmail.com', '2026-02-17', NULL, '$2y$10$Y7eScE3XD.5xIc4lRxLHMuPb884cbzzcaMF4swTMdK4I7ozkA87Jm', 'staff', 'Staff', 'Security', 'Supervisor', '2026-05-18', 'L', NULL, NULL, '2026-05-17 11:19:40', '2026-05-21 04:16:39'),
(17, 'kasavana', 'kasavana@gmail.com', '2025-05-18', NULL, '$2y$10$s1RYPHKVpL3sYNs4MPSXjuQyYOFEOjX4snfGRTLqIVCdrIBOhTZc2', 'staff', 'Staff', 'Housekeeping', 'Laundry Linen', '2005-08-19', 'L', NULL, NULL, '2026-05-18 10:55:13', '2026-05-21 04:43:43'),
(18, 'rohib', 'rohib@gmail.com', '2026-04-18', NULL, '$2y$10$gbj.wTQ8l1/hfdt5x9jKTOZKPOPjXOhk4JD9TQoRT3CqJ0ndWfCvK', 'staff', 'Staff', 'Finance', 'Galon', '2005-02-19', 'L', NULL, NULL, '2026-05-18 11:28:10', '2026-05-21 04:15:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cutis`
--
ALTER TABLE `cutis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cutis_user_id_foreign` (`user_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `departments_name_unique` (`name`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `pegawais`
--
ALTER TABLE `pegawais`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pegawais_email_unique` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `positions_name_unique` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cutis`
--
ALTER TABLE `cutis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `pegawais`
--
ALTER TABLE `pegawais`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cutis`
--
ALTER TABLE `cutis`
  ADD CONSTRAINT `cutis_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

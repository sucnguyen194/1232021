-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost:3306
-- Thời gian đã tạo: Th3 13, 2021 lúc 03:26 AM
-- Phiên bản máy phục vụ: 5.7.24
-- Phiên bản PHP: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `chuyennhathanhtai`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `agency_id` int(11) NOT NULL DEFAULT '0',
  `amount` bigint(20) NOT NULL,
  `balance` bigint(20) NOT NULL,
  `source_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source_id` bigint(20) UNSIGNED DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `note` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `agency_id`, `amount`, `balance`, `source_type`, `source_id`, `admin_id`, `note`, `created_at`, `updated_at`) VALUES
(1, 0, 2, 300000, 1445000, 'App\\Models\\Import', 10, 7, 'Tạo mới nhập hàng #10', '2021-03-13 02:21:31', '2021-03-13 02:21:31'),
(6, 12, 0, 15000, 251000, 'App\\Models\\Order', 4, 7, 'Tạo mới đơn hàng #4', '2021-03-13 02:51:42', '2021-03-13 02:51:42'),
(7, 0, 2, -300000, 845000, 'App\\Models\\Import', 9, 7, 'Hủy sản phẩm - thông tin nhập hàng #9', '2021-03-13 02:58:54', '2021-03-13 02:58:54'),
(8, 12, 0, 88000, 339000, 'App\\Models\\Order', 2, 7, 'Cập nhật sản phẩm #4 - đơn hàng #2', '2021-03-13 03:06:06', '2021-03-13 03:06:06');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_source_type_source_id_index` (`source_type`,`source_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

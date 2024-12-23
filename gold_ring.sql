-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: MariaDB-11.2
-- Время создания: Дек 02 2024 г., 04:10
-- Версия сервера: 11.2.2-MariaDB
-- Версия PHP: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `gold_ring`
--

-- --------------------------------------------------------

--
-- Структура таблицы `edge`
--

DROP TABLE IF EXISTS `edge`;
CREATE TABLE `edge` (
  `id` int(10) UNSIGNED NOT NULL,
  `source_id` int(10) UNSIGNED NOT NULL,
  `target_id` int(10) UNSIGNED NOT NULL,
  `time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `edge`
--

INSERT INTO `edge` (`id`, `source_id`, `target_id`, `time`) VALUES
(12, 1, 2, '01:30:00'),
(13, 2, 3, '01:10:00'),
(14, 3, 4, '01:00:00'),
(15, 4, 5, '01:00:00'),
(16, 5, 6, '01:40:00'),
(17, 6, 7, '01:40:00'),
(18, 7, 8, '01:10:00'),
(19, 8, 9, '00:40:00'),
(20, 9, 1, '02:40:00'),
(21, 10, 9, '01:00:00'),
(22, 11, 7, '01:10:00');

-- --------------------------------------------------------

--
-- Структура таблицы `point`
--

DROP TABLE IF EXISTS `point`;
CREATE TABLE `point` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `end_point` tinyint(3) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `point`
--

INSERT INTO `point` (`id`, `title`, `end_point`) VALUES
(1, 'Москва', 0),
(2, 'Сергиев Пасад', 0),
(3, 'Переславль Залесский', 0),
(4, 'Ростов', 0),
(5, 'Ярославль', 0),
(6, 'Кострома', 0),
(7, 'Иваново', 0),
(8, 'Суздаль', 0),
(9, 'Владимир', 0),
(10, 'Гусь-Хрустальный', 1),
(11, 'Палех', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `role`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `role`
--

INSERT INTO `role` (`id`, `title`) VALUES
(1, 'ul'),
(2, 'manager');

-- --------------------------------------------------------

--
-- Структура таблицы `route`
--

DROP TABLE IF EXISTS `route`;
CREATE TABLE `route` (
  `id` int(10) UNSIGNED NOT NULL,
  `point_start_id` int(10) UNSIGNED NOT NULL,
  `point_end_id` int(10) UNSIGNED NOT NULL,
  `date_start` date NOT NULL,
  `time_start` time NOT NULL,
  `time_all` int(10) UNSIGNED NOT NULL,
  `time_end` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `before_end` int(10) UNSIGNED DEFAULT NULL,
  `after_start` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `route`
--

INSERT INTO `route` (`id`, `point_start_id`, `point_end_id`, `date_start`, `time_start`, `time_all`, `time_end`, `user_id`, `created_at`, `updated_at`, `before_end`, `after_start`) VALUES
(54, 1, 9, '2024-12-13', '11:00:00', 35400, 75000, 10, '2024-12-02 00:53:26', NULL, NULL, 5400),
(56, 1, 9, '2024-12-13', '11:00:00', 35400, 72600, 10, '2024-12-02 01:00:57', NULL, NULL, 5400),
(59, 1, 10, '2024-12-13', '11:00:00', 13200, 52800, 10, '2024-12-02 01:06:46', NULL, 3600, 9600),
(60, 1, 10, '2024-12-13', '11:00:00', 13200, 52800, 10, '2024-12-02 01:08:20', NULL, 3600, 9600);

-- --------------------------------------------------------

--
-- Структура таблицы `route_item`
--

DROP TABLE IF EXISTS `route_item`;
CREATE TABLE `route_item` (
  `id` int(10) UNSIGNED NOT NULL,
  `route_id` int(10) UNSIGNED NOT NULL,
  `point_id` int(10) UNSIGNED NOT NULL,
  `time_route` time NOT NULL DEFAULT '00:00:00',
  `time_route_sec` int(10) UNSIGNED DEFAULT NULL,
  `time_visit` int(10) UNSIGNED DEFAULT NULL,
  `time_out` int(10) UNSIGNED DEFAULT NULL,
  `time_pause` time DEFAULT NULL,
  `time_pause_sec` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `route_item`
--

INSERT INTO `route_item` (`id`, `route_id`, `point_id`, `time_route`, `time_route_sec`, `time_visit`, `time_out`, `time_pause`, `time_pause_sec`) VALUES
(151, 54, 2, '01:10:00', 4200, 45000, 45000, NULL, NULL),
(152, 54, 3, '01:00:00', 3600, 49200, 49200, NULL, NULL),
(153, 54, 4, '01:00:00', 3600, 52800, 52800, NULL, NULL),
(154, 54, 5, '01:40:00', 6000, 56400, 56400, NULL, NULL),
(155, 54, 6, '01:40:00', 6000, 62400, 62400, NULL, NULL),
(156, 54, 7, '01:10:00', 4200, 68400, 68400, NULL, NULL),
(157, 54, 8, '00:40:00', 2400, 72600, 72600, NULL, NULL),
(159, 56, 2, '01:10:00', 4200, 45000, 45000, NULL, NULL),
(160, 56, 3, '01:00:00', 3600, 49200, 49200, NULL, NULL),
(161, 56, 4, '01:00:00', 3600, 52800, 52800, NULL, NULL),
(162, 56, 5, '01:40:00', 6000, 56400, 56400, NULL, NULL),
(163, 56, 6, '01:40:00', 6000, 62400, 62400, NULL, NULL),
(164, 56, 7, '01:10:00', 4200, 68400, 68400, NULL, NULL),
(165, 56, 8, '00:40:00', 2400, 72600, 72600, NULL, NULL),
(168, 59, 9, '02:40:00', 9600, 49200, 49200, NULL, NULL),
(169, 60, 9, '02:40:00', 9600, 49200, 49200, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(10) UNSIGNED NOT NULL,
  `inn` varchar(10) NOT NULL,
  `password` varchar(255) NOT NULL,
  `auth_key` varchar(255) DEFAULT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `inn`, `password`, `auth_key`, `role_id`) VALUES
(3, '7801025747', '$2y$13$/xuCPCEXSkLy.BIm4ap3r.n1u6LbuB.S1OfStH56B2xEYvl6SGqO6', NULL, 2),
(10, '1234567892', '$2y$13$2qTWfpSyrSyMk2gp/XjvBubuRgDgqsqa1HJZXE6cdIByT60FkWxhi', 'nHy5qr6lyN84Kya12NOeLof7Ls3gXNOv', 1),
(11, '1234567891', '$2y$13$F/cGPlNGzBVv0VVu9XYLI.808ieHmNWKI5LwLAT/pmUD8Q7wxDAgK', NULL, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `user_info`
--

DROP TABLE IF EXISTS `user_info`;
CREATE TABLE `user_info` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `rto` varchar(10) DEFAULT NULL,
  `kpp` varchar(9) NOT NULL,
  `rs` varchar(20) NOT NULL,
  `bank` varchar(255) NOT NULL,
  `bik` varchar(9) NOT NULL,
  `kor` varchar(20) DEFAULT NULL,
  `fio` varchar(255) NOT NULL,
  `phone` varchar(16) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `user_info`
--

INSERT INTO `user_info` (`id`, `user_id`, `title`, `address`, `rto`, `kpp`, `rs`, `bank`, `bik`, `kor`, `fio`, `phone`, `email`) VALUES
(3, 10, 'q', 'q', 'РТО 111111', '123456789', '12345678901234567890', 'q', '123456789', '12345678901234567890', 'й', '+7 999 999 99 99', 'q@q.q'),
(4, 11, 'q', 'q', '', '123456789', '12345678901234567890', 'q', '123456789', '12345678901234567890', 'й', '+7 999 999 99 99', 'q@q.q');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `edge`
--
ALTER TABLE `edge`
  ADD PRIMARY KEY (`id`),
  ADD KEY `edges_ibfk_1` (`source_id`),
  ADD KEY `edges_ibfk_2` (`target_id`);

--
-- Индексы таблицы `point`
--
ALTER TABLE `point`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `route`
--
ALTER TABLE `route`
  ADD PRIMARY KEY (`id`),
  ADD KEY `point_start_id` (`point_start_id`),
  ADD KEY `point_end_id` (`point_end_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `route_item`
--
ALTER TABLE `route_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`route_id`),
  ADD KEY `point_id` (`point_id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `inn` (`inn`),
  ADD KEY `role_id` (`role_id`);

--
-- Индексы таблицы `user_info`
--
ALTER TABLE `user_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `edge`
--
ALTER TABLE `edge`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT для таблицы `point`
--
ALTER TABLE `point`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `role`
--
ALTER TABLE `role`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `route`
--
ALTER TABLE `route`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT для таблицы `route_item`
--
ALTER TABLE `route_item`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `user_info`
--
ALTER TABLE `user_info`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `edge`
--
ALTER TABLE `edge`
  ADD CONSTRAINT `edge_ibfk_1` FOREIGN KEY (`source_id`) REFERENCES `point` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `edge_ibfk_2` FOREIGN KEY (`target_id`) REFERENCES `point` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `route`
--
ALTER TABLE `route`
  ADD CONSTRAINT `route_ibfk_1` FOREIGN KEY (`point_start_id`) REFERENCES `point` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `route_ibfk_2` FOREIGN KEY (`point_end_id`) REFERENCES `point` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `route_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `route_item`
--
ALTER TABLE `route_item`
  ADD CONSTRAINT `route_item_ibfk_1` FOREIGN KEY (`route_id`) REFERENCES `route` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `route_item_ibfk_2` FOREIGN KEY (`point_id`) REFERENCES `point` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_info`
--
ALTER TABLE `user_info`
  ADD CONSTRAINT `user_info_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

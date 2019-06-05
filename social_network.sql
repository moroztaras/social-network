-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Час створення: Чрв 05 2019 р., 11:15
-- Версія сервера: 5.7.24-0ubuntu0.16.04.1
-- Версія PHP: 7.2.17-1+ubuntu16.04.1+deb.sury.org+3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База даних: `social_network`
--

-- --------------------------------------------------------

--
-- Структура таблиці `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `svistyn_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `approved` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп даних таблиці `comment`
--

INSERT INTO `comment` (`id`, `svistyn_id`, `user_id`, `comment`, `created_at`, `approved`) VALUES
(1, 6, 1, 'The best IT company in Cherkassy', '2019-05-12 22:25:04', 1);

-- --------------------------------------------------------

--
-- Структура таблиці `dialogue`
--

CREATE TABLE `dialogue` (
  `id` int(11) NOT NULL,
  `creator_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `receiver_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп даних таблиці `dialogue`
--

INSERT INTO `dialogue` (`id`, `creator_id`, `created_at`, `updated_at`, `receiver_id`) VALUES
(1, 1, '2019-05-28 13:06:17', '2019-06-05 12:47:56', 2);

-- --------------------------------------------------------

--
-- Структура таблиці `file_manager`
--

CREATE TABLE `file_manager` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `origin_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` int(11) NOT NULL,
  `file_mime` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL,
  `created` datetime NOT NULL,
  `handler` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп даних таблиці `file_manager`
--

INSERT INTO `file_manager` (`id`, `user_id`, `filename`, `origin_name`, `url`, `file_size`, `file_mime`, `status`, `created`, `handler`) VALUES
(1, 1, '8d6deff29f2fd1065419470b5176e5c6.png', 'my_photo.png', 'public://user/1/8d6deff29f2fd1065419470b5176e5c6.png', 643340, 'image/png', 1, '2019-05-12 21:33:05', NULL),
(2, 1, 'bd03d9f7f25b6af62fb714f93cd1de9d.jpg', 'cover_11.jpg', 'public://user/1/bd03d9f7f25b6af62fb714f93cd1de9d.jpg', 123597, 'image/jpeg', 1, '2019-05-12 21:33:05', NULL),
(3, 1, 'f1d4112827cd36926b1aca0e65a92ed0.png', 'Amazon-Web-Services_logo835x396.png', 'public://svistyn/1/f1d4112827cd36926b1aca0e65a92ed0.png', 60665, 'image/png', 1, '2019-05-12 21:38:17', NULL),
(4, 1, 'e101535bd1dc2564bb93ec9f89d4e290.png', 'i00011528.png', 'public://svistyn/2/e101535bd1dc2564bb93ec9f89d4e290.png', 44933, 'image/png', 1, '2019-05-12 21:42:56', NULL),
(5, 1, '1881fac691e8eb9cd9867f54b6111e96.jpg', '59990110_426910711189598_4020986234061979648_n.jpg', 'public://svistyn/3/1881fac691e8eb9cd9867f54b6111e96.jpg', 160445, 'image/jpeg', 1, '2019-05-12 21:49:36', NULL),
(6, 1, '01f439452cbf1b3dcfc8de702442ee31.jpg', 'evpatoriya.jpg', 'public://svistyn/4/01f439452cbf1b3dcfc8de702442ee31.jpg', 58940, 'image/jpeg', 1, '2019-05-12 22:00:46', NULL),
(7, 1, 'd87c9b6ce6d984e2506a44920669fdd3.jpg', '40617982_321833875030616_3516089030003392512_n.jpg', 'public://svistyn/5/d87c9b6ce6d984e2506a44920669fdd3.jpg', 27148, 'image/jpeg', 1, '2019-05-12 22:15:47', NULL),
(8, 2, 'dbd56bef1d1670c1992e2c1bce8931ee.png', 'cropped-Favicon.png', 'public://svistyn/6/dbd56bef1d1670c1992e2c1bce8931ee.png', 12118, 'image/png', 1, '2019-05-12 22:20:46', NULL);

-- --------------------------------------------------------

--
-- Структура таблиці `file_usage`
--

CREATE TABLE `file_usage` (
  `id` int(11) NOT NULL,
  `file_id` int(11) DEFAULT NULL,
  `origin_id` int(11) DEFAULT NULL,
  `entity_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entity_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `friends`
--

CREATE TABLE `friends` (
  `id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `friend_id` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп даних таблиці `friends`
--

INSERT INTO `friends` (`id`, `created_at`, `user_id`, `friend_id`, `status`) VALUES
(39, '2019-06-05 14:08:16', 1, 2, 1);

-- --------------------------------------------------------

--
-- Структура таблиці `media`
--

CREATE TABLE `media` (
  `id` int(11) NOT NULL,
  `content_type` varchar(190) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `s3key` longtext COLLATE utf8mb4_unicode_ci,
  `name_file` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` longtext COLLATE utf8mb4_unicode_ci,
  `dtype` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `dialogue_id` int(11) DEFAULT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп даних таблиці `message`
--

INSERT INTO `message` (`id`, `sender_id`, `receiver_id`, `dialogue_id`, `message`, `created_at`, `status`) VALUES
(1, 1, 2, 1, 'Привіт! Як справи?', '2019-05-28 13:06:17', 1),
(2, 2, 1, 1, 'Привіт все добре!', '2019-06-03 15:53:42', 1);

-- --------------------------------------------------------

--
-- Структура таблиці `migration_versions`
--

CREATE TABLE `migration_versions` (
  `version` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп даних таблиці `migration_versions`
--

INSERT INTO `migration_versions` (`version`, `executed_at`) VALUES
('20190307162700', '2019-05-12 18:30:21'),
('20190308084711', '2019-05-12 18:30:23'),
('20190312085102', '2019-05-12 18:30:23'),
('20190315094922', '2019-05-12 18:30:26'),
('20190316095607', '2019-05-12 18:30:26'),
('20190327112900', '2019-05-12 18:30:27'),
('20190513075410', '2019-05-13 07:55:00'),
('20190522090525', '2019-05-22 09:07:44'),
('20190522123125', '2019-05-28 10:05:35'),
('20190603122235', '2019-06-03 12:26:03');

-- --------------------------------------------------------

--
-- Структура таблиці `svistyn`
--

CREATE TABLE `svistyn` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `photo_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `text` text COLLATE utf8mb4_unicode_ci,
  `embed_video` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `marking` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `views` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп даних таблиці `svistyn`
--

INSERT INTO `svistyn` (`id`, `user_id`, `photo_id`, `parent_id`, `text`, `embed_video`, `state`, `status`, `created`, `updated`, `marking`, `views`) VALUES
(1, 1, 3, NULL, 'AWS - Amazon Web Services - Що це і чому тобі це потрібно', 'https://www.youtube.com/watch?v=8jbx8O3wuLg', 0, 1, '2019-05-12 21:36:13', '2019-05-12 21:38:17', 'active', 0),
(2, 1, 4, NULL, 'Docker - Все що потрібно знати щоб почати працювати з Docker, всі основи в одному уроці', 'https://www.youtube.com/watch?v=I18TNwZ2Nqg&t=445s', 0, 1, '2019-05-12 21:41:53', '2019-05-12 21:42:56', 'active', 0),
(3, 1, 5, NULL, 'Команду eKreative переповнюють емоції\r\nАдже відбувся випуск Першого сезону Lektorium!\r\nВітаємо наших випускників!', NULL, 0, 1, '2019-05-12 21:47:35', '2019-05-12 21:51:46', 'active', 0),
(4, 1, 6, NULL, 'Євпаторія, Крим. Коротко про курорт. Пляж, Житло, Відпочинок', 'https://www.youtube.com/watch?v=Djm1FphB0WQ', 0, 1, '2019-05-12 21:53:44', '2019-05-12 22:00:46', 'active', 0),
(5, 1, 7, NULL, 'Lektorium', NULL, 0, 1, '2019-05-12 22:11:49', '2019-05-12 22:15:47', 'active', 0),
(6, 2, 8, NULL, 'EKreative - web and mobile app development', NULL, 0, 1, '2019-05-12 22:16:50', '2019-05-12 22:20:46', 'active', 0),
(7, 1, NULL, 6, 'IT company Cherkasy', NULL, 1, 1, '2019-05-12 22:22:52', '2019-05-12 22:22:52', 'active', 0);

-- --------------------------------------------------------

--
-- Структура таблиці `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `avatar_fid` int(11) DEFAULT NULL,
  `cover_fid` int(11) DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `status` smallint(6) NOT NULL,
  `roles` json NOT NULL,
  `fullname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birthday` datetime NOT NULL,
  `gender` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `region` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token_recover` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп даних таблиці `user`
--

INSERT INTO `user` (`id`, `avatar_fid`, `cover_fid`, `username`, `email`, `password`, `created`, `updated`, `status`, `roles`, `fullname`, `birthday`, `gender`, `region`, `token_recover`, `api_token`) VALUES
(1, 1, 2, 'df0eb898662d2f97249fa60225dac7c6', 'moroztaras@i.ua', '$2y$13$utklY8PJH8N1icKQ66la.uLda9SFZGeT2BG7wXjtIucQGR7RkO/ue', '2019-05-12 21:30:33', '2019-05-12 21:30:33', 1, '["ROLE_SUPER_ADMIN"]', 'Moroz Taras', '1986-07-15 00:00:00', 'm', 'UA', NULL, 'ULJQGWATiF4uurM13Aijdf8X8TUH_RR8QC7jnNm7Df4'),
(2, NULL, NULL, 'f8dc84f58cfcd8068fdc1b690bc556d6', 'user@mail.ua', '$2y$13$GfW9kvlAUOmvCDfQsVuwMOhTLfPqsvuQ4O1YxSWRvJwGxqfFF3MP6', '2019-05-12 21:30:34', '2019-05-12 21:30:34', 1, '["ROLE_USER"]', 'FullName', '2019-05-12 21:30:34', 'm', 'UA', NULL, 'HZIskuH6zN3bQqJADQ60oU6k5AYgrWWVxW2jp8a7-qk');

--
-- Індекси збережених таблиць
--

--
-- Індекси таблиці `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_9474526C49E1CCEF` (`svistyn_id`),
  ADD KEY `IDX_9474526CA76ED395` (`user_id`);

--
-- Індекси таблиці `dialogue`
--
ALTER TABLE `dialogue`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F18A1C3961220EA6` (`creator_id`),
  ADD KEY `IDX_F18A1C39CD53EDB6` (`receiver_id`);

--
-- Індекси таблиці `file_manager`
--
ALTER TABLE `file_manager`
  ADD PRIMARY KEY (`id`);

--
-- Індекси таблиці `file_usage`
--
ALTER TABLE `file_usage`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_7A68EE4793CB796C` (`file_id`);

--
-- Індекси таблиці `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_21EE70696A5458E8` (`friend_id`),
  ADD KEY `IDX_21EE7069A76ED395` (`user_id`);

--
-- Індекси таблиці `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`);

--
-- Індекси таблиці `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B6BD307FF624B39D` (`sender_id`),
  ADD KEY `IDX_B6BD307FCD53EDB6` (`receiver_id`),
  ADD KEY `IDX_B6BD307FA6E12CBD` (`dialogue_id`);

--
-- Індекси таблиці `migration_versions`
--
ALTER TABLE `migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Індекси таблиці `svistyn`
--
ALTER TABLE `svistyn`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_7730AF36A76ED395` (`user_id`),
  ADD KEY `IDX_7730AF367E9E4C8C` (`photo_id`),
  ADD KEY `IDX_7730AF36727ACA70` (`parent_id`);

--
-- Індекси таблиці `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  ADD UNIQUE KEY `UNIQ_8D93D6493B1E5BE3` (`avatar_fid`),
  ADD UNIQUE KEY `UNIQ_8D93D649FF6B0E46` (`cover_fid`),
  ADD UNIQUE KEY `UNIQ_8D93D6497BA2F5EB` (`api_token`);

--
-- AUTO_INCREMENT для збережених таблиць
--

--
-- AUTO_INCREMENT для таблиці `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблиці `dialogue`
--
ALTER TABLE `dialogue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблиці `file_manager`
--
ALTER TABLE `file_manager`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT для таблиці `file_usage`
--
ALTER TABLE `file_usage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблиці `friends`
--
ALTER TABLE `friends`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT для таблиці `media`
--
ALTER TABLE `media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблиці `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT для таблиці `svistyn`
--
ALTER TABLE `svistyn`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT для таблиці `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Обмеження зовнішнього ключа збережених таблиць
--

--
-- Обмеження зовнішнього ключа таблиці `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `FK_9474526C49E1CCEF` FOREIGN KEY (`svistyn_id`) REFERENCES `svistyn` (`id`),
  ADD CONSTRAINT `FK_9474526CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Обмеження зовнішнього ключа таблиці `dialogue`
--
ALTER TABLE `dialogue`
  ADD CONSTRAINT `FK_F18A1C3961220EA6` FOREIGN KEY (`creator_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_F18A1C39CD53EDB6` FOREIGN KEY (`receiver_id`) REFERENCES `user` (`id`);

--
-- Обмеження зовнішнього ключа таблиці `file_usage`
--
ALTER TABLE `file_usage`
  ADD CONSTRAINT `FK_7A68EE4793CB796C` FOREIGN KEY (`file_id`) REFERENCES `file_manager` (`id`);

--
-- Обмеження зовнішнього ключа таблиці `friends`
--
ALTER TABLE `friends`
  ADD CONSTRAINT `FK_21EE70696A5458E8` FOREIGN KEY (`friend_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_21EE7069A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Обмеження зовнішнього ключа таблиці `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `FK_B6BD307FA6E12CBD` FOREIGN KEY (`dialogue_id`) REFERENCES `dialogue` (`id`),
  ADD CONSTRAINT `FK_B6BD307FCD53EDB6` FOREIGN KEY (`receiver_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_B6BD307FF624B39D` FOREIGN KEY (`sender_id`) REFERENCES `user` (`id`);

--
-- Обмеження зовнішнього ключа таблиці `svistyn`
--
ALTER TABLE `svistyn`
  ADD CONSTRAINT `FK_7730AF36727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `svistyn` (`id`),
  ADD CONSTRAINT `FK_7730AF367E9E4C8C` FOREIGN KEY (`photo_id`) REFERENCES `file_manager` (`id`),
  ADD CONSTRAINT `FK_7730AF36A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Обмеження зовнішнього ключа таблиці `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D6493B1E5BE3` FOREIGN KEY (`avatar_fid`) REFERENCES `file_manager` (`id`),
  ADD CONSTRAINT `FK_8D93D649FF6B0E46` FOREIGN KEY (`cover_fid`) REFERENCES `file_manager` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

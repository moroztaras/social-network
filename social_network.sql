-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Час створення: Чрв 13 2019 р., 07:36
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

-- --------------------------------------------------------

--
-- Структура таблиці `dialogue`
--

CREATE TABLE `dialogue` (
  `id` int(11) NOT NULL,
  `creator_id` int(11) DEFAULT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп даних таблиці `dialogue`
--

INSERT INTO `dialogue` (`id`, `creator_id`, `receiver_id`, `created_at`, `updated_at`) VALUES
(1, 1, 2, '2019-06-13 10:25:10', '2019-06-13 10:27:30');

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
(1, 1, 'cdc84957cacf7359f0e96f7c67d908cf.png', 'my_photo.png', 'public://user/1/cdc84957cacf7359f0e96f7c67d908cf.png', 643340, 'image/png', 1, '2019-06-13 10:21:39', NULL),
(2, 1, '863bec6423e0cb361fd8c1f9ce816fd7.jpg', 'cover_1.jpg', 'public://user/1/863bec6423e0cb361fd8c1f9ce816fd7.jpg', 102119, 'image/jpeg', 1, '2019-06-13 10:21:39', NULL),
(3, 1, '2265d302808e6a93454c181ba305f359.jpg', 'ek_avatar.jpg', 'public://group/1/2265d302808e6a93454c181ba305f359.jpg', 7239, 'image/jpeg', 1, '2019-06-13 10:34:51', NULL),
(4, 1, '98ce9a35f7526c80fb5e4fe36c22e2ec.png', 'cover_7.png', 'public://group/1/98ce9a35f7526c80fb5e4fe36c22e2ec.png', 648557, 'image/png', 1, '2019-06-13 10:34:51', NULL);

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
  `friend_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп даних таблиці `friends`
--

INSERT INTO `friends` (`id`, `friend_id`, `user_id`, `status`, `created_at`) VALUES
(1, 2, 1, 1, '2019-06-13 10:22:26'),
(3, 1, 2, 1, '2019-06-13 10:23:40');

-- --------------------------------------------------------

--
-- Структура таблиці `group_users`
--

CREATE TABLE `group_users` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `avatar_fid` int(11) DEFAULT NULL,
  `cover_fid` int(11) DEFAULT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `slug` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `confidentiality` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп даних таблиці `group_users`
--

INSERT INTO `group_users` (`id`, `admin_id`, `avatar_fid`, `cover_fid`, `name`, `description`, `slug`, `confidentiality`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 4, 'Ekreative', 'Група співробітників IT компанії Ekriative', 'ekreative', 'open', '2019-06-13 10:30:29', '2019-06-13 10:30:29');

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
(1, 1, 2, 1, 'Привіт як твої справи?', '2019-06-13 10:25:10', 1),
(2, 2, 1, 1, 'Привіт, все добре, а в тебе як справи?', '2019-06-13 10:27:30', 0);

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
('20190613071723', '2019-06-13 07:17:58');

-- --------------------------------------------------------

--
-- Структура таблиці `svistyn`
--

CREATE TABLE `svistyn` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `photo_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `group_users_id` int(11) DEFAULT NULL,
  `text` text COLLATE utf8mb4_unicode_ci,
  `embed_video` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `views` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `marking` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 1, 2, '588e29970938dc478ac22b0be97144ee', 'moroztaras@i.ua', '$2y$13$w7vZ24Ybzqn1f1iyufGujeLar.tTLCJMJrST99zDEzwEcfP.BBWLS', '2019-06-13 10:19:12', '2019-06-13 10:19:12', 1, '["ROLE_SUPER_ADMIN"]', 'Moroz Taras', '1986-06-15 00:00:00', 'm', 'UA', NULL, 'qo8252uMPfPzbD7mUgAft0DVkwwMiRF8sJ6pWnei4SY'),
(2, NULL, NULL, 'f3afb403f3251e79ae710228c17768b5', 'user@mail.ua', '$2y$13$ZGnCH8X6sZ9ZhAECb3a6YeCMc07K9JnovazWaHytGu9rO7QfKKmXu', '2019-06-13 10:19:13', '2019-06-13 10:19:13', 1, '["ROLE_USER"]', 'FullName', '2019-06-13 10:19:14', 'm', 'UA', NULL, 'pPX9PumqJWx1CZaglHoAIHWIOBulHWEAyPfIwyfrobI');

-- --------------------------------------------------------

--
-- Структура таблиці `user_group_users`
--

CREATE TABLE `user_group_users` (
  `user_id` int(11) NOT NULL,
  `group_users_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп даних таблиці `user_group_users`
--

INSERT INTO `user_group_users` (`user_id`, `group_users_id`) VALUES
(1, 1);

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
-- Індекси таблиці `group_users`
--
ALTER TABLE `group_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_44AF8E8E3B1E5BE3` (`avatar_fid`),
  ADD UNIQUE KEY `UNIQ_44AF8E8EFF6B0E46` (`cover_fid`),
  ADD KEY `IDX_44AF8E8E642B8210` (`admin_id`);

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
  ADD KEY `IDX_7730AF36727ACA70` (`parent_id`),
  ADD KEY `IDX_7730AF366E83F842` (`group_users_id`);

--
-- Індекси таблиці `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  ADD UNIQUE KEY `UNIQ_8D93D6497BA2F5EB` (`api_token`),
  ADD UNIQUE KEY `UNIQ_8D93D6493B1E5BE3` (`avatar_fid`),
  ADD UNIQUE KEY `UNIQ_8D93D649FF6B0E46` (`cover_fid`);

--
-- Індекси таблиці `user_group_users`
--
ALTER TABLE `user_group_users`
  ADD PRIMARY KEY (`user_id`,`group_users_id`),
  ADD KEY `IDX_EDB4471BA76ED395` (`user_id`),
  ADD KEY `IDX_EDB4471B6E83F842` (`group_users_id`);

--
-- AUTO_INCREMENT для збережених таблиць
--

--
-- AUTO_INCREMENT для таблиці `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблиці `dialogue`
--
ALTER TABLE `dialogue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблиці `file_manager`
--
ALTER TABLE `file_manager`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблиці `file_usage`
--
ALTER TABLE `file_usage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблиці `friends`
--
ALTER TABLE `friends`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблиці `group_users`
--
ALTER TABLE `group_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблиці `media`
--
ALTER TABLE `media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблиці `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблиці `svistyn`
--
ALTER TABLE `svistyn`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
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
-- Обмеження зовнішнього ключа таблиці `group_users`
--
ALTER TABLE `group_users`
  ADD CONSTRAINT `FK_44AF8E8E3B1E5BE3` FOREIGN KEY (`avatar_fid`) REFERENCES `file_manager` (`id`),
  ADD CONSTRAINT `FK_44AF8E8E642B8210` FOREIGN KEY (`admin_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_44AF8E8EFF6B0E46` FOREIGN KEY (`cover_fid`) REFERENCES `file_manager` (`id`);

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
  ADD CONSTRAINT `FK_7730AF366E83F842` FOREIGN KEY (`group_users_id`) REFERENCES `group_users` (`id`),
  ADD CONSTRAINT `FK_7730AF36727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `svistyn` (`id`),
  ADD CONSTRAINT `FK_7730AF367E9E4C8C` FOREIGN KEY (`photo_id`) REFERENCES `file_manager` (`id`),
  ADD CONSTRAINT `FK_7730AF36A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Обмеження зовнішнього ключа таблиці `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D6493B1E5BE3` FOREIGN KEY (`avatar_fid`) REFERENCES `file_manager` (`id`),
  ADD CONSTRAINT `FK_8D93D649FF6B0E46` FOREIGN KEY (`cover_fid`) REFERENCES `file_manager` (`id`);

--
-- Обмеження зовнішнього ключа таблиці `user_group_users`
--
ALTER TABLE `user_group_users`
  ADD CONSTRAINT `FK_EDB4471B6E83F842` FOREIGN KEY (`group_users_id`) REFERENCES `group_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_EDB4471BA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

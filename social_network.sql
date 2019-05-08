-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Час створення: Трв 03 2019 р., 12:10
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
(1, 1, '675128cbef81dfb68a866c207614d5ae.png', 'my_photo.png', 'public://user/1/675128cbef81dfb68a866c207614d5ae.png', 643340, 'image/png', 1, '2019-05-03 15:09:51', NULL),
(2, 1, 'b67a82ee7c786d97af126f5399a068ae.jpg', 'cover.jpg', 'public://user/1/b67a82ee7c786d97af126f5399a068ae.jpg', 167008, 'image/jpeg', 1, '2019-05-03 15:09:51', NULL);

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
  `friend_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('20190307162700', '2019-05-03 12:03:31'),
('20190308084711', '2019-05-03 12:03:33'),
('20190312085102', '2019-05-03 12:03:33'),
('20190315094922', '2019-05-03 12:03:36'),
('20190316095607', '2019-05-03 12:03:37'),
('20190327112900', '2019-05-03 12:03:38');

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
(1, 1, 2, '09610b594a995bd4af28668bc6b19123', 'moroztaras@i.ua', '$2y$13$ykvMxZ1qZffP3rw6zKQPn.5hWZoSprZPJQ5e7Tpl0JohtTghZbwDe', '2019-05-03 15:03:46', '2019-05-03 15:03:46', 1, '["ROLE_SUPER_ADMIN"]', 'Moroz Taras', '1986-07-15 00:00:00', 'm', 'UA', NULL, 'hYcJHxNnkBup60lYeaQZKvA_dM-1s-UTqmPZNB-DFKE'),
(2, NULL, NULL, 'd73df47d056a6f622df25de35e3e5256', 'user@mail.ua', '$2y$13$KLFTXD0axSgeFWh7HjlIbOs/lTuVltSUMUyNYSQWPOlgLaAdeDQTi', '2019-05-03 15:03:47', '2019-05-03 15:03:47', 1, '["ROLE_USER"]', 'FullName', '2019-05-03 15:03:47', 'm', 'UA', NULL, 'diU0Sy9JQ_zxGqHL2xjMt3R342vHcg3QZvkoOXR-_HA');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблиці `file_manager`
--
ALTER TABLE `file_manager`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблиці `file_usage`
--
ALTER TABLE `file_usage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблиці `friends`
--
ALTER TABLE `friends`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблиці `media`
--
ALTER TABLE `media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
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

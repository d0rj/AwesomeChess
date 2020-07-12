-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Версия сервера: 10.3.22-MariaDB
-- Версия PHP: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- База данных: `chess_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `games`
--

CREATE TABLE `games` (
  `id` int(11) NOT NULL,
  `state` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'RNBQKBNRPPPPPPPP................................pppppppprnbqkbnr',
  `history` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `whiteId` int(11) NOT NULL,
  `blackId` int(11) NOT NULL DEFAULT 0,
  `ended` tinyint(1) NOT NULL DEFAULT 0,
  `whiteMove` tinyint(1) NOT NULL DEFAULT 1,
  `winner` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `whiteId` (`whiteId`,`blackId`),
  ADD KEY `blackId` (`blackId`),
  ADD KEY `winner` (`winner`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `games`
--
ALTER TABLE `games`
  ADD CONSTRAINT `games_ibfk_1` FOREIGN KEY (`whiteId`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `games_ibfk_2` FOREIGN KEY (`blackId`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `games_ibfk_3` FOREIGN KEY (`winner`) REFERENCES `users` (`id`);
COMMIT;
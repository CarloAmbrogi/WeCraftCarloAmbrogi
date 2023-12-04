-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Dic 04, 2023 alle 18:59
-- Versione del server: 8.0.30
-- Versione PHP: 8.0.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `my_carloambrogipolimi`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `Artisan`
--

CREATE TABLE `Artisan` (
  `id` int NOT NULL,
  `shopName` varchar(25) NOT NULL,
  `openingHours` varchar(183) NOT NULL,
  `description` text NOT NULL,
  `phoneNumber` varchar(25) NOT NULL,
  `latitude` varchar(25) NOT NULL,
  `longitude` varchar(25) NOT NULL,
  `address` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `Artisan`
--

INSERT INTO `Artisan` (`id`, `shopName`, `openingHours`, `description`, `phoneNumber`, `latitude`, `longitude`, `address`) VALUES
(71, 'shopName', '%MonF01:0203:04S05:0607:08', 'aaa', '2', '1.3', '1.5', 'via');

-- --------------------------------------------------------

--
-- Struttura della tabella `Customer`
--

CREATE TABLE `Customer` (
  `id` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `Customer`
--

INSERT INTO `Customer` (`id`) VALUES
(70);

-- --------------------------------------------------------

--
-- Struttura della tabella `Designer`
--

CREATE TABLE `Designer` (
  `id` int NOT NULL,
  `description` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `Product`
--

CREATE TABLE `Product` (
  `id` int NOT NULL,
  `artisan` int NOT NULL,
  `name` varchar(25) NOT NULL,
  `description` text NOT NULL,
  `iconExtension` varchar(7) DEFAULT NULL,
  `icon` longblob,
  `price` float NOT NULL,
  `quantity` int NOT NULL,
  `category` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `added` timestamp NOT NULL,
  `lastSell` timestamp NULL DEFAULT NULL
) ;

--
-- Dump dei dati per la tabella `Product`
--

INSERT INTO `Product` (`id`, `artisan`, `name`, `description`, `iconExtension`, `icon`, `price`, `quantity`, `category`, `added`, `lastSell`) VALUES
(19, 71, 'aaa', 'aaa', NULL, NULL, 5.5, 8, 'Home decoration', '2023-12-04 16:50:12', NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `ProductImages`
--

CREATE TABLE `ProductImages` (
  `id` int NOT NULL,
  `productId` int NOT NULL,
  `imgExtension` varchar(7) NOT NULL,
  `image` longblob NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `ProductTags`
--

CREATE TABLE `ProductTags` (
  `id` int NOT NULL,
  `productId` int NOT NULL,
  `tag` varchar(25) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `ShoppingCart`
--

CREATE TABLE `ShoppingCart` (
  `customer` int NOT NULL,
  `product` int NOT NULL,
  `quantity` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `User`
--

CREATE TABLE `User` (
  `id` int NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(25) NOT NULL,
  `surname` varchar(25) NOT NULL,
  `iconExtension` varchar(7) DEFAULT NULL,
  `icon` longblob,
  `emailVerified` tinyint(1) NOT NULL,
  `verificationCode` varchar(10) NOT NULL,
  `timeVerificationCode` timestamp NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `User`
--

INSERT INTO `User` (`id`, `email`, `password`, `name`, `surname`, `iconExtension`, `icon`, `emailVerified`, `verificationCode`, `timeVerificationCode`) VALUES
(70, 'customer@mail.com', '$2y$10$QUJxP00IC0Vpayxol/H5..VJJtz0mfe.tex1WYRzvlZzPSEmQME5u', 'name', 'surname', NULL, NULL, 1, '230241', '2023-12-04 16:49:04'),
(71, 'artisan@mail.com', '$2y$10$89hUXXg3qMR/ZGxcwstEMOb6x51/HNvoBwFg.SjeNTamPkW4x8Zmy', 'name', 'surname', NULL, NULL, 1, '484518', '2023-12-04 16:49:46');

--
-- Trigger `User`
--
DELIMITER $$
CREATE TRIGGER `WhenDeleteOnUserDeleteAlsoOnArtisan` AFTER DELETE ON `User` FOR EACH ROW DELETE FROM `Artisan` WHERE `id` = old.`id`
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `WhenDeleteOnUserDeleteAlsoOnCustomer` AFTER DELETE ON `User` FOR EACH ROW DELETE FROM `Customer` WHERE `id` = old.`id`
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `WhenDeleteOnUserDeleteAlsoOnDesigner` AFTER DELETE ON `User` FOR EACH ROW DELETE FROM `Designer` WHERE `id` = old.`id`
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `UserImages`
--

CREATE TABLE `UserImages` (
  `id` int NOT NULL,
  `userId` int NOT NULL,
  `imgExtension` varchar(7) NOT NULL,
  `image` longblob NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `Artisan`
--
ALTER TABLE `Artisan`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `Customer`
--
ALTER TABLE `Customer`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `Designer`
--
ALTER TABLE `Designer`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `Product`
--
ALTER TABLE `Product`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `ProductImages`
--
ALTER TABLE `ProductImages`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `ProductTags`
--
ALTER TABLE `ProductTags`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `ShoppingCart`
--
ALTER TABLE `ShoppingCart`
  ADD PRIMARY KEY (`customer`,`product`);

--
-- Indici per le tabelle `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indici per le tabelle `UserImages`
--
ALTER TABLE `UserImages`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `Product`
--
ALTER TABLE `Product`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ProductImages`
--
ALTER TABLE `ProductImages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT per la tabella `ProductTags`
--
ALTER TABLE `ProductTags`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT per la tabella `User`
--
ALTER TABLE `User`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT per la tabella `UserImages`
--
ALTER TABLE `UserImages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Gen 17, 2024 alle 12:50
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
-- Struttura della tabella `Advertisement`
--

CREATE TABLE `Advertisement` (
  `artisan` int NOT NULL,
  `product` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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

-- --------------------------------------------------------

--
-- Struttura della tabella `ContentPurchase`
--

CREATE TABLE `ContentPurchase` (
  `purchaseId` int NOT NULL,
  `product` int NOT NULL,
  `artisan` int NOT NULL,
  `singleItemCost` float NOT NULL,
  `quantity` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `CooperativeDesignProducts`
--

CREATE TABLE `CooperativeDesignProducts` (
  `user` int NOT NULL,
  `product` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `CooperativeDesignProjects`
--

CREATE TABLE `CooperativeDesignProjects` (
  `user` int NOT NULL,
  `project` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `Customer`
--

CREATE TABLE `Customer` (
  `id` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
-- Struttura della tabella `ExchangeProduct`
--

CREATE TABLE `ExchangeProduct` (
  `artisan` int NOT NULL,
  `product` int NOT NULL,
  `quantity` int NOT NULL
) ;

--
-- Trigger `ExchangeProduct`
--
DELIMITER $$
CREATE TRIGGER `whenDeleteExchangeProductDeleteAlsoRelatedShoppingCart` AFTER DELETE ON `ExchangeProduct` FOR EACH ROW DELETE FROM `ShoppingCart` WHERE `product` = old.`product` AND `artisan` = old.`artisan`
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `Messages`
--

CREATE TABLE `Messages` (
  `id` int NOT NULL,
  `fromWho` int NOT NULL,
  `toKind` varchar(25) NOT NULL,
  `toWho` int NOT NULL,
  `timestamp` timestamp NOT NULL,
  `isANotification` tinyint(1) NOT NULL,
  `text` text NOT NULL,
  `imgExtension` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `image` longblob,
  `linkKind` varchar(25) DEFAULT NULL,
  `linkTo` int DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `Metadata`
--

CREATE TABLE `Metadata` (
  `MediaCode` int NOT NULL,
  `Title` varchar(250) NOT NULL,
  `Description` text NOT NULL,
  `URL` varchar(250) NOT NULL,
  `Type` varchar(30) NOT NULL,
  `PublicationDate` datetime NOT NULL,
  `StartDate` datetime NOT NULL,
  `EndDate` datetime NOT NULL,
  `Location` varchar(250) NOT NULL,
  `TagsFound` varchar(250) NOT NULL,
  `TrainingTags` varchar(250) NOT NULL,
  `ImageURL` varchar(250) NOT NULL,
  `ProviderName` varchar(250) NOT NULL,
  `ProviderURL` varchar(250) NOT NULL,
  `ProviderIcon` varchar(250) NOT NULL,
  `CodePOI` int NOT NULL,
  `TrainingText` text NOT NULL,
  `Usage` varchar(30) NOT NULL,
  `TagsAlgorithm` varchar(250) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='This table includes multimedia files and their metadata';

-- --------------------------------------------------------

--
-- Struttura della tabella `MetadataTags`
--

CREATE TABLE `MetadataTags` (
  `MediaCode` int NOT NULL,
  `TagID` int NOT NULL,
  `Source` varchar(24) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `Ontology`
--

CREATE TABLE `Ontology` (
  `RelationID` int NOT NULL,
  `Tag1` varchar(30) NOT NULL,
  `Tag2` varchar(30) NOT NULL,
  `Relation` varchar(24) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='This table represents the relationships between tags';

-- --------------------------------------------------------

--
-- Struttura della tabella `POI`
--

CREATE TABLE `POI` (
  `CodePOI` int NOT NULL,
  `Name` varchar(250) NOT NULL,
  `Latitude` decimal(10,8) NOT NULL,
  `Longitude` decimal(11,8) NOT NULL,
  `Elevation` int NOT NULL DEFAULT '0',
  `TypePOI` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'TypePOI',
  `Surface` int NOT NULL DEFAULT '0',
  `Address` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Address'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='This table includes the Points of Interest identified on the map';

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
  `lastSell` timestamp NULL DEFAULT NULL,
  `percentageResell` float DEFAULT NULL
) ;

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
-- Struttura della tabella `Project`
--

CREATE TABLE `Project` (
  `id` int NOT NULL,
  `designer` int NOT NULL,
  `customer` int NOT NULL,
  `name` varchar(25) NOT NULL,
  `description` text NOT NULL,
  `iconExtension` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `icon` longblob,
  `price` float NOT NULL,
  `percentageToDesigner` float NOT NULL,
  `claimedByThisArtisan` int DEFAULT NULL,
  `confirmedByTheCustomer` tinyint(1) NOT NULL,
  `timestampPurchase` timestamp NULL DEFAULT NULL,
  `address` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `timestampReady` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `ProjectAssignArtisans`
--

CREATE TABLE `ProjectAssignArtisans` (
  `project` int NOT NULL,
  `artisan` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `ProjectImages`
--

CREATE TABLE `ProjectImages` (
  `id` int NOT NULL,
  `projectId` int NOT NULL,
  `imgExtension` varchar(7) NOT NULL,
  `image` longblob NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `PurchasesCronology`
--

CREATE TABLE `PurchasesCronology` (
  `id` int NOT NULL,
  `customer` int NOT NULL,
  `timestamp` timestamp NOT NULL,
  `address` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `ReadMessage`
--

CREATE TABLE `ReadMessage` (
  `readBy` int NOT NULL,
  `messageId` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `SheetProducts`
--

CREATE TABLE `SheetProducts` (
  `product` int NOT NULL,
  `content` text NOT NULL,
  `lastUpdateFrom` int DEFAULT NULL,
  `lastUpdateWhen` timestamp NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `SheetProjects`
--

CREATE TABLE `SheetProjects` (
  `project` int NOT NULL,
  `content` text NOT NULL,
  `lastUpdateFrom` int DEFAULT NULL,
  `lastUpdateWhen` timestamp NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `ShoppingCart`
--

CREATE TABLE `ShoppingCart` (
  `customer` int NOT NULL,
  `product` int NOT NULL,
  `artisan` int NOT NULL,
  `quantity` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `Tags`
--

CREATE TABLE `Tags` (
  `TagID` int NOT NULL,
  `Context` varchar(30) NOT NULL DEFAULT 'News',
  `Name` varchar(30) NOT NULL,
  `ExprUK` varchar(64) NOT NULL,
  `ExprIT` varchar(64) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='This table includes the classification tags related to the media';

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

-- --------------------------------------------------------

--
-- Struttura della tabella `Users`
--

CREATE TABLE `Users` (
  `userid` int NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(24) NOT NULL,
  `type` varchar(12) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `Advertisement`
--
ALTER TABLE `Advertisement`
  ADD PRIMARY KEY (`artisan`,`product`);

--
-- Indici per le tabelle `Artisan`
--
ALTER TABLE `Artisan`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `ContentPurchase`
--
ALTER TABLE `ContentPurchase`
  ADD PRIMARY KEY (`purchaseId`,`product`,`artisan`);

--
-- Indici per le tabelle `CooperativeDesignProducts`
--
ALTER TABLE `CooperativeDesignProducts`
  ADD PRIMARY KEY (`user`,`product`);

--
-- Indici per le tabelle `CooperativeDesignProjects`
--
ALTER TABLE `CooperativeDesignProjects`
  ADD PRIMARY KEY (`user`,`project`);

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
-- Indici per le tabelle `ExchangeProduct`
--
ALTER TABLE `ExchangeProduct`
  ADD PRIMARY KEY (`artisan`,`product`);

--
-- Indici per le tabelle `Messages`
--
ALTER TABLE `Messages`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `Metadata`
--
ALTER TABLE `Metadata`
  ADD PRIMARY KEY (`MediaCode`);

--
-- Indici per le tabelle `MetadataTags`
--
ALTER TABLE `MetadataTags`
  ADD PRIMARY KEY (`MediaCode`,`TagID`);

--
-- Indici per le tabelle `Ontology`
--
ALTER TABLE `Ontology`
  ADD PRIMARY KEY (`RelationID`);

--
-- Indici per le tabelle `POI`
--
ALTER TABLE `POI`
  ADD PRIMARY KEY (`CodePOI`);

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
-- Indici per le tabelle `Project`
--
ALTER TABLE `Project`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `ProjectAssignArtisans`
--
ALTER TABLE `ProjectAssignArtisans`
  ADD PRIMARY KEY (`project`,`artisan`);

--
-- Indici per le tabelle `ProjectImages`
--
ALTER TABLE `ProjectImages`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `PurchasesCronology`
--
ALTER TABLE `PurchasesCronology`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `ReadMessage`
--
ALTER TABLE `ReadMessage`
  ADD PRIMARY KEY (`readBy`,`messageId`);

--
-- Indici per le tabelle `SheetProducts`
--
ALTER TABLE `SheetProducts`
  ADD PRIMARY KEY (`product`);

--
-- Indici per le tabelle `SheetProjects`
--
ALTER TABLE `SheetProjects`
  ADD PRIMARY KEY (`project`);

--
-- Indici per le tabelle `ShoppingCart`
--
ALTER TABLE `ShoppingCart`
  ADD PRIMARY KEY (`customer`,`product`,`artisan`);

--
-- Indici per le tabelle `Tags`
--
ALTER TABLE `Tags`
  ADD PRIMARY KEY (`TagID`);

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
-- Indici per le tabelle `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `Messages`
--
ALTER TABLE `Messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `Metadata`
--
ALTER TABLE `Metadata`
  MODIFY `MediaCode` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `Ontology`
--
ALTER TABLE `Ontology`
  MODIFY `RelationID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `POI`
--
ALTER TABLE `POI`
  MODIFY `CodePOI` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `Product`
--
ALTER TABLE `Product`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ProductImages`
--
ALTER TABLE `ProductImages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ProductTags`
--
ALTER TABLE `ProductTags`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `Project`
--
ALTER TABLE `Project`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ProjectImages`
--
ALTER TABLE `ProjectImages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `PurchasesCronology`
--
ALTER TABLE `PurchasesCronology`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `Tags`
--
ALTER TABLE `Tags`
  MODIFY `TagID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `User`
--
ALTER TABLE `User`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `UserImages`
--
ALTER TABLE `UserImages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `Users`
--
ALTER TABLE `Users`
  MODIFY `userid` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

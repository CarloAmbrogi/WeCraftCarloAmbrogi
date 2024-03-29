-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Feb 15, 2024 alle 15:53
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

--
-- Dump dei dati per la tabella `Advertisement`
--

INSERT INTO `Advertisement` (`artisan`, `product`) VALUES
(4, 1);

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
(3, 'TuttoShop', '%MonF01:0203:04S05:0607:08', 'I\'m the artisan number 1, from me you can buy 2 objects', '333', '1.3', '1.5', 'via'),
(4, 'ArtisticShop', '', 'I sell just 1 item but I\'m at least the artisan number 2. Sadly my shop is always close', '444', '234.45', '678.76', 'via delle vie');

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

--
-- Dump dei dati per la tabella `ContentPurchase`
--

INSERT INTO `ContentPurchase` (`purchaseId`, `product`, `artisan`, `singleItemCost`, `quantity`) VALUES
(1, 2, 3, 4.44, 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `CooperativeProductionProducts`
--

CREATE TABLE `CooperativeProductionProducts` (
  `user` int NOT NULL,
  `product` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Trigger `CooperativeProductionProducts`
--
DELIMITER $$
CREATE TRIGGER `deleteCooperativeProductionProducts` AFTER DELETE ON `CooperativeProductionProducts` FOR EACH ROW insert into `CooperativeProductionProductsTrig` (`id`,`user`,`product`,`action`,`timestamp`) values (NULL, old.`user`, old.`product`,'delete',CURRENT_TIMESTAMP())
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `insertCooperativeProductionProducts` AFTER INSERT ON `CooperativeProductionProducts` FOR EACH ROW insert into `CooperativeProductionProductsTrig` (`id`,`user`,`product`,`action`,`timestamp`) values (NULL, new.`user`,new.`product`,'insert',CURRENT_TIMESTAMP())
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `CooperativeProductionProductsTrig`
--

CREATE TABLE `CooperativeProductionProductsTrig` (
  `id` int NOT NULL,
  `user` int NOT NULL,
  `product` int NOT NULL,
  `action` varchar(25) NOT NULL,
  `timestamp` timestamp NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `CooperativeProductionProjects`
--

CREATE TABLE `CooperativeProductionProjects` (
  `user` int NOT NULL,
  `project` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Trigger `CooperativeProductionProjects`
--
DELIMITER $$
CREATE TRIGGER `deleteCooperativeProductionProjects` AFTER DELETE ON `CooperativeProductionProjects` FOR EACH ROW insert into `CooperativeProductionProjectsTrig` (`id`,`user`,`project `,`action`,`timestamp`) values (NULL, old.`user`, old.`project`,'delete',CURRENT_TIMESTAMP())
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `insertCooperativeProductionProjects` AFTER INSERT ON `CooperativeProductionProjects` FOR EACH ROW insert into `CooperativeProductionProjectsTrig` (`id`,`user`,`project`,`action`,`timestamp`) values (NULL, new.`user`,new.`project`,'insert',CURRENT_TIMESTAMP())
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `CooperativeProductionProjectsTrig`
--

CREATE TABLE `CooperativeProductionProjectsTrig` (
  `id` int NOT NULL,
  `user` int NOT NULL,
  `project` int NOT NULL,
  `action` varchar(25) NOT NULL,
  `timestamp` timestamp NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(1);

-- --------------------------------------------------------

--
-- Struttura della tabella `Designer`
--

CREATE TABLE `Designer` (
  `id` int NOT NULL,
  `description` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `Designer`
--

INSERT INTO `Designer` (`id`, `description`) VALUES
(2, 'I\'m the best designer ever');

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
-- Dump dei dati per la tabella `ExchangeProduct`
--

INSERT INTO `ExchangeProduct` (`artisan`, `product`, `quantity`) VALUES
(4, 2, 4);

--
-- Trigger `ExchangeProduct`
--
DELIMITER $$
CREATE TRIGGER `whenDeleteExchangeProductDeleteAlsoRelatedShoppingCart` AFTER DELETE ON `ExchangeProduct` FOR EACH ROW DELETE FROM `ShoppingCart` WHERE `product` = old.`product` AND `artisan` = old.`artisan`
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `FeedbackCollaboration`
--

CREATE TABLE `FeedbackCollaboration` (
  `id` int NOT NULL,
  `fromWho` int NOT NULL,
  `fromKind` varchar(25) NOT NULL,
  `toWhat` int NOT NULL,
  `toWhatKind` varchar(25) NOT NULL,
  `feedback` text NOT NULL,
  `timestamp` timestamp NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `linkTo` int DEFAULT NULL,
  `extraText` text
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

--
-- Dump dei dati per la tabella `Metadata`
--

INSERT INTO `Metadata` (`MediaCode`, `Title`, `Description`, `URL`, `Type`, `PublicationDate`, `StartDate`, `EndDate`, `Location`, `TagsFound`, `TrainingTags`, `ImageURL`, `ProviderName`, `ProviderURL`, `ProviderIcon`, `CodePOI`, `TrainingText`, `Usage`, `TagsAlgorithm`) VALUES
(1, 'Alla scoperta del Ferragosto al Civico Museo Archeologico', 'Alla scoperta del Ferragosto al Civico Museo Archeologico', 'https://www.milanotoday.it/eventi/cosa-fare-milano-ferragosto-2021.html', 'link', '2021-08-09 14:00:00', '2021-08-15 11:00:00', '2021-08-15 00:00:00', 'Corso Magenta 15', 'feste\r\nmusei\r\nferragosto\r\nmilano\r\neventi\r\n', 'news; culture; museum ', 'https://www.milanotoday.it/~media/horizontal-hi/34130978343816/civico-museo-archeologico-3.jpg', 'MilanoToday', 'https://milanotoday.it', '', 1, 'Alla scoperta del Ferragosto al Civico Museo Archeologico feste\nmusei\nferragosto\nmilano\neventi\n cosa fare milano ferragosto 2021 ', 'Training', 'Museum'),
(2, 'TuttoShop (Indiano ArtigianTutto)', 'I\'m the artisan number 1, from me you can buy 2 objects', 'http://carloambrogipolimi.altervista.org/WeCraft/pages/artisan.php?id=3', 'link', '2024-01-16 19:19:38', '2024-01-16 19:19:38', '2024-01-16 19:19:38', 'via', 'WeCraft', 'WeCraft', 'http://carloambrogipolimi.altervista.org/WeCraft/temp/b16290770be394352d8273ea0c606f0c03630232.png', 'WeCraft', 'http://carloambrogipolimi.altervista.org/WeCraft/pages/index.php', '', 2, '', 'WeCraft', 'WeCraft'),
(3, 'ArtisticShop (Arte Igiano)', 'I sell just 1 item but I\'m at least the artisan number 2. Sadly my shop is always close', 'http://carloambrogipolimi.altervista.org/WeCraft/pages/artisan.php?id=4', 'link', '2024-01-16 19:19:38', '2024-01-16 19:19:38', '2024-01-16 19:19:38', 'via delle vie', 'WeCraft', 'WeCraft', 'http://carloambrogipolimi.altervista.org/WeCraft/temp/ed6da55586ca89c28ebeb2d10c683952c8e040c2.png', 'WeCraft', 'http://carloambrogipolimi.altervista.org/WeCraft/pages/index.php', '', 3, '', 'WeCraft', 'WeCraft'),
(4, 'Ring', 'A nice ring', 'http://carloambrogipolimi.altervista.org/WeCraft/pages/product.php?id=1', 'link', '2024-01-16 19:38:13', '2024-01-16 19:38:13', '2024-01-16 19:38:13', 'via', 'WeCraft', 'WeCraft', 'http://carloambrogipolimi.altervista.org/WeCraft/temp/60152adf57bbd7bdf6d1b92c7df0ae069e181a61.png', 'WeCraft', 'http://carloambrogipolimi.altervista.org/WeCraft/pages/index.php', '', 2, '', 'WeCraft', 'WeCraft'),
(5, 'Table', 'A confortable table', 'http://carloambrogipolimi.altervista.org/WeCraft/pages/product.php?id=2', 'link', '2024-01-16 19:38:13', '2024-01-16 19:38:13', '2024-01-16 19:38:13', 'via', 'WeCraft', 'WeCraft', 'http://carloambrogipolimi.altervista.org/WeCraft/temp/e950a6c83412338204271127c84773e47c201ab5.png', 'WeCraft', 'http://carloambrogipolimi.altervista.org/WeCraft/pages/index.php', '', 2, '', 'WeCraft', 'WeCraft'),
(6, 'Generic useless product', 'A generic and useless product which moreover is not available', 'http://carloambrogipolimi.altervista.org/WeCraft/pages/product.php?id=3', 'link', '2024-01-16 19:38:13', '2024-01-16 19:38:13', '2024-01-16 19:38:13', 'via delle vie', 'WeCraft', 'WeCraft', 'http://carloambrogipolimi.altervista.org/WeCraft/temp/245b80974601f4851e74f476d9573b8bfa9f4226.png', 'WeCraft', 'http://carloambrogipolimi.altervista.org/WeCraft/pages/index.php', '', 3, '', 'WeCraft', 'WeCraft');

-- --------------------------------------------------------

--
-- Struttura della tabella `MetadataTags`
--

CREATE TABLE `MetadataTags` (
  `MediaCode` int NOT NULL,
  `TagID` int NOT NULL,
  `Source` varchar(24) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `MetadataTags`
--

INSERT INTO `MetadataTags` (`MediaCode`, `TagID`, `Source`) VALUES
(1, 1, 'SVM_02'),
(2, 2, 'SVM_02'),
(3, 2, 'SVM_02'),
(4, 6, 'SVM_02'),
(5, 5, 'SVM_02'),
(6, 3, 'SVM_02');

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

--
-- Dump dei dati per la tabella `Ontology`
--

INSERT INTO `Ontology` (`RelationID`, `Tag1`, `Tag2`, `Relation`) VALUES
(1, 'Concert', 'PublicEvent', 'is_a'),
(2, 'Concert', 'Music', 'related_to'),
(3, 'University', 'Culture', 'is_a'),
(4, 'Discovery', 'Culture', 'is_a'),
(5, 'PublicEvent', 'Culture', 'is_a'),
(6, 'Interview', 'Discovery', 'related_to'),
(7, 'Interactive activity', 'Discovery', 'related_to'),
(8, 'Interactive activity', 'Museum', 'related_to'),
(9, 'Museum', 'PublicEvent', 'is_a'),
(10, 'Cinema', 'PublicEvent', 'is_a'),
(11, 'Theatre', 'PublicEvent', 'is_a'),
(12, 'Infrastructures & Transports', 'Constructions', 'related_to'),
(13, 'Infrastructures & Transports', 'Warning', 'related_to'),
(14, 'Infrastructures & Transports', 'Transports', 'related_to'),
(15, 'Construction site', 'Constructions', 'is_a'),
(16, 'Roadwork', 'Constructions', 'is_a'),
(17, 'Cycle path', 'Constructions', 'is_a'),
(18, 'Road', 'Transports', 'is_a'),
(19, 'Railway', 'Transports', 'is_a'),
(20, 'Tram', 'Transports', 'is_a'),
(21, 'Underground', 'Transports', 'is_a'),
(22, 'Cycle path', 'Transports', 'is_a'),
(23, 'Cycle path', 'Road', 'related_to'),
(24, 'Roadwork', 'Transports', 'related_to'),
(25, 'Warning', 'Transports', 'related_to'),
(26, 'Warning', 'Constructions', 'related_to'),
(27, 'Crime', 'Local news', 'is_a'),
(28, 'Accident', 'Crime', 'is_a'),
(29, 'Accident', 'Warning', 'related_to'),
(30, 'Accident', 'Infrastructures & Transports', 'related_to'),
(31, 'New opening', 'Local news', 'is_a'),
(32, 'New opening', 'PublicEvent', 'related_to'),
(33, 'Politics', 'Local news', 'is_a'),
(34, 'Commemoration', 'Local news', 'is_a'),
(35, 'Commemoration', 'Politics', 'related_to');

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

--
-- Dump dei dati per la tabella `POI`
--

INSERT INTO `POI` (`CodePOI`, `Name`, `Latitude`, `Longitude`, `Elevation`, `TypePOI`, `Surface`, `Address`) VALUES
(1, 'Civico Museo Archeologico, Milano', '45.47088839', '9.20491554', 0, 'Building', 0, 'Corso Magenta, 15, 20123 Milano MI'),
(2, 'TuttoShop (Indiano ArtigianTutto)', '1.30000000', '1.50000000', 0, 'PoiWeCraft', 0, 'via'),
(3, 'ArtisticShop (Arte Igiano)', '3.30000000', '3.50000000', 0, 'PoiWeCraft', 0, 'via delle vie');

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

--
-- Dump dei dati per la tabella `Product`
--

INSERT INTO `Product` (`id`, `artisan`, `name`, `description`, `iconExtension`, `icon`, `price`, `quantity`, `category`, `added`, `lastSell`, `percentageResell`) VALUES
(1, 3, 'Ring', 'A nice ring', 'png', 0x89504e470d0a1a0a0000000d494844520000000f0000000f08030000000c0865780000000467414d410000b18f0bfc6105000000206348524d00007a26000080840000fa00000080e8000075300000ea6000003a98000017709cba513c000002a6504c5445ffffd6b2ccc74b8385929e70c5d2b3bed5cf7cafbb29747c00343d10444b3b829580904f506c457d8c4c919235dbde90e1e89f7b723883968a80acb5cddbb8b0cac26fa5b634767e28676d4b8995879046435a388395598c9132d1ce61e9d65c77725380aebadcdc9dc3c791c4cb8ca6beb59abcbd81adb36da1b37a8a4831462367713284893d978739e9d54e8ba58d90b5b3f7dc56b6b97bc7b457e7be41eaeebda9c2ba98b5afa7b171437766778135a9ad5b59613fb8a12ca0aa7693b7b1dec145adb17ac5a544d1a928dbde97b9ba62b39f33e9de73b8ca8aa3ac5e979a423d4e33937e17a8a86f8cafafc0ad49b1b07ec9ad2ea39e53b7c28ba79421bcaf4bcbcc6edacb4aadb97e808a483e501c787d23989e708aaca6928e409e9c50d1bb37aaae62a7bb92a39c3fbeb243acb045bdaf30b3ac3f859e754d4104816806b49d3a7f986f9a8625bba62fd7c750c7c05b9bac6e9fa046c2bf519fa84b969e47999a32838733967009835e00b49c3b879866927c1bb39d20ccb123cbba427c8b49808a43b3b03eb9aa26859645838d2a918501c49e165c4f0592934bd8d29ba27d18b6b151d6ca48e1d15b6272368c8022b7b142a8ae467083355f6719606919ceae1b72691872601c857e348f7311968322c0a943ded1855166299b8f25c4b6407f923e4f5c1a6b692588904c3c4313545b298f7927887e2a9e89239487379c852295a0543d794e84a880eeedb983a77c43541964602162692763663c3a482e787e38737d37898e427a8a5089893e5c8c5f44989472a18b86a37c62825634715e545e31676b28647d7b484f275b5e18575e224e5927616b2b6d6f2351866a27868a7aa28b5c938d1a727622808c2050493a563670939044523033452449572742522753764b486d4b498682216f7247786d5294992f7c7d216e712b5f5f245450ffffff4706cbdb00000001624b4744e15f08cfa60000000774494d4507e70c0d0a2c1237102b76000000016f724e5401cfa2779a000000fb4944415408d701f0000fff00000102030405060708090a0b0c0d0e000f101112131415161718191a1b1c1d001e1f202122232425262728292a2b2c002d2e2f303132333435363738393a3b003c3d3e3f404142434445464748494a004b4c4d4e4f50515253545556575859005a5b5c5d5e5f60616263646566676800696a6b6c6d6e6f70717273747576770078797a7b7c7d7e7f80818283848586008788898a8b8c8d8e8f90919293949500969798999a9b9c9d9e9fa0a1a2a3a400a5a6a7a8a9aaabacadaeafb0b1b2b300b4b5b6b7b8b9babbbcbdbebfc0c1c200c3c4c5c6c7c8c9cacbcccdcecfd0d100d2d3d4d5d6d7d8d9dadbdcdddedfe0b54a6271a7dabbd20000002574455874646174653a63726561746500323032332d31322d31335431303a34343a31372b30303a3030a769852a0000002574455874646174653a6d6f6469667900323032332d31322d31335431303a34343a31372b30303a3030d6343d960000000049454e44ae426082, 11.24, 47, 'Jewerly', '2023-12-13 08:46:39', NULL, NULL),
(2, 3, 'Table', 'A confortable table', 'png', 0x89504e470d0a1a0a0000000d494844520000000f0000000f08030000000c0865780000000467414d410000b18f0bfc6105000000206348524d00007a26000080840000fa00000080e8000075300000ea6000003a98000017709cba513c0000028b504c544548515a4b565e515e685769775e738361798b627b8d62788a607483566b7d556370555c6352575e4f545a4d52584a535c4e596154636f5c7182627e9366859c69879e688599647d937d7c766b706854616d545b6252575d50555b4a545d4c5c67556a785f7b8e67869e6f8ba27490a8658bae787778a86050776a56546371555b615254586751486a5851657b8e6e90ab7da0bd8ca6b584938c728a92cec0b6697a8651626f525f6951585f4e53595855566759546861606a79886782966461668e7258deb37a7f6653ddcdb57b6353563f355a4338534c4b4d535b4e555b6e544966554f6c422f7b3f21742f0b8b4826a966428f4d29ca9d7d8b4d2b6f2b086e2c0a4f49494751595059605f504c515e6a834b306e371f804a32793c207235197c3c1e7c391a7737186a3c2756382c4e5156474f575860676e554a9277619565446f4b38675a566c5146675753706564695f5e7f4d356a5d5959443e5e5b5d565c636264696d54497c6356804b306c4737715a53745c54756d6e7d7f85797f877a5d507367666f4e3f6b6668686a7079777b735a516f626083482a775a4d6d5e595f4b476a636578777b7f8188835c4a8c7b77765f58827c7f827e82a19fa2846d668d8d9486563e7b6861706a6b73696a89868a9a979aa0a3a9916b59948480837675aba7a9aaa9abb4b2b4a79f9cb6b9bd90756a8f766cb3b3b7bbb9bbc6c6c9cccdcfd3dae0a68b7fbbb0aecbcfd4cacacdcbccd0c3c3c5c7c9ccc9cbcebebabab9b5b5cbced1ccced2d2d6dbd4d8dcdfe6ec9f8c85c0bbbbdde4e9d7dbe0d9dee2d1d5dad3d7dcd5d9dedadfe4dce2e7dadfe3dbdfe5d3d4d7d9dce0dce1e5dbe0e4dce0e5d4d9ddd7dce0dee3e8e0e5eae2e7ede3e9eee2e8eee5ebf2e1e7ecdfe4e9dfe5e9ffffff9073322700000001624b4744d8000d47ae000000097048597300000ec300000ec301c76fa8640000000774494d4507e70c0d0a2b0312e19d43000000016f724e5401cfa2779a000000fb4944415408d701f0000fff00000102030405060708090a0b0c0d0e000f101112131415161718191a1b1c1d001e1f202122232425262728292a0c1d002b2c2d2e2f30313233343536373839003a3b3c3d3e3f40414243444546474800494a4b4c4d4e4f50515253545556570058595a5b5c5d5e5f60616263646566006768696a6b6c6d6e6f70717273747500767778797a7b7c7d7e7f80818283840085868788898a8b8c8d8e8f90919293009495969798999a9b9c9d9e9fa0a1a200a3a4a5a6a7a8a9aaabacadaeafb0b100b2b3b4b5b6b7b8b9babbbcbdbebfc000c1c2c3c4c5c0c4c4c6c7c8c9cacbcc00c1cdcec6cacfd0d1d2d3d4d5d6d6d704ee604d9706c0720000004174455874636f6d6d656e740043524541544f523a2067642d6a7065672076312e3020287573696e6720494a47204a50454720763632292c207175616c697479203d2039300a77755ec90000002574455874646174653a63726561746500323032332d31322d31335431303a34333a30322b30303a3030db27b16a0000002574455874646174653a6d6f6469667900323032332d31322d31335431303a34333a30322b30303a3030aa7a09d60000000049454e44ae426082, 156.39, 5, 'Home decoration', '2023-12-22 15:11:12', '2023-12-12 16:36:07', 5.5),
(3, 4, 'Generic useless product', 'A generic and useless product which moreover is not available', 'png', 0x89504e470d0a1a0a0000000d494844520000000f0000000f08030000000c08657800000c3569434350696363000048899557075853c9169e5b9290901020808094d09b209d0052426801a417c146480284126320a8d8cba2826b170bd8d05511c50e881db1b328f6be581050d6c5825d799302baee2bdf9b7c33f3e79f33ff3973eedc3200d04ff224923c5413807c71a1343e2c88392a358d49ea0454f8a30017e0cbe31748d8b1b151009681feefe5dd4d80c8fb6b8e72ad7f8effd7a2251016f001406221ce1014f0f3213e08005ec997480b0120ca798b498512398615e8486180102f90e32c25ae94e30c25deabb0498ce740dc0c801a95c7936601a07105f2cc227e16d4d0e885d8592c108901a03321f6cfcf9f2080381d625b68238158aecfcaf84127eb6f9a19839a3c5ed62056ae4551d4824505923cde94ff331dffbbe4e7c9067c58c34acd9686c7cbd70cf3763b7742a41c5321ee116744c740ac0df1079140610f314ac996852729ed51237e0107e60ce841ec2ce00547426c0471a8382f3a4ac567648a42b910c31d824e1615721321d6877881b020244165b3493a215ee50badcb9472d82afe3c4faaf02bf7f550969bc456e9bfce167255fa98467176620ac414882d8b44c9d1106b40ec54909b10a9b219519ccd891eb091cae2e5f15b421c2f14870529f5b1a24c6968bccabe34bf6060bdd8a66c11375a85f7176627862bf38335f3798af8e15ab02b42313b69404758302a6a602d0261708872ed5897509c94a0d2f920290c8a57cec52992bc58953d6e2ecc0b93f3e610bb171425a8e6e2c98570432af5f14c49616ca2324ebc38871711ab8c075f0aa2000704032690c19a0126801c206aeda9ef81ff9423a18007a4200b0881a38a199891a21811c3360114833f21128282c179418a51212882fcd74156d93a824cc5689162462e7806713e880479f0bf4c314b3ce82d193c858ce81fde79b0f261bc79b0cac7ff3d3fc07e67d890895231b2018f4cfa80253184184c0c278612ed7043dc1ff7c5a3601b08ab2bcec2bd07d6f1dd9ef08cd046784cb8416827dc192f9a23fd29ca91a01dea87aa7291f1632e706ba8e98107e17e501d2ae37ab82170c4dda11f361e003d7b4096a38a5b9e15e64fda7f5bc10f574365477626a3e421e440b2edcf3335ec353c0655e4b9fe313fca583306f3cd191cf9d93fe787ec0b601ff9b325b6003b809dc34e6117b0a3583d606227b006ac053b26c783bbeba962770d788b57c4930b7544fff0377065e5992c70ae71ee76fea21c2b144e963fa3016782648a5494955dc864c3378290c915f39d86315d9d5ddd0090bf5f948faf37718af706a2d7f29d9bfb07007e27fafbfb8f7ce7224e00b0cf0bdefe87bf73b62cf8ea5007e0fc61be4c5aa4e4707943804f093abcd30c8009b000b6703daec013f88240100222400c4804a9601c8c3e1bee73299804a681d9a0049481a56015580736822d6007d80df6837a70149c0267c1257005dc00f7e0eee9002f402f78073e2308424268080331404c112bc401714558883f12824421f1482a928e64216244864c43e62265c872641db219a946f621879153c805a40db9833c42ba91d7c8271443a9a80e6a8c5aa3c35116ca4623d144742c9a854e448bd179e862740d5a85ee42ebd053e825f406da8ebe40fb3080a9637a9819e688b1300e1683a5619998149b819562e55815568b35c2eb7c0d6bc77ab08f381167e04cdc11eee0703c09e7e313f119f8227c1dbe03afc39bf16bf823bc17ff46a0118c080e041f0297308a9045984428219413b6110e11cec07ba983f08e4824ea116d885ef05e4c25e610a7121711d713f7104f12db884f887d2412c980e440f223c59078a4425209692d6917e904e92aa983f4414d5dcd54cd552d542d4d4dac3647ad5c6da7da71b5ab6a9d6a9fc99a642bb20f39862c204f212f216f2537922f933bc89f295a141b8a1f25919243994d5943a9a59ca1dca7bc5157573757f7568f5317a9cf525fa3be57fdbcfa23f58f546daa3d95431d4395511753b7534f52ef50dfd068346b5a202d8d56485b4caba69da63da47dd0606838697035041a33352a34ea34ae6abca493e95674367d1cbd985e4e3f40bf4cefd1246b5a6b7234799a33342b340f6bded2ecd36268b968c568e56b2dd2daa97541ab4b9ba46dad1da22dd09ea7bd45fbb4f61306c6b06070187cc65cc656c61946870e51c74687ab93a353a6b35ba755a757575bd75d375977b26e85ee31dd763d4ccf5a8fab97a7b7446fbfde4dbd4f438c87b08708872c1c523be4ea90f7fa43f503f585faa5fa7bf46fe87f32601a8418e41a2c33a8377860881bda1bc6194e32dc6078c6b067a8ce50dfa1fca1a543f70fbd6b841ad91bc51b4d35da62d462d4676c621c662c315e6b7cdab8c744cf24d024c764a5c971936e5386a9bfa9c874a5e909d3e74c5d269b99c75cc36c66f69a1999859bc9cc369bb59a7d36b7314f329f63bec7fc8105c582659169b1d2a2c9a2d7d2d472a4e534cb1acbbb56642b9655b6d56aab7356efad6dac53ace75bd75b77d9e8db706d8a6d6a6ceedbd26c036c27da56d95eb723dab1ec72edd6db5db147ed3decb3ed2bec2f3ba00e9e0e2287f50e6dc308c3bc878987550dbbe54875643b1639d6383e72d2738a729ae354eff472b8e5f0b4e1cb869f1bfecdd9c339cf79abf33d176d970897392e8d2eaf5ded5df9ae15aed7dd686ea16e33dd1adc5eb93bb80bdd37b8dff660788cf498efd1e4f1d5d3cb53ea59ebd9ed65e995ee55e9758ba5c38a652d629df726780779cff43eeafdd1c7d3a7d067bfcf5fbe8ebeb9be3b7dbb46d88c108ed83ae2899fb91fcf6fb35fbb3fd33fdd7f937f7b8059002fa02ae071a045a020705b6027db8e9dc3dec57e19e41c240d3a14f49ee3c399ce39198c0587059706b78668872485ac0b79186a1e9a155a13da1be6113635ec6438213c327c59f82dae3197cfade6f64678454c8f688ea4462644ae8b7c1c651f258d6a1c898e8c18b962e4fd68ab6871747d0c88e1c6ac8879106b133b31f6481c312e36ae22ee59bc4bfcb4f873098c84f1093b13de2506252e49bc97649b244b6a4aa6278f49ae4e7e9f129cb23ca57dd4f051d3475d4a354c15a536a491d292d3b6a5f58d0e19bd6a74c7188f3125636e8eb5193b79ec857186e3f2c61d1b4f1fcf1b7f209d909e92be33fd0b2f8657c5ebcbe0665466f4f239fcd5fc178240c14a41b7d04fb85cd899e997b93cb32bcb2f6b45567776407679768f88235a277a95139eb331e77d6e4ceef6dcfebc94bc3df96af9e9f987c5dae25c71f30493099327b4491c242592f6893e13574dec95464ab7152005630b1a0a75e0877c8bcc56f68bec51917f5145d18749c9930e4cd69a2c9edc32c57ecac2299dc5a1c5bf4dc5a7f2a7364d339b367bdaa3e9ece99b672033326634cdb498396f66c7acb0593b665366e7cefe7d8ef39ce573dece4d99db38cf78deac794f7e09fba5a644a3445a726bbeeffc8d0bf005a205ad0bdd16ae5df8ad54507ab1ccb9acbceccb22fea28bbfbafcbae6d7fec5998b5b97782ed9b094b854bcf4e6b280653b966b2d2f5efe64c5c815752b992b4b57be5d357ed58572f7f28dab29ab65abdbd744ad69586bb976e9da2febb2d7dda808aad8536954b9b0f2fd7ac1faab1b0237d46e34de58b6f1d326d1a6db9bc336d7555957956f216e29daf26c6bf2d673bfb17eabde66b8ad6cdbd7ede2eded3be27734577b5557ef34dab9a406ad91d574ef1ab3ebcaeee0dd0db58eb59bf7e8ed29db0bf6caf63edf97beefe6fec8fd4d0758076a0f5a1dac3cc438545a87d44da9ebadcfae6f6f486d683b1c71b8a9d1b7f1d011a723db8f9a1dad38a67b6cc971caf179c7fb4f149fe83b2939d9732aebd493a6f14df74e8f3a7dbd39aeb9f54ce499f36743cf9e3ec73e77e2bcdff9a3177c2e1cbec8ba587fc9f3525d8b47cba1df3d7e3fd4ead95a77d9eb72c315ef2b8d6d23da8e5f0db87aea5af0b5b3d7b9d72fdd88bed17633e9e6ed5b636eb5df16dceeba9377e7d5dda2bb9fefcdba4fb85ffa40f341f943a387557fd8fdb1a7ddb3fdd8a3e0472d8f131edf7bc27ff2e269c1d32f1df39ed19e95779a765677b9761ded0eedbef27cf4f38e1792179f7b4afed4fab3f2a5edcb837f05fed5d23baab7e395f455ffeb456f0cde6c7febfeb6a92fb6efe1bbfc779fdf977e30f8b0e323ebe3b94f299f3a3f4ffa42fab2e6abddd7c66f91dfeef7e7f7f74b78529ee253008315cdcc04e0f5760068a90030e0f98c325a79fe53144479665520f09fb0f28ca8289e00d4c2eff7b81ef875730b80bd5be1f10bead3c700104b0320d11ba06e6e8375e0aca63857ca0b119e0336c57ccdc8cf00ffa628cf9c3fc4fd730fe4aaeee0e7fe5f1e2c7c97d5764158000000206348524d00007a26000080840000fa00000080e8000075300000ea6000003a98000017709cba513c0000027c504c54456dabf16caaf367a6ed92c6ef90d5f07dd4f077d3f279d3f577d2f484d8f39ce8f291f5f488f5f6509fee509eef4d9ae87bbeee78cff267cdf264cbf364cbf462caf26fcef186e2f17ff5f379f6f779f6f879f6f94194f44296f63f91ef6db7f16bcaf35dc8f45ac5f65ac6f658c4f664c8f57ae0f077f8ef74faf973f9fb73f9fc4b9af54797f076b9f473cdf564cdf760cbf861ccf760c9f56bcef57fe4f57df7f678f9f977f8fa63abe062abe05ca9dc8cc3e788d3e673d3e46dd3e66fd3e46ed1dd7dd6db95e7e28cf6e57ff8e37ef7e24b99c84b9cc9499ac876bcd873cdd564cdcd62cdcc61cccc60cac96dcfcd83e4d67ef9d378facf77f9d077f9cf4090c74193c63f92c66eb7d76ac9d45dc9ca5bc9c85cc8c959c6c867ccce7ce3d778f9d374fbcf73fad054a2ca53a5cb51a1c87fbfda7cced76ccfce68d0cc69cfcd66ceca74d3d18be5db82f8d47bfad37bfad47bfad554a5ac53a8ad4fa1a67dbfc17bcfbd6bd0af67d0ab67d0ac65cdab71d0b589e2c581f5ba79f7b57af7b67af7b744989d44989a3f91916eb8b56ccdb060cd9f5dcc9b5ecc9c5cc99a66cda77be3bb78f6ad75fba375fba44394984293983e8d906cb8b36bcbae5dc79b5bc7985cc89a5ac49865c8a77cdfbc78f3af74f8a46eb1a66cb3a967aea794cbc290d8bd7ed6af7ad7ac7cd8ae78d6b086d8bc9ee7c78ff5b786f7ae87f7ad4d9d734b9a7346927177b89f74cc9764cc7f63cd7b64ce7d61ca7b6dcf8783e49c7cf58d76f68277f6824294644294673d8c606bb49569c88d5cc6725ac56c5bc66e59c36964ca7978e19777f68874fa7547996e489b7144936a74b89c71ca9361c97b5ec8755fc9775ec77669cb877ee0a37af59376f98075f880ffffffb77d334400000001624b4744d397df9e260000000970485973000016250000162501495224f00000000774494d4507e70c0d0a2b2b275435b900000e4f7a5458745261772070726f66696c6520747970652069636300005885ad995992242b0e45ff59452fc1012160398c66bdff0df4910f91115999f5ec997564511e8e33080d57571eeebf63b8fff091230677d8678f437d3ef2a1e308feecd2a92b4b0e294896108e54524d2d1c475e99c7e36eed3822136277ea35e6980ff1e948878ce3fe7cbfffdb67b3ab49e49f8e19c37c49f62f3feedf0df75e45538e1aafdb74f76b702ad6adf37ad0e5bc4a1d1cf8c8215ff7fa4c08316734773cfdf7f5f0e250e7a9c6eb4129cf03cdeffd75bcfa3fc6f7e37d21c13297a83aae1dca313082cf41f379bfe623d19115f9f32dc9da777f3c9c2e4e5d759df73b3c0f168698baf59ab0f559a8dc0b5db7fb39412c4e7f9328ff2ea9fe20a93b1fe43f1f7c58e7eb5304f9cdef942573fd7af02fcdfffbe7ffbf10aa1d397d3f4a78b43cb4e42025ddd6f097cefc2cda754b4fe159e8f213bf06560cb2e4b6abbf26fa5db0ac7c6d70f787a3b1416283e43e7608de3c3a4abc1deef09744219af2bd88dc1ee8af8d83281b0f361eee73028655ada272dbe38ee79091288bd4f484c4e53fa114b3ada414bf2dd4882fddf8cfe7d142677d7414c57f8e1fc5fcff4dd9cfd1e642e13da5d784fb083b287b4b7df0e95e281e2ba3255c373fcabe741281438e9654d6ddbfeefe8573f6145f3aba178ac31642d2f5e74221a714d28d1be19234a6a0499781eec70962be8eac2ff33f3b94a633bff9d2ada3d840188c501f499ffe0e62d9d1a47cfa511c426c7bb3c23de1b24e9cc729917f247d9308aba5f265fe2feb2027fef24cb82e71155b287d6d704bb48ba1a584afa35d3bcbd12caebfb2c73d817cc44243f21322e1ee8f0075568cf0f2eceb2249cf5c26df620a07b69d49508f435e3a156cd650454bc7a78e4e8736690cd0442c98c888e9ba2aff298eaaed04f1b546cf6b5ccf4e1d3d93845d14d1cc530de82c7584f9b9f0f74d527ee6b090e1b5dd982273bb60f2ec63217385f04d4af3a5d78636c6043087dcf387c1ef3bdbc26fd29e13ef4d2c1f2d7d2432f1fb71a6e97340fadacd24fb7ebcc4c425f798fa3c77bf9fdf2438aff156f2b8aecf029fe3dddf27fcb1f84f63bc8d71d7e0f74176b4cf63f274ffb1a8e256ca779562f0e9708fcaa28d318bd61937aeef76dab32fd00aad7e37cafb06eeb54342fb697aaed0b38913a3e7346bda5a4bf87eccef4796e92eecb2237c3864ba3d79fde81e429aba244da7601259e812ff125b88213ba6999480bc8e843225eeebd836d1c6ca77a55bd09a047f75c8bf1ae209937ba1274cbe2d58b9543a2bc4b2b2660de5b74dbf94bdc8452ba61fa4793cfc373fd27789c20f91fec3a4df55e0ee87bf8481e57bbb3f63f1af8b3a3247211b44803fd33cad723f68f91c1cfba6316658df7cf57f4a6684fd15c9f987487f43017d10e20f0fff16fd3f45fc0bbc623c1a622fecb8c7fb22d92862c9f2225a127503f0151251e194c592aadce50489143887c090bac30824e468ed4c4d4fb1f341fd764cfe2ba558bd30c6ab2a7afb94384fb61267dfd7a777f7d34069fd1cd87dbcd86eebf5a771a1c472ed78f453a2517a85a92508847eaef8fa6699d788c6be729dafc6134f2a779cde6ed11fcdc66a76be665aba162619d9b28498ea7162ca69807261fc99049036a3f4822a8a99bf740b281a0b5416a82cd098d498d498d49964be836f1df8190497c61c23f7e0d6312d9398b21737ab9f529f45c7eea8dbfe326da28048abb40d1548349e730a8f7e3c100477a1e1471edb7ac4b7744e3141e32110e111db171641625f1983b4beb141636ce7dab922a12712fc2468fde4cba26371a53cf2bbe193916685b2d026e443691bdd16489ca7f503aa41e3199b87cc4281341d0a9d651da132101b87368c2bc15a99843e02ba0866245410763acc15e3d129b0155b119f66b5c82e50741ac109518fa4e78855625e46058f58b967f1d8b8efed0aeec9f865048c40de64a103ab09cab4c422274e5b3a02a7a1789040f80f78cd82524851e848b0a420a950bf098b092a312f910d89485826c1a713d648b04ed8f4915221c567e09856485348951aad93a6207457baa261e5f3cf9f0992b8b44ace3226d6538894e68eef0d100316daf6851ee43c5d1425e8c86239e31299cd334c35cb76b0143c8662977283e0e7218acec45a9e54c2ec9a77a104ed38f0265a0335a41e055d16dca414ee91b6b4e6706c3a9854563e0ace58bd658b440669648e7d587955293a6be17be33b0aaf83ef6c5219df08d91628211aa2357c025a4954d0890b342cd4d0459be368db0ccd1f5ca2931c3b50d34149d0f7e88ceddda207c2ded17cc784032a3c4849035718691cd493c0c23a068c795898e1b88345a7fde1a0532cd4da3151cb2c586de2f2733060ae63a28f853b2cbc6f61bdc5b156de206c271c157a198eb51661598fcdf1375ebe41869dd1d14659bb1b14ef632f0b6fb414d4123e66420119912bc1d5e99fd9667a02f9fce76503a3d3fb329cf70d758deafd2a1c2e7be2ca07493ea87842c787167d18b415919516686c129556680d971ec9810489d084be1379149b5eb479291d3a3f4187c5a680830f9e3a819a463d3903b0e83ef5e93995459bbd9a709efad5ab0eaf657bed0149f1d3dd7c061bb2784fa9e1738146b4e5f12d9f77f6051c21ce3da58e2fa5f9d2b6f36572c3c413723852d5ee2b27ad1ddf9dd348a16fa1f9866a5acec0d1f2b8866fe813b97d8fdd778dce7756ec48d267f38640b8801f896bee7ea09f31861f2878025a93289ed920ac7a2ced278b2daf9e64edfc4227ab8a5f38d45ad56fccb1e3f27047bfe9df7dfbbd5a38a114dd1378102662638019601d9602ff8ae3d06cdfd0e658815921c03ca96943287c6d3b8439493a8dc45a0258156295104708b041c072079149253c5c201b0699cda22b240627a591b9d3a06d32380b93de83bd1cd14e5b905c4f931a7226ca989f270b151cbec41d0a2e541a824c8179c3be5914c5875a67004d081e04a4dca63c0fad76d078032221f4a8a07275a133109708066d23d63014a2c0826366307384895e66d630d9792efc12af5932028201e139103281033ab0bc855d7dd81c656f902b52b829c1d788ded909045c59301f2af29deb0ed1640b1a63a81ddc1778c9743102ce31ef082b8b119309c28ad5bb7545990582126302a053a12e1f21a63da3bdd2d18ceedb8a0a67ca4844914cc2a823e69963f150f4646c70c4320ae8934e235536aa9d836fe08e655a96c849492c3b72e0d8b53b324c8b7d152c9a23942a0e8e34568c130b132d113f8d204ca44489942b7191914012cce3e3e6f9c633f60a8ecc18e5ac97da093ea8162f5442ba0dbc74210f990ad9434f1236a210ee88cf6e4618a318fb93d21c55d532d0209551ee03ca692ed160efaaa087ad0b5452d08ee4dcf13b521c09ab4817e2580af048b0400ba323ef5581c960972c8d9d1084cc3ee47cc9c3c27d058cdd04a3cae84d70296194cc8a2de69005095854d9822f5cc952c8193cdcd3123d809d7d02d9817ab0825c081c240233e1042964c21a5007d6126624af3697d058b2d724523656ee4459492973d041b480ab4a6ad55613a437a18d948924b2782a7860c93395d153f52c54b5027896f2726a4c6b8db653ea680d0e9afa62cd4803c5c6d4349176e692e6a80964a2f01c69f5e912904f0af78904a067519ff02c56231b80bf3c6980388205541aea50689282d98a736924b59cf57115a7b22adec35695205ff69679aaf29d2857f2bae68a133386c056d2b39635b4b250ad4deb3647299c7c3b3842d6cecebd71dd1b41b38eb6e1ae05f8863b409d8955787fd735f00634be210a1b48878d91e7a12d90d14c3e019c5386236060c2b921080c32a209e20f0f95eb2dffc04c800dcc3da31c3b5756805d77c939a9c3cf22098644a12b1706d7d0722dd086056c89e6d612244732009f3b7104b0e651689654a85608218ce85d5e40e06200e8008391bc172c4b324ca218a528e4a8e2c704440e900e00de0224e44290923e67c1d58bace80ace8fed3becc8b41b500c55939da9da1b7d29452b60b04a05b46b45d6bd0a6159da68854c6fd547e97bb83214c4a073025d933973efb24ee3acb22369b3ae7a567b00cf31492db04fdf4a257d00daad92ca2d585c054c801c385521e8889394764da31287380e0094614c68b3a2885a64d4d2110ca782dcd5ba074e506a9bc9d50ed6f7b62b69b00ec68c9debc4c5c84295c457575bb8cbe4a0bdeedd2098d0db4956954c6655d09d56d551be28814f23530a51459837d2524b8dcda02a68a0e13478d96a48d50a89a40c69002c232a5c66b456b66ba494d609b3be2a297db401139c021721972f72c722ed9b032391bd3a86c6265857ebd090ee27f94a48f823b88e313ae0cb0989d34a28fbd85369f03304cfb5036c785de948436813de937286ac5a4720a7f4de7a62a5e5f8667ee6fba8dd184f9f505b4e008c836954abd03a22075209953c168c4233cc01884f9ba447460048e21437c0d901dc8e249d93e10d42593da838c408c926974c4ad531c81fec55212865a08b01588cde299f49529cc30d32d600cf481c3a4005886b1e9b0350cdcca343dbe2e030d0d0b827d5d204ee27e90635d609f64e807626ea35f0234d9dc038eba1875994b4b8f04d85106377cc35dbeee81ef24b15324a837e4182ab92c1c784f1cc1daa9bbb6db214f540a75fa87706fc2691c66647c8b800af451a59b2075108ce1f73d9ef68c0c5c201c888d0803e1c1c0bdf9f1814506d0bd4045d3afa065a17c4774d7b514c55885dd726f3ed018f4ed4132b6d0f5df07b436586dbd1d70d246d21819077406bb077aead3ab79ad1609a853105af83ea100279832cbbc1e47bce1b731228c5a1c1ba2731b2a4ef35071839ed25453ece9f0efddbeb84e74705fb35e77cd1b081aeb367a67abe8920b95f4338dec76f8c5f9ff77726ef7dafabfbedc1f7fe8fdf2abf843c2583815faf7dc8a0e7ab7a89f31495224c9e339ce3cdb1af1724a7e8e97c1709fb88e7ab9ff3eade3a3e3e1fbf8abe3a2f61e1f07b4fea86a9afd737fff8d3e1c7cfa9f779eeeba3f4f82cf4d1f1767f3cd2c62fb97ebd77ff03ad5272b3dc6cc8c7000000016f724e5401cfa2779a000000fb4944415408d701f0000fff00000102030405060708090a0b0c0c0c000d0e0f101112131415161718191a1b001c1d1e1f202122232425262728292a002b2b2c2d2e2f3031323334353637370038393a3b3c3d3e3f4041424344444500464748494a4b4c4d4e4f50515253540055565758595a5b5c5d5e5f6061626200636465666768696a6b6c6d6e6f70710072737475767778797a7b7c7d7e7f80008182838485868788898a8b8c8d8e8e008f909192939495969798999a9b9b9b009c9d9e9fa0a1a2a3a4a5a6a7a8a9a900aaabacadaeafb0b1b2b3b4b5b6b7b700b8b9babbbcbdbebfc0c1c2c3c4c4c400c5c6c7c8c9cacbcccdcecfd0d1d2d20cad5d2a613390180000008a655849664d4d002a000000080004011a0005000000010000003e011b0005000000010000004601280003000000010002000087690004000000010000004e00000000000000900000000100000090000000010003928600070000001200000078a0020004000000010000000ea0030004000000010000001400000000415343494900000053637265656e73686f74dad2241b0000002574455874646174653a63726561746500323032332d31322d31335431303a34333a34322b30303a30305f6dbf900000002574455874646174653a6d6f6469667900323032332d31322d31335431303a34333a34322b30303a30302e30072c0000001274455874657869663a457869664f6666736574003738c9d47b270000001774455874657869663a506978656c5844696d656e73696f6e00313453f9a0a00000001774455874657869663a506978656c5944696d656e73696f6e003230a22feeff0000005c74455874657869663a55736572436f6d6d656e740036352c2038332c2036372c2037332c2037332c20302c20302c20302c2038332c2039392c203131342c203130312c203130312c203131302c203131352c203130342c203131312c2031313640b81f7200000028744558746963633a636f7079726967687400436f70797269676874204170706c6520496e632e2c203230323393b38f0a00000017744558746963633a6465736372697074696f6e00446973706c6179171b95b80000000049454e44ae426082, 0, 0, 'Nonee', '2023-12-13 08:45:59', NULL, NULL);

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

--
-- Dump dei dati per la tabella `ProductImages`
--

INSERT INTO `ProductImages` (`id`, `productId`, `imgExtension`, `image`) VALUES
(7, 38, 'png', 0x89504e470d0a1a0a0000000d494844520000000f0000000f08030000000c0865780000000467414d410000b18f0bfc6105000000206348524d00007a26000080840000fa00000080e8000075300000ea6000003a98000017709cba513c000002a6504c5445ffffd6b2ccc74b8385929e70c5d2b3bed5cf7cafbb29747c00343d10444b3b829580904f506c457d8c4c919235dbde90e1e89f7b723883968a80acb5cddbb8b0cac26fa5b634767e28676d4b8995879046435a388395598c9132d1ce61e9d65c77725380aebadcdc9dc3c791c4cb8ca6beb59abcbd81adb36da1b37a8a4831462367713284893d978739e9d54e8ba58d90b5b3f7dc56b6b97bc7b457e7be41eaeebda9c2ba98b5afa7b171437766778135a9ad5b59613fb8a12ca0aa7693b7b1dec145adb17ac5a544d1a928dbde97b9ba62b39f33e9de73b8ca8aa3ac5e979a423d4e33937e17a8a86f8cafafc0ad49b1b07ec9ad2ea39e53b7c28ba79421bcaf4bcbcc6edacb4aadb97e808a483e501c787d23989e708aaca6928e409e9c50d1bb37aaae62a7bb92a39c3fbeb243acb045bdaf30b3ac3f859e754d4104816806b49d3a7f986f9a8625bba62fd7c750c7c05b9bac6e9fa046c2bf519fa84b969e47999a32838733967009835e00b49c3b879866927c1bb39d20ccb123cbba427c8b49808a43b3b03eb9aa26859645838d2a918501c49e165c4f0592934bd8d29ba27d18b6b151d6ca48e1d15b6272368c8022b7b142a8ae467083355f6719606919ceae1b72691872601c857e348f7311968322c0a943ded1855166299b8f25c4b6407f923e4f5c1a6b692588904c3c4313545b298f7927887e2a9e89239487379c852295a0543d794e84a880eeedb983a77c43541964602162692763663c3a482e787e38737d37898e427a8a5089893e5c8c5f44989472a18b86a37c62825634715e545e31676b28647d7b484f275b5e18575e224e5927616b2b6d6f2351866a27868a7aa28b5c938d1a727622808c2050493a563670939044523033452449572742522753764b486d4b498682216f7247786d5294992f7c7d216e712b5f5f245450ffffff4706cbdb00000001624b4744e15f08cfa60000000774494d4507e70c0d0a301fafd60a96000000016f724e5401cfa2779a000000fb4944415408d701f0000fff00000102030405060708090a0b0c0d0e000f101112131415161718191a1b1c1d001e1f202122232425262728292a2b2c002d2e2f303132333435363738393a3b003c3d3e3f404142434445464748494a004b4c4d4e4f50515253545556575859005a5b5c5d5e5f60616263646566676800696a6b6c6d6e6f70717273747576770078797a7b7c7d7e7f80818283848586008788898a8b8c8d8e8f90919293949500969798999a9b9c9d9e9fa0a1a2a3a400a5a6a7a8a9aaabacadaeafb0b1b2b300b4b5b6b7b8b9babbbcbdbebfc0c1c200c3c4c5c6c7c8c9cacbcccdcecfd0d100d2d3d4d5d6d7d8d9dadbdcdddedfe0b54a6271a7dabbd20000002574455874646174653a63726561746500323032332d31322d31335431303a34383a33302b30303a30303ad75c570000002574455874646174653a6d6f6469667900323032332d31322d31335431303a34383a33302b30303a30304b8ae4eb0000000049454e44ae426082),
(8, 38, 'png', 0x89504e470d0a1a0a0000000d494844520000000f0000000f08030000000c0865780000000467414d410000b18f0bfc6105000000206348524d00007a26000080840000fa00000080e8000075300000ea6000003a98000017709cba513c0000029d504c54459d622e925f2d76441a3f16005025045025052b100432170650321e5d3a226a4e40916a5a5c3116d7b1919b5d2a6b32069d68316f3a13512b12532806512706280e041d06005635217c4c2e5e4033744d3d51270e6c544593562978410db984477a451b957c68593414451f01351c0d7f6b5a6b4c3778482a573c2e9b7b64411e08372a22c19d8457330e64411b4e2d13917a685b371c3d1700563621c0a992785a44703b1d704e3f705f4a7b604c958575e7d7c7230e051c080129140b6c55456043302f0c005b3d2a5537226f543c582a108b5743795244907764c4a995bba7981803022c1813200c073c2820160200513b2f220c0166513e442f20543123572917735340895d4c7750411702011d0704362117210a02352017290f05553d2f2d1000614d3c432f213f261b220d023f271b37190e39190d2005022b0f084e3326280c004d3729331505503424694c34553d2e2c150a543c2a2d0d00482a1e220b051400012107042004006e564aa8988b4b33262f15098b7564ece2d6a8998d452e214f35248a74606c52452101002409051f0604240900827063fcf6ed897464715b4cfbf6efede6defffffca09185968273f9f2e996867853392a290a03160000a3948bede3d8dfd2c7f9f3ecded2c5af9882b29c86c0ab97ece4dafdf8f2e4d8cdf8efe6b4a499260700130000a08f86c4b5a8a38f81968071a58d749d856bae977fb6a393b09d8cc0afa0cbbbae947b6b260a06432211aa88656d503a7151396f4f377b5e439c85698c775d9b8368977c649d846b9e846da58c777a624f582f1549361cb1957485684c684c33694e33a08565bca488aa9174bba386998162826b51866e559f8469826b57462e15473f2d4b453369563e5e49336c51378d7356d1bfa5e2d5c3c5b39c9b8366987f64826d5274634d3a3724ffffff936ce42300000001624b4744dee96ee29b0000000774494d4507e70c0d0a30241edde3b20000023e7a5458745261772070726f66696c65207479706520786d700000388d95554bb2dc200cdceb143902484282e3d883d9a52acb1c3f2dfc3c1f8fe725b1ab182c84bad5120cfdfef98b7ec4e3d5486e32bc7ab26c62ab15574ec656ccadd9269d791bebba0e66d89b69588a4bd12e49bb2715f8566ba4d517c7c622bee856d4f08b8022d8c42c43364e72f32a0b60b1d17a8059e614df76b3cd25d62810c0466d040f59f685bbfb64f20803db1a3bf4be8353a9da4b220e72c3a7490a6f62dcc1278b4a82c5a5c196c524c15224f30d56c65716e721b1274623eed3b860042e5c40faf4f2577a0c16264b6155b5536a4c7331d2abae78932c4867f87c787378f13619077e9616efe4c0181963df01c0c8c5519f50c42bd20242acbfb20005940a85606b53a90685e071ac5b2608361cc206ab5dd8e75a84c0ef7c27d8f62811e6429876a452c1270579489b8e5a9d436a47f77c084baf71afc32260bf082eae255a776a4c7f277d1d3c9adba1177c46842397a9116343d9e387d43b7684d710b8208846ed4a000cbbbd35650223388493cc598a0655b9375d451fa173e749dc6b3b3db93dc0a6720b692c4b14df228da24d67057727a4d6e6515d2228fa5c679717cd2a180547334b567c93469f1638558c052c824b8b3e872bbae28cac918e7889d3180c0f60ba23eb25329f913f01d303f9b8305ed0cbec65033a3e308f0aa6a7c22ff6250dfd9f3613f692217dabcd33f2076d0e60bad646dabf6a730013c77530d5e1f1fd593f7bedf7d261a5f7db785fbaf84b404afbe5efbcdfe6f40757aa6ec5f100b6ca000000016f724e5401cfa2779a000000fb4944415408d701f0000fff00000102030405060708090a0b0c0d0e000f101112131415161718191a1b1c1d001e1f202122232425262728292a2b2c002d2e2f303132333435363738393a3b003c3d3e3f404142434445464748494a004b4b4c4d4e4f50515253545556575800595a5b5c5d5e5f60616263646566670068696a6b6c6d6e6f70717273747576007778797a7b7c7d7e7f80818283848500868788898a8b8c8d8e8f90919293940095969798999a9b9c9d9e9fa0a1a2a300a4a5a6a7a830a9aaabacadaeafb0b100b2b3b4b5b6b7b8b9babbbcbdbebfc000c1c2c3c4c5c6c7c8c9cacbcccdcecf00d0d1d2d3d4d5d6d7d8d9dadbdcd1dd657161208d2dd220000000106558496649492a000800000000000000000000009c3cb9280000002574455874646174653a63726561746500323032332d31322d31335431303a34383a33352b30303a303068ef73f00000002574455874646174653a6d6f6469667900323032332d31322d31335431303a34383a33352b30303a303019b2cb4c0000000049454e44ae426082),
(9, 38, 'png', 0x89504e470d0a1a0a0000000d494844520000000f0000000f08030000000c0865780000000467414d410000b18f0bfc6105000000206348524d00007a26000080840000fa00000080e8000075300000ea6000003a98000017709cba513c000001c8504c5445f4f6fbf5f8fdf3f6fbf4f7fcf3f6fcf2f6fceff2f8edf1f6e8ecf1dfe5ecd7dfe6f6f9fdf6f9fef7fafef2f5f9f0f2f7edeff3e2e7ede4e9eff6f8fdf8faffeff1f5eeedf1eef0f3fbfefff7f8fcf6f7faf0f2f6eef0f4edeff4e8ebf0f7f8fbfbfdffe3e7eaacbabec2c3c8dfe0e5f5f8fcf7f9fef8f9fcf8f9fbf9fafceeeef0f2f3f5f5f6f8e9ebf0fafcfef5f5f9afbabe95b2b8b1bfc6c7c5c9e0e1e6fffffffdfdfefdfeffcfd3d7e0e2e6f9fafdeceff3fcfeffeeeef1bbbec29fbcc0abc6c7ceccd0dedee2eef1f4dfe5e9faf8faa0b6bdbccad0fffefff0f3f7fbfcfebfbec2b6bec0adb5b8c4c3c6e6e5e8a0aeb08ca6a9dfe1e4aebcc2ced8dcf5f6fafafbfdfeffffd4d5d8b5b4b8b6b5b8e3e4e6e1e1e483929193a8a9b8bcbdf6f6f7f9f9fbf8f9fde4e5e9f3f3f5e9e9ecd5d2d7b6c5cad4eff1d0d4d9fdfdfffafcfdf8fafcfcfdfee0e0e4fcfcfdeaeaeee4e4e9fafbfec1c3c8b6bfc3d6d5dbf8fafefbfdfedcdce0dbdbdfd2d1d7d5d5daf2f2f4e2e1e4e6e6eae8e9ecf9fbfdd3d3d7b6b5bbe5e6e8d5d5d9e2e2e4dddce1c1c0c5f5f6f9fcfdfff9fafefefffefdfefefbfdfdf8f9faf4f5f7ebecefe4e5e7dedfe1d0d1d4fbfcfffafbfffefefefcfefed015d97700000001624b474435deb6d96b0000000774494d4507e70c0d0a3029606c9f0f000000016f724e5401cfa2779a000000f44944415408d76360606462620602165636760e4e2e0666666e1e5e9000331fbf80a01083b088a898b804b7b0b0a494b48cac1c83bc82a292b28aaa9aba86a696b68e2e839ebe81a191b189a999a6b985a59535838dad9dbd83a393b38bb9ab9bbb8727839786b78faf9f7f4060906970886928435878446454744c6c5cbc69426258124398694c724a6a5a7a46669657764e1243ae795e7e4161517149a94e6e587619837979456555b5464d6d5d7d7858032f8399696353734bab565b7b4767915e1743774f6f5fff848993264feaf49a32652ac3b4702030350d370def2c9fe23585c1d4d4b4a767faf4dc5cafce4e73af4e00b67f40b781744be90000002574455874646174653a63726561746500323032332d31322d31335431303a34383a34302b30303a30303012554e0000002574455874646174653a6d6f6469667900323032332d31322d31335431303a34383a34302b30303a3030414fedf20000000049454e44ae426082),
(10, 39, 'png', 0x89504e470d0a1a0a0000000d494844520000000f0000000f08030000000c0865780000000467414d410000b18f0bfc6105000000206348524d00007a26000080840000fa00000080e8000075300000ea6000003a98000017709cba513c0000028b504c544548515a4b565e515e685769775e738361798b627b8d62788a607483566b7d556370555c6352575e4f545a4d52584a535c4e596154636f5c7182627e9366859c69879e688599647d937d7c766b706854616d545b6252575d50555b4a545d4c5c67556a785f7b8e67869e6f8ba27490a8658bae787778a86050776a56546371555b615254586751486a5851657b8e6e90ab7da0bd8ca6b584938c728a92cec0b6697a8651626f525f6951585f4e53595855566759546861606a79886782966461668e7258deb37a7f6653ddcdb57b6353563f355a4338534c4b4d535b4e555b6e544966554f6c422f7b3f21742f0b8b4826a966428f4d29ca9d7d8b4d2b6f2b086e2c0a4f49494751595059605f504c515e6a834b306e371f804a32793c207235197c3c1e7c391a7737186a3c2756382c4e5156474f575860676e554a9277619565446f4b38675a566c5146675753706564695f5e7f4d356a5d5959443e5e5b5d565c636264696d54497c6356804b306c4737715a53745c54756d6e7d7f85797f877a5d507367666f4e3f6b6668686a7079777b735a516f626083482a775a4d6d5e595f4b476a636578777b7f8188835c4a8c7b77765f58827c7f827e82a19fa2846d668d8d9486563e7b6861706a6b73696a89868a9a979aa0a3a9916b59948480837675aba7a9aaa9abb4b2b4a79f9cb6b9bd90756a8f766cb3b3b7bbb9bbc6c6c9cccdcfd3dae0a68b7fbbb0aecbcfd4cacacdcbccd0c3c3c5c7c9ccc9cbcebebabab9b5b5cbced1ccced2d2d6dbd4d8dcdfe6ec9f8c85c0bbbbdde4e9d7dbe0d9dee2d1d5dad3d7dcd5d9dedadfe4dce2e7dadfe3dbdfe5d3d4d7d9dce0dce1e5dbe0e4dce0e5d4d9ddd7dce0dee3e8e0e5eae2e7ede3e9eee2e8eee5ebf2e1e7ecdfe4e9dfe5e9ffffff9073322700000001624b4744d8000d47ae000000097048597300000ec300000ec301c76fa8640000000774494d4507e70c0d0a302d67015b16000000016f724e5401cfa2779a000000fb4944415408d701f0000fff00000102030405060708090a0b0c0d0e000f101112131415161718191a1b1c1d001e1f202122232425262728292a0c1d002b2c2d2e2f30313233343536373839003a3b3c3d3e3f40414243444546474800494a4b4c4d4e4f50515253545556570058595a5b5c5d5e5f60616263646566006768696a6b6c6d6e6f70717273747500767778797a7b7c7d7e7f80818283840085868788898a8b8c8d8e8f90919293009495969798999a9b9c9d9e9fa0a1a200a3a4a5a6a7a8a9aaabacadaeafb0b100b2b3b4b5b6b7b8b9babbbcbdbebfc000c1c2c3c4c5c0c4c4c6c7c8c9cacbcc00c1cdcec6cacfd0d1d2d3d4d5d6d6d704ee604d9706c0720000004174455874636f6d6d656e740043524541544f523a2067642d6a7065672076312e3020287573696e6720494a47204a50454720763632292c207175616c697479203d2039300a77755ec90000002574455874646174653a63726561746500323032332d31322d31335431303a34383a34342b30303a3030c45d715d0000002574455874646174653a6d6f6469667900323032332d31322d31335431303a34383a34342b30303a3030b500c9e10000000049454e44ae426082),
(11, 39, 'png', 0x89504e470d0a1a0a0000000d494844520000000f0000000f08030000000c0865780000000467414d410000b18f0bfc6105000000206348524d00007a26000080840000fa00000080e8000075300000ea6000003a98000017709cba513c000002a3504c54456462606c6a696b6b6a5d5f5d515552545853595e5b787b7b8184838183828f93937f85866f71719da0a39fa2a46563616d6c6a6566655f625d5e5f5d5c605c6669686c6d6d7a7a7a8a8b8a9398997a7f7f7777779ea1a39fa1a464625f6e6c6966666561625e747573696a696b6f707a7977847f7e747676a2a09d9793907675749ca0a29b9ea0514f4d6b6966777673515251626162635e60534e4b655a516a645f6d6863cbc4bfddd5cf7d787596989996999a4844424a454148423c413a35524a44685d554e443d4e443c625a5378716b847e7a746f6a7a79768f91929295974443415b5a57343231100e0c3c39364c4847524f4d43444656595b6467685356552221207575749799989a9a993537375c5c59747471383a392224244745474442434e4d4e73706a43423fada89fbfb8b0d1cbc1d7d1c7514c4b5857566967636d6964212120868279857b6cb2a48eb5a793a2998d989086a5a198ece5dadbd5cbddd7cd5048487e736ac2ac9250463a6c6356b9ae9eb4a38dcebfaab4a6948d8578ddd0bd787269b7b1a6d8cfc1d5cec1aa9479a7947e6e635461574bb6a38b8e877c988973a89781a99883b2a695cec1b0baaea06d6962c5bcb0cac1b5af95798e7a634b4135b8a187af9980968d80a89682af9a82b4a28db8a691b6a693bfb09ca89c8cbdb3a7ccc2b79f856b90796184715c9f8a7198846b8b8276ac9c88ad9a83b19e85b3a189b1a18cb5a591bfb1a1bbafa2bfb4a78c7359856f587e6b578d7860907a618b7d6b9f8f799f8d76a5957faa9b87a39484b0a291b5a899b7ac9fbbb1a78d775e957e63a68e71a68f73a08c74a18e759b8a749a8a759d8e7ba49786a89989b2a394b7aa99baac9cb4a89c9e8a70a69071ac977aa59076a08c73a08b739e8e799f917fa69785a79889aa9d8ea5988aa09488a89d90ada396ffffff70aac8af00000001624b4744e0280fff300000000774494d4507e70c0d0a3032ea0956e300000046744558745261772070726f66696c652074797065206170703132000a61707031320a20202020202031350a3434373536333662373930303031303030343030303030303263303030300a6abf7e2f000002bc7a5458745261772070726f66696c65207479706520786d700000388d95555b92e33008fce7147b04191048c77122eb6fabf6738fbf0d4a328993a9a98d676c1921689a87e9efef3ff42b7ead1592ab4c6f5e6c33b18b55572ec656cdaddb2183f99897cb653243de4d43525daa0e293abca840b759276dbe3b0e56f15d8faa86270c8ae010b34c39b8c8d59becde0c076d8433dbb8c4bb5ded70893d0a0f40a3360387ec6be3a19e48becc40768913fa38c1a5361db51007b8e92992ca87180fe0d944a441e2d221dba44a97262cca5748590a64c613cf8efb264a3c52b88728ef007dbaf8161e0385c95e5955ed141a536e4678cd1557911de14ccf1f1f0e2d3e12b1a7e71e572261dc19f7b11c00918b233fc1883784050fb1ff8a0210902a2482ad27531d0c41e3be6f1b81b0e92036502d629f731104eb40aadf30a7c3e32b4d8417906d03e134602a1100e8c51306c73d6b4fc6c5b5469d9d08a14fd6bf371e95e8080e3a33ccb9643c6c4228c5b41fbc7c0aece7a0965bfae4f76ef2c6cfae91b5be9a0606ae1a26c46b946da449bb6a56f63a04cb9addb44b8527141dd615c5891483918e64a258d13a266824ac5a14221239040191e08ed6d8f01f870ab6e40dd107046700b410a8e9a6ac55f0aa15950bb3128db2e14db0075b786225d0520eb974f854e864d9d08be7284c89d2b420b0be79eef7d05f1cc7afd2ff7abee5720f7210e806b748455542f4d3ae6fa3a16058cd54925c9598122a8fce773433c6478ec36c305aaadc1fddf54a72cdee423a0c2f58479996a7eadeed4607fdc807928eab5a50801188646bd2e1b98a4aee511404ab15236c400907f28f2507400c5b0c323e21f8a614e865cca6d242e26f2c01dd169373f99366519e0b2f30516e3634c6c8a155b3667b0ed62056e38983316c378e61964cf23c4f2a7a1d5567b535561fd2b76fc9da890f1a9dbe68887b7dbb9cd7c788fe01abb49b4bc26fb656000000016f724e5401cfa2779a000000fb4944415408d701f0000fff00000102030405060708090a0b0c0d0e000f101112131415161718191a1b1c1d001e1f202122232425262728292a2b2c002d2e2f303132333435363738393a3b003c3d3e3f404142434445464748494a004b4c4d4e4f50515253545556575859005a5b5c5d5e5f6061106263646566670068696a6b6c6d6e6f70717273747576007778797a7b7c7d7e7f80818283848500868788898a8b8c8d8e8f90919293940095969798999a9b9c9d9e9fa0a1a2a300a4a5a6a7a8a9aaabacadaeafb0b1b200b3b4b5b6b7b8b9babbbcbdbebfc0c100c2c3c4c5c6c7c8c9cacbcccdcecfd000d1d2d3d4d5d6d7d8d9dadbdcdddedf68f761a19fa4f2aa000000106558496649492a000800000000000000000000009c3cb9280000002574455874646174653a63726561746500323032332d31322d31335431303a34383a34392b30303a3030a58a109d0000002574455874646174653a6d6f6469667900323032332d31322d31335431303a34383a34392b30303a3030d4d7a8210000000049454e44ae426082),
(12, 39, 'png', 0x89504e470d0a1a0a0000000d494844520000000f0000000f08030000000c0865780000000467414d410000b18f0bfc6105000000206348524d00007a26000080840000fa00000080e8000075300000ea6000003a98000017709cba513c000002a0504c54456c615760564d6271879aaece9493894b47397c7d73827e756f655cc7bba9c1b8abbfb8abaa9e8b9e9384afa79b51443c5b4f494d5059848a9caaaca559584975756976726a675d53beb1a0b8aea0beb8a8c0b6a0c3bdacbeb39d524741857975444345707077abada68c958d85877d756e635b534cb6a899b3a798beb8a699978adde0d5d0ccb55c5350453d3b33302f626061736f6cb4aba1d2c7b49b8c7a735f4db8a390b7aa9ab9b7a299978bd2cab8c5b99e2d26240a0202514b4ba59b9ca29590ceb09fdfbb9ee8b992eaaf7de9b889e9bb8fdeb68eceb89bd2c0a5c2b09824221f562225c99b8fe4b796e1a476e9aa75f1bf91eebe93e7b181eeb786e0b28acaaf93b8a691b8ab9fd5b99644352eab7853eea66df0ab70eeb079e7b07fe7b68ae8b68aecb384cea8867c71688b87876365729c948ecbb39613110f2c241d69513da37957cf986cdea779d7a87ed4a77ea98c736a6059514f5485838c9992919b948da19a942e2b271c1a19110f0f6764656d5f52806247bd9b8196887e514c4c9385827a7377958e8cb4ab9fd0c2aec0b7ac36333258524d70675d938f8e716e6a66615a7c756c99938b7f766e9e918c9c938fa9a296b9ad9ebaaea1c0bab4958d829f95897b73677e79758a8481726a62716961a09b956b675e837f74b3ab9cb4aa9ca59a8ebdb4afd3cbc4898174837b6f857e70766e61766c61827871847b74a29e9a766f6778736b8a8277baab9ba9a198c7c0bbe3dbd28e7f71867a6c7a70627f7466776b5c746758817669938d8579726a8a8175bbad9e9a9490c7beb8ddd6cb71665b8c7c6e8072647b70627c736482786c857d6f978e80a69a8aac9e8ebcae9c8f887e8f8884c0b7b0d7cfc62c2927564e468b7f71897d6f84796b877e709a9083c7b9a8d3c2adada0915c595789837dada59dc8c0b8ffffff5d64545d00000001624b4744df9e69d20d000000097048597300000ec300000ec301c76fa8640000000774494d4507e70c0d0a30379a63a26c0000036a7a5458745261772070726f66696c65207479706520786d7000005885d5595b72a33010fc9f53ec11248d3403c7c120feb66a3ff7f8db236c2362c5b1c356054c0563348fee79c90ef4f7f71ffa8517a7e888479eb553275e582e923406274192a8f492790a9ae7cbe53207c5fd5ea2dd490ad5895d9cd445866c273dc54e078562621d624e51f00e83cc500aca336737f0a81d0fda0914653267e283b3cf324a56b635320f401365361c3c2c0b777120b99a0a90111e528831ca073358232c9aa94e230ec70354672daf90155221cb0c410d337beeedc095e38073c0795a1c84899415b130efda85c93c60bda07043702b12c04068403c48af0ef23d386420bbae136805f804114316d4d4b7c0a32f686140c223fae23a8ba755c88c5dfd646470960962881e04412b6476b788992b1e370e068a45f4b65c934948acfa75a55e03da7ea565c5436003851415cc9135ab9250a261740390a515c5cddc164bb4b825c40884120a2d23ca6ec904de15675cf318e696daaa7573414d1f09b51e635fcc7748468449b112b5443338432ae20a56398a5da1203bc1edb28c75a8c4a2e20d0fea3b3530556e56cad4c003c3573409e6524193508a1e4e3c30f4f8d481fec6056d559f05157d880420fcdd3d35798d223d17ac531fe63af975c1d80ab56aa61197aada6a936be350dd39ef77d8da60f4598741901b73c195321dd799607238a320471416a68f4d807b57f96d5715a383e1c1df58c76ec56ebd366a7cda510567f9e4258b1ace3272acb605359ecc02996768cdf035632a17bee6d56677b4661855808491a7a779a37b5837c86ca94da89d3314e4ffc9194fd4ce19ca31b6a6f96d1ede368b7504d2a272cd166fb3753567c3d622d759ca11510f4417a0b35c0333a3dbd93648ab8e96c2b3883c0684be1791c780d0b24fda1efa4e44a0e1d5d59b02551159ccd5c314b46c26e018111bb6824455f942f883347d147f3d32b5540a643bf9fdf6c35785eda87b76d06b623f6be85532a7a0f63d32c7a4b6d9b20f816887a1cf12734a6aaf55d949a87da7650e4b6d7fff1f87dae627c42110ed30d44ecc49a9bd5265a7a1f67ecb1c98dadefe3f12b585cb9110ed30d44acc69a97d5d6527a2f66ecb1c9adabefe3f16b5f273fd508876187a4ccc4f23da61e8ab2a3b15b5f75ae6e0d4f6f4ffd1a8b9e168887618b27fa6d58ff8e8fa8c2f495e9ee6a92e8fe7e81f5606b8c1a281fce5000000016f724e5401cfa2779a000000fb4944415408d701f0000fff00000102030405060708090a0b0c0d0e000f101112131415161718191a1b1c1d001e1f202122232425262728292a2b2c002d2e2f303132333435363738393a3b003c3d3e3f404142434445464748494a004b4c4d4e4f50515253545556575859005a5b5c5d5e5f60616263646566676800696a6b6c6d6e6f70717273747576770078797a7b7c7d7e7f80818283848586008788898a8b8c8d8e8f90919293949500969798999a9b9c9d9e9fa0a1a2a3a400a5a6a7a8a9aaabacadaeafb0b1b2b300b4b5b6b7b8b9babb9bbcbdbebfc0c100c2c3c4c5c6c7c8c9cacbcccdcecfd000d1d2d3d4d5d6a7d7d8d9dadbdcdddeabba61f41ebcedd0000000b46558496649492a000800000006001201030001000000010000001a01050001000000560000001b010500010000005e0000002801030001000000020000001302030001000000010000006987040001000000660000000000000060000000010000006000000001000000060000900700040000003032313001910700040000000102030000a00700040000003031303001a0030001000000ffff000002a0040001000000dc05000003a0040001000000dc05000000000000b07215ef0000002574455874646174653a63726561746500323032332d31322d31335431303a34383a35342b30303a303008f771c30000002574455874646174653a6d6f6469667900323032332d31322d31335431303a34383a35342b30303a303079aac97f0000001574455874657869663a436f6c6f725370616365003635353335337b006e0000002774455874657869663a436f6d706f6e656e7473436f6e66696775726174696f6e00312c20322c20332c203055a423bf0000001374455874657869663a457869664f666673657400313032734229a70000001f74455874657869663a4578696656657273696f6e0034382c2035302c2034392c203438d29f88ba0000002374455874657869663a466c61736850697856657273696f6e0034382c2034392c2034382c203438efd9076b0000001974455874657869663a506978656c5844696d656e73696f6e0031353030c155326f0000001974455874657869663a506978656c5944696d656e73696f6e003135303078aee9870000001774455874657869663a5943624372506f736974696f6e696e670031ac0f806300000016744558747064663a417574686f720041726b696d65646533363099510aa90000001574455874786d703a43726561746f72546f6f6c0043616e7661eac712b10000000049454e44ae426082),
(13, 39, 'png', 0x89504e470d0a1a0a0000000d494844520000000f0000000f08030000000c0865780000000467414d410000b18f0bfc6105000000206348524d00007a26000080840000fa00000080e8000075300000ea6000003a98000017709cba513c000002a0504c5445dfd9d3dcd6cfd8d1caddd8d1b2aba4654c3c532a0e5130179d917dc5cac3c2c9c595a6a36e8a907b7667a48a69ced3d3d4d6d5d8dad7e0dfd8aa9f926e584a3d2816493420aca089d3dcd89daeb05e797d5c7d887e786aa88f6ebfd3e8bed5eac3daf1c9d0d2a79e9475655f312315483827b1a389bac5bc758c916a838c5a7988af9776c5d1e3c5d4eab7c8deb5b5adbfb9b4776a693b2a1a624f3accbb9db0bab3657b7f6f8486647d7e8b836eb59e7e91a3a78d9a9299a49aabaea4c4bdb7786c69503b297f6b51e7d6bab8bfbc7283748591726e7b5b97886cbca78782837f716a5f877865b8b9a7c0b5a9786964694f3b8f785cece0c7cecfc8777d69797e64586248b1a07fd5c0a0919093887c7b927166d3d4cdb3a699a0948c5d4d4286725ce2d0bacfd6c28ea87f8da48296a691d7c9a8f8ead4bebfc1ada29be8e4e3d1cbc7a3999094908e79726ca19183a18876cbccbcb6c6a19ea9839ab781d8cea1fcf0e0d0d2d8c5c4c7d4d9e3b7b1ae998b787c6a5a8773628d867bb5ab9bdcd7cec2bcb6b5a495cdd5c5dfd3b1fbefddc5cedec5cdddcad5e7b5b5b9816d577c664da2978a898176ada59d9e979181776d9f8c73eeecece9dbc0f9eed8c7cfe0b1b6c18a898b9088858c7b6b7f6b52a0968b81756aa6a29ca9a8a7a39994aa9f98e7e7e6ebddc2f9f1e0b0b6c3c1c2c6aca8a39b9792867b72705e4d76645891877e978f85abacab9e9b98b7b4b2d6d7d9e6cfabf8e0b79ea1a8abaeb38c8a8973716f7e757099979593857c91857d91867ba29c97a09c98a19e9a938e8996846fdcba8ba5a09ea4a09faea6a2827d79766d677e76716e5a4f4837315647406b5b527e6f6478706981776c8f8272958571a2968ea0948d93877fa99e959a908a62595558524e5547405e4e45655347725f52796e637a70657b6e5efffffffae1a18200000001624b4744df9e69d20d0000000774494d4507e70c0d0a303b93d5ee47000000016f724e5401cfa2779a000000fb4944415408d701f0000fff00000102030405060708090a0b0c0d0e000f101112131415161718191a1b1c1d001e1f202122232425262728292a1c2b002c2d2e2f303132333435363738393a003b3c3d3e3f40414243444546474849004a4b4c4d4e4f50515253545556575800595a5b5c5d5e5f60616263646566670068696a6b6c6d6e6f70717273747576007778797a7b7c7d7e7f80818283848500868788898a8b8c8d8e8f90919293940095969798999a9b9c9d9e9fa0a1a2a300a4a5a6a7a8a9aaabacadaeafb0b1b200b3b4b5b6b7b8b9babbbcbdbebfc0c100c2c3c4c5c6c7c8c9cacbcccdcecfd000d1d2d1d3d4d5d6d7d8d9dadbdcddde6543619ff74b8c2e0000002574455874646174653a63726561746500323032332d31322d31335431303a34383a35382b30303a3030cf571bb70000002574455874646174653a6d6f6469667900323032332d31322d31335431303a34383a35382b30303a3030be0aa30b0000000049454e44ae426082),
(14, 40, 'png', 0x89504e470d0a1a0a0000000d494844520000000f0000000f08030000000c08657800000c3569434350696363000048899557075853c9169e5b9290901020808094d09b209d0052426801a417c146480284126320a8d8cba2826b170bd8d05511c50e881db1b328f6be581050d6c5825d799302baee2bdf9b7c33f3e79f33ff3973eedc3200d04ff224923c5413807c71a1343e2c88392a358d49ea0454f8a30017e0cbe31748d8b1b151009681feefe5dd4d80c8fb6b8e72ad7f8effd7a2251016f001406221ce1014f0f3213e08005ec997480b0120ca798b498512398615e8486180102f90e32c25ae94e30c25deabb0498ce740dc0c801a95c7936601a07105f2cc227e16d4d0e885d8592c108901a03321f6cfcf9f2080381d625b68238158aecfcaf84127eb6f9a19839a3c5ed62056ae4551d4824505923cde94ff331dffbbe4e7c9067c58c34acd9686c7cbd70cf3763b7742a41c5321ee116744c740ac0df1079140610f314ac996852729ed51237e0107e60ce841ec2ce00547426c0471a8382f3a4ac567648a42b910c31d824e1615721321d6877881b020244165b3493a215ee50badcb9472d82afe3c4faaf02bf7f550969bc456e9bfce167255fa98467176620ac414882d8b44c9d1106b40ec54909b10a9b219519ccd891eb091cae2e5f15b421c2f14870529f5b1a24c6968bccabe34bf6060bdd8a66c11375a85f7176627862bf38335f3798af8e15ab02b42313b69404758302a6a602d0261708872ed5897509c94a0d2f920290c8a57cec52992bc58953d6e2ecc0b93f3e610bb171425a8e6e2c98570432af5f14c49616ca2324ebc38871711ab8c075f0aa2000704032690c19a0126801c206aeda9ef81ff9423a18007a4200b0881a38a199891a21811c3360114833f21128282c179418a51212882fcd74156d93a824cc5689162462e7806713e880479f0bf4c314b3ce82d193c858ce81fde79b0f261bc79b0cac7ff3d3fc07e67d890895231b2018f4cfa80253184184c0c278612ed7043dc1ff7c5a3601b08ab2bcec2bd07d6f1dd9ef08cd046784cb8416827dc192f9a23fd29ca91a01dea87aa7291f1632e706ba8e98107e17e501d2ae37ab82170c4dda11f361e003d7b4096a38a5b9e15e64fda7f5bc10f574365477626a3e421e440b2edcf3335ec353c0655e4b9fe313fca583306f3cd191cf9d93fe787ec0b601ff9b325b6003b809dc34e6117b0a3583d606227b006ac053b26c783bbeba962770d788b57c4930b7544fff0377065e5992c70ae71ee76fea21c2b144e963fa3016782648a5494955dc864c3378290c915f39d86315d9d5ddd0090bf5f948faf37718af706a2d7f29d9bfb07007e27fafbfb8f7ce7224e00b0cf0bdefe87bf73b62cf8ea5007e0fc61be4c5aa4e4707943804f093abcd30c8009b000b6703daec013f88240100222400c4804a9601c8c3e1bee73299804a681d9a0049481a56015580736822d6007d80df6837a70149c0267c1257005dc00f7e0eee9002f402f78073e2308424268080331404c112bc401714558883f12824421f1482a928e64216244864c43e62265c872641db219a946f621879153c805a40db9833c42ba91d7c8271443a9a80e6a8c5aa3c35116ca4623d144742c9a854e448bd179e862740d5a85ee42ebd053e825f406da8ebe40fb3080a9637a9819e688b1300e1683a5619998149b819562e55815568b35c2eb7c0d6bc77ab08f381167e04cdc11eee0703c09e7e313f119f8227c1dbe03afc39bf16bf823bc17ff46a0118c080e041f0297308a9045984428219413b6110e11cec07ba983f08e4824ea116d885ef05e4c25e610a7121711d713f7104f12db884f887d2412c980e440f223c59078a4425209692d6917e904e92aa983f4414d5dcd54cd552d542d4d4dac3647ad5c6da7da71b5ab6a9d6a9fc99a642bb20f39862c204f212f216f2537922f933bc89f295a141b8a1f25919243994d5943a9a59ca1dca7bc5157573757f7568f5317a9cf525fa3be57fdbcfa23f58f546daa3d95431d4395511753b7534f52ef50dfd068346b5a202d8d56485b4caba69da63da47dd0606838697035041a33352a34ea34ae6abca493e95674367d1cbd985e4e3f40bf4cefd1246b5a6b7234799a33342b340f6bded2ecd36268b968c568e56b2dd2daa97541ab4b9ba46dad1da22dd09ea7bd45fbb4f61306c6b06070187cc65cc656c61946870e51c74687ab93a353a6b35ba755a757575bd75d375977b26e85ee31dd763d4ccf5a8fab97a7b7446fbfde4dbd4f438c87b08708872c1c523be4ea90f7fa43f503f585faa5fa7bf46fe87f32601a8418e41a2c33a8377860881bda1bc6194e32dc6078c6b067a8ce50dfa1fca1a543f70fbd6b841ad91bc51b4d35da62d462d4676c621c662c315e6b7cdab8c744cf24d024c764a5c971936e5386a9bfa9c874a5e909d3e74c5d269b99c75cc36c66f69a1999859bc9cc369bb59a7d36b7314f329f63bec7fc8105c582659169b1d2a2c9a2d7d2d472a4e534cb1acbbb56642b9655b6d56aab7356efad6dac53ace75bd75b77d9e8db706d8a6d6a6ceedbd26c036c27da56d95eb723dab1ec72edd6db5db147ed3decb3ed2bec2f3ba00e9e0e2287f50e6dc308c3bc878987550dbbe54875643b1639d6383e72d2738a729ae354eff472b8e5f0b4e1cb869f1bfecdd9c339cf79abf33d176d970897392e8d2eaf5ded5df9ae15aed7dd686ea16e33dd1adc5eb93bb80bdd37b8dff660788cf498efd1e4f1d5d3cb53ea59ebd9ed65e995ee55e9758ba5c38a652d629df726780779cff43eeafdd1c7d3a7d067bfcf5fbe8ebeb9be3b7dbb46d88c108ed83ae2899fb91fcf6fb35fbb3fd33fdd7f937f7b8059002fa02ae071a045a020705b6027db8e9dc3dec57e19e41c240d3a14f49ee3c399ce39198c0587059706b78668872485ac0b79186a1e9a155a13da1be6113635ec6438213c327c59f82dae3197cfade6f64678454c8f688ea4462644ae8b7c1c651f258d6a1c898e8c18b962e4fd68ab6871747d0c88e1c6ac8879106b133b31f6481c312e36ae22ee59bc4bfcb4f873098c84f1093b13de2506252e49bc97649b244b6a4aa6278f49ae4e7e9f129cb23ca57dd4f051d3475d4a354c15a536a491d292d3b6a5f58d0e19bd6a74c7188f3125636e8eb5193b79ec857186e3f2c61d1b4f1fcf1b7f209d909e92be33fd0b2f8657c5ebcbe0665466f4f239fcd5fc178240c14a41b7d04fb85cd899e997b93cb32bcb2f6b45567776407679768f88235a277a95139eb331e77d6e4ceef6dcfebc94bc3df96af9e9f987c5dae25c71f30493099327b4491c242592f6893e13574dec95464ab7152005630b1a0a75e0877c8bcc56f68bec51917f5145d18749c9930e4cd69a2c9edc32c57ecac2299dc5a1c5bf4dc5a7f2a7364d339b367bdaa3e9ece99b672033326634cdb498396f66c7acb0593b665366e7cefe7d8ef39ce573dece4d99db38cf78deac794f7e09fba5a644a3445a726bbeeffc8d0bf005a205ad0bdd16ae5df8ad54507ab1ccb9acbceccb22fea28bbfbafcbae6d7fec5998b5b97782ed9b094b854bcf4e6b280653b966b2d2f5efe64c5c815752b992b4b57be5d357ed58572f7f28dab29ab65abdbd744ad69586bb976e9da2febb2d7dda808aad8536954b9b0f2fd7ac1faab1b0237d46e34de58b6f1d326d1a6db9bc336d7555957956f216e29daf26c6bf2d673bfb17eabde66b8ad6cdbd7ede2eded3be27734577b5557ef34dab9a406ad91d574ef1ab3ebcaeee0dd0db58eb59bf7e8ed29db0bf6caf63edf97beefe6fec8fd4d0758076a0f5a1dac3cc438545a87d44da9ebadcfae6f6f486d683b1c71b8a9d1b7f1d011a723db8f9a1dad38a67b6cc971caf179c7fb4f149fe83b2939d9732aebd493a6f14df74e8f3a7dbd39aeb9f54ce499f36743cf9e3ec73e77e2bcdff9a3177c2e1cbec8ba587fc9f3525d8b47cba1df3d7e3fd4ead95a77d9eb72c315ef2b8d6d23da8e5f0db87aea5af0b5b3d7b9d72fdd88bed17633e9e6ed5b636eb5df16dceeba9377e7d5dda2bb9fefcdba4fb85ffa40f341f943a387557fd8fdb1a7ddb3fdd8a3e0472d8f131edf7bc27ff2e269c1d32f1df39ed19e95779a765677b9761ded0eedbef27cf4f38e1792179f7b4afed4fab3f2a5edcb837f05fed5d23baab7e395f455ffeb456f0cde6c7febfeb6a92fb6efe1bbfc779fdf977e30f8b0e323ebe3b94f299f3a3f4ffa42fab2e6abddd7c66f91dfeef7e7f7f74b78529ee253008315cdcc04e0f5760068a90030e0f98c325a79fe53144479665520f09fb0f28ca8289e00d4c2eff7b81ef875730b80bd5be1f10bead3c700104b0320d11ba06e6e8375e0aca63857ca0b119e0336c57ccdc8cf00ffa628cf9c3fc4fd730fe4aaeee0e7fe5f1e2c7c97d5764158000000206348524d00007a26000080840000fa00000080e8000075300000ea6000003a98000017709cba513c000002a6504c54454c4d574542494a41465b555c6f707f7c8192535157322f325453597778827170798f93a18688905f5f65454042625859584c4b594a476353517166685a5253312a29201a19433d3f5f5b605854595753584d47494d413f3a2a24747077474144554b4e817679897d7c6f65604f4741342b2655504b5f5f575853565952564c4240443835251e1ed9b293be8863766360716a717b716f8c82819a9c8672614e8988768a957786807d867c877c777e6b666e433e43f4ceaeeeb488705c54464142615349816869b6ae9c81634b806f62a2aa8d90887f7f757c726f6e5c58602e2d33d9a683e0a47b7360594a484d645a568e7b82bfbaaa9c826b826c56999c76948b8281757e7f7d805d5a63312e33b0714dc8865c7560573d3a3f564f579c8a9bcdc5bbb8ad9d9f957b938b6e90837d8879817e797d5a585f3a3335b4744bb47046785e535a51516958598b6f6ea68e808f7d6f7767586a5949604d495f4f51564948403737372c29cf9974b07854674a3b4537355c54557c6c69634d3f5545345b52475a534d32241e291f1d2c282632282533231fd8b59cd5a5838c6c59453b396a66668f7c7879604f938673a0999060575441312a322520332b263a2e28352927aba099a19289887971675b597c6a6d766c6578776dc6c8c7d9dce3b9b9bd7d78793e3631362a2539312d302a2a7a6a657f6d66584e4a9e999bc9c8cec1c4c6c5c9cbdfe3e8ced4ddb1b1b779716f5e524e6b6462807f81403b3a64574f8f82797c766f928b85b4b0ab918d87918c8accc9ceafa9af8076764740394c443d66605c635e5c433e398f857cb6afa5c6c3bbc3c0b7cdcac1c1bcb4776a62685c595e51503c2f2a40352d685d547c746d837a71797066b3ada3c5c0b6d0cfc7d2d1c9d3d1c9cecec67978725b5b565957524c4a467b7973b6b2a9b3afa6a9a39b9a9088ffffff2a48d14200000001624b4744e15f08cfa60000000970485973000016250000162501495224f00000000774494d4507e70c0d0a31043ca8f23b00000e4f7a5458745261772070726f66696c6520747970652069636300005885ad995992242b0e45ff59452fc1012160398c66bdff0df4910f91115999f5ec997564511e8e33080d57571eeebf63b8fff091230677d8678f437d3ef2a1e308feecd2a92b4b0e294896108e54524d2d1c475e99c7e36eed3822136277ea35e6980ff1e948878ce3fe7cbfffdb67b3ab49e49f8e19c37c49f62f3feedf0df75e45538e1aafdb74f76b702ad6adf37ad0e5bc4a1d1cf8c8215ff7fa4c08316734773cfdf7f5f0e250e7a9c6eb4129cf03cdeffd75bcfa3fc6f7e37d21c13297a83aae1dca313082cf41f379bfe623d19115f9f32dc9da777f3c9c2e4e5d759df73b3c0f168698baf59ab0f559a8dc0b5db7fb39412c4e7f9328ff2ea9fe20a93b1fe43f1f7c58e7eb5304f9cdef942573fd7af02fcdfffbe7ffbf10aa1d397d3f4a78b43cb4e42025ddd6f097cefc2cda754b4fe159e8f213bf06560cb2e4b6abbf26fa5db0ac7c6d70f787a3b1416283e43e7608de3c3a4abc1deef09744219af2bd88dc1ee8af8d83281b0f361eee73028655ada272dbe38ee79091288bd4f484c4e53fa114b3ada414bf2dd4882fddf8cfe7d142677d7414c57f8e1fc5fcff4dd9cfd1e642e13da5d784fb083b287b4b7df0e95e281e2ba3255c373fcabe741281438e9654d6ddbfeefe8573f6145f3aba178ac31642d2f5e74221a714d28d1be19234a6a0499781eec70962be8eac2ff33f3b94a633bff9d2ada3d840188c501f499ffe0e62d9d1a47cfa511c426c7bb3c23de1b24e9cc729917f247d9308aba5f265fe2feb2027fef24cb82e71155b287d6d704bb48ba1a584afa35d3bcbd12caebfb2c73d817cc44243f21322e1ee8f0075568cf0f2eceb2249cf5c26df620a07b69d49508f435e3a156cd650454bc7a78e4e8736690cd0442c98c888e9ba2aff298eaaed04f1b546cf6b5ccf4e1d3d93845d14d1cc530de82c7584f9b9f0f74d527ee6b090e1b5dd982273bb60f2ec63217385f04d4af3a5d78636c6043087dcf387c1ef3bdbc26fd29e13ef4d2c1f2d7d2432f1fb71a6e97340fadacd24fb7ebcc4c425f798fa3c77bf9fdf2438aff156f2b8aecf029fe3dddf27fcb1f84f63bc8d71d7e0f74176b4cf63f274ffb1a8e256ca779562f0e9708fcaa28d318bd61937aeef76dab32fd00aad7e37cafb06eeb54342fb697aaed0b38913a3e7346bda5a4bf87eccef4796e92eecb2237c3864ba3d79fde81e429aba244da7601259e812ff125b88213ba6999480bc8e843225eeebd836d1c6ca77a55bd09a047f75c8bf1ae209937ba1274cbe2d58b9543a2bc4b2b2660de5b74dbf94bdc8452ba61fa4793cfc373fd27789c20f91fec3a4df55e0ee87bf8481e57bbb3f63f1af8b3a3247211b44803fd33cad723f68f91c1cfba6316658df7cf57f4a6684fd15c9f987487f43017d10e20f0fff16fd3f45fc0bbc623c1a622fecb8c7fb22d92862c9f2225a127503f0151251e194c592aadce50489143887c090bac30824e468ed4c4d4fb1f341fd764cfe2ba558bd30c6ab2a7afb94384fb61267dfd7a777f7d34069fd1cd87dbcd86eebf5a771a1c472ed78f453a2517a85a92508847eaef8fa6699d788c6be729dafc6134f2a779cde6ed11fcdc66a76be665aba162619d9b28498ea7162ca69807261fc99049036a3f4822a8a99bf740b281a0b5416a82cd098d498d498d49964be836f1df8190497c61c23f7e0d6312d9398b21737ab9f529f45c7eea8dbfe326da28048abb40d1548349e730a8f7e3c100477a1e1471edb7ac4b7744e3141e32110e111db171641625f1983b4beb141636ce7dab922a12712fc2468fde4cba26371a53cf2bbe193916685b2d026e443691bdd16489ca7f503aa41e3199b87cc4281341d0a9d651da132101b87368c2bc15a99843e02ba0866245410763acc15e3d129b0155b119f66b5c82e50741ac109518fa4e78855625e46058f58b967f1d8b8efed0aeec9f865048c40de64a103ab09cab4c422274e5b3a02a7a1789040f80f78cd82524851e848b0a420a950bf098b092a312f910d89485826c1a713d648b04ed8f4915221c567e09856485348951aad93a6207457baa261e5f3cf9f0992b8b44ace3226d6538894e68eef0d100316daf6851ee43c5d1425e8c86239e31299cd334c35cb76b0143c8662977283e0e7218acec45a9e54c2ec9a77a104ed38f0265a0335a41e055d16dca414ee91b6b4e6706c3a9854563e0ace58bd658b440669648e7d587955293a6be17be33b0aaf83ef6c5219df08d91628211aa2357c025a4954d0890b342cd4d0459be368db0ccd1f5ca2931c3b50d34149d0f7e88ceddda207c2ded17cc784032a3c4849035718691cd493c0c23a068c795898e1b88345a7fde1a0532cd4da3151cb2c586de2f2733060ae63a28f853b2cbc6f61bdc5b156de206c271c157a198eb51661598fcdf1375ebe41869dd1d14659bb1b14ef632f0b6fb414d4123e66420119912bc1d5e99fd9667a02f9fce76503a3d3fb329cf70d758deafd2a1c2e7be2ca07493ea87842c787167d18b415919516686c129556680d971ec9810489d084be1379149b5eb479291d3a3f4187c5a680830f9e3a819a463d3903b0e83ef5e93995459bbd9a709efad5ab0eaf657bed0149f1d3dd7c061bb2784fa9e1738146b4e5f12d9f77f6051c21ce3da58e2fa5f9d2b6f36572c3c413723852d5ee2b27ad1ddf9dd348a16fa1f9866a5acec0d1f2b8866fe813b97d8fdd778dce7756ec48d267f38640b8801f896bee7ea09f31861f2878025a93289ed920ac7a2ced278b2daf9e64edfc4227ab8a5f38d45ad56fccb1e3f27047bfe9df7dfbbd5a38a114dd1378102662638019601d9602ff8ae3d06cdfd0e658815921c03ca96943287c6d3b8439493a8dc45a0258156295104708b041c072079149253c5c201b0699cda22b240627a591b9d3a06d32380b93de83bd1cd14e5b905c4f931a7226ca989f270b151cbec41d0a2e541a824c8179c3be5914c5875a67004d081e04a4dca63c0fad76d078032221f4a8a07275a133109708066d23d63014a2c0826366307384895e66d630d9792efc12af5932028201e139103281033ab0bc855d7dd81c656f902b52b829c1d788ded909045c59301f2af29deb0ed1640b1a63a81ddc1778c9743102ce31ef082b8b119309c28ad5bb7545990582126302a053a12e1f21a63da3bdd2d18ceedb8a0a67ca4844914cc2a823e69963f150f4646c70c4320ae8934e235536aa9d836fe08e655a96c849492c3b72e0d8b53b324c8b7d152c9a23942a0e8e34568c130b132d113f8d204ca44489942b7191914012cce3e3e6f9c633f60a8ecc18e5ac97da093ea8162f5442ba0dbc74210f990ad9434f1236a210ee88cf6e4618a318fb93d21c55d532d0209551ee03ca692ed160efaaa087ad0b5452d08ee4dcf13b521c09ab4817e2580af048b0400ba323ef5581c960972c8d9d1084cc3ee47cc9c3c27d058cdd04a3cae84d70296194cc8a2de69005095854d9822f5cc952c8193cdcd3123d809d7d02d9817ab0825c081c240233e1042964c21a5007d6126624af3697d058b2d724523656ee4459492973d041b480ab4a6ad55613a437a18d948924b2782a7860c93395d153f52c54b5027896f2726a4c6b8db653ea680d0e9afa62cd4803c5c6d4349176e692e6a80964a2f01c69f5e912904f0af78904a067519ff02c56231b80bf3c6980388205541aea50689282d98a736924b59cf57115a7b22adec35695205ff69679aaf29d2857f2bae68a133386c056d2b39635b4b250ad4deb3647299c7c3b3842d6cecebd71dd1b41b38eb6e1ae05f8863b409d8955787fd735f00634be210a1b48878d91e7a12d90d14c3e019c5386236060c2b921080c32a209e20f0f95eb2dffc04c800dcc3da31c3b5756805d77c939a9c3cf22098644a12b1706d7d0722dd086056c89e6d612244732009f3b7104b0e651689654a85608218ce85d5e40e06200e8008391bc172c4b324ca218a528e4a8e2c704440e900e00de0224e44290923e67c1d58bace80ace8fed3becc8b41b500c55939da9da1b7d29452b60b04a05b46b45d6bd0a6159da68854c6fd547e97bb83214c4a073025d933973efb24ee3acb22369b3ae7a567b00cf31492db04fdf4a257d00daad92ca2d585c054c801c385521e8889394764da31287380e0094614c68b3a2885a64d4d2110ca782dcd5ba074e506a9bc9d50ed6f7b62b69b00ec68c9debc4c5c84295c457575bb8cbe4a0bdeedd2098d0db4956954c6655d09d56d551be28814f23530a51459837d2524b8dcda02a68a0e13478d96a48d50a89a40c69002c232a5c66b456b66ba494d609b3be2a297db401139c021721972f72c722ed9b032391bd3a86c6265857ebd090ee27f94a48f823b88e313ae0cb0989d34a28fbd85369f03304cfb5036c785de948436813de937286ac5a4720a7f4de7a62a5e5f8667ee6fba8dd184f9f505b4e008c836954abd03a22075209953c168c4233cc01884f9ba447460048e21437c0d901dc8e249d93e10d42593da838c408c926974c4ad531c81fec55212865a08b01588cde299f49529cc30d32d600cf481c3a4005886b1e9b0350cdcca343dbe2e030d0d0b827d5d204ee27e90635d609f64e807626ea35f0234d9dc038eba1875994b4b8f04d85106377cc35dbeee81ef24b15324a837e4182ab92c1c784f1cc1daa9bbb6db214f540a75fa87706fc2691c66647c8b800af451a59b2075108ce1f73d9ef68c0c5c201c888d0803e1c1c0bdf9f1814506d0bd4045d3afa065a17c4774d7b514c55885dd726f3ed018f4ed4132b6d0f5df07b436586dbd1d70d246d21819077406bb077aead3ab79ad1609a853105af83ea100279832cbbc1e47bce1b731228c5a1c1ba2731b2a4ef35071839ed25453ece9f0efddbeb84e74705fb35e77cd1b081aeb367a67abe8920b95f4338dec76f8c5f9ff77726ef7dafabfbedc1f7fe8fdf2abf843c2583815faf7dc8a0e7ab7a89f31495224c9e339ce3cdb1af1724a7e8e97c1709fb88e7ab9ff3eade3a3e3e1fbf8abe3a2f61e1f07b4fea86a9afd737fff8d3e1c7cfa9f779eeeba3f4f82cf4d1f1767f3cd2c62fb97ebd77ff03ad5272b3dc6cc8c7000000016f724e5401cfa2779a000000fb4944415408d701f0000fff00000102030405060708090a0b0c0d0e000f101112131415161718191a1b1c1d001e1f202122232425262728292a2b2c002d2e2f303132333435363738393a3b003c3d3e3f404142434445464748494a004b4c4d4e4f50515253545556575859005a5b5c5d5e5f60616263646566676800696a6b6c6d6e6f70717273747576770078797a7b7c7d7e7f80818283848586008788898a8b8c8d8e8f90919293949500969798999a9b9c9d9e9fa0a1a2a3a400a5a6a7a8a9aaabacadaeafb0b1b2b300b4b5b6b7b8b9babbbcbdbebfc0c1c200c3c4c5c6c7c8c9cacbcccdcecfd0d100d2d3d4d5d6d7d8d9dadbdcdddedfe0b54a6271a7dabbd20000008a655849664d4d002a000000080004011a0005000000010000003e011b0005000000010000004601280003000000010002000087690004000000010000004e00000000000000900000000100000090000000010003928600070000001200000078a002000400000001000001c6a003000400000001000002ae00000000415343494900000053637265656e73686f744f298a090000002574455874646174653a63726561746500323032332d31322d31335431303a34393a30332b30303a30306a722a170000002574455874646174653a6d6f6469667900323032332d31322d31335431303a34393a30332b30303a30301b2f92ab0000001274455874657869663a457869664f6666736574003738c9d47b270000001874455874657869663a506978656c5844696d656e73696f6e003435343ae3b2da0000001874455874657869663a506978656c5944696d656e73696f6e00363836ffc898a30000005c74455874657869663a55736572436f6d6d656e740036352c2038332c2036372c2037332c2037332c20302c20302c20302c2038332c2039392c203131342c203130312c203130312c203131302c203131352c203130342c203131312c2031313640b81f7200000028744558746963633a636f7079726967687400436f70797269676874204170706c6520496e632e2c203230323393b38f0a00000017744558746963633a6465736372697074696f6e00446973706c6179171b95b80000000049454e44ae426082);
INSERT INTO `ProductImages` (`id`, `productId`, `imgExtension`, `image`) VALUES
(15, 40, 'png', 0x89504e470d0a1a0a0000000d494844520000000f0000000f08030000000c08657800000c3569434350696363000048899557075853c9169e5b9290901020808094d09b209d0052426801a417c146480284126320a8d8cba2826b170bd8d05511c50e881db1b328f6be581050d6c5825d799302baee2bdf9b7c33f3e79f33ff3973eedc3200d04ff224923c5413807c71a1343e2c88392a358d49ea0454f8a30017e0cbe31748d8b1b151009681feefe5dd4d80c8fb6b8e72ad7f8effd7a2251016f001406221ce1014f0f3213e08005ec997480b0120ca798b498512398615e8486180102f90e32c25ae94e30c25deabb0498ce740dc0c801a95c7936601a07105f2cc227e16d4d0e885d8592c108901a03321f6cfcf9f2080381d625b68238158aecfcaf84127eb6f9a19839a3c5ed62056ae4551d4824505923cde94ff331dffbbe4e7c9067c58c34acd9686c7cbd70cf3763b7742a41c5321ee116744c740ac0df1079140610f314ac996852729ed51237e0107e60ce841ec2ce00547426c0471a8382f3a4ac567648a42b910c31d824e1615721321d6877881b020244165b3493a215ee50badcb9472d82afe3c4faaf02bf7f550969bc456e9bfce167255fa98467176620ac414882d8b44c9d1106b40ec54909b10a9b219519ccd891eb091cae2e5f15b421c2f14870529f5b1a24c6968bccabe34bf6060bdd8a66c11375a85f7176627862bf38335f3798af8e15ab02b42313b69404758302a6a602d0261708872ed5897509c94a0d2f920290c8a57cec52992bc58953d6e2ecc0b93f3e610bb171425a8e6e2c98570432af5f14c49616ca2324ebc38871711ab8c075f0aa2000704032690c19a0126801c206aeda9ef81ff9423a18007a4200b0881a38a199891a21811c3360114833f21128282c179418a51212882fcd74156d93a824cc5689162462e7806713e880479f0bf4c314b3ce82d193c858ce81fde79b0f261bc79b0cac7ff3d3fc07e67d890895231b2018f4cfa80253184184c0c278612ed7043dc1ff7c5a3601b08ab2bcec2bd07d6f1dd9ef08cd046784cb8416827dc192f9a23fd29ca91a01dea87aa7291f1632e706ba8e98107e17e501d2ae37ab82170c4dda11f361e003d7b4096a38a5b9e15e64fda7f5bc10f574365477626a3e421e440b2edcf3335ec353c0655e4b9fe313fca583306f3cd191cf9d93fe787ec0b601ff9b325b6003b809dc34e6117b0a3583d606227b006ac053b26c783bbeba962770d788b57c4930b7544fff0377065e5992c70ae71ee76fea21c2b144e963fa3016782648a5494955dc864c3378290c915f39d86315d9d5ddd0090bf5f948faf37718af706a2d7f29d9bfb07007e27fafbfb8f7ce7224e00b0cf0bdefe87bf73b62cf8ea5007e0fc61be4c5aa4e4707943804f093abcd30c8009b000b6703daec013f88240100222400c4804a9601c8c3e1bee73299804a681d9a0049481a56015580736822d6007d80df6837a70149c0267c1257005dc00f7e0eee9002f402f78073e2308424268080331404c112bc401714558883f12824421f1482a928e64216244864c43e62265c872641db219a946f621879153c805a40db9833c42ba91d7c8271443a9a80e6a8c5aa3c35116ca4623d144742c9a854e448bd179e862740d5a85ee42ebd053e825f406da8ebe40fb3080a9637a9819e688b1300e1683a5619998149b819562e55815568b35c2eb7c0d6bc77ab08f381167e04cdc11eee0703c09e7e313f119f8227c1dbe03afc39bf16bf823bc17ff46a0118c080e041f0297308a9045984428219413b6110e11cec07ba983f08e4824ea116d885ef05e4c25e610a7121711d713f7104f12db884f887d2412c980e440f223c59078a4425209692d6917e904e92aa983f4414d5dcd54cd552d542d4d4dac3647ad5c6da7da71b5ab6a9d6a9fc99a642bb20f39862c204f212f216f2537922f933bc89f295a141b8a1f25919243994d5943a9a59ca1dca7bc5157573757f7568f5317a9cf525fa3be57fdbcfa23f58f546daa3d95431d4395511753b7534f52ef50dfd068346b5a202d8d56485b4caba69da63da47dd0606838697035041a33352a34ea34ae6abca493e95674367d1cbd985e4e3f40bf4cefd1246b5a6b7234799a33342b340f6bded2ecd36268b968c568e56b2dd2daa97541ab4b9ba46dad1da22dd09ea7bd45fbb4f61306c6b06070187cc65cc656c61946870e51c74687ab93a353a6b35ba755a757575bd75d375977b26e85ee31dd763d4ccf5a8fab97a7b7446fbfde4dbd4f438c87b08708872c1c523be4ea90f7fa43f503f585faa5fa7bf46fe87f32601a8418e41a2c33a8377860881bda1bc6194e32dc6078c6b067a8ce50dfa1fca1a543f70fbd6b841ad91bc51b4d35da62d462d4676c621c662c315e6b7cdab8c744cf24d024c764a5c971936e5386a9bfa9c874a5e909d3e74c5d269b99c75cc36c66f69a1999859bc9cc369bb59a7d36b7314f329f63bec7fc8105c582659169b1d2a2c9a2d7d2d472a4e534cb1acbbb56642b9655b6d56aab7356efad6dac53ace75bd75b77d9e8db706d8a6d6a6ceedbd26c036c27da56d95eb723dab1ec72edd6db5db147ed3decb3ed2bec2f3ba00e9e0e2287f50e6dc308c3bc878987550dbbe54875643b1639d6383e72d2738a729ae354eff472b8e5f0b4e1cb869f1bfecdd9c339cf79abf33d176d970897392e8d2eaf5ded5df9ae15aed7dd686ea16e33dd1adc5eb93bb80bdd37b8dff660788cf498efd1e4f1d5d3cb53ea59ebd9ed65e995ee55e9758ba5c38a652d629df726780779cff43eeafdd1c7d3a7d067bfcf5fbe8ebeb9be3b7dbb46d88c108ed83ae2899fb91fcf6fb35fbb3fd33fdd7f937f7b8059002fa02ae071a045a020705b6027db8e9dc3dec57e19e41c240d3a14f49ee3c399ce39198c0587059706b78668872485ac0b79186a1e9a155a13da1be6113635ec6438213c327c59f82dae3197cfade6f64678454c8f688ea4462644ae8b7c1c651f258d6a1c898e8c18b962e4fd68ab6871747d0c88e1c6ac8879106b133b31f6481c312e36ae22ee59bc4bfcb4f873098c84f1093b13de2506252e49bc97649b244b6a4aa6278f49ae4e7e9f129cb23ca57dd4f051d3475d4a354c15a536a491d292d3b6a5f58d0e19bd6a74c7188f3125636e8eb5193b79ec857186e3f2c61d1b4f1fcf1b7f209d909e92be33fd0b2f8657c5ebcbe0665466f4f239fcd5fc178240c14a41b7d04fb85cd899e997b93cb32bcb2f6b45567776407679768f88235a277a95139eb331e77d6e4ceef6dcfebc94bc3df96af9e9f987c5dae25c71f30493099327b4491c242592f6893e13574dec95464ab7152005630b1a0a75e0877c8bcc56f68bec51917f5145d18749c9930e4cd69a2c9edc32c57ecac2299dc5a1c5bf4dc5a7f2a7364d339b367bdaa3e9ece99b672033326634cdb498396f66c7acb0593b665366e7cefe7d8ef39ce573dece4d99db38cf78deac794f7e09fba5a644a3445a726bbeeffc8d0bf005a205ad0bdd16ae5df8ad54507ab1ccb9acbceccb22fea28bbfbafcbae6d7fec5998b5b97782ed9b094b854bcf4e6b280653b966b2d2f5efe64c5c815752b992b4b57be5d357ed58572f7f28dab29ab65abdbd744ad69586bb976e9da2febb2d7dda808aad8536954b9b0f2fd7ac1faab1b0237d46e34de58b6f1d326d1a6db9bc336d7555957956f216e29daf26c6bf2d673bfb17eabde66b8ad6cdbd7ede2eded3be27734577b5557ef34dab9a406ad91d574ef1ab3ebcaeee0dd0db58eb59bf7e8ed29db0bf6caf63edf97beefe6fec8fd4d0758076a0f5a1dac3cc438545a87d44da9ebadcfae6f6f486d683b1c71b8a9d1b7f1d011a723db8f9a1dad38a67b6cc971caf179c7fb4f149fe83b2939d9732aebd493a6f14df74e8f3a7dbd39aeb9f54ce499f36743cf9e3ec73e77e2bcdff9a3177c2e1cbec8ba587fc9f3525d8b47cba1df3d7e3fd4ead95a77d9eb72c315ef2b8d6d23da8e5f0db87aea5af0b5b3d7b9d72fdd88bed17633e9e6ed5b636eb5df16dceeba9377e7d5dda2bb9fefcdba4fb85ffa40f341f943a387557fd8fdb1a7ddb3fdd8a3e0472d8f131edf7bc27ff2e269c1d32f1df39ed19e95779a765677b9761ded0eedbef27cf4f38e1792179f7b4afed4fab3f2a5edcb837f05fed5d23baab7e395f455ffeb456f0cde6c7febfeb6a92fb6efe1bbfc779fdf977e30f8b0e323ebe3b94f299f3a3f4ffa42fab2e6abddd7c66f91dfeef7e7f7f74b78529ee253008315cdcc04e0f5760068a90030e0f98c325a79fe53144479665520f09fb0f28ca8289e00d4c2eff7b81ef875730b80bd5be1f10bead3c700104b0320d11ba06e6e8375e0aca63857ca0b119e0336c57ccdc8cf00ffa628cf9c3fc4fd730fe4aaeee0e7fe5f1e2c7c97d5764158000000206348524d00007a26000080840000fa00000080e8000075300000ea6000003a98000017709cba513c000002a0504c544586827c807c797b797985848482807e8c888396918c938e8974706c5d5752796f63807b6f736e687c76717f79748b8781817e7b7675758080807e7d7b8f8d89928f8a8885826f6d6b5e5a58796e66756e646c696576726d7975708c8882817f7b6b6a6a74737274726e82807c807e7a7c7a776b69665d5854766f6977726c6564623837353432308f8a8383817d6e6c6777736c7b776f7f7d797b7a7778777463615f4f4c48655d53756d616e69634f4a444a453f8a87827e7c797a78738a86808e8b867674707b7874716e6880807b859082716c64766f667d77708e857992877983837f7f7f7c7c7c797f7c76837f747f7a72837c76928d849f9f98918a789280759690878d8a84a39e96a0988e83837e76767373726f837f78847f75928e8998948d948f8b776659998176b5b0a8a9a69fa5a19895918982827e7f7f7a817f7a9a979195928c88857f89857b867e7697677b805c5f735b528e89819e9a919f9b91a19e9683827d7f7c757d7a72827f77807b72847f73847f727c736786526692606e6b5f5a999994aba79daca9a0a6a39a77746d716e677470687e7a727d796f716b60726c60867270704d546860548379779f92929e9b92a09c9494908667625a84817aa09e96a8a69ea4a29a7d7a739c9a91a6929269574c57654c736d667a6a659f998fa29b92a7a09684807894928b97948c9592899c998f9c9a94b5b5b08c7c816c5c557a7f6e5c504d746561b1aba2a9a49eb1a9a19c98928a87808c898194918887847b8e8c859094938f94977a7c747f817950443e736960aea69ea69f96aaa39a9c9a9f7c7773807c758f8a82837e767d83846e787c717b7c838d8d8e877f998d83b0a69cada69ca49e95c1c5d87f7d80665f5a736b646e686078746e767b7c515b635b5b5b6d6f6f767a79949999a3a29da69f959b968dffffffcf94e1bc00000001624b4744df9e69d20d0000000970485973000016250000162501495224f00000000774494d4507e70c0d0a3108351ebe1000000e4f7a5458745261772070726f66696c6520747970652069636300005885ad995992242b0e45ff59452fc1012160398c66bdff0df4910f91115999f5ec997564511e8e33080d57571eeebf63b8fff091230677d8678f437d3ef2a1e308feecd2a92b4b0e294896108e54524d2d1c475e99c7e36eed3822136277ea35e6980ff1e948878ce3fe7cbfffdb67b3ab49e49f8e19c37c49f62f3feedf0df75e45538e1aafdb74f76b702ad6adf37ad0e5bc4a1d1cf8c8215ff7fa4c08316734773cfdf7f5f0e250e7a9c6eb4129cf03cdeffd75bcfa3fc6f7e37d21c13297a83aae1dca313082cf41f379bfe623d19115f9f32dc9da777f3c9c2e4e5d759df73b3c0f168698baf59ab0f559a8dc0b5db7fb39412c4e7f9328ff2ea9fe20a93b1fe43f1f7c58e7eb5304f9cdef942573fd7af02fcdfffbe7ffbf10aa1d397d3f4a78b43cb4e42025ddd6f097cefc2cda754b4fe159e8f213bf06560cb2e4b6abbf26fa5db0ac7c6d70f787a3b1416283e43e7608de3c3a4abc1deef09744219af2bd88dc1ee8af8d83281b0f361eee73028655ada272dbe38ee79091288bd4f484c4e53fa114b3ada414bf2dd4882fddf8cfe7d142677d7414c57f8e1fc5fcff4dd9cfd1e642e13da5d784fb083b287b4b7df0e95e281e2ba3255c373fcabe741281438e9654d6ddbfeefe8573f6145f3aba178ac31642d2f5e74221a714d28d1be19234a6a0499781eec70962be8eac2ff33f3b94a633bff9d2ada3d840188c501f499ffe0e62d9d1a47cfa511c426c7bb3c23de1b24e9cc729917f247d9308aba5f265fe2feb2027fef24cb82e71155b287d6d704bb48ba1a584afa35d3bcbd12caebfb2c73d817cc44243f21322e1ee8f0075568cf0f2eceb2249cf5c26df620a07b69d49508f435e3a156cd650454bc7a78e4e8736690cd0442c98c888e9ba2aff298eaaed04f1b546cf6b5ccf4e1d3d93845d14d1cc530de82c7584f9b9f0f74d527ee6b090e1b5dd982273bb60f2ec63217385f04d4af3a5d78636c6043087dcf387c1ef3bdbc26fd29e13ef4d2c1f2d7d2432f1fb71a6e97340fadacd24fb7ebcc4c425f798fa3c77bf9fdf2438aff156f2b8aecf029fe3dddf27fcb1f84f63bc8d71d7e0f74176b4cf63f274ffb1a8e256ca779562f0e9708fcaa28d318bd61937aeef76dab32fd00aad7e37cafb06eeb54342fb697aaed0b38913a3e7346bda5a4bf87eccef4796e92eecb2237c3864ba3d79fde81e429aba244da7601259e812ff125b88213ba6999480bc8e843225eeebd836d1c6ca77a55bd09a047f75c8bf1ae209937ba1274cbe2d58b9543a2bc4b2b2660de5b74dbf94bdc8452ba61fa4793cfc373fd27789c20f91fec3a4df55e0ee87bf8481e57bbb3f63f1af8b3a3247211b44803fd33cad723f68f91c1cfba6316658df7cf57f4a6684fd15c9f987487f43017d10e20f0fff16fd3f45fc0bbc623c1a622fecb8c7fb22d92862c9f2225a127503f0151251e194c592aadce50489143887c090bac30824e468ed4c4d4fb1f341fd764cfe2ba558bd30c6ab2a7afb94384fb61267dfd7a777f7d34069fd1cd87dbcd86eebf5a771a1c472ed78f453a2517a85a92508847eaef8fa6699d788c6be729dafc6134f2a779cde6ed11fcdc66a76be665aba162619d9b28498ea7162ca69807261fc99049036a3f4822a8a99bf740b281a0b5416a82cd098d498d498d49964be836f1df8190497c61c23f7e0d6312d9398b21737ab9f529f45c7eea8dbfe326da28048abb40d1548349e730a8f7e3c100477a1e1471edb7ac4b7744e3141e32110e111db171641625f1983b4beb141636ce7dab922a12712fc2468fde4cba26371a53cf2bbe193916685b2d026e443691bdd16489ca7f503aa41e3199b87cc4281341d0a9d651da132101b87368c2bc15a99843e02ba0866245410763acc15e3d129b0155b119f66b5c82e50741ac109518fa4e78855625e46058f58b967f1d8b8efed0aeec9f865048c40de64a103ab09cab4c422274e5b3a02a7a1789040f80f78cd82524851e848b0a420a950bf098b092a312f910d89485826c1a713d648b04ed8f4915221c567e09856485348951aad93a6207457baa261e5f3cf9f0992b8b44ace3226d6538894e68eef0d100316daf6851ee43c5d1425e8c86239e31299cd334c35cb76b0143c8662977283e0e7218acec45a9e54c2ec9a77a104ed38f0265a0335a41e055d16dca414ee91b6b4e6706c3a9854563e0ace58bd658b440669648e7d587955293a6be17be33b0aaf83ef6c5219df08d91628211aa2357c025a4954d0890b342cd4d0459be368db0ccd1f5ca2931c3b50d34149d0f7e88ceddda207c2ded17cc784032a3c4849035718691cd493c0c23a068c795898e1b88345a7fde1a0532cd4da3151cb2c586de2f2733060ae63a28f853b2cbc6f61bdc5b156de206c271c157a198eb51661598fcdf1375ebe41869dd1d14659bb1b14ef632f0b6fb414d4123e66420119912bc1d5e99fd9667a02f9fce76503a3d3fb329cf70d758deafd2a1c2e7be2ca07493ea87842c787167d18b415919516686c129556680d971ec9810489d084be1379149b5eb479291d3a3f4187c5a680830f9e3a819a463d3903b0e83ef5e93995459bbd9a709efad5ab0eaf657bed0149f1d3dd7c061bb2784fa9e1738146b4e5f12d9f77f6051c21ce3da58e2fa5f9d2b6f36572c3c413723852d5ee2b27ad1ddf9dd348a16fa1f9866a5acec0d1f2b8866fe813b97d8fdd778dce7756ec48d267f38640b8801f896bee7ea09f31861f2878025a93289ed920ac7a2ced278b2daf9e64edfc4227ab8a5f38d45ad56fccb1e3f27047bfe9df7dfbbd5a38a114dd1378102662638019601d9602ff8ae3d06cdfd0e658815921c03ca96943287c6d3b8439493a8dc45a0258156295104708b041c072079149253c5c201b0699cda22b240627a591b9d3a06d32380b93de83bd1cd14e5b905c4f931a7226ca989f270b151cbec41d0a2e541a824c8179c3be5914c5875a67004d081e04a4dca63c0fad76d078032221f4a8a07275a133109708066d23d63014a2c0826366307384895e66d630d9792efc12af5932028201e139103281033ab0bc855d7dd81c656f902b52b829c1d788ded909045c59301f2af29deb0ed1640b1a63a81ddc1778c9743102ce31ef082b8b119309c28ad5bb7545990582126302a053a12e1f21a63da3bdd2d18ceedb8a0a67ca4844914cc2a823e69963f150f4646c70c4320ae8934e235536aa9d836fe08e655a96c849492c3b72e0d8b53b324c8b7d152c9a23942a0e8e34568c130b132d113f8d204ca44489942b7191914012cce3e3e6f9c633f60a8ecc18e5ac97da093ea8162f5442ba0dbc74210f990ad9434f1236a210ee88cf6e4618a318fb93d21c55d532d0209551ee03ca692ed160efaaa087ad0b5452d08ee4dcf13b521c09ab4817e2580af048b0400ba323ef5581c960972c8d9d1084cc3ee47cc9c3c27d058cdd04a3cae84d70296194cc8a2de69005095854d9822f5cc952c8193cdcd3123d809d7d02d9817ab0825c081c240233e1042964c21a5007d6126624af3697d058b2d724523656ee4459492973d041b480ab4a6ad55613a437a18d948924b2782a7860c93395d153f52c54b5027896f2726a4c6b8db653ea680d0e9afa62cd4803c5c6d4349176e692e6a80964a2f01c69f5e912904f0af78904a067519ff02c56231b80bf3c6980388205541aea50689282d98a736924b59cf57115a7b22adec35695205ff69679aaf29d2857f2bae68a133386c056d2b39635b4b250ad4deb3647299c7c3b3842d6cecebd71dd1b41b38eb6e1ae05f8863b409d8955787fd735f00634be210a1b48878d91e7a12d90d14c3e019c5386236060c2b921080c32a209e20f0f95eb2dffc04c800dcc3da31c3b5756805d77c939a9c3cf22098644a12b1706d7d0722dd086056c89e6d612244732009f3b7104b0e651689654a85608218ce85d5e40e06200e8008391bc172c4b324ca218a528e4a8e2c704440e900e00de0224e44290923e67c1d58bace80ace8fed3becc8b41b500c55939da9da1b7d29452b60b04a05b46b45d6bd0a6159da68854c6fd547e97bb83214c4a073025d933973efb24ee3acb22369b3ae7a567b00cf31492db04fdf4a257d00daad92ca2d585c054c801c385521e8889394764da31287380e0094614c68b3a2885a64d4d2110ca782dcd5ba074e506a9bc9d50ed6f7b62b69b00ec68c9debc4c5c84295c457575bb8cbe4a0bdeedd2098d0db4956954c6655d09d56d551be28814f23530a51459837d2524b8dcda02a68a0e13478d96a48d50a89a40c69002c232a5c66b456b66ba494d609b3be2a297db401139c021721972f72c722ed9b032391bd3a86c6265857ebd090ee27f94a48f823b88e313ae0cb0989d34a28fbd85369f03304cfb5036c785de948436813de937286ac5a4720a7f4de7a62a5e5f8667ee6fba8dd184f9f505b4e008c836954abd03a22075209953c168c4233cc01884f9ba447460048e21437c0d901dc8e249d93e10d42593da838c408c926974c4ad531c81fec55212865a08b01588cde299f49529cc30d32d600cf481c3a4005886b1e9b0350cdcca343dbe2e030d0d0b827d5d204ee27e90635d609f64e807626ea35f0234d9dc038eba1875994b4b8f04d85106377cc35dbeee81ef24b15324a837e4182ab92c1c784f1cc1daa9bbb6db214f540a75fa87706fc2691c66647c8b800af451a59b2075108ce1f73d9ef68c0c5c201c888d0803e1c1c0bdf9f1814506d0bd4045d3afa065a17c4774d7b514c55885dd726f3ed018f4ed4132b6d0f5df07b436586dbd1d70d246d21819077406bb077aead3ab79ad1609a853105af83ea100279832cbbc1e47bce1b731228c5a1c1ba2731b2a4ef35071839ed25453ece9f0efddbeb84e74705fb35e77cd1b081aeb367a67abe8920b95f4338dec76f8c5f9ff77726ef7dafabfbedc1f7fe8fdf2abf843c2583815faf7dc8a0e7ab7a89f31495224c9e339ce3cdb1af1724a7e8e97c1709fb88e7ab9ff3eade3a3e3e1fbf8abe3a2f61e1f07b4fea86a9afd737fff8d3e1c7cfa9f779eeeba3f4f82cf4d1f1767f3cd2c62fb97ebd77ff03ad5272b3dc6cc8c7000000016f724e5401cfa2779a000000fb4944415408d701f0000fff00000102030405060708090a0b0c0d0e000f101112131415161718191a1b1c1d001e1f202122232425262728292a2b2c002d2e2f303132333435363738393a3b003c3d3e3f404142434445464748494a004b4c4d4e4f50515253545556575859005a5b5c5d5e3f5f60616263646566670068696a6b6c6d6e6f70717273747576007778797a7b7c7d7e7f80818283848500868788898a8b8c8d8e8f90919293940095969798999a9b9c9d9e9fa0a1a2a300a4a5a6a7a8a9aaabacadaeafb0b1b200b3b4b5b6b7b8b9babbbcbdbebfc0c100c2c3c4c5c61dc7c8c9cacbcccdcecf00d0d1d2d3d4d5d6d7d8d9dadbdcddde6ee4610eff93ef4f0000008a655849664d4d002a000000080004011a0005000000010000003e011b0005000000010000004601280003000000010002000087690004000000010000004e00000000000000900000000100000090000000010003928600070000001200000078a002000400000001000002f4a003000400000001000001f200000000415343494900000053637265656e73686f74e3c9c01a0000002574455874646174653a63726561746500323032332d31322d31335431303a34393a30372b30303a30309e3d0e040000002574455874646174653a6d6f6469667900323032332d31322d31335431303a34393a30372b30303a3030ef60b6b80000001274455874657869663a457869664f6666736574003738c9d47b270000001874455874657869663a506978656c5844696d656e73696f6e00373536d6ab6daf0000001874455874657869663a506978656c5944696d656e73696f6e0034393802ef508b0000005c74455874657869663a55736572436f6d6d656e740036352c2038332c2036372c2037332c2037332c20302c20302c20302c2038332c2039392c203131342c203130312c203130312c203131302c203131352c203130342c203131312c2031313640b81f7200000028744558746963633a636f7079726967687400436f70797269676874204170706c6520496e632e2c203230323393b38f0a00000017744558746963633a6465736372697074696f6e00446973706c6179171b95b80000000049454e44ae426082),
(16, 40, 'png', 0x89504e470d0a1a0a0000000d494844520000000f0000000f08030000000c08657800000c3569434350696363000048899557075853c9169e5b9290901020808094d09b209d0052426801a417c146480284126320a8d8cba2826b170bd8d05511c50e881db1b328f6be581050d6c5825d799302baee2bdf9b7c33f3e79f33ff3973eedc3200d04ff224923c5413807c71a1343e2c88392a358d49ea0454f8a30017e0cbe31748d8b1b151009681feefe5dd4d80c8fb6b8e72ad7f8effd7a2251016f001406221ce1014f0f3213e08005ec997480b0120ca798b498512398615e8486180102f90e32c25ae94e30c25deabb0498ce740dc0c801a95c7936601a07105f2cc227e16d4d0e885d8592c108901a03321f6cfcf9f2080381d625b68238158aecfcaf84127eb6f9a19839a3c5ed62056ae4551d4824505923cde94ff331dffbbe4e7c9067c58c34acd9686c7cbd70cf3763b7742a41c5321ee116744c740ac0df1079140610f314ac996852729ed51237e0107e60ce841ec2ce00547426c0471a8382f3a4ac567648a42b910c31d824e1615721321d6877881b020244165b3493a215ee50badcb9472d82afe3c4faaf02bf7f550969bc456e9bfce167255fa98467176620ac414882d8b44c9d1106b40ec54909b10a9b219519ccd891eb091cae2e5f15b421c2f14870529f5b1a24c6968bccabe34bf6060bdd8a66c11375a85f7176627862bf38335f3798af8e15ab02b42313b69404758302a6a602d0261708872ed5897509c94a0d2f920290c8a57cec52992bc58953d6e2ecc0b93f3e610bb171425a8e6e2c98570432af5f14c49616ca2324ebc38871711ab8c075f0aa2000704032690c19a0126801c206aeda9ef81ff9423a18007a4200b0881a38a199891a21811c3360114833f21128282c179418a51212882fcd74156d93a824cc5689162462e7806713e880479f0bf4c314b3ce82d193c858ce81fde79b0f261bc79b0cac7ff3d3fc07e67d890895231b2018f4cfa80253184184c0c278612ed7043dc1ff7c5a3601b08ab2bcec2bd07d6f1dd9ef08cd046784cb8416827dc192f9a23fd29ca91a01dea87aa7291f1632e706ba8e98107e17e501d2ae37ab82170c4dda11f361e003d7b4096a38a5b9e15e64fda7f5bc10f574365477626a3e421e440b2edcf3335ec353c0655e4b9fe313fca583306f3cd191cf9d93fe787ec0b601ff9b325b6003b809dc34e6117b0a3583d606227b006ac053b26c783bbeba962770d788b57c4930b7544fff0377065e5992c70ae71ee76fea21c2b144e963fa3016782648a5494955dc864c3378290c915f39d86315d9d5ddd0090bf5f948faf37718af706a2d7f29d9bfb07007e27fafbfb8f7ce7224e00b0cf0bdefe87bf73b62cf8ea5007e0fc61be4c5aa4e4707943804f093abcd30c8009b000b6703daec013f88240100222400c4804a9601c8c3e1bee73299804a681d9a0049481a56015580736822d6007d80df6837a70149c0267c1257005dc00f7e0eee9002f402f78073e2308424268080331404c112bc401714558883f12824421f1482a928e64216244864c43e62265c872641db219a946f621879153c805a40db9833c42ba91d7c8271443a9a80e6a8c5aa3c35116ca4623d144742c9a854e448bd179e862740d5a85ee42ebd053e825f406da8ebe40fb3080a9637a9819e688b1300e1683a5619998149b819562e55815568b35c2eb7c0d6bc77ab08f381167e04cdc11eee0703c09e7e313f119f8227c1dbe03afc39bf16bf823bc17ff46a0118c080e041f0297308a9045984428219413b6110e11cec07ba983f08e4824ea116d885ef05e4c25e610a7121711d713f7104f12db884f887d2412c980e440f223c59078a4425209692d6917e904e92aa983f4414d5dcd54cd552d542d4d4dac3647ad5c6da7da71b5ab6a9d6a9fc99a642bb20f39862c204f212f216f2537922f933bc89f295a141b8a1f25919243994d5943a9a59ca1dca7bc5157573757f7568f5317a9cf525fa3be57fdbcfa23f58f546daa3d95431d4395511753b7534f52ef50dfd068346b5a202d8d56485b4caba69da63da47dd0606838697035041a33352a34ea34ae6abca493e95674367d1cbd985e4e3f40bf4cefd1246b5a6b7234799a33342b340f6bded2ecd36268b968c568e56b2dd2daa97541ab4b9ba46dad1da22dd09ea7bd45fbb4f61306c6b06070187cc65cc656c61946870e51c74687ab93a353a6b35ba755a757575bd75d375977b26e85ee31dd763d4ccf5a8fab97a7b7446fbfde4dbd4f438c87b08708872c1c523be4ea90f7fa43f503f585faa5fa7bf46fe87f32601a8418e41a2c33a8377860881bda1bc6194e32dc6078c6b067a8ce50dfa1fca1a543f70fbd6b841ad91bc51b4d35da62d462d4676c621c662c315e6b7cdab8c744cf24d024c764a5c971936e5386a9bfa9c874a5e909d3e74c5d269b99c75cc36c66f69a1999859bc9cc369bb59a7d36b7314f329f63bec7fc8105c582659169b1d2a2c9a2d7d2d472a4e534cb1acbbb56642b9655b6d56aab7356efad6dac53ace75bd75b77d9e8db706d8a6d6a6ceedbd26c036c27da56d95eb723dab1ec72edd6db5db147ed3decb3ed2bec2f3ba00e9e0e2287f50e6dc308c3bc878987550dbbe54875643b1639d6383e72d2738a729ae354eff472b8e5f0b4e1cb869f1bfecdd9c339cf79abf33d176d970897392e8d2eaf5ded5df9ae15aed7dd686ea16e33dd1adc5eb93bb80bdd37b8dff660788cf498efd1e4f1d5d3cb53ea59ebd9ed65e995ee55e9758ba5c38a652d629df726780779cff43eeafdd1c7d3a7d067bfcf5fbe8ebeb9be3b7dbb46d88c108ed83ae2899fb91fcf6fb35fbb3fd33fdd7f937f7b8059002fa02ae071a045a020705b6027db8e9dc3dec57e19e41c240d3a14f49ee3c399ce39198c0587059706b78668872485ac0b79186a1e9a155a13da1be6113635ec6438213c327c59f82dae3197cfade6f64678454c8f688ea4462644ae8b7c1c651f258d6a1c898e8c18b962e4fd68ab6871747d0c88e1c6ac8879106b133b31f6481c312e36ae22ee59bc4bfcb4f873098c84f1093b13de2506252e49bc97649b244b6a4aa6278f49ae4e7e9f129cb23ca57dd4f051d3475d4a354c15a536a491d292d3b6a5f58d0e19bd6a74c7188f3125636e8eb5193b79ec857186e3f2c61d1b4f1fcf1b7f209d909e92be33fd0b2f8657c5ebcbe0665466f4f239fcd5fc178240c14a41b7d04fb85cd899e997b93cb32bcb2f6b45567776407679768f88235a277a95139eb331e77d6e4ceef6dcfebc94bc3df96af9e9f987c5dae25c71f30493099327b4491c242592f6893e13574dec95464ab7152005630b1a0a75e0877c8bcc56f68bec51917f5145d18749c9930e4cd69a2c9edc32c57ecac2299dc5a1c5bf4dc5a7f2a7364d339b367bdaa3e9ece99b672033326634cdb498396f66c7acb0593b665366e7cefe7d8ef39ce573dece4d99db38cf78deac794f7e09fba5a644a3445a726bbeeffc8d0bf005a205ad0bdd16ae5df8ad54507ab1ccb9acbceccb22fea28bbfbafcbae6d7fec5998b5b97782ed9b094b854bcf4e6b280653b966b2d2f5efe64c5c815752b992b4b57be5d357ed58572f7f28dab29ab65abdbd744ad69586bb976e9da2febb2d7dda808aad8536954b9b0f2fd7ac1faab1b0237d46e34de58b6f1d326d1a6db9bc336d7555957956f216e29daf26c6bf2d673bfb17eabde66b8ad6cdbd7ede2eded3be27734577b5557ef34dab9a406ad91d574ef1ab3ebcaeee0dd0db58eb59bf7e8ed29db0bf6caf63edf97beefe6fec8fd4d0758076a0f5a1dac3cc438545a87d44da9ebadcfae6f6f486d683b1c71b8a9d1b7f1d011a723db8f9a1dad38a67b6cc971caf179c7fb4f149fe83b2939d9732aebd493a6f14df74e8f3a7dbd39aeb9f54ce499f36743cf9e3ec73e77e2bcdff9a3177c2e1cbec8ba587fc9f3525d8b47cba1df3d7e3fd4ead95a77d9eb72c315ef2b8d6d23da8e5f0db87aea5af0b5b3d7b9d72fdd88bed17633e9e6ed5b636eb5df16dceeba9377e7d5dda2bb9fefcdba4fb85ffa40f341f943a387557fd8fdb1a7ddb3fdd8a3e0472d8f131edf7bc27ff2e269c1d32f1df39ed19e95779a765677b9761ded0eedbef27cf4f38e1792179f7b4afed4fab3f2a5edcb837f05fed5d23baab7e395f455ffeb456f0cde6c7febfeb6a92fb6efe1bbfc779fdf977e30f8b0e323ebe3b94f299f3a3f4ffa42fab2e6abddd7c66f91dfeef7e7f7f74b78529ee253008315cdcc04e0f5760068a90030e0f98c325a79fe53144479665520f09fb0f28ca8289e00d4c2eff7b81ef875730b80bd5be1f10bead3c700104b0320d11ba06e6e8375e0aca63857ca0b119e0336c57ccdc8cf00ffa628cf9c3fc4fd730fe4aaeee0e7fe5f1e2c7c97d5764158000000206348524d00007a26000080840000fa00000080e8000075300000ea6000003a98000017709cba513c000002a3504c5445414844414345787b7c9b9b9799969597959fa2a3ada5abb38a8a937e7b8768678a625d744a43463f37394e41425d5e5966605e8f9292aeada9a09c9b837f8a85838f898a947a78805f5d63635e80534f65352f304c42446153556a6964736b68929593aeafaa9a959368636875717985889264666e545257635c775b526342393a5a4f50645656676863655c588b8d8ba3a39c857d7d695b625c5f6863697257585f5b575e63585e6e5f62564b4c6456576e5c5d65605a63554e85858185817d56454d7b58626a626a50545b42444a5b52575e4c51594a4d4d454a564a556f5c5e68615968585078736e84817c6f6a6e8f787f66575c5c5c5f504c515a51545c4b4e544b4b5251554c4855756465625d556a5b536e696586847f888a8a948e907e797b8b8b8f706d6f635e5f7061657a757b5e5c635d5658716463514a486c5e575f5b5a5f5c5e4f5157817f8397918fb2b0b29a97987c787869676c68646b544d516c64637c6f6d4d4b4d6763625e5d5e55545731343c595b62afa9a8bfbcbdaaa6a6757e8652585f4f4c4d655f5d655f5e786f6b575354625f5f6866676562634e4e52727175bcb7b7c3c1c4bab9bc7c7d815c54535d5655666060706a6a665f5e6c6767625e5d6b66656f67676564688d8f95c0bdbec0bdc0b8b5b7808283686260726a696b63634b46454e4947726c6a6a65656e66648a7b7b86818298979bbebbbdbcbabbb2b0b17d7f816260627876766e6967403a38585251716b69868180959392a2a0a0aeababb5b2b3b9b7b8b7b3b4b0adb07f7e806661616e6a6a7e79786460606966687e78769f9b9baaa6a7aeabacb3b0b1b6b3b5b4b1b2adabac7b7c806965665e5c5f4f4d505652537473769e9a9aa3a0a0a9a6a7aeacadafadafb1afb0b3b0b2b1aeb0aca9ab75777c6563645b5a5e353f51333c4d86878bffffffe509fa0f00000001624b4744e0280fff300000000970485973000016250000162501495224f00000000774494d4507e70c0d0a310d45744a9f00000e4f7a5458745261772070726f66696c6520747970652069636300005885ad995992242b0e45ff59452fc1012160398c66bdff0df4910f91115999f5ec997564511e8e33080d57571eeebf63b8fff091230677d8678f437d3ef2a1e308feecd2a92b4b0e294896108e54524d2d1c475e99c7e36eed3822136277ea35e6980ff1e948878ce3fe7cbfffdb67b3ab49e49f8e19c37c49f62f3feedf0df75e45538e1aafdb74f76b702ad6adf37ad0e5bc4a1d1cf8c8215ff7fa4c08316734773cfdf7f5f0e250e7a9c6eb4129cf03cdeffd75bcfa3fc6f7e37d21c13297a83aae1dca313082cf41f379bfe623d19115f9f32dc9da777f3c9c2e4e5d759df73b3c0f168698baf59ab0f559a8dc0b5db7fb39412c4e7f9328ff2ea9fe20a93b1fe43f1f7c58e7eb5304f9cdef942573fd7af02fcdfffbe7ffbf10aa1d397d3f4a78b43cb4e42025ddd6f097cefc2cda754b4fe159e8f213bf06560cb2e4b6abbf26fa5db0ac7c6d70f787a3b1416283e43e7608de3c3a4abc1deef09744219af2bd88dc1ee8af8d83281b0f361eee73028655ada272dbe38ee79091288bd4f484c4e53fa114b3ada414bf2dd4882fddf8cfe7d142677d7414c57f8e1fc5fcff4dd9cfd1e642e13da5d784fb083b287b4b7df0e95e281e2ba3255c373fcabe741281438e9654d6ddbfeefe8573f6145f3aba178ac31642d2f5e74221a714d28d1be19234a6a0499781eec70962be8eac2ff33f3b94a633bff9d2ada3d840188c501f499ffe0e62d9d1a47cfa511c426c7bb3c23de1b24e9cc729917f247d9308aba5f265fe2feb2027fef24cb82e71155b287d6d704bb48ba1a584afa35d3bcbd12caebfb2c73d817cc44243f21322e1ee8f0075568cf0f2eceb2249cf5c26df620a07b69d49508f435e3a156cd650454bc7a78e4e8736690cd0442c98c888e9ba2aff298eaaed04f1b546cf6b5ccf4e1d3d93845d14d1cc530de82c7584f9b9f0f74d527ee6b090e1b5dd982273bb60f2ec63217385f04d4af3a5d78636c6043087dcf387c1ef3bdbc26fd29e13ef4d2c1f2d7d2432f1fb71a6e97340fadacd24fb7ebcc4c425f798fa3c77bf9fdf2438aff156f2b8aecf029fe3dddf27fcb1f84f63bc8d71d7e0f74176b4cf63f274ffb1a8e256ca779562f0e9708fcaa28d318bd61937aeef76dab32fd00aad7e37cafb06eeb54342fb697aaed0b38913a3e7346bda5a4bf87eccef4796e92eecb2237c3864ba3d79fde81e429aba244da7601259e812ff125b88213ba6999480bc8e843225eeebd836d1c6ca77a55bd09a047f75c8bf1ae209937ba1274cbe2d58b9543a2bc4b2b2660de5b74dbf94bdc8452ba61fa4793cfc373fd27789c20f91fec3a4df55e0ee87bf8481e57bbb3f63f1af8b3a3247211b44803fd33cad723f68f91c1cfba6316658df7cf57f4a6684fd15c9f987487f43017d10e20f0fff16fd3f45fc0bbc623c1a622fecb8c7fb22d92862c9f2225a127503f0151251e194c592aadce50489143887c090bac30824e468ed4c4d4fb1f341fd764cfe2ba558bd30c6ab2a7afb94384fb61267dfd7a777f7d34069fd1cd87dbcd86eebf5a771a1c472ed78f453a2517a85a92508847eaef8fa6699d788c6be729dafc6134f2a779cde6ed11fcdc66a76be665aba162619d9b28498ea7162ca69807261fc99049036a3f4822a8a99bf740b281a0b5416a82cd098d498d498d49964be836f1df8190497c61c23f7e0d6312d9398b21737ab9f529f45c7eea8dbfe326da28048abb40d1548349e730a8f7e3c100477a1e1471edb7ac4b7744e3141e32110e111db171641625f1983b4beb141636ce7dab922a12712fc2468fde4cba26371a53cf2bbe193916685b2d026e443691bdd16489ca7f503aa41e3199b87cc4281341d0a9d651da132101b87368c2bc15a99843e02ba0866245410763acc15e3d129b0155b119f66b5c82e50741ac109518fa4e78855625e46058f58b967f1d8b8efed0aeec9f865048c40de64a103ab09cab4c422274e5b3a02a7a1789040f80f78cd82524851e848b0a420a950bf098b092a312f910d89485826c1a713d648b04ed8f4915221c567e09856485348951aad93a6207457baa261e5f3cf9f0992b8b44ace3226d6538894e68eef0d100316daf6851ee43c5d1425e8c86239e31299cd334c35cb76b0143c8662977283e0e7218acec45a9e54c2ec9a77a104ed38f0265a0335a41e055d16dca414ee91b6b4e6706c3a9854563e0ace58bd658b440669648e7d587955293a6be17be33b0aaf83ef6c5219df08d91628211aa2357c025a4954d0890b342cd4d0459be368db0ccd1f5ca2931c3b50d34149d0f7e88ceddda207c2ded17cc784032a3c4849035718691cd493c0c23a068c795898e1b88345a7fde1a0532cd4da3151cb2c586de2f2733060ae63a28f853b2cbc6f61bdc5b156de206c271c157a198eb51661598fcdf1375ebe41869dd1d14659bb1b14ef632f0b6fb414d4123e66420119912bc1d5e99fd9667a02f9fce76503a3d3fb329cf70d758deafd2a1c2e7be2ca07493ea87842c787167d18b415919516686c129556680d971ec9810489d084be1379149b5eb479291d3a3f4187c5a680830f9e3a819a463d3903b0e83ef5e93995459bbd9a709efad5ab0eaf657bed0149f1d3dd7c061bb2784fa9e1738146b4e5f12d9f77f6051c21ce3da58e2fa5f9d2b6f36572c3c413723852d5ee2b27ad1ddf9dd348a16fa1f9866a5acec0d1f2b8866fe813b97d8fdd778dce7756ec48d267f38640b8801f896bee7ea09f31861f2878025a93289ed920ac7a2ced278b2daf9e64edfc4227ab8a5f38d45ad56fccb1e3f27047bfe9df7dfbbd5a38a114dd1378102662638019601d9602ff8ae3d06cdfd0e658815921c03ca96943287c6d3b8439493a8dc45a0258156295104708b041c072079149253c5c201b0699cda22b240627a591b9d3a06d32380b93de83bd1cd14e5b905c4f931a7226ca989f270b151cbec41d0a2e541a824c8179c3be5914c5875a67004d081e04a4dca63c0fad76d078032221f4a8a07275a133109708066d23d63014a2c0826366307384895e66d630d9792efc12af5932028201e139103281033ab0bc855d7dd81c656f902b52b829c1d788ded909045c59301f2af29deb0ed1640b1a63a81ddc1778c9743102ce31ef082b8b119309c28ad5bb7545990582126302a053a12e1f21a63da3bdd2d18ceedb8a0a67ca4844914cc2a823e69963f150f4646c70c4320ae8934e235536aa9d836fe08e655a96c849492c3b72e0d8b53b324c8b7d152c9a23942a0e8e34568c130b132d113f8d204ca44489942b7191914012cce3e3e6f9c633f60a8ecc18e5ac97da093ea8162f5442ba0dbc74210f990ad9434f1236a210ee88cf6e4618a318fb93d21c55d532d0209551ee03ca692ed160efaaa087ad0b5452d08ee4dcf13b521c09ab4817e2580af048b0400ba323ef5581c960972c8d9d1084cc3ee47cc9c3c27d058cdd04a3cae84d70296194cc8a2de69005095854d9822f5cc952c8193cdcd3123d809d7d02d9817ab0825c081c240233e1042964c21a5007d6126624af3697d058b2d724523656ee4459492973d041b480ab4a6ad55613a437a18d948924b2782a7860c93395d153f52c54b5027896f2726a4c6b8db653ea680d0e9afa62cd4803c5c6d4349176e692e6a80964a2f01c69f5e912904f0af78904a067519ff02c56231b80bf3c6980388205541aea50689282d98a736924b59cf57115a7b22adec35695205ff69679aaf29d2857f2bae68a133386c056d2b39635b4b250ad4deb3647299c7c3b3842d6cecebd71dd1b41b38eb6e1ae05f8863b409d8955787fd735f00634be210a1b48878d91e7a12d90d14c3e019c5386236060c2b921080c32a209e20f0f95eb2dffc04c800dcc3da31c3b5756805d77c939a9c3cf22098644a12b1706d7d0722dd086056c89e6d612244732009f3b7104b0e651689654a85608218ce85d5e40e06200e8008391bc172c4b324ca218a528e4a8e2c704440e900e00de0224e44290923e67c1d58bace80ace8fed3becc8b41b500c55939da9da1b7d29452b60b04a05b46b45d6bd0a6159da68854c6fd547e97bb83214c4a073025d933973efb24ee3acb22369b3ae7a567b00cf31492db04fdf4a257d00daad92ca2d585c054c801c385521e8889394764da31287380e0094614c68b3a2885a64d4d2110ca782dcd5ba074e506a9bc9d50ed6f7b62b69b00ec68c9debc4c5c84295c457575bb8cbe4a0bdeedd2098d0db4956954c6655d09d56d551be28814f23530a51459837d2524b8dcda02a68a0e13478d96a48d50a89a40c69002c232a5c66b456b66ba494d609b3be2a297db401139c021721972f72c722ed9b032391bd3a86c6265857ebd090ee27f94a48f823b88e313ae0cb0989d34a28fbd85369f03304cfb5036c785de948436813de937286ac5a4720a7f4de7a62a5e5f8667ee6fba8dd184f9f505b4e008c836954abd03a22075209953c168c4233cc01884f9ba447460048e21437c0d901dc8e249d93e10d42593da838c408c926974c4ad531c81fec55212865a08b01588cde299f49529cc30d32d600cf481c3a4005886b1e9b0350cdcca343dbe2e030d0d0b827d5d204ee27e90635d609f64e807626ea35f0234d9dc038eba1875994b4b8f04d85106377cc35dbeee81ef24b15324a837e4182ab92c1c784f1cc1daa9bbb6db214f540a75fa87706fc2691c66647c8b800af451a59b2075108ce1f73d9ef68c0c5c201c888d0803e1c1c0bdf9f1814506d0bd4045d3afa065a17c4774d7b514c55885dd726f3ed018f4ed4132b6d0f5df07b436586dbd1d70d246d21819077406bb077aead3ab79ad1609a853105af83ea100279832cbbc1e47bce1b731228c5a1c1ba2731b2a4ef35071839ed25453ece9f0efddbeb84e74705fb35e77cd1b081aeb367a67abe8920b95f4338dec76f8c5f9ff77726ef7dafabfbedc1f7fe8fdf2abf843c2583815faf7dc8a0e7ab7a89f31495224c9e339ce3cdb1af1724a7e8e97c1709fb88e7ab9ff3eade3a3e3e1fbf8abe3a2f61e1f07b4fea86a9afd737fff8d3e1c7cfa9f779eeeba3f4f82cf4d1f1767f3cd2c62fb97ebd77ff03ad5272b3dc6cc8c7000000016f724e5401cfa2779a000000fb4944415408d701f0000fff00000102030405060708090a0b0c0d0e000f101112131415161718191a1b1c1d001e1f202122232425262728292a2b2c002d2e2f303132333435363738393a3b003c3d3e3f404142434445464748494a004b4c4d4e4f50515253545556575859005a5b5c5d5e5f60616263646566676800696a6b6c6d6e6f70717273747576770078797a7b7c7d7e7f80818283848586008788898a8b8c8d8e8f90919293949500969798999a9b9c9d9e9fa0a1a2a3a400a5a6a7a8a9aaabacadaeafb0b1b2b300b4b5b6b7b8b9babbbcbdbebfc0c1c200c3c4c5c6c7c8c8c9cacbcccdcecfd000d1d2d3d4d5d6d7d8d9dadbdcdddedfb4156259e383e8090000008a655849664d4d002a000000080004011a0005000000010000003e011b0005000000010000004601280003000000010002000087690004000000010000004e00000000000000900000000100000090000000010003928600070000001200000078a00200040000000100000338a0030004000000010000021c00000000415343494900000053637265656e73686f747b6966620000002574455874646174653a63726561746500323032332d31322d31335431303a34393a31322b30303a303000af213d0000002574455874646174653a6d6f6469667900323032332d31322d31335431303a34393a31322b30303a303071f299810000001274455874657869663a457869664f6666736574003738c9d47b270000001874455874657869663a506978656c5844696d656e73696f6e003832347cb8dd790000001874455874657869663a506978656c5944696d656e73696f6e00353430b858ccc30000005c74455874657869663a55736572436f6d6d656e740036352c2038332c2036372c2037332c2037332c20302c20302c20302c2038332c2039392c203131342c203130312c203130312c203131302c203131352c203130342c203131312c2031313640b81f7200000028744558746963633a636f7079726967687400436f70797269676874204170706c6520496e632e2c203230323393b38f0a00000017744558746963633a6465736372697074696f6e00446973706c6179171b95b80000000049454e44ae426082);

-- --------------------------------------------------------

--
-- Struttura della tabella `ProductTags`
--

CREATE TABLE `ProductTags` (
  `id` int NOT NULL,
  `productId` int NOT NULL,
  `tag` varchar(25) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `ProductTags`
--

INSERT INTO `ProductTags` (`id`, `productId`, `tag`) VALUES
(1, 3, 'useless'),
(2, 2, 'robust'),
(3, 2, 'resistent'),
(4, 2, 'confortable');

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
  `timestampReady` timestamp NULL DEFAULT NULL,
  `estimatedTime` bigint NOT NULL,
  `isPublic` tinyint(1) NOT NULL DEFAULT '0'
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
-- Struttura della tabella `PurchasesChronology`
--

CREATE TABLE `PurchasesChronology` (
  `id` int NOT NULL,
  `customer` int NOT NULL,
  `timestamp` timestamp NOT NULL,
  `address` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `PurchasesChronology`
--

INSERT INTO `PurchasesChronology` (`id`, `customer`, `timestamp`, `address`) VALUES
(1, 1, '2023-12-12 17:36:07', 'This is my address');

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
-- Struttura della tabella `Review`
--

CREATE TABLE `Review` (
  `id` int NOT NULL,
  `fromWho` int NOT NULL,
  `product` int NOT NULL,
  `stars` int NOT NULL,
  `text` text NOT NULL,
  `timestamp` timestamp NOT NULL
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

--
-- Dump dei dati per la tabella `Tags`
--

INSERT INTO `Tags` (`TagID`, `Context`, `Name`, `ExprUK`, `ExprIT`) VALUES
(1, 'News', 'Cinema', 'Cinema', 'Cinema'),
(2, 'WeCraft', 'Artisan', 'Artisan', 'Artigiano'),
(3, 'WeCraft', 'No category', 'No category', 'Nessuna categoria'),
(4, 'WeCraft', 'Bedware Bathroomware', 'Bedware / Bathroomware', 'Coperte / asciugamani'),
(5, 'WeCraft', 'Home decoration', 'Home decoration', 'Decorazioni per la casa'),
(6, 'WeCraft', 'Jewerly', 'Jewerly', 'Gioielleria');

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
  `timeVerificationCode` timestamp NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  `oldEmail` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `User`
--

INSERT INTO `User` (`id`, `email`, `password`, `name`, `surname`, `iconExtension`, `icon`, `emailVerified`, `verificationCode`, `timeVerificationCode`, `isActive`, `oldEmail`) VALUES
(1, 'customer@mail.com', '$2y$10$g3bjhnCg.C3BynqSICch9OQtxKf5twUNxHeHqNDvMVWtAJbsaALGG', 'Cliente', 'Intelligente', 'png', 0x89504e470d0a1a0a0000000d494844520000000f0000000f08030000000c0865780000000467414d410000b18f0bfc6105000000206348524d00007a26000080840000fa00000080e8000075300000ea6000003a98000017709cba513c00000294504c5445b9aea2bbb1a5c0b6aac2b8abcac3b5c4bbaebab1a4bcb3a7988f83a79f93c7c0b1c4bbadc2b8acbeb2a6b8ac9eb6aca0b8aea3b9afa4c2b8aea79e93443b33433a323028232e231d45392f8d8479cac1b4bfb5a9bdb1a5b8ab9cb3a99db3a99eb5aba0b4aa9f26211e0b040365463a7d5a4b3d27202b1e18473a33c4baaebeb1a5baaea0b6aa9ab0a69aaea498bbb0a46a6058000000aa7862fec2a4ffdcc2ce91793c1c131d130faa9e91bfb3a4b9ab9bb6a898aca297bbafa44a4541482f26ffb99af7b79df2b9a1ffb5989d644d0e03008c8175c5b5a5b8a898b7a697ada398aaa097b8ada2605a545d3e33a57262995d4eb66d5a905a4b91635025150e877d71c5b4a3b7a797b6a696ada297aaa096b1a69c807a72382d283c231d361c184e30282e1510472d23332823a09385bcac9db4a494aba197aca296ada4989f938b866860784f408c5b4ed08e826f4436915f4e704a43a79284b3a392b1a191aba296ada397afa59aa99e948e61587e473cba7464e998859352418047387c4e42ac998bb6a797b1a292af9f8fa99f94afa49ab4ada2806359884f4373433e5a28206f3b2e683220816656b6a99ab5a495b1a192ad9c8ea69c92aa9f96b6ada47d685d98614f793e3d531c1a91574684543e74665ab2a799b3a697aea090a99a8ca49a90aaa196b7aea4b2a99f776f687f5f52a16657653026a76955664c3e746c61aba192b4a99aaca092a5998ca8a196b2ada29c968d7d716c634c43593c344c2b224931276644346b5547a39b8dada595a69d8fa4998d9d968ba5a196c4bfb4beb5ac8c7f7a825f5267463c5633286c443378503c887466d8cec4d2c9bcaea6989b9285a7a298d9d0c6f2e8def0e7dc9e938f826e6a845b507a4c3c78513e6f5749aa9c91f8eee4f7ebe1e8dcd2c2b9adffffff4ba55d1e00000001624b4744db99041614000000097048597300000b1200000b1201d2dd7efc0000000774494d4507e70c0d0a2416f8a46567000000016f724e5401cfa2779a000000fb4944415408d701f0000fff00000102030405060708090a0b0c0d0e000f101112131415161718191a1b1c1d001e1f202122232425262728292a2b2c002d2e2f303132333435363738393a3b002e3c3d3e3f40414243444546474849004a4b4c4d4e4f50515253545556575800595a5b5c5d5e5f6061626364655866006768696a6b6c6d6e6f7071723b73740075767778797a7b7c7d7e7f8081828300843c85868788898a8b8c8d8e8f90910092935b9495969798999a9b9c9d9e9f00a0a1a2a3a4a5a6a7a8a9aaabacadae00a0afb0b1b2b3b4b5b6b7b8b9babbbc00bdbebfc0c1c2c3c4c5c6c7c8c9cacb00cccdcecfd0d1d2d3d4d5d6d7d8d9dacf525f38f90918430000002574455874646174653a63726561746500323032332d31322d31335431303a33363a32312b30303a303067f8724d0000002574455874646174653a6d6f6469667900323032332d31322d31335431303a33363a32312b30303a303016a5caf10000000049454e44ae426082, 1, '307158', '2023-12-13 09:40:17', 1, NULL),
(2, 'designer@mail.com', '$2y$10$WjeBLy4kQgJTDNVhB84FT./EkeRI.nqs5vpVLC4dN.dJMN8AKjGSa', 'Bello', 'Bellissimo', 'png', 0x89504e470d0a1a0a0000000d494844520000000f0000000f08030000000c0865780000000467414d410000b18f0bfc6105000000206348524d00007a26000080840000fa00000080e8000075300000ea6000003a98000017709cba513c000001e6504c5445000000391013390a0dffdeffffa3dbff8aa62f18197b645d5d2f3103010164514b5d514bffffff00010100020300020217090b17060817151317131264575163504a26221f261f1d21090b7b232a7b1a212105075e1a20590f14ad303a63181d4b30324b292b1e0f0f1618154d22247d4e51733f43281415000000483a36423a360000000101010b080761564f61504b0b0b0a5c514b5c4a45000000211c1aae978bae8f85211917000000001c2500293616363f142c3400283400191d000000003546002f380a0303023444022e370a0102161513342322341e1d1614123a332f3a2f2b1c19173d34300827300823273d312d1c171500000000141700272d0022280110119f2d369f2c35e9414eff4957e73542f94553c53944a3333ec53945f337459f3c41442c301b363d9d3339602e3176645da69388897970d1b8aba68c82dbc1b3c2aa9effe2d2dbbaade8ccbef5d7c8fdddcde8c5b6dfc1b39b867cdfc0b2334e5629474f30474f00526a00506900506a01445a3b4c3928423a004d6400526c00526b00475d282f2c5047291e4848004f670a374600536c01495f212e29342b1c093c4c004f690051680a30399380771344531731373a2e21053d4e005169133e4592797000394b04252e032e3b003b4c003a4c003949001d22001e23001c20ffffff0ba46eed0000005774524e5300000000000000000000000000000000000000000000000042e1e142949588cf7c792a34c6f6e76e019faf220f30c7c72f93930259f6f6590e96d0f7f7d0962beaea66fbfb6526d6d6266e6d229ffafa9f2227e5ecf6e5791ffc9700000001624b47440c81b35163000000097048597300000ec400000ec401952b0e1b0000000774494d4507e70c0d0a252957d9791b000000016f724e5401cfa2779a000000dd4944415408d7636060606094900c0f8f9092666280006699c8a8a8a8685916289f552e26362e3e415e4151890dc8655756494c4a4e4a49555553e700f23534d3d2333233b2d2b4b47538817c2e5dbdec9cdc9c3c7d036e887e1ec3fc82c28222231ea879c626a6c525a566e6c6601eaf85a595755979858dad9d052f90cf67ef5059555d535b57ef68cf0fe40b383937343635b7b4b6b9b80a323008b9b9b777747675f7f4f6f57b780a3388784d98d83069f2948686a9d3a67b8b3288f9f8facd98396bf69c3973fd0302c581e60705cf9b3f7f5e48e882b0205e06005b2239f0bb63adca0000002574455874646174653a63726561746500323032332d31322d31335431303a33373a33392b30303a3030777f578a0000002574455874646174653a6d6f6469667900323032332d31322d31335431303a33373a33392b30303a30300622ef360000001974455874536f667477617265007777772e696e6b73636170652e6f72679bee3c1a0000000049454e44ae426082, 1, '064339', '2023-12-13 09:40:07', 1, NULL),
(3, 'artisan1@mail.com', '$2y$10$mHXq54W8E0Bm4uj4Xr94NOPsZedv7EXhR5XM18XmCzzfTuTHGQGOK', 'Indiano', 'ArtigianTutto', 'png', 0x89504e470d0a1a0a0000000d494844520000000f0000000f08030000000c08657800000c3569434350696363000048899557075853c9169e5b9290901020808094d09b209d0052426801a417c146480284126320a8d8cba2826b170bd8d05511c50e881db1b328f6be581050d6c5825d799302baee2bdf9b7c33f3e79f33ff3973eedc3200d04ff224923c5413807c71a1343e2c88392a358d49ea0454f8a30017e0cbe31748d8b1b151009681feefe5dd4d80c8fb6b8e72ad7f8effd7a2251016f001406221ce1014f0f3213e08005ec997480b0120ca798b498512398615e8486180102f90e32c25ae94e30c25deabb0498ce740dc0c801a95c7936601a07105f2cc227e16d4d0e885d8592c108901a03321f6cfcf9f2080381d625b68238158aecfcaf84127eb6f9a19839a3c5ed62056ae4551d4824505923cde94ff331dffbbe4e7c9067c58c34acd9686c7cbd70cf3763b7742a41c5321ee116744c740ac0df1079140610f314ac996852729ed51237e0107e60ce841ec2ce00547426c0471a8382f3a4ac567648a42b910c31d824e1615721321d6877881b020244165b3493a215ee50badcb9472d82afe3c4faaf02bf7f550969bc456e9bfce167255fa98467176620ac414882d8b44c9d1106b40ec54909b10a9b219519ccd891eb091cae2e5f15b421c2f14870529f5b1a24c6968bccabe34bf6060bdd8a66c11375a85f7176627862bf38335f3798af8e15ab02b42313b69404758302a6a602d0261708872ed5897509c94a0d2f920290c8a57cec52992bc58953d6e2ecc0b93f3e610bb171425a8e6e2c98570432af5f14c49616ca2324ebc38871711ab8c075f0aa2000704032690c19a0126801c206aeda9ef81ff9423a18007a4200b0881a38a199891a21811c3360114833f21128282c179418a51212882fcd74156d93a824cc5689162462e7806713e880479f0bf4c314b3ce82d193c858ce81fde79b0f261bc79b0cac7ff3d3fc07e67d890895231b2018f4cfa80253184184c0c278612ed7043dc1ff7c5a3601b08ab2bcec2bd07d6f1dd9ef08cd046784cb8416827dc192f9a23fd29ca91a01dea87aa7291f1632e706ba8e98107e17e501d2ae37ab82170c4dda11f361e003d7b4096a38a5b9e15e64fda7f5bc10f574365477626a3e421e440b2edcf3335ec353c0655e4b9fe313fca583306f3cd191cf9d93fe787ec0b601ff9b325b6003b809dc34e6117b0a3583d606227b006ac053b26c783bbeba962770d788b57c4930b7544fff0377065e5992c70ae71ee76fea21c2b144e963fa3016782648a5494955dc864c3378290c915f39d86315d9d5ddd0090bf5f948faf37718af706a2d7f29d9bfb07007e27fafbfb8f7ce7224e00b0cf0bdefe87bf73b62cf8ea5007e0fc61be4c5aa4e4707943804f093abcd30c8009b000b6703daec013f88240100222400c4804a9601c8c3e1bee73299804a681d9a0049481a56015580736822d6007d80df6837a70149c0267c1257005dc00f7e0eee9002f402f78073e2308424268080331404c112bc401714558883f12824421f1482a928e64216244864c43e62265c872641db219a946f621879153c805a40db9833c42ba91d7c8271443a9a80e6a8c5aa3c35116ca4623d144742c9a854e448bd179e862740d5a85ee42ebd053e825f406da8ebe40fb3080a9637a9819e688b1300e1683a5619998149b819562e55815568b35c2eb7c0d6bc77ab08f381167e04cdc11eee0703c09e7e313f119f8227c1dbe03afc39bf16bf823bc17ff46a0118c080e041f0297308a9045984428219413b6110e11cec07ba983f08e4824ea116d885ef05e4c25e610a7121711d713f7104f12db884f887d2412c980e440f223c59078a4425209692d6917e904e92aa983f4414d5dcd54cd552d542d4d4dac3647ad5c6da7da71b5ab6a9d6a9fc99a642bb20f39862c204f212f216f2537922f933bc89f295a141b8a1f25919243994d5943a9a59ca1dca7bc5157573757f7568f5317a9cf525fa3be57fdbcfa23f58f546daa3d95431d4395511753b7534f52ef50dfd068346b5a202d8d56485b4caba69da63da47dd0606838697035041a33352a34ea34ae6abca493e95674367d1cbd985e4e3f40bf4cefd1246b5a6b7234799a33342b340f6bded2ecd36268b968c568e56b2dd2daa97541ab4b9ba46dad1da22dd09ea7bd45fbb4f61306c6b06070187cc65cc656c61946870e51c74687ab93a353a6b35ba755a757575bd75d375977b26e85ee31dd763d4ccf5a8fab97a7b7446fbfde4dbd4f438c87b08708872c1c523be4ea90f7fa43f503f585faa5fa7bf46fe87f32601a8418e41a2c33a8377860881bda1bc6194e32dc6078c6b067a8ce50dfa1fca1a543f70fbd6b841ad91bc51b4d35da62d462d4676c621c662c315e6b7cdab8c744cf24d024c764a5c971936e5386a9bfa9c874a5e909d3e74c5d269b99c75cc36c66f69a1999859bc9cc369bb59a7d36b7314f329f63bec7fc8105c582659169b1d2a2c9a2d7d2d472a4e534cb1acbbb56642b9655b6d56aab7356efad6dac53ace75bd75b77d9e8db706d8a6d6a6ceedbd26c036c27da56d95eb723dab1ec72edd6db5db147ed3decb3ed2bec2f3ba00e9e0e2287f50e6dc308c3bc878987550dbbe54875643b1639d6383e72d2738a729ae354eff472b8e5f0b4e1cb869f1bfecdd9c339cf79abf33d176d970897392e8d2eaf5ded5df9ae15aed7dd686ea16e33dd1adc5eb93bb80bdd37b8dff660788cf498efd1e4f1d5d3cb53ea59ebd9ed65e995ee55e9758ba5c38a652d629df726780779cff43eeafdd1c7d3a7d067bfcf5fbe8ebeb9be3b7dbb46d88c108ed83ae2899fb91fcf6fb35fbb3fd33fdd7f937f7b8059002fa02ae071a045a020705b6027db8e9dc3dec57e19e41c240d3a14f49ee3c399ce39198c0587059706b78668872485ac0b79186a1e9a155a13da1be6113635ec6438213c327c59f82dae3197cfade6f64678454c8f688ea4462644ae8b7c1c651f258d6a1c898e8c18b962e4fd68ab6871747d0c88e1c6ac8879106b133b31f6481c312e36ae22ee59bc4bfcb4f873098c84f1093b13de2506252e49bc97649b244b6a4aa6278f49ae4e7e9f129cb23ca57dd4f051d3475d4a354c15a536a491d292d3b6a5f58d0e19bd6a74c7188f3125636e8eb5193b79ec857186e3f2c61d1b4f1fcf1b7f209d909e92be33fd0b2f8657c5ebcbe0665466f4f239fcd5fc178240c14a41b7d04fb85cd899e997b93cb32bcb2f6b45567776407679768f88235a277a95139eb331e77d6e4ceef6dcfebc94bc3df96af9e9f987c5dae25c71f30493099327b4491c242592f6893e13574dec95464ab7152005630b1a0a75e0877c8bcc56f68bec51917f5145d18749c9930e4cd69a2c9edc32c57ecac2299dc5a1c5bf4dc5a7f2a7364d339b367bdaa3e9ece99b672033326634cdb498396f66c7acb0593b665366e7cefe7d8ef39ce573dece4d99db38cf78deac794f7e09fba5a644a3445a726bbeeffc8d0bf005a205ad0bdd16ae5df8ad54507ab1ccb9acbceccb22fea28bbfbafcbae6d7fec5998b5b97782ed9b094b854bcf4e6b280653b966b2d2f5efe64c5c815752b992b4b57be5d357ed58572f7f28dab29ab65abdbd744ad69586bb976e9da2febb2d7dda808aad8536954b9b0f2fd7ac1faab1b0237d46e34de58b6f1d326d1a6db9bc336d7555957956f216e29daf26c6bf2d673bfb17eabde66b8ad6cdbd7ede2eded3be27734577b5557ef34dab9a406ad91d574ef1ab3ebcaeee0dd0db58eb59bf7e8ed29db0bf6caf63edf97beefe6fec8fd4d0758076a0f5a1dac3cc438545a87d44da9ebadcfae6f6f486d683b1c71b8a9d1b7f1d011a723db8f9a1dad38a67b6cc971caf179c7fb4f149fe83b2939d9732aebd493a6f14df74e8f3a7dbd39aeb9f54ce499f36743cf9e3ec73e77e2bcdff9a3177c2e1cbec8ba587fc9f3525d8b47cba1df3d7e3fd4ead95a77d9eb72c315ef2b8d6d23da8e5f0db87aea5af0b5b3d7b9d72fdd88bed17633e9e6ed5b636eb5df16dceeba9377e7d5dda2bb9fefcdba4fb85ffa40f341f943a387557fd8fdb1a7ddb3fdd8a3e0472d8f131edf7bc27ff2e269c1d32f1df39ed19e95779a765677b9761ded0eedbef27cf4f38e1792179f7b4afed4fab3f2a5edcb837f05fed5d23baab7e395f455ffeb456f0cde6c7febfeb6a92fb6efe1bbfc779fdf977e30f8b0e323ebe3b94f299f3a3f4ffa42fab2e6abddd7c66f91dfeef7e7f7f74b78529ee253008315cdcc04e0f5760068a90030e0f98c325a79fe53144479665520f09fb0f28ca8289e00d4c2eff7b81ef875730b80bd5be1f10bead3c700104b0320d11ba06e6e8375e0aca63857ca0b119e0336c57ccdc8cf00ffa628cf9c3fc4fd730fe4aaeee0e7fe5f1e2c7c97d5764158000000206348524d00007a26000080840000fa00000080e8000075300000ea6000003a98000017709cba513c00000117504c5445f7f9f8f8faf9f9fbfaf6f8f7f0f1f0bebfbea5a5a4b4b5b4edeeedf5f7f6abababb6b7b6e8e9e8c2c3c2afb0afebedec8686859f9f9ebbbcbbc6c6c690908fedefeef8f9f9ebeceb969695dbdcdbcacbcbb9bab98d8d8ceceeeda9aaa9cdcecdf3f5f4ababaaf6f7f7d5d6d58b8b8aa0a0a0888786d6d7d79b9b9ab8b9b8bcbdbcb7b7b6a3a3a2a9a9a8858483bfc0bf9fa09fb3b4b3a4a4a3e3e5e49d9d9c7473727b7a799999987a7a79d0d1d19c9c9b8383828282818a8a89c0c2c18889889e9e9df1f2f1bdbebd838381afafaeacadaca7a7a7fbfdfca5a6a6aeaeadadaead858584f2f4f39a9a99a7a8a77c7b7bb5b6b5e1e2e1b4b4b37c7c7b989897e8eae9aeafaeb0b1b1a6a7a6a8a8a8aeaeaef4f6f5ffffff55f1a21f00000001624b47445cead800970000000970485973000016250000162501495224f00000000774494d4507e70c0d0a2701505ab36300000e4f7a5458745261772070726f66696c6520747970652069636300005885ad995992242b0e45ff59452fc1012160398c66bdff0df4910f91115999f5ec997564511e8e33080d57571eeebf63b8fff091230677d8678f437d3ef2a1e308feecd2a92b4b0e294896108e54524d2d1c475e99c7e36eed3822136277ea35e6980ff1e948878ce3fe7cbfffdb67b3ab49e49f8e19c37c49f62f3feedf0df75e45538e1aafdb74f76b702ad6adf37ad0e5bc4a1d1cf8c8215ff7fa4c08316734773cfdf7f5f0e250e7a9c6eb4129cf03cdeffd75bcfa3fc6f7e37d21c13297a83aae1dca313082cf41f379bfe623d19115f9f32dc9da777f3c9c2e4e5d759df73b3c0f168698baf59ab0f559a8dc0b5db7fb39412c4e7f9328ff2ea9fe20a93b1fe43f1f7c58e7eb5304f9cdef942573fd7af02fcdfffbe7ffbf10aa1d397d3f4a78b43cb4e42025ddd6f097cefc2cda754b4fe159e8f213bf06560cb2e4b6abbf26fa5db0ac7c6d70f787a3b1416283e43e7608de3c3a4abc1deef09744219af2bd88dc1ee8af8d83281b0f361eee73028655ada272dbe38ee79091288bd4f484c4e53fa114b3ada414bf2dd4882fddf8cfe7d142677d7414c57f8e1fc5fcff4dd9cfd1e642e13da5d784fb083b287b4b7df0e95e281e2ba3255c373fcabe741281438e9654d6ddbfeefe8573f6145f3aba178ac31642d2f5e74221a714d28d1be19234a6a0499781eec70962be8eac2ff33f3b94a633bff9d2ada3d840188c501f499ffe0e62d9d1a47cfa511c426c7bb3c23de1b24e9cc729917f247d9308aba5f265fe2feb2027fef24cb82e71155b287d6d704bb48ba1a584afa35d3bcbd12caebfb2c73d817cc44243f21322e1ee8f0075568cf0f2eceb2249cf5c26df620a07b69d49508f435e3a156cd650454bc7a78e4e8736690cd0442c98c888e9ba2aff298eaaed04f1b546cf6b5ccf4e1d3d93845d14d1cc530de82c7584f9b9f0f74d527ee6b090e1b5dd982273bb60f2ec63217385f04d4af3a5d78636c6043087dcf387c1ef3bdbc26fd29e13ef4d2c1f2d7d2432f1fb71a6e97340fadacd24fb7ebcc4c425f798fa3c77bf9fdf2438aff156f2b8aecf029fe3dddf27fcb1f84f63bc8d71d7e0f74176b4cf63f274ffb1a8e256ca779562f0e9708fcaa28d318bd61937aeef76dab32fd00aad7e37cafb06eeb54342fb697aaed0b38913a3e7346bda5a4bf87eccef4796e92eecb2237c3864ba3d79fde81e429aba244da7601259e812ff125b88213ba6999480bc8e843225eeebd836d1c6ca77a55bd09a047f75c8bf1ae209937ba1274cbe2d58b9543a2bc4b2b2660de5b74dbf94bdc8452ba61fa4793cfc373fd27789c20f91fec3a4df55e0ee87bf8481e57bbb3f63f1af8b3a3247211b44803fd33cad723f68f91c1cfba6316658df7cf57f4a6684fd15c9f987487f43017d10e20f0fff16fd3f45fc0bbc623c1a622fecb8c7fb22d92862c9f2225a127503f0151251e194c592aadce50489143887c090bac30824e468ed4c4d4fb1f341fd764cfe2ba558bd30c6ab2a7afb94384fb61267dfd7a777f7d34069fd1cd87dbcd86eebf5a771a1c472ed78f453a2517a85a92508847eaef8fa6699d788c6be729dafc6134f2a779cde6ed11fcdc66a76be665aba162619d9b28498ea7162ca69807261fc99049036a3f4822a8a99bf740b281a0b5416a82cd098d498d498d49964be836f1df8190497c61c23f7e0d6312d9398b21737ab9f529f45c7eea8dbfe326da28048abb40d1548349e730a8f7e3c100477a1e1471edb7ac4b7744e3141e32110e111db171641625f1983b4beb141636ce7dab922a12712fc2468fde4cba26371a53cf2bbe193916685b2d026e443691bdd16489ca7f503aa41e3199b87cc4281341d0a9d651da132101b87368c2bc15a99843e02ba0866245410763acc15e3d129b0155b119f66b5c82e50741ac109518fa4e78855625e46058f58b967f1d8b8efed0aeec9f865048c40de64a103ab09cab4c422274e5b3a02a7a1789040f80f78cd82524851e848b0a420a950bf098b092a312f910d89485826c1a713d648b04ed8f4915221c567e09856485348951aad93a6207457baa261e5f3cf9f0992b8b44ace3226d6538894e68eef0d100316daf6851ee43c5d1425e8c86239e31299cd334c35cb76b0143c8662977283e0e7218acec45a9e54c2ec9a77a104ed38f0265a0335a41e055d16dca414ee91b6b4e6706c3a9854563e0ace58bd658b440669648e7d587955293a6be17be33b0aaf83ef6c5219df08d91628211aa2357c025a4954d0890b342cd4d0459be368db0ccd1f5ca2931c3b50d34149d0f7e88ceddda207c2ded17cc784032a3c4849035718691cd493c0c23a068c795898e1b88345a7fde1a0532cd4da3151cb2c586de2f2733060ae63a28f853b2cbc6f61bdc5b156de206c271c157a198eb51661598fcdf1375ebe41869dd1d14659bb1b14ef632f0b6fb414d4123e66420119912bc1d5e99fd9667a02f9fce76503a3d3fb329cf70d758deafd2a1c2e7be2ca07493ea87842c787167d18b415919516686c129556680d971ec9810489d084be1379149b5eb479291d3a3f4187c5a680830f9e3a819a463d3903b0e83ef5e93995459bbd9a709efad5ab0eaf657bed0149f1d3dd7c061bb2784fa9e1738146b4e5f12d9f77f6051c21ce3da58e2fa5f9d2b6f36572c3c413723852d5ee2b27ad1ddf9dd348a16fa1f9866a5acec0d1f2b8866fe813b97d8fdd778dce7756ec48d267f38640b8801f896bee7ea09f31861f2878025a93289ed920ac7a2ced278b2daf9e64edfc4227ab8a5f38d45ad56fccb1e3f27047bfe9df7dfbbd5a38a114dd1378102662638019601d9602ff8ae3d06cdfd0e658815921c03ca96943287c6d3b8439493a8dc45a0258156295104708b041c072079149253c5c201b0699cda22b240627a591b9d3a06d32380b93de83bd1cd14e5b905c4f931a7226ca989f270b151cbec41d0a2e541a824c8179c3be5914c5875a67004d081e04a4dca63c0fad76d078032221f4a8a07275a133109708066d23d63014a2c0826366307384895e66d630d9792efc12af5932028201e139103281033ab0bc855d7dd81c656f902b52b829c1d788ded909045c59301f2af29deb0ed1640b1a63a81ddc1778c9743102ce31ef082b8b119309c28ad5bb7545990582126302a053a12e1f21a63da3bdd2d18ceedb8a0a67ca4844914cc2a823e69963f150f4646c70c4320ae8934e235536aa9d836fe08e655a96c849492c3b72e0d8b53b324c8b7d152c9a23942a0e8e34568c130b132d113f8d204ca44489942b7191914012cce3e3e6f9c633f60a8ecc18e5ac97da093ea8162f5442ba0dbc74210f990ad9434f1236a210ee88cf6e4618a318fb93d21c55d532d0209551ee03ca692ed160efaaa087ad0b5452d08ee4dcf13b521c09ab4817e2580af048b0400ba323ef5581c960972c8d9d1084cc3ee47cc9c3c27d058cdd04a3cae84d70296194cc8a2de69005095854d9822f5cc952c8193cdcd3123d809d7d02d9817ab0825c081c240233e1042964c21a5007d6126624af3697d058b2d724523656ee4459492973d041b480ab4a6ad55613a437a18d948924b2782a7860c93395d153f52c54b5027896f2726a4c6b8db653ea680d0e9afa62cd4803c5c6d4349176e692e6a80964a2f01c69f5e912904f0af78904a067519ff02c56231b80bf3c6980388205541aea50689282d98a736924b59cf57115a7b22adec35695205ff69679aaf29d2857f2bae68a133386c056d2b39635b4b250ad4deb3647299c7c3b3842d6cecebd71dd1b41b38eb6e1ae05f8863b409d8955787fd735f00634be210a1b48878d91e7a12d90d14c3e019c5386236060c2b921080c32a209e20f0f95eb2dffc04c800dcc3da31c3b5756805d77c939a9c3cf22098644a12b1706d7d0722dd086056c89e6d612244732009f3b7104b0e651689654a85608218ce85d5e40e06200e8008391bc172c4b324ca218a528e4a8e2c704440e900e00de0224e44290923e67c1d58bace80ace8fed3becc8b41b500c55939da9da1b7d29452b60b04a05b46b45d6bd0a6159da68854c6fd547e97bb83214c4a073025d933973efb24ee3acb22369b3ae7a567b00cf31492db04fdf4a257d00daad92ca2d585c054c801c385521e8889394764da31287380e0094614c68b3a2885a64d4d2110ca782dcd5ba074e506a9bc9d50ed6f7b62b69b00ec68c9debc4c5c84295c457575bb8cbe4a0bdeedd2098d0db4956954c6655d09d56d551be28814f23530a51459837d2524b8dcda02a68a0e13478d96a48d50a89a40c69002c232a5c66b456b66ba494d609b3be2a297db401139c021721972f72c722ed9b032391bd3a86c6265857ebd090ee27f94a48f823b88e313ae0cb0989d34a28fbd85369f03304cfb5036c785de948436813de937286ac5a4720a7f4de7a62a5e5f8667ee6fba8dd184f9f505b4e008c836954abd03a22075209953c168c4233cc01884f9ba447460048e21437c0d901dc8e249d93e10d42593da838c408c926974c4ad531c81fec55212865a08b01588cde299f49529cc30d32d600cf481c3a4005886b1e9b0350cdcca343dbe2e030d0d0b827d5d204ee27e90635d609f64e807626ea35f0234d9dc038eba1875994b4b8f04d85106377cc35dbeee81ef24b15324a837e4182ab92c1c784f1cc1daa9bbb6db214f540a75fa87706fc2691c66647c8b800af451a59b2075108ce1f73d9ef68c0c5c201c888d0803e1c1c0bdf9f1814506d0bd4045d3afa065a17c4774d7b514c55885dd726f3ed018f4ed4132b6d0f5df07b436586dbd1d70d246d21819077406bb077aead3ab79ad1609a853105af83ea100279832cbbc1e47bce1b731228c5a1c1ba2731b2a4ef35071839ed25453ece9f0efddbeb84e74705fb35e77cd1b081aeb367a67abe8920b95f4338dec76f8c5f9ff77726ef7dafabfbedc1f7fe8fdf2abf843c2583815faf7dc8a0e7ab7a89f31495224c9e339ce3cdb1af1724a7e8e97c1709fb88e7ab9ff3eade3a3e3e1fbf8abe3a2f61e1f07b4fea86a9afd737fff8d3e1c7cfa9f779eeeba3f4f82cf4d1f1767f3cd2c62fb97ebd77ff03ad5272b3dc6cc8c7000000016f724e5401cfa2779a000000aa4944415408d763600001462666264606386064616563e74008707271f3f0f2c16519f905048584454419c5a002e2129252d232b2700d9c72f20af28a4a080dca2aaa6aea70aeb8069ba696b68e2e2784cfa4adc7ad6fa06f68640c3540d1c4d4ccdcc254ce122a6f656d636b676f6dedc004e23a3a39bbb8bab97b787a79ebfb004d33f665f5f30f080c0af6730a09059ae0231d161e1119e617111ea5150dd6cfc8c8c404428c40fd0026c314fa1aea7d750000008a655849664d4d002a000000080004011a0005000000010000003e011b0005000000010000004601280003000000010002000087690004000000010000004e00000000000000900000000100000090000000010003928600070000001200000078a0020004000000010000012aa0030004000000010000012a00000000415343494900000053637265656e73686f74370df5150000002574455874646174653a63726561746500323032332d31322d31335431303a33383a35392b30303a3030401b05800000002574455874646174653a6d6f6469667900323032332d31322d31335431303a33383a35392b30303a30303146bd3c0000001274455874657869663a457869664f6666736574003738c9d47b270000001874455874657869663a506978656c5844696d656e73696f6e003239389b6dcd4f0000001874455874657869663a506978656c5944696d656e73696f6e0032393806622c390000005c74455874657869663a55736572436f6d6d656e740036352c2038332c2036372c2037332c2037332c20302c20302c20302c2038332c2039392c203131342c203130312c203130312c203131302c203131352c203130342c203131312c2031313640b81f7200000028744558746963633a636f7079726967687400436f70797269676874204170706c6520496e632e2c203230323393b38f0a00000017744558746963633a6465736372697074696f6e00446973706c6179171b95b80000000049454e44ae426082, 1, '016768', '2023-12-13 09:41:20', 1, NULL),
(4, 'artisan2@mail.com', '$2y$10$UJfzKgiJnaFfVpVSnanNhe7FX/nZhF6raT/7A93Fqke.CqpSb.wvW', 'Arte', 'Igiano', 'png', 0x89504e470d0a1a0a0000000d494844520000000f0000000f08000000001ebdca9600000002624b474400ff878fccbf0000000970485973000016250000162501495224f00000000774494d4507e70c0d0a272812e82b0f00000e4f7a5458745261772070726f66696c6520747970652069636300005885ad995992242b0e45ff59452fc1012160398c66bdff0df4910f91115999f5ec997564511e8e33080d57571eeebf63b8fff091230677d8678f437d3ef2a1e308feecd2a92b4b0e294896108e54524d2d1c475e99c7e36eed3822136277ea35e6980ff1e948878ce3fe7cbfffdb67b3ab49e49f8e19c37c49f62f3feedf0df75e45538e1aafdb74f76b702ad6adf37ad0e5bc4a1d1cf8c8215ff7fa4c08316734773cfdf7f5f0e250e7a9c6eb4129cf03cdeffd75bcfa3fc6f7e37d21c13297a83aae1dca313082cf41f379bfe623d19115f9f32dc9da777f3c9c2e4e5d759df73b3c0f168698baf59ab0f559a8dc0b5db7fb39412c4e7f9328ff2ea9fe20a93b1fe43f1f7c58e7eb5304f9cdef942573fd7af02fcdfffbe7ffbf10aa1d397d3f4a78b43cb4e42025ddd6f097cefc2cda754b4fe159e8f213bf06560cb2e4b6abbf26fa5db0ac7c6d70f787a3b1416283e43e7608de3c3a4abc1deef09744219af2bd88dc1ee8af8d83281b0f361eee73028655ada272dbe38ee79091288bd4f484c4e53fa114b3ada414bf2dd4882fddf8cfe7d142677d7414c57f8e1fc5fcff4dd9cfd1e642e13da5d784fb083b287b4b7df0e95e281e2ba3255c373fcabe741281438e9654d6ddbfeefe8573f6145f3aba178ac31642d2f5e74221a714d28d1be19234a6a0499781eec70962be8eac2ff33f3b94a633bff9d2ada3d840188c501f499ffe0e62d9d1a47cfa511c426c7bb3c23de1b24e9cc729917f247d9308aba5f265fe2feb2027fef24cb82e71155b287d6d704bb48ba1a584afa35d3bcbd12caebfb2c73d817cc44243f21322e1ee8f0075568cf0f2eceb2249cf5c26df620a07b69d49508f435e3a156cd650454bc7a78e4e8736690cd0442c98c888e9ba2aff298eaaed04f1b546cf6b5ccf4e1d3d93845d14d1cc530de82c7584f9b9f0f74d527ee6b090e1b5dd982273bb60f2ec63217385f04d4af3a5d78636c6043087dcf387c1ef3bdbc26fd29e13ef4d2c1f2d7d2432f1fb71a6e97340fadacd24fb7ebcc4c425f798fa3c77bf9fdf2438aff156f2b8aecf029fe3dddf27fcb1f84f63bc8d71d7e0f74176b4cf63f274ffb1a8e256ca779562f0e9708fcaa28d318bd61937aeef76dab32fd00aad7e37cafb06eeb54342fb697aaed0b38913a3e7346bda5a4bf87eccef4796e92eecb2237c3864ba3d79fde81e429aba244da7601259e812ff125b88213ba6999480bc8e843225eeebd836d1c6ca77a55bd09a047f75c8bf1ae209937ba1274cbe2d58b9543a2bc4b2b2660de5b74dbf94bdc8452ba61fa4793cfc373fd27789c20f91fec3a4df55e0ee87bf8481e57bbb3f63f1af8b3a3247211b44803fd33cad723f68f91c1cfba6316658df7cf57f4a6684fd15c9f987487f43017d10e20f0fff16fd3f45fc0bbc623c1a622fecb8c7fb22d92862c9f2225a127503f0151251e194c592aadce50489143887c090bac30824e468ed4c4d4fb1f341fd764cfe2ba558bd30c6ab2a7afb94384fb61267dfd7a777f7d34069fd1cd87dbcd86eebf5a771a1c472ed78f453a2517a85a92508847eaef8fa6699d788c6be729dafc6134f2a779cde6ed11fcdc66a76be665aba162619d9b28498ea7162ca69807261fc99049036a3f4822a8a99bf740b281a0b5416a82cd098d498d498d49964be836f1df8190497c61c23f7e0d6312d9398b21737ab9f529f45c7eea8dbfe326da28048abb40d1548349e730a8f7e3c100477a1e1471edb7ac4b7744e3141e32110e111db171641625f1983b4beb141636ce7dab922a12712fc2468fde4cba26371a53cf2bbe193916685b2d026e443691bdd16489ca7f503aa41e3199b87cc4281341d0a9d651da132101b87368c2bc15a99843e02ba0866245410763acc15e3d129b0155b119f66b5c82e50741ac109518fa4e78855625e46058f58b967f1d8b8efed0aeec9f865048c40de64a103ab09cab4c422274e5b3a02a7a1789040f80f78cd82524851e848b0a420a950bf098b092a312f910d89485826c1a713d648b04ed8f4915221c567e09856485348951aad93a6207457baa261e5f3cf9f0992b8b44ace3226d6538894e68eef0d100316daf6851ee43c5d1425e8c86239e31299cd334c35cb76b0143c8662977283e0e7218acec45a9e54c2ec9a77a104ed38f0265a0335a41e055d16dca414ee91b6b4e6706c3a9854563e0ace58bd658b440669648e7d587955293a6be17be33b0aaf83ef6c5219df08d91628211aa2357c025a4954d0890b342cd4d0459be368db0ccd1f5ca2931c3b50d34149d0f7e88ceddda207c2ded17cc784032a3c4849035718691cd493c0c23a068c795898e1b88345a7fde1a0532cd4da3151cb2c586de2f2733060ae63a28f853b2cbc6f61bdc5b156de206c271c157a198eb51661598fcdf1375ebe41869dd1d14659bb1b14ef632f0b6fb414d4123e66420119912bc1d5e99fd9667a02f9fce76503a3d3fb329cf70d758deafd2a1c2e7be2ca07493ea87842c787167d18b415919516686c129556680d971ec9810489d084be1379149b5eb479291d3a3f4187c5a680830f9e3a819a463d3903b0e83ef5e93995459bbd9a709efad5ab0eaf657bed0149f1d3dd7c061bb2784fa9e1738146b4e5f12d9f77f6051c21ce3da58e2fa5f9d2b6f36572c3c413723852d5ee2b27ad1ddf9dd348a16fa1f9866a5acec0d1f2b8866fe813b97d8fdd778dce7756ec48d267f38640b8801f896bee7ea09f31861f2878025a93289ed920ac7a2ced278b2daf9e64edfc4227ab8a5f38d45ad56fccb1e3f27047bfe9df7dfbbd5a38a114dd1378102662638019601d9602ff8ae3d06cdfd0e658815921c03ca96943287c6d3b8439493a8dc45a0258156295104708b041c072079149253c5c201b0699cda22b240627a591b9d3a06d32380b93de83bd1cd14e5b905c4f931a7226ca989f270b151cbec41d0a2e541a824c8179c3be5914c5875a67004d081e04a4dca63c0fad76d078032221f4a8a07275a133109708066d23d63014a2c0826366307384895e66d630d9792efc12af5932028201e139103281033ab0bc855d7dd81c656f902b52b829c1d788ded909045c59301f2af29deb0ed1640b1a63a81ddc1778c9743102ce31ef082b8b119309c28ad5bb7545990582126302a053a12e1f21a63da3bdd2d18ceedb8a0a67ca4844914cc2a823e69963f150f4646c70c4320ae8934e235536aa9d836fe08e655a96c849492c3b72e0d8b53b324c8b7d152c9a23942a0e8e34568c130b132d113f8d204ca44489942b7191914012cce3e3e6f9c633f60a8ecc18e5ac97da093ea8162f5442ba0dbc74210f990ad9434f1236a210ee88cf6e4618a318fb93d21c55d532d0209551ee03ca692ed160efaaa087ad0b5452d08ee4dcf13b521c09ab4817e2580af048b0400ba323ef5581c960972c8d9d1084cc3ee47cc9c3c27d058cdd04a3cae84d70296194cc8a2de69005095854d9822f5cc952c8193cdcd3123d809d7d02d9817ab0825c081c240233e1042964c21a5007d6126624af3697d058b2d724523656ee4459492973d041b480ab4a6ad55613a437a18d948924b2782a7860c93395d153f52c54b5027896f2726a4c6b8db653ea680d0e9afa62cd4803c5c6d4349176e692e6a80964a2f01c69f5e912904f0af78904a067519ff02c56231b80bf3c6980388205541aea50689282d98a736924b59cf57115a7b22adec35695205ff69679aaf29d2857f2bae68a133386c056d2b39635b4b250ad4deb3647299c7c3b3842d6cecebd71dd1b41b38eb6e1ae05f8863b409d8955787fd735f00634be210a1b48878d91e7a12d90d14c3e019c5386236060c2b921080c32a209e20f0f95eb2dffc04c800dcc3da31c3b5756805d77c939a9c3cf22098644a12b1706d7d0722dd086056c89e6d612244732009f3b7104b0e651689654a85608218ce85d5e40e06200e8008391bc172c4b324ca218a528e4a8e2c704440e900e00de0224e44290923e67c1d58bace80ace8fed3becc8b41b500c55939da9da1b7d29452b60b04a05b46b45d6bd0a6159da68854c6fd547e97bb83214c4a073025d933973efb24ee3acb22369b3ae7a567b00cf31492db04fdf4a257d00daad92ca2d585c054c801c385521e8889394764da31287380e0094614c68b3a2885a64d4d2110ca782dcd5ba074e506a9bc9d50ed6f7b62b69b00ec68c9debc4c5c84295c457575bb8cbe4a0bdeedd2098d0db4956954c6655d09d56d551be28814f23530a51459837d2524b8dcda02a68a0e13478d96a48d50a89a40c69002c232a5c66b456b66ba494d609b3be2a297db401139c021721972f72c722ed9b032391bd3a86c6265857ebd090ee27f94a48f823b88e313ae0cb0989d34a28fbd85369f03304cfb5036c785de948436813de937286ac5a4720a7f4de7a62a5e5f8667ee6fba8dd184f9f505b4e008c836954abd03a22075209953c168c4233cc01884f9ba447460048e21437c0d901dc8e249d93e10d42593da838c408c926974c4ad531c81fec55212865a08b01588cde299f49529cc30d32d600cf481c3a4005886b1e9b0350cdcca343dbe2e030d0d0b827d5d204ee27e90635d609f64e807626ea35f0234d9dc038eba1875994b4b8f04d85106377cc35dbeee81ef24b15324a837e4182ab92c1c784f1cc1daa9bbb6db214f540a75fa87706fc2691c66647c8b800af451a59b2075108ce1f73d9ef68c0c5c201c888d0803e1c1c0bdf9f1814506d0bd4045d3afa065a17c4774d7b514c55885dd726f3ed018f4ed4132b6d0f5df07b436586dbd1d70d246d21819077406bb077aead3ab79ad1609a853105af83ea100279832cbbc1e47bce1b731228c5a1c1ba2731b2a4ef35071839ed25453ece9f0efddbeb84e74705fb35e77cd1b081aeb367a67abe8920b95f4338dec76f8c5f9ff77726ef7dafabfbedc1f7fe8fdf2abf843c2583815faf7dc8a0e7ab7a89f31495224c9e339ce3cdb1af1724a7e8e97c1709fb88e7ab9ff3eade3a3e3e1fbf8abe3a2f61e1f07b4fea86a9afd737fff8d3e1c7cfa9f779eeeba3f4f82cf4d1f1767f3cd2c62fb97ebd77ff03ad5272b3dc6cc8c7000000016f724e5401cfa2779a000000db4944415408d763fccec0c0f0eb8b2013e33f060606060626060606e67371d7de3e61646460606060616060f8bf6f6f2aebc7d854feff0c0c0cdfbf7fff9ce91dccc2c09cf6eec7f7ef0cdfbf7ffffee2dd4d470d59eeb5bfbe7f676260606010e4bffc7dd2269d43ff21e6317eb97e975350af5af01f44ffaf8dea3146feafbebcfa0e557ff1ee73d6fb3f59f9a0f6ff7bcbc3f1e3e97dc6ff503e83b7c1ab0f6f17ff86bbefdd115e4e99ade799a1fc5fdbfe786b8972cdff05e133fee4d3b536ffea75e206130303e3770606865f2f24dedd547aa128fa9f0100ebed591b077120b90000008a655849664d4d002a000000080004011a0005000000010000003e011b0005000000010000004601280003000000010002000087690004000000010000004e00000000000000900000000100000090000000010003928600070000001200000078a00200040000000100000154a0030004000000010000015400000000415343494900000053637265656e73686f74ee27576b0000002574455874646174653a63726561746500323032332d31322d31335431303a33393a33392b30303a303069b667390000002574455874646174653a6d6f6469667900323032332d31322d31335431303a33393a33392b30303a303018ebdf850000001274455874657869663a457869664f6666736574003738c9d47b270000001874455874657869663a506978656c5844696d656e73696f6e0033343021da51070000001874455874657869663a506978656c5944696d656e73696f6e00333430bcd5b0710000005c74455874657869663a55736572436f6d6d656e740036352c2038332c2036372c2037332c2037332c20302c20302c20302c2038332c2039392c203131342c203130312c203130312c203131302c203131352c203130342c203131312c2031313640b81f7200000028744558746963633a636f7079726967687400436f70797269676874204170706c6520496e632e2c203230323393b38f0a00000017744558746963633a6465736372697074696f6e00446973706c6179171b95b80000000049454e44ae426082, 1, '419098', '2023-12-13 09:42:03', 1, NULL);

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
-- Dump dei dati per la tabella `UserImages`
--

INSERT INTO `UserImages` (`id`, `userId`, `imgExtension`, `image`) VALUES
(23, 2, 'png', 0x89504e470d0a1a0a0000000d494844520000000f0000000f08030000000c0865780000000467414d410000b18f0bfc6105000000206348524d00007a26000080840000fa00000080e8000075300000ea6000003a98000017709cba513c00000294504c544500000092df1691dc16fff104fcf1044bba31e9ff00ffff00fcc60805974cfe980e1a6acafe12172655b8451f89e300d0f50a49333fa7333fa5ee017cee017d91dd168ddc16bcea0dfdfe00ffff00ffff00fef602fef004fef10493de1591dd158fdc16fff104fff104fef20400934f48b93291dd1599e014c6ea0fe3e80affed02fff304fff104ffc309fe930e00954e00954e49b93283d61b1aa2433aad3ce3e80affae0afea60cfee805ffc309ff950efe950e00954a00954c03964d02954e06964dff960eff950eff960eff960eff970e009a8600955500954eff950eff920eff7d0e01a1db019eb500954cff960eff6810ff571001a1dd01a1d300954aff980eff5c10ff571001a0dd0696ce048e62ff7f0eff4f10ff56100f80d21670c61872a2f23a1cff1e13ff2e121969cb196acb285dbe57349b5c3097772580812379e0172dff1312ff1213ff1113196bcb196bcb2555b8383ca35c2c9365278f6428906428917f208ee0057ef70a47ff1313fe1313186ccc2555b8333fa5353ea45c2c946428907d218ce7037def017cf60a47ff1311333ea5333fa5323fa5652890f0017cee017cee017d333fa53140a647369d642890662890b71184f3007bee017ca6e312f6fc02ffff00fffe00fff4038cda17ffee0404974cff980e00954d9cd61effd605ff950e00976800944f4ab436f2fa03fffa01ffb50aff940eff890e01a0d2009157529f71d5e327fefd01ffd303ff770cff8b0eff5c1001a1de0a847f176aca307db6b38249ff2d0fff1313ff6010ff5710039ddc1473b5186bcc136ccf893c71ff100fff1112ff2911ff51101475cf2262c22263c41f64c6863c74f01420ec1522eb1623ff2012196bcb5b3097692b8c782580196acafe12163240a6ee02793c3aa1612a926f258ed30980ffffff2f2c00030000009674524e53000000000000000000000000000000000000000000166ec6f2fef2c76f1636bcfafabd3736d8fcd1b7dab7d1fbd93816bbf29279cef9cf7a91f2bd176efac4b7f6f6b8c3fa70c5ded9d9dec7f1a6c5c6a5f2fd90c1c28efef1a6c5c6a5f2c6ded8d9ddc76ffac4b8f7f7b9c3fefa7116bcf2917acff9cf7a91f2be1737d9fbd0b6dab6d0fbda3837bdfafefbbe381670c8f3f3c87117fe183c0c00000001624b4744db99041614000000097048597300000ec400000ec401952b0e1b0000000774494d4507e70c0d0a2821ecac8f64000000016f724e5401cfa2779a000000fb4944415408d701f0000fff0000010215161718191a1b1c1d0304000005061e1f20969798999a2122230708000924259b262728292a2b2c9c2d2e0a002f309d3132333435363738399e3a3b003c3d9f3e3f40a098a1414243a244450046a34748a4a5a698a7a8a9494aaa4b004cab4d4eacadaeafb0b1b24f50b3510052b45354b5b6b7b8b9babb5556bc570058bd595abebfc0c1c2c3c45b5cc55d005ec65f60c7c8c9cacbcccd6162ce63006465cf666768d0d1d2696a6b6c6d6e006f70d3717273747576777879d47a7b000b7c7dd57e7f8081828384d685860c000d0e878889d7d88ad9da8b8c8d0f10000011128e8f90918a92939495131400499d5efa9c5ec9650000002574455874646174653a63726561746500323032332d31322d31335431303a34303a33322b30303a3030be9f0d8a0000002574455874646174653a6d6f6469667900323032332d31322d31335431303a34303a33322b30303a3030cfc2b5360000000049454e44ae426082),
(24, 2, 'png', 0x89504e470d0a1a0a0000000d494844520000000f0000000f08030000000c0865780000000467414d410000b18f0bfc6105000000206348524d00007a26000080840000fa00000080e8000075300000ea6000003a98000017709cba513c000001b0504c5445fffffffdfefbdcf2b2f0f04dfff332fded6cfef5d7fefffdcbec988bd513d8e600fff000fbd80afbd240fef4cfc9eec178d1167ed100c5e200ffee01fbd20efac70ffacd53fef7eef9fdf957ce582cbe0079d000b1dd00feeb02fbce10f9c115f7a622fcdaaad5f3d419bc1803b5004ec600a0d900f8e604fac912f8ae1df79d20f9bd66fffdfaa9e7bf03b81e00b50213b9007dd100f0df06faba19f7a123f79c20f9a03dfef1e58be3d900c09a01bd6b00b83136c40adbcf0bfa9f1ff8871dfa7316fb7022fee1d181e0d700c2af01c4b100bfa909ac8aae7b3dfe6211fb6212fb6211fb691dfedcca8be2da00b9b001a3b60088be1764c29a2d8cfa2c17fe3e06fc510cfb6920fee0d1a9d9e8037ec0006fc30968c23848bb8d28a8e22759fe2504ff2600fe4f26ffeae3d4e6f5187bc9006cc22457be493eb88524abce288cef2734ff2100fe664dfff9f8f9fbfd569fd7125ebf384aba5139b77d22adc42895da2877f82718ffaa9ac2d8ef415bc03946b95a33b57821b0b92797d3268ce75d89ffeeeafefefeadb2e24953be612ab3731eb0aa249cdb50a2f5d1e7fbfcfec2c3e89464c78c48bfbf78c6f8d9ea0e66a0e800000001624b47440088051d48000000097048597300000ec400000ec401952b0e1b0000000774494d4507e70c0d0a29173a0d2bbc000000016f724e5401cfa2779a000000b64944415408d763600002462666165636061860e7e0e4e2e6e1e583f1f9050485844544c5c4215c09492969195939790545085f495945554d5d43534b5b07ccd7d5d3373034323631353307f32d2cadac6d6cedec1d1c9dc07c67175737770f4f2f6f1f5f30dfcf3f2030283824342c3c02cc8f8c8a8e898d8b4f484c4a06f35352d3d23332b3b27372f3c0fcfc82c2a2e292d2b2f20aa8fb2aabaa6b6aebea1b1aa1fca6e696d6b6f68e4eb887baba7b7afbfac14c002e1d2839550b59a10000002574455874646174653a63726561746500323032332d31322d31335431303a34313a32312b30303a3030ac1f7cb70000002574455874646174653a6d6f6469667900323032332d31322d31335431303a34313a32312b30303a3030dd42c40b0000000049454e44ae426082),
(25, 2, 'png', 0x89504e470d0a1a0a0000000d494844520000000f0000000f08030000000c0865780000000467414d410000b18f0bfc6105000000206348524d00007a26000080840000fa00000080e8000075300000ea6000003a98000017709cba513c000002a6504c54454520194b591f498e1f49c31e4df61e75f51e79c11f798d1f74581e7b241eec231ef9571ef88d1efac420fcf7194e2e4455624a54934a55c54a59f4497df34b82c4498193497c624b823149ea314af5614af59349f8c84bfbf7454e2c6354616b53936951c46956f4697cf46c7fc46c7f936a7a6168812f68e62f69f46068f49369f7c76bfbf7674e2c8452618851938b51c68b55f78c7cf68b7ec58b7e938b7a5f89812e87eb2e8af85e8df8928cf9c78cfdf7894f2ca65462aa5394ab53c6af57f6af7cf6ae7ec6ae7f94aa7d60ab812fa9ea30aaf660adf693acf8c7adfcf7ad4f2bc45460c85294ca51c7cc55f6ce7cf5cf80c6cd8094c87d61c68130c6ea30caf660ccf593cef8c8d1fcf8ce522ce55761e45593e756c6ec58f6ef7af5ef7cc5eb7b93e87760e47f2fe1ed2ee8fa5eeafa92eefdc7f2fff8f33a2cf04061f13f94f53dc6f746f5fa8ef7fc94c6f99293f69060f2952ff0db2ef5e55ef7e592fae8c8fce9f8fe232de82961e82993ec26c6ed30f6eda4f7f1aec7eeac94ebaa61eaad2fe8ca2fe9cd5feccf92efd0c8f0d0f7f3222ac52860c82993cb26c6cc31f7cda1f7cfa9c5cda692caa35ec8a52cc7cb2bc8d05ccad190cdd3c5cfd3f6cf2229a5285fa92993aa25c6ab32f5aea1f6aeabc6ada793aca35eaba52daaca2baad15dabd290acd3c5afd2f6ad262d852d60872e928b2ac58c34f58ca2f68cadc68da9948da5608aa63088cb2f89d15f8bd1928cd3c78cd4f689262c602c61672c91692ac16a33f36aa2f46aabc66aa7946ba3606ba22f69c62f68cc5f68ce926bd2c66bd2f567282e432e61492e93492bc64a36f44aa3f64bacc649a8944ca46249a63249c93149d06149d2924bd2c64cd3f54719211821571f1f8d1d1dc41f29f71e9df71fa6c41fa48e1ea0581ea3241dc9231ed0561fd08c1ed1c321d2f619ffffffba9c602200000001624b4744e15f08cfa6000000097048597300000ec300000ec301c76fa8640000000774494d4507e70c0d0a2a027cfd9c94000000016f724e5401cfa2779a000000fb4944415408d701f0000fff00000102030405060708090a0b0c0d0e000f101112131415161718191a1b1c1d001e1f202122232425262728292a2b2c002d2e2f303132333435363738393a3b003c3d3e3f404142434445464748494a004b4c4d4e4f50515253545556575859005a5b5c5d5e5f60616263646566676800696a6b6c6d6e6f70717273747576770078797a7b7c7d7e7f80818283848586008788898a8b8c8d8e8f90919293949500969798999a9b9c9d9e9fa0a1a2a3a400a5a6a7a8a9aaabacadaeafb0b1b2b300b4b5b6b7b8b9babbbcbdbebfc0c1c200c3c4c5c6c7c8c9cacbcccdcecfd0d100d2d3d4d5d6d7d8d9dadbdcdddedfe0b54a6271a7dabbd20000004174455874636f6d6d656e740043524541544f523a2067642d6a7065672076312e3020287573696e6720494a47204a50454720763830292c207175616c697479203d2037350acd8d05c80000002574455874646174653a63726561746500323032332d31322d31335431303a34323a30312b30303a3030050dc0c90000002574455874646174653a6d6f6469667900323032332d31322d31335431303a34323a30312b30303a3030745078750000000049454e44ae426082);

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
-- Dump dei dati per la tabella `Users`
--

INSERT INTO `Users` (`userid`, `username`, `password`, `type`) VALUES
(1, 'DEIB', 'a', 'user'),
(2, 'GEO', 'a', 'user');

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
-- Indici per le tabelle `CooperativeProductionProducts`
--
ALTER TABLE `CooperativeProductionProducts`
  ADD PRIMARY KEY (`user`,`product`);

--
-- Indici per le tabelle `CooperativeProductionProductsTrig`
--
ALTER TABLE `CooperativeProductionProductsTrig`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `CooperativeProductionProjects`
--
ALTER TABLE `CooperativeProductionProjects`
  ADD PRIMARY KEY (`user`,`project`);

--
-- Indici per le tabelle `CooperativeProductionProjectsTrig`
--
ALTER TABLE `CooperativeProductionProjectsTrig`
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
-- Indici per le tabelle `ExchangeProduct`
--
ALTER TABLE `ExchangeProduct`
  ADD PRIMARY KEY (`artisan`,`product`);

--
-- Indici per le tabelle `FeedbackCollaboration`
--
ALTER TABLE `FeedbackCollaboration`
  ADD PRIMARY KEY (`id`);

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
-- Indici per le tabelle `PurchasesChronology`
--
ALTER TABLE `PurchasesChronology`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `ReadMessage`
--
ALTER TABLE `ReadMessage`
  ADD PRIMARY KEY (`readBy`,`messageId`);

--
-- Indici per le tabelle `Review`
--
ALTER TABLE `Review`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT per la tabella `CooperativeProductionProductsTrig`
--
ALTER TABLE `CooperativeProductionProductsTrig`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `CooperativeProductionProjectsTrig`
--
ALTER TABLE `CooperativeProductionProjectsTrig`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `FeedbackCollaboration`
--
ALTER TABLE `FeedbackCollaboration`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `Messages`
--
ALTER TABLE `Messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `Metadata`
--
ALTER TABLE `Metadata`
  MODIFY `MediaCode` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT per la tabella `Ontology`
--
ALTER TABLE `Ontology`
  MODIFY `RelationID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT per la tabella `POI`
--
ALTER TABLE `POI`
  MODIFY `CodePOI` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `Product`
--
ALTER TABLE `Product`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ProductImages`
--
ALTER TABLE `ProductImages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT per la tabella `ProductTags`
--
ALTER TABLE `ProductTags`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
-- AUTO_INCREMENT per la tabella `PurchasesChronology`
--
ALTER TABLE `PurchasesChronology`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `Review`
--
ALTER TABLE `Review`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `Tags`
--
ALTER TABLE `Tags`
  MODIFY `TagID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT per la tabella `User`
--
ALTER TABLE `User`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `UserImages`
--
ALTER TABLE `UserImages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT per la tabella `Users`
--
ALTER TABLE `Users`
  MODIFY `userid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

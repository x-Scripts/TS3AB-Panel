-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Czas generowania: 03 Wrz 2020, 21:39
-- Wersja serwera: 5.6.12
-- Wersja PHP: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `www1675_xDashTS3AudioBot`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `xDashAccounts`
--

CREATE TABLE `xDashAccounts` (
  `username` varchar(32) NOT NULL,
  `password` text NOT NULL,
  `tokenAuthentication` text,
  `clientAvatar` blob
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `xDashAccounts`
--

INSERT INTO `xDashAccounts` (`username`, `password`, `tokenAuthentication`, `clientAvatar`) VALUES
('admin', '$2y$10$fHiZaQk3S/lagGF0J2wQz.UjlAzUORB3Y.UKUHafMdhCmxVj4o6Za', NULL, NULL);
-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `xDashBotList`
--

CREATE TABLE `xDashBotList` (
  `id` varchar(8) NOT NULL,
  `timeCreate` int(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `xDashBotList`
--

INSERT INTO `xDashBotList` (`id`, `timeCreate`) VALUES ('default', 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `xDashBotRights`
--

CREATE TABLE `xDashBotRights` (
  `id` varchar(7) NOT NULL,
  `clientPanel` text CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `clientFiles` text NOT NULL,
  `rightsCmd` text NOT NULL,
  `groups` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `xDashBotRights`
--

INSERT INTO `xDashBotRights` (`id`, `clientPanel`, `clientFiles`, `rightsCmd`, `groups`) VALUES
('default', '[]', '[]', '[]', '[]');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `xDashBotsUsers`
--

CREATE TABLE `xDashBotsUsers` (
  `username` varchar(32) NOT NULL,
  `botID` varchar(8) NOT NULL,
  `timeAdd` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `xDashLoginHistory`
--

CREATE TABLE `xDashLoginHistory` (
  `username` varchar(32) NOT NULL,
  `time` int(30) NOT NULL,
  `ip` text NOT NULL,
  `browser` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `xDashPermissionsUsers`
--

CREATE TABLE `xDashPermissionsUsers` (
  `username` varchar(32) NOT NULL,
  `limitBots` int(5) DEFAULT NULL,
  `userRights` text,
  `viewLogs` int(1) DEFAULT NULL,
  `startStopApp` int(1) DEFAULT NULL,
  `viewUsage` int(1) DEFAULT NULL,
  `editSettings` int(1) NOT NULL,
  `viewAllBots` int(1) DEFAULT NULL,
  `deleteBots` int(1) DEFAULT NULL,
  `addBotUsers` int(1) NOT NULL,
  `addUsersBot` int(1) DEFAULT NULL,
  `editSimpleBot` int(1) DEFAULT NULL,
  `editAdvancedBot` int(1) DEFAULT NULL,
  `editExpertBot` int(1) DEFAULT NULL,
  `editRightsBot` int(1) DEFAULT NULL,
  `managePlaylist` int(1) DEFAULT NULL,
  `playSong` int(1) DEFAULT NULL,
  `manageMusic` int(1) DEFAULT NULL,
  `createSimple` int(1) DEFAULT NULL,
  `createAdvanced` int(1) DEFAULT NULL,
  `createExpert` int(1) DEFAULT NULL,
  `viewAllHistory` int(1) DEFAULT NULL,
  `clearHistory` int(1) DEFAULT NULL,
  `viewFullIP` int(1) DEFAULT NULL,
  `viewAccountsList` int(1) DEFAULT NULL,
  `deleteAccount` int(1) DEFAULT NULL,
  `addAccount` int(1) DEFAULT NULL,
  `editAccountPassword` int(1) DEFAULT NULL,
  `editAccountLogin` int(1) DEFAULT NULL,
  `editLimitBots` int(1) DEFAULT NULL,
  `editAccountTwoAuth` int(1) DEFAULT NULL,
  `editAccountBotRights` int(1) DEFAULT NULL,
  `editAccountPerms` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `xDashPermissionsUsers`
--

INSERT INTO `xDashPermissionsUsers` (`username`, `limitBots`, `userRights`, `viewLogs`, `startStopApp`, `viewUsage`, `editSettings`, `viewAllBots`, `deleteBots`, `addBotUsers`, `addUsersBot`, `editSimpleBot`, `editAdvancedBot`, `editExpertBot`, `editRightsBot`, `managePlaylist`, `playSong`, `manageMusic`, `createSimple`, `createAdvanced`, `createExpert`, `viewAllHistory`, `clearHistory`, `viewFullIP`, `viewAccountsList`, `deleteAccount`, `addAccount`, `editAccountPassword`, `editAccountLogin`, `editLimitBots`, `editAccountTwoAuth`, `editAccountBotRights`, `editAccountPerms`) VALUES
('admin', 0, 'all', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `xDashRemembers`
--

CREATE TABLE `xDashRemembers` (
  `id` varchar(20) NOT NULL,
  `username` varchar(32) NOT NULL,
  `time` int(30) NOT NULL,
  `platform` text,
  `browser` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `xDashSessions`
--

CREATE TABLE `xDashSessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `xDashSettings`
--

CREATE TABLE `xDashSettings` (
  `id` varchar(30) NOT NULL,
  `type` varchar(10) NOT NULL,
  `value` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `xDashSettings`
--

INSERT INTO `xDashSettings` (`id`, `type`, `value`) VALUES
('adminGroups', 'JSON', '[]'),
('adminUsers', 'JSON', '[]'),
('apiKey', 'STRING', ''),
('apiLocal', 'STRING', ''),
('apiToken', 'STRING', ''),
('apiType', 'STRING', 'externalhost'),
('appApi', 'INT', '0'),
('host', 'STRING', ''),
('ownerGroup', 'JSON', '[]'),
('ownerUserUID', 'JSON', '[]'),
('port', 'INT', '58913'),
('timeout', 'INT', '5');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `xDashAccounts`
--
ALTER TABLE `xDashAccounts`
  ADD PRIMARY KEY (`username`);

--
-- Indeksy dla tabeli `xDashBotList`
--
ALTER TABLE `xDashBotList`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `xDashBotRights`
--
ALTER TABLE `xDashBotRights`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `xDashPermissionsUsers`
--
ALTER TABLE `xDashPermissionsUsers`
  ADD PRIMARY KEY (`username`);

--
-- Indeksy dla tabeli `xDashRemembers`
--
ALTER TABLE `xDashRemembers`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `xDashSessions`
--
ALTER TABLE `xDashSessions`
  ADD KEY `ci_sessions_timestamp` (`timestamp`);

--
-- Indeksy dla tabeli `xDashSettings`
--
ALTER TABLE `xDashSettings`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

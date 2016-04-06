-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 07-Abr-2016 às 00:30
-- Versão do servidor: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `multiclientcms`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `clients`
--

CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `webHost` varchar(256) NOT NULL,
  `dbHost` varchar(256) NOT NULL,
  `dbName` varchar(256) NOT NULL,
  `dbUser` varchar(256) NOT NULL,
  `dbPass` varchar(256) NOT NULL,
  `ftpHost` varchar(256) NOT NULL,
  `ftpUser` varchar(256) NOT NULL,
  `ftpPass` varchar(256) NOT NULL,
  `ftpRoot` varchar(256) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `image_settings`
--

CREATE TABLE IF NOT EXISTS `image_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `limit` int(11) NOT NULL DEFAULT '0',
  `cropWidth` int(11) NOT NULL,
  `cropHeight` int(11) NOT NULL,
  `cropAspectRatio` varchar(256) NOT NULL,
  `client` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `client` (`client`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `schemes`
--

CREATE TABLE IF NOT EXISTS `schemes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `tableName` varchar(256) NOT NULL,
  `listFields` int(11) NOT NULL,
  `maxRecords` int(11) NOT NULL,
  `sortable` tinyint(4) NOT NULL DEFAULT '0',
  `editFields` int(11) NOT NULL,
  `groupBy` varchar(256) NOT NULL,
  `hide` tinyint(4) NOT NULL,
  `imageSettings` int(11) DEFAULT NULL,
  `listThumbnail` tinyint(4) NOT NULL DEFAULT '0',
  `subScheme` int(11) DEFAULT NULL,
  `client` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `client` (`client`),
  KEY `subScheme` (`subScheme`),
  KEY `image_settings` (`imageSettings`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `translations`
--

CREATE TABLE IF NOT EXISTS `translations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` varchar(256) NOT NULL,
  `to` varchar(256) NOT NULL,
  `client` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `client` (`client`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(256) NOT NULL DEFAULT '',
  `name` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `isAdmin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `users_clients`
--

CREATE TABLE IF NOT EXISTS `users_clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `client` (`client`),
  KEY `user` (`user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `_assortment`
--

CREATE TABLE IF NOT EXISTS `_assortment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tableName` varchar(256) NOT NULL,
  `itemId` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=107 ;

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `image_settings`
--
ALTER TABLE `image_settings`
  ADD CONSTRAINT `image_settings_ibfk_1` FOREIGN KEY (`client`) REFERENCES `clients` (`id`);

--
-- Limitadores para a tabela `schemes`
--
ALTER TABLE `schemes`
  ADD CONSTRAINT `schemes_ibfk_1` FOREIGN KEY (`client`) REFERENCES `clients` (`id`),
  ADD CONSTRAINT `schemes_ibfk_2` FOREIGN KEY (`subScheme`) REFERENCES `schemes` (`id`),
  ADD CONSTRAINT `schemes_ibfk_3` FOREIGN KEY (`imageSettings`) REFERENCES `image_settings` (`id`);

--
-- Limitadores para a tabela `translations`
--
ALTER TABLE `translations`
  ADD CONSTRAINT `translations_ibfk_1` FOREIGN KEY (`client`) REFERENCES `clients` (`id`);

--
-- Limitadores para a tabela `users_clients`
--
ALTER TABLE `users_clients`
  ADD CONSTRAINT `users_clients_ibfk_1` FOREIGN KEY (`user`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `users_clients_ibfk_2` FOREIGN KEY (`client`) REFERENCES `clients` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

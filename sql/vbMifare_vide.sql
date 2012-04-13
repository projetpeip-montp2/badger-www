-- phpMyAdmin SQL Dump
-- version 3.5.0
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Sam 14 Avril 2012 à 00:19
-- Version du serveur: 5.5.23-log
-- Version de PHP: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `vbMifare`
--
CREATE DATABASE IF NOT EXISTS `vbMifare`;
USE `vbMifare`;

-- --------------------------------------------------------

--
-- Structure de la table `Answers`
--

CREATE TABLE IF NOT EXISTS `Answers` (
  `Id_answer` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `Id_question` smallint(5) unsigned NOT NULL,
  `Label_fr` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `Label_en` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `TrueOrFalse` char(1) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`Id_answer`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `AnswersOfUsers`
--

CREATE TABLE IF NOT EXISTS `AnswersOfUsers` (
  `Id_user` varchar(60) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `Id_question` smallint(5) unsigned NOT NULL,
  `Id_answer` smallint(5) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `Availabilities`
--

CREATE TABLE IF NOT EXISTS `Availabilities` (
  `Id_availability` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `Id_classroom` smallint(5) unsigned NOT NULL,
  `Date` date NOT NULL,
  `StartTime` time NOT NULL,
  `EndTime` time NOT NULL,
  PRIMARY KEY (`Id_availability`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `BadgingInformations`
--

CREATE TABLE IF NOT EXISTS `BadgingInformations` (
  `Mifare` char(8) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `Date` date NOT NULL,
  `Time` time NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `Classrooms`
--

CREATE TABLE IF NOT EXISTS `Classrooms` (
  `Id_classroom` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `Name` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `Size` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`Id_classroom`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `DocumentsOfPackages`
--

CREATE TABLE IF NOT EXISTS `DocumentsOfPackages` (
  `Id_package` smallint(5) unsigned NOT NULL,
  `Filename` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `Path` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `Downloadable` tinyint(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `DocumentsOfUsers`
--

CREATE TABLE IF NOT EXISTS `DocumentsOfUsers` (
  `Id_package` smallint(5) unsigned NOT NULL,
  `Id_user` smallint(5) unsigned NOT NULL,
  `Filename` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `Path` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `Lectures`
--

CREATE TABLE IF NOT EXISTS `Lectures` (
  `Id_lecture` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `Id_package` smallint(5) unsigned NOT NULL,
  `Id_availability` smallint(5) unsigned NOT NULL,
  `Name_fr` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `Name_en` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `Description_fr` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `Description_en` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `Date` date NOT NULL,
  `StartTime` time NOT NULL,
  `EndTime` time NOT NULL,
  PRIMARY KEY (`Id_lecture`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `Packages`
--

CREATE TABLE IF NOT EXISTS `Packages` (
  `Id_package` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `Lecturer` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `Name_fr` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `Name_en` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `Description_fr` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `Description_en` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`Id_package`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `Questions`
--

CREATE TABLE IF NOT EXISTS `Questions` (
  `Id_question` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `Id_package` smallint(5) unsigned NOT NULL,
  `Label_fr` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `Label_en` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `Status` enum('Possible','Impossible','Obligatory') CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  PRIMARY KEY (`Id_question`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `QuestionsOfUsers`
--

CREATE TABLE IF NOT EXISTS `QuestionsOfUsers` (
  `Id_user` varchar(60) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `Id_question` smallint(5) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `Registrations`
--

CREATE TABLE IF NOT EXISTS `Registrations` (
  `Id_package` smallint(5) unsigned NOT NULL,
  `Id_user` varchar(60) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `Status` enum('Coming','Absent','Present') CHARACTER SET latin1 COLLATE latin1_bin NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
  `Id_user` varchar(60) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `MCQStatus` set('Visitor','CanTakeMCQ','Generated','Taken') NOT NULL,
  `Mark` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`Id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

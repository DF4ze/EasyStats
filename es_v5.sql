-- phpMyAdmin SQL Dump
-- version 2.10.1
-- http://www.phpmyadmin.net
-- 
-- Serveur: localhost
-- Généré le : Lun 30 Avril 2012 à 09:31
-- Version du serveur: 5.0.45
-- Version de PHP: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Base de données: `es_v5`
-- 

-- --------------------------------------------------------

-- 
-- Structure de la table `crashs`
-- 

CREATE TABLE `crashs` (
  `id` int(11) NOT NULL auto_increment,
  `client` varchar(50) NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL,
  `duree` int(11) NOT NULL,
  `commentaire` longtext NOT NULL,
  `categorie` enum('crash','evenement') NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- 
-- Contenu de la table `crashs`
-- 

INSERT INTO `crashs` (`id`, `client`, `date_debut`, `date_fin`, `duree`, `commentaire`, `categorie`) VALUES 
(1, 'Aer_TLSE', '2011-01-21 16:45:00', '2011-01-21 17:30:00', 10, 'Test', 'crash');

-- --------------------------------------------------------

-- 
-- Structure de la table `errors`
-- 

CREATE TABLE `errors` (
  `id` int(11) NOT NULL auto_increment,
  `type` enum('warning','error') NOT NULL,
  `message` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- 
-- Contenu de la table `errors`
-- 

INSERT INTO `errors` (`id`, `type`, `message`) VALUES 
(1, 'error', 'Function appelée sans parametres'),
(2, 'error', 'Les parametres fondamentaux ne sont pas présent.'),
(3, 'error', 'Offset invalide'),
(4, 'warning', 'Entrée deja présente.');

-- --------------------------------------------------------

-- 
-- Structure de la table `es_clients`
-- 

CREATE TABLE `es_clients` (
  `id` smallint(3) unsigned NOT NULL auto_increment,
  `nomclient` varchar(30) NOT NULL,
  `nomequipe` varchar(30) NOT NULL default 'NoTeam',
  `h_ouverture` varchar(10) NOT NULL default '08:00',
  `h_fermeture` varchar(10) NOT NULL default '18:00',
  `work_jf` tinyint(1) NOT NULL,
  `work_we` tinyint(1) NOT NULL,
  `tabbornes` text NOT NULL,
  `sla` mediumint(9) NOT NULL,
  `color` varchar(8) NOT NULL default '000000',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=62 ;

-- 
-- Contenu de la table `es_clients`
-- 

INSERT INTO `es_clients` (`id`, `nomclient`, `nomequipe`, `h_ouverture`, `h_fermeture`, `work_jf`, `work_we`, `tabbornes`, `sla`, `color`) VALUES 
(1, 'sis', 'Equipe DTC', '07:30', '20:01', 1, 0, 'a:0:{}', 90, ''),
(2, 'sdis38', 'Equipe DTC', '07:30', '20:00', 1, 0, 'a:4:{i:0;a:4:{s:3:"nom";s:9:"Base Stat";s:4:"type";s:12:"personnalise";s:4:"code";s:52:"[nbappels]+([nbappels]-[compabs;inf;20;])-[nbappels]";s:5:"total";s:3:"sum";}i:1;a:5:{s:3:"nom";s:10:"% abs / BS";s:4:"type";s:12:"personnalise";s:4:"code";s:54:"[compabs;inf;20;]*[100]/([nbappels]-[compabs;inf;20;])";s:5:"total";s:3:"avg";s:5:"seuil";i:0;}i:2;a:3:{s:3:"nom";s:11:"Moy Attente";s:4:"type";s:13:"moytpsattente";s:5:"total";s:3:"avg";}i:3;a:3:{s:3:"nom";s:7:"Moy Com";s:4:"type";s:9:"moytpscom";s:5:"total";s:3:"avg";}}', 0, ''),
(3, 'aer_tlse', 'Equipe DTC', '07:30', '20:00', 1, 0, 'a:4:{i:0;a:4:{s:3:"nom";s:9:"Base Stat";s:4:"type";s:12:"personnalise";s:4:"code";s:52:"[nbappels]+([nbappels]-[compabs;inf;20;])-[nbappels]";s:5:"total";s:3:"sum";}i:1;a:5:{s:3:"nom";s:10:"% abs / BS";s:4:"type";s:12:"personnalise";s:4:"code";s:54:"[compabs;inf;20;]*[100]/([nbappels]-[compabs;inf;20;])";s:5:"total";s:3:"avg";s:5:"seuil";i:0;}i:2;a:3:{s:3:"nom";s:11:"Moy Attente";s:4:"type";s:13:"moytpsattente";s:5:"total";s:3:"avg";}i:3;a:3:{s:3:"nom";s:7:"Moy Com";s:4:"type";s:9:"moytpscom";s:5:"total";s:3:"avg";}}', 0, ''),
(4, 'adoma', 'NoTeam', '07:30', '20:00', 0, 0, 'a:0:{}', 0, ''),
(5, 'aer_tlsecrash', 'NoTeam', '07:30', '20:00', 0, 0, 'a:0:{}', 0, ''),
(6, 'aero_toulouse_cergy', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(7, 'afpa', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(8, 'afpacrash', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(9, 'amecspie', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(10, 'amecspiecrash', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(11, 'asc_idf', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(12, 'asc_idfcrash', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(13, 'asc_oc-ne', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(14, 'asc_oc-necrash', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(15, 'asei', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(16, 'cg60', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(17, 'cg82', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(18, 'chu_grenoble', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(19, 'chu31', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(20, 'chu31crash', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(21, 'cnsa', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(22, 'conseil_europe', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(23, 'corteo', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(24, 'corteo_idf', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(25, 'corteo_ne', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(26, 'corteo_se', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(27, 'corteo_so', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(28, 'drse', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(29, 'drso', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(30, 'eps_ville_evrard', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(31, 'gie_sesame_et_vitale', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(32, 'hd_spse', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(33, 'hd_3a', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(34, 'inserm', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(35, 'intespace', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(36, 'ipme', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(37, 'lambdatelecom', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(38, 'mairie_blagnac', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(39, 'mairieboulogne', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(40, 'mcc', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(41, 'ministere_justice', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(42, 'mpm', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(43, 'mtsdsi', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(44, 'occupation', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(45, 'pair', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(46, 'rodia', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(47, 'saira_seats', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(48, 'sermont', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(49, 'service_test', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(50, 'sogerma', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(51, 'spie_est', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(52, 'supervision_ft', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(53, 'telviewh24', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(54, 'telviewho', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(55, 'telviewtech', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(56, 'testmalakoff', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(57, 'ugap', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(58, 'ugap_cciv', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(59, 'ugap_opac71', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(60, 'ugap_ville_valence', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, ''),
(61, 'vnf', 'NoTeam', '07:30', '20:00', 0, 0, 'N;', 0, '');

-- --------------------------------------------------------

-- 
-- Structure de la table `matable`
-- 

CREATE TABLE `matable` (
  `CallID` int(11) NOT NULL auto_increment,
  `CallTime` datetime NOT NULL,
  `CallWaitingDuration` int(11) NOT NULL,
  PRIMARY KEY  (`CallID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=57 ;

-- 
-- Contenu de la table `matable`
-- 

INSERT INTO `matable` (`CallID`, `CallTime`, `CallWaitingDuration`) VALUES 
(1, '2011-01-20 08:25:40', 11),
(2, '2011-01-20 09:02:26', 8),
(3, '2011-01-20 09:05:00', 6),
(4, '2011-01-20 09:13:06', 7),
(5, '2011-01-20 09:28:00', 11),
(6, '2011-01-20 09:37:46', 9),
(7, '2011-01-20 10:40:48', 20),
(8, '2011-01-20 11:50:10', 6),
(9, '2011-01-20 11:53:53', 9),
(10, '2011-01-20 12:17:10', 34),
(11, '2011-01-20 14:13:48', 8),
(12, '2011-01-20 14:19:10', 16),
(13, '2011-01-20 14:38:03', 13),
(14, '2011-01-20 15:54:28', 7),
(15, '2011-01-20 16:04:27', 11),
(16, '2011-01-20 16:20:58', 13),
(17, '2011-01-20 16:52:12', 13),
(18, '2011-01-20 17:06:41', 1),
(19, '2011-01-20 17:40:59', 18),
(20, '2011-01-20 18:39:14', 44),
(21, '2011-01-21 07:33:39', 3),
(22, '2011-01-21 07:52:40', 5),
(23, '2011-01-21 07:52:51', 7),
(24, '2011-01-21 09:27:29', 9),
(25, '2011-01-21 09:31:55', 7),
(26, '2011-01-21 09:38:30', 11),
(27, '2011-01-21 09:52:13', 10),
(28, '2011-01-21 11:06:13', 7),
(29, '2011-01-21 12:03:22', 6),
(30, '2011-01-21 14:56:03', 9),
(31, '2011-01-21 14:56:17', 12),
(32, '2011-01-21 16:00:22', 1),
(33, '2011-01-21 16:46:31', 12),
(34, '2011-01-21 17:27:04', 38),
(35, '2011-01-21 17:49:19', 10),
(36, '2011-01-21 19:19:38', 9),
(37, '2011-01-22 08:25:40', 11),
(38, '2011-01-22 09:02:26', 8),
(39, '2011-01-22 09:05:00', 6),
(40, '2011-01-22 09:13:06', 7),
(41, '2011-01-22 09:28:00', 11),
(42, '2011-01-22 09:37:46', 9),
(43, '2011-01-22 10:40:48', 20),
(44, '2011-01-22 11:50:10', 6),
(45, '2011-01-22 11:53:53', 9),
(46, '2011-01-22 12:17:10', 34),
(47, '2011-01-22 14:13:48', 8),
(48, '2011-01-22 14:19:10', 16),
(49, '2011-01-22 14:38:03', 13),
(50, '2011-01-22 15:54:28', 7),
(51, '2011-01-22 16:04:27', 11),
(52, '2011-01-22 16:20:58', 13),
(53, '2011-01-22 16:52:12', 13),
(54, '2011-01-22 17:06:41', 1),
(55, '2011-01-22 17:40:59', 18),
(56, '2011-01-22 18:39:14', 44);

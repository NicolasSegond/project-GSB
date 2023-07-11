-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 30 mars 2023 à 15:36
-- Version du serveur : 5.7.36
-- Version de PHP : 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gsbextranet1`
--

-- --------------------------------------------------------

--
-- Structure de la table `avisconference`
--

DROP TABLE IF EXISTS `avisconference`;
CREATE TABLE IF NOT EXISTS `avisconference` (
  `idMedecin` int(11) NOT NULL,
  `idVisio` int(11) NOT NULL,
  `avis` varchar(255) NOT NULL,
  `validationAvis` int(1) NOT NULL,
  PRIMARY KEY (`idMedecin`,`idVisio`),
  KEY `idVisio` (`idVisio`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `avisconference`
--

INSERT INTO `avisconference` (`idMedecin`, `idVisio`, `avis`, `validationAvis`) VALUES
(6, 3, 'fzfzf', 0),
(11, 1, 'test', 0),
(11, 3, 'test', 1),
(11, 4, 'super', 0);

-- --------------------------------------------------------

--
-- Structure de la table `historiqueconnexion`
--

DROP TABLE IF EXISTS `historiqueconnexion`;
CREATE TABLE IF NOT EXISTS `historiqueconnexion` (
  `idMedecin` int(11) NOT NULL,
  `dateDebutLog` datetime NOT NULL,
  `dateFinLog` datetime DEFAULT NULL,
  PRIMARY KEY (`idMedecin`,`dateDebutLog`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `historiqueconnexion`
--

INSERT INTO `historiqueconnexion` (`idMedecin`, `dateDebutLog`, `dateFinLog`) VALUES
(12, '2022-11-07 18:59:50', '2022-11-07 18:59:50'),
(12, '2022-11-07 19:00:17', '2022-11-07 19:00:17'),
(12, '2023-03-30 14:42:36', '2023-03-30 14:42:36');

-- --------------------------------------------------------

--
-- Structure de la table `listeoperationproduit`
--

DROP TABLE IF EXISTS `listeoperationproduit`;
CREATE TABLE IF NOT EXISTS `listeoperationproduit` (
  `idProd` int(11) NOT NULL,
  `idTypeOpe` int(11) NOT NULL,
  `idMedecin` int(11) NOT NULL,
  PRIMARY KEY (`idProd`,`idTypeOpe`,`idMedecin`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `listeoperationproduit`
--

INSERT INTO `listeoperationproduit` (`idProd`, `idTypeOpe`, `idMedecin`) VALUES
(4, 2, 5),
(4, 3, 5),
(12, 1, 5),
(12, 3, 5),
(13, 1, 5),
(13, 2, 5),
(13, 3, 5),
(14, 1, 5),
(14, 2, 5),
(14, 3, 5),
(15, 1, 5),
(15, 2, 5),
(15, 3, 5),
(16, 1, 5),
(16, 3, 5),
(17, 1, 5),
(17, 3, 5),
(18, 1, 5),
(18, 2, 5),
(18, 3, 5),
(19, 1, 5),
(19, 2, 5),
(19, 3, 5),
(20, 1, 5),
(20, 3, 5),
(21, 1, 5),
(22, 1, 5),
(22, 2, 5);

-- --------------------------------------------------------

--
-- Structure de la table `maintenance`
--

DROP TABLE IF EXISTS `maintenance`;
CREATE TABLE IF NOT EXISTS `maintenance` (
  `siMaintenance` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `maintenance`
--

INSERT INTO `maintenance` (`siMaintenance`) VALUES
(0);

-- --------------------------------------------------------

--
-- Structure de la table `medecin`
--

DROP TABLE IF EXISTS `medecin`;
CREATE TABLE IF NOT EXISTS `medecin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(40) DEFAULT NULL,
  `prenom` varchar(30) DEFAULT NULL,
  `mail` varchar(50) DEFAULT NULL,
  `dateNaissance` date DEFAULT NULL,
  `motDePasse` varchar(30) DEFAULT NULL,
  `dateCreation` datetime DEFAULT NULL,
  `rpps` varchar(10) DEFAULT NULL,
  `token` varchar(100) DEFAULT NULL,
  `dateDiplome` date DEFAULT NULL,
  `dateConsentement` date DEFAULT NULL,
  `typeUtilisateur` int(11) DEFAULT NULL,
  `RppsValide` tinyint(1) NOT NULL DEFAULT '0',
  `CodeVerif` varchar(6) DEFAULT NULL,
  `ip` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `TypeUtilisateur` (`typeUtilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `medecin`
--

INSERT INTO `medecin` (`id`, `nom`, `prenom`, `mail`, `dateNaissance`, `motDePasse`, `dateCreation`, `rpps`, `token`, `dateDiplome`, `dateConsentement`, `typeUtilisateur`, `RppsValide`, `CodeVerif`, `ip`) VALUES
(2, NULL, NULL, 'modo@modo.fr', NULL, 'Modo123456*', '2022-09-15 16:52:39', NULL, NULL, NULL, '2022-09-15', 2, 1, '8318a2', '::1'),
(3, NULL, NULL, 'admin@admin.fr', NULL, 'Admin123456*', '2022-09-15 17:06:47', NULL, NULL, NULL, '2022-09-15', 5, 1, '2dbbbe', '::1'),
(4, NULL, NULL, 'valideur@validateur.fr', NULL, 'Validateur123456*', '2022-09-22 16:03:17', NULL, NULL, NULL, '2022-09-22', 3, 1, 'cd9e05', '::1'),
(5, NULL, NULL, 'chef@chef.com', NULL, 'Chef123456*', '2022-09-22 16:04:07', NULL, NULL, NULL, '2022-09-22', 4, 1, 'd75756', '::1'),
(6, NULL, NULL, 'medecin@medecin.fr', NULL, 'Medecin123456*', '2022-09-22 16:21:32', NULL, NULL, NULL, '2022-09-22', 1, 1, '99a1d0', '::1'),
(11, 'Jean', 'François', 'contactgsb9@gmail.com', NULL, 'Admin123456*', '2022-11-03 16:11:03', '121212', NULL, NULL, '2022-11-03', 1, 1, '0356d8', '::1'),
(12, 'test', 'Nicolas', 'nicolassegond0@gmail.com', NULL, 'Test123456*', '2023-03-30 14:42:36', '1212121212', NULL, NULL, '2023-03-30', 1, 1, '8cd3b0', '::1');

-- --------------------------------------------------------

--
-- Structure de la table `medecinproduit`
--

DROP TABLE IF EXISTS `medecinproduit`;
CREATE TABLE IF NOT EXISTS `medecinproduit` (
  `idMedecin` int(11) NOT NULL,
  `idProduit` int(11) NOT NULL,
  `Date` date NOT NULL,
  `Heure` time NOT NULL,
  PRIMARY KEY (`idMedecin`,`idProduit`,`Date`,`Heure`),
  KEY `idProduit` (`idProduit`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `medecinvisio`
--

DROP TABLE IF EXISTS `medecinvisio`;
CREATE TABLE IF NOT EXISTS `medecinvisio` (
  `idMedecin` int(11) NOT NULL,
  `idVisio` int(11) NOT NULL,
  `dateInscription` date NOT NULL,
  PRIMARY KEY (`idMedecin`,`idVisio`),
  KEY `idVisio` (`idVisio`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `medecinvisio`
--

INSERT INTO `medecinvisio` (`idMedecin`, `idVisio`, `dateInscription`) VALUES
(5, 6, '2023-03-30'),
(6, 7, '2023-03-30'),
(11, 1, '2022-11-20'),
(11, 2, '2022-11-13'),
(11, 3, '2022-11-20'),
(11, 4, '2022-11-12'),
(11, 6, '2023-03-30'),
(11, 8, '2022-11-13');

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

DROP TABLE IF EXISTS `produit`;
CREATE TABLE IF NOT EXISTS `produit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(60) NOT NULL,
  `image` varchar(60) NOT NULL,
  `objectif` mediumtext NOT NULL,
  `information` mediumtext NOT NULL,
  `effetIndesirable` mediumtext NOT NULL,
  `vuesProduits` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id`, `nom`, `image`, `objectif`, `information`, `effetIndesirable`, `vuesProduits`) VALUES
(21, 'Doliprane', 'Doliprane.jpg', 'réduire les maux', 'Le médicament Doliprane est un analgésique antipyrétique couramment utilisé pour traiter la douleur et la fièvre. Il contient comme principe actif le paracétamol, une substance qui agit en inhibant la production de prostaglandines, des substances dans l\'organisme qui provoquent douleur et inflammation.', 'Les effets indésirables les plus courants associés au Doliprane sont :  Les nausées et les vomissements La perte d\'appétit La constipation Les douleurs abdominales Les éruptions cutanées Les maux de tête Les vertiges La somnolence', 0),
(22, 'Smecta', 'Smecta.jpg', 'Le Smecta est un médicament utilisé pour traiter les troubles gastro-intestinaux tels que la diarrhée et les douleurs abdominales. L\'objectif principal du Smecta est de soulager les symptômes associés à ces troubles en réduisant l\'inflammation de la muqueuse intestinale et en régulant le transit intestinal.', 'Le Smecta est un médicament anti-diarrhéique qui est utilisé pour traiter les symptômes de la diarrhée aiguë et chronique chez les adultes et les enfants de plus de 2 ans. Il contient du dioctahedral smectite, une argile naturelle qui absorbe l\'excès de liquide dans l\'intestin et qui aide à réguler le transit intestinal.', 'Le médicament Smecta est généralement bien toléré par la plupart des patients, mais comme tout médicament, il peut avoir des effets indésirables. ', 0);

-- --------------------------------------------------------

--
-- Structure de la table `typeoperationproduit`
--

DROP TABLE IF EXISTS `typeoperationproduit`;
CREATE TABLE IF NOT EXISTS `typeoperationproduit` (
  `idTypeOpe` int(11) NOT NULL,
  `typeOperation` varchar(12) NOT NULL,
  PRIMARY KEY (`idTypeOpe`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `typeoperationproduit`
--

INSERT INTO `typeoperationproduit` (`idTypeOpe`, `typeOperation`) VALUES
(1, 'Création'),
(2, 'Mise à jour'),
(3, 'Suppression');

-- --------------------------------------------------------

--
-- Structure de la table `typeutilisateur`
--

DROP TABLE IF EXISTS `typeutilisateur`;
CREATE TABLE IF NOT EXISTS `typeutilisateur` (
  `TypeUtilisateur` int(11) NOT NULL AUTO_INCREMENT,
  `nomType` varchar(30) NOT NULL,
  PRIMARY KEY (`TypeUtilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `typeutilisateur`
--

INSERT INTO `typeutilisateur` (`TypeUtilisateur`, `nomType`) VALUES
(1, 'Médecin'),
(2, 'Modérateur'),
(3, 'Validateur'),
(4, 'Chef de produit'),
(5, 'Administrateur');

-- --------------------------------------------------------

--
-- Structure de la table `visioconference`
--

DROP TABLE IF EXISTS `visioconference`;
CREATE TABLE IF NOT EXISTS `visioconference` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomVisio` varchar(100) DEFAULT NULL,
  `objectif` text,
  `url` varchar(100) DEFAULT NULL,
  `dateVisio` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `visioconference`
--

INSERT INTO `visioconference` (`id`, `nomVisio`, `objectif`, `url`, `dateVisio`) VALUES
(3, 'test1', 'un objectif', 'test.fr', '2022-11-27'),
(6, 'Visio consultatif', 'Etre consulté', 'www.teams.fr', '2023-03-31');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `avisconference`
--
ALTER TABLE `avisconference`
  ADD CONSTRAINT `idMedecin` FOREIGN KEY (`idMedecin`) REFERENCES `medecinvisio` (`idMedecin`),
  ADD CONSTRAINT `idVisio` FOREIGN KEY (`idVisio`) REFERENCES `medecinvisio` (`idVisio`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

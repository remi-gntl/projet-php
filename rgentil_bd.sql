-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : mysql-5.7
-- Généré le : ven. 25 oct. 2024 à 17:47
-- Version du serveur : 5.7.28
-- Version de PHP : 8.0.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `rgentil_bd`
--

-- --------------------------------------------------------

--
-- Structure de la table `diplome`
--

CREATE TABLE `diplome` (
  `id` int(11) NOT NULL,
  `Titre` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Prix` decimal(10,2) NOT NULL,
  `Image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `diplome`
--

INSERT INTO `diplome` (`id`, `Titre`, `Description`, `Prix`, `Image`) VALUES
(1, 'Baccalaureat', 'Diplome officiel du baccalaureat ', '150.00', 'images/diplome-bac.jpg'),
(2, 'Brevet des collèges', 'Diplome officiel du brevet', '100.00', 'images/diplome-brevet.jpg'),
(3, 'Permis de Conduire', 'Permis de conduire officiel categorie B', '500.00', 'images/diplome-permis.png'),
(4, 'Diplome BUT Informatique', 'Diplome officiel du BUT Informatique (fait à Montaury)', '5.00', 'images/diplome-but.png'),
(5, 'Diplome Pro-Player Fortnite', 'Diplome officiel du meilleur joueur fortnite ever', '95.00', 'images/diplome-fortnite.png'),
(6, 'Diplome meilleur Alcoolique', 'Diplome officiel du plus grand trou à gnôle, il en faut pour le coucher celui la...', '50.00', 'images/diplome-alcoolique.png');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `status` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `email`, `mot_de_passe`, `status`) VALUES
(6, 'user@gmail.com', 'user', 0),
(7, 'admin@gmail.com', 'admin', 1),
(8, 'remi@gmail.com', 'remi', 1),
(9, 'maxime@gmail.com', 'maxime', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `diplome`
--
ALTER TABLE `diplome`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `diplome`
--
ALTER TABLE `diplome`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1deb1+focal2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : jeu. 18 déc. 2025 à 15:47
-- Version du serveur : 8.0.42-0ubuntu0.20.04.1
-- Version de PHP : 7.4.3-4ubuntu2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `dutinfopw201627`
--

-- --------------------------------------------------------

--
-- Structure de la table `achat_produit`
--

CREATE TABLE `achat_produit` (
  `id_produit` bigint UNSIGNED NOT NULL,
  `bar_associe` bigint UNSIGNED NOT NULL,
  `quantite` int UNSIGNED NOT NULL,
  `date_achat_produit` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `bar`
--

CREATE TABLE `bar` (
  `id_bar` bigint UNSIGNED NOT NULL,
  `nom` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `bilan_mensuel`
--

CREATE TABLE `bilan_mensuel` (
  `date_bilan_mensuel` date NOT NULL,
  `chiffre_affaire` double UNSIGNED NOT NULL,
  `depense` double UNSIGNED NOT NULL,
  `etat_stock_debut_mois` json NOT NULL,
  `etat_stock_fin_mois` json NOT NULL,
  `stock_consomme_mois` json NOT NULL,
  `bar_associe` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `bilan_quotidien`
--

CREATE TABLE `bilan_quotidien` (
  `date_bilan_quotidien` date NOT NULL,
  `chiffre_affaire` double UNSIGNED NOT NULL,
  `depense` double UNSIGNED NOT NULL,
  `etat_stock` json NOT NULL,
  `stock_consomme_du_jour` json NOT NULL,
  `bar_associe` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id_commande` bigint UNSIGNED NOT NULL,
  `date_commande` date NOT NULL,
  `login_utilisateur` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `bar_associe` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `fournisseur`
--

CREATE TABLE `fournisseur` (
  `id_fournisseur` bigint UNSIGNED NOT NULL,
  `nom` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `telephone` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `inventaire_manuel`
--

CREATE TABLE `inventaire_manuel` (
  `id_inventaire` bigint UNSIGNED NOT NULL,
  `date_inventaire` date NOT NULL,
  `bar_associe` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id_produit` bigint UNSIGNED NOT NULL,
  `nom_produit` varchar(30) NOT NULL,
  `prix_achat` double UNSIGNED NOT NULL,
  `prix_vente` double UNSIGNED NOT NULL,
  `fournisseur` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `produit_commande`
--

CREATE TABLE `produit_commande` (
  `id_commande` bigint UNSIGNED NOT NULL,
  `id_produit` bigint UNSIGNED NOT NULL,
  `quantite` int UNSIGNED NOT NULL,
  `prix` double UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `produit_inventaire`
--

CREATE TABLE `produit_inventaire` (
  `id_produit_inventaire` bigint UNSIGNED NOT NULL,
  `id_inventaire` bigint UNSIGNED NOT NULL,
  `quantite` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `login_utilisateur` varchar(30) NOT NULL,
  `bar_associe` bigint UNSIGNED NOT NULL,
  `role_bar` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `stock`
--

CREATE TABLE `stock` (
  `id_produit` bigint UNSIGNED NOT NULL,
  `bar_associe` bigint UNSIGNED NOT NULL,
  `quantite` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `login` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `solde` double UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `achat_produit`
--
ALTER TABLE `achat_produit`
  ADD PRIMARY KEY (`id_produit`,`bar_associe`,`date_achat_produit`),
  ADD KEY `fk_achat_produit_bar` (`bar_associe`);

--
-- Index pour la table `bar`
--
ALTER TABLE `bar`
  ADD PRIMARY KEY (`id_bar`),
  ADD UNIQUE KEY `id_bar` (`id_bar`);

--
-- Index pour la table `bilan_mensuel`
--
ALTER TABLE `bilan_mensuel`
  ADD PRIMARY KEY (`date_bilan_mensuel`),
  ADD KEY `fk_bilan_mensuel_bar` (`bar_associe`);

--
-- Index pour la table `bilan_quotidien`
--
ALTER TABLE `bilan_quotidien`
  ADD PRIMARY KEY (`date_bilan_quotidien`),
  ADD KEY `fk_bilan_quotidien_bar` (`bar_associe`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id_commande`),
  ADD UNIQUE KEY `id_commande` (`id_commande`),
  ADD KEY `fk_commande_bar` (`bar_associe`),
  ADD KEY `fk_commande_utilisateur` (`login_utilisateur`);

--
-- Index pour la table `fournisseur`
--
ALTER TABLE `fournisseur`
  ADD PRIMARY KEY (`id_fournisseur`),
  ADD UNIQUE KEY `id_fournisseur` (`id_fournisseur`);

--
-- Index pour la table `inventaire_manuel`
--
ALTER TABLE `inventaire_manuel`
  ADD PRIMARY KEY (`id_inventaire`),
  ADD UNIQUE KEY `id_inventaire` (`id_inventaire`),
  ADD KEY `fk_inventaire_manuel_bar` (`bar_associe`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id_produit`),
  ADD UNIQUE KEY `id_produit` (`id_produit`),
  ADD KEY `fk_produit_fournisseur` (`fournisseur`);

--
-- Index pour la table `produit_commande`
--
ALTER TABLE `produit_commande`
  ADD PRIMARY KEY (`id_commande`,`id_produit`),
  ADD KEY `fk_produit_commande_produit` (`id_produit`);

--
-- Index pour la table `produit_inventaire`
--
ALTER TABLE `produit_inventaire`
  ADD PRIMARY KEY (`id_produit_inventaire`,`id_inventaire`),
  ADD KEY `fk_produit_inventaire_inventaire_manuel` (`id_inventaire`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`login_utilisateur`,`bar_associe`),
  ADD KEY `fk_role_bar` (`bar_associe`);

--
-- Index pour la table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id_produit`,`bar_associe`),
  ADD KEY `fk_stock_bar` (`bar_associe`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`login`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `bar`
--
ALTER TABLE `bar`
  MODIFY `id_bar` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id_commande` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `fournisseur`
--
ALTER TABLE `fournisseur`
  MODIFY `id_fournisseur` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `inventaire_manuel`
--
ALTER TABLE `inventaire_manuel`
  MODIFY `id_inventaire` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `id_produit` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `achat_produit`
--
ALTER TABLE `achat_produit`
  ADD CONSTRAINT `fk_achat_produit_bar` FOREIGN KEY (`bar_associe`) REFERENCES `bar` (`id_bar`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_achat_produit_produit` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `bilan_mensuel`
--
ALTER TABLE `bilan_mensuel`
  ADD CONSTRAINT `fk_bilan_mensuel_bar` FOREIGN KEY (`bar_associe`) REFERENCES `bar` (`id_bar`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `bilan_quotidien`
--
ALTER TABLE `bilan_quotidien`
  ADD CONSTRAINT `fk_bilan_quotidien_bar` FOREIGN KEY (`bar_associe`) REFERENCES `bar` (`id_bar`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `fk_commande_bar` FOREIGN KEY (`bar_associe`) REFERENCES `bar` (`id_bar`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_commande_utilisateur` FOREIGN KEY (`login_utilisateur`) REFERENCES `utilisateur` (`login`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `inventaire_manuel`
--
ALTER TABLE `inventaire_manuel`
  ADD CONSTRAINT `fk_inventaire_manuel_bar` FOREIGN KEY (`bar_associe`) REFERENCES `bar` (`id_bar`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `fk_produit_fournisseur` FOREIGN KEY (`fournisseur`) REFERENCES `fournisseur` (`id_fournisseur`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `produit_commande`
--
ALTER TABLE `produit_commande`
  ADD CONSTRAINT `fk_produit_commande_commande` FOREIGN KEY (`id_commande`) REFERENCES `commande` (`id_commande`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_produit_commande_produit` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `produit_inventaire`
--
ALTER TABLE `produit_inventaire`
  ADD CONSTRAINT `fk_produit_inventaire_inventaire_manuel` FOREIGN KEY (`id_inventaire`) REFERENCES `inventaire_manuel` (`id_inventaire`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_produit_inventaire_produit` FOREIGN KEY (`id_produit_inventaire`) REFERENCES `produit` (`id_produit`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `role`
--
ALTER TABLE `role`
  ADD CONSTRAINT `fk_role_bar` FOREIGN KEY (`bar_associe`) REFERENCES `bar` (`id_bar`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_role_utilisateur` FOREIGN KEY (`login_utilisateur`) REFERENCES `utilisateur` (`login`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `fk_stock_bar` FOREIGN KEY (`bar_associe`) REFERENCES `bar` (`id_bar`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_stock_produit` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

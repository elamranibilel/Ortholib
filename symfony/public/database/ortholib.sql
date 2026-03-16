-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 09 mars 2025 à 14:30
-- Version du serveur : 8.0.31
-- Version de PHP : 8.2.0

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ortholib`
--
CREATE DATABASE IF NOT EXISTS `ortholib` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `ortholib`;

-- --------------------------------------------------------

--
-- Structure de la table `administrateur`
--

DROP TABLE IF EXISTS `administrateur`;
CREATE TABLE IF NOT EXISTS `administrateur` (
  `id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tronquer la table avant d'insérer `administrateur`
--

TRUNCATE TABLE `administrateur`;
--
-- Déchargement des données de la table `administrateur`
--

INSERT INTO `administrateur` VALUES
(1),
(2),
(3),
(4),
(5);

-- --------------------------------------------------------

--
-- Structure de la table `cabinet`
--

DROP TABLE IF EXISTS `cabinet`;
CREATE TABLE IF NOT EXISTS `cabinet` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tronquer la table avant d'insérer `cabinet`
--

TRUNCATE TABLE `cabinet`;
--
-- Déchargement des données de la table `cabinet`
--

INSERT INTO `cabinet` VALUES
(1, 'Duhamel', '4, boulevard de Laurent\n62801 Vallet', '+33 5 52 81 66 54'),
(2, 'Faivre', '280, rue Baudry\n32323 Jourdan', '05 30 57 62 79'),
(3, 'Pottier', '59, avenue Susanne Hoarau\n55075 Texierboeuf', '+33 (0)5 05 42 28 48'),
(4, 'Maillot', 'rue de Aubry\n42083 Blanchard-sur-Mer', '+33 9 51 89 64 19'),
(5, 'Bernier', '55, boulevard de Guyon\n03004 Joseph-la-Forêt', '+33 (0)3 23 53 62 50'),
(6, 'Moreau', '539, avenue Thierry Boyer\n52219 Charles', '+33 1 99 32 13 76'),
(7, 'Marie', 'chemin Constance Gay\n96446 Leclercq', '0188577965'),
(8, 'Dumont', '699, rue Tessier\n77483 Fernandez', '+33 (0)4 90 55 41 88'),
(9, 'Lemonnier', '8, avenue Zacharie Raymond\n56607 Ferrand', '0289794480'),
(10, 'Perrier', '93, boulevard de Jourdan\n09348 Humbert', '08 06 12 38 70');

-- --------------------------------------------------------

--
-- Structure de la table `commentaire`
--

DROP TABLE IF EXISTS `commentaire`;
CREATE TABLE IF NOT EXISTS `commentaire` (
  `id` int NOT NULL AUTO_INCREMENT,
  `auteur_id` int NOT NULL,
  `destinataire_id` int NOT NULL,
  `texte` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `role_auteur` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_destinataire` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_67F068BC60BB6FE6` (`auteur_id`),
  KEY `IDX_67F068BCA4F84F6E` (`destinataire_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tronquer la table avant d'insérer `commentaire`
--

TRUNCATE TABLE `commentaire`;
--
-- Déchargement des données de la table `commentaire`
--

INSERT INTO `commentaire` VALUES
(2, 15, 10, 'Minus inventore et accusantium tenetur excepturi quo.', '2024-10-23 22:26:29', '', ''),
(3, 15, 10, 'Sed voluptas earum libero quas distinctio.', '2024-12-13 07:27:53', '', ''),
(4, 10, 10, 'Praesentium maiores culpa unde sed velit quis et.', '2025-02-02 14:35:38', '', ''),
(5, 15, 10, 'Quod repellendus dolorum rerum nam optio.', '2025-02-11 13:25:50', '', ''),
(6, 5, 15, 'Sunt assumenda asperiores enim repudiandae at.', '2025-03-06 11:04:41', '', ''),
(7, 15, 10, 'Ut aut sint dolores reiciendis in tempore incidunt ut.', '2024-09-14 00:50:58', '', ''),
(8, 10, 10, 'Quasi ea perspiciatis minus mollitia.', '2024-12-23 16:20:16', '', ''),
(10, 15, 10, 'Génial ! ça marche!', '2025-03-07 10:13:43', NULL, NULL),
(11, 15, 10, 'salut legros', '2025-03-07 17:34:30', NULL, NULL),
(12, 10, 15, 'comment tu vas ?', '2025-03-08 10:13:44', NULL, NULL),
(13, 10, 15, 'sdfsdq', '2025-03-08 10:14:00', NULL, NULL),
(14, 15, 10, 'sdfqs', '2025-03-08 10:19:23', NULL, NULL),
(15, 15, 10, 'sdfqs', '2025-03-08 10:19:33', NULL, NULL),
(16, 15, 10, 'sdfqs', '2025-03-08 10:20:08', NULL, NULL),
(17, 15, 10, 'ça va tranquille.', '2025-03-08 10:51:32', NULL, NULL),
(18, 15, 10, 'ça va tranquille.', '2025-03-08 10:52:15', NULL, NULL),
(19, 15, 10, 'ça va tranquille', '2025-03-08 10:55:15', NULL, NULL),
(20, 10, 15, 'Super !', '2025-03-08 11:06:21', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Tronquer la table avant d'insérer `doctrine_migration_versions`
--

TRUNCATE TABLE `doctrine_migration_versions`;
--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` VALUES
('DoctrineMigrations\\Version20250121223707', '2025-03-07 08:43:33', 1295);

-- --------------------------------------------------------

--
-- Structure de la table `exercice`
--

DROP TABLE IF EXISTS `exercice`;
CREATE TABLE IF NOT EXISTS `exercice` (
  `id` int NOT NULL AUTO_INCREMENT,
  `patient_id` int NOT NULL,
  `orthophoniste_id` int NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `chronometre` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E418C74D6B899279` (`patient_id`),
  KEY `IDX_E418C74D78A4CD0F` (`orthophoniste_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tronquer la table avant d'insérer `exercice`
--

TRUNCATE TABLE `exercice`;
--
-- Déchargement des données de la table `exercice`
--

INSERT INTO `exercice` VALUES
(1, 15, 10, 'Mémoire', NULL),
(2, 15, 10, 'Orthographe', NULL),
(3, 15, 10, 'Mémoire', NULL),
(4, 15, 10, 'Vocabulaire', NULL),
(5, 15, 10, 'Mémoire', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `exercice_signification`
--

DROP TABLE IF EXISTS `exercice_signification`;
CREATE TABLE IF NOT EXISTS `exercice_signification` (
  `exercice_id` int NOT NULL,
  `signification_id` int NOT NULL,
  PRIMARY KEY (`exercice_id`,`signification_id`),
  KEY `IDX_9663C1C189D40298` (`exercice_id`),
  KEY `IDX_9663C1C14DC030D` (`signification_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tronquer la table avant d'insérer `exercice_signification`
--

TRUNCATE TABLE `exercice_signification`;
-- --------------------------------------------------------

--
-- Structure de la table `image`
--

DROP TABLE IF EXISTS `image`;
CREATE TABLE IF NOT EXISTS `image` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cabinet_id` int DEFAULT NULL,
  `orthophoniste_id` int DEFAULT NULL,
  `patient_id` int DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C53D045FD351EC` (`cabinet_id`),
  KEY `IDX_C53D045F78A4CD0F` (`orthophoniste_id`),
  KEY `IDX_C53D045F6B899279` (`patient_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tronquer la table avant d'insérer `image`
--

TRUNCATE TABLE `image`;
--
-- Déchargement des données de la table `image`
--

INSERT INTO `image` VALUES
(1, 6, NULL, NULL, 'audition.webp'),
(2, 7, NULL, NULL, 'cabinet1.png'),
(3, 8, NULL, NULL, 'cabinet1.png'),
(4, 9, NULL, NULL, 'audition.webp'),
(5, 10, NULL, NULL, 'audition.webp'),
(6, NULL, 6, NULL, 'cabinet1.png'),
(7, NULL, 7, NULL, 'audition.webp'),
(8, NULL, 8, NULL, 'audition.webp'),
(9, NULL, 9, NULL, 'cabinet1.png'),
(10, NULL, 10, NULL, 'cabinet1.png'),
(11, NULL, NULL, 11, 'audition.webp'),
(12, NULL, NULL, 12, 'audition.webp'),
(13, NULL, NULL, 13, 'cabinet1.png'),
(14, NULL, NULL, 14, 'audition.webp'),
(15, NULL, NULL, 15, 'cabinet1.png');

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tronquer la table avant d'insérer `messenger_messages`
--

TRUNCATE TABLE `messenger_messages`;
-- --------------------------------------------------------

--
-- Structure de la table `orthophoniste`
--

DROP TABLE IF EXISTS `orthophoniste`;
CREATE TABLE IF NOT EXISTS `orthophoniste` (
  `id` int NOT NULL,
  `cabinet_id` int NOT NULL,
  `nombre_heure_travail` int DEFAULT NULL,
  `specialisation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_4C2CDD16D351EC` (`cabinet_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tronquer la table avant d'insérer `orthophoniste`
--

TRUNCATE TABLE `orthophoniste`;
--
-- Déchargement des données de la table `orthophoniste`
--

INSERT INTO `orthophoniste` VALUES
(6, 10, 35, 'Troubles cognitifs et communication alternative'),
(7, 10, 35, 'Troubles cognitifs et communication alternative'),
(8, 10, 35, 'Troubles du langage écrit'),
(9, 10, 35, 'Troubles auditifs et surdité'),
(10, 10, 35, 'Troubles auditifs et surdité');

-- --------------------------------------------------------

--
-- Structure de la table `patient`
--

DROP TABLE IF EXISTS `patient`;
CREATE TABLE IF NOT EXISTS `patient` (
  `id` int NOT NULL,
  `choix_ortho_id` int DEFAULT NULL,
  `niveau_langue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `difficulte_test` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `niveau_apprentissage` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deficient` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `difficulte_rencontrees` json NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1ADAD7EBFD553130` (`choix_ortho_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tronquer la table avant d'insérer `patient`
--

TRUNCATE TABLE `patient`;
--
-- Déchargement des données de la table `patient`
--

INSERT INTO `patient` VALUES
(11, 10, 'Débutant', 'Lecture', 'A1', 'Auditif', '[\"Compréhension\", \"Prononciation\"]'),
(12, 10, 'Débutant', 'Lecture', 'A1', 'Auditif', '[\"Compréhension\", \"Prononciation\"]'),
(13, 10, 'Débutant', 'Lecture', 'A1', 'Auditif', '[\"Compréhension\", \"Prononciation\"]'),
(14, 10, 'Débutant', 'Lecture', 'A1', 'Auditif', '[\"Compréhension\", \"Prononciation\"]'),
(15, 10, 'Débutant', 'Lecture', 'A1', 'Auditif', '[\"Compréhension\", \"Prononciation\"]');

-- --------------------------------------------------------

--
-- Structure de la table `resultat_exercice`
--

DROP TABLE IF EXISTS `resultat_exercice`;
CREATE TABLE IF NOT EXISTS `resultat_exercice` (
  `id` int NOT NULL AUTO_INCREMENT,
  `exercice_id` int NOT NULL,
  `patient_id` int NOT NULL,
  `score` int NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B2BEB1D289D40298` (`exercice_id`),
  KEY `IDX_B2BEB1D26B899279` (`patient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tronquer la table avant d'insérer `resultat_exercice`
--

TRUNCATE TABLE `resultat_exercice`;
-- --------------------------------------------------------

--
-- Structure de la table `seances`
--

DROP TABLE IF EXISTS `seances`;
CREATE TABLE IF NOT EXISTS `seances` (
  `id` int NOT NULL AUTO_INCREMENT,
  `orthophoniste_id` int NOT NULL,
  `patient_id` int NOT NULL,
  `date_heure_debut` datetime NOT NULL,
  `date_heure_fin` datetime NOT NULL,
  `mode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duree` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `exercices` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_FC699FF178A4CD0F` (`orthophoniste_id`),
  KEY `IDX_FC699FF16B899279` (`patient_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tronquer la table avant d'insérer `seances`
--

TRUNCATE TABLE `seances`;
--
-- Déchargement des données de la table `seances`
--

INSERT INTO `seances` VALUES
(1, 10, 11, '2025-03-21 14:00:00', '2025-03-21 15:01:00', 'visioconférence', '00:00:00', 'sdfq');

-- --------------------------------------------------------

--
-- Structure de la table `signification`
--

DROP TABLE IF EXISTS `signification`;
CREATE TABLE IF NOT EXISTS `signification` (
  `id` int NOT NULL AUTO_INCREMENT,
  `mots` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `definition` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tronquer la table avant d'insérer `signification`
--

TRUNCATE TABLE `signification`;
--
-- Déchargement des données de la table `signification`
--

INSERT INTO `signification` VALUES
(1, 'commodi', 'Sed quia deleniti ut soluta in.'),
(2, 'ea', 'Sequi eum mollitia aliquam magnam repellendus veritatis rem.'),
(3, 'adipisci', 'Animi eum repellat amet repudiandae ex ut.'),
(4, 'laborum', 'Dolor at odio est corporis aspernatur.'),
(5, 'quia', 'Blanditiis voluptatem velit voluptatem iusto laudantium non a.'),
(6, 'aut', 'Dolorum iure natus quia aut.'),
(7, 'quo', 'Laboriosam nulla labore aut facere minima.');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `genre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ville` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tronquer la table avant d'insérer `users`
--

TRUNCATE TABLE `users`;
--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` VALUES
(1, 'admin0@example.com', '[\"ROLE_ADMIN\", \"ROLE_USER\"]', '$2y$13$wdmNID1njApC8yORL/X0G.6yyZyotX0Hnzi1OJg1nmCfTmWETLEly', 'Admin', 'Super', '05 16 05 61 31', '2025-03-07 08:52:02', 'Homme', 'Foucher-sur-Mer', 'administrateur'),
(2, 'admin1@example.com', '[\"ROLE_ADMIN\", \"ROLE_USER\"]', '$2y$13$C/wa43Ea8OJRJPBR9Egjn.bfQUeh/8Hwj68XeZ1Pf6TRsQLDZDtHC', 'Admin', 'Super', '+33 8 11 63 80 19', '2025-03-07 08:52:02', 'Homme', 'Le Gallboeuf', 'administrateur'),
(3, 'admin2@example.com', '[\"ROLE_ADMIN\", \"ROLE_USER\"]', '$2y$13$zNYRVslH8kk6tCNJCH4CK.1iGRv8Mj/a3K7cmvnl80JjaQD7lNNFm', 'Admin', 'Super', '0898718128', '2025-03-07 08:52:03', 'Homme', 'Vallee', 'administrateur'),
(4, 'admin3@example.com', '[\"ROLE_ADMIN\", \"ROLE_USER\"]', '$2y$13$31RkBGHutQ8O6WqVEEuHnuh4RWpRyFr5kV80oc5ta6484aKfKvY.y', 'Admin', 'Super', '0900250794', '2025-03-07 08:52:03', 'Homme', 'Morel-les-Bains', 'administrateur'),
(5, 'admin4@example.com', '[\"ROLE_ADMIN\", \"ROLE_USER\"]', '$2y$13$cc84YvvuL.gil36mO6MBJuIY5YDI9EvT5hpbQcED/hswAsdYiiPUe', 'Admin', 'Super', '0165025848', '2025-03-07 08:52:03', 'Homme', 'Henry-sur-Diaz', 'administrateur'),
(6, 'ortho0@example.com', '[\"ROLE_ORTHO\", \"ROLE_USER\"]', '$2y$13$9c/IrFG9atFpHWy2g4iAo.AIZl5xFIhsjNnw8x2xs5TDUO7T15d1u', 'Guillou', 'Arthur', '0278045442', '2025-03-07 08:52:04', 'Femme', 'Bonnet', 'orthophoniste'),
(7, 'ortho1@example.com', '[\"ROLE_ORTHO\", \"ROLE_USER\"]', '$2y$13$bq9WzL51w5kMaec3TZeo9O/1QPlEN3XWVWz7UqgnnDDwbsWupuqW.', 'Daniel', 'Anastasie', '0993440663', '2025-03-07 08:52:04', 'Homme', 'Ledoux', 'orthophoniste'),
(8, 'ortho2@example.com', '[\"ROLE_ORTHO\", \"ROLE_USER\"]', '$2y$13$R5vDuS9Epn0QVTTBD0125.jhm7lypyN/map6z.fxWZewbLWH5lNGS', 'Barbier', 'Claire', '0144242902', '2025-03-07 08:52:04', 'Homme', 'Perrin', 'orthophoniste'),
(9, 'ortho3@example.com', '[\"ROLE_ORTHO\", \"ROLE_USER\"]', '$2y$13$KwcoCyW5j.41FVi6YeQgTu/odgml493Ks2F8QJIA8mnFQwtaMIBoe', 'Grenier', 'Claude', '+33 (0)6 95 43 54 64', '2025-03-07 08:52:05', 'Femme', 'ParisVille', 'orthophoniste'),
(10, 'ortho4@example.com', '[\"ROLE_ORTHO\", \"ROLE_USER\"]', '$2y$13$E0H6I4iPY7yIXP/79heS8evi3NpfnioO.orAAkRN0PYx3Wq7apejG', 'Labbe', 'Yves', '01 09 92 64 16', '2025-03-07 08:52:05', 'Femme', 'MartinsBourg', 'orthophoniste'),
(11, 'patient0@example.com', '[\"ROLE_PATIENT\", \"ROLE_USER\"]', '$2y$13$uyehjZa7zGAdKy6dHmUwsOuP2DX.ckPUCJjwaXu.y6.34JbYID25y', 'Loiseau', 'Claude', '02 20 45 30 09', '2025-03-07 08:52:05', 'Homme', 'Riou', 'patient'),
(12, 'patient1@example.com', '[\"ROLE_PATIENT\", \"ROLE_USER\"]', '$2y$13$idKTopvdhHJDaCqOKV/zle.VgxufkJYtqAyTkF34qvzjFHCjI/hiS', 'Petitjean', 'Sylvie', '+33 9 15 52 03 64', '2025-03-07 08:52:06', 'Homme', 'Moulin', 'patient'),
(13, 'patient2@example.com', '[\"ROLE_PATIENT\", \"ROLE_USER\"]', '$2y$13$llja9yVyRcxyXbcjrFqYkurBdOYq12uYTQwOcsoLydZDe12YhpqVq', 'Delannoy', 'Christine', '0994161876', '2025-03-07 08:52:06', 'Femme', 'GiraudBourg', 'patient'),
(14, 'patient3@example.com', '[\"ROLE_PATIENT\", \"ROLE_USER\"]', '$2y$13$frWGx3rVmT3COFFwD.N18u6A4/0qnHQPj0pUfs0Ad6qYsiPOY7AiK', 'Le Goff', 'Nicolas', '+33 1 82 72 88 77', '2025-03-07 08:52:06', 'Homme', 'Pasquier', 'patient'),
(15, 'patient4@example.com', '[\"ROLE_PATIENT\", \"ROLE_USER\"]', '$2y$13$w4VHj4FAlZK8UjrZGUTzeu4trKfyH.iQoFIc.ILbL5Hgdl8AGe.pO', 'Legros', 'Bernard', '0528351383', '2025-03-07 08:52:07', 'Femme', 'Marion', 'patient');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `administrateur`
--
ALTER TABLE `administrateur`
  ADD CONSTRAINT `FK_32EB52E8BF396750` FOREIGN KEY (`id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD CONSTRAINT `FK_67F068BC60BB6FE6` FOREIGN KEY (`auteur_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `FK_67F068BCA4F84F6E` FOREIGN KEY (`destinataire_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `exercice`
--
ALTER TABLE `exercice`
  ADD CONSTRAINT `FK_E418C74D6B899279` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`),
  ADD CONSTRAINT `FK_E418C74D78A4CD0F` FOREIGN KEY (`orthophoniste_id`) REFERENCES `orthophoniste` (`id`);

--
-- Contraintes pour la table `exercice_signification`
--
ALTER TABLE `exercice_signification`
  ADD CONSTRAINT `FK_9663C1C14DC030D` FOREIGN KEY (`signification_id`) REFERENCES `signification` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_9663C1C189D40298` FOREIGN KEY (`exercice_id`) REFERENCES `exercice` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `FK_C53D045F6B899279` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`),
  ADD CONSTRAINT `FK_C53D045F78A4CD0F` FOREIGN KEY (`orthophoniste_id`) REFERENCES `orthophoniste` (`id`),
  ADD CONSTRAINT `FK_C53D045FD351EC` FOREIGN KEY (`cabinet_id`) REFERENCES `cabinet` (`id`);

--
-- Contraintes pour la table `orthophoniste`
--
ALTER TABLE `orthophoniste`
  ADD CONSTRAINT `FK_4C2CDD16BF396750` FOREIGN KEY (`id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_4C2CDD16D351EC` FOREIGN KEY (`cabinet_id`) REFERENCES `cabinet` (`id`);

--
-- Contraintes pour la table `patient`
--
ALTER TABLE `patient`
  ADD CONSTRAINT `FK_1ADAD7EBBF396750` FOREIGN KEY (`id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_1ADAD7EBFD553130` FOREIGN KEY (`choix_ortho_id`) REFERENCES `orthophoniste` (`id`);

--
-- Contraintes pour la table `resultat_exercice`
--
ALTER TABLE `resultat_exercice`
  ADD CONSTRAINT `FK_B2BEB1D26B899279` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`),
  ADD CONSTRAINT `FK_B2BEB1D289D40298` FOREIGN KEY (`exercice_id`) REFERENCES `exercice` (`id`);

--
-- Contraintes pour la table `seances`
--
ALTER TABLE `seances`
  ADD CONSTRAINT `FK_FC699FF16B899279` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`),
  ADD CONSTRAINT `FK_FC699FF178A4CD0F` FOREIGN KEY (`orthophoniste_id`) REFERENCES `orthophoniste` (`id`);
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

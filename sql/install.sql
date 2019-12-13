DROP DATABASE IF EXISTS SnowTricks;

CREATE DATABASE SnowTricks CHARACTER SET 'utf8';

USE SnowTricks;

--
-- Base de données :  `SnowTricks`
--

-- --------------------------------------------------------

--
-- Structure de la table `migration_versions`
--

CREATE TABLE `migration_versions` (
  `version` varchar(14) COLLATE utf8mb4_unicode_ci PRIMARY KEY,
  `executed_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migration_versions`
--

INSERT INTO `migration_versions` (`version`, `executed_at`) VALUES
('20191016205110', '2019-10-16 20:51:30'),
('20191016211646', '2019-10-16 21:17:08'),
('20191016221509', '2019-10-16 22:15:24'),
('20191017163444', '2019-10-17 16:34:58'),
('20191018135606', '2019-10-18 13:56:29'),
('20191025100151', '2019-10-25 10:02:21'),
('20191025101004', '2019-10-25 10:10:15'),
('20191025102114', '2019-10-25 10:21:32'),
('20191101125514', '2019-11-01 12:55:32'),
('20191108104922', '2019-11-08 10:49:32'),
('20191108110636', '2019-11-08 11:06:55'),
('20191120202457', '2019-11-20 20:25:14');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) AUTO_INCREMENT PRIMARY KEY,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci UNIQUE KEY NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `pseudo` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secure_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `trick_group`
--

CREATE TABLE `trick_group` (
  `id` int(11) AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `trick`
--

CREATE TABLE `trick` (
  `id` int(11) AUTO_INCREMENT PRIMARY KEY,
  `id_user_id` int(11) DEFAULT NULL,
  `id_trick_group_id` int(11) DEFAULT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `active` tinyint(1) NOT NULL,
  CONSTRAINT `FK_D8F0A91E79F37AE5` FOREIGN KEY (`id_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_D8F0A91EA6E1E0FE` FOREIGN KEY (`id_trick_group_id`) REFERENCES `trick_group` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

CREATE TABLE `image` (
  `id` int(11) AUTO_INCREMENT PRIMARY KEY,
  `id_trick_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `active` tinyint(1) NOT NULL,
  `main_img` tinyint(1) NOT NULL,
  CONSTRAINT `FK_C53D045FE25A52BB` FOREIGN KEY (`id_trick_id`) REFERENCES `trick` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `video`
--

CREATE TABLE `video` (
  `id` int(11) AUTO_INCREMENT PRIMARY KEY,
  `id_trick_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `active` tinyint(1) NOT NULL,
  `url` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  CONSTRAINT `FK_7CC7DA2CE25A52BB` FOREIGN KEY (`id_trick_id`) REFERENCES `trick` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) AUTO_INCREMENT PRIMARY KEY,
  `id_user_id` int(11) DEFAULT NULL,
  `id_trick_id` int(11) DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  CONSTRAINT `FK_9474526C79F37AE5` FOREIGN KEY (`id_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_9474526CE25A52BB` FOREIGN KEY (`id_trick_id`) REFERENCES `trick` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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

--
-- Déchargement des données de la table `trick_group`
--

INSERT INTO `trick_group` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Les grabs', 'Un grab consiste à attraper la planche avec la main pendant le saut. Le verbe anglais to grab signifie « attraper. »', '2019-12-19 10:58:19', '2019-12-19 10:58:19'),
(2, 'Les rotations', 'On désigne par le mot « rotation » uniquement des rotations horizontales', '2019-12-19 10:58:47', '2019-12-19 10:58:47'),
(3, 'Les flips', 'Un flip est une rotation verticale. On distingue les front flips, rotations en avant, et les back flips, rotations en arrière.', '2019-12-19 10:59:09', '2019-12-19 10:59:09'),
(4, 'Les slides', 'Un slide consiste à glisser sur une barre de slide.', '2019-12-19 10:59:25', '2019-12-19 10:59:25');

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

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `created_at`, `pseudo`, `secure_key`) VALUES
(1, 'tabetdamien@free.fr', '[\"ROLE_ADMIN\"]', '$2y$13$Jd9jcdY0qcm.mj5nUHAlcuZbJbOEDX05ssqnLDnL63g81SQzi3VFq', '2019-12-19 10:55:48', 'Dams', '2098d988f51f6ec0d7b2418b9f010477'),
(2, 'test@test.fr', '[\"ROLE_USER\"]', '$2y$13$I9fgKT99MdtVHwCwUYNnB.hVSgzUbPfktga3rRL.VtmaoU6dzDoAu', '2019-12-19 13:29:21', 'Utilisateur test', '82b0f857c5b8db36ad5032c10fe7b6eb');

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

--
-- Déchargement des données de la table `video`
--

INSERT INTO `video` (`id`, `id_trick_id`, `name`, `created_at`, `active`, `url`, `type`) VALUES
(1, 1, 'La liste des grabs', '2019-12-19 11:09:12', 1, 'https://www.youtube.com/embed/CA5bURVJ5zk', '1'),
(2, 1, 'La session', '2019-12-19 11:10:13', 1, 'https://www.youtube.com/embed/Opg5g4zsiGY', '1'),
(3, 2, 'Comment faire un Indy ?', '2019-12-19 11:15:35', 1, 'https://www.youtube.com/embed/iKkhKekZNQ8', '1'),
(4, 2, 'Les grabs', '2019-12-19 11:16:13', 1, 'https://www.youtube.com/embed/CA5bURVJ5zk', '1'),
(5, 3, 'Comment le faire ?', '2019-12-19 11:20:37', 1, 'https://www.youtube.com/embed/JMS2PGAFMcE', '1'),
(6, 3, 'Les différentes rotations', '2019-12-19 11:21:16', 1, 'https://www.youtube.com/embed/6gFsbU3GWF0', '1'),
(7, 4, 'Le 1080°', '2019-12-19 11:29:51', 1, 'https://www.youtube.com/embed/KWWIwmkqrDA', '1'),
(8, 5, 'Frontflip', '2019-12-19 11:34:06', 1, 'https://www.youtube.com/embed/xhvqu2XBvI0', '1'),
(9, 5, 'Backflip', '2019-12-19 11:35:15', 1, 'https://www.youtube.com/embed/arzLq-47QFA', '1'),
(10, 6, 'Slides', '2019-12-19 13:21:50', 1, 'https://www.youtube.com/embed/R3OG9rNDIcs', '1'),
(11, 6, 'Box slide', '2019-12-19 13:23:03', 1, 'https://www.youtube.com/embed/WOgw5uBSLp0', '1');

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

--
-- Déchargement des données de la table `comment`
--

INSERT INTO `comment` (`id`, `id_user_id`, `id_trick_id`, `content`, `created_at`) VALUES
(1, 1, 4, 'La figure la plus compliqué à mon avis !', '2019-12-19 13:28:10'),
(2, 2, 1, 'Cette figure n\'est pas facile, mais j\'adore la pratiquer !', '2019-12-19 13:30:16'),
(3, 2, 6, 'En réussissant ce genre de figure, nous ne sommes plus un débutant :-)', '2019-12-19 13:31:40'),
(4, 1, 4, 'Je suis tout à fais d\'accord avec vous. Un tour, c\'est facile ; ensuite, c\'est une autre histoire ^^', '2019-12-19 13:32:53');
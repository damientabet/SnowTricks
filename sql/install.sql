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

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `created_at`, `pseudo`, `secure_key`) VALUES
(1, 'tabetdamien@free.fr', '[\"ROLE_ADMIN\"]', '$2y$13$Jd9jcdY0qcm.mj5nUHAlcuZbJbOEDX05ssqnLDnL63g81SQzi3VFq', '2019-12-19 10:55:48', 'Dams', '2098d988f51f6ec0d7b2418b9f010477'),
(2, 'test@test.fr', '[\"ROLE_USER\"]', '$2y$13$I9fgKT99MdtVHwCwUYNnB.hVSgzUbPfktga3rRL.VtmaoU6dzDoAu', '2019-12-19 13:29:21', 'Utilisateur test', '82b0f857c5b8db36ad5032c10fe7b6eb');

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

--
-- Déchargement des données de la table `trick`
--

INSERT INTO `trick` (`id`, `id_user_id`, `id_trick_group_id`, `name`, `description`, `created_at`, `updated_at`, `active`) VALUES
(1, 1, 1, 'Mute', 'Il s\'agit de la saisie de la carre frontside de la planche entre les deux pieds avec la main avant.', '2019-12-19 11:05:15', '2019-12-19 11:05:15', 1),
(2, 1, 1, 'Indy', 'Pour cela, il faut saisir la carre frontside de la planche, entre les deux pieds, avec la main arrière', '2019-12-19 11:12:43', '2019-12-19 11:12:43', 1),
(3, 1, 2, '180°', 'On désigne par le mot « rotation » uniquement des rotations horizontales ; les rotations verticales sont des flips. Le principe est d\'effectuer une rotation horizontale pendant le saut, puis d\'attérir en position switch ou normal. La nomenclature se base sur le nombre de degrés de rotation effectués : un 180 désigne un demi-tour, soit 180 degrés d\'angle', '2019-12-19 11:18:08', '2019-12-19 11:18:08', 1),
(4, 1, 2, '1080°', 'On désigne par le mot « rotation » uniquement des rotations horizontales ; les rotations verticales sont des flips. Le principe est d\'effectuer une rotation horizontale pendant le saut, puis d\'attérir en position switch ou normal. La nomenclature se base sur le nombre de degrés de rotation effectués : 1080 ou big foot pour trois tours.\r\nUne rotation peut être agrémentée d\'un grab, ce qui rend le saut plus esthétique mais aussi plus difficile car la position tweakée a tendance à déséquilibrer le rideur et désaxer la rotation. De plus, le sens de la rotation a tendance à favoriser un sens de grab plutôt qu\'un autre. Les rotations de plus de trois tours existent mais sont plus rares, d\'abord parce que les modules assez gros pour lancer un tel saut sont rares, et ensuite parce que la vitesse de rotation est tellement élevée qu\'un grab devient difficile, ce qui rend le saut considérablement moins esthétique.', '2019-12-19 11:24:44', '2019-12-19 11:24:44', 1),
(5, 1, 3, 'Les flips', 'Un flip est une rotation verticale. On distingue les front flips, rotations en avant, et les back flips, rotations en arrière.\r\n\r\nIl est possible de faire plusieurs flips à la suite, et d\'ajouter un grab à la rotation.\r\n\r\nLes flips agrémentés d\'une vrille existent aussi (Mac Twist, Hakon Flip, ...), mais de manière beaucoup plus rare, et se confondent souvent avec certaines rotations horizontales désaxées.\r\n\r\nNéanmoins, en dépit de la difficulté technique relative d\'une telle figure, le danger de retomber sur la tête ou la nuque est réel et conduit certaines stations de ski à interdire de telles figures dans ses snowparks.', '2019-12-19 11:32:11', '2019-12-19 11:32:11', 1),
(6, 1, 4, 'Les slides', 'Un slide consiste à glisser sur une barre de slide. Le slide se fait soit avec la planche dans l\'axe de la barre, soit perpendiculaire, soit plus ou moins désaxé.\r\n\r\nOn peut slider avec la planche centrée par rapport à la barre (celle-ci se situe approximativement au-dessous des pieds du rideur), mais aussi en nose slide, c\'est-à-dire l\'avant de la planche sur la barre, ou en tail slide, l\'arrière de la planche sur la barre.', '2019-12-19 13:04:48', '2019-12-19 13:04:48', 1);


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
-- Déchargement des données de la table `image`
--

INSERT INTO `image` (`id`, `id_trick_id`, `name`, `created_at`, `active`, `main_img`) VALUES
(1, 1, 'ae03876211c17b516626b9ef4dcd2a9a-5dfb597c1b7ac.jpeg', '2019-12-19 11:05:32', 1, 0),
(2, 1, 'f303515a31713bdee50a370dc3b3a1cb-5dfb5982c62e9.jpeg', '2019-12-19 11:05:38', 1, 0),
(3, 1, 'mute-grab-5dfb59888852e.jpeg', '2019-12-19 11:05:44', 1, 0),
(4, 1, 'DSC00659-5dfb5a01475ca.jpeg', '2019-12-19 11:07:45', 1, 1),
(5, 2, 'weway98epgp01-5dfb5b858c459.jpeg', '2019-12-19 11:14:13', 1, 1),
(6, 2, 'snowboarder-indy-grab-chamonix-france-pierre-leclerc-photography-5dfb5b8f8aa5b.jpeg', '2019-12-19 11:14:23', 1, 0),
(7, 2, 'Alli+Dew+Tour+Day+2+5nHcOvskH2-l-5dfb5ba126641.jpeg', '2019-12-19 11:14:41', 1, 0),
(8, 3, 'how-to-snowboard-8211-180-8217-s-w-dan-brisse-5dfb5cc8db398.jpeg', '2019-12-19 11:19:36', 1, 1),
(9, 3, 'maxresdefault-5dfb5cd05e933.jpeg', '2019-12-19 11:19:44', 1, 0),
(10, 3, 'FS180-620x413-5dfb5cd94512f.jpeg', '2019-12-19 11:19:53', 1, 0),
(11, 4, 'FS180-620x413-5dfb5e092bed3.jpeg', '2019-12-19 11:24:57', 1, 0),
(12, 4, '1*kF6y0uh6R5F4jQWeTpdKDw-5dfb5e8a237aa.jpeg', '2019-12-19 11:27:06', 1, 0),
(13, 4, '1702282738-5dfb5e936d041.jpeg', '2019-12-19 11:27:15', 1, 0),
(14, 4, 'beea83908492b8d2dd7a675f9b946ef7-5dfb5eca5b315.jpeg', '2019-12-19 11:28:10', 1, 1),
(15, 5, '1-udpvcpu7sqlvxgy15zgxrq-5dfb5fc7ad3e5.jpeg', '2019-12-19 11:32:23', 1, 1),
(16, 5, '151116-veste-homme-5dfb5fd026cac.jpeg', '2019-12-19 11:32:32', 1, 0),
(17, 5, 'C_900x900-5dfb5fdccaea6.jpeg', '2019-12-19 11:32:44', 1, 0),
(18, 6, 'sebastien-toutant-slide-red-bull-uncorked-snowboard-5dfb76e49033a.jpeg', '2019-12-19 13:11:00', 1, 0),
(19, 6, 'SNC_Snowboard_1-5dfb76ef96e51.jpeg', '2019-12-19 13:11:11', 1, 0),
(20, 6, 'd3c16beb7c4e8308120c03e2f1fb4812-5dfb76f79b96d.jpeg', '2019-12-19 13:11:19', 1, 0),
(21, 6, '1*QDE76DCnVHn1i84l5Ml4GA-5dfb79f7a4065.jpeg', '2019-12-19 13:24:07', 1, 1);

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
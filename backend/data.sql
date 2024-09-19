-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : jeu. 19 sep. 2024 à 14:31
-- Version du serveur : 8.0.30
-- Version de PHP : 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `twitter`
--

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE `comments` (
  `id` int NOT NULL,
  `author_id` int NOT NULL,
  `publication_id` int NOT NULL,
  `content` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`id`, `author_id`, `publication_id`, `content`, `created_at`) VALUES
(1, 1, 4, 'J\'aime pas trop l\'espace, tout ça. Mais par contre j\'aime bien les Renault Espace', '2024-09-18 12:29:42'),
(6, 1, 35, 'espace !!!!!!!!\n', '2024-09-19 07:14:22');

-- --------------------------------------------------------

--
-- Structure de la table `hashtags`
--

CREATE TABLE `hashtags` (
  `id` int NOT NULL,
  `tag` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `hashtags`
--

INSERT INTO `hashtags` (`id`, `tag`) VALUES
(6, 'alol'),
(4, 'bonjour'),
(8, 'brrrr'),
(9, 'cacaille'),
(5, 'cava'),
(1, 'developpement'),
(3, 'helloworld'),
(7, 'sauron'),
(2, 'serie');

-- --------------------------------------------------------

--
-- Structure de la table `publications`
--

CREATE TABLE `publications` (
  `id` int NOT NULL,
  `author_id` int NOT NULL,
  `content` varchar(255) NOT NULL,
  `image_id` int DEFAULT NULL,
  `publishdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `publications`
--

INSERT INTO `publications` (`id`, `author_id`, `content`, `image_id`, `publishdate`) VALUES
(2, 1, 'Je suis une publication dans la bdd', 1, '2024-09-18 09:32:40'),
(3, 2, 'Salut, je suis nouveau sur Wishtter', NULL, '2024-09-18 09:32:40'),
(4, 2, 'J\'aime bien l\'espace !!!!!', 2, '2024-09-18 09:32:40'),
(14, 2, 'j\'ai publié !!!', NULL, '2024-09-18 09:35:42'),
(18, 1, 'bonjour a tous\n', NULL, '2024-09-18 12:02:57'),
(33, 1, 'Coucou', 3, '2024-09-19 07:13:28'),
(35, 1, 'Fond écran des familles', 4, '2024-09-19 07:13:56'),
(38, 1, 'Moi quand j\'arrive sur Wishtter\r\n#sauron #helloworld #bonjour', 6, '2024-09-19 11:19:24'),
(39, 12, 'il fait froid dehors \n#cacaille #brrrr', 7, '2024-09-19 11:25:12');

-- --------------------------------------------------------

--
-- Structure de la table `publication_hashtags`
--

CREATE TABLE `publication_hashtags` (
  `publication_id` int NOT NULL,
  `hashtag_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `publication_hashtags`
--

INSERT INTO `publication_hashtags` (`publication_id`, `hashtag_id`) VALUES
(38, 3),
(38, 4),
(38, 7),
(39, 8),
(39, 9);

-- --------------------------------------------------------

--
-- Structure de la table `publication_image`
--

CREATE TABLE `publication_image` (
  `id` int NOT NULL,
  `publication_id` int NOT NULL,
  `image` varchar(255) NOT NULL,
  `uploader_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `publication_image`
--

INSERT INTO `publication_image` (`id`, `publication_id`, `image`, `uploader_id`) VALUES
(1, 2, 'https://wallpaper.forfun.com/fetch/87/87c93aa33275b4c8c73637ad3fbee836.jpeg', 1),
(2, 4, 'https://www.polytechnique-insights.com/wp-content/uploads/2022/11/space-1024x640.jpeg', 2),
(3, 33, 'http://localhost:5173/public/upload/66ebcf18d56cd.jpeg', 1),
(4, 35, 'http://localhost:5173/public/upload/66ebcf34dac10.jpg', 1),
(6, 38, 'http://localhost:5173/public/upload/66ec08bc7c48c.gif', 1),
(7, 39, 'http://localhost:5173/public/upload/66ec0a1814d91.gif', 12);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(60) NOT NULL,
  `pictures` varchar(255) NOT NULL DEFAULT 'https://i.pravatar.cc/300',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `pictures`, `created_at`) VALUES
(1, 'admin', 'admin@admin', '$2y$10$EGWjq0KPjRMbbNMilS5wEuPI65HiSXeIBQz4Wbuo1GP1I177nlKrS', 'https://attakus.art/wp-content/uploads/2022/10/dark-vador-darth-vader-article-attakus-art.jpg', '2024-09-18 06:25:09'),
(2, 'Kevin', 'kev@in.fr', '$2y$10$Qt8vRE1gwzyIEHWbjz.4ne4br5PYTAf4DgvM.Y5q3Tpg2SZ6XeoYW', 'https://content.imageresizer.com/images/memes/Venom-slam-dunk-meme-4.jpg', '2024-09-18 06:25:09'),
(3, 'test', 'test@test.fr', '$2y$10$x8sY4KQYQi9jmryM3EnV2edrmiP4Do.aS6mlY6uiOhFv93T0z0hRW', 'https://i.pravatar.cc/300', '2024-09-18 06:25:20'),
(4, 'ddd', 'ddd@dd', '$2y$10$wFVhbbmH1bw8qz/ZQfHuf.IYCkyixK49sW2yVuhZv/n5ECYEcHrHe', 'https://i.pravatar.cc/300', '2024-09-18 06:44:15'),
(5, 'ddd', 'dd@dd.fr', '$2y$10$TCy0WscP8imkiOiBkb4H2OBIxLG0ejOuj/.BRxU5V6l76/LgoMhpC', 'https://i.pravatar.cc/300', '2024-09-18 06:45:07'),
(6, 'ddd', 'ddd@ddd.fr', '$2y$10$N5C4Mary0jorh5qGic8Wr.0mN.QbVDubvUbpNkj3/u/i3.vFGkMOq', 'https://i.pravatar.cc/300', '2024-09-18 06:45:34'),
(7, 'tested', 'ddd@dd', '$2y$10$lgU6IP9HvvZjz34eKMQjTeR4eW.VYo0kFrKJM2CiSA3nrbGyp1L3e', 'https://i.pravatar.cc/300', '2024-09-18 06:46:28'),
(8, 'tested', 'tested@test.fr', '$2y$10$XoqkwXIQh3ECdH7REyNQ7.84dkirq.sdoH2yBmiHNu5jbSrxi8dla', 'https://i.pravatar.cc/300', '2024-09-18 06:47:38'),
(9, 'tested', 'tested@dd.fr', '$2y$10$jDd3U0UualftYVIKoDatle5VEbYRHWEiLNj0Tzq5biDrHmB.u1rVy', 'https://i.pravatar.cc/300', '2024-09-18 06:48:17'),
(10, 'dada', 'dada@dd.fr', '$2y$10$hNI1zSl6P8AgtYciO3Cgce/wfd8GkTEUsNyhkd8oUhZjr1ukwUf56', 'https://i.pravatar.cc/300', '2024-09-18 06:49:04'),
(11, 'tested', 'tested@test.fr', '$2y$10$kDq/ork4QEiOpyTFTJeGLeQk.DO68Oy5RzAvGDUX2F5MmOHOufJbK', 'https://i.pravatar.cc/300', '2024-09-18 06:50:38'),
(12, 'JenClodVendam', 'jen@clodvandam', '$2y$10$vFFt3MGcITBlwdJjOEUQJuzza58oy4S0bgK76pGhnCbJ7lmi8sVWy', 'https://i.pravatar.cc/300', '2024-09-19 11:23:14');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `publication_id` (`publication_id`);

--
-- Index pour la table `hashtags`
--
ALTER TABLE `hashtags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tag` (`tag`);

--
-- Index pour la table `publications`
--
ALTER TABLE `publications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`);

--
-- Index pour la table `publication_hashtags`
--
ALTER TABLE `publication_hashtags`
  ADD PRIMARY KEY (`publication_id`,`hashtag_id`),
  ADD KEY `hashtag_id` (`hashtag_id`);

--
-- Index pour la table `publication_image`
--
ALTER TABLE `publication_image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uploader_id` (`uploader_id`),
  ADD KEY `publication_id` (`publication_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `hashtags`
--
ALTER TABLE `hashtags`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `publications`
--
ALTER TABLE `publications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT pour la table `publication_image`
--
ALTER TABLE `publication_image`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`publication_id`) REFERENCES `publications` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `publications`
--
ALTER TABLE `publications`
  ADD CONSTRAINT `publications_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `publication_hashtags`
--
ALTER TABLE `publication_hashtags`
  ADD CONSTRAINT `publication_hashtags_ibfk_1` FOREIGN KEY (`publication_id`) REFERENCES `publications` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `publication_hashtags_ibfk_2` FOREIGN KEY (`hashtag_id`) REFERENCES `hashtags` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `publication_image`
--
ALTER TABLE `publication_image`
  ADD CONSTRAINT `publication_image_ibfk_1` FOREIGN KEY (`uploader_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `publication_image_ibfk_2` FOREIGN KEY (`publication_id`) REFERENCES `publications` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

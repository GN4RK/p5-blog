-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 21 juin 2022 à 14:03
-- Version du serveur : 10.4.10-MariaDB
-- Version de PHP : 8.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `blog`
--

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `content` text NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_post` int(11) NOT NULL,
  `status` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `comment`
--

INSERT INTO `comment` (`id`, `date`, `content`, `id_user`, `id_post`, `status`) VALUES
(1, '2022-06-01 22:57:10', 'test', 1, 4, 'public'),
(2, '2022-06-01 23:42:18', 'Proin leo leo, iaculis a imperdiet ac, convallis et erat. Aenean rutrum pretium urna nec tristique. Nam tempus imperdiet quam ac ultrices. Duis sollicitudin eget augue non mollis. Fusce rhoncus tellus velit, quis tincidunt mi volutpat quis. Integer dapibus convallis efficitur. Nunc nec ultricies quam. Interdum et malesuada fames ac ante ipsum primis in faucibus. ', 1, 4, 'public'),
(3, '2022-06-02 00:18:59', '123', 1, 4, 'public'),
(4, '2022-06-02 00:21:07', 'testset', 1, 4, 'public'),
(5, '2022-06-02 15:28:04', 'test status commentaire', 1, 4, 'public'),
(6, '2022-06-02 15:31:25', 'testsetsetset', 1, 4, 'public'),
(7, '2022-06-02 15:45:11', 'pending', 1, 4, 'public'),
(8, '2022-06-03 14:06:40', 'test', 15, 1, 'public'),
(9, '2022-06-03 14:15:50', 'commentaire', 1, 1, 'public'),
(10, '2022-06-03 17:20:24', 'nouveau com', 1, 4, 'public'),
(11, '2022-06-06 16:07:44', 'test int ', 1, 6, 'pending'),
(12, '2022-06-06 16:08:50', '123', 1, 6, 'pending'),
(13, '2022-06-06 16:21:58', 'test', 1, 6, 'pending'),
(14, '2022-06-07 16:26:20', 'test', 1, 2, 'public'),
(15, '2022-06-07 16:26:54', 'new com non admin', 15, 4, 'public'),
(16, '2022-06-07 16:27:48', 'test2', 15, 4, 'public'),
(17, '2022-06-09 15:36:00', 'test', 1, 10, 'public'),
(18, '2022-06-13 15:54:33', 'test', 1, 13, 'public'),
(19, '2022-06-13 16:26:59', '123', 1, 13, 'public'),
(20, '2022-06-16 15:47:26', 'test', 1, 19, 'public'),
(21, '2022-06-16 17:17:03', '<>', 1, 20, 'pending'),
(22, '2022-06-16 17:30:24', '&lt;&lt;&lt;', 1, 20, 'pending'),
(23, '2022-06-16 17:30:36', '&quot;&quot;&#039;', 1, 20, 'pending');

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

DROP TABLE IF EXISTS `post`;
CREATE TABLE IF NOT EXISTS `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `title` text NOT NULL,
  `header` text NOT NULL,
  `content` text NOT NULL,
  `publication_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_update` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `post`
--

INSERT INTO `post` (`id`, `id_user`, `title`, `header`, `content`, `publication_date`, `last_update`, `status`) VALUES
(2, 1, 'Un article de blog', ' Lorem ipsum dolor sit amet, consectetur adipiscin.', ' Lorem ipsum dolor sit amet, consectetur adipiscing elit. In iaculis enim eu felis tempus euismod. Vivamus varius purus ut augue dictum, sit amet finibus velit dignissim. Suspendisse nec interdum turpis. Cras eu libero sed arcu sollicitudin condimentum eu vitae est. Integer in nisi a ex venenatis luctus at nec justo. Maecenas sagittis leo diam, eu efficitur tortor vehicula egestas. Sed id varius nunc. Aenean ultricies ante urna. Fusce accumsan fringilla quam. Duis tempus mauris nisl, eget mattis tortor vehicula id. Integer urna elit, mollis quis feugiat quis, condimentum in libero. Etiam lacinia lacus erat, eu ultricies nisl vestibulum vel. ', '2022-06-06 16:24:30', '2022-06-08 15:20:37', 'public'),
(11, 1, 'article namespace test', 'namespace', ' Ut lobortis posuere tellus, a varius enim maximus viverra. Donec at ex nec nisl viverra sagittis. Vivamus elementum risus ac purus dictum, at malesuada quam vehicula. Duis orci risus, elementum id convallis ut, malesuada sed urna. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Proin ullamcorper facilisis erat, in venenatis risus pretium nec. Etiam molestie viverra tincidunt. Ut at leo a nibh consequat lacinia. Duis efficitur dictum eros, sit amet cursus est dapibus vel. Nullam quis ex libero. Vestibulum id dui vestibulum mauris rutrum luctus. Nulla lobortis ipsum in augue vulputate hendrerit. Nunc ac mi at neque venenatis porttitor. Praesent fringilla tristique elementum. Maecenas id quam id enim maximus lacinia. Donec maximus eros lacus, egestas hendrerit magna egestas ac.\r\n\r\nPraesent ultricies sagittis erat, sed tincidunt eros fermentum at. Mauris ut placerat tellus. Nullam porttitor in nunc ac condimentum. Aenean egestas augue elit, vitae malesuada ex condimentum id. Proin in sagittis mi. Maecenas lacinia sit amet lorem vitae iaculis. Donec erat sem, vulputate pretium eros non, consequat mattis nisl. Suspendisse potenti. Donec sed felis euismod, accumsan eros ut, convallis velit. Morbi ac porta eros, vel porta justo. Quisque laoreet at lorem nec vehicula. Nullam dolor orci, pellentesque nec neque vel, faucibus maximus velit. Donec non neque in nunc tincidunt vestibulum. Etiam enim enim, tempor id enim et, lacinia viverra libero. Maecenas fringilla elit ac augue ultricies, at rutrum nunc rhoncus. Suspendisse non nunc non ante rhoncus ornare. ', '2022-06-09 15:58:27', '2022-06-09 15:58:39', 'public'),
(13, 1, 'test2', '22.', '22..', '2022-06-10 00:38:00', '2022-06-15 16:03:37', 'public'),
(15, 1, 'un titre très long lorem ipsum dolor sit amet qzfh qzpefh hzifh qziufheh fzih', 'test', 'lml', '2022-06-14 16:42:35', '2022-06-14 16:42:49', 'public'),
(3, 1, 'test', 'contenu', 'contenu', '2022-06-06 16:24:30', '2022-06-01 21:18:36', 'public'),
(4, 1, 'Titre du billet.', 'Donec a sollicitudin ante. Proin leo leo, iaculis a imperdiet ac, convallis et erat. Aenean rutrum pretium urna nec tristique. Nam tempus imperdiet quam ac ultrices. Duis sollicitudin eget augue non mollis.', 'Integer sit amet ullamcorper dolor, ac gravida ipsum. Suspendisse iaculis, quam id venenatis tempor, nisi massa aliquet massa, in maximus odio lectus id nunc. In felis dui, ullamcorper vitae finibus vitae, tempus eget urna. Donec a sollicitudin ante. Proin leo leo, iaculis a imperdiet ac, convallis et erat. Aenean rutrum pretium urna nec tristique. Nam tempus imperdiet quam ac ultrices. Duis sollicitudin eget augue non mollis. Fusce rhoncus tellus velit, quis tincidunt mi volutpat quis. Integer dapibus convallis efficitur. Nunc nec ultricies quam. Interdum et malesuada fames ac ante ipsum primis in faucibus. Nam ac enim varius, rhoncus dolor sed, lobortis augue. Suspendisse potenti. ', '2022-06-06 16:24:30', '2022-06-20 15:54:47', 'pending'),
(10, 1, 'article du jeudi.', 'test chapo', 'lorem ipsum dolor sit amet', '2022-06-09 13:23:21', '2022-06-09 15:37:08', 'public'),
(16, 1, 'Billet de démo', 'chapo demo', 'contenu test  Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ac consectetur metus. Fusce eu quam quis ipsum fringilla malesuada sed non libero. Cras iaculis efficitur sapien sit amet commodo. Nullam cursus dolor sed est varius tempor. Vivamus semper viverra libero, a semper ex volutpat ut. Donec eget blandit ante. Vivamus efficitur quam sit amet molestie commodo.\r\n\r\nFusce venenatis felis id libero convallis, nec iaculis purus scelerisque. Nunc lobortis purus nisl, eget luctus enim malesuada vitae. Nam ut ullamcorper massa. Donec interdum eleifend mi, eget lobortis felis feugiat eu. Nullam sed augue interdum, ornare arcu vel, tincidunt lacus. Cras molestie enim eu turpis volutpat, quis vestibulum ex lacinia. Praesent congue, ex ut rutrum sollicitudin, dolor ante consequat purus, sed laoreet ante sapien sit amet libero. Nunc facilisis, mi eget imperdiet consequat, nibh elit pellentesque tellus, vitae ultricies velit nunc eu risus. In ac tempus metus, sit amet luctus nulla. Donec ornare porta tortor, ac mollis libero malesuada ut. Integer non vestibulum neque. ', '2022-06-15 16:02:50', '2022-06-15 16:02:50', 'public'),
(17, 1, 'demo', 'demo', ' Duis velit nulla, mollis at aliquam sit amet, facilisis a justo. Praesent a mi quis metus lacinia tristique vitae quis quam. Aliquam eget iaculis ligula. Nullam luctus quam lacus, quis suscipit nulla venenatis ut. Ut elementum risus nulla, ut bibendum urna ornare a. Maecenas gravida nisi a convallis hendrerit. Maecenas non pharetra purus. Proin sagittis sollicitudin elit eget molestie.\r\n\r\nUt quis tincidunt dui. Nulla vehicula accumsan quam vitae placerat. Sed ac velit eget enim facilisis malesuada. Mauris vitae tortor ornare, convallis nulla tristique, aliquet tortor. In hac habitasse platea dictumst. Vestibulum vel maximus elit. Nulla facilisi. Proin pharetra quam et nisi convallis efficitur. Vivamus id convallis felis. Cras condimentum finibus molestie. Sed ultrices sodales quam. Interdum et malesuada fames ac ante ipsum primis in faucibus. ', '2022-06-15 16:03:22', '2022-06-15 16:03:22', 'public'),
(18, 1, 'un article sur pusheen', 'pusheen le chat', 'Maecenas est justo, gravida vel tincidunt eget, vulputate nec magna. Nulla ac vestibulum ligula. Donec congue lectus sed aliquet consequat. Maecenas ac nulla eget massa malesuada efficitur. Phasellus nulla ligula, sollicitudin ac mauris eu, tincidunt dictum orci. Nulla aliquam vestibulum libero nec consequat. Donec blandit, metus at rutrum lobortis, neque justo iaculis metus, efficitur iaculis nibh dolor ac leo. Sed eu scelerisque augue. Nunc varius lobortis varius. Sed ultricies blandit lorem eget consequat. Mauris vestibulum magna ante, et commodo nulla aliquet vel. Aliquam nulla eros, feugiat sed arcu lobortis, eleifend pulvinar augue. Sed porttitor interdum varius. Pellentesque vel condimentum erat, sit amet molestie mauris. Vivamus rhoncus, dolor id dignissim aliquam, eros dolor finibus lorem, sed fringilla quam urna eget nunc. ', '2022-06-15 16:04:31', '2022-06-15 16:04:31', 'public'),
(19, 22, 'billet', 'fftt', ' Praesent consectetur non nulla quis pulvinar. In lacus lacus, scelerisque ac porttitor vitae, tincidunt quis sapien. Vestibulum molestie accumsan hendrerit. Suspendisse placerat pretium rutrum. Etiam sed neque ante. Pellentesque non lacus neque. Ut cursus odio non sodales viverra. Pellentesque varius, orci quis congue luctus, augue felis lobortis odio, at mollis nunc nibh non arcu. Nam orci justo, ornare porta mollis sit amet, porta non ligula. Proin fermentum ultrices elit id suscipit. Aliquam aliquam ut eros at cursus. Phasellus eget augue augue. Aliquam eu dolor arcu. ', '2022-06-15 17:36:28', '2022-06-17 15:30:37', 'public'),
(21, 1, 'test', 'test', 'encore un autre test', '2022-06-20 14:59:15', '2022-06-20 14:59:15', 'pending');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `first_name` text NOT NULL,
  `role` text NOT NULL DEFAULT 'reader',
  `email` text NOT NULL,
  `status` text NOT NULL,
  `password` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `name`, `first_name`, `role`, `email`, `status`, `password`) VALUES
(1, '_NAME_', '_FIRST_NAME_', 'admin', '_EMAIL_', 'validated', '$2y$10$r/G3DjbqcJOgESo7ZIMJz..Kk7.DRVWhqPi3FF64vWJHS.OIviPDq'),
(15, 'DUPONT', 'Jean', 'reader', 'jeand@mail.com', 'validated', '$2y$10$a4ROVuCLmHCJZLGoMXvshu7da3h5gxpdZGXdcRXqm3281pRq4L0sm'),
(22, 'DUBOIS', 'Anne', 'reader', 'anned@mail.com', 'validated', '$2y$10$/APSa6X0WFNe3fIVdHS/a.d9UPh7hsRSN1SPYeJbd2V9PC2lmyQzu');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

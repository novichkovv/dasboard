SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Data base: `dashboard`
--
CREATE DATABASE IF NOT EXISTS `dashboard` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `dashboard`;

-- --------------------------------------------------------

--
-- Table structure `system_routes`
--

CREATE TABLE IF NOT EXISTS `system_routes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `route` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `position` int(11) NOT NULL,
  `hidden` tinyint(4) NOT NULL,
  `extenal` tinyint(4) NOT NULL,
  `parent` bigint(20) unsigned NOT NULL,
  `icon` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Table data dump `system_routes`
--

INSERT INTO `system_routes` (`id`, `route`, `title`, `position`, `hidden`, `extenal`, `parent`, `icon`) VALUES
(1, '', 'Dashboard', 1, 0, 0, 0, 'fa fa-dashboard'),
(2, '', 'Backend Users', 2, 0, 0, 0, 'fa fa-user'),
(3, 'users', 'User List', 3, 0, 0, 2, ''),
(4, 'users/add', 'Add User', 4, 0, 0, 2, ''),
(5, 'users/groups', 'User Groups', 5, 0, 0, 2, ''),
(6, 'users/add_group', 'Add Group', 6, 0, 0, 2, ''),
(7, 'users/permissions', 'Group Permissions', 7, 0, 0, 2, ''),
(8, 'upload', 'Upload', 7, 0, 0, 0, 'fa fa-upload');

-- --------------------------------------------------------

--
-- Table Structure `system_routes_user_groups_relations`
--

CREATE TABLE IF NOT EXISTS `system_routes_user_groups_relations` (
  `system_route_id` bigint(20) unsigned NOT NULL,
  `user_group_id` bigint(20) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table data dump `system_routes_user_groups_relations`
--

INSERT INTO `system_routes_user_groups_relations` (`system_route_id`, `user_group_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1);

-- --------------------------------------------------------

--
-- Table Structure `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_group_id` bigint(20) unsigned NOT NULL,
  `email` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_surname` varchar(255) NOT NULL,
  `hash` varchar(255) NOT NULL,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Table data dump `users`
--

INSERT INTO `users` (`id`, `user_group_id`, `email`, `user_password`, `phone`, `user_name`, `user_surname`, `hash`, `create_date`) VALUES
(1, 1, 'admin', '4d32b723c8b58e1846a8e997c6ecdb63', '', 'Yevgeniy', 'Novichkov', '', '2015-08-07 11:04:00');

-- --------------------------------------------------------

--
-- Table Structure `user_groups`
--

CREATE TABLE IF NOT EXISTS `user_groups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `group_name` varchar(255) NOT NULL,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Table data dump `user_groups`
--

INSERT INTO `user_groups` (`id`, `group_name`, `create_date`) VALUES
(1, 'Super Admin', '2015-08-07 11:04:00');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- --------------------------------------------------------

--
-- Table Structure `charts`
--

CREATE TABLE IF NOT EXISTS `charts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `chart_name` varchar(255) NOT NULL,
  `chart_key` varchar(255) NOT NULL,
  `position` int(11) NOT NULL,
  `icon` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Table data dump `charts`
--
INSERT INTO `charts` (`chart_name`, `chart_key`, `position`, `icon`) VALUES
  ('Active Projects', 'active_projects', 1, ''),
  ('Team Member Hours', 'team_member_hours', 2, ''),
  ('Team Member Table', 'team_member_table', 3, ''),
  ('Utilization In Office', 'utilization', 4, ''),
  ('Week Performance', 'week', 5, ''),
  ('Project Detail', 'project_detail', 6, ''),
  ('Project Cost', 'project_cost', 7, ''),
  ('Overall Costs', 'overall', 8, '');

-- --------------------------------------------------------

--
-- Table Structure `charts_user_groups_relations`
--

CREATE TABLE IF NOT EXISTS `charts_user_groups_relations` (
  `chart_id` bigint(20) unsigned NOT NULL,
  `user_group_id` bigint(20) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



CREATE TABLE `user_mapping` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(100) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `user_name` (`user_name`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE work_time (
  `id` SERIAL PRIMARY KEY,
  `username` varchar(100) NOT NULL,
  `work_begin` DATETIME NOT NULL,
  `work_end` DATETIME NOT NULL
);
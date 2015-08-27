-- phpMyAdmin SQL Dump
-- version 4.0.10.6
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Авг 27 2015 г., 18:55
-- Версия сервера: 5.5.41-log
-- Версия PHP: 5.5.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `dashboard`
--

-- --------------------------------------------------------

--
-- Структура таблицы `asanatt_charts`
--

CREATE TABLE IF NOT EXISTS `asanatt_charts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `chart_name` varchar(255) NOT NULL,
  `chart_key` varchar(255) NOT NULL,
  `position` int(11) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `color` varchar(30) NOT NULL,
  `url` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Дамп данных таблицы `asanatt_charts`
--

INSERT INTO `asanatt_charts` (`id`, `chart_name`, `chart_key`, `position`, `icon`, `color`, `url`) VALUES
  (1, 'Active Projects', 'active_projects', 1, 'fa fa-bar-chart', 'st-red', 'index'),
  (2, 'Team Member Hours', 'team_member_hours', 2, 'fa fa-clock-o', 'st-yellow', 'index'),
  (3, 'Team Member Table', 'team_member_table', 3, 'fa fa-th', 'st-grey', 'members_table'),
  (4, 'Utilization In Office', 'utilization', 4, 'fa fa-circle-o-notch', 'st-green', 'office_utilization'),
  (5, 'Week Performance', 'week', 5, 'fa fa-calendar', 'st-blue', 'week_performance'),
  (6, 'Project Detail', 'project_detail', 6, 'fa fa-tasks', 'st-violet', 'detail'),
  (7, 'Project Cost', 'project_cost', 7, 'fa fa-usd', 'st-pink', 'cost'),
  (8, 'Overall Costs', 'overall', 8, 'fa fa-area-chart', 'st-dark-blue', 'overall_cost');

-- --------------------------------------------------------

--
-- Структура таблицы `charts_user_groups_relations`
--

CREATE TABLE IF NOT EXISTS `asanatt_charts_user_groups_relations` (
  `chart_id` bigint(20) unsigned NOT NULL,
  `user_group_id` bigint(20) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `asanatt_charts_user_groups_relations`
--

INSERT INTO `asanatt_charts_user_groups_relations` (`chart_id`, `user_group_id`) VALUES
  (1, 1),
  (2, 1),
  (3, 1),
  (4, 1),
  (5, 1),
  (6, 1),
  (7, 1),
  (8, 1),
  (1, 3),
  (2, 3),
  (3, 3),
  (4, 3),
  (5, 3),
  (6, 3),
  (7, 3),
  (8, 3);
-- --------------------------------------------------------

--
-- Структура таблицы `asanatt_system_routes`
--

CREATE TABLE IF NOT EXISTS `asanatt_system_routes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `route` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `position` int(11) NOT NULL,
  `hidden` tinyint(4) NOT NULL,
  `permitted` tinyint(4) NOT NULL,
  `extenal` tinyint(4) NOT NULL,
  `parent` bigint(20) unsigned NOT NULL,
  `icon` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Дамп данных таблицы `asanatt_system_routes`
--

INSERT INTO `asanatt_system_routes` (`id`, `route`, `title`, `position`, `hidden`, `permitted`, `extenal`, `parent`, `icon`) VALUES
  (1, '', 'Dashboard', 1, 0, 0, 0, 0, 'fa fa-dashboard'),
  (2, '', 'Backend Users', 2, 0, 0, 0, 0, 'fa fa-user'),
  (3, 'users', 'User List', 3, 0, 0, 0, 2, ''),
  (4, 'users/add', 'Add User', 4, 0, 0, 0, 2, ''),
  (5, 'users/groups', 'User Groups', 5, 0, 0, 0, 2, ''),
  (6, 'users/add_group', 'Add Group', 6, 0, 0, 0, 2, ''),
  (7, 'users/permissions', 'Group Permissions', 7, 0, 0, 0, 2, ''),
  (8, 'upload', 'Upload', 8, 0, 0, 0, 0, 'fa fa-upload'),
  (9, 'mapping', 'User Mapping', 9, 0, 0, 0, 0, 'fa fa-exchange'),
  (10, 'settings', 'Settings', 10, 1, 0, 0, 0, ''),
  (11, 'dashboard/index', 'Dashboard', 11, 1, 1, 0, 0, ''),
  (12, 'dashboard/members_table', 'Dashboard', 12, 1, 1, 0, 0, ''),
  (13, 'dashboard/office_utilization', 'Dashboard', 13, 1, 1, 0, 0, ''),
  (14, 'dashboard/week_performance', 'Dashboard', 14, 1, 1, 0, 0, ''),
  (15, 'dashboard/detail', 'Dashboard', 15, 1, 1, 0, 0, ''),
  (16, 'dashboard/cost', 'Dashboard', 16, 1, 1, 0, 0, '');

--
-- Структура таблицы `asanatt_system_routes_user_groups_relations`
--

CREATE TABLE IF NOT EXISTS `asanatt_system_routes_user_groups_relations` (
  `system_route_id` bigint(20) unsigned NOT NULL,
  `user_group_id` bigint(20) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `asanatt_system_routes_user_groups_relations`
--

INSERT INTO `asanatt_system_routes_user_groups_relations` (`system_route_id`, `user_group_id`) VALUES
  (1, 1),
  (2, 1),
  (3, 1),
  (4, 1),
  (5, 1),
  (6, 1),
  (7, 1),
  (8, 1),
  (9, 1),
  (10, 1),
  (8, 2),
  (10, 2),
  (1, 3),
  (10, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `asanatt_users`
--

CREATE TABLE IF NOT EXISTS `asanatt_users` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `asanatt_users`
--

INSERT INTO `asanatt_users` (`id`, `user_group_id`, `email`, `user_password`, `phone`, `user_name`, `user_surname`, `hash`, `create_date`) VALUES
  (1, 1, 'admin', '297ff4a97fcda4bc0ecf0bb18168034a', '', 'Admin', '', '', '2015-08-07 11:04:00'),
  (2, 2, 'uploader@mail.com', '297ff4a97fcda4bc0ecf0bb18168034a', '', 'Uploader', '', '', '2015-08-07 12:50:53'),
  (3, 3, 'viewer@mail.com', '297ff4a97fcda4bc0ecf0bb18168034a', '', 'Viewer', '', '', '2015-08-07 12:51:46');

-- --------------------------------------------------------

--
-- Структура таблицы `asanatt_user_groups`
--

CREATE TABLE IF NOT EXISTS `asanatt_user_groups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `group_name` varchar(255) NOT NULL,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `asanatt_user_groups`
--

INSERT INTO `asanatt_user_groups` (`id`, `group_name`, `create_date`) VALUES
  (1, 'Super Admin', '2015-08-27 11:04:00'),
  (2, 'Uploader', '2015-08-27 11:04:00'),
  (3, 'Viewer', '2015-08-27 11:04:00');

-- --------------------------------------------------------

--
-- Структура таблицы `asanatt_user_mapping`
--

CREATE TABLE IF NOT EXISTS `asanatt_user_mapping` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_email` varchar(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_rate` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `user_name` (`user_email`),
  UNIQUE KEY `user_id` (`user_name`),
  UNIQUE KEY `user_email` (`user_email`),
  UNIQUE KEY `user_name_2` (`user_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=59 ;

--
-- Дамп данных таблицы `asanatt_user_mapping`
--

INSERT INTO `asanatt_user_mapping` (`id`, `user_email`, `user_name`, `user_rate`) VALUES
  (41, 'bernardo@viatechnik.com', 'Bernardo, Dennis', ''),
  (42, 'barrido@viatechnik.com', 'Barrido, Gloria Mae', ''),
  (43, 'buizon@viatechnik.com', 'Buizon, Monico', ''),
  (44, 'martinez@viatechnik.com', 'Martinez, Jermaine', ''),
  (45, 'dumo@viatechnik.com', 'Dumo, Kristel', ''),
  (46, 'belardo@viatechnik.com', 'Belardo, Daisy', ''),
  (47, 'bacong@viatechnik.com', 'Bacong, Kenneth', ''),
  (48, 'mandapad@viatechnik.com', 'Mandapat, Abigael', ''),
  (49, 'cunanan@viatechnik.com', 'Cunanan, Ronald', ''),
  (50, 'aguillon@viatechnik.com', 'Aguillon, Oliver', ''),
  (51, 'gallardo@viatechnik.com', 'Gallardo, Johanna', ''),
  (52, 'balasa@viatechnik.com', 'Balasa, Joan B', ''),
  (53, 'caluag@viatechnik.com', 'Caluag, Rosanna', ''),
  (54, 'bonifacio@viatechnik.com', 'Bonifacio, Woody', ''),
  (55, 'dayauon@viatechnik.com', 'Dayauon, Eric', ''),
  (56, 'deguzman@viatechnik.com', 'De Guzman, Joseph', ''),
  (57, 'gose@viatechnik.com', 'Goce, Dennis', ''),
  (58, 'campos@viatechnik.com', 'Campos, Alexis', '');

-- --------------------------------------------------------

--
-- Структура таблицы `asanatt_excel_time`
--

CREATE TABLE IF NOT EXISTS `asanatt_excel_time` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `work_begin` datetime NOT NULL,
  `work_end` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1733 ;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;



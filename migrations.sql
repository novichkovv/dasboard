INSERT INTO `dashboard`.`asanatt_system_routes` (`route`, `title`, `position`, `icon`) VALUES ('summary', 'Summary', '17', 'fa fa-calendar');
INSERT INTO `dashboard`.`asanatt_system_routes` (`route`, `title`, `position`, `icon`) VALUES ('summary/settings', 'Settings', '18', 'fa fa-gears');
INSERT INTO `dashboard`.`asanatt_system_routes` (`route`, `title`, `position`, `icon`) VALUES ('summary/overtime', 'Overtime', '19', 'fa fa-clock-o');
INSERT INTO `dashboard`.`asanatt_system_routes` (`route`, `title`, `position`, `icon`) VALUES ('summary/approve', 'Approve', '19', 'fa fa-check-square-o');

CREATE TABLE asanatt_system_config (
  id SERIAL PRIMARY KEY,
  config_key VARCHAR(50) NOT NULL,
  config_value VARCHAR(50) NOT NULL
);

CREATE TABLE asanatt_overtime (
  id SERIAL PRIMARY KEY,
  user_id BIGINT UNSIGNED NOT NULL,
  overtime_suggested DECIMAL (4,2) NOT NULL,
  overtime_approved DECIMAL (4,2) NULL,
  work_date DATE NOT NULL
)ENGINE=MyISAM;

ALTER TABLE asanatt_user_groups ADD can_approve TINYINT NOT NULL DEFAULT 0 AFTER group_name;


CREATE DATABASE IF NOT EXISTS soft_monitor;
USE soft_monitor;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `user_groups`;
DROP TABLE IF EXISTS `templates`;
DROP TABLE IF EXISTS `oids`;
DROP TABLE IF EXISTS `device_list`;
DROP TABLE IF EXISTS `connections`;
DROP TABLE IF EXISTS `config`;
DROP TABLE IF EXISTS `alarms`;
DROP TABLE IF EXISTS `logging`;

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `login` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pass` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `group_id` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `users`
  ADD UNIQUE KEY `login` (`login`);

ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

INSERT INTO `users` (`login`, `pass`, `group_id`) VALUES
('admin', '21232f297a57a5a743894a0e4a801fc3', '1');


CREATE TABLE `user_groups` (
  `id` int(4) UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `rights` int(1) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

ALTER TABLE `user_groups`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `user_groups`
  ADD UNIQUE KEY `name` (`name`);

ALTER TABLE `user_groups`
  MODIFY `id` int(4) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

INSERT INTO `user_groups` (`name`, `rights`) VALUES
('admins', '1'),
('dispatchers', '0');

CREATE TABLE `templates` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `recover_time` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

ALTER TABLE `templates`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `templates`
  ADD UNIQUE KEY `name` (`name`);

ALTER TABLE `templates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

INSERT INTO `templates` (`id`, `name`, `recover_time`) VALUES
(1, 'Eaton APS', 30),
(2, 'Elteco BZX PS', 30),
(3, 'Eaton PW9130', 30),
(4, 'Socomec PS', 30);

CREATE TABLE `oids` (
  `id` int(10) UNSIGNED NOT NULL,
  `oid` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `low` VARCHAR(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `high` VARCHAR(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `message` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `level` INT(1) UNSIGNED NOT NULL,
  `template_id` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

ALTER TABLE `oids`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `oids`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

INSERT INTO `oids` (`id`, `oid`, `low`, `high`, `message`, `level`, `template_id`) VALUES
(1, '.1.3.6.1.4.1.1918.2.13.10.100.10.0', '95', '', 'Battery discharge', 1, 1),
(2, '.1.3.6.1.4.1.1918.2.13.10.60.13.0', '', '0.5', 'Rectifier Alarm', 2, 1),
(3, '.1.3.6.1.4.1.1918.2.13.10.40.10.0', '210', '250', 'Input voltage is out of limits', 2, 1),
(4, '.1.3.6.1.4.1.1918.2.13.10.40.10.0', '10', '', 'No input voltage', 3, 1),
(5, '.1.3.6.1.4.1.1918.2.13.10.90.15.50.0', '', '0.5', 'Battery disconnection', 3, 1),
(6, '.1.3.6.1.4.1.1918.2.13.10.60.17.0', '', '0.5', 'Rectifier Shutdown', 3, 1),
(7, '.1.3.6.1.4.1.1918.2.13.10.100.30.0', '12', '', 'Low battery temperature', 1, 1),
(8, '.1.3.6.1.4.1.1918.2.13.10.100.30.0', '', '37', 'High battery temperature', 1, 1),
(9, '.1.3.6.1.4.1.1488.16.1.47.1.2.4.0', '95', '', 'Battery discharge', 1, 2),
(10, '.1.3.6.1.4.1.1488.16.1.47.1.4.3.63.0', '', '0.5', 'No input voltage', 3, 2),
(11, '.1.3.6.1.4.1.1488.16.1.47.1.2.3.0', '12', '', 'Low battery temperature', 1, 2),
(12, '.1.3.6.1.4.1.1488.16.1.47.1.2.3.0', '', '37', 'High battery temperature', 1, 2),
(13, '.1.3.6.1.2.1.33.1.2.4.0', '95', '', 'Battery discharge', 1, 3),
(14, '.1.3.6.1.2.1.33.1.3.3.1.3.0', '210', '250', 'Input voltage is out of limits', 2, 3),
(15, '.1.3.6.1.2.1.33.1.3.3.1.3.0', '10', '', 'No input voltage', 3, 3),
(16, '.1.3.6.1.4.1.534.1.6.1.0', '12', '', 'Low battery temperature', 1, 3),
(17, '.1.3.6.1.4.1.534.1.6.1.0', '', '37', 'High battery temperature', 3, 3),
(18, '.1.3.6.1.4.1.4555.1.1.1.1.3.3.1.5.1', '100', '', 'No input voltage. Phase 1', 3, 4),
(19, '.1.3.6.1.4.1.4555.1.1.1.1.3.3.1.5.2', '100', '', 'No input voltage. Phase 2', 3, 4),
(20, '.1.3.6.1.4.1.4555.1.1.1.1.3.3.1.5.3', '100', '', 'No input voltage. Phase 3', 3, 4),
(21, '.1.3.6.1.4.1.4555.1.1.1.1.3.3.1.5.1', '2100', '2500', 'Input voltage is out of limits. Phase 1', 2, 4),
(22, '.1.3.6.1.4.1.4555.1.1.1.1.3.3.1.5.2', '2100', '2500', 'Input voltage is out of limits. Phase 2', 2, 4),
(23, '.1.3.6.1.4.1.4555.1.1.1.1.3.3.1.5.3', '2100', '2500', 'Input voltage is out of limits. Phase 3', 2, 4),
(24, '.1.3.6.1.4.1.4555.1.1.1.1.2.4.0', '95', '', 'Battery discharge', 1, 4),
(25, '.1.3.6.1.4.1.4555.1.1.1.1.2.1.0', '', '2.5', 'Battery Alarm', 2, 4);

CREATE TABLE `device_list` (
  `id` int(20) UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ip` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `template_id` int(10) UNSIGNED NOT NULL,
  `connection_id` int(10) UNSIGNED NOT NULL,
  `ping` VARCHAR(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'None',
  `ping_attempts` INT(2) UNSIGNED NOT NULL,
  `ping_timeout` INT(5) UNSIGNED NOT NULL,
  `group_id` int(4) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

ALTER TABLE `device_list`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `device_list`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;


CREATE TABLE `connections` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `snmp_version` int(1) UNSIGNED NOT NULL,
  `login` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pass` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

ALTER TABLE `connections`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `connections`
  ADD UNIQUE KEY `name` (`name`);

ALTER TABLE `connections`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

INSERT INTO `connections` (`name`, `snmp_version`, `login`, `pass`) VALUES
('Standard V1', '1', 'public', '');

CREATE TABLE `config` (
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `param` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

ALTER TABLE `config`
  ADD PRIMARY KEY (`name`);
  
INSERT INTO `config` (`name`, `param`) VALUES
('default_recovery_time', '30'),
('default_ping_attempts', '1'),
('default_ping_timeout', '200'),
('processor_instances', '3');


CREATE TABLE `alarms` (
  `id` int(10) UNSIGNED NOT NULL,
  `device_id` int(20) UNSIGNED NOT NULL,
  `oid_id` INT(10) UNSIGNED NOT NULL,
  `message` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `occur_time` int(10) UNSIGNED NOT NULL,
  `lvl` int(1) UNSIGNED NOT NULL,
  `recover` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `recover_time` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `ack` INT(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

ALTER TABLE `alarms`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `alarms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;


CREATE TABLE `logging` (
  `id` int(30) UNSIGNED NOT NULL,
  `occur_time` int(10) UNSIGNED NOT NULL,
  `lvl` int(1) UNSIGNED NOT NULL,
  `message` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `device_id` INT(20) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

ALTER TABLE `logging`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `logging`
  MODIFY `id` int(30) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

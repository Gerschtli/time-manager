USE `time-manager`;

CREATE TABLE `tm_projects` (
  `projectId` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(127) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`projectId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `tm_tasks` (
  `taskId` int(11) NOT NULL AUTO_INCREMENT,
  `projectId` int(11) NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`taskId`),
  FOREIGN KEY (`projectId`) REFERENCES `tm_projects` (`projectId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `tm_time` (
  `timeId` int(11) NOT NULL AUTO_INCREMENT,
  `taskId` int(11) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  PRIMARY KEY (`timeId`),
  FOREIGN KEY (`taskId`) REFERENCES `tm_tasks` (`taskId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `tm_projects` (`projectId`, `name`) VALUES
(1, 'Profitmax'),
(2, 'Test1');

INSERT INTO `tm_tasks` (`taskId`, `projectId`, `description`) VALUES
(1, 1, '1.Task'),
(2, 2, '2.Task');

INSERT INTO `tm_time` (`timeId`, `taskId`, `start`, `end`) VALUES
(1, 1, '2015-10-18 05:00:00', '2015-10-18 08:00:00'),
(2, 1, '2015-10-19 06:00:00', '2015-10-19 10:00:00');

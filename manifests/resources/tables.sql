USE `time-manager`;

CREATE TABLE `tm_projects` (
  `projectId` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(127) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`projectId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `tm_tasks` (
  `taskId` int(11) NOT NULL AUTO_INCREMENT,
  `projectId` int(11) NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`taskId`),
  FOREIGN KEY (`projectId`) REFERENCES `tm_projects` (`projectId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `tm_time` (
  `timeId` int(11) NOT NULL AUTO_INCREMENT,
  `taskId` int(11) NULL,
  `start` datetime NOT NULL,
  `end` datetime NULL,
  PRIMARY KEY (`timeId`),
  FOREIGN KEY (`taskId`) REFERENCES `tm_tasks` (`taskId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

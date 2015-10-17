USE `time-manager`;

CREATE TABLE `projects` (
  `projectId` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(127) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`projectId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `tasks` (
  `taskId` int(11) NOT NULL AUTO_INCREMENT,
  `projectId` int(11) NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`taskId`),
  FOREIGN KEY (`projectId`) REFERENCES `projects` (`projectId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `time` (
  `timeId` int(11) NOT NULL AUTO_INCREMENT,
  `taskId` int(11) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  PRIMARY KEY (`timeId`),
  FOREIGN KEY (`taskId`) REFERENCES `tasks` (`taskId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

USE `time-manager`;

CREATE TABLE `tm_tasks` (
  `taskId` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `tm_time` (
  `timeId` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `taskId` int(11) NOT NULL REFERENCES `tm_tasks`.`taskId`,
  `start` datetime NOT NULL,
  `end` datetime NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

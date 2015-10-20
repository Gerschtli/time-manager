USE `time-manager`;

INSERT INTO `tm_projects` (`projectId`, `name`) VALUES
(1, 'Profitmax'),
(2, 'Test1');

INSERT INTO `tm_tasks` (`taskId`, `projectId`, `description`) VALUES
(1, 1, '1.Task'),
(2, 2, '2.Task');

INSERT INTO `tm_time` (`timeId`, `taskId`, `start`, `end`) VALUES
(1, 1, '2015-10-18 05:00:00', '2015-10-18 08:00:00'),
(2, 1, '2015-10-19 06:00:00', '2015-10-19 10:00:00');

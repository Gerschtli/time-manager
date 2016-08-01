USE time_manager;

INSERT INTO tasks (id, description) VALUES
(1, '1.Task'),
(2, '2.Task');

INSERT INTO times (id, start, end) VALUES
(1, '2015-10-18 05:00:00', '2015-10-18 08:00:00'),
(2, '2015-10-19 06:00:00', '2015-10-19 10:00:00');

INSERT INTO tasks_times (task_id, time_id) VALUES
(1, 1),
(1, 2);

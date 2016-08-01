USE time_manager;

CREATE TABLE tasks (
    id          int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    description varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE times (
    id    int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    start datetime NOT NULL,
    end   datetime NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE tasks_times (
    task_id int(11) NOT NULL REFERENCES tasks(id),
    time_id int(11) NOT NULL UNIQUE REFERENCES times(id),
    PRIMARY KEY(task_id, time_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

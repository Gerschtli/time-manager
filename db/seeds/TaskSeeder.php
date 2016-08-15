<?php

use Phinx\Seed\AbstractSeed;

class TaskSeeder extends AbstractSeed
{
    public function run()
    {
        $this->table('tasks')
            ->insert(
                [
                    [
                        'id'          => 1,
                        'description' => '1.Task',
                    ],
                    [
                        'id'          => 2,
                        'description' => '2.Task',
                    ],
                ]
            )
            ->save();

        $this->table('times')
            ->insert(
                [
                    [
                        'id'    => 1,
                        'start' => '2015-10-18 05:00:00',
                        'end'   => '2015-10-18 08:00:00',
                    ],
                    [
                        'id'    => 2,
                        'start' => '2015-10-18 06:00:00',
                    ],
                    [
                        'id'    => 3,
                        'start' => '2015-10-18 12:00:00',
                        'end'   => '2015-10-18 15:30:00',
                    ],
                ]
            )
            ->save();

        $this->table('tasks_times')
            ->insert(
                [
                    [
                        'task_id' => 1,
                        'time_id' => 1,
                    ],
                    [
                        'task_id' => 1,
                        'time_id' => 2,
                    ],
                    [
                        'task_id' => 2,
                        'time_id' => 3,
                    ],
                ]
            )
            ->save();
    }
}

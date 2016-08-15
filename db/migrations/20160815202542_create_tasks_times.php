<?php

use Phinx\Migration\AbstractMigration;

class CreateTasksTimes extends AbstractMigration
{
    public function change()
    {
        $this
            ->table(
                'tasks_times',
                [
                    'id'          => false,
                    'primary_key' => ['task_id', 'time_id'],
                ]
            )
            ->addColumn('task_id', 'integer')
            ->addColumn('time_id', 'integer')
            ->addIndex('time_id', ['type' => 'unique'])
            ->addForeignKey('task_id', 'tasks')
            ->addForeignKey('time_id', 'times')
            ->create();
    }
}

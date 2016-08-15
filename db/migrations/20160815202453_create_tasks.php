<?php

use Phinx\Migration\AbstractMigration;

class CreateTasks extends AbstractMigration
{
    public function change()
    {
        $this
            ->table('tasks')
            ->addColumn('description', 'string', ['null' => false])
            ->create();
    }
}

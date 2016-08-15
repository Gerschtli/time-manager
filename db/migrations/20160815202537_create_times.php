<?php

use Phinx\Migration\AbstractMigration;

class CreateTimes extends AbstractMigration
{
    public function change()
    {
        $this
            ->table('times')
            ->addColumn('start', 'datetime', ['null' => false])
            ->addColumn('end', 'datetime', ['null' => true])
            ->create();
    }
}

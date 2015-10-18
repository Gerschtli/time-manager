<?php

namespace TimeManager\Service;

use Slim\Slim;

class Project
{
    private $_app;

    public function __construct(Slim $app)
    {
        $this->_app = $app;
    }

    public function getById($projectId)
    {
        $dbal = $this->_app->dbal;
        return $dbal->find('\TimeManager\Model\Project', $projectId);
    }
}

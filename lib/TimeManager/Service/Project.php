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

    public function getByName($name)
    {
        return $this->_app->dbal
            ->getRepository('\TimeManager\Model\Project')
            ->findOneBy(['_name'=> $name]);
    }
}

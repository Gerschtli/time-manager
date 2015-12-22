<?php

namespace TimeManager\Service;

use TimeManager\AppAware;

class Project extends AppAware
{
    public function createModel($name)
    {
        $project = $this->getByName($name);

        if (is_null($project)) {
            $project = $this->_app->modelProject;
            $project->setName($name);

            $this->_app->dbal->persist($project);
            $this->_app->dbal->flush();
        }

        return $project;
    }

    public function getByName($name)
    {
        return $this->_app->dbal
            ->getRepository('\TimeManager\Model\Project')
            ->findOneBy(['_name'=> $name]);
    }
}

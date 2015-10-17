<?php

namespace TimeManager\Service;

class ProjectTest extends \LocalWebTestCase
{
    private $_object;

    public function setUp()
    {
        parent::setUp();
        $this->_object = new Project($this->app);
    }

    public function testGetById()
    {
        $projectId = time();
        $this->app->dbal = $this
            ->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();
        $this->app->dbal
            ->expects($this->once())
            ->method('find')
            ->with(
                $this->equalTo('\TimeManager\Model\Project'),
                $this->equalTo($projectId)
            )
            ->will($this->returnValue('return'));

        $this->assertEquals('return', $this->_object->getById($projectId));
    }
}

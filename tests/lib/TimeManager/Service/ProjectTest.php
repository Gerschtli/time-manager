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

    public function testGetByName()
    {
        $name = time();

        $this->app->dbal = $this
            ->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();
        $repository = $this
            ->getMockBuilder('\Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->dbal
            ->expects($this->once())
            ->method('getRepository')
            ->with($this->equalTo('\TimeManager\Model\Project'))
            ->will($this->returnValue($repository));

        $repository
            ->expects($this->once())
            ->method('findOneBy')
            ->with($this->equalTo(['_name' => $name]))
            ->will($this->returnValue('return'));

        $this->assertEquals(
            'return',
            $this->_object->getByName($name)
        );
    }
}

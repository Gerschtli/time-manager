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

    public function testInstance()
    {
        $this->assertInstanceOf('\TimeManager\Service\Project', $this->_object);
        $this->assertInstanceOf('\TimeManager\AppAware', $this->_object);
    }

    public function testCreateModel()
    {
        $object = $this
            ->getMockBuilder('\TimeManager\Service\Project')
            ->setConstructorArgs([$this->app])
            ->setMethods(['getByName'])
            ->getMock();

        $object
            ->expects($this->once())
            ->method('getByName')
            ->with($this->equalTo('project'))
            ->will($this->returnValue('test'));

        $this->assertEquals('test', $object->createModel('project'));
    }

    public function testCreateModelWithExistingProject()
    {
        $object                  = $this
            ->getMockBuilder('\TimeManager\Service\Project')
            ->setConstructorArgs([$this->app])
            ->setMethods(['getByName'])
            ->getMock();
        $this->app->modelProject = $this
            ->getMockBuilder('\TimeManager\Model\Project')
            ->disableOriginalConstructor()
            ->getMock();
        $this->app->dbal         = $this
            ->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $object
            ->expects($this->once())
            ->method('getByName')
            ->with($this->equalTo('project'))
            ->will($this->returnValue(null));

        $this->app->modelProject
            ->expects($this->once())
            ->method('setName')
            ->with($this->equalTo('project'));

        $this->app->dbal
            ->expects($this->at(0))
            ->method('persist')
            ->with($this->equalTo($this->app->modelProject));
        $this->app->dbal
            ->expects($this->at(1))
            ->method('flush');

        $this->assertEquals($this->app->modelProject, $object->createModel('project'));
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

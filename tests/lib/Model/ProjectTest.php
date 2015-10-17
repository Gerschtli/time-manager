<?php

namespace TimeManager\Model;

class ProjectTest extends \LocalWebTestCase
{
    private $_object;

    public function setUp()
    {
        parent::setUp();
        $this->_object = new Project();
    }

    public function testGetProjectId()
    {
        $projectId          = time();
        $reflectionClass    = new \ReflectionClass('\TimeManager\Model\Project');
        $reflectionProperty = $reflectionClass->getProperty('projectId');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->_object, $projectId);

        $this->assertEquals($projectId, $this->_object->getProjectId());
    }

    /**
     * @dataProvider dataProviderForTestGetterSetter
     */
    public function testGetterSetter($name)
    {
        $value  = time();

        $setter = 'set' . ucfirst($name);
        $getter = 'get' . ucfirst($name);

        $this->_object->$setter($value);
        $this->assertEquals($value, $this->_object->$getter());
    }

    public function dataProviderForTestGetterSetter()
    {
        return [
            ['name'],
        ];
    }
}

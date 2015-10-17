<?php

class DicTest extends \LocalWebTestCase
{
    private $_allDependencies;
    private $_systemDependencies = [
        'settings',
        'environment',
        'request',
        'response',
        'router',
        'view',
        'logWriter',
        'log',
        'mode',
    ];

    public function testDic()
    {
        $dependencies = [
            'config'         => ['_sameInstance', '\stdClass'],
            'controllerTask' => ['_sameInstance', '\TimeManager\Controller\Task'],
            'dbal'           => ['_sameInstance', '\Doctrine\ORM\EntityManager'],
            'decoratorError' => ['_sameInstance', '\TimeManager\Decorator\Error'],
            'modelProject'   => ['_notSameInstance', '\TimeManager\Model\Project'],
            'serviceProject' => ['_sameInstance', '\TimeManager\Service\Project'],
        ];

        $this->_allDependencies = $this->app->container->all();

        foreach ($dependencies as $name => $info) {
            $this->{$info[0]}($name, $info[1]);
        }

        $this->_clearList();

        $this->assertEquals(0, count($this->_allDependencies));
    }

    private function _exists($name, $class)
    {
        $this->assertTrue($this->app->container->has($name), "{$name} exists");
        unset($this->_allDependencies[$name]);
    }

    private function _instanceOf($name, $class)
    {
        $this->_exists($name, $class);
        $this->assertInstanceOf($class, $this->app->$name);
    }

    private function _sameInstance($name, $class)
    {
        $this->_instanceOf($name, $class);
        
        $instanceOne = $this->app->$name;
        $instanceTwo = $this->app->$name;
        $this->assertSame($instanceOne, $instanceTwo);
    }

    private function _notSameInstance($name, $class)
    {
        $this->_instanceOf($name, $class);
        
        $instanceOne = $this->app->$name;
        $instanceTwo = $this->app->$name;
        $this->assertNotSame($instanceOne, $instanceTwo);
    }

    private function _clearList()
    {
        foreach ($this->_systemDependencies as $dependency) {
            unset($this->_allDependencies[$dependency]);
        }
    }
}

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
            'errorDecorator' => ['_instanceOf', '\TimeManager\Decorator\Error'],
            'pdo'            => ['_exists', '\Slim\PDO\Database'],
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
        $this->assertTrue($this->app->container->has($name));
        unset($this->_allDependencies[$name]);
    }

    private function _instanceOf($name, $class)
    {
        $this->_exists($name, $class);
        $this->assertInstanceOf($class, $this->app->$name);
    }

    private function _clearList()
    {
        foreach ($this->_systemDependencies as $dependency) {
            unset($this->_allDependencies[$dependency]);
        }
    }
}

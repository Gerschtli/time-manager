<?php

/**
 * @SuppressWarnings(PMD.UnusedPrivateMethod)
 * @SuppressWarnings(PMD.UnusedFormalParameter)
 */
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
            'config'                  => ['_sameInstance', '\stdClass'],
            'controllerError'         => ['_sameInstance', '\TimeManager\Controller\Error'],
            'controllerTask'          => ['_sameInstance', '\TimeManager\Controller\Task'],
            'entityManager'           => ['_sameInstance', '\Doctrine\ORM\EntityManager'],
            'middlewareJsonConverter' => ['_sameInstance', '\TimeManager\Middleware\JsonConverter'],
            'modelTask'               => ['_notSameInstance', '\TimeManager\Model\Task'],
            'modelTime'               => ['_notSameInstance', '\TimeManager\Model\Time'],
            'presenterData'           => ['_sameInstance', '\TimeManager\Presenter\Data'],
            'presenterInfo'           => ['_sameInstance', '\TimeManager\Presenter\Info'],
            'serviceTask'             => ['_sameInstance', '\TimeManager\Service\Task'],
            'serviceTime'             => ['_sameInstance', '\TimeManager\Service\Time'],
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

<?php

use Slim\Environment;
use Slim\Slim;

/**
 * @SuppressWarnings(PMD.UnusedPrivateMethod)
 * @SuppressWarnings(PMD.UnusedFormalParameter)
 */
class DicTest extends \PHPUnit_Framework_TestCase
{
    private $_allDependencies;
    private $_systemDependencies = [
        'environment',
        'log',
        'logWriter',
        'mode',
        'request',
        'response',
        'router',
        'settings',
        'view',
    ];
    private $_app;

    public function testDic()
    {
        $dependencies = [
            'config'                  => ['_sameInstance', '\stdClass'],
            'controllerError'         => ['_sameInstance', '\TimeManager\Controller\Error'],
            'controllerTask'          => ['_sameInstance', '\TimeManager\Controller\Task'],
            'entityManager'           => ['_sameInstance', '\Doctrine\ORM\EntityManager'],
            'middlewareJsonConverter' => ['_sameInstance', '\TimeManager\Middleware\JsonConverter'],
            'presenterData'           => ['_sameInstance', '\TimeManager\Presenter\Data'],
            'presenterInfo'           => ['_sameInstance', '\TimeManager\Presenter\Info'],
            'serviceTask'             => ['_sameInstance', '\TimeManager\Service\Task'],
            'serviceTime'             => ['_sameInstance', '\TimeManager\Service\Time'],
        ];

        $this->_app = $this->_getSlimInstance();

        $this->_allDependencies = $this->_app->container->all();

        foreach ($dependencies as $name => $info) {
            $this->{$info[0]}($name, $info[1]);
        }

        $this->_clearList();

        $this->assertEquals(0, count($this->_allDependencies));
    }

    private function _getSlimInstance()
    {
        $app = new Slim(
            [
                'debug' => false,
            ]
        );

        Environment::mock([]);

        require(PROJECT_ROOT . '/app/app.php');
        return $app;
    }

    private function _exists($name, $class)
    {
        $this->assertTrue($this->_app->container->has($name), "{$name} exists");
        unset($this->_allDependencies[$name]);
    }

    private function _instanceOf($name, $class)
    {
        $this->_exists($name, $class);
        $this->assertInstanceOf($class, $this->_app->$name);
    }

    private function _sameInstance($name, $class)
    {
        $this->_instanceOf($name, $class);

        $instanceOne = $this->_app->$name;
        $instanceTwo = $this->_app->$name;
        $this->assertSame($instanceOne, $instanceTwo);
    }

    private function _notSameInstance($name, $class)
    {
        $this->_instanceOf($name, $class);

        $instanceOne = $this->_app->$name;
        $instanceTwo = $this->_app->$name;
        $this->assertNotSame($instanceOne, $instanceTwo);
    }

    private function _clearList()
    {
        foreach ($this->_systemDependencies as $dependency) {
            unset($this->_allDependencies[$dependency]);
        }
    }
}

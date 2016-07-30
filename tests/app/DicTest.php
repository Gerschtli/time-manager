<?php

use Doctrine\ORM\EntityManager;
use Slim\App;
use TimeManager\Controller\Error as ErrorController;
use TimeManager\Controller\Task as TaskController;
use TimeManager\Middleware\JsonConverter;
use TimeManager\Presenter\Data as DataPresenter;
use TimeManager\Presenter\Info as InfoPresenter;
use TimeManager\Service\Task as TaskService;
use TimeManager\Service\Time as TimeService;

/**
 * @SuppressWarnings(PMD.UnusedPrivateMethod)
 * @SuppressWarnings(PMD.UnusedFormalParameter)
 */
class DicTest extends \PHPUnit_Framework_TestCase
{
    private $_allDependencies;
    private $_systemDependencies = [
        'callableResolver',
        'environment',
        'foundHandler',
        'phpErrorHandler',
        'request',
        'response',
        'router',
        'settings',
    ];
    private $_container;

    public function testDic()
    {
        $dependencies = [
            'errorHandler'         => [
                '_testCallable',
                [ErrorController::class, 'errorAction'],
            ],
            'notAllowedHandler'    => [
                '_testCallable',
                [ErrorController::class, 'notAllowedAction'],
            ],
            'notFoundHandler'      => [
                '_testCallable',
                [ErrorController::class, 'notFoundAction'],
            ],
            ErrorController::class => ['_sameInstance', ErrorController::class],
            TaskController::class  => ['_sameInstance', TaskController::class],
            EntityManager::class   => ['_sameInstance', EntityManager::class],
            JsonConverter::class   => ['_sameInstance', JsonConverter::class],
            DataPresenter::class   => ['_sameInstance', DataPresenter::class],
            InfoPresenter::class   => ['_sameInstance', InfoPresenter::class],
            TaskService::class     => ['_sameInstance', TaskService::class],
            TimeService::class     => ['_sameInstance', TimeService::class],
        ];

        $this->_container = $this->_getSlimInstance()->getContainer();

        $this->_allDependencies = $this->_container->keys();

        foreach ($dependencies as $name => $info) {
            $this->{$info[0]}($name, $info[1]);
        }

        $this->_clearList();

        $this->assertEquals(0, count($this->_allDependencies));
    }

    private function _getSlimInstance()
    {
        $app = new App(
            [
                'settings' => ['test' => 'bla'],
            ]
        );

        require_once(__DIR__ . '/../../app/dependencies.php');

        return $app;
    }

    private function _exists($name, $value)
    {
        $this->assertTrue($this->_container->has($name), "{$name} exists");

        $this->_delete($name);
    }

    private function _instanceOf($name, $value)
    {
        $this->_exists($name, $value);
        $this->assertInstanceOf($value, $this->_container->get($name));
    }

    private function _sameInstance($name, $value)
    {
        $this->_instanceOf($name, $value);

        $instanceOne = $this->_container->get($name);
        $instanceTwo = $this->_container->get($name);
        $this->assertSame($instanceOne, $instanceTwo);
    }

    private function _testCallable($name, $value)
    {
        $this->_exists($name, $value);

        $callable = $this->_container->get($name);
        $this->assertInstanceOf($value[0], $callable[0]);
        $this->assertEquals($value[1], $callable[1]);
    }

    private function _clearList()
    {
        foreach ($this->_systemDependencies as $dependency) {
            $this->_delete($dependency);
        }
    }

    private function _delete($name)
    {
        $key = array_search($name, $this->_allDependencies);

        if ($key !== false) {
            unset($this->_allDependencies[$key]);
        }
    }
}

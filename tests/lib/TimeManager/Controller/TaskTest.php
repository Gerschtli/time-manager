<?php

namespace TimeManager\Controller;

use TimeManager\Model\Task as TaskModel;

class TaskTest extends \PHPUnit_Framework_TestCase
{
    private $_object;
    private $_dataPresenter;
    private $_infoPresenter;
    private $_request;
    private $_taskService;

    public function setUp()
    {
        parent::setUp();

        $this->_dataPresenter = $this
            ->getMockBuilder('\TimeManager\Presenter\Data')
            ->disableOriginalConstructor()
            ->getMock();
        $this->_infoPresenter = $this
            ->getMockBuilder('\TimeManager\Presenter\Info')
            ->disableOriginalConstructor()
            ->getMock();
        $this->_request = $this
            ->getMockBuilder('\Slim\Http\Request')
            ->disableOriginalConstructor()
            ->getMock();
        $this->_taskService = $this
            ->getMockBuilder('\TimeManager\Service\Task')
            ->disableOriginalConstructor()
            ->getMock();

        $this->_object = new Task(
            $this->_dataPresenter, $this->_infoPresenter, $this->_request, $this->_taskService
        );
    }

    public function testAddAction()
    {
        $requestData = (object) [
            'description' => 'bla',
        ];

        $modelTask              = new TaskModel();
        $modelTask->description = 'bla';

        $this->_request
            ->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue($requestData));

        $this->_taskService
            ->expects($this->at(0))
            ->method('convertToEntity')
            ->with($this->equalTo($requestData))
            ->will($this->returnValue($modelTask));
        $this->_taskService
            ->expects($this->at(1))
            ->method('persistEntity')
            ->with($this->equalTo($modelTask));

        $this->_dataPresenter
            ->expects($this->once())
            ->method('process')
            ->with(
                $this->equalTo(201),
                $this->equalTo($modelTask)
            );

        $this->_object->addAction();
    }

    public function testAddActionWithInvalidData()
    {
        $requestData = (object) [
            'test' => 'bla',
        ];

        $this->_request
            ->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue($requestData));

        $this->_taskService
            ->expects($this->once())
            ->method('convertToEntity')
            ->with($this->equalTo($requestData))
            ->will($this->returnValue(null));
        $this->_taskService
            ->expects($this->never())
            ->method('persistEntity');

        $this->_infoPresenter
            ->expects($this->once())
            ->method('process')
            ->with(
                $this->equalTo(422),
                $this->equalTo('JSON is in invalid data structure')
            );

        $this->_object->addAction();
    }

    public function testDeleteAction()
    {
        $taskId = time();

        $this->_taskService
            ->expects($this->once())
            ->method('deleteById')
            ->with($this->equalTo($taskId));

        $this->_infoPresenter
            ->expects($this->once())
            ->method('process')
            ->with(
                $this->equalTo(200),
                $this->equalTo('Deletion successful')
            );

        $this->_object->deleteAction($taskId);
    }

    public function testGetAction()
    {
        $taskId = time();

        $modelTask = new TaskModel();

        $this->_taskService
            ->expects($this->once())
            ->method('getById')
            ->with($this->equalTo($taskId))
            ->will($this->returnValue($modelTask));

        $this->_dataPresenter
            ->expects($this->once())
            ->method('process')
            ->with(
                $this->equalTo(200),
                $this->equalTo($modelTask)
            );

        $this->_object->getAction($taskId);
    }

    public function testGetActionWithoutValidId()
    {
        $taskId = time();

        $this->_taskService
            ->expects($this->once())
            ->method('getById')
            ->with($this->equalTo($taskId))
            ->will($this->returnValue(null));

        $this->_infoPresenter
            ->expects($this->once())
            ->method('process')
            ->with(
                $this->equalTo(404),
                $this->equalTo('No Data with provided Key found')
            );

        $this->_object->getAction($taskId);
    }

    public function testGetAllAction()
    {
        $modelTaskOne              = new TaskModel();
        $modelTaskOne->description = 'dsad';

        $modelTaskTwo              = new TaskModel();
        $modelTaskTwo->description = 'ssssss';

        $this->_taskService
            ->expects($this->once())
            ->method('getAll')
            ->will($this->returnValue([$modelTaskOne, $modelTaskTwo]));

        $this->_dataPresenter
            ->expects($this->once())
            ->method('process')
            ->with(
                $this->equalTo(200),
                $this->equalTo([$modelTaskOne, $modelTaskTwo])
            );

        $this->_object->getAllAction();
    }

    public function testUpdateAction()
    {
        $taskId      = time();
        $requestData = (object) [
            'description' => 'bla',
        ];

        $modelTask = (object) ['taskId' => $taskId];

        $this->_request
            ->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue($requestData));

        $this->_taskService
            ->expects($this->once())
            ->method('update')
            ->with($this->equalTo($taskId), $this->equalTo($requestData))
            ->will($this->returnValue($modelTask));

        $this->_dataPresenter
            ->expects($this->once())
            ->method('process')
            ->with(
                $this->equalTo(202),
                $this->equalTo($modelTask)
            );

        $this->_object->updateAction($taskId);
    }

    public function testUpdateActionWithInvalidData()
    {
        $taskId      = time();
        $requestData = (object) [
            'test' => 'bla',
        ];

        $this->_request
            ->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue($requestData));

        $this->_taskService
            ->expects($this->once())
            ->method('update')
            ->with($this->equalTo($taskId), $this->equalTo($requestData))
            ->will($this->returnValue(null));

        $this->_infoPresenter
            ->expects($this->once())
            ->method('process')
            ->with(
                $this->equalTo(404),
                $this->equalTo('No Data with provided Key found')
            );

        $this->_object->updateAction($taskId);
    }
}

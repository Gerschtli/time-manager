<?php

namespace TimeManager\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use TimeManager\Model\Task as TaskModel;
use TimeManager\Presenter\Data as DataPresenter;
use TimeManager\Presenter\Info as InfoPresenter;
use TimeManager\Service\Task as TaskService;
use TimeManager\Transformer\Task as TaskTransformer;

class TaskTest extends \PHPUnit_Framework_TestCase
{
    private $_object;
    private $_dataPresenter;
    private $_infoPresenter;
    private $_taskService;
    private $_taskTransformer;

    public function setUp()
    {
        parent::setUp();

        $this->_dataPresenter = $this
            ->getMockBuilder(DataPresenter::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->_infoPresenter = $this
            ->getMockBuilder(InfoPresenter::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->_taskService = $this
            ->getMockBuilder(TaskService::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->_taskTransformer = $this
            ->getMockBuilder(TaskTransformer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_object = new Task(
            $this->_dataPresenter,
            $this->_infoPresenter,
            $this->_taskService,
            $this->_taskTransformer
        );
    }

    public function testAddAction()
    {
        $requestData = (object) [
            'description' => 'bla',
        ];

        $modelTask              = new TaskModel();
        $modelTask->description = 'bla';

        $request = $this
            ->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();
        $response = $this
            ->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();

        $request
            ->expects($this->once())
            ->method('getParsedBody')
            ->will($this->returnValue($requestData));

        $this->_taskTransformer
            ->expects($this->once())
            ->method('transformToModel')
            ->with($this->equalTo($requestData))
            ->will($this->returnValue($modelTask));

        $this->_taskService
            ->expects($this->once())
            ->method('persistEntity')
            ->with($this->equalTo($modelTask));

        $this->_dataPresenter
            ->expects($this->once())
            ->method('render')
            ->with(
                $this->equalTo($response),
                $this->equalTo(201),
                $this->equalTo($modelTask)
            )
            ->will($this->returnValue($response));

        $this->assertEquals(
            $response,
            $this->_object->addAction($request, $response, [])
        );
    }

    public function testAddActionWithInvalidData()
    {
        $requestData = (object) [
            'test' => 'bla',
        ];

        $request = $this
            ->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();
        $response = $this
            ->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();

        $request
            ->expects($this->once())
            ->method('getParsedBody')
            ->will($this->returnValue($requestData));

        $this->_taskTransformer
            ->expects($this->once())
            ->method('transformToModel')
            ->with($this->equalTo($requestData))
            ->will($this->returnValue(null));

        $this->_taskService
            ->expects($this->never())
            ->method('persistEntity');

        $this->_infoPresenter
            ->expects($this->once())
            ->method('render')
            ->with(
                $this->equalTo($response),
                $this->equalTo(422),
                $this->equalTo('JSON is in invalid data structure')
            )
            ->will($this->returnValue($response));

        $this->assertEquals(
            $response,
            $this->_object->addAction($request, $response, [])
        );
    }

    public function testDeleteAction()
    {
        $taskId = time();

        $request = $this
            ->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();
        $response = $this
            ->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_taskService
            ->expects($this->once())
            ->method('deleteById')
            ->with($this->equalTo($taskId));

        $this->_infoPresenter
            ->expects($this->once())
            ->method('render')
            ->with(
                $this->equalTo($response),
                $this->equalTo(200),
                $this->equalTo('Deletion successful')
            )
            ->will($this->returnValue($response));

        $this->assertEquals(
            $response,
            $this->_object->deleteAction($request, $response, ['taskId' => $taskId])
        );
    }

    public function testGetAction()
    {
        $taskId = time();

        $modelTask = new TaskModel();

        $request = $this
            ->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();
        $response = $this
            ->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_taskService
            ->expects($this->once())
            ->method('getById')
            ->with($this->equalTo($taskId))
            ->will($this->returnValue($modelTask));

        $this->_dataPresenter
            ->expects($this->once())
            ->method('render')
            ->with(
                $this->equalTo($response),
                $this->equalTo(200),
                $this->equalTo($modelTask)
            )
            ->will($this->returnValue($response));

        $this->assertEquals(
            $response,
            $this->_object->getAction($request, $response, ['taskId' => $taskId])
        );
    }

    public function testGetActionWithoutValidId()
    {
        $taskId = time();

        $request = $this
            ->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();
        $response = $this
            ->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_taskService
            ->expects($this->once())
            ->method('getById')
            ->with($this->equalTo($taskId))
            ->will($this->returnValue(null));

        $this->_infoPresenter
            ->expects($this->once())
            ->method('render')
            ->with(
                $this->equalTo($response),
                $this->equalTo(404),
                $this->equalTo('No Data with provided Key found')
            )
            ->will($this->returnValue($response));

        $this->assertEquals(
            $response,
            $this->_object->getAction($request, $response, ['taskId' => $taskId])
        );
    }

    public function testGetAllAction()
    {
        $modelTaskOne              = new TaskModel();
        $modelTaskOne->description = 'dsad';

        $modelTaskTwo              = new TaskModel();
        $modelTaskTwo->description = 'ssssss';

        $request = $this
            ->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();
        $response = $this
            ->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_taskService
            ->expects($this->once())
            ->method('getAll')
            ->will($this->returnValue([$modelTaskOne, $modelTaskTwo]));

        $this->_dataPresenter
            ->expects($this->once())
            ->method('render')
            ->with(
                $this->equalTo($response),
                $this->equalTo(200),
                $this->equalTo([$modelTaskOne, $modelTaskTwo])
            )
            ->will($this->returnValue($response));

        $this->assertEquals(
            $response,
            $this->_object->getAllAction($request, $response, [])
        );
    }

    public function testUpdateAction()
    {
        $taskId      = time();
        $requestData = (object) [
            'description' => 'bla',
        ];

        $modelTask         = new TaskModel();
        $modelTask->taskId = $taskId;

        $request = $this
            ->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();
        $response = $this
            ->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();

        $request
            ->expects($this->once())
            ->method('getParsedBody')
            ->will($this->returnValue($requestData));

        $this->_taskTransformer
            ->expects($this->once())
            ->method('transformToModel')
            ->with($this->equalTo($requestData))
            ->will($this->returnValue($modelTask));

        $this->_taskService
            ->expects($this->once())
            ->method('update')
            ->with($this->equalTo($taskId), $this->equalTo($modelTask))
            ->will($this->returnValue($modelTask));

        $this->_dataPresenter
            ->expects($this->once())
            ->method('render')
            ->with(
                $this->equalTo($response),
                $this->equalTo(202),
                $this->equalTo($modelTask)
            )
            ->will($this->returnValue($response));

        $this->assertEquals(
            $response,
            $this->_object->updateAction($request, $response, ['taskId' => $taskId])
        );
    }

    public function testUpdateActionWhenUpdateFails()
    {
        $taskId      = time();
        $requestData = (object) [
            'test' => 'bla',
        ];

        $modelTask         = new TaskModel();
        $modelTask->taskId = $taskId;

        $request = $this
            ->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();
        $response = $this
            ->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();

        $request
            ->expects($this->once())
            ->method('getParsedBody')
            ->will($this->returnValue($requestData));

        $this->_taskTransformer
            ->expects($this->once())
            ->method('transformToModel')
            ->with($this->equalTo($requestData))
            ->will($this->returnValue($modelTask));

        $this->_taskService
            ->expects($this->once())
            ->method('update')
            ->with($this->equalTo($taskId), $this->equalTo($modelTask))
            ->will($this->returnValue(null));

        $this->_infoPresenter
            ->expects($this->once())
            ->method('render')
            ->with(
                $this->equalTo($response),
                $this->equalTo(404),
                $this->equalTo('No Data with provided Key found')
            )
            ->will($this->returnValue($response));

        $this->assertEquals(
            $response,
            $this->_object->updateAction($request, $response, ['taskId' => $taskId])
        );
    }

    public function testUpdateActionWithInvalidData()
    {
        $taskId      = time();
        $requestData = (object) [
            'test' => 'bla',
        ];

        $request = $this
            ->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();
        $response = $this
            ->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();

        $request
            ->expects($this->once())
            ->method('getParsedBody')
            ->will($this->returnValue($requestData));

        $this->_taskTransformer
            ->expects($this->once())
            ->method('transformToModel')
            ->with($this->equalTo($requestData))
            ->will($this->returnValue(null));

        $this->_taskService
            ->expects($this->never())
            ->method('update');

        $this->_infoPresenter
            ->expects($this->once())
            ->method('render')
            ->with(
                $this->equalTo($response),
                $this->equalTo(422),
                $this->equalTo('JSON is in invalid data structure')
            )
            ->will($this->returnValue($response));

        $this->assertEquals(
            $response,
            $this->_object->updateAction($request, $response, ['taskId' => $taskId])
        );
    }
}

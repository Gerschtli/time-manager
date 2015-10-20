<?php

class TaskTest extends \LocalWebTestCase
{
    public function testaddAction()
    {
        $this->app->controllerTask = $this
            ->getMockBuilder('\TimeManager\Controller\Task')
            ->disableOriginalConstructor()
            ->getMock();
        $this->app->controllerTask
            ->expects($this->once())
            ->method('addAction');

        $data = (object)['test' => 'data'];
        $data = json_encode($data);
        $this->client->post('/task', $data, ['CONTENT_TYPE' => 'application/json']);
    }
}

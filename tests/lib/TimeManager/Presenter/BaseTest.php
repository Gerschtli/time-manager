<?php

namespace TimeManager\Presenter;

use ReflectionClass;

class BaseTest extends \LocalWebTestCase
{
    private $_object;

    public function setUp()
    {
        parent::setUp();
        $this->_object = new BaseWrapper($this->app);
    }

    public function testInstance()
    {
        $this->assertInstanceOf('\TimeManager\Presenter\Base', $this->_object);
        $this->assertInstanceOf('\TimeManager\AppAware', $this->_object);
    }

    public function testConstants()
    {
        $object = new ReflectionClass('\TimeManager\Presenter\Base');

        $this->assertEquals(
            [
                'STATUS_OK'                       => 200,
                'STATUS_CREATED'                  => 201,
                'STATUS_BAD_REQUEST'              => 400,
                'STATUS_NOT_FOUND'                => 404,
                'STATUS_UNSUPPORTED_MEDIA_TYPE'   => 415,
                'STATUS_UNPROCESSABLE_ENTITY'     => 422,
                'STATUS_INTERNAL_SERVER_ERROR'    => 500,
                'DESCRIPTION_SUCCESSFUL_DELETION' => 'Deletion successful',
                'DESCRIPTION_INVALID_STRUCTURE'   => 'JSON is in invalid data structure',
                'DESCRIPTION_NONEXISTING_KEY'     => 'No Data with provided Key found',
                'DESCRIPTION_ONLY_JSON'           => 'Only JSON is allowed',
                'DESCRIPTION_PARSE_ERROR'         => 'JSON Parse Error',
                'DESCRIPTION_NO_ROUTE_MATCHED'    => 'No existing Route matched',
            ],
            $object->getConstants()
        );
    }
}

class BaseWrapper extends Base
{}

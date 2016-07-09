<?php

namespace TimeManager\Presenter;

use ReflectionClass;

class PresenterTest extends \PHPUnit_Framework_TestCase
{
    public function testConstants()
    {
        $object = new ReflectionClass('\TimeManager\Presenter\Presenter');

        $this->assertEquals(
            [
                'STATUS_OK'                       => 200,
                'STATUS_CREATED'                  => 201,
                'STATUS_ACCEPTED'                 => 202,
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

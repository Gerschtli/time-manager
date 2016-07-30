<?php

namespace TimeManager\Presenter;

use ReflectionClass;

class PresenterTest extends \PHPUnit_Framework_TestCase
{
    public function testConstants()
    {
        $object = new ReflectionClass(Presenter::class);

        $this->assertEquals(
            [
                'STATUS_OK'                       => 200,
                'STATUS_CREATED'                  => 201,
                'STATUS_ACCEPTED'                 => 202,
                'STATUS_NOT_FOUND'                => 404,
                'STATUS_METHOD_NOT_ALLOWED'       => 405,
                'STATUS_UNPROCESSABLE_ENTITY'     => 422,
                'STATUS_INTERNAL_SERVER_ERROR'    => 500,
                'DESCRIPTION_SUCCESSFUL_DELETION' => 'Deletion successful',
                'DESCRIPTION_INVALID_STRUCTURE'   => 'JSON is in invalid data structure',
                'DESCRIPTION_NONEXISTING_KEY'     => 'No Data with provided Key found',
                'DESCRIPTION_NO_ROUTE_MATCHED'    => 'No existing Route matched',
                'DESCRIPTION_AVAILABLE_METHODS'   => 'Available Methods: %s',
            ],
            $object->getConstants()
        );
    }
}

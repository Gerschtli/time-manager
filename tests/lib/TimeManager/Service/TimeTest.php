<?php

namespace TimeManager\Service;

use DateTime;

class TimeTest extends \LocalWebTestCase
{
    private $_object;

    public function setUp()
    {
        parent::setUp();
        $this->_object = new Time($this->app);
    }

    public function testInstance()
    {
        $this->assertInstanceOf('\TimeManager\Service\Time', $this->_object);
        $this->assertInstanceOf('\TimeManager\AppAware', $this->_object);
    }

    public function testCreateModel()
    {
        $data = (object)[
            'start' => '2015-01-01 12:00:42',
            'end'   => '2015-01-05 12:00:42',
        ];

        $this->app->modelTime = new \stdClass();

        $this->app->dbal = $this
            ->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->dbal
            ->expects($this->at(0))
            ->method('persist')
            ->with($this->equalTo($this->app->modelTime));
        $this->app->dbal
            ->expects($this->at(1))
            ->method('flush');

        $expected = (object)[
            'start' => new \DateTime('2015-01-01 12:00:42'),
            'end'   => new \DateTime('2015-01-05 12:00:42'),
        ];

        $this->assertEquals(
            $expected,
            $this->_object->createModel($data)
        );
    }

    /**
     * @dataProvider dataProviderForTestCreateModelWithNoEnd
     */
    public function testCreateModelWithNoEnd($data, $expected)
    {
        $this->app->modelTime = new \stdClass();

        $this->app->dbal = $this
            ->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->dbal
            ->expects($this->at(0))
            ->method('persist')
            ->with($this->equalTo($this->app->modelTime));
        $this->app->dbal
            ->expects($this->at(1))
            ->method('flush');

        $this->assertEquals(
            $expected,
            $this->_object->createModel($data)
        );
    }

    public function dataProviderForTestCreateModelWithNoEnd()
    {
        return [
            [
                (object)[
                    'start' => '2015-01-01 12:00:42',
                ],
                (object)[
                    'start' => new \DateTime('2015-01-01 12:00:42'),
                ],
            ],
            [
                (object)[
                    'start' => '2015-01-01 12:00:42',
                    'end'   => null,
                ],
                (object)[
                    'start' => new \DateTime('2015-01-01 12:00:42'),
                ],
            ],
            [
                (object)[
                    'start' => '2015-01-01 12:00:42',
                    'end'   => 5,
                ],
                (object)[
                    'start' => new \DateTime('2015-01-01 12:00:42'),
                ],
            ],
            [
                (object)[
                    'start' => '2015-01-01 12:00:42',
                    'end'   => '2015-15-40 25:61:123',
                ],
                (object)[
                    'start' => new \DateTime('2015-01-01 12:00:42'),
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForTestCreateModelWithInvalidData
     */
    public function testCreateModelWithInvalidData($data)
    {
        $this->assertNull($this->_object->createModel($data));
    }

    public function dataProviderForTestCreateModelWithInvalidData()
    {
        return [
            [
                (object)[],
            ],
            [
                (object)[
                    'test' => 456,
                ],
            ],
            [
                (object)[
                    'start' => null,
                ],
            ],
            [
                (object)[
                    'start' => 5,
                ],
            ],
            [
                (object)[
                    'start' => '2015-15-40 25:61:123',
                ],
            ],
        ];
    }
}

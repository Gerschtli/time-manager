<?php

namespace TimeManager\Service;

use DateTime;
use TimeManager\Model\Time as TimeModel;

class TimeTest extends \PHPUnit_Framework_TestCase
{
    private $_object;
    private $_entityManager;

    public function setUp()
    {
        parent::setUp();

        $this->_entityManager = $this
            ->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->_object = new Time($this->_entityManager);
    }

    /**
     * @dataProvider dataProviderForTestConvertToEntity
     */
    public function testConvertToEntity($data, $expected)
    {
        $this->assertEquals(
            $expected,
            $this->_object->convertToEntity($data)
        );
    }

    public function dataProviderForTestConvertToEntity()
    {
        $timeOnlyStart = new TimeModel();
        $timeOnlyStart->start = new DateTime('2015-01-01 12:00:42');

        $timeStartEnd = new TimeModel();
        $timeStartEnd->start = new DateTime('2015-01-01 12:00:42');
        $timeStartEnd->end   = new DateTime('2015-01-01 14:00:42');

        return [
            [
                (object)[
                    'start' => '2015-01-01 12:00:42',
                    'end'   => '2015-01-01 14:00:42',
                ],
                $timeStartEnd,
            ],
            [
                (object)[
                    'start' => '2015-01-01 12:00:42',
                ],
                $timeOnlyStart,
            ],
            [
                (object)[
                    'start' => '2015-01-01 12:00:42',
                    'end'   => null,
                ],
                $timeOnlyStart,
            ],
            [
                (object)[
                    'start' => '2015-01-01 12:00:42',
                    'end'   => 5,
                ],
                $timeOnlyStart,
            ],
            [
                (object)[
                    'start' => '2015-01-01 12:00:42',
                    'end'   => '2015-15-40 25:61:123',
                ],
                $timeOnlyStart,
            ],
            [
                (object)[],
                null,
            ],
            [
                (object)[
                    'test' => 456,
                ],
                null,
            ],
            [
                (object)[
                    'start' => null,
                ],
                null,
            ],
            [
                (object)[
                    'start' => 5,
                ],
                null,
            ],
            [
                (object)[
                    'start' => '2015-15-40 25:61:123',
                ],
                null,
            ],
        ];
    }

    public function testPersistEntity()
    {
        $modelTime = new TimeModel();
        $modelTime->start = '2015-01-01 12:00:42';

        $this->_entityManager
            ->expects($this->at(0))
            ->method('persist')
            ->with($this->equalTo($modelTime));
        $this->_entityManager
            ->expects($this->at(1))
            ->method('flush');

        $this->_object->persistEntity($modelTime);
    }
}

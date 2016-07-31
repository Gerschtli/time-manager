<?php

namespace TimeManager\Util;

use DateTime;

class DateTest extends \PHPUnit_Framework_TestCase
{
    private $_object;

    public function setUp()
    {
        parent::setUp();

        $this->_object = new Date();
    }

    public function testConvertToObject()
    {
        $this->assertEquals(
            new DateTime('2015-07-31 14:15:43'),
            $this->_object->convertToObject('2015-07-31 14:15:43')
        );
    }

    /**
     * @dataProvider dataProviderForTestConvertToObjectWithInvalidDate
     */
    public function testConvertToObjectWithInvalidDate($date)
    {
        $this->assertNull($this->_object->convertToObject($date));
    }

    public function dataProviderForTestConvertToObjectWithInvalidDate()
    {
        return [
            ['2015-07-32'],
            [''],
            [null],
        ];
    }

    public function testFormat()
    {
        $date = new DateTime('2015-07-31 14:15:43.42');

        $this->assertEquals(
            '2015-07-31 14:15:43',
            $this->_object->format($date)
        );
    }
}

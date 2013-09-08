<?php
/**
 * Tests for the compatibility library with PHP 5.2+ DateTimeZone class
 *
 * @author    Jason Varnedoe <jason@fuzzystatic.com>
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Jason Varnedoe
 */

class DateTimeZoneCompatTest extends PHPUnit_Framework_TestCase {

    protected $sys_tz;
    protected $invalid;
    protected $valid = array();

    protected function setUp() {
        if (ini_get('date.timezone')) {
            $this->sys_tz = ini_get('date.timezone');
        }
        else {
            $this->sys_tz = date_default_timezone_get();
        }

        $this->invalid = 'Murica/Texas';

        $this->valid['construct'] = array(
            'name'       => 'America/Chicago',
            'offset_dst' => '-18000',
            'offset'     => '-21600'
        );
    }

    /**
     * @test
     * @covers DateTimeZoneCompat::__construct
     */
    public function constructReturnsDateTimeCompat() {
        $this->assertInstanceOf("DateTimeZoneCompat", new DateTimeZoneCompat($this->valid['construct']['name']));
    }

    /**
     * @test
     * @covers DateTimeZoneCompat::__construct
     * @expectedException Exception
     */
    public function constructThrowsExceptionOnInvalid() {
        $dummy = new DateTimeZoneCompat($this->invalid);
    }

    /**
     * @test
     * @covers DateTimeZoneCompat::getName
     */
    public function getNameReturnsExpcected() {
        $dummy = new DateTimeZoneCompat($this->valid['construct']['name']);
        $this->assertEquals($this->valid['construct']['name'], $dummy->getName());
    }

    /**
     * @test
     * @covers DateTimeZoneCompat::getOffset
     */
    public function getOffsetReturnsExpcected() {
        $dummy = new DateTimeZoneCompat($this->valid['construct']['name']);
        $time = new DateTimeCompat();

        /** for this to work, must set to the expected timezone ourselves and see if we are in DST */
        date_default_timezone_set($this->valid['construct']['name']);
        if (date('I')) {
            $offset = $this->valid['construct']['offset_dst'];
        }
        else {
            $offset = $this->valid['construct']['offset'];
        }
        date_default_timezone_set($this->sys_tz);


        $this->assertEquals($offset, $dummy->getOffset($time));
    }

}

<?php
/**
 * Tests for the compatibility library with PHP 5.2+ DateTimeZone class
 *
 * @author Jason Varnedoe <jason@fuzzystatic.com>
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Jason Varnedoe
 */

class DateTimeZoneCompatTest extends PHPUnit_Framework_TestCase {

    /**
     * @test
     * @covers DateTimeZoneCompat::__construct
     */
    public function constructReturnsDateTimeCompat() {
        $this->assertInstanceOf("DateTimeZoneCompat", new DateTimeZoneCompat());
    }

}

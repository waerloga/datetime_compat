<?php
/**
 * Tests for the compatibility library with PHP 5.2+ DateTime class
 *
 * @author Jason Varnedoe <jason@fuzzystatic.com>
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Jason Varnedoe
 */

class DateTimeCompatTest extends PHPUnit_Framework_TestCase {

    protected $timestamp;
    protected $invalid;

    protected function setUp() {
        /** @var int timestamp */
        $this->timestamp = time();
        /** @var string invalid */
        $this->invalid = "NOTADATE";
    }

    /**
     * @test
     * @covers DateTimeCompat::__construct
     */
    public function constructReturnsDateTimeCompat() {
        $dummy = new DateTimeCompat();
        $this->assertInstanceOf("DateTimeCompat", $dummy);
    }

    /**
     * @test
     * @covers DateTimeCompat::__construct
     * @expectedException Exception
     */
    public function constructReturnsExceptionOnUnParsable() {
        $dummy = new DateTimeCompat($this->invalid);
    }


}

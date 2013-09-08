<?php
/**
 * Tests for the compatibility library with PHP 5.2+ DateTime class
 *
 * @author Jason Varnedoe <jason@fuzzystatic.com>
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Jason Varnedoe
 */

class DateTimeCompatTest extends PHPUnit_Framework_TestCase {

    /** @var string */
    protected $invalid;

    /** @var array */
    protected $data = array();

    protected function setUp() {
        $this->invalid = "NOTADATE";
        $this->data['formattest'] = array(
            'timestamp' => 1377835200,
            'format'    => 'Y-m-d H:i:s',
            'date'      => '2013-08-30 00:00:00'
        );
        $this->data['modifytest1'] = array(
            'timestamp' => 1377835200,
            'startdate' => '2013-08-30 00:00:00',
            'format'    => 'Y-m-d H:i:s',
            'modify'    => '+2 day',
            'date'      => '2013-09-01 00:00:00'
        );
        $this->data['modifytest2'] = array(
            'timestamp' => 1377835200,
            'startdate' => '2013-08-30 00:00:00',
            'format'    => 'Y-m-d H:i:s',
            'modify'    => '-1 day',
            'date'      => '2013-08-29 00:00:00'
        );
    }

    /**
     * @test
     * @covers DateTimeCompat::__construct
     */
    public function constructReturnsDateTimeCompat() {
        $this->assertInstanceOf("DateTimeCompat", new DateTimeCompat());
    }

    /**
     * @test
     * @covers DateTimeCompat::__construct
     * @expectedException Exception
     */
    public function constructReturnsExceptionOnUnParsable() {
        $dummy = new DateTimeCompat($this->invalid);
    }

    /**
     * @test
     * @covers DateTimeCompat::format
     */
    public function formatReturnsDateTimeString() {
        $dummy = new DateTimeCompat($this->data['formattest']['date']);
        $this->assertEquals($this->data['formattest']['date'],$dummy->format($this->data['formattest']['format']));
    }

    /**
     * @test
     * @covers DateTimeCompat::modify
     */
    public function modifyReturnsFalseOnInvalid() {
        $dummy = new DateTimeCompat($this->data['modifytest1']['startdate']);
        $this->assertFalse($dummy->modify($this->invalid));
    }

    /**
     * @test
     * @covers DateTimeCompat::modify
     */
    public function modifyReturnsObjectOnValid() {
        $dummy = new DateTimeCompat($this->data['modifytest1']['startdate']);
        $this->assertInstanceOf("DateTimeCompat", $dummy->modify($this->data['modifytest1']['modify']));
    }

    /**
     * @test
     * @covers DateTimeCompat::modify
     */
    public function modifyAddsProperly() {
        $dummy = new DateTimeCompat($this->data['modifytest1']['startdate']);
        $dummy->modify($this->data['modifytest1']['modify']);
        $this->assertEquals($this->data['modifytest1']['date'],$dummy->format($this->data['modifytest1']['format']));
    }

    /**
     * @test
     * @covers DateTimeCompat::modify
     */
    public function modifySubsProperly() {
        $dummy = new DateTimeCompat($this->data['modifytest2']['startdate']);
        $dummy->modify($this->data['modifytest2']['modify']);
        $this->assertEquals($this->data['modifytest2']['date'],$dummy->format($this->data['modifytest2']['format']));
    }

}

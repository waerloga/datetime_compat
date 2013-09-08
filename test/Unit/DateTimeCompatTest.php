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
        $this->data['diff'] = array(
            'a'         => '2013-08-30 00:00:00',
            'b'         => '2013-08-30 00:00:10',
            'diff-sec'  => 10,
            'diff-else' => 0
        );

        $this->data['add'] = array(
            'startdate' => '2013-08-30 00:00:00',
            'enddate'   => '2013-08-30 00:00:20',
            'format'    => 'Y-m-d H:i:s',
            'interval'  => 'PT20S'
        );

        $this->data['sub'] = array(
            'startdate' => '2013-08-30 00:00:20',
            'enddate'   => '2013-08-30 00:00:00',
            'format'    => 'Y-m-d H:i:s',
            'interval'  => 'PT20S'
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

    /**
     * @test
     * @covers DateTimeCompat::diff
     */
    public function diffReturnsDateIntervalCompat() {
        $a = new DateTimeCompat($this->data['diff']['a']);
        $b = new DateTimeCompat($this->data['diff']['b']);
        $interval = $a->diff($b);
        $this->assertEquals($this->data['diff']['diff-else'], $interval->y);
        $this->assertEquals($this->data['diff']['diff-else'], $interval->m);
        $this->assertEquals($this->data['diff']['diff-else'], $interval->d);
        $this->assertEquals($this->data['diff']['diff-else'], $interval->h);
        $this->assertEquals($this->data['diff']['diff-else'], $interval->i);
        $this->assertEquals($this->data['diff']['diff-sec'], $interval->s);

    }

    /**
     * @test
     * @covers DateTimeCompat::add
     */
    public function addReturnsCorrectDateTimeCompat() {
        $dummy = new DateTimeCompat($this->data['add']['startdate']);
        $interval = new DateIntervalCompat($this->data['add']['interval']);
        $dummy->add($interval);
        $this->assertEquals($this->data['add']['enddate'], $dummy->format($this->data['add']['format']));
    }

    /**
     * @test
     * @covers DateTimeCompat::sub
     */
    public function subReturnsCorrectDateTimeCompat() {
        $dummy = new DateTimeCompat($this->data['sub']['startdate']);
        $interval = new DateIntervalCompat($this->data['sub']['interval']);
        $dummy->sub($interval);
        $this->assertEquals($this->data['sub']['enddate'], $dummy->format($this->data['sub']['format']));
    }

}

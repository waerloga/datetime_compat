<?php
/**
 * Tests for the compatibility library with PHP 5.2+ DateTime class
 *
 * @author    Jason Varnedoe <jason@fuzzystatic.com>
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
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
            'startdate' => '2013-08-30 00:00:00-0500',
            'format'    => 'Y-m-d H:i:s',
            'modify'    => '+2 day',
            'date'      => '2013-09-01 00:00:00'
        );
        $this->data['modifytest2'] = array(
            'timestamp' => 1377835200,
            'startdate' => '2013-08-30 00:00:00Z',
            'format'    => 'Y-m-d H:i:s',
            'modify'    => '-1 day',
            'date'      => '2013-08-29 00:00:00'
        );
        $this->data['diff'] = array(
            'a'           => '2013-08-30 00:01:00-0500',
            'b'           => '2013-08-30 00:00:10-06:00',
            'diff-sec'    => 10,
            'diff-min'    => 59,
            'diff-else'   => 0,
            'diff-invert' => 0
        );

        $this->data['add'] = array(
            'startdate' => '2013-08-30 00:00:00+07:00',
            'enddate'   => '2013-09-30 00:10:20',
            'format'    => 'Y-m-d H:i:s',
            'interval'  => 'P1MT10M20S'
        );

        $this->data['sub'] = array(
            'startdate' => '2013-08-30 00:00:20',
            'enddate'   => '2013-08-29 00:00:00',
            'format'    => 'Y-m-d H:i:s',
            'interval'  => 'P1DT20S'
        );

        $this->data['setdate'] = array(
            'startdate' => '2013-08-30 00:10:00+06:00',
            'enddate'   => '2013-08-09 00:10:00',
            'format'    => 'Y-m-d H:i:s',
            'set-year'  => 2013,
            'set-month' => 8,
            'set-day'   => 9
        );

        $this->data['settime'] = array(
            'startdate' => '2013-08-30 00:10:00',
            'enddate'   => '2013-08-30 01:15:09',
            'format'    => 'Y-m-d H:i:s',
            'set-hour'  => 1,
            'set-min'   => 15,
            'set-sec'   => 9
        );

        $this->data['offset'] = array(
            'datetime' => '2013-08-30 00:00:00',
            'timezone' => 'America/Chicago',
            'offset'   => '-18000'
        );

        $this->data['settz'] = array(
            'datetime' => '2013-08-30 00:00:00',
            'timezone' => 'UTC',
            'newtz'    => 'America/Chicago',
            'result'   => '2013-08-29 19:00:00',
            'format'   => 'Y-m-d H:i:s'
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
        $this->assertEquals($this->data['formattest']['date'], $dummy->format($this->data['formattest']['format']));
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
        $this->assertEquals($this->data['modifytest1']['date'], $dummy->format($this->data['modifytest1']['format']));
    }

    /**
     * @test
     * @covers DateTimeCompat::modify
     */
    public function modifySubsProperly() {
        $dummy = new DateTimeCompat($this->data['modifytest2']['startdate']);
        $dummy->modify($this->data['modifytest2']['modify']);
        $this->assertEquals($this->data['modifytest2']['date'], $dummy->format($this->data['modifytest2']['format']));
    }

    /**
     * @test
     * @covers DateTimeCompat::diff
     */
    public function diffReturnsDateIntervalCompat() {
        $a = new DateTimeCompat($this->data['diff']['a']);
        $b = new DateTimeCompat($this->data['diff']['b']);
        $interval = $a->diff($b);
        $this->assertEquals($this->data['diff']['diff-else'], $interval->y, 'Failed Years');
        $this->assertEquals($this->data['diff']['diff-else'], $interval->m, 'Failed Months');
        $this->assertEquals($this->data['diff']['diff-else'], $interval->d, 'Failed Days');
        $this->assertEquals($this->data['diff']['diff-else'], $interval->h, 'Failed Hours');
        $this->assertEquals($this->data['diff']['diff-min'], $interval->i, 'Failed Minutes');
        $this->assertEquals($this->data['diff']['diff-sec'], $interval->s, 'Failed Seconds');
        $this->assertEquals($this->data['diff']['diff-invert'], $interval->invert, 'Failed Invert');

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

    /**
     * @test
     * @covers DateTimeCompat::setDate
     */
    public function setDateReturnsExpectedDateTimeCompat() {
        $dummy = new DateTimeCompat($this->data['setdate']['startdate']);
        $dummy->setDate($this->data['setdate']['set-year'], $this->data['setdate']['set-month'], $this->data['setdate']['set-day']);
        $this->assertEquals($this->data['setdate']['enddate'], $dummy->format($this->data['setdate']['format']));
    }

    /**
     * @test
     * @covers DateTimeCompat::setTime
     */
    public function setTimeReturnsExpectedDateTimeCompat() {
        $dummy = new DateTimeCompat($this->data['settime']['startdate']);
        $dummy->setTime($this->data['settime']['set-hour'], $this->data['settime']['set-min'], $this->data['settime']['set-sec']);
        $this->assertEquals($this->data['settime']['enddate'], $dummy->format($this->data['settime']['format']));
    }

    /**
     * @test
     * @covers DateTimeCompat::getOffset
     */
    public function getOffsetReturnsExpeted() {
        $dummy = new DateTimeCompat($this->data['offset']['datetime'], new DateTimeZoneCompat($this->data['offset']['timezone']));
        $this->assertEquals($this->data['offset']['offset'], $dummy->getOffset());
    }

    /**
     * @test
     * @covers DateTimeCompat::setTimezone
     */
    public function changeTzReturnsExpected() {
        $dummy = new DateTimeCompat($this->data['settz']['datetime'], new DateTimeZoneCompat($this->data['settz']['timezone']));
        $dummy->setTimezone(new DateTimeZoneCompat($this->data['settz']['newtz']));
        $this->assertEquals($this->data['settz']['result'], $dummy->format($this->data['settz']['format']));
    }
}

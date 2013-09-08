<?php
/**
 * Tests for the compatibility library with PHP 5.3+ DateInterval class
 *
 * @author Jason Varnedoe <jason@fuzzystatic.com>
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Jason Varnedoe
 */

class DateIntervalCompatTest extends PHPUnit_Framework_TestCase {

    protected $valid = array();
    protected $invalid;


    public function setUp() {
        $this->valid['full'] = array (
            'interval' => 'P2Y3M4DT6H8M3S',
            'year'     => 2,
            'month'    => 3,
            'day'      => 4,
            'hour'     => 6,
            'minute'   => 8,
            'second'   => 3
        );

        $this->valid['part'] = array (
            'interval' => 'P2Y4MT6H3S',
            'year'     => 2,
            'month'    => 4,
            'day'      => 0,
            'hour'     => 6,
            'minute'   => 0,
            'second'   => 3
        );

        $this->valid['weekandday'] = array(
            'interval' => 'P1W3D',
            'year'     => 0,
            'month'    => 0,
            'day'      => 3,
            'hour'     => 0,
            'minute'   => 0,
            'second'   => 0
        );

        $this->valid['week'] = array(
            'interval' => 'P1W',
            'year'     => 0,
            'month'    => 0,
            'day'      => 7,
            'hour'     => 0,
            'minute'   => 0,
            'second'   => 0
        );

        $this->valid['timeonly'] = array(
            'interval' => 'PT2H',
            'year'     => 0,
            'month'    => 0,
            'day'      => 0,
            'hour'     => 2,
            'minute'   => 0,
            'second'   => 0
        );

        $this->valid['datestring'] = array(
            'interval' => '-3 day',
            'year'     => 0,
            'month'    => 0,
            'day'      => 0,
            'hour'     => 0,
            'minute'   => 0,
            'second'   => 259200
        );

        $this->invalid = "NOTVALID";
    }

    /**
     * @test
     * @covers DateIntervalCompat::__construct
     */
    public function constructReturnsDateIntervalCompat() {
        $dummy = new DateIntervalCompat($this->valid['full']['interval']);
        $this->assertInstanceOf("DateIntervalCompat", $dummy);
    }

    /**
     * @test
     * @covers DateIntervalCompat::__construct
     * @expectedException Exception
     */
    public function constructThrowsExceptionOnNonParsable() {
        $dummy = new DateIntervalCompat($this->invalid);

    }

    /**
     * @test
     * @covers DateIntervalCompat::__construct
     */
    public function constructReturnsExcpectedObject() {
        $dummy = new DateIntervalCompat($this->valid['full']['interval']);
        $this->assertEquals($this->valid['full']['year'], $dummy->y);
        $this->assertEquals($this->valid['full']['month'], $dummy->m);
        $this->assertEquals($this->valid['full']['day'], $dummy->d);
        $this->assertEquals($this->valid['full']['hour'], $dummy->h);
        $this->assertEquals($this->valid['full']['minute'], $dummy->i);
        $this->assertEquals($this->valid['full']['second'], $dummy->s);
    }

    /**
     * @test
     * @covers DateIntervalCompat::__construct
     */
    public function constructPartialReturnsExcpectedObject() {
        $dummy = new DateIntervalCompat($this->valid['part']['interval']);
        $this->assertEquals($this->valid['part']['year'], $dummy->y);
        $this->assertEquals($this->valid['part']['month'], $dummy->m);
        $this->assertEquals($this->valid['part']['day'], $dummy->d);
        $this->assertEquals($this->valid['part']['hour'], $dummy->h);
        $this->assertEquals($this->valid['part']['minute'], $dummy->i);
        $this->assertEquals($this->valid['part']['second'], $dummy->s);
    }

    /**
     * @test
     * @covers DateIntervalCompat::__construct
     */
    public function constructMixedReturnsExcpectedObject() {
        $dummy = new DateIntervalCompat($this->valid['weekandday']['interval']);
        $this->assertEquals($this->valid['weekandday']['year'], $dummy->y);
        $this->assertEquals($this->valid['weekandday']['month'], $dummy->m);
        $this->assertEquals($this->valid['weekandday']['day'], $dummy->d);
        $this->assertEquals($this->valid['weekandday']['hour'], $dummy->h);
        $this->assertEquals($this->valid['weekandday']['minute'], $dummy->i);
        $this->assertEquals($this->valid['weekandday']['second'], $dummy->s);
    }

    /**
     * @test
     * @covers DateIntervalCompat::__construct
     */
    public function constructWeekReturnsExcpectedObject() {
        $dummy = new DateIntervalCompat($this->valid['week']['interval']);
        $this->assertEquals($this->valid['week']['year'], $dummy->y);
        $this->assertEquals($this->valid['week']['month'], $dummy->m);
        $this->assertEquals($this->valid['week']['day'], $dummy->d);
        $this->assertEquals($this->valid['week']['hour'], $dummy->h);
        $this->assertEquals($this->valid['week']['minute'], $dummy->i);
        $this->assertEquals($this->valid['week']['second'], $dummy->s);
    }

    /**
     * @test
     * @covers DateIntervalCompat::__construct
     */
    public function construtTimeReturnsExcpectedObject() {
        $dummy = new DateIntervalCompat($this->valid['timeonly']['interval']);
        $this->assertEquals($this->valid['timeonly']['year'], $dummy->y);
        $this->assertEquals($this->valid['timeonly']['month'], $dummy->m);
        $this->assertEquals($this->valid['timeonly']['day'], $dummy->d);
        $this->assertEquals($this->valid['timeonly']['hour'], $dummy->h);
        $this->assertEquals($this->valid['timeonly']['minute'], $dummy->i);
        $this->assertEquals($this->valid['timeonly']['second'], $dummy->s);
    }

    /**
     * @test
     * @covers DateIntervalCompat::createFromDateString
     */
    public function createFromDateStringInvalidReturnsExcpectedObject() {
        $dummy = DateIntervalCompat::createFromDateString($this->invalid);
        $this->assertEquals(0, $dummy->y);
        $this->assertEquals(0, $dummy->m);
        $this->assertEquals(0, $dummy->d);
        $this->assertEquals(0, $dummy->h);
        $this->assertEquals(0, $dummy->i);
        $this->assertEquals(0, $dummy->s);
    }

    /**
     * @test
     * @covers DateIntervalCompat::createFromDateString
     */
    public function createFromDateStringReturnsExcpectedObject() {
        $dummy = DateIntervalCompat::createFromDateString($this->valid['datestring']['interval']);
        $this->assertEquals($this->valid['datestring']['year'], $dummy->y);
        $this->assertEquals($this->valid['datestring']['month'], $dummy->m);
        $this->assertEquals($this->valid['datestring']['day'], $dummy->d);
        $this->assertEquals($this->valid['datestring']['hour'], $dummy->h);
        $this->assertEquals($this->valid['datestring']['minute'], $dummy->i);
        $this->assertEquals($this->valid['datestring']['second'], $dummy->s);
    }

    /**
     * @test
     * @covers DateIntervalCompat::format
     */
    public function formatReturnsExpectedStrings() {
        $dummy = new DateIntervalCompat($this->valid['part']['interval']);
        $this->assertSame((string)$this->valid['part']['year'], $dummy->format('%y'));
        $this->assertSame('0' . $this->valid['part']['year'], $dummy->format('%Y'));
        $this->assertSame((string)$this->valid['part']['month'], $dummy->format('%m'));
        $this->assertSame('0' . $this->valid['part']['month'], $dummy->format('%M'));
        $this->assertSame($this->valid['part']['day'] . ' ' . $this->valid['part']['hour'], $dummy->format('%d %h'));
        $this->assertSame('0' . $this->valid['part']['day'] . ' 0' . $this->valid['part']['hour'], $dummy->format('%D %H'));
        $this->assertSame((string)$this->valid['part']['minute'], $dummy->format('%i'));
        $this->assertSame('0' . $this->valid['part']['minute'], $dummy->format('%I'));
        $this->assertSame((string)$this->valid['part']['second'], $dummy->format('%s'));
        $this->assertSame('0' . $this->valid['part']['second'], $dummy->format('%S'));
        $this->assertSame('(unknown)', $dummy->format('%a'));
        $this->assertSame('+', $dummy->format('%R'));
        $this->assertSame('', $dummy->format('%r'));
    }
}

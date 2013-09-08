<?php
/**
 * Created by IntelliJ IDEA.
 * User: jason
 * Date: 9/7/13
 * Time: 7:27 PM
 * To change this template use File | Settings | File Templates.
 */

class DateIntervalCompatTest extends PHPUnit_Framework_TestCase {

    protected $valid;
    protected $invalid;


    public function setUp() {
        /** @var string valid */
        $this->valid = "P2Y4DT6H8M"; /** 2years 4days 6hours 8minutes */

        $this->invalid = "NOTVALID";
    }

    /**
     * @test
     * @covers DateIntervalCompat::__construct
     */
    public function constructReturnsDateTimeCompat() {
        $dummy = new DateIntervalCompat();
        $this->assertInstanceOf("DateIntervalCompat", $dummy);
    }

}

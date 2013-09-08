<?php
/**
 * A compatibility library with PHP 5.2+ DateTime class
 *
 * @author Jason Varnedoe <jason@fuzzystatic.com>
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Jason Varnedoe
 */

class DateTimeCompat {

    /** @var int */
    protected $timestamp;

    /**
     * Returns a new DateTimeCompat object
     *
     * @param string $input
     * @param DateTimeZoneCompat $timezone
     * @throws Exception
     * @returns DateTimeCompat
     */
    public function __construct($input = "now", DateTimeZoneCompat $timezone = null) {
        $this->timestamp = strtotime($input);
        if($this->timestamp === false || $this->timestamp == -1) {
            throw new Exception(__CLASS__."::".__METHOD__.": Failed to parse time string (".$input.")");
        }
        return $this;
    }

    /**
     * Returns date formatted according to a given format
     * @param string $input
     * @return string|boolean
     */
    public function format($input) {
        return date($input, $this->timestamp);
    }


    public function modify($input) {
        $temp = strtotime($input, $this->timestamp);
        if($temp === false || $temp == -1) {
            return false;
        } else {
            $this->timestamp = $temp;
        }

        return $this;
    }

    public function add() {

    }

    public function sub() {

    }

    public function diff() {

    }

    public function getTimestamp() {

    }



}
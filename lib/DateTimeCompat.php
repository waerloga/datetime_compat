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

    /**
     * Adds an amount of days, months, years, hours, minutes, and seconds to a DateTimeCompat object
     * FIXME: Refactor to handle more than just seconds
     *
     * @param DateIntervalCompat $input
     * @return DateTimeCompat|boolean
     */
    public function add(DateIntervalCompat $input) {

        if($input->invert) {
            $m = -1;
        } else {
            $m = 1;
        }

        $this->timestamp += ($m *$input->s);
        return $this;
    }

    /**
     * Subtracts an amount of days, months, years, hours, minutes, and seconds from a DateTimeCompat object
     *
     * @param DateIntervalCompat $input
     * @return DateTimeCompat|boolean
     */
    public function sub($input) {
        if($input->invert) {
            $input->invert = 0;
        } else {
            $input->invert = 1;
        }
        return $this->add($input);
    }

    /**
     * Returns the difference between two DateTimeCompat objects
     * FIXME: Refactor to deal with something other than just seconds.
     *
     * @param DateTimeCompat $input
     * @param bool $absolute
     * @return DateIntervalCompat
     */
    public function diff(DateTimeCompat $input, $absolute = false) {
        $temp = abs($input->getTimestamp() - $this->getTimestamp());
        $temp = new DateIntervalCompat('PT' . $temp . 'S');
        if($absolute) {
            $temp->invert = 1;
        }
        return $temp;
    }

    public function getTimestamp() {
        return $this->timestamp;
    }



}
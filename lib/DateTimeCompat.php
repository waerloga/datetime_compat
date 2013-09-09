<?php
/**
 * A compatibility library with PHP 5.2+ DateTime class
 *
 * @author    Jason Varnedoe <jason@fuzzystatic.com>
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Jason Varnedoe
 */

class DateTimeCompat {

    /** @var string */
    public $date;
    protected $sec_in_day = 86400;
    protected $intervals = array('year', 'month', 'day', 'hour', 'minute', 'second');
    /** @var string */
    protected $system_tz;
    /** @var DateTimeZoneCompat */
    protected $timezone;
    /** @var int */
    protected $timestamp;

    protected $tz_list = array(
        14  => 'Etc/GMT-14',
        13  => 'Etc/GMT-13',
        12  => 'Etc/GMT-12',
        11  => 'Etc/GMT-11',
        10  => 'Etc/GMT-10',
        9   => 'Etc/GMT-9',
        8   => 'Etc/GMT-8',
        7   => 'Etc/GMT-7',
        6   => 'Etc/GMT-6',
        5   => 'Etc/GMT-5',
        4   => 'Etc/GMT-4',
        3   => 'Etc/GMT-3',
        2   => 'Etc/GMT-2',
        1   => 'Etc/GMT-1',
        0   => 'Etc/GMT-0',
        -0   => 'Etc/GMT+0',
        -1   => 'Etc/GMT+1',
        -2   => 'Etc/GMT+2',
        -3   => 'Etc/GMT+3',
        -4   => 'Etc/GMT+4',
        -5   => 'Etc/GMT+5',
        -6   => 'Etc/GMT+6',
        -7   => 'Etc/GMT+7',
        -8   => 'Etc/GMT+8',
        -9   => 'Etc/GMT+9',
        -10  => 'Etc/GMT+10',
        -11  => 'Etc/GMT+11',
        -12  => 'Etc/GMT+12'
    );

    /**
     * Returns a new DateTimeCompat object
     *
     * @param string             $input
     * @param DateTimeZoneCompat $timezone
     *
     * @throws Exception
     * @returns DateTimeCompat
     * @todo Better TZ handling
     */
    public function __construct($input = "now", DateTimeZoneCompat $timezone = null) {
        if (ini_get('date.timezone')) {
            $this->system_tz = ini_get('date.timezone');
        } else {
            $this->system_tz = date_default_timezone_get();
        }

        if ($timezone === null) {
            $tz_str = $this->tzFromString($input);
            if($tz_str === '') {
                $this->setTimezone(new DateTimeZoneCompat($this->system_tz));
            } else {
                $this->setTimezone(new DateTimeZoneCompat($tz_str));
            }
        } else {
            $this->setTimezone($timezone);
        }

        if (!$this->strtotime($input)) {
            throw new Exception(__CLASS__ . "::" . __METHOD__ . ": Failed to parse time string (" . $input . ")");
        }

        return $this;
    }

    /**
     * Helper function for refactorability
     *
     * @param string   $input
     * @param null|int $now
     *
     * @return bool
     */
    protected function strtotime($input, $now = null) {
        date_default_timezone_set($this->getTimezone()->getName());
        if ($now === null) {
            $now = time();
        }
        $temp = strtotime($input, $now);
        date_default_timezone_set($this->system_tz);
        if ($temp === false || $temp === -1) {
            return false;
        }
        $this->setTimestamp($temp);

        return true;
    }

    /**
     * Returns a new DateTimeCompat object formatted according to the specified format
     *
     * @todo make it go
     */
    public static function createFromFormat() {
    }

    /**
     * Returns the warnings and errors
     *
     * @todo make it go
     */
    public static function getLastErrors() {
    }

    /**
     * Returns date formatted according to a given format
     *
     * @param string $input
     *
     * @return string|boolean
     */
    public function format($input) {
        date_default_timezone_set($this->getTimezone()->getName());
        $rtrn = date($input, $this->getTimestamp());
        date_default_timezone_set($this->system_tz);

        return $rtrn;
    }

    /**
     * Gets the timezone of the DateTimeCompat object
     *
     * @return DateTimeZoneCompat
     */
    public function getTimezone() {
        return $this->timezone;
    }

    /**
     * Sets the timezone of the DateTimeCompat object
     *
     * @param DateTimeZoneCompat $input
     *
     * @return $this
     */
    public function setTimezone(DateTimeZoneCompat $input) {
        $this->timezone = $input;
        $this->setTimestamp($this->getTimestamp());

        return $this;
    }

    /**
     * Gets the Unix timestamp
     *
     * @return int
     */
    public function getTimestamp() {
        return $this->timestamp;
    }

    /**
     * Sets the date and time based on an Unix timestamp
     *
     * @param int $input
     *
     * @return DateTimeCompat|bool
     */
    public function setTimestamp($input) {
        date_default_timezone_set($this->getTimezone()->getName());
        if (!$temp = date('Y-m-d H:i:s', $input)) {
            date_default_timezone_set($this->system_tz);
            return false;
        }
        $this->timestamp = $input;
        $this->date = $temp;
        date_default_timezone_set($this->system_tz);
        return $this;
    }

    public function modify($input) {
        if (!$this->strtotime($input, $this->getTimestamp())) {
            return false;
        }

        return $this;
    }

    /**
     * Subtracts an amount of days, months, years, hours, minutes, and seconds from a DateTimeCompat object
     *
     * @param DateIntervalCompat $input
     *
     * @return DateTimeCompat|boolean
     */
    public function sub($input) {
        if ($input->invert) {
            $input->invert = 0;
        }
        else {
            $input->invert = 1;
        }

        return $this->add($input);
    }

    /**
     * Adds an amount of days, months, years, hours, minutes, and seconds to a DateTimeCompat object
     *
     * @param DateIntervalCompat $input
     *
     * @return DateTimeCompat|boolean
     */
    public function add(DateIntervalCompat $input) {

        if (!$this->strtotime($input->format('%R%y year %R%m month %R%d day %R%h hour %R%i minute %R%s second'), $this->getTimestamp())) {
            return false;
        }

        return $this;
    }

    /**
     * Returns the difference between two DateTimeCompat objects
     *
     * @param DateTimeCompat $input
     * @param bool           $absolute
     *
     * @return DateIntervalCompat
     */
    public function diff(DateTimeCompat $input, $absolute = false) {
        if ($this->getTimestamp() > $input->getTimestamp()) {
            $a = $input->getTimestamp();
            $b = $this->getTimestamp();
        }
        else {
            $a = $this->getTimestamp();
            $b = $input->getTimestamp();
        }

        $diffs = array();

        foreach ($this->intervals as $interval) {
            $temp = strtotime('+1' . $interval, $a);
            $add = 1;
            $looped = 0;
            while ($b >= $temp) {
                $add++;
                $temp = strtotime("+" . $add . " " . $interval, $a);
                $looped++;
            }
            $a = strtotime("+" . $looped . " " . $interval, $a);
            $diffs[$interval] = $looped;
        }

        if ($input->getTimestamp() > $this->getTimestamp()) {
            $invert = 0;
        }
        else {
            $invert = 1;
        }

        $y = $diffs['year'];
        $m = $diffs['month'];
        $d = $diffs['day'];
        $h = $diffs['hour'];
        $i = $diffs['minute'];
        $s = $diffs['second'];

        $rtrn = new DateIntervalCompat('P' . $y . 'Y' . $m . 'M' . $d . 'DT' . $h . 'H' . $i . 'M' . $s . 'S');
        if (!$absolute) {
            $rtrn->invert = $invert;
        }
        $rtrn->days = (int)floor(abs($input->getTimestamp() - $this->getTimestamp()) / $this->sec_in_day);

        return $rtrn;
    }

    /**
     * Resets the current date of the DateTimeCompat object to a different date.
     *
     * @param int $year
     * @param int $month
     * @param int $day
     *
     * @return DateTimeCompat|boolean
     */
    public function setDate($year, $month, $day) {
        date_default_timezone_set($this->getTimezone()->getName());
        if (!$this->strtotime($year . '-' . $month . '-' . $day . ' ' . date('H:i:s', $this->getTimestamp()))) {
            date_default_timezone_set($this->system_tz);
            return false;
        }
        date_default_timezone_set($this->system_tz);

        return $this;
    }

    /**
     * Resets the current time of the DateTimeCompat object to a different time
     *
     * @param int $hour
     * @param int $minute
     * @param int $second
     *
     * @return DateTimeCompat|boolean
     */
    public function setTime($hour, $minute, $second = 0) {
        date_default_timezone_set($this->getTimezone()->getName());
        if (!$this->strtotime(date('Y-m-d', $this->getTimestamp()) . ' ' . $hour . ':' . $minute . ':' . $second)) {
            date_default_timezone_set($this->system_tz);
            return false;
        }
        date_default_timezone_set($this->system_tz);

        return $this;
    }

    /**
     * Sets the ISO Date
     *
     * @todo make it go
     */
    public function setISODate() {

    }

    /**
     * Returns the timezone offset in seconds from UTC
     *
     * @return int|bool
     */
    public function getOffset() {
        return $this->getTimezone()->getOffset($this);
    }

    /**
     * Parses a datetime string for the TZ portion
     *
     * @param $input
     *
     * @return string
     * @todo limitations of DateTimeZoneCompat require a valid TZ designation
     */
    protected function tzFromString($input) {
        $tz_regex = '%((GMT)?[+-]0?([1-9]|1[0-2]):?[0-5][0-9]\b)|([A-Z][a-z]+([_/][A-Z][a-z]+)+)|(("?[A-Za-z]{2,6})"?)|(?:)\d\d(Z)%';
        if(preg_match($tz_regex, $input, $regs)) {
            $temp = $regs[0];
        } else {
            $temp = '';
        }

        if(substr($temp, -1) === 'Z') {
            $temp = 'UTC';
        }
        if($temp == 'now') {
            $temp = '';
        }
        if(substr($temp, -2) == '00') {
            $temp = str_replace(":", "", $temp);
            $temp = substr($temp, 0, -2);
            $temp = $this->tz_list[(int)$temp];
        }

        return $temp;
    }
}
<?php
/**
 * A compatibility library with PHP 5.2+ DateTimeZone class
 *
 * @author Jason Varnedoe <jason@fuzzystatic.com>
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Jason Varnedoe
 */

class DateTimeZoneCompat {

    /** @var string */
    protected $system_tz;
    /** @var string */
    protected $name;

    public function __construct($input) {
        if(ini_get('date.timezone')) {
            $this->system_tz = ini_get('date.timezone');
        } else {
            $this->system_tz = date_default_timezone_get();
        }

        if(!$this->setName($input)) {
            throw new Exception(__CLASS__ . '::' . __METHOD__ . ': Unkonwn or bad timezone (' . $input . ')');
        }

        return $this;
    }

    /**
     * Sets the TZ Name (checking to see if it's valid first)
     *
     * @param $input
     * @return bool
     */
    protected function setName($input) {
        if(!date_default_timezone_set($input)) {
            return false;
        }
        $this->name = $input;
        date_default_timezone_set($this->system_tz);

        return true;
    }

    /**
     * Returns the name of the timezone
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Returns the offset to UTC for the input date/time in seconds.
     *
     * @param DateTimeCompat $input
     * @return int
     */
    public function getOffset(DateTimeCompat $input) {
        date_default_timezone_set($this->getName());
        $temp = date('Z', $input->getTimestamp());
        date_default_timezone_set($this->system_tz);
        if($temp) {
            return (int)$temp;
        } else {
            return false;
        }
    }

    /**
     * Returns location information for a timezone
     *
     * @return array
     * @todo make it go
     */
    public function getLocation() {

    }


    public function getTransitions() {

    }

    /**
     * Returns associated array containing dst, offset, and the timezone name
     *
     * @return array
     * @todo make it go
     */
    public static function listAbbreviations() {

    }

    /**
     * Returns a numerically indexed array containing all defined timezone identifiers
     *
     * @return array
     * @todo make it go
     */
    public static function listIdentifiers() {

    }


}
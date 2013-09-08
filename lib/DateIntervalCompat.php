<?php
/**
 * A compatibility library with PHP 5.3+ DateInterval class
 *
 * @author Jason Varnedoe <jason@fuzzystatic.com>
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Jason Varnedoe
 */

class DateIntervalCompat {

    /** @var int */
    public $y;
    /** @var int */
    public $m;
    /** @var int */
    public $d;
    /** @var int */
    public $h;
    /** @var int */
    public $i;
    /** @var int */
    public $s;
    /** @var int */
    public $invert;
    /** @var int|boolean */
    public $days;

    /**
     * @param $input
     * @throws Exception
     * @returns DateIntervalCompat
     */
    public function __construct($input) {
        $temp = array();
        $rtn = preg_match('/^(-|)?P([0-9]+Y|)?([0-9]+M|)?([0-9]+W|)?([0-9]+D|)?T?([0-9]+H|)?([0-9]+M|)?([0-9]+S|)?$/', $input, $temp);
        if(empty($temp) || $rtn === false || $rtn === 0) {
            throw new Exception(__CLASS__."::".__METHOD__.": Unknown or bad format (".$input.")");
        }

        $this->y = (int)$temp[2];
        $this->m = (int)$temp[3];
        if($temp[5] != 0) {
            $this->d = (int)$temp[5];
        } else {
            $this->d = 7 * (int)$temp[4];
        }
        $this->h = (int)$temp[6];
        $this->i = (int)$temp[7];
        $this->s = (int)$temp[8];

        return $this;

    }

    public function createFromDateString() {

    }

    public function format() {

    }

}
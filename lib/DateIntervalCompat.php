<?php
/**
 * A compatibility library with PHP 5.3+ DateInterval class
 *
 * @author    Jason Varnedoe <jason@fuzzystatic.com>
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
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
    public $days = false;

    /**
     * @param $input
     *
     * @throws Exception
     * @returns DateIntervalCompat
     */
    public function __construct($input) {
        $temp = array();
        $rtn = preg_match('/^(-|)?P([0-9]+Y|)?([0-9]+M|)?([0-9]+W|)?([0-9]+D|)?T?([0-9]+H|)?([0-9]+M|)?([0-9]+S|)?$/', $input, $temp);
        if (empty($temp) || $rtn === false || $rtn === 0) {
            throw new Exception(__CLASS__ . '::' . __METHOD__ . ': Unknown or bad format (' . $input . ')');
        }

        $this->y = (int)$temp[2];
        $this->m = (int)$temp[3];
        if ($temp[5] != 0) {
            $this->d = (int)$temp[5];
        }
        else {
            $this->d = 7 * (int)$temp[4];
        }
        $this->h = (int)$temp[6];
        $this->i = (int)$temp[7];
        $this->s = (int)$temp[8];

        return $this;
    }

    /**
     * Sets up a DateIntervalCompat from the relative parts of the string
     *
     * @param $input
     *
     * @return DateIntervalCompat
     * @todo attempt to handle more than strings
     */
    public static function createFromDateString($input) {
        $temp_ts = time();
        $temp = strtotime($input, $temp_ts);
        if ($temp === false || $temp == -1) {
            return new DateIntervalCompat('PT0S');
        }

        return new DateIntervalCompat('PT' . abs($temp - $temp_ts) . 'S');

    }

    /**
     * Formats the interval
     *
     * @param $input
     *
     * @return string
     * @todo Ugly hack, bad attempt at a parser
     */
    public function format($input) {
        $temp = explode('%', $input);
        $rtrn = "";
        for ($i = 0; $i <= (count($temp) - 1); $i++) {
            if ($i == 0) {
                $rtrn .= $temp[$i];
            }
            elseif (strlen($temp[$i]) == 0) {
                $rtrn .= '%';
            }
            else {
                switch ($temp[$i][0]) {
                    case 'Y':
                        $rtrn .= str_pad($this->y, 2, '0', STR_PAD_LEFT) . substr($temp[$i], 1);
                        break;
                    case 'y':
                        $rtrn .= $this->y . substr($temp[$i], 1);
                        break;
                    case 'M':
                        $rtrn .= str_pad($this->m, 2, '0', STR_PAD_LEFT) . substr($temp[$i], 1);
                        break;
                    case 'm':
                        $rtrn .= $this->m . substr($temp[$i], 1);
                        break;
                    case 'D':
                        $rtrn .= str_pad($this->d, 2, '0', STR_PAD_LEFT) . substr($temp[$i], 1);
                        break;
                    case 'd':
                        $rtrn .= $this->d . substr($temp[$i], 1);
                        break;
                    case 'a':
                        if ($this->days) {
                            $rtrn .= $this->days . substr($temp[$i], 1);
                        }
                        else {
                            $rtrn .= '(unknown)';
                        }
                        break;
                    case 'H':
                        $rtrn .= str_pad($this->h, 2, '0', STR_PAD_LEFT) . substr($temp[$i], 1);
                        break;
                    case 'h':
                        $rtrn .= $this->h . substr($temp[$i], 1);
                        break;
                    case 'I':
                        $rtrn .= str_pad($this->i, 2, '0', STR_PAD_LEFT) . substr($temp[$i], 1);
                        break;
                    case 'i':
                        $rtrn .= $this->i . substr($temp[$i], 1);
                        break;
                    case 'S':
                        $rtrn .= str_pad($this->s, 2, '0', STR_PAD_LEFT) . substr($temp[$i], 1);
                        break;
                    case 's':
                        $rtrn .= $this->s . substr($temp[$i], 1);
                        break;
                    case 'R':
                        if ($this->invert) {
                            $rtrn .= '-';
                        }
                        else {
                            $rtrn .= '+';
                        }
                        $rtrn .= substr($temp[$i], 1);
                        break;
                    case 'r':
                        if ($this->invert) {
                            $rtrn .= '-';
                        }
                        $rtrn .= substr($temp[$i], 1);
                        break;
                    default:
                        $rtrn .= $temp[$i];
                }
            }
        }

        return $rtrn;
    }

}
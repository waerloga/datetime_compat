<?php
/**
 * A compatibility library with PHP 5.2+ DateTime class
 * This file is to serve as a shim to provide DateTime/DateTimeInterval classes for 5.1
 *
 * @author Jason Varnedoe <jason@fuzzystatic.com>
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Jason Varnedoe
 */

require_once dirname(__FILE__)."/DateTimeCompat.php";
require_once dirname(__FILE__)."/DateIntervalCompat.php";
require_once dirname(__FILE__)."/DateTimeZoneCompat.php";

if(!class_exists("DateTime", false)) {
    class DateTime extends DateTimeCompat { }
}

if(!class_exists("DateInterval", false)) {
    class DateInterval extends DateIntervalCompat { }
}

if(!class_exists("DateTimeZone", false)) {
    class DateTimeZone extends DateTimeZoneCompat { }
}
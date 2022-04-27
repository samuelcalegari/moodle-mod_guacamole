<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Internal library of functions for module guacamole
 *
 * @package   mod_guacamole
 * @copyright 2022 Samuel Calegari <samuel.calegari@univ-perp.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Test if free for reservation
 *
 * @param int $start
 * @param int $end
 * @return boolean exist /not exist
 */
function isFree($start, $end) {
   global $DB;

    $query= 'SELECT * FROM {guacamole} WHERE 
                                (timeopen <= ' . $start. ' and timeclose > '. $start .') OR 
                                (timeopen < ' . $end. ' and timeclose >= '. $end .') OR 
                                (timeopen >= ' .$start .' and timeclose <= ' . $end . ')';
    //echo($query);
    return !$DB->record_exists_sql($query);
}

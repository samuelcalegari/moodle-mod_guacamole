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
 * External library of functions for module guacamole
 *
 * @package   mod_guacamole
 * @copyright 2022 Samuel Calegari <samuel.calegari@univ-perp.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->libdir . "/externallib.php");

class mod_guacamole_users_cnx extends external_api
{

    public static function get_users_cnx_parameters()
    {
        return new external_function_parameters(array());
    }

    public static function get_users_cnx()
    {
        global $USER, $DB;

        $context = get_context_instance(CONTEXT_USER, $USER->id);
        self::validate_context($context);

        if (!has_capability('moodle/user:viewdetails', $context)) {
            throw new moodle_exception('cannotviewprofile');
        }

        $tmp = array('cnx' => array(), 'oldcnx' => array());
        $now = time();
        $lastminute = $now - 60;

        // Search active guacamole
        $records = $DB->get_records_sql("SELECT * FROM {guacamole} WHERE timeopen <= $now AND timeopen >= $lastminute");

        foreach ($records as $record) {
            $timeopen = $record->timeopen;
            $timeclose = $record->timeclose;

            // Search enrolled users in course
            $context = context_course::instance($record->course);
            $users = get_enrolled_users($context, 'moodle/course:isincompletionreports');

            foreach ($users as $user) {
                array_push($tmp['cnx'], array(
                    'user' => $user->username,
                    'timeopen' => $timeopen,
                    'timeclose' => $timeclose,
                ));
            }
        }

        // Search old guacamole
        $records = $DB->get_records_sql("SELECT * FROM {guacamole} WHERE timeclose >= $lastminute AND timeclose <= $now");

        foreach ($records as $record) {
            $timeopen = $record->timeopen;
            $timeclose = $record->timeclose;

            // Search enrolled users in course
            $context = context_course::instance($record->course);
            $users = get_enrolled_users($context, 'moodle/course:isincompletionreports');

            foreach ($users as $user) {
                array_push($tmp['oldcnx'], array(
                    'user' => $user->username,
                    'timeopen' => $timeopen,
                    'timeclose' => $timeclose,
                ));
            }
        }

        return $tmp;
    }

    public static function get_users_cnx_returns()
    {

        return new external_single_structure(array(
            'cnx' => new external_multiple_structure(self::cnx_structure()),
            'oldcnx' => new external_multiple_structure(self::cnx_structure())
        ));
    }

    public static function cnx_structure()
    {

        return new external_single_structure(
            array(
                'user' => new external_value(PARAM_TEXT, 'User Name'),
                'timeopen' => new external_value(PARAM_INT, 'Open Time'),
                'timeclose' => new external_value(PARAM_INT, 'Close Time'),
            )
        );
    }
}

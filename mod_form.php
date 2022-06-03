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
 * The main guacamole configuration form
 *
 * @package   mod_guacamole
 * @copyright 2022 Samuel Calegari <samuel.calegari@univ-perp.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');
require_once($CFG->dirroot.'/mod/guacamole/locallib.php');

/**
 * Guacamole settings form.
 *
 * @package   mod_guacamole
 * @copyright 2022 Samuel Calegari <samuel.calegari@univ-perp.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_guacamole_mod_form extends moodleform_mod {

    /**
     * Defines forms elements
     */
    public function definition() {

        global $COURSE;
        $mform =& $this->_form;

        // Add the "general" fieldset, where all the common settings are showed.
        $mform->addElement('header', 'general', get_string('general', 'form'));

        // Add the standard "name" field.
        $mform->addElement('text', 'name', get_string('name', 'guacamole'), array('size' => '64'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEAN);
        }
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('name', 'name', 'guacamole');

        $mform->addElement('header', 'availability', get_string('availability', 'assign'));
        $mform->setExpanded('availability', true);

        $name = get_string('open', 'guacamole');
        $options = array('optional' => false);
        $mform->addElement('date_time_selector', 'timeopen', $name, $options);

        $name = get_string('close', 'guacamole');
        $options = array('optional' => false);
        $mform->addElement('date_time_selector', 'timeclose', $name, $options);


        // Add standard elements, common to all modules.
        $this->standard_coursemodule_elements();

        // Add standard buttons, common to all modules.
        $this->add_action_buttons();

    }

    /**
     * Perform minimal validation on the settings form
     * @param array $data
     * @param array $files
     */
    public function validation($data, $files) {

        $errors = parent::validation($data, $files);

        if($data['timeopen'] > $data['timeclose'])
            $errors['timeopen'] = get_string('err1', 'mod_guacamole');

        if($data['timeclose'] - $data['timeopen'] > (get_string('configmaxtime', 'guacamole') * 60))
            $errors['timeclose'] = get_string('err2', 'mod_guacamole');

        if(!isFree($data['timeopen'], $data['timeclose'], $data["instance"]))
            $errors['timeopen'] = get_string('err3', 'mod_guacamole').' : ' . get_string('configmaxtime', 'guacamole') .  ' mins';

        if(!isPauseTimeRespected($data['timeopen'], $data['timeclose'], $data["instance"], get_string('configpausetime', 'guacamole')*60))
            $errors['timeopen'] = get_string('err4', 'mod_guacamole').' : ' . get_string('configpausetime', 'guacamole') .  ' mins';

        return $errors;
    }
}

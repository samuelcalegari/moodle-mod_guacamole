<?php

/**
 * The mod_guacamole settings.
 *
 * @package   mod_guacamole
 * @copyright 2022 Samuel Calegari <samuel.calegari@univ-perp.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

    //--- general settings -----------------------------------------------------------------------------------
    $settings->add(new admin_setting_configtext('guacamole/url', get_string('url', 'guacamole'),
        get_string('configurl', 'guacamole'), '', PARAM_URL));
}

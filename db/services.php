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
 * Web services definition for the guacamole module
 *
 * @package   mod_guacamole
 * @copyright 2022 Samuel Calegari <samuel.calegari@univ-perp.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// We defined the web service functions to install.
$functions = array(
    // Récupération de la liste des utilisateurs avec le début et la fin de connexion
    'mod_guacamole_get_users_cnx' => array(
        'classname'   => 'mod_guacamole_users_cnx',
        'methodname'  => 'get_users_cnx',
        'classpath'   => 'mod/guacamole/externallib.php',
        'description' => 'return available ',
        'type'        => 'read',
        'services'      => array('moodle_guacamole_app')
    ),
);

// We define the services to install as pre-build services. A pre-build service is not editable by administrator.
$services = array(
    'Guacamole service' => array(
        'functions' => array (
            'mod_guacamole_get_users_cnx',
        ),
        'restrictedusers' => 0,
        'enabled'=>1,
        'shortname' => 'moodle_guacamole_app'
    )
);

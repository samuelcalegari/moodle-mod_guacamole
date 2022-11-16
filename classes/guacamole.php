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
 * The mod_guacamole main class.
 *
 * @package   mod_guacamole
 * @copyright 2022 Samuel Calegari <samuel.calegari@univ-perp.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_guacamole;

use renderable;
use renderer_base;
use templatable;
use context_course;
use stdClass;
use ArrayIterator;
use moodle_url;


/**
 * The mod_guacamole main class.
 *
 * @package   mod_guacamole
 * @copyright 2022 Samuel Calegari <samuel.calegari@univ-perp.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class guacamole implements renderable, templatable {

    /**
     * @var int $course
     */
    private $course;

    /**
     * @var int $alwaysopen
     */
    private $alwaysopen;

    /**
     * @var int $timeopen
     */
    private $timeopen;

    /**
     * @var int $timeclose
     */
    private $timeclose;

    /**
     * Construct method.
     *
     * @param stdClass $guacamoleinstance Some text to show how to pass data to a template.
     * @return void
     */
    public function __construct(stdClass $guacamoleinstance) {
        $this->course = $guacamoleinstance->course;
        $this->alwaysopen = $guacamoleinstance->alwaysopen;
        $this->timeopen = $guacamoleinstance->timeopen;
        $this->timeclose = $guacamoleinstance->timeclose;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param renderer_base $output The output renderer object.
     * @return stdClass
     */
    public function export_for_template(renderer_base $output)
    {

        $now = time();
        $msg = "";
        $available = true;
        if (!$this->alwaysopen) {

            if ($this->timeopen > $now) {
                $msg = get_string('classroomnotopen', 'mod_guacamole');
                $available = false;
            }

            if ($this->timeclose < $now) {
                $msg = get_string('classroomclosed', 'mod_guacamole');
                $available = false;
            }
        }

        $data['url'] = get_config('guacamole', 'url');
        $data['start'] = strftime('%A %e %B %Y à %R', $this->timeopen);
        $data['end'] = strftime('%A %e %B %Y à %R', $this->timeclose);
        $data['msg'] = $msg;
        $data['available'] = $available;
        $data['alwaysopen'] = $this->alwaysopen;
        $data['img_visu_src'] = $output->image_url('virtual-classroom', 'mod_guacamole');
        return $data;
    }
}

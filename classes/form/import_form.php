<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <https://www.gnu.org/licenses/>.

/**
 * Import a ZIP of slide images into a Moodle Book.
 *
 * @package    local_pptxbook
 * @copyright  2026 PowerPoint to Book contributors
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_pptxbook\form;

defined('MOODLE_INTERNAL') || die();
global $CFG;
require_once($CFG->libdir . '/formslib.php');

/**
 * Upload images for the current Book.
 *
 * @package    local_pptxbook
 * @copyright  2026 PowerPoint to Book contributors
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class import_form extends \moodleform {
    /**
     * Define the ZIP upload form.
     *
     * @return void
     */
    public function definition() {
        $mform = $this->_form;
        $course = $this->_customdata['course'];
        $mform->addElement('hidden', 'id', $this->_customdata['cmid']);
        $mform->setType('id', PARAM_INT);
        $mform->addElement('filepicker', 'presentation', get_string('presentation', 'local_pptxbook'), null, [
            'accepted_types' => ['.zip'],
            'maxbytes' => \local_pptxbook\options::maxbytes($course),
        ]);
        $mform->addRule('presentation', null, 'required', null, 'client');
        $mform->addElement('static', 'notice', '', get_string('importnotice', 'local_pptxbook'));
        $this->add_action_buttons(true, get_string('import', 'local_pptxbook'));
    }
}

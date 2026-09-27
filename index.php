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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Import a ZIP of slide images into a Moodle Book.
 *
 * @package    local_pptxbook
 * @copyright  2026 EDUNOVER
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../config.php');

$id = required_param('id', PARAM_INT);
$cm = get_coursemodule_from_id('book', $id, 0, false, MUST_EXIST);
$course = get_course($cm->course);
require_login($course, false, $cm);
$context = context_module::instance($cm->id);
\local_pptxbook\importer::require_access($context);
$book = $DB->get_record('book', ['id' => $cm->instance], '*', MUST_EXIST);
$PAGE->set_url('/local/pptxbook/index.php', ['id' => $cm->id]);
$PAGE->set_cm($cm, $course);
$PAGE->set_context($context);
$PAGE->set_pagelayout('incourse');
$PAGE->set_title(get_string('import', 'local_pptxbook'));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->navbar->add(get_string('import', 'local_pptxbook'));
$form = new \local_pptxbook\form\import_form(null, ['course' => $course, 'cmid' => $cm->id]);
$bookurl = new moodle_url('/mod/book/view.php', ['id' => $cm->id]);
if ($form->is_cancelled()) {
    redirect($bookurl);
}
$error = null;
if ($data = $form->get_data()) {
    require_sesskey();
    $directory = null;
    try {
        \local_pptxbook\archive::check();
        $directory = make_request_directory();
        $input = $directory . '/slides.zip';
        if (
            !$form->save_file('presentation', $input, true) ||
                filesize($input) > \local_pptxbook\options::maxbytes($course)
        ) {
            throw new moodle_exception('invalidzip', 'local_pptxbook');
        }
        $images = \local_pptxbook\archive::read(
            $input,
            $directory,
            \local_pptxbook\options::integer('maxslides', 50, 1, 200)
        );
        $count = \local_pptxbook\importer::append($cm, $images);
    } catch (moodle_exception $exception) {
        $error = $exception->getMessage();
    } finally {
        if ($directory) {
            remove_dir($directory);
        }
    }
    if (isset($count)) {
        redirect(
            $bookurl,
            get_string('success', 'local_pptxbook', $count),
            null,
            \core\output\notification::NOTIFY_SUCCESS
        );
    }
}
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('import', 'local_pptxbook'));
echo $OUTPUT->heading(format_string($book->name), 3);
if ($error !== null) {
    echo $OUTPUT->notification(s($error), 'notifyproblem');
}
$form->display();
echo $OUTPUT->footer();

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
defined('MOODLE_INTERNAL') || die();

/**
 * Add the import action to the current Book's settings / More menu.
 *
 * @param settings_navigation $settingsnav Settings navigation tree.
 * @param context $context Current page context.
 * @return void
 */
function local_pptxbook_extend_settings_navigation($settingsnav, $context) {
    global $PAGE;

    $cm = $PAGE->cm;
    if (!$cm || $cm->modname !== 'book' || $context->contextlevel !== CONTEXT_MODULE) {
        return;
    }
    $node = $settingsnav->find('modulesettings', navigation_node::TYPE_SETTING);
    if ($node && has_all_capabilities(['local/pptxbook:import', 'mod/book:edit'], $context)) {
        $importnode = $node->add(
            get_string('import', 'local_pptxbook'),
            new moodle_url('/local/pptxbook/index.php', ['id' => $cm->id]),
            navigation_node::TYPE_SETTING,
            null,
            'pptxbook',
            new pix_icon('f/archive', '')
        );
        $importnode->set_force_into_more_menu(true);
    }
}

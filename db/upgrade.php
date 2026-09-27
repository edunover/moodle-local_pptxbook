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

/**
 * Remove settings from the former external conversion implementation.
 *
 * @param int $oldversion Previously installed version.
 * @return bool Whether the upgrade succeeded.
 */
function xmldb_local_pptxbook_upgrade($oldversion) {
    if ($oldversion < 2026092701) {
        foreach (['libreoffice', 'pdftoppm', 'timeout', 'width'] as $setting) {
            unset_config($setting, 'local_pptxbook');
        }
        upgrade_plugin_savepoint(true, 2026092701, 'local', 'pptxbook');
    }
    return true;
}

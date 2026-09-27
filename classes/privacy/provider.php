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
namespace local_pptxbook\privacy;
defined('MOODLE_INTERNAL') || die();
/** All durable content belongs to the standard Book and core file subsystems. */
class provider implements \core_privacy\local\metadata\null_provider {
    /** @return string Language identifier. */
    public static function get_reason(): string {
        return 'privacy:metadata';
    }
}

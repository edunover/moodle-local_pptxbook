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
namespace local_pptxbook;
/**
 * Configuration with hard upper bounds.
 *
 * @package    local_pptxbook
 */
class options {
    /**
     * Read a bounded integer setting.
     *
     * @param string $name Setting name.
     * @param int $default Default value.
     * @param int $min Minimum value.
     * @param int $max Maximum value.
     * @return int Bounded value.
     */
    public static function integer(string $name, int $default, int $min, int $max): int {
        $value = get_config('local_pptxbook', $name);
        return max($min, min($max, $value === false ? $default : (int)$value));
    }
    /**
     * Get the effective upload limit.
     *
     * @param \stdClass $course Target course.
     * @return int Maximum upload bytes.
     */
    public static function maxbytes(\stdClass $course): int {
        global $CFG;
        return get_max_upload_file_size(
            $CFG->maxbytes,
            $course->maxbytes,
            self::integer('maxmb', 25, 1, 100) * 1024 * 1024
        );
    }
}

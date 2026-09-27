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
defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Slide images to Book';
$string['pptxbook:import'] = 'Import slide images as Books';
$string['import'] = 'Import images (ZIP)';
$string['bookname'] = 'Book name';
$string['section'] = 'Course section';
$string['presentation'] = 'ZIP archive of PNG/JPEG images';
$string['importnotice'] = 'Images are sorted naturally by their full relative filenames (Slide2 before Slide10). Use consistent names and put the images in one folder. Images are appended to this Book; existing chapters are preserved. Each filename becomes a chapter title. If the Book is visible, new chapters are visible immediately. Add accessible descriptions manually where needed. Keep this page open until the import finishes.';
$string['invalidname'] = 'Enter a Book name between 1 and 255 characters.';
$string['maxslides'] = 'Maximum images';
$string['maxslides_desc'] = 'Maximum images per import (1–200). Default: 50.';
$string['maxmb'] = 'Maximum ZIP size (MB)';
$string['maxmb_desc'] = 'ZIP upload limit (1–100 MB). Moodle, course and PHP limits also apply. Default: 25 MB.';
$string['notconfigured'] = 'PHP ZIP and GD extensions are required. Ask your hosting provider to enable them.';
$string['invalidzip'] = 'Invalid, damaged or unsafe ZIP archive. Create a new ZIP containing only PNG/JPEG images.';
$string['archivelimit'] = 'The ZIP exceeds 5,000 entries or 128 MB of uncompressed content.';
$string['unsupportedfile'] = 'The ZIP contains a file other than PNG or JPEG. Remove other documents before importing.';
$string['duplicatename'] = 'The ZIP contains duplicate paths (ignoring case). Rename the files before importing.';
$string['imagelimit'] = 'An image is empty, encrypted or larger than 10 MB.';
$string['invalidimage'] = 'An image is damaged, has the wrong extension, or exceeds 8 million pixels or 16,000 pixels on an edge. Export smaller PNG/JPEG images.';
$string['writefailed'] = 'Temporary files could not be written. Check Moodle temporary storage and disk space.';
$string['slidelimit'] = 'The ZIP must contain between 1 and {$a} images.';
$string['invalidsection'] = 'Select an existing, ordinary course section.';
$string['busy'] = 'Another ZIP is being imported into this Book. Try again shortly.';
$string['slide'] = 'Slide {$a}';
$string['success'] = '{$a} chapters have been appended to the Book.';
$string['privacy:metadata'] = 'The plugin has no separate personal-data store. Draft uploads belong to core files; chapters and images belong to mod_book. Temporary images are removed after import.';

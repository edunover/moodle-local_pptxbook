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
 * Read and validate ZIP images without extracting user-controlled paths.
 *
 * @package    local_pptxbook
 * @copyright  2026 EDUNOVER
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class archive {
    /** @var int Maximum uncompressed bytes per image. */
    public const MAX_IMAGE_BYTES = 10 * 1024 * 1024;
    /** @var int Maximum declared uncompressed bytes in an archive. */
    public const MAX_TOTAL_BYTES = 128 * 1024 * 1024;
    /** @var int Maximum pixels in an image to bound GD decoding memory. */
    public const MAX_PIXELS = 8000000;

    /**
     * Ensure required PHP extensions are available; no external command is used.
     *
     * @return void
     */
    public static function check(): void {
        if (!class_exists('ZipArchive') || !function_exists('imagecreatefromstring')) {
            throw new \moodle_exception('notconfigured', 'local_pptxbook');
        }
    }

    /**
     * Validate images and write them under generated names in a private directory.
     *
     * Sort naturally by full relative path, case-insensitively; use identical
     * prefixes for all slides or place them together for predictable ordering.
     *
     * @param string $path ZIP pathname.
     * @param string $directory Existing request-local output directory.
     * @param int $maxslides Maximum number of slide images.
     * @return array Validated image descriptors in chapter order.
     */
    public static function read(string $path, string $directory, int $maxslides): array {
        self::check();
        $zip = new \ZipArchive();
        if ($zip->open($path, \ZipArchive::RDONLY) !== true) {
            throw new \moodle_exception('invalidzip', 'local_pptxbook');
        }
        $written = [];
        try {
            if ($zip->numFiles > 5000) {
                throw new \moodle_exception('archivelimit', 'local_pptxbook');
            }
            $entries = [];
            $names = [];
            $total = 0;
            for ($index = 0; $index < $zip->numFiles; $index++) {
                $stat = $zip->statIndex($index);
                if ($stat === false) {
                    throw new \moodle_exception('invalidzip', 'local_pptxbook');
                }
                $name = $stat['name'];
                $total += $stat['size'];
                if ($total > self::MAX_TOTAL_BYTES) {
                    throw new \moodle_exception('archivelimit', 'local_pptxbook');
                }
                if (preg_match('~(^/|^[a-zA-Z]:|(^|/)\.\.(/|$)|[\\\\\x00-\x1f])~', $name)) {
                    throw new \moodle_exception('invalidzip', 'local_pptxbook');
                }
                $opsys = 0;
                $attributes = 0;
                if (
                    $zip->getExternalAttributesIndex($index, $opsys, $attributes) &&
                        (($attributes >> 16) & 0170000) === 0120000
                ) {
                    throw new \moodle_exception('invalidzip', 'local_pptxbook');
                }
                $key = strtolower($name);
                if (isset($names[$key])) {
                    throw new \moodle_exception('duplicatename', 'local_pptxbook');
                }
                $names[$key] = true;
                $basename = basename($name);
                // Ignore directories and common Finder/Explorer metadata only.
                if (
                    str_ends_with($name, '/') || str_starts_with($name, '__MACOSX/') ||
                        $basename === '.DS_Store' || str_starts_with($basename, '._') ||
                        in_array(strtolower($basename), ['thumbs.db', 'desktop.ini'], true)
                ) {
                    continue;
                }
                $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                if (!in_array($extension, ['png', 'jpg', 'jpeg'], true)) {
                    throw new \moodle_exception('unsupportedfile', 'local_pptxbook');
                }
                if (
                    $stat['size'] < 1 || $stat['size'] > self::MAX_IMAGE_BYTES ||
                        !empty($stat['encryption_method'])
                ) {
                    throw new \moodle_exception('imagelimit', 'local_pptxbook');
                }
                $entries[] = ['index' => $index, 'name' => $name, 'size' => $stat['size'], 'extension' => $extension];
            }
            if (!$entries || count($entries) > $maxslides) {
                throw new \moodle_exception('slidelimit', 'local_pptxbook', '', $maxslides);
            }
            usort($entries, static function (array $first, array $second): int {
                return strnatcasecmp($first['name'], $second['name']) ?: strcmp($first['name'], $second['name']);
            });
            $images = [];
            foreach ($entries as $number => $entry) {
                $bytes = $zip->getFromIndex($entry['index'], self::MAX_IMAGE_BYTES + 1);
                if ($bytes === false || strlen($bytes) !== $entry['size']) {
                    throw new \moodle_exception('invalidzip', 'local_pptxbook');
                }
                $size = @getimagesizefromstring($bytes);
                $expected = $entry['extension'] === 'png' ? IMAGETYPE_PNG : IMAGETYPE_JPEG;
                if (
                    !$size || $size[2] !== $expected || $size[0] < 1 || $size[1] < 1 ||
                        $size[0] > 16000 || $size[1] > 16000 || $size[0] * $size[1] > self::MAX_PIXELS
                ) {
                    throw new \moodle_exception('invalidimage', 'local_pptxbook');
                }
                // Check that GD can actually decode the file, not only its header.
                $decoded = @imagecreatefromstring($bytes);
                if ($decoded === false) {
                    throw new \moodle_exception('invalidimage', 'local_pptxbook');
                }
                unset($decoded);
                $extension = $expected === IMAGETYPE_PNG ? 'png' : 'jpg';
                $output = $directory . '/image-' . ($number + 1) . '.' . $extension;
                $written[] = $output;
                if (file_put_contents($output, $bytes) !== strlen($bytes)) {
                    throw new \moodle_exception('writefailed', 'local_pptxbook');
                }
                $images[] = [
                    'path' => $output,
                    'title' => str_replace('_', ' ', pathinfo(basename($entry['name']), PATHINFO_FILENAME)),
                    'extension' => $extension,
                    'mimetype' => image_type_to_mime_type($expected),
                ];
                unset($bytes);
            }
            return $images;
        } catch (\Throwable $exception) {
            foreach ($written as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            throw $exception;
        } finally {
            $zip->close();
        }
    }
}

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
namespace local_pptxbook;

defined('MOODLE_INTERNAL') || die();

/**
 * Append validated images to a standard Moodle Book.
 *
 * @package    local_pptxbook
 * @copyright  2026 PowerPoint to Book contributors
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class importer {
    /**
     * Require permission to import and edit the current Book.
     *
     * @param \context_module $context Book context.
     * @return void
     */
    public static function require_access(\context_module $context): void {
        require_capability('local/pptxbook:import', $context);
        require_capability('mod/book:edit', $context);
    }

    /**
     * Append chapters atomically while preserving existing content and visibility.
     *
     * @param \stdClass $cm Course module returned by get_coursemodule_from_id().
     * @param array $images Validated descriptors returned by archive::read().
     * @return int Number of added chapters.
     */
    public static function append(\stdClass $cm, array $images): int {
        global $DB, $USER;

        // Resolve again to ensure the supplied module really is a Book.
        $cm = get_coursemodule_from_id('book', $cm->id, 0, false, MUST_EXIST);
        $context = \context_module::instance($cm->id);
        self::require_access($context);
        if (!$images) {
            throw new \moodle_exception('invalidzip', 'local_pptxbook');
        }
        $factory = \core\lock\lock_config::get_lock_factory('local_pptxbook');
        $lock = $factory->get_lock('book-' . $cm->instance, 0);
        if (!$lock) {
            throw new \moodle_exception('busy', 'local_pptxbook');
        }
        try {
            $transaction = $DB->start_delegated_transaction();
            try {
                $book = $DB->get_record('book', ['id' => $cm->instance], '*', MUST_EXIST);
                $lastpage = (int)$DB->get_field_sql(
                    'SELECT MAX(pagenum) FROM {book_chapters} WHERE bookid = ?', [$book->id]
                );
                $fs = get_file_storage();
                foreach ($images as $index => $image) {
                    $number = $lastpage + $index + 1;
                    $title = trim(clean_param($image['title'], PARAM_TEXT));
                    $title = $title === '' ? get_string('slide', 'local_pptxbook', $number) : \core_text::substr($title, 0, 255);
                    $chapter = (object)[
                        'bookid' => $book->id,
                        'pagenum' => $number,
                        'subchapter' => 0,
                        'title' => $title,
                        'content' => '',
                        'contentformat' => FORMAT_HTML,
                        'hidden' => 0,
                        'timecreated' => time(),
                        'timemodified' => time(),
                        'importsrc' => '',
                    ];
                    $chapter->id = $DB->insert_record('book_chapters', $chapter);
                    $fs->create_file_from_pathname([
                        'contextid' => $context->id,
                        'component' => 'mod_book',
                        'filearea' => 'chapter',
                        'itemid' => $chapter->id,
                        'filepath' => '/',
                        'filename' => 'slide.' . $image['extension'],
                        'mimetype' => $image['mimetype'],
                        'userid' => $USER->id,
                    ], $image['path']);
                    $chapter->content = \html_writer::empty_tag('img', [
                        'src' => '@@PLUGINFILE@@/slide.' . $image['extension'],
                        'alt' => $title,
                        'style' => 'max-width:100%;height:auto;',
                    ]);
                    $DB->update_record('book_chapters', $chapter);
                    \mod_book\event\chapter_created::create_from_chapter($book, $context, $chapter)->trigger();
                }
                $DB->update_record('book', (object)[
                    'id' => $book->id,
                    'revision' => $book->revision + 1,
                    'timemodified' => time(),
                ]);
                $transaction->allow_commit();
            } catch (\Throwable $exception) {
                $transaction->rollback($exception);
            }
        } finally {
            $lock->release();
        }
        return count($images);
    }
}

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
 * Moodle integration tests for appending chapters.
 *
 * @package    local_pptxbook
 * @copyright  2026 EDUNOVER
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers     \local_pptxbook\importer
 */
final class importer_test extends \advanced_testcase {
    /**
     * Verify existing content, placement, visibility and chapter files are preserved.
     *
     * @return void
     */
    public function test_append_preserves_existing_chapters(): void {
        global $DB;
        $this->resetAfterTest();
        $this->setAdminUser();
        $course = $this->getDataGenerator()->create_course();
        $book = $this->getDataGenerator()->create_module('book', ['course' => $course->id, 'visible' => 1]);
        $existing = (object)[
            'bookid' => $book->id,
            'pagenum' => 1,
            'subchapter' => 0,
            'title' => 'Existing chapter',
            'content' => '<p>Keep this content</p>',
            'contentformat' => FORMAT_HTML,
            'hidden' => 0,
            'timecreated' => time(),
            'timemodified' => time(),
            'importsrc' => '',
        ];
        $existing->id = $DB->insert_record('book_chapters', $existing);
        $cm = get_coursemodule_from_instance('book', $book->id);
        $before = $DB->count_records('course_modules', ['course' => $course->id]);
        $path = make_request_directory() . '/test.png';
        $image = imagecreatetruecolor(10, 10);
        imagepng($image, $path);
        unset($image);
        $images = [
            ['path' => $path, 'title' => 'Slide 2', 'extension' => 'png', 'mimetype' => 'image/png'],
            ['path' => $path, 'title' => '', 'extension' => 'png', 'mimetype' => 'image/png'],
        ];
        $this->assertSame(2, importer::append($cm, $images));
        $chapters = array_values($DB->get_records('book_chapters', ['bookid' => $book->id], 'pagenum'));
        $this->assertCount(3, $chapters);
        $this->assertEquals($existing, $chapters[0]);
        $this->assertEquals(2, $chapters[1]->pagenum);
        $this->assertEquals('Slide 2', $chapters[1]->title);
        $this->assertEquals(get_string('slide', 'local_pptxbook', 3), $chapters[2]->title);
        $this->assertStringContainsString(
            '@@PLUGINFILE@@/slide.png',
            $chapters[1]->content
        );
        $this->assertEquals($before, $DB->count_records('course_modules', ['course' => $course->id]));
        $this->assertEquals(1, $DB->get_field('course_modules', 'visible', ['id' => $cm->id]));
        $files = get_file_storage()->get_area_files(
            \context_module::instance($cm->id)->id,
            'mod_book',
            'chapter',
            false,
            'id',
            false
        );
        $this->assertCount(2, $files);
        $this->assertSame(1, importer::append($cm, [$images[0]]));
        $this->assertEquals(4, $DB->count_records('book_chapters', ['bookid' => $book->id]));
    }

    /**
     * Students cannot append chapters via the service.
     *
     * @return void
     */
    public function test_student_cannot_import(): void {
        $this->resetAfterTest();
        $course = $this->getDataGenerator()->create_course();
        $book = $this->getDataGenerator()->create_module('book', ['course' => $course->id]);
        $student = $this->getDataGenerator()->create_user();
        $this->getDataGenerator()->enrol_user($student->id, $course->id, 'student');
        $this->setUser($student);
        $cm = get_coursemodule_from_instance('book', $book->id);
        $this->expectException(\required_capability_exception::class);
        importer::require_access(\context_module::instance($cm->id));
    }
}

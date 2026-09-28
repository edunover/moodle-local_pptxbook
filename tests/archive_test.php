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
 * Validate archive contents and reject unsafe uploads.
 *
 * @package    local_pptxbook
 * @copyright  2026 EDUNOVER
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers     \local_pptxbook\archive
 */
#[\PHPUnit\Framework\Attributes\CoversClass(archive::class)]
final class archive_test extends \advanced_testcase {
    /**
     * Create a ZIP fixture inside Moodle's temporary directory.
     *
     * @param array $files Entry names mapped to file contents.
     * @return string ZIP pathname.
     */
    private function create_zip(array $files): string {
        $path = make_request_directory() . '/slides.zip';
        $zip = new \ZipArchive();
        $this->assertTrue($zip->open($path, \ZipArchive::CREATE));
        foreach ($files as $name => $bytes) {
            $zip->addFromString($name, $bytes);
        }
        $zip->close();
        return $path;
    }

    /**
     * Create a valid image fixture.
     *
     * @param bool $jpeg Whether to produce JPEG instead of PNG.
     * @param int $width Width and height in pixels.
     * @return string Encoded image.
     */
    private function create_image(bool $jpeg = false, int $width = 10): string {
        $image = imagecreatetruecolor($width, $width);
        ob_start();
        if ($jpeg) {
            imagejpeg($image);
        } else {
            imagepng($image);
        }
        unset($image);
        return ob_get_clean();
    }

    /**
     * Check numeric ordering, titles, metadata and unchanged JPEG bytes.
     *
     * @return void
     */
    public function test_valid_images(): void {
        $this->resetAfterTest();
        $png = $this->create_image();
        $jpeg = $this->create_image(true);
        $path = $this->create_zip([
            'Slides/Slide_10.PNG' => $png,
            'Slides/Slide_2.jpeg' => $jpeg,
            'Slides/Slide_1.png' => $png,
            '__MACOSX/._Slide1.png' => 'ignored',
            'Slides/.DS_Store' => 'ignored',
            'Slides/Thumbs.db' => 'ignored',
        ]);
        $images = archive::read($path, make_request_directory(), 50);
        $this->assertSame(['Slide 1', 'Slide 2', 'Slide 10'], array_column($images, 'title'));
        $this->assertSame(['png', 'jpg', 'png'], array_column($images, 'extension'));
        $this->assertSame('image/jpeg', $images[1]['mimetype']);
        $this->assertSame($jpeg, file_get_contents($images[1]['path']));
    }

    /**
     * Check errors and cleanup after partially processing an archive.
     *
     * @return void
     */
    public function test_invalid_archives(): void {
        $this->resetAfterTest();
        $png = $this->create_image();
        $cases = [
            [['slide1.png' => $png, 'slide2.png' => $png], 'slidelimit', 1],
            [['readme.txt' => 'not an image'], 'unsupportedfile', 50],
            [['.DS_Store' => 'metadata only'], 'slidelimit', 50],
            [['../slide.png' => $png], 'invalidzip', 50],
            [['/slide.png' => $png], 'invalidzip', 50],
            [['C:/slide.png' => $png], 'invalidzip', 50],
            [['folder\\slide.png' => $png], 'invalidzip', 50],
            [['Slide1.png' => $png, 'slide1.png' => $png], 'duplicatename', 50],
            [['slide.png' => $this->create_image(true)], 'invalidimage', 50],
            [['slide.png' => 'fake PNG'], 'invalidimage', 50],
            [['slide.png' => ''], 'imagelimit', 50],
            [['slide.png' => str_repeat('x', archive::MAX_IMAGE_BYTES + 1)], 'imagelimit', 50],
            [['slide1.png' => $png, 'slide2.png' => 'broken'], 'invalidimage', 50],
            [['large.png' => $this->create_image(false, 3000)], 'invalidimage', 50],
        ];
        foreach ($cases as [$files, $error, $max]) {
            $this->assert_rejected($this->create_zip($files), $error, $max);
        }
        $bad = make_request_directory() . '/bad.zip';
        file_put_contents($bad, 'not a ZIP');
        $this->assert_rejected($bad, 'invalidzip');
        $link = $this->create_zip(['slide.png' => 'target']);
        $zip = new \ZipArchive();
        $zip->open($link);
        $zip->setExternalAttributesName('slide.png', \ZipArchive::OPSYS_UNIX, 0120777 << 16);
        $zip->close();
        $this->assert_rejected($link, 'invalidzip');
    }

    /**
     * Assert that no partially extracted image survives a rejected upload.
     *
     * @param string $path ZIP pathname.
     * @param string $error Expected Moodle error code.
     * @param int $max Maximum number of images.
     * @return void
     */
    private function assert_rejected(string $path, string $error, int $max = 50): void {
        $out = make_request_directory();
        try {
            archive::read($path, $out, $max);
            $this->fail('Expected ' . $error);
        } catch (\moodle_exception $exception) {
            $this->assertSame($error, $exception->errorcode);
            $this->assertSame([], glob($out . '/*'));
        }
    }
}

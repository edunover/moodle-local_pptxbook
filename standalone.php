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
// Standalone archive and navigation checks. Not a substitute for Moodle PHPUnit.
if (PHP_SAPI !== 'cli' || defined('MOODLE_INTERNAL')) {
    exit(1);
}
define('MOODLE_INTERNAL', true);
define('CONTEXT_MODULE', 70);
class moodle_exception extends Exception {
    public function __construct(public string $errorcode, ...$unused) {
        parent::__construct($errorcode);
    }
}
require_once(__DIR__ . '/../classes/archive.php');
$dir = sys_get_temp_dir() . '/bookzip-' . bin2hex(random_bytes(8));
mkdir($dir, 0700);
$count = 0;
function check(bool $ok, string $name): void {
    global $count;
    if (!$ok) {
        throw new Exception($name);
    }
    $count++;
    echo "PASS $name\n";
}
function zipfile(array $files): string {
    global $dir;
    $path = $dir . '/' . bin2hex(random_bytes(4)) . '.zip';
    $zip = new ZipArchive();
    $zip->open($path, ZipArchive::CREATE);
    foreach ($files as $name => $bytes) {
        $zip->addFromString($name, $bytes);
    }
    $zip->close();
    return $path;
}
function reject(string $path, string $error, int $max = 50): void {
    global $dir;
    $out = $dir . '/' . bin2hex(random_bytes(4));
    mkdir($out);
    try {
        \local_pptxbook\archive::read($path, $out, $max);
    } catch (moodle_exception $exception) {
        check($exception->errorcode === $error, $error);
        check(count(glob($out . '/*')) === 0, 'No partial extracted images after failure');
        return;
    }
    throw new Exception('Expected ' . $error);
}
function remove_tree(string $path): void {
    foreach (new FilesystemIterator($path) as $item) {
        if ($item->isDir()) {
            remove_tree($item->getPathname());
        } else {
            unlink($item->getPathname());
        }
    }
    rmdir($path);
}
try {
    $im = imagecreatetruecolor(80, 45);
    imagefill($im, 0, 0, imagecolorallocate($im, 10, 80, 180));
    ob_start();
    imagepng($im);
    $png = ob_get_clean();
    ob_start();
    imagejpeg($im);
    $jpg = ob_get_clean();
    unset($im);
    $path = zipfile(['Slides/Slide10.PNG' => $png, 'Slides/Slide2.jpeg' => $jpg, 'Slides/Slide1.png' => $png,
        '__MACOSX/._Slide1.png' => 'ignored', 'Slides/.DS_Store' => 'ignored', 'Slides/Thumbs.db' => 'ignored']);
    $images = \local_pptxbook\archive::read($path, $dir, 50);
    check(array_column($images, 'title') === ['Slide1', 'Slide2', 'Slide10'], 'Natural numeric ordering and OS metadata');
    check(array_column($images, 'extension') === ['png', 'jpg', 'png'], 'Mixed PNG/JPEG extensions');
    check($images[1]['mimetype'] === 'image/jpeg', 'Correct JPEG MIME type');
    check(file_get_contents($images[1]['path']) === $jpg, 'Original JPEG preserved');
    foreach ($images as $image) {
        unlink($image['path']);
    }
    reject($path, 'slidelimit', 2);
    reject(zipfile(['readme.txt' => 'not an image']), 'unsupportedfile');
    reject(zipfile(['.DS_Store' => 'metadata only']), 'slidelimit');
    reject(zipfile(['../slide.png' => $png]), 'invalidzip');
    reject(zipfile(['/slide.png' => $png]), 'invalidzip');
    reject(zipfile(['C:/slide.png' => $png]), 'invalidzip');
    reject(zipfile(['folder\\slide.png' => $png]), 'invalidzip');
    reject(zipfile(['Slide1.png' => $png, 'slide1.png' => $png]), 'duplicatename');
    reject(zipfile(['slide.png' => $jpg]), 'invalidimage');
    reject(zipfile(['slide.png' => 'fake PNG']), 'invalidimage');
    reject(zipfile(['slide.png' => '']), 'imagelimit');
    reject(zipfile(['slide.png' => str_repeat('x', 10 * 1024 * 1024 + 1)]), 'imagelimit');
    reject(zipfile(['slide1.png' => $png, 'slide2.png' => 'broken']), 'invalidimage');
    $bad = $dir . '/bad.zip';
    file_put_contents($bad, 'not a ZIP');
    reject($bad, 'invalidzip');
    $link = zipfile(['slide.png' => 'target']);
    $zip = new ZipArchive();
    $zip->open($link);
    $zip->setExternalAttributesName('slide.png', ZipArchive::OPSYS_UNIX, 0120777 << 16);
    $zip->close();
    reject($link, 'invalidzip');
    $large = imagecreatetruecolor(3000, 3000);
    ob_start(); imagepng($large); $oversize = ob_get_clean(); unset($large);
    reject(zipfile(['large.png' => $oversize]), 'invalidimage');
    // Minimal navigation doubles exercise the actual callback and routing guards.
    class navigation_node { public const TYPE_SETTING = 1; }
    class pix_icon { public function __construct(...$args) {} }
    class moodle_url { public function __construct(public string $url, public array $params) {} }
    class testnode {
        public array $nodes = [];
        public bool $forced = false;
        public function find(...$args) { return $this; }
        public function add($text, $url, ...$args) {
            $child = new self();
            $this->nodes[] = ['url' => $url, 'node' => $child];
            return $child;
        }
        public function set_force_into_more_menu($value) { $this->forced = $value; }
    }
    function get_string(...$args) { return 'Import'; }
    function has_all_capabilities(...$args) { global $allowed; return $allowed; }
    require_once(__DIR__ . '/../lib.php');
    $PAGE = (object)['cm' => (object)['id' => 123, 'modname' => 'book']];
    $context = (object)['contextlevel' => CONTEXT_MODULE];
    $allowed = true;
    $node = new testnode();
    local_pptxbook_extend_settings_navigation($node, $context);
    check(count($node->nodes) === 1, 'Import added to Book settings');
    check($node->nodes[0]['node']->forced, 'Import explicitly forced into More menu');
    check($node->nodes[0]['url']->params === ['id' => 123], 'URL targets current Book course-module ID');
    $allowed = false;
    $node = new testnode();
    local_pptxbook_extend_settings_navigation($node, $context);
    check(!$node->nodes, 'No import menu without permissions');
    $allowed = true;
    $PAGE->cm->modname = 'page';
    local_pptxbook_extend_settings_navigation($node, $context);
    check(!$node->nodes, 'No import menu on another activity type');
    echo "$count checks passed. Moodle integration remains to be tested.\n";
} finally {
    remove_tree($dir);
}

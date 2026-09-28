# Slide images to Book

Moodle local plugin `local_pptxbook`, version **0.2.4 beta**.
Imports a ZIP of PNG/JPEG slide images into an **existing Book activity**.
No LibreOffice, shell commands or external conversion service is required.
Export your slides as images in PowerPoint before creating the ZIP; direct PPTX
conversion is not included. English, French and Dutch interfaces are included.

[Documentation en français](README.md).

## Requirements and installation

Target Moodle versions: 4.5, 5.0, 5.1 and 5.2, with a PHP version supported by Moodle,
and PHP ZIP/GD extensions enabled. Automated compatibility checks pass on all target versions.

Upload `local_pptxbook-1.0.0.zip` under Site administration > Plugins > Install plugins.
For manual installation, copy `pptxbook` to `local/` (4.5–5.0) or `public/local/`
(5.1–5.2), then visit Site administration > Notifications. For upgrades replace the
whole plugin directory to remove obsolete conversion files; do not uninstall first.
The component name is retained for upgrades from 0.1.0; obsolete LibreOffice/Poppler
settings are removed. Existing Books are preserved.

Settings are under Site administration > Plugins > Local plugins > Slide images to Book,
not under activity modules. The Book module must be enabled.

## Usage

1. Export slides as PNG or JPEG and name them consistently (Slide1.png, Slide2.png, Slide10.png).
2. Compress the images into a ZIP, optionally within one containing directory.
3. Open the target Book, then choose **More > Import images**.
4. Upload the ZIP. Each image becomes a new main chapter at the end of the Book.

File names without extensions become chapter titles, with underscores replaced by spaces.
Images are sorted naturally by full relative path, ignoring case. Existing chapters
and activity visibility are preserved. New chapters are visible immediately in a visible
Book: hide the activity first when preparing content. Repeating an import appends duplicates.

Both `local/pptxbook:import` and `mod/book:edit` are required in the Book context.
Editing teachers and managers receive the import capability by default.

## Limits and data handling

Default limits are 25 MB per ZIP and 50 images, configurable up to 100 MB and 200 images.
PHP, site and course upload limits still apply. Fixed safeguards are 10 MB per image,
8 million pixels per image, 16,000 pixels per side, 128 MB total uncompressed data and
5,000 archive entries. A 1920×1080 image is suitable. Images are not resized.

The plugin rejects unsafe paths, symlinks, duplicate names, encrypted images, unsupported
files and invalid image data. Common macOS/Windows metadata is ignored. SVG, PDF, PPTX
and nested archives are not accepted. All images are validated before chapters are added.
Imports use Moodle transactions and a per-Book lock; the lock does not block the native
Book editor, so avoid simultaneous manual editing. Large imports should be split into
smaller ZIPs. Check the Book before retrying after a connection interruption.

Images are stored in the native `mod_book/chapter` file area and remain unchanged.
Book access controls, backup/restore and deletion therefore apply. No external service
receives the images; the plugin creates no personal-data tables or separate file area.
Draft uploads and request temporary files follow Moodle's retention/cleanup mechanisms.

File names supply initial alternative text, not an accessible transcription. Add meaningful
text descriptions to chapters for charts and slides containing text. OCR is not provided.

## Development and validation

GPL v3 or later; see LICENSE.txt. Copyright 2026 EDUNOVER.
Source: https://github.com/edunover/moodle-local_pptxbook
Issues: https://github.com/edunover/moodle-local_pptxbook/issues
These links require access while the repository is private.

GitHub Actions passed on 28 September 2026 for Moodle 4.5/PHP 8.1, 5.0/PHP 8.2,
5.1/PHP 8.3 and 5.2/PHP 8.3 with PostgreSQL 17, plus Moodle 5.2/PHP 8.3 with MariaDB 11. Checks include PHP lint, Moodle
coding standards, PHPDoc, plugin structure, upgrade savepoints and plugin PHPUnit tests.
[Successful run](https://github.com/edunover/moodle-local_pptxbook/actions/runs/36445221979).

Browser acceptance, upgrade, invalid archive, backup/restore and deletion tests passed. Hidden-Book access, storage rollback and true concurrent imports remain to be tested.
See [TESTING.en.md](TESTING.en.md) and [GITHUB_TESTS.en.md](GITHUB_TESTS.en.md). This release is not Marketplace-approved.

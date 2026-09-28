# Installing version 1.0.0

1. Back up the site before an upgrade.
2. Open **Site administration → Plugins → Install plugins**.
3. Select `local_pptxbook-1.0.0.zip` and complete the installation or upgrade.
4. Settings are under **Plugins → Local plugins → Slide images to Book**.
5. In a Book, open **More → Import images**.

The PHP ZIP and GD extensions must be enabled. LibreOffice is not required.
Export the slides as PNG/JPEG first, then compress the images into a ZIP.

For a manual installation, place the `pptxbook` directory in `local/` on Moodle
4.5–5.0, or in `public/local/` on Moodle 5.1–5.2. Replace the complete previous
plugin directory before opening Moodle notifications. Do not uninstall it first.

Read the [English documentation](README.en.md) for limits and the
[English test report](TESTING.en.md) for the exact validation status.


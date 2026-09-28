# Validation report

## GitHub Actions — 28 September 2026

[Successful run](https://github.com/edunover/moodle-local_pptxbook/actions/runs/36445221979),
commit `8bca96ea130be424432b1eee28a8e877971074b3`.

| Moodle | PHP | Database | Result |
|---|---|---|---|
| 4.5 | 8.1 | PostgreSQL 17 | Passed |
| 5.0 | 8.2 | PostgreSQL 17 | Passed |
| 5.1 | 8.3 | PostgreSQL 17 | Passed |
| 5.2 | 8.3 | PostgreSQL 17 | Passed |
| 5.2 | 8.3 | MariaDB 11 | Passed |

Each job installed Moodle and ran PHP lint, Moodle CodeSniffer with no accepted
warnings, PHPDoc with no accepted warnings, plugin structure validation, upgrade
savepoint validation and PHPUnit.

## PHPUnit tests

- `archive_test.php`: two tests covering natural ordering, titles, PNG/JPEG formats,
  byte preservation, system metadata, forbidden files, unsafe paths, duplicate names,
  corruption, limits, symbolic links and cleanup after an error.
- `importer_test.php`: two tests covering chapter creation, preservation of existing
  content and visibility, native image storage, successive imports and denial for a student.

## Manual test on Moodle 4.5.11+

Version 0.2.4 was installed through Moodle's ZIP installer on PHP 8.3.33. Moodle
recognised `local_pptxbook`, its dependency on `mod_book`, the supported range 4.5–5.2
and its settings page. The only installation notice was the declared beta maturity.

A ZIP containing three images was imported into an existing Book. Existing content
was retained, the three chapters were appended in natural order (`1`, `2`, `10`),
their names were correct and each image was served from Moodle's native Book file area.
Previous/next chapter navigation and the Cancel action worked.

The More menu, form and action were checked in English, French and Dutch. With the
student role, the import action was absent from the More menu and direct access to
the import URL was denied. The administrator role was restored after the test.

An archive containing an unsupported file was rejected with an explicit message and
without adding a chapter. Upgrade from an earlier plugin version, Book backup and
restore, and deletion of imported chapters and files were also tested successfully.

## Issues found and fixed by the test runs

The first run detected incorrectly ordered translation keys. They were sorted in all
three languages. Test coverage metadata was made compatible with PHPUnit 9 and 11,
then its placement was adjusted for the Moodle 4.5 checker. The latest run passed in
all five PostgreSQL and MariaDB environments.

## Remaining release checks

- Access to images in a hidden Book and logged-out access.
- Rollback after a storage error and two truly concurrent imports.

The automated and manual results do not constitute Moodle Marketplace approval.

## Running the checks again

See the [English GitHub Actions guide](GITHUB_TESTS.en.md).

# Running the Moodle tests with GitHub Actions

The workflow is stored in `.github/workflows/moodle-tests.yml`. It runs for every
push and pull request and can also be started manually.

## Test environments

GitHub starts clean Ubuntu environments and uses `moodle-plugin-ci` to install
Moodle, create the test database and run the plugin checks.

| Moodle | Moodle branch | PHP | Database |
|---|---|---|---|
| 4.5 | `MOODLE_405_STABLE` | 8.1 | PostgreSQL 17 |
| 5.0 | `MOODLE_500_STABLE` | 8.2 | PostgreSQL 17 |
| 5.1 | `MOODLE_501_STABLE` | 8.3 | PostgreSQL 17 |
| 5.2 | `MOODLE_502_STABLE` | 8.3 | PostgreSQL 17 |
| 5.2 | `MOODLE_502_STABLE` | 8.3 | MariaDB 11 |

MariaDB uses the Moodle `mariadb` driver, UTF-8 MB4 and the health check from
the official MariaDB image.

## Checks performed

1. Moodle and the test database are installed.
2. PHP syntax is checked.
3. Moodle coding standards run with no accepted warnings.
4. PHPDoc is checked with no accepted warnings.
5. The plugin structure and metadata are validated.
6. Upgrade savepoints are checked.
7. The plugin PHPUnit suite is run with warnings treated as failures.

## Starting a manual run

1. Open the repository on GitHub.
2. Select **Actions**.
3. Select **Tests Moodle**.
4. Choose **Run workflow**, select `main`, then confirm.
5. Wait for all five jobs to become green.

No repository secret is required. The databases exist only inside temporary GitHub
jobs and never use credentials or data from the production or test Moodle site.

## Reading a failure

Open the failed job and then the red step. The first error normally identifies the
file, line or validation rule to correct. Commit and push the correction; the workflow
starts again automatically.

Do not describe a release as passing the complete matrix until all five jobs in the
same workflow run have succeeded. Automated checks still require the manual validation
listed in [TESTING.en.md](TESTING.en.md).

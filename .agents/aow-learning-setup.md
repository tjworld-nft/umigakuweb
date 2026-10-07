# AOW Learning account system

## Production setup

1. Copy `aow-learning/access-config.example.php` to `aow-learning/access-config.php`.
2. Set `database_path` to `dirname(__DIR__, 2) . '/private-data/aow-learning.sqlite'` so the database remains outside `public_html`.
3. Generate `app_key` with `php -r "echo bin2hex(random_bytes(32)), PHP_EOL;"`.
4. Generate the admin hash with `php -r "echo password_hash('ADMIN-PASSWORD', PASSWORD_DEFAULT), PHP_EOL;"`.
5. Upload `access-config.php` manually. It is intentionally excluded from Git and automatic deployment.
6. Open `/aow-learning/admin.php`, issue a one-time enrollment code, then test registration in a private browser window.
7. Verify that unauthenticated requests to `/aow-learning/index.html` return 403 and `/aow-learning/course.php?course=aow` redirects to login.

## Data model

- No name, email address, or phone number is stored.
- `users` stores an anonymous learner ID, password hash, recovery-code hash, status, and timestamps.
- `enrollments` grants course access.
- `course_progress` stores authoritative server-side progress and completion records.
- `invite_codes` stores only keyed hashes of one-time codes.
- `login_attempts` stores keyed hashes for throttling, not raw IP addresses.

## Adding courses later

Add the course to the `courses` seed in `lib.php`, create its protected course route and answer key, then set `active = 1` when its content is ready. Existing users can redeem a newly issued course code from their dashboard.


## Night Diver SP (2026-10-07)

- Course slug: `night`; protected route: `/aow-learning/course.php?course=night`.
- Content: `night.html`, `night.css`; six lessons and 30 original questions.
- The existing admin can issue a code for Night alone or with AOW. Existing learners redeem a new code from their dashboard.
- Progress and `NIGHT-YYYYMMDD-XXXXXXXX` learning records are independent of AOW. Schema 4 adds the catalog entry without replacing existing student records.
- Admin previews do not save student progress. Direct `.html` requests remain forbidden by `.htaccess`.
- Completion means **shop pre-study completed / instructor confirmation pending**. It does not certify completion of official PADI eLearning, knowledge development, practical dives, or a specialty certification.
- Before formal course use, the responsible instructor must compare all content with the current Night Diver Specialty Instructor Guide, required official learning materials, Knowledge Review, and current Training Bulletins. The dedicated Night SP guide was not available for full verification.
- Research scope and limitations: `.agents/night-diver-learning.md`.
- Regression check: `php .agents/tests/learning-courses.php` (uses and removes a temporary SQLite database; never loads production student data).

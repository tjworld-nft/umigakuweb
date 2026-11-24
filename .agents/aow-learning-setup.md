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

## Dry Suit Diver SP pre-study (2026-10-07)

- `dry` is a separate protected course at `course.php?course=dry`, with six lessons and 30 original questions.
- Admin preview, invite-code selection, progress, and `DRY-` learning records use the existing management interface. AOW, night, and dry states remain independent.
- Schema version 5 adds/enables dry without replacing accounts, enrollments, or course progress. PHP provides the client course configuration and the course-specific CSS version.
- This records shop pre-study completion, pending instructor review. Verify the current Dry Suit Specialty Instructor Guide, official learning materials and Knowledge Review before using it as formal knowledge development. It does not issue PADI certification.
- Research evidence stays in `.agents/dry-diver-learning.md`. Student pages have no reference link lists, as requested.
- Regression: `php .agents/tests/learning-courses.php` runs 49 checks in a disposable isolated database.


## DPV / Subnado Plus SP pre-study (2026-10-16)

- `dpv` is a protected course at `course.php?course=dpv`, with seven lessons and 35 original questions.
- Schema 6 adds the active course without changing existing accounts, permissions, or AOW/night/dry records. Enrollment still requires its own invite code.
- Admin previews and course-code issuance use the existing interface; `DPV-` records remain independent. Completion is shop pre-study, pending instructor review.
- Editable content: `.agents/dpv-learning-content.json`; generate with `python3 .agents/build-dpv.py`. Research, model review details and validation: `.agents/dpv-diver-learning.md`.
  (These DPV source files were not committed to this repository; they are kept in the Codex worktree `~/.codex/worktrees/dpv-learning/海学HP.php/.agents/` on this Mac.)
- DPV-only guided interface resumes at unresolved questions, marks the visible chapter, keeps feedback above the bottom bar, and blocks answers if initial progress loading fails.
- Regression suite now has 79 checks. An additional 46 isolated HTTP checks and browser completion/reload/failure checks passed.

## Underwater Naturalist SP pre-study (2026-11-24)

- `naturalist` is a protected course at `course.php?course=naturalist`, with eight lessons and 40 original questions, using the same guided interface as DPV (`guidedStudy` in `app.js`; shared `dpv.css` plus `naturalist.css`).
- Schema 7 adds the active course without changing existing accounts, permissions, or AOW/night/dry/DPV records. Enrollment still requires its own invite code. Completion records use `NAT-` and stay "pending instructor review".
- Editable content: `.agents/naturalist-learning-content.json`; generate with `python3 .agents/build-naturalist.py` (it stops if the answers differ from `course_answer_key()` in `lib.php`). Photos: `aow-learning/assets/naturalist/` (tool `.agents/naturalist-tools/make_photos.py`). Sources, review and open items: `.agents/naturalist-diver-learning.md`.
- Regression suite now has 101 checks.

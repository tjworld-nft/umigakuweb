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

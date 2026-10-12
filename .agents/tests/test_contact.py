#!/usr/bin/env python3
"""Offline contact regression tests: python3 .agents/tests/test_contact.py.

AGENTS.md prohibits local HTTP POST tests because a previous PHP server queued
real mail. This suite starts NO HTTP server and makes NO network requests.
It invokes PHP CLI with synthetic request globals supplied over stdin. PHP's
sendmail_path is verified before the handler runs and points exclusively to a
temporary recorder that cannot send mail. State/session files are also private
temporary fixtures. Requires PHP CLI with mbstring and Python standard library.
"""

import base64
import concurrent.futures
from email import policy
from email.parser import BytesParser
import json
import os
from pathlib import Path
import re
import shutil
import subprocess
import sys
import tempfile
import threading
import time
import unittest


ROOT = Path(__file__).resolve().parents[2]
PHP = shutil.which("php")
MARKER = "\n__CONTACT_TEST_META__"


class Client:
    def __init__(self, server, ip="192.0.2.1"):
        self.server = server
        self.ip = ip
        self.cookie = ""

    def request(self, fields=None, path="/contact/send_mail.php", extra_headers=None, method=None):
        headers = {"Accept": "application/json"}
        headers.update(extra_headers or {})
        request = {
            "server": {"REQUEST_METHOD": method or ("GET" if fields is None else "POST"),
                       "HTTP_HOST": "miura-diving.com", "HTTPS": "on",
                       "REQUEST_URI": path, "REMOTE_ADDR": self.ip,
                       "CONTENT_LENGTH": len(json.dumps(fields or {}).encode())},
            "get": {"action": "token"} if path.endswith("?action=token") else {},
            "post": fields or {},
            "cookie": {"UMIGAKU_CONTACT": self.cookie} if self.cookie else {},
        }
        request["server"].update({"HTTP_" + key.upper().replace("-", "_"): value
                                  for key, value in headers.items()})
        result = subprocess.run(self.server.command, input=json.dumps(request),
                                text=True, capture_output=True, env=self.server.env, timeout=20)
        with self.server.log_lock:
            self.server.logs.append(result.stderr)
        if result.returncode != 0 or MARKER not in result.stdout:
            raise AssertionError(f"PHP harness failed ({result.returncode}): {result.stderr}\n{result.stdout[:1000]}")
        body, metadata = result.stdout.rsplit(MARKER, 1)
        meta = json.loads(metadata)
        if meta["session_id"]:
            self.cookie = meta["session_id"]
        if headers["Accept"] == "application/json":
            try:
                payload = json.loads(body)
            except json.JSONDecodeError as error:
                raise AssertionError(f"Non-JSON handler output: {body!r}; metadata: {meta}") from error
        else:
            payload = body
        return meta["status"], payload, meta

    def token(self, age=4):
        status, result, meta = self.request(path="/contact/send_mail.php?action=token")
        assert status == 200 and result.get("success") is True, (status, result)
        assert re.fullmatch(r"[a-f0-9]{64}", result.get("token", "")), result
        assert result.get("expires_in") == 7200, result
        assert result.get("min_wait") == 3, result
        assert meta["session_name"] == "UMIGAKU_CONTACT", meta
        assert self.cookie, "No session was created"
        if age:
            self.age_session(age)
        return result["token"]

    def age_session(self, seconds):
        session_file = self.server.sessions / ("sess_" + self.cookie)
        payload = session_file.read_text()
        now = int(time.time())
        changed = 0

        def age(match):
            nonlocal changed
            value = int(match[1])
            if now - 86400 < value <= now + 5:
                changed += 1
                return f"i:{value - seconds};"
            return match[0]

        payload = re.sub(r"i:(\d{10});", age, payload)
        assert changed, "Cannot find issuance timestamp in isolated test session"
        session_file.write_text(payload)

    def submit(self, token=None, **updates):
        fields = self.fields(token, **updates)
        return self.request(fields)

    @staticmethod
    def fields(token=None, **updates):
        fields = {"name": "検証担当", "kana": "ケンショウタントウ",
                  "email": "inquiry@example.test", "phone": "090-0000-0000",
                  "category": "ファンダイビング", "date": "2026-11-01", "people": "2名",
                  "message": "次の週末のファンダイビングについて相談したいです。",
                  "ref": "home-course", "website": ""}
        if token is not None:
            fields["csrf_token"] = token
        fields.update(updates)
        return fields


class CliHarness:
    """Executes isolated PHP CLI processes; never opens a network socket."""

    def __init__(self):
        self.temp = tempfile.TemporaryDirectory(prefix="umigaku-contact-test-")
        self.root = Path(self.temp.name)
        self.state = self.root / "state"
        self.sessions = self.state / "sessions"
        self.mail_log = self.root / "mail.jsonl"
        self.mail_fail = self.root / "mail-fail"
        self.logs = []
        self.log_lock = threading.Lock()
        transport = self.root / "record-mail"
        transport.write_text(
            f"#!{sys.executable}\n"
            "import base64, fcntl, json, os, sys\n"
            "payload = sys.stdin.buffer.read()\n"
            "with open(os.environ['UMIGAKU_TEST_MAIL_LOG'], 'a') as out:\n"
            "    fcntl.flock(out, fcntl.LOCK_EX)\n"
            "    out.write(json.dumps({'data': base64.b64encode(payload).decode()}) + '\\n')\n"
            "sys.exit(1 if os.path.exists(os.environ['UMIGAKU_TEST_MAIL_FAIL']) else 0)\n"
        )
        transport.chmod(0o700)
        harness = self.root / "request.php"
        harness.write_text(
            "<?php\n"
            "if (PHP_SAPI !== 'cli' || ini_get('sendmail_path') !== getenv('UMIGAKU_TEST_TRANSPORT')) {\n"
            "    fwrite(STDERR, 'Refusing unsafe mail transport'); exit(97);\n"
            "}\n"
            "$request = json_decode(stream_get_contents(STDIN), true, 512, JSON_THROW_ON_ERROR);\n"
            "$_SERVER = $request['server']; $_GET = $request['get'];\n"
            "$_POST = $request['post']; $_COOKIE = $request['cookie'];\n"
            "register_shutdown_function(function () {\n"
            "    echo \"\\n__CONTACT_TEST_META__\" . json_encode([\n"
            "        'status' => http_response_code() ?: 200,\n"
            "        'session_id' => session_id(), 'session_name' => session_name(),\n"
            "        'cookie_params' => session_get_cookie_params(),\n"
            "        'headers' => headers_list()]);\n"
            "});\n"
            "$handlers = [\n"
            "    '/contact/send_mail.php' => " + json.dumps(str(ROOT / "contact" / "send_mail.php"), ensure_ascii=False) + ",\n"
            "    '/contact/contact-guard.php' => " + json.dumps(str(ROOT / "contact" / "contact-guard.php"), ensure_ascii=False) + ",\n"
            "    '/contact-handler.php' => " + json.dumps(str(ROOT / "contact-handler.php"), ensure_ascii=False) + "];\n"
            "require $handlers[explode('?', $_SERVER['REQUEST_URI'])[0]];\n"
        )
        self.env = dict(os.environ)
        self.env.update({"UMIGAKU_CONTACT_STATE_DIR": str(self.state),
                         "UMIGAKU_TEST_MAIL_LOG": str(self.mail_log),
                         "UMIGAKU_TEST_MAIL_FAIL": str(self.mail_fail),
                         "UMIGAKU_TEST_TRANSPORT": str(transport)})
        self.command = [PHP, "-d", f"sendmail_path={transport}",
                        "-d", "session.serialize_handler=php", "-d", "display_errors=0", str(harness)]

    def mails(self):
        if not self.mail_log.exists():
            return []
        return [BytesParser(policy=policy.default).parsebytes(base64.b64decode(json.loads(line)["data"]))
                for line in self.mail_log.read_text().splitlines()]

    def output(self):
        return "\n".join(self.logs)

    def close(self):
        self.temp.cleanup()


@unittest.skipUnless(PHP, "PHP CLI is required")
class ContactSecurityTests(unittest.TestCase):
    def setUp(self):
        self.server = CliHarness()
        self.addCleanup(self.server.close)
        self.client = Client(self.server)

    def accepted(self, result):
        status, payload, _ = result
        self.assertEqual(status, 200, payload)
        self.assertIs(payload.get("success"), True, payload)

    def rejected(self, result, mails_before=0, codes=(400, 403, 409, 429, 503)):
        status, payload, _ = result
        self.assertIn(status, codes, payload)
        self.assertIs(payload.get("success"), False, payload)
        self.assertEqual(len(self.server.mails()), mails_before)

    def test_normal_booking_fields_and_source_preserved_in_mail(self):
        self.accepted(self.client.submit(self.client.token()))
        mails = self.server.mails()
        self.assertEqual(len(mails), 2)
        admin_body = mails[0].get_content()
        for value in ("検証担当", "ケンショウタントウ", "inquiry@example.test", "090-0000-0000",
                      "ファンダイビング", "2026-11-01", "2名", "home-course",
                      "次の週末のファンダイビングについて相談したいです。"):
            self.assertIn(value, admin_body)
        self.assertIn("info@miura-diving.com", mails[0]["To"])
        self.assertIn("inquiry@example.test", mails[0]["Reply-To"])
        self.assertIn("inquiry@example.test", mails[1]["To"])

    def test_english_inquiry_and_optional_empty_booking_fields(self):
        self.accepted(self.client.submit(self.client.token(), name="Alex Example", kana="", phone="",
                                        date="", people="", category="その他", ref="",
                                        message="Could we arrange a diving trip next month?"))
        self.assertEqual(len(self.server.mails()), 2)

    def test_admin_preserves_freeform_content_without_echoing_it_to_customer(self):
        message = "本文の保持を確認します。\nURL https://example.test/untrusted-link?a=1&b=2 と <example> と C:\\files。"
        self.accepted(self.client.submit(self.client.token(), message=message))
        mails = self.server.mails()
        self.assertEqual(len(mails), 2)
        self.assertIn(message, mails[0].get_content())
        self.assertNotIn("https://example.test/untrusted-link", mails[1].get_content())
        self.assertNotIn("本文の保持を確認します。", mails[1].get_content())

    def test_missing_and_forged_tokens_never_send(self):
        self.rejected(self.client.submit())
        self.rejected(self.client.submit("0" * 64))
        self.client.token()
        self.rejected(self.client.submit("f" * 64))

    def test_token_is_bound_to_its_session(self):
        token = self.client.token()
        other = Client(self.server)
        other.token()
        self.rejected(other.submit(token))
        self.accepted(self.client.submit(token))

    def test_fast_timestamp_cannot_be_spoofed_by_client(self):
        token = self.client.token(age=0)
        self.rejected(self.client.submit(token, ts="1000000000000"))
        self.client.age_session(4)
        self.accepted(self.client.submit(token))

    def test_expired_token_never_sends(self):
        self.rejected(self.client.submit(self.client.token(age=7201)))

    def test_future_token_timestamp_never_sends(self):
        self.rejected(self.client.submit(self.client.token(age=-60)))

    def test_cookie_and_origin_protection(self):
        _, _, meta = self.client.request(path="/contact/send_mail.php?action=token")
        self.assertIs(meta["cookie_params"]["httponly"], True)
        self.assertIs(meta["cookie_params"]["secure"], True)
        self.assertEqual(meta["cookie_params"]["samesite"], "Strict")
        self.rejected(self.client.request(path="/contact/send_mail.php?action=token",
                      extra_headers={"Origin": "https://attacker.example"}), codes=(403,))
        self.rejected(self.client.request(path="/contact/send_mail.php?action=token",
                      extra_headers={"Sec-Fetch-Site": "cross-site"}), codes=(403,))

    def test_token_replay_with_changed_content_never_sends(self):
        token = self.client.token()
        self.accepted(self.client.submit(token))
        self.rejected(self.client.submit(token, email="changed@example.test", message="別の内容です。"), 2)

    def test_two_open_tabs_can_each_submit(self):
        first, second = self.client.token(), self.client.token()
        self.assertNotEqual(first, second)
        self.accepted(self.client.submit(first))
        self.accepted(self.client.submit(second, email="second@example.test", message="別件のお問い合わせです。"))
        self.assertEqual(len(self.server.mails()), 4)

    def test_validation_error_can_be_corrected_with_same_token(self):
        token = self.client.token()
        self.rejected(self.client.submit(token, email="invalid"))
        self.accepted(self.client.submit(token))

    def test_arrays_in_all_scalar_fields_rejected_without_php_warnings(self):
        for field in ("csrf_token", "name", "kana", "email", "message", "phone", "category", "date", "people", "ref", "website"):
            with self.subTest(field=field):
                fields = self.client.fields(self.client.token())
                fields[field] = ["malformed"]
                self.rejected(self.client.request(fields))
        self.assertNotRegex(self.server.output(), r"PHP (?:Warning|Fatal error|Deprecated)")

    def test_unknown_category_date_and_people_rejected(self):
        token = self.client.token()
        for changes in ({"category": "invalid"}, {"date": "2026-02-30"}, {"date": "tomorrow"},
                        {"people": "100人"}, {"date": "2026-1-2"}):
            with self.subTest(changes=changes):
                self.rejected(self.client.submit(token, **changes))
        self.accepted(self.client.submit(token))

    def test_all_existing_categories_and_people_choices_accepted(self):
        categories = ["体験ダイビング", "OWDライセンス", "AOWライセンス", "ファンダイビング",
                      "リフレッシュ", "マリンアクティビティ", "書籍・講座", "その他"]
        people = ["", "1名", "2名", "3名", "4名", "5名以上"]
        for number, category in enumerate(categories):
            client = Client(self.server, f"192.0.2.{number + 1}")
            self.accepted(client.submit(client.token(), name=f"検証{number}", category=category,
                                        people=people[number % len(people)], email=f"category{number}@example.test",
                                        message=f"各種コースのお問い合わせ確認 {number}"))
        self.assertEqual(len(self.server.mails()), 16)

    def test_header_injection_rejected(self):
        self.rejected(self.client.submit(self.client.token(), email="valid@example.test\r\nBcc: another@example.test"))

    def test_ascii_name_cannot_add_another_reply_to_address(self):
        crafted_name = "Other <other@example.test>, User"
        self.accepted(self.client.submit(self.client.token(), name=crafted_name))
        admin = self.server.mails()[0]
        self.assertEqual([address.addr_spec for address in admin["Reply-To"].addresses],
                         ["inquiry@example.test"])
        self.assertIn(crafted_name, admin.get_content())

    def test_overlong_fields_rejected(self):
        token = self.client.token()
        self.rejected(self.client.submit(token, name="a" * 101))
        self.rejected(self.client.submit(token, message="a" * 5001))
        self.rejected(self.client.submit(token, message="a" * 33000), codes=(413,))

    def test_honeypot_never_sends(self):
        result = self.client.submit(self.client.token(), website="https://example.test/bot")
        self.assertIn(result[0], (200, 400, 403))
        self.assertEqual(len(self.server.mails()), 0)

    def test_duplicate_email_and_booking_blocked_across_ip(self):
        self.accepted(self.client.submit(self.client.token()))
        other = Client(self.server, "192.0.2.2")
        self.rejected(other.submit(other.token()), 2, codes=(409,))

    def test_duplicate_normalizes_email_case_and_body_whitespace(self):
        self.accepted(self.client.submit(self.client.token(), message="Please discuss diving"))
        other = Client(self.server, "192.0.2.2")
        self.rejected(other.submit(other.token(), email="INQUIRY@example.test",
                                   message="  please\n discuss\t diving  "), 2, codes=(409,))

    def test_different_booking_details_do_not_count_as_duplicate(self):
        self.accepted(self.client.submit(self.client.token()))
        for index, changes in enumerate(({"category": "体験ダイビング"}, {"date": "2026-11-02"}, {"people": "3名"})):
            # Keep the original and one amended booking to isolate duplicate
            # matching from the separate three-per-hour email limit.
            state_path = self.server.state / "submissions.json"
            state = json.loads(state_path.read_text())
            state["events"] = state["events"][:1]
            state_path.write_text(json.dumps(state))
            other = Client(self.server, f"192.0.2.{index + 2}")
            self.accepted(other.submit(other.token(), **changes))
        self.assertEqual(len(self.server.mails()), 8)

    def test_ip_limit_persists_across_new_sessions(self):
        for number in range(4):
            client = Client(self.server)
            result = client.submit(client.token(), email=f"ip{number}@example.test", message=f"IP limit inquiry number {number}")
            if number < 3:
                self.accepted(result)
            else:
                self.rejected(result, 6, codes=(429,))

    def test_email_rate_limit_across_different_ips(self):
        for number in range(4):
            client = Client(self.server, f"192.0.2.{number + 1}")
            result = client.submit(client.token(), message=f"Different inquiry number {number}")
            if number < 3:
                self.accepted(result)
            else:
                self.rejected(result, 6, codes=(429,))

    def test_repeated_body_rate_limit_across_ips_and_emails(self):
        for number in range(4):
            client = Client(self.server, f"192.0.2.{number + 1}")
            result = client.submit(client.token(), name=f"Customer {number}", email=f"body{number}@example.test",
                                   message="This repeated automated inquiry has enough characters to identify a repeated campaign.")
            if number < 3:
                self.accepted(result)
            else:
                self.rejected(result, 6, codes=(429,))

    def test_short_common_question_allows_distinct_customers(self):
        for number in range(4):
            client = Client(self.server, f"192.0.2.{number + 1}")
            self.accepted(client.submit(client.token(), name=f"Customer {number}", email=f"short{number}@example.test",
                                        message="料金を教えてください。"))
        self.assertEqual(len(self.server.mails()), 8)

    def test_ip_day_limit_survives_expired_short_window(self):
        state_path = self.server.state / "submissions.json"
        for number in range(21):
            client = Client(self.server)
            result = client.submit(client.token(), email=f"day{number}@example.test", message=f"Daily limit inquiry number {number}")
            if number < 20:
                self.accepted(result)
                state = json.loads(state_path.read_text())
                for event in state["events"]:
                    event["t"] = int(time.time()) - 601
                state_path.write_text(json.dumps(state))
            else:
                self.rejected(result, 40, codes=(429,))

    def parallel_requests(self, clients, tokens, different_content):
        barrier = threading.Barrier(len(clients))

        def submit(number):
            barrier.wait(timeout=5)
            updates = {"email": f"race{number}@example.test", "message": f"Concurrent inquiry number {number}"} if different_content else {}
            return clients[number].submit(tokens[number], **updates)

        with concurrent.futures.ThreadPoolExecutor(max_workers=len(clients)) as pool:
            return list(pool.map(submit, range(len(clients))))

    def test_concurrent_ip_limit_is_atomic(self):
        clients = [Client(self.server) for _ in range(6)]
        results = self.parallel_requests(clients, [client.token() for client in clients], True)
        self.assertEqual(sum(result[0] == 200 for result in results), 3, results)
        self.assertEqual(sum(result[0] == 429 for result in results), 3, results)
        self.assertEqual(len(self.server.mails()), 6)

    def test_concurrent_duplicates_only_send_one_pair_of_mails(self):
        clients = [Client(self.server, f"192.0.2.{number + 1}") for number in range(4)]
        results = self.parallel_requests(clients, [client.token() for client in clients], False)
        self.assertEqual(sum(result[0] == 200 for result in results), 1, results)
        self.assertEqual(sum(result[0] == 409 for result in results), 3, results)
        self.assertEqual(len(self.server.mails()), 2)

    def test_concurrent_replays_only_send_one_pair_of_mails(self):
        token = self.client.token()
        clients = [Client(self.server, f"192.0.2.{number + 1}") for number in range(4)]
        for client in clients:
            client.cookie = self.client.cookie
        results = self.parallel_requests(clients, [token] * len(clients), True)
        self.assertEqual(sum(result[0] == 200 for result in results), 1, results)
        self.assertEqual(sum(result[0] == 403 for result in results), 3, results)
        self.assertEqual(len(self.server.mails()), 2)

    def test_unavailable_state_directory_fails_closed(self):
        token = self.client.token()
        shutil.rmtree(self.server.state)
        self.server.state.write_text("not a directory")
        self.rejected(self.client.submit(token), codes=(503,))

    def test_corrupt_state_fails_closed(self):
        for contents in ("{truncated", "{}", '{"secret":"' + "a" * 64 + '","events":[{}]}'):
            with self.subTest(contents=contents):
                token = self.client.token()
                (self.server.state / "submissions.json").write_text(contents)
                self.rejected(self.client.submit(token), codes=(503,))

    @unittest.skipIf(hasattr(os, "geteuid") and os.geteuid() == 0, "root ignores ordinary write permissions")
    def test_unwritable_state_directory_fails_closed(self):
        token = self.client.token()
        self.server.state.chmod(0o500)
        try:
            self.rejected(self.client.submit(token), codes=(503,))
        finally:
            self.server.state.chmod(0o700)

    def test_failed_mail_releases_reservation_for_retry(self):
        self.server.mail_fail.touch()
        result = self.client.submit(self.client.token())
        self.assertEqual(result[0], 500, result)
        self.assertIs(result[1].get("success"), False)
        self.assertEqual(len(self.server.mails()), 1, "Failed admin mail must not trigger an auto-reply")
        state = json.loads((self.server.state / "submissions.json").read_text())
        self.assertEqual(state["events"], [])
        self.server.mail_fail.unlink()
        self.accepted(self.client.submit(self.client.token()))
        self.assertEqual(len(self.server.mails()), 3)

    def test_store_contains_no_plaintext_contact_or_ip_data(self):
        self.accepted(self.client.submit(self.client.token()))
        files = [path for path in self.server.state.rglob("*") if path.is_file()]
        self.assertTrue(files)
        state_text = "\n".join(path.read_text(errors="replace") for path in files)
        for value in ("inquiry@example.test", "192.0.2.1", "検証担当", "ファンダイビング", "090-0000-0000"):
            self.assertNotIn(value, state_text)

    def test_legacy_form_redirects_after_success_and_validation_error(self):
        token = self.client.token()
        rejected = self.client.request(self.client.fields(token, email="invalid"), extra_headers={"Accept": "text/html"})
        self.assertIn(rejected[0], (302, 303), rejected)
        self.assertEqual(rejected[1], "")
        self.assertEqual(len(self.server.mails()), 0)
        accepted = self.client.request(self.client.fields(token), extra_headers={"Accept": "text/html"})
        self.assertIn(accepted[0], (302, 303), accepted)
        self.assertEqual(accepted[1], "")
        self.assertEqual(len(self.server.mails()), 2)

    def test_retired_handler_never_sends_valid_legacy_fields(self):
        fields = {"name_kanji": "検証担当", "name_romaji": "Test Person",
                  "email": "legacy@example.test", "message": "旧送信先の安全性の検証です。"}
        self.rejected(self.client.request(fields, path="/contact-handler.php"), codes=(410,))
        html = self.client.request(fields, path="/contact-handler.php", extra_headers={"Accept": "text/html"})
        self.assertEqual(html[0], 410)
        self.assertIn('href="/contact/"', html[1])
        self.assertEqual(len(self.server.mails()), 0)

    def test_retired_handler_get_and_head_redirect(self):
        for method in ("GET", "HEAD"):
            result = self.client.request(path="/contact-handler.php", method=method,
                                         extra_headers={"Accept": "text/html"})
            self.assertEqual(result[0], 301, result)
            self.assertEqual(result[1], "")
        self.assertEqual(len(self.server.mails()), 0)

    def test_guard_direct_access_never_sends(self):
        for fields in (None, self.client.fields()):
            result = self.client.request(fields, path="/contact/contact-guard.php",
                                         extra_headers={"Accept": "text/html"})
            self.assertEqual(result[0], 404, result)
            self.assertEqual(result[1], "")
        self.assertEqual(len(self.server.mails()), 0)


if __name__ == "__main__":
    unittest.main(verbosity=2)

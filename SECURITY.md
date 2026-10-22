# Security maintenance

This repository contains a Typecho theme, not the PHP runtime or Typecho core.
Updating this theme does not patch PHP or update the installed CMS.

## Required server update (2026-10-22)

PHP's 2026-10-21 advisory covers CVE-2026-109618 and CVE-2026-109619.
Use PHP 8.2.35, 8.3.36, 8.4.27 or 8.5.12 (or newer), or obtain confirmation
from the hosting provider that both fixes were backported to its build.
A selector that only says "8.3" does not confirm that the patches are present.
The provider must also restart the PHP workers as part of deploying its update.

Official advisory: https://www.php.net/archive/2026.php#2026-10-21-1

## Theme deployment

1. Back up the current `index.php` and `comments.php` outside the public web root.
2. Replace those two files in the active theme directory (`usr/themes/ZutomaOS/`
   on kyokuya.cc). Preserve `music/`, `images/`, uploads, Typecho core and database.
3. Verify the homepage, article reader, category filtering, terminal and comment
   submission. The CSRF token is bound to the rendered page URL, so test comments
   from both the homepage and an individual article.
4. Do not upload `tests/` into the production theme directory.

The theme escapes plain text and HTML attributes, keeps titles out of inline
JavaScript, serializes the theme URL with JSON and appends terminal output as
text nodes. Article HTML and comment HTML still use Typecho's content renderer;
keep Typecho and plugins updated and restrict permission to publish raw HTML.
Enable comment moderation in Typecho to reduce spam; this patch does not delete
existing comments or change moderation settings.

## Regression checks

With PHP 8.2+ (also tested using PHP.wasm CLI), Python 3 and Node.js:

```sh
php -l index.php
php -l comments.php
php tests/render-security.php > /tmp/ztmy-security.html
python3 tests/check-render.py /tmp/ztmy-security.html /tmp/ztmy-security.js
node tests/check-script.cjs /tmp/ztmy-security.js
```

The fixture uses a mock Typecho archive and performs no live requests. It checks
HTML injection in titles, category names and comment authors, multiple-category
filtering, text-only terminal output, and the CSRF token's page URL. It does not
replace integration testing against the installed Typecho version.

# Validation

Validated in a disposable local WordPress 7.1.3 installation with PHP 8.3 and MariaDB 11.4.

- All theme PHP files passed PHP syntax checks; JavaScript passed `node --check`.
- Browser checks passed for the homepage, four services, three insights, desktop/mobile widths (375, 768, 1440 px) without horizontal overflow, menu expansion, and Escape handling.
- About, Services, Blog, a single article, search results, and HTTP 404 behavior passed.
- Contact form rejected an invalid nonce and invalid email; valid input produced a captured mail addressed to the configured WordPress admin mailbox; repeated submission was throttled.
- No browser JavaScript errors were observed.
- Starter XML parsed successfully with six pages and three posts. Equivalent content was loaded into the local site for functional checks; the WordPress importer UI itself was not tested.

Mail was intercepted locally through `tests/capture-mail.php`. External delivery and inbox receipt were not tested and must be verified on your hosting. That test helper is excluded from the installable theme ZIP. The complete project ZIP includes it for development only.

Previews in `previews/` show the tested theme with the optional starter content. This theme is designed for WordPress 6.4+ and PHP 8.0+, but the full version compatibility matrix was not tested.

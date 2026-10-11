# Nexora IT WordPress project

An installable WordPress theme for an IT company, with responsive design, editable homepage content, blog, regular pages, and a built-in inquiry form. Uses native WordPress features; no paid plugins or build step required.

## Install on WordPress

1. Download `dist/nexora-it.zip`.
2. In WordPress, open **Appearance → Themes → Add New → Upload Theme**.
3. Upload the ZIP, install, and activate **Nexora IT**.
4. Set your company name in **Settings → General**. Customize your logo under **Appearance → Customize → Site Identity** and homepage content under **IT Company Content**.
5. Optionally import `demo-content/nexora-starter.xml` through **Tools → Import → WordPress**. WordPress will offer its free importer if it is not installed. Assign the imported author to your account; no attachments need downloading. Starter content includes Home, About, Services, Contact, Careers, Blog, and three editable articles. Import once to avoid duplicates.
6. In **Settings → Reading**, choose a static homepage: **Home**, and posts page: **Blog**.
7. Build a primary menu under **Appearance → Menus**. Link to your imported pages or homepage sections such as `/#services` and `/#contact`. Assign footer links separately.
8. Replace all placeholder contact information, review starter copy, and create a suitable privacy policy under **Settings → Privacy**.

The theme also works immediately without importing demo content. Activation never overwrites your pages or settings. The separate project ZIP contains the theme ZIP, starter XML, documentation, source, and validation scripts; upload only **nexora-it.zip** through the theme installer.

## Contact form and delivery

Inquiries go to the verified **Administration Email Address** under Settings → General. The email displayed on the site is a separate Customizer field. Configure email delivery with your host or an SMTP plugin, submit a test inquiry, and verify actual receipt before launch. Local validation checks submission handling, not external inbox delivery. Nonce protection, honeypot, length limits, and per-IP throttling are included. The theme does not store submissions.

## Development

Theme files are in `nexora-it/`; edit with PHP 8.0+ and WordPress 6.4+. Keep custom changes in a child theme for future updates. CSS and JavaScript are local and need no npm dependencies. Demo articles are starter editorial content, not claims about customers or company achievements.

To repackage from the repository root:

```sh
zip -r dist/nexora-it.zip nexora-it
```

`tests/` contains local validation helpers for a disposable WordPress instance, not files required on production hosting.

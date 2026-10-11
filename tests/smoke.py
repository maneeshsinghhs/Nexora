"""Run against the disposable WordPress instance at localhost:8080."""
from pathlib import Path
from playwright.sync_api import sync_playwright
import json
import subprocess

URL = 'http://localhost:8080'
with sync_playwright() as p:
    browser = p.chromium.launch(executable_path='/usr/bin/chromium', args=['--no-sandbox'])
    page = browser.new_page(viewport={'width': 1440, 'height': 1000})
    errors = []
    page.on('pageerror', lambda error: errors.append(str(error)))
    assert page.goto(URL).status == 200
    assert page.locator('h1').inner_text() == 'Technology that moves your business forward.'
    assert page.locator('.service-card').count() == 4
    assert page.locator('.post-card').count() == 3
    assert page.locator('.hero').evaluate('(el) => getComputedStyle(el).display') == 'block'
    page.screenshot(path='/tmp/nexora-desktop.png', full_page=True)
    for width in [375, 768, 1440]:
        page.set_viewport_size({'width': width, 'height': 900})
        assert page.evaluate('document.documentElement.scrollWidth <= innerWidth'), f'Overflow at {width}'
    page.set_viewport_size({'width': 375, 'height': 812})
    toggle = page.get_by_role('button', name='Menu', exact=True)
    assert toggle.is_visible()
    assert not page.locator('#primary-navigation').is_visible()
    toggle.click()
    assert toggle.get_attribute('aria-expanded') == 'true'
    page.keyboard.press('Escape')
    assert toggle.get_attribute('aria-expanded') == 'false'
    page.screenshot(path='/tmp/nexora-mobile.png', full_page=True)
    for route, heading in [('/about/', 'About'), ('/services/', 'Services'), ('/blog/', 'Insights'), ('/planning-software/', 'Planning your next software project')]:
        assert page.goto(URL + route).status == 200
        assert page.locator('h1').inner_text() == heading
    assert page.goto(URL + '/missing-page/').status == 404
    assert page.get_by_role('heading', name='Let’s get you back on track.').is_visible()
    assert page.goto(URL + '/?s=cloud').status == 200
    assert page.locator('.post-card').count() >= 1
    page.goto(URL)
    nonce = page.locator('[name=nexora_nonce]').input_value()
    endpoint = URL + '/wp-admin/admin-post.php'
    payload = dict(action='nexora_contact', nexora_nonce=nonce, name='Test Visitor', email='visitor@example.com', message='Please help us build a secure application.')
    invalid = page.request.post(endpoint, form={**payload, 'nexora_nonce': 'bad'}, max_redirects=0)
    assert 'contact=error' in invalid.headers['location']
    invalid = page.request.post(endpoint, form={**payload, 'email': 'invalid'}, max_redirects=0)
    assert 'contact=invalid' in invalid.headers['location']
    sent = page.request.post(endpoint, form=payload, max_redirects=0)
    assert 'contact=sent' in sent.headers['location']
    mail = json.loads(subprocess.check_output(['docker','exec','nexora-wp','cat','/tmp/nexora-mail.json']))
    assert mail['to'] == 'admin@example.com'
    assert 'visitor@example.com' in mail['message']
    limited = page.request.post(endpoint, form=payload, max_redirects=0)
    assert 'contact=wait' in limited.headers['location']
    assert not errors, errors
    browser.close()
print('PASS: homepage, responsive widths, mobile menu, pages, blog, single post, search, 404, nonce rejection, input validation, captured mail, and rate limit.')

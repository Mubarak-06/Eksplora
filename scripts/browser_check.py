"""Local Chrome checks via DevTools. Uses installed websocket-client; no Node/npm."""
import base64
import json
import os
from pathlib import Path
import socket
import subprocess
import tempfile
import time
import urllib.request
import websocket

ROOT = Path(__file__).resolve().parents[1]
OUT = ROOT / 'storage' / 'app' / 'browser-check'
OUT.mkdir(parents=True, exist_ok=True)
BASE = 'http://127.0.0.1:8123'
flags = subprocess.CREATE_NO_WINDOW if os.name == 'nt' else 0
report = {'checks': [], 'errors': []}

def wait_http(url):
    for _ in range(100):
        try:
            with urllib.request.urlopen(url, timeout=1) as r:
                return r.read()
        except Exception:
            time.sleep(.1)
    raise RuntimeError('Server unavailable: ' + url)

class Browser:
    def __init__(self, wsurl):
        self.ws = websocket.create_connection(wsurl, timeout=15, suppress_origin=True)
        self.seq = 0
        self.call('Page.enable')
        self.call('Runtime.enable')
    def call(self, method, params=None):
        self.seq += 1
        seq = self.seq
        self.ws.send(json.dumps({'id': seq, 'method': method, 'params': params or {}}))
        while True:
            msg = json.loads(self.ws.recv())
            if msg.get('method') == 'Runtime.exceptionThrown':
                report['errors'].append(msg['params']['exceptionDetails'])
            if msg.get('id') == seq:
                if 'error' in msg:
                    raise RuntimeError(msg['error'])
                return msg.get('result', {})
    def js(self, expression):
        result = self.call('Runtime.evaluate', {'expression': expression, 'returnByValue': True, 'awaitPromise': True})
        if 'exceptionDetails' in result:
            raise RuntimeError(result['exceptionDetails'])
        return result.get('result', {}).get('value')
    def until(self, expression):
        for _ in range(100):
            if self.js(expression):
                return
            time.sleep(.1)
        raise RuntimeError('Timed out: ' + expression)
    def size(self, w, h):
        self.call('Emulation.setDeviceMetricsOverride', {'width': w, 'height': h, 'deviceScaleFactor': 1, 'mobile': False})
    def go(self, path):
        self.call('Page.navigate', {'url': BASE + path})
        self.until('document.readyState === "complete" && document.querySelector(".navbar") !== null')
        self.until('[...document.images].filter(i => i.loading !== "lazy").every(i => i.complete)')
    def screenshot(self, name):
        data = self.call('Page.captureScreenshot', {'format': 'png', 'captureBeyondViewport': False})
        (OUT / (name + '.png')).write_bytes(base64.b64decode(data['data']))
    def check(self, name, expression):
        value = self.js(expression)
        report['checks'].append({'name': name, 'passed': bool(value), 'value': value})
        if not value:
            raise AssertionError(name)

with tempfile.TemporaryDirectory(prefix='eksplora-chrome-') as profile:
    log = open(OUT / 'server.log', 'w', encoding='utf-8')
    server = subprocess.Popen(['php', '-S', '127.0.0.1:8123', '-t', 'public'], cwd=ROOT, stdout=log, stderr=log, creationflags=flags)
    chrome = None
    try:
        wait_http(BASE)
        chrome = subprocess.Popen([
            r'C:\Program Files\Google\Chrome\Application\chrome.exe', '--headless=new', '--disable-gpu', '--no-first-run',
            '--no-default-browser-check', '--remote-debugging-port=9229', '--remote-allow-origins=http://localhost:9229',
            '--user-data-dir=' + profile, 'about:blank'
        ], stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL, creationflags=flags)
        targets = json.loads(wait_http('http://localhost:9229/json'))
        browser = Browser(next(t['webSocketDebuggerUrl'] for t in targets if t['type'] == 'page'))
        browser.size(1280, 800)
        browser.go('/')
        browser.screenshot('home-desktop')
        browser.check('Home fits 1280x800 vertically', 'document.documentElement.scrollHeight <= innerHeight')
        browser.check('Home has no horizontal overflow', 'document.documentElement.scrollWidth <= innerWidth')
        browser.check('Hero CTA and statistics visible', 'document.querySelector(".stats-panel").getBoundingClientRect().bottom <= innerHeight && document.querySelector(".hero-button").getBoundingClientRect().bottom <= innerHeight')
        browser.js('document.querySelector(".search-toggle").click()')
        browser.check('Navbar search opens', '!document.querySelector("#nav-search").hidden')
        browser.js('document.querySelector("#global-q").value="haruan"; document.querySelector("#nav-search").requestSubmit()')
        browser.until('location.pathname === "/pencarian" && document.readyState === "complete"')
        browser.check('Navbar search finds organism', 'document.querySelector("main").textContent.includes("Ikan Haruan")')
        browser.go('/eksplorasi/rawa-lebak')
        browser.js('document.querySelector("#tab-flora").click()')
        browser.check('Ecosystem tab changes content', '!document.querySelector("#panel-flora").hidden && document.querySelector("#panel-deskripsi").hidden')
        browser.js('document.querySelector("#tab-pelestarian").click()')
        browser.check('Conservation tab available', '!document.querySelector("#panel-pelestarian").hidden')
        browser.go('/perbandingan')
        browser.js('document.querySelector(".swap").click()')
        browser.check('Comparison swap works', 'document.querySelector("#first").value === "hutan-mangrove" && document.querySelector("#second").value === "rawa-lebak"')
        browser.js('document.querySelector(".comparison-form").requestSubmit()')
        browser.until('location.search.includes("first=hutan-mangrove") && document.readyState === "complete"')
        browser.check('Comparison updates table', 'document.querySelector(".comparison-table thead").textContent.includes("Hutan Mangrove")')
        for width, height in [(1280, 800), (768, 1024), (390, 844)]:
            browser.size(width, height)
            for path in ['/', '/eksplorasi', '/tentang-lahan-basah', '/eksplorasi/rawa-lebak', '/flora-fauna', '/flora-fauna/ikan-haruan', '/perbandingan', '/kuis', '/masuk']:
                browser.go(path)
                browser.check(str(width) + ' no horizontal overflow ' + path, 'document.documentElement.scrollWidth <= innerWidth')
                browser.check(str(width) + ' no broken images ' + path, '[...document.images].filter(i => i.loading !== "lazy").every(i => i.naturalWidth > 0)')
                if width == 1280 and path in ['/eksplorasi', '/flora-fauna', '/kuis']:
                    browser.screenshot(path.strip('/'))
            if width == 390:
                browser.go('/')
                browser.screenshot('home-mobile')
                browser.js('document.querySelector(".menu-toggle").click()')
                browser.check('Mobile menu includes all 7 links', 'document.querySelector("#navigation").classList.contains("open") && document.querySelectorAll("#navigation a").length === 7')
        browser.size(1280, 800)
        for quiz in ['pilihan-ganda', 'tebak-gambar', 'mencocokkan']:
            browser.go('/kuis/' + quiz)
            browser.js('document.querySelector("#game-start").click()')
            for index in range(5):
                browser.until('document.querySelector(".choices") !== null && [...document.querySelectorAll(".choices button")].some(b => !b.disabled)')
                browser.js('document.querySelector(".choices button").click(); [...document.querySelectorAll("#game-content > button")].find(b => b.textContent === "Periksa Jawaban").click()')
                browser.until('document.querySelector("#game-content .notice") !== null')
                browser.check(quiz + ' answer ' + str(index + 1) + ' feedback', 'document.querySelector("#game-content .notice").textContent.includes("Jawaban yang sesuai")')
                browser.js('[...document.querySelectorAll("#game-content > button")].find(b => /Soal Berikutnya|Lihat Nilai/.test(b.textContent)).click()')
            browser.until('document.querySelector(".score") !== null')
            browser.check(quiz + ' completes', 'document.querySelector("#game-content").textContent.includes("Kuis Selesai!")')
        browser.go('/kuis/puzzle')
        browser.js('document.querySelector("#game-start").click()')
        browser.until('document.querySelectorAll(".puzzle-tile").length === 9')
        for i in range(9):
            labels = browser.js('[...document.querySelectorAll(".puzzle-tile")].map(b => Number(b.getAttribute("aria-label").split("potongan ")[1])-1)')
            if labels[i] != i:
                j = labels.index(i)
                browser.js(f'document.querySelectorAll(".puzzle-tile")[{i}].click(); document.querySelectorAll(".puzzle-tile")[{j}].click()')
        browser.until('document.querySelector(".score") !== null')
        browser.check('Puzzle detects solved image and verifies server score', 'document.querySelector(".score").textContent === "100/100"')
        browser.check('No browser JavaScript errors', 'true')
        browser.ws.close()
    except Exception as exc:
        report['errors'].append(str(exc))
        raise
    finally:
        (OUT / 'report.json').write_text(json.dumps(report, indent=2, ensure_ascii=False), encoding='utf-8')
        if chrome:
            chrome.terminate()
            chrome.wait(timeout=15)
        server.terminate()
        server.wait(timeout=10)
        log.close()
        print(json.dumps({'checks': len(report['checks']), 'failed': sum(not c['passed'] for c in report['checks']), 'errors': len(report['errors']), 'output': str(OUT)}))

import hashlib
import json
import sys
from html.parser import HTMLParser
from pathlib import Path

class Page(HTMLParser):
    def __init__(self):
        super().__init__(convert_charrefs=True)
        self.nodes = []
        self.scripts = []
        self.script = None
    def handle_starttag(self, tag, attrs):
        attrs = dict(attrs)
        self.nodes.append((tag, attrs))
        assert not any(key.startswith('on') and 'injected' in value for key, value in attrs.items()), attrs
        if tag == 'script':
            self.script = ''
    def handle_data(self, data):
        if self.script is not None:
            self.script += data
    def handle_endtag(self, tag):
        if tag == 'script':
            self.scripts.append(self.script)
            self.script = None

page = Page()
page.feed(Path(sys.argv[1]).read_text())
assert not any(tag == 'img' for tag, attrs in page.nodes), 'Payload created an image element'
assert len(page.scripts) == 3, 'Payload created an extra script element'
row = next(attrs for tag, attrs in page.nodes if attrs.get('class') == 'file-row')
assert json.loads(row['data-cats']) == ["quote'category", 'second']
assert "<img src=x" in row['data-title'], 'Title did not survive as text data'
token = next(attrs['value'] for tag, attrs in page.nodes if attrs.get('name') == '_')
assert token == hashlib.sha256(b'fixture-secret&https://example.test/?page=2').hexdigest()
assert '<strong>formatting</strong>' in Path(sys.argv[1]).read_text()
Path(sys.argv[2]).write_text(page.scripts[-1])
print('PASS: HTML injection, category round-trip, current-page CSRF token and article formatting')

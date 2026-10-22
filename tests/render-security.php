<?php
// Isolated rendering fixture: no database, server credentials or live writes.
define('__TYPECHO_ROOT_DIR__', __DIR__);
function _t($text) { return $text; }
const PAYLOAD = "\"'><img src=x onerror=globalThis.injected=1> & </script><script>globalThis.injected=1</script>";
class FixtureOptions {
    public $title = PAYLOAD;
    public $themeUrl = 'https://example.test/usr/themes/ZutomaOS/';
    public $adminUrl = 'https://example.test/admin/';
    public $commentsRequireMail = true;
}
class FixtureCategories {
    public $slug = "quote'category";
    public $name = PAYLOAD;
    private $read = false;
    public function to(&$target) { $target = $this; }
    public function next() { if ($this->read) return false; $this->read = true; return true; }
}
class FixtureComments {
    public $author = PAYLOAD;
    private $read = false;
    public function to(&$target) { $target = $this; }
    public function have() { return true; }
    public function next() { if ($this->read) return false; $this->read = true; return true; }
    public function theId() { echo 'comment-1'; }
    public function date($format) { echo '2026-10-22'; }
    public function content() { echo '<p>A normal comment</p>'; }
    public function pageNav(...$args) {}
}
class FixtureRequest {
    public function getRequestUrl() { return 'https://example.test/?page=2'; }
    public function getReferer() { return 'https://unrelated.test/'; }
}
class FixtureSecurity {
    public function getToken($url) { return hash('sha256', 'fixture-secret&' . $url); }
}
class FixtureArchive {
    public $options;
    public $request;
    public $title = PAYLOAD;
    public $cid = 1;
    public $commentUrl = 'https://example.test/index.php/archives/1/comment';
    public $categories;
    private $read = false;
    public function __construct() {
        $this->options = new FixtureOptions();
        $this->request = new FixtureRequest();
        $this->categories = [['slug' => "quote'category", 'name' => PAYLOAD], ['slug' => 'second', 'name' => 'Second']];
    }
    public function next() { if ($this->read) return false; $this->read = true; return true; }
    public function widget($name) { return $name === 'Widget_Security' ? new FixtureSecurity() : new FixtureCategories(); }
    public function date($format = null) { echo '2026-10-22'; }
    public function content() { echo '<p>Article <strong>formatting</strong></p>'; }
    public function need($path) { include __DIR__ . '/../' . $path; }
    public function comments() { return new FixtureComments(); }
    public function commentsNum(...$args) { echo '1'; }
    public function allow($permission) { return true; }
    public function respondId() { echo 'respond-1'; }
    public function remember($name) { echo htmlspecialchars(PAYLOAD, ENT_QUOTES, 'UTF-8'); }
    public function render() { include __DIR__ . '/../index.php'; }
}
(new FixtureArchive())->render();

# Tests

Running `haxe build.hxml` in the base directory compiles **Logipar** to its targets, and also creates test files here.

* `neko Test.n`  – runs the Neko test
* `python Test.py`  – runs the Python test
* `php php/index.php`  – runs the PHP test

These tests are defined in [/src/Test.hx](../src/Test.hx).


It's also a good idea to test the samples.

* `php php/sample.php`
* `python python/sample.py`
* `node js/sample.js`
* Open [js/index.html](js/index.html} in a web browser.

Presumably the package managed samples should be tested when deploying a new version to the individual package managers.
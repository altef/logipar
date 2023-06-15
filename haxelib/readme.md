# Haxelib packaging

Remember to update the version number and release notes.

To package for haxelib: `python package.py`

To test your package:

1. `haxelib install logipar.zip`
2. `haxelib set logipar` then enter whatever version you're testing
3. `haxe run.hxml`

To publish your package: `haxelib submit logipar.zip`
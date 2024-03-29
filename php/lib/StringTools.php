<?php
/**
 */

use \php\Boot;
use \php\_Boot\HxString;

/**
 * This class provides advanced methods on Strings. It is ideally used with
 * `using StringTools` and then acts as an [extension](https://haxe.org/manual/lf-static-extension.html)
 * to the `String` class.
 * If the first argument to any of the methods is null, the result is
 * unspecified.
 */
class StringTools {
	/**
	 * Tells if the character in the string `s` at position `pos` is a space.
	 * A character is considered to be a space character if its character code
	 * is 9,10,11,12,13 or 32.
	 * If `s` is the empty String `""`, or if pos is not a valid position within
	 * `s`, the result is false.
	 * 
	 * @param string $s
	 * @param int $pos
	 * 
	 * @return bool
	 */
	public static function isSpace ($s, $pos) {
		#C:\HaxeToolkit\haxe\std/php/_std/StringTools.hx:57: characters 3-29
		$c = HxString::charCodeAt($s, $pos);
		#C:\HaxeToolkit\haxe\std/php/_std/StringTools.hx:58: characters 10-40
		if (!(($c >= 9) && ($c <= 13))) {
			#C:\HaxeToolkit\haxe\std/php/_std/StringTools.hx:58: characters 33-40
			return $c === 32;
		} else {
			#C:\HaxeToolkit\haxe\std/php/_std/StringTools.hx:58: characters 10-40
			return true;
		}
	}
}

Boot::registerClass(StringTools::class, 'StringTools');

<?php
/**
 */

namespace haxe\ds;

use \php\Boot;
use \haxe\IMap;

/**
 * StringMap allows mapping of String keys to arbitrary values.
 * See `Map` for documentation details.
 * @see https://haxe.org/manual/std-Map.html
 */
class StringMap implements IMap {
	/**
	 * @var array
	 */
	public $data;

	/**
	 * Creates a new StringMap.
	 * 
	 * @return void
	 */
	public function __construct () {
		#C:\HaxeToolkit\haxe\std/php/_std/haxe/ds/StringMap.hx:35: characters 10-32
		$this1 = [];
		#C:\HaxeToolkit\haxe\std/php/_std/haxe/ds/StringMap.hx:35: characters 3-32
		$this->data = $this1;
	}

	/**
	 * See `Map.toString`
	 * 
	 * @return string
	 */
	public function toString () {
		#C:\HaxeToolkit\haxe\std/php/_std/haxe/ds/StringMap.hx:78: characters 15-32
		$this1 = [];
		#C:\HaxeToolkit\haxe\std/php/_std/haxe/ds/StringMap.hx:78: characters 3-33
		$parts = $this1;
		#C:\HaxeToolkit\haxe\std/php/_std/haxe/ds/StringMap.hx:79: lines 79-81
		$collection = $this->data;
		foreach ($collection as $key => $value) {
			#C:\HaxeToolkit\haxe\std/php/_std/haxe/ds/StringMap.hx:80: characters 4-60
			\array_push($parts, "" . ($key??'null') . " => " . \Std::string($value));
		}
		#C:\HaxeToolkit\haxe\std/php/_std/haxe/ds/StringMap.hx:83: characters 3-49
		return "{" . (\implode(", ", $parts)??'null') . "}";
	}

	public function __toString() {
		return $this->toString();
	}
}

Boot::registerClass(StringMap::class, 'haxe.ds.StringMap');

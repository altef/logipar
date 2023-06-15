<?php
/**
 */

namespace php\_Boot;

use \php\Boot;

/**
 * Anonymous objects implementation
 */
class HxAnon extends \StdClass {
	/**
	 * @return void
	 */
	public function __construct () {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:950: lines 950-960
		;
	}

	/**
	 * @param string $name
	 * @param array $args
	 * 
	 * @return mixed
	 */
	public function __call ($name, $args) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:958: characters 3-57
		return ($this->$name)(...$args);
	}

	/**
	 * @param string $name
	 * 
	 * @return mixed
	 */
	public function __get ($name) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:953: characters 3-14
		return null;
	}
}

Boot::registerClass(HxAnon::class, 'php._Boot.HxAnon');

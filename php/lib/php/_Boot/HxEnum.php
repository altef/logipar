<?php
/**
 */

namespace php\_Boot;

use \php\Boot;

/**
 * Base class for enum types
 */
class HxEnum {
	/**
	 * @var int
	 */
	public $index;
	/**
	 * @var array
	 */
	public $params;
	/**
	 * @var string
	 */
	public $tag;

	/**
	 * @param string $tag
	 * @param int $index
	 * @param array $arguments
	 * 
	 * @return void
	 */
	public function __construct ($tag, $index, $arguments = null) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:721: characters 3-17
		$this->tag = $tag;
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:722: characters 3-21
		$this->index = $index;
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:723: characters 12-63
		$tmp = null;
		if ($arguments === null) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:723: characters 33-50
			$this1 = [];
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:723: characters 12-63
			$tmp = $this1;
		} else {
			$tmp = $arguments;
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:723: characters 3-63
		$this->params = $tmp;
	}

	/**
	 * PHP magic method to get string representation of this `Class`
	 * 
	 * @return string
	 */
	public function __toString () {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:738: characters 3-30
		return Boot::stringify($this);
	}

	/**
	 * Get string representation of this `Class`
	 * 
	 * @return string
	 */
	public function toString () {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:730: characters 3-22
		return $this->__toString();
	}
}

Boot::registerClass(HxEnum::class, 'php._Boot.HxEnum');

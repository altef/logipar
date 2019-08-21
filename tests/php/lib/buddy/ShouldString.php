<?php
/**
 * Generated by Haxe 4.0.0-rc.2+77068e10c
 */

namespace buddy;

use \php\Boot;
use \php\_Boot\HxString;

class ShouldString extends Should {

	/**
	 * @param string $str
	 * 
	 * @return ShouldString
	 */
	static public function should ($str) {
		#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:317: characters 3-31
		return new ShouldString($str);
	}

	/**
	 * @param string $value
	 * @param bool $inverse
	 * 
	 * @return void
	 */
	public function __construct ($value, $inverse = false) {
		#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:322: characters 3-24
		if ($inverse === null) {
			$inverse = false;
		}
		parent::__construct($value, $inverse);
	}

	/**
	 * @param string $substring
	 * @param object $p
	 * 
	 * @return void
	 */
	public function contain ($substring, $p = null) {
		#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:332: lines 332-335
		if ($this->value === null) {
			$this->fail("Expected string to contain " . ($this->quote($substring)??'null') . " but string was null", "Expected string not to contain " . ($this->quote($substring)??'null') . " but string was null", $p);
			return;
		}
		#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:337: lines 337-340
		$this->test(HxString::indexOf($this->value, $substring) >= 0, $p, "Expected " . ($this->quote($this->value)??'null') . " to contain " . ($this->quote($substring)??'null'), "Expected " . ($this->quote($this->value)??'null') . " not to contain " . ($this->quote($substring)??'null'));
	}

	/**
	 * @param string $substring
	 * @param object $p
	 * 
	 * @return void
	 */
	public function endWith ($substring, $p = null) {
		#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:358: lines 358-361
		if ($this->value === null) {
			$this->fail("Expected string to end with " . ($this->quote($substring)??'null') . " but string was null", "Expected string not to end with " . ($this->quote($substring)??'null') . " but string was null", $p);
			return;
		}
		#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:363: lines 363-366
		$this->test(\StringTools::endsWith($this->value, $substring), $p, "Expected " . ($this->quote($this->value)??'null') . " to end with " . ($this->quote($substring)??'null'), "Expected " . ($this->quote($this->value)??'null') . " not to end with " . ($this->quote($substring)??'null'));
	}

	/**
	 * @return ShouldString
	 */
	public function get_not () {
		#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:326: characters 31-71
		return new ShouldString($this->value, !$this->inverse);
	}

	/**
	 * @param \EReg $regexp
	 * @param object $p
	 * 
	 * @return void
	 */
	public function match ($regexp, $p = null) {
		#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:371: lines 371-374
		if ($this->value === null) {
			$this->fail("Expected string to match regular expression but string was null", "Expected string not to match regular expression but string was null", $p);
			return;
		}
		#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:376: lines 376-379
		$this->test($regexp->match($this->value), $p, "Expected " . ($this->quote($this->value)??'null') . " to match regular expression", "Expected " . ($this->quote($this->value)??'null') . " not to match regular expression");
	}

	/**
	 * @param string $substring
	 * @param object $p
	 * 
	 * @return void
	 */
	public function startWith ($substring, $p = null) {
		#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:345: lines 345-348
		if ($this->value === null) {
			$this->fail("Expected string to start with " . ($this->quote($substring)??'null') . " but string was null", "Expected string not to start with " . ($this->quote($substring)??'null') . " but string was null", $p);
			return;
		}
		#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:350: lines 350-353
		$this->test(\StringTools::startsWith($this->value, $substring), $p, "Expected " . ($this->quote($this->value)??'null') . " to start with " . ($this->quote($substring)??'null'), "Expected " . ($this->quote($this->value)??'null') . " not to start with " . ($this->quote($substring)??'null'));
	}
}

Boot::registerClass(ShouldString::class, 'buddy.ShouldString');
Boot::registerGetters('buddy\\ShouldString', [
	'not' => true
]);

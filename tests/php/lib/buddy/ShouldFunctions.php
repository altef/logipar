<?php
/**
 * Generated by Haxe 4.0.0-rc.2+77068e10c
 */

namespace buddy;

use \php\Boot;
use \haxe\CallStack;
use \haxe\ds\List_hx;
use \php\_Boot\HxException;

class ShouldFunctions {
	/**
	 * @var bool
	 */
	public $inverse;
	/**
	 * @var \Closure
	 */
	public $value;

	/**
	 * @param \Closure $value
	 * 
	 * @return ShouldFunctions
	 */
	static public function should ($value) {
		#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:400: characters 3-36
		return new ShouldFunctions($value);
	}

	/**
	 * @param \Closure $value
	 * @param bool $inverse
	 * 
	 * @return void
	 */
	public function __construct ($value, $inverse = false) {
		#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:390: lines 390-393
		if ($inverse === null) {
			$inverse = false;
		}
		#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:391: characters 3-21
		$this->value = $value;
		#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:392: characters 3-25
		$this->inverse = $inverse;
	}

	/**
	 * Test for equality between two value types (bool, int, float and string), or identity for reference types
	 * 
	 * @param \Closure $expected
	 * @param object $p
	 * 
	 * @return void
	 */
	public function be ($expected, $p = null) {
		#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:496: lines 496-499
		$this->test(Boot::equal($this->value, $expected), $p, "Expected " . ($this->quote($expected)??'null') . ", was " . ($this->quote($this->value)??'null'), "Didn't expect " . ($this->quote($expected)??'null') . " but was equal to that");
	}

	/**
	 * @return ShouldFunctions
	 */
	public function get_not () {
		#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:396: characters 31-74
		return new ShouldFunctions($this->value, !$this->inverse);
	}

	/**
	 * @param mixed $v
	 * 
	 * @return string
	 */
	public function quote ($v) {
		#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:504: characters 3-39
		if (Boot::is($v, Boot::getClass('String'))) {
			#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:504: characters 26-39
			return "\"" . (\Std::string($v)??'null') . "\"";
		}
		#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:505: characters 3-58
		if (($v instanceof List_hx)) {
			#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:505: characters 24-58
			return \Std::string(\Lambda::array($v));
		}
		#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:506: characters 3-23
		return \Std::string($v);
	}

	/**
	 * @param bool $expr
	 * @param object $p
	 * @param string $error
	 * @param string $errorInverted
	 * 
	 * @return void
	 */
	public function test ($expr, $p, $error, $errorInverted) {
		#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:511: characters 3-46
		if (SuitesRunner::$currentTest === null) {
			#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:511: characters 41-46
			throw new HxException("SuitesRunner.currentTest was null");
		}
		#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:513: lines 513-516
		if (!$this->inverse) {
			#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:514: characters 42-73
			$tmp = SuitesRunner::posInfosToStack($p);
			#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:514: characters 4-74
			(SuitesRunner::$currentTest)($expr, $error, $tmp);
		} else {
			#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:516: characters 51-82
			$tmp1 = SuitesRunner::posInfosToStack($p);
			#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:516: characters 4-83
			(SuitesRunner::$currentTest)(!$expr, $errorInverted, $tmp1);
		}
	}

	/**
	 * Will call the specified method and test if it throws anything.
	 * 
	 * @param object $p
	 * 
	 * @return mixed
	 */
	public function throwAnything ($p = null) {
		#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:408: characters 3-22
		$caught = false;
		#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:409: characters 3-34
		$exception = null;
		#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:411: lines 411-412
		try {
			#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:411: characters 9-16
			($this->value)();
		} catch (\Throwable $__hx__caught_e) {
			CallStack::saveExceptionTrace($__hx__caught_e);
			$__hx__real_e = ($__hx__caught_e instanceof HxException ? $__hx__caught_e->e : $__hx__caught_e);
			$e = $__hx__real_e;
			#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:412: characters 25-38
			$exception = $e;
			#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:412: characters 40-53
			$caught = true;
		}
		#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:414: lines 414-417
		$this->test($caught, $p, "Expected " . ($this->quote($this->value)??'null') . " to throw anything, nothing was thrown", "Expected " . ($this->quote($this->value)??'null') . " not to throw anything, " . ($this->quote($exception)??'null') . " was thrown");
		#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:419: characters 3-19
		return $exception;
	}

	/**
	 * Will call the specified method and test if it throws a specific type.
	 * 
	 * @param Class $type
	 * @param object $p
	 * 
	 * @return mixed
	 */
	public function throwType ($type, $p = null) {
		#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:459: characters 3-34
		$exception = null;
		#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:461: lines 461-475
		try {
			#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:461: characters 9-16
			($this->value)();
		} catch (\Throwable $__hx__caught_e) {
			CallStack::saveExceptionTrace($__hx__caught_e);
			$__hx__real_e = ($__hx__caught_e instanceof HxException ? $__hx__caught_e->e : $__hx__caught_e);
			$e = $__hx__real_e;
			#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:463: characters 4-31
			$cause = null;
			#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:474: characters 32-33
			$exception = ($cause === null ? $e : $cause);
		}
		#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:477: characters 3-51
		$typeName = \Type::getClassName($type);
		#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:479: characters 3-94
		$exceptionName = ($exception === null ? null : \Type::getClassName(\Type::getClass($exception)));
		#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:480: characters 3-60
		if ($exceptionName === null) {
			#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:480: characters 30-60
			$exceptionName = "no exception";
		}
		#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:482: characters 3-42
		$isCaught = Boot::is($exception, $type);
		#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:483: lines 483-486
		$this->test($isCaught, $p, "Expected " . ($this->quote($this->value)??'null') . " to throw type " . ($typeName??'null') . ", " . ($exceptionName??'null') . " was thrown instead", "Expected " . ($this->quote($this->value)??'null') . " not to throw type " . ($typeName??'null'));
		#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:488: characters 3-19
		return $exception;
	}

	/**
	 * Will call the specified method and test if it throws a specific value.
	 * 
	 * @param mixed $v
	 * @param object $p
	 * 
	 * @return mixed
	 */
	public function throwValue ($v, $p = null) {
		#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:427: characters 3-34
		$exception = null;
		#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:429: lines 429-443
		try {
			#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:429: characters 9-16
			($this->value)();
		} catch (\Throwable $__hx__caught_e) {
			CallStack::saveExceptionTrace($__hx__caught_e);
			$__hx__real_e = ($__hx__caught_e instanceof HxException ? $__hx__caught_e->e : $__hx__caught_e);
			$e = $__hx__real_e;
			#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:431: characters 4-31
			$cause = null;
			#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:442: characters 32-33
			$exception = ($cause === null ? $e : $cause);
		}
		#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:445: characters 3-33
		$isCaught = Boot::equal($exception, $v);
		#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:446: lines 446-449
		$this->test($isCaught, $p, "Expected " . ($this->quote($this->value)??'null') . " to throw " . ($this->quote($v)??'null'), "Expected " . ($this->quote($this->value)??'null') . " not to throw " . ($this->quote($v)??'null'));
		#C:\HaxeToolkit\haxe\lib\buddy/2,10,2/buddy/Should.hx:451: characters 3-19
		return $exception;
	}
}

Boot::registerClass(ShouldFunctions::class, 'buddy.ShouldFunctions');
Boot::registerGetters('buddy\\ShouldFunctions', [
	'not' => true
]);
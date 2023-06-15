<?php
/**
 */

namespace haxe;

use \php\Boot;

/**
 * An exception containing arbitrary value.
 * This class is automatically used for throwing values, which don't extend `haxe.Exception`
 * or native exception type.
 * For example:
 * ```haxe
 * throw "Terrible error";
 * ```
 * will be compiled to
 * ```haxe
 * throw new ValueException("Terrible error");
 * ```
 */
class ValueException extends Exception {
	/**
	 * @var mixed
	 * Thrown value.
	 */
	public $value;

	/**
	 * @param mixed $value
	 * @param Exception $previous
	 * @param mixed $native
	 * 
	 * @return void
	 */
	public function __construct ($value, $previous = null, $native = null) {
		#C:\HaxeToolkit\haxe\std/haxe/ValueException.hx:24: characters 3-100
		parent::__construct(\Std::string($value), $previous, $native);
		#C:\HaxeToolkit\haxe\std/haxe/ValueException.hx:25: characters 3-21
		$this->value = $value;
	}
}

Boot::registerClass(ValueException::class, 'haxe.ValueException');

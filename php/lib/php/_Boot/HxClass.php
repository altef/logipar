<?php
/**
 */

namespace php\_Boot;

use \php\Boot;

/**
 * Class<T> implementation for Haxe->PHP internals.
 */
class HxClass {
	/**
	 * @var string
	 */
	public $phpClassName;

	/**
	 * @param string $phpClassName
	 * 
	 * @return void
	 */
	public function __construct ($phpClassName) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:667: characters 3-35
		$this->phpClassName = $phpClassName;
	}

	/**
	 * Magic method to call static methods of this class, when `HxClass` instance is in a `Dynamic` variable.
	 * 
	 * @param string $method
	 * @param array $args
	 * 
	 * @return mixed
	 */
	public function __call ($method, $args) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:675: characters 3-111
		$callback = ((($this->phpClassName === "String" ? HxString::class : $this->phpClassName))??'null') . "::" . ($method??'null');
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:676: characters 3-53
		return \call_user_func_array($callback, $args);
	}

	/**
	 * Magic method to get static vars of this class, when `HxClass` instance is in a `Dynamic` variable.
	 * 
	 * @param string $property
	 * 
	 * @return mixed
	 */
	public function __get ($property) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:684: lines 684-692
		if (\defined("" . ($this->phpClassName??'null') . "::" . ($property??'null'))) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:685: characters 4-54
			return \constant("" . ($this->phpClassName??'null') . "::" . ($property??'null'));
		} else if (Boot::hasGetter($this->phpClassName, $property)) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:687: characters 29-41
			$tmp = $this->phpClassName;
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:687: characters 4-59
			return $tmp::{"get_" . ($property??'null')}();
		} else if (\method_exists($this->phpClassName, $property)) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:689: characters 4-56
			return Boot::getStaticClosure($this->phpClassName, $property);
		} else {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:691: characters 33-45
			$tmp = $this->phpClassName;
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:691: characters 4-56
			return $tmp::${$property};
		}
	}

	/**
	 * Magic method to set static vars of this class, when `HxClass` instance is in a `Dynamic` variable.
	 * 
	 * @param string $property
	 * @param mixed $value
	 * 
	 * @return void
	 */
	public function __set ($property, $value) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:700: lines 700-704
		if (Boot::hasSetter($this->phpClassName, $property)) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:701: characters 22-34
			$tmp = $this->phpClassName;
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:701: characters 4-59
			$tmp::{"set_" . ($property??'null')}($value);
		} else {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:703: characters 26-38
			$tmp = $this->phpClassName;
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:703: characters 4-56
			$tmp::${$property} = $value;
		}
	}
}

Boot::registerClass(HxClass::class, 'php._Boot.HxClass');

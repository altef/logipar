<?php
/**
 */

namespace php;

use \php\_Boot\HxDynamicStr;
use \php\_Boot\HxAnon;
use \php\_Boot\HxClass;
use \haxe\Exception;
use \php\_Boot\HxString;
use \php\_Boot\HxClosure;
use \php\_Boot\HxEnum;

/**
 * Various Haxe->PHP compatibility utilities.
 * You should not use this class directly.
 */
class Boot {
	const PHP_PREFIX = "";

	/**
	 * @var array
	 * List of Haxe classes registered by their PHP class names
	 */
	static protected $aliases;
	/**
	 * @var array
	 * Cache of HxClass instances
	 */
	static protected $classes;
	/**
	 * @var array
	 * List of getters (for Reflect)
	 */
	static protected $getters;
	/**
	 * @var array
	 * Metadata storage
	 */
	static protected $meta;
	/**
	 * @var array
	 * List of setters (for Reflect)
	 */
	static protected $setters;
	/**
	 * @var array
	 * Cache for closures created of static methods
	 */
	static protected $staticClosures;

	/**
	 * Concat `left` and `right` if both are strings or string and null.
	 * Otherwise return sum of `left` and `right`.
	 * 
	 * @param mixed $left
	 * @param mixed $right
	 * 
	 * @return mixed
	 */
	public static function addOrConcat ($left, $right) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:452: lines 452-454
		if (\is_string($left) || \is_string($right)) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:453: characters 4-45
			return ($left??'null') . ($right??'null');
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:455: characters 3-33
		return ($left + $right);
	}

	/**
	 * Unsafe cast to HxClass
	 * 
	 * @param Class $cls
	 * 
	 * @return HxClass
	 */
	public static function castClass ($cls) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:301: characters 3-18
		return $cls;
	}

	/**
	 * Unsafe cast to HxClosure
	 * 
	 * @param mixed $value
	 * 
	 * @return HxClosure
	 */
	public static function castClosure ($value) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:294: characters 3-15
		return $value;
	}

	/**
	 * Unsafe cast to HxEnum
	 * 
	 * @param mixed $enm
	 * 
	 * @return HxEnum
	 */
	public static function castEnumValue ($enm) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:308: characters 3-18
		return $enm;
	}

	/**
	 * Creates Haxe-compatible closure.
	 * @param type `this` for instance methods; full php class name for static methods
	 * @param func Method name
	 * 
	 * @param mixed $target
	 * @param string $func
	 * 
	 * @return HxClosure
	 */
	public static function closure ($target, $func) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:634: characters 10-96
		if (\is_string($target)) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:634: characters 31-61
			return Boot::getStaticClosure($target, $func);
		} else {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:634: characters 64-96
			return Boot::getInstanceClosure($target, $func);
		}
	}

	/**
	 * Returns `Class<T>` for `HxClosure`
	 * 
	 * @return HxClass
	 */
	public static function closureHxClass () {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:315: characters 3-24
		return Boot::getClass(HxClosure::class);
	}

	/**
	 * Create Haxe-compatible anonymous structure of `data` associative array
	 * 
	 * @param array $data
	 * 
	 * @return mixed
	 */
	public static function createAnon ($data) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:563: characters 3-24
		$o = new HxAnon();
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:564: characters 3-86
		foreach ($data as $key => $value) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:564: characters 53-85
			$o->{$key} = $value;
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:565: characters 3-11
		return $o;
	}

	/**
	 * Helper method to avoid "Cannot use temporary expression in write context" error for expressions like this:
	 * ```haxe
	 * (new MyClass()).fieldName = 'value';
	 * ```
	 * 
	 * @param mixed $value
	 * 
	 * @return mixed
	 */
	public static function deref ($value) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:556: characters 3-15
		return $value;
	}

	/**
	 * @param float $value
	 * 
	 * @return float
	 */
	public static function divByZero ($value) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:654: characters 10-71
		if (Boot::equal($value, 0)) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:654: characters 23-32
			return \NAN;
		} else if ($value < 0) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:654: characters 48-58
			return -\INF;
		} else {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:654: characters 61-70
			return \INF;
		}
	}

	/**
	 * Get `field` of a dynamic `value` in a safe manner (avoid exceptions on trying to get a method)
	 * 
	 * @param mixed $value
	 * @param string $field
	 * 
	 * @return mixed
	 */
	public static function dynamicField ($value, $field) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:579: lines 579-581
		if (\method_exists($value, $field)) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:580: characters 11-32
			if (\is_string($value)) {
				return Boot::getStaticClosure($value, $field);
			} else {
				return Boot::getInstanceClosure($value, $field);
			}
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:582: lines 582-584
		if (\is_string($value)) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:583: characters 4-51
			$value = new HxDynamicStr($value);
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:585: characters 3-36
		return $value->{$field};
	}

	/**
	 * @param string $str
	 * 
	 * @return HxDynamicStr
	 */
	public static function dynamicString ($str) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:589: characters 3-47
		return new HxDynamicStr($str);
	}

	/**
	 * Make sure specified class is loaded
	 * 
	 * @param string $phpClassName
	 * 
	 * @return bool
	 */
	public static function ensureLoaded ($phpClassName) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:572: characters 10-84
		if (!\class_exists($phpClassName)) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:572: characters 47-84
			return \interface_exists($phpClassName);
		} else {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:572: characters 10-84
			return true;
		}
	}

	/**
	 * Check if specified values are equal
	 * 
	 * @param mixed $left
	 * @param mixed $right
	 * 
	 * @return bool
	 */
	public static function equal ($left, $right) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:438: lines 438-440
		if ((\is_int($left) || \is_float($left)) && (\is_int($right) || \is_float($right))) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:439: characters 4-36
			return ($left == $right);
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:441: lines 441-443
		if (($left instanceof HxClosure) && ($right instanceof HxClosure)) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:442: characters 4-43
			return $left->equals($right);
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:444: characters 3-41
		return ($left === $right);
	}

	/**
	 * Get Class<T> instance for PHP fully qualified class name (E.g. '\some\pack\MyClass')
	 * It's always the same instance for the same `phpClassName`
	 * 
	 * @param string $phpClassName
	 * 
	 * @return HxClass
	 */
	public static function getClass ($phpClassName) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:185: lines 185-187
		if (\mb_substr($phpClassName, 0, 1) === "\\") {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:186: characters 19-41
			$phpClassName = \mb_substr($phpClassName, 1, null);
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:188: lines 188-190
		if (!isset(Boot::$classes[$phpClassName])) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:189: characters 4-53
			$val = new HxClass($phpClassName);
			Boot::$classes[$phpClassName] = $val;
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:192: characters 3-31
		return Boot::$classes[$phpClassName];
	}

	/**
	 * Returns either Haxe class name for specified `phpClassName` or (if no such Haxe class registered) `phpClassName`.
	 * 
	 * @param string $phpClassName
	 * 
	 * @return string
	 */
	public static function getClassName ($phpClassName) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:220: characters 3-40
		$hxClass = Boot::getClass($phpClassName);
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:221: characters 3-35
		$name = Boot::getHaxeName($hxClass);
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:222: characters 10-54
		if ($name === null) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:222: characters 26-46
			return $hxClass->phpClassName;
		} else {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:222: characters 49-53
			return $name;
		}
	}

	/**
	 * Returns original Haxe fully qualified class name for this type (if exists)
	 * 
	 * @param HxClass $hxClass
	 * 
	 * @return string
	 */
	public static function getHaxeName ($hxClass) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:229: characters 11-31
		$__hx__switch = ($hxClass->phpClassName);
		if ($__hx__switch === "Bool") {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:235: characters 5-18
			return "Bool";
		} else if ($__hx__switch === "Class") {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:239: characters 5-19
			return "Class";
		} else if ($__hx__switch === "Dynamic") {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:243: characters 5-21
			return "Dynamic";
		} else if ($__hx__switch === "Enum") {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:241: characters 5-18
			return "Enum";
		} else if ($__hx__switch === "Float") {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:237: characters 5-19
			return "Float";
		} else if ($__hx__switch === "Int") {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:231: characters 5-17
			return "Int";
		} else if ($__hx__switch === "String") {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:233: characters 5-20
			return "String";
		} else {
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:250: lines 250-256
		if (isset(Boot::$aliases[$hxClass->phpClassName])) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:251: characters 4-40
			return Boot::$aliases[$hxClass->phpClassName];
		} else if (\class_exists($hxClass->phpClassName) && isset(Boot::$aliases[$hxClass->phpClassName])) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:253: characters 4-40
			return Boot::$aliases[$hxClass->phpClassName];
		} else if (\interface_exists($hxClass->phpClassName) && isset(Boot::$aliases[$hxClass->phpClassName])) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:255: characters 4-40
			return Boot::$aliases[$hxClass->phpClassName];
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:258: characters 3-14
		return null;
	}

	/**
	 * Returns Class<HxAnon>
	 * 
	 * @return HxClass
	 */
	public static function getHxAnon () {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:199: characters 3-21
		return Boot::getClass(HxAnon::class);
	}

	/**
	 * Returns Class<HxClass>
	 * 
	 * @return HxClass
	 */
	public static function getHxClass () {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:213: characters 3-22
		return Boot::getClass(HxClass::class);
	}

	/**
	 * Creates Haxe-compatible closure of an instance method.
	 * @param obj - any object
	 * 
	 * @param object $obj
	 * @param string $methodName
	 * 
	 * @return HxClosure
	 */
	public static function getInstanceClosure ($obj, $methodName) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:597: characters 3-73
		$result = ($obj->__hx_closureCache[$methodName] ?? null);
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:598: lines 598-600
		if ($result !== null) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:599: characters 4-17
			return $result;
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:601: lines 601-603
		if (!\method_exists($obj, $methodName) && !isset($obj->{$methodName})) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:602: characters 4-15
			return null;
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:604: characters 3-42
		$result = new HxClosure($obj, $methodName);
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:605: lines 605-607
		if (!\property_exists($obj, "__hx_closureCache")) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:606: characters 28-50
			$this1 = [];
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:606: characters 4-50
			$obj->__hx_closureCache = $this1;
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:608: characters 3-45
		$obj->__hx_closureCache[$methodName] = $result;
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:609: characters 3-16
		return $result;
	}

	/**
	 * Retrieve metadata for specified class
	 * 
	 * @param string $phpClassName
	 * 
	 * @return mixed
	 */
	public static function getMeta ($phpClassName) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:151: characters 3-46
		if (!(\class_exists($phpClassName) || \interface_exists($phpClassName))) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:151: characters 35-46
			return null;
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:152: characters 10-70
		if (isset(Boot::$meta[$phpClassName])) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:152: characters 45-63
			return Boot::$meta[$phpClassName];
		} else {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:152: characters 66-70
			return null;
		}
	}

	/**
	 * Find corresponding PHP class name.
	 * Returns `null` if specified class does not exist.
	 * 
	 * @param string $haxeName
	 * 
	 * @return string
	 */
	public static function getPhpName ($haxeName) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:266: characters 3-28
		$prefix = Boot::getPrefix();
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:267: characters 3-63
		$phpParts = (\strlen($prefix) === 0 ? new \Array_hx() : \Array_hx::wrap([$prefix]));
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:269: characters 3-39
		$haxeParts = HxString::split($haxeName, ".");
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:270: lines 270-275
		$_g = 0;
		while ($_g < $haxeParts->length) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:270: characters 8-12
			$part = ($haxeParts->arr[$_g] ?? null);
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:270: lines 270-275
			++$_g;
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:271: lines 271-273
			if (Boot::isPhpKeyword($part)) {
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:272: characters 5-18
				$part = ($part??'null') . "_hx";
			}
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:274: characters 4-23
			$phpParts->arr[$phpParts->length++] = $part;
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:277: characters 3-29
		return $phpParts->join("\\");
	}

	/**
	 * Returns root namespace based on a value of `-D php-prefix=value` compiler flag.
	 * Returns empty string if no `-D php-prefix=value` provided.
	 * 
	 * @return string
	 */
	public static function getPrefix () {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:89: characters 3-41
		return self::PHP_PREFIX;
	}

	/**
	 * Returns a list of phpName=>haxeName for currently loaded haxe-generated classes.
	 * 
	 * @return array
	 */
	public static function getRegisteredAliases () {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:177: characters 3-17
		return Boot::$aliases;
	}

	/**
	 * Returns a list of currently loaded haxe-generated classes.
	 * 
	 * @return Class[]|\Array_hx
	 */
	public static function getRegisteredClasses () {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:166: characters 3-19
		$result = new \Array_hx();
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:167: lines 167-169
		$collection = Boot::$aliases;
		foreach ($collection as $key => $value) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:168: characters 4-39
			$x = Boot::getClass($key);
			$result->arr[$result->length++] = $x;
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:170: characters 3-16
		return $result;
	}

	/**
	 * Creates Haxe-compatible closure of a static method.
	 * 
	 * @param string $phpClassName
	 * @param string $methodName
	 * 
	 * @return HxClosure
	 */
	public static function getStaticClosure ($phpClassName, $methodName) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:616: characters 3-80
		$result = (Boot::$staticClosures[$phpClassName][$methodName] ?? null);
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:617: lines 617-619
		if ($result !== null) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:618: characters 4-17
			return $result;
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:620: characters 3-51
		$result = new HxClosure($phpClassName, $methodName);
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:621: lines 621-623
		if (!\array_key_exists($phpClassName, Boot::$staticClosures)) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:622: characters 35-57
			$this1 = [];
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:622: characters 4-57
			Boot::$staticClosures[$phpClassName] = $this1;
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:624: characters 3-52
		Boot::$staticClosures[$phpClassName][$methodName] = $result;
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:625: characters 3-16
		return $result;
	}

	/**
	 * Check if specified property has getter
	 * 
	 * @param string $phpClassName
	 * @param string $property
	 * 
	 * @return bool
	 */
	public static function hasGetter ($phpClassName, $property) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:110: lines 110-111
		if (!(\class_exists($phpClassName) || \interface_exists($phpClassName))) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:111: characters 4-16
			return false;
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:113: characters 3-19
		$has = false;
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:114: characters 3-72
		$phpClassName1 = $phpClassName;
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:115: lines 115-118
		while (true) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:116: characters 4-55
			$has = isset(Boot::$getters[$phpClassName1][$property]);
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:117: characters 4-56
			$phpClassName1 = \get_parent_class($phpClassName1);
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:115: lines 115-118
			if (!(!$has && ($phpClassName1 !== false) && \class_exists($phpClassName1))) {
				break;
			}
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:120: characters 3-13
		return $has;
	}

	/**
	 * Check if specified property has setter
	 * 
	 * @param string $phpClassName
	 * @param string $property
	 * 
	 * @return bool
	 */
	public static function hasSetter ($phpClassName, $property) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:127: lines 127-128
		if (!(\class_exists($phpClassName) || \interface_exists($phpClassName))) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:128: characters 4-16
			return false;
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:130: characters 3-19
		$has = false;
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:131: characters 3-72
		$phpClassName1 = $phpClassName;
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:132: lines 132-135
		while (true) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:133: characters 4-55
			$has = isset(Boot::$setters[$phpClassName1][$property]);
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:134: characters 4-56
			$phpClassName1 = \get_parent_class($phpClassName1);
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:132: lines 132-135
			if (!(!$has && ($phpClassName1 !== false) && \class_exists($phpClassName1))) {
				break;
			}
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:137: characters 3-13
		return $has;
	}

	/**
	 * @param mixed $value
	 * @param HxClass $type
	 * 
	 * @return bool
	 */
	public static function is ($value, $type) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:460: characters 3-31
		return Boot::isOfType($value, $type);
	}

	/**
	 * Check if provided value is an anonymous object
	 * 
	 * @param mixed $v
	 * 
	 * @return bool
	 */
	public static function isAnon ($v) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:206: characters 3-33
		return ($v instanceof HxAnon);
	}

	/**
	 * Check if `value` is a `Class<T>`
	 * 
	 * @param mixed $value
	 * 
	 * @return bool
	 */
	public static function isClass ($value) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:512: characters 3-38
		return ($value instanceof HxClass);
	}

	/**
	 * Check if `value` is an enum constructor instance
	 * 
	 * @param mixed $value
	 * 
	 * @return bool
	 */
	public static function isEnumValue ($value) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:519: characters 3-37
		return ($value instanceof HxEnum);
	}

	/**
	 * Check if `value` is a function
	 * 
	 * @param mixed $value
	 * 
	 * @return bool
	 */
	public static function isFunction ($value) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:526: characters 10-72
		if (!($value instanceof \Closure)) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:526: characters 42-72
			return ($value instanceof HxClosure);
		} else {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:526: characters 10-72
			return true;
		}
	}

	/**
	 * Check if `value` is an instance of `HxClosure`
	 * 
	 * @param mixed $value
	 * 
	 * @return bool
	 */
	public static function isHxClosure ($value) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:533: characters 3-40
		return ($value instanceof HxClosure);
	}

	/**
	 * @param mixed $value
	 * 
	 * @return bool
	 */
	public static function isNumber ($value) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:431: characters 10-44
		if (!\is_int($value)) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:431: characters 28-44
			return \is_float($value);
		} else {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:431: characters 10-44
			return true;
		}
	}

	/**
	 * `Std.isOfType()` implementation
	 * 
	 * @param mixed $value
	 * @param HxClass $type
	 * 
	 * @return bool
	 */
	public static function isOfType ($value, $type) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:467: lines 467-468
		if ($type === null) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:468: characters 4-16
			return false;
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:470: characters 3-35
		$phpType = $type->phpClassName;
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:478: lines 478-504
		if ($phpType === "Bool") {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:487: characters 5-27
			return \is_bool($value);
		} else if ($phpType === "Dynamic") {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:480: characters 5-25
			return $value !== null;
		} else if ($phpType === "Class" || $phpType === "Enum") {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:493: lines 493-498
			if (($value instanceof HxClass)) {
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:494: characters 6-62
				$valuePhpClass = $value->phpClassName;
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:495: characters 6-62
				$enumPhpClass = Boot::getClass(HxEnum::class)->phpClassName;
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:496: characters 6-74
				$isEnumType = \is_subclass_of($valuePhpClass, $enumPhpClass);
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:497: characters 13-59
				if ($phpType === "Enum") {
					#C:\HaxeToolkit\haxe\std/php/Boot.hx:497: characters 34-44
					return $isEnumType;
				} else {
					#C:\HaxeToolkit\haxe\std/php/Boot.hx:497: characters 47-58
					return !$isEnumType;
				}
			}
		} else if ($phpType === "Float") {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:485: characters 12-46
			if (!\is_float($value)) {
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:485: characters 32-46
				return \is_int($value);
			} else {
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:485: characters 12-46
				return true;
			}
		} else if ($phpType === "Int") {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:482: lines 482-483
			if (\is_int($value) || (\is_float($value) && ((int)($value) == $value) && !\is_nan($value))) {
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:483: characters 9-40
				return \abs($value) <= 2147483648;
			} else {
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:482: lines 482-483
				return false;
			}
		} else if ($phpType === "String") {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:489: characters 5-29
			return \is_string($value);
		} else if ($phpType === "php\\NativeArray" || $phpType === "php\\_NativeArray\\NativeArray_Impl_") {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:491: characters 5-28
			return \is_array($value);
		} else {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:500: lines 500-503
			if (\is_object($value)) {
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:501: characters 6-42
				$type1 = $type;
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:502: characters 6-43
				return ($value instanceof $type1->phpClassName);
			}
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:505: characters 3-15
		return false;
	}

	/**
	 * Check if the value of `str` is a reserved keyword in PHP
	 * @see https://www.php.net/manual/en/reserved.keywords.php
	 * 
	 * @param string $str
	 * 
	 * @return bool
	 */
	public static function isPhpKeyword ($str) {
		switch(strtolower($str)) {
			case '__halt_compiler': case 'abstract': case 'and': case 'array': case 'as': case 'break': 
			case 'callable': case 'case': case 'catch': case 'class': case 'clone': case 'const': case 'continue': 
			case 'declare': case 'default': case 'die': case 'do': case 'echo': case 'else': case 'elseif': 
			case 'empty': case 'enddeclare': case 'endfor': case 'endforeach': case 'endif': case 'endswitch': case 'endwhile': 
			case 'eval': case 'exit': case 'extends': case 'final': case 'finally': case 'for': case 'foreach': 
			case 'function': case 'global': case 'goto': case 'if': case 'implements': case 'include': case 'include_once': 
			case 'instanceof': case 'insteadof': case 'interface': case 'isset': case 'list': case 'namespace': case 'new': 
			case 'or': case 'print': case 'private': case 'protected': case 'public': case 'require': case 'require_once': 
			case 'return': case 'static': case 'switch': case 'throw': case 'trait': case 'try': case 'unset': 
			case 'use': case 'var': case 'while': case 'xor': case 'yield': case '__class__': case '__dir__': 
			case '__file__': case '__function__': case '__line__': case '__method__': case '__trait__': case '__namespace__': case 'int': 
			case 'float': case 'bool': case 'string': case 'true': case 'false': case 'null': case 'parent': 
			case 'void': case 'iterable': case 'object': case 'fn': 
				return true;
			default:
				return false;
		}
	}

	/**
	 * Associate PHP class name with Haxe class name
	 * 
	 * @param string $phpClassName
	 * @param string $haxeClassName
	 * 
	 * @return void
	 */
	public static function registerClass ($phpClassName, $haxeClassName) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:159: characters 3-40
		Boot::$aliases[$phpClassName] = $haxeClassName;
	}

	/**
	 * Register list of getters to be able to call getters using reflection
	 * 
	 * @param string $phpClassName
	 * @param array $list
	 * 
	 * @return void
	 */
	public static function registerGetters ($phpClassName, $list) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:96: characters 3-31
		Boot::$getters[$phpClassName] = $list;
	}

	/**
	 * Save metadata for specified class
	 * 
	 * @param string $phpClassName
	 * @param mixed $data
	 * 
	 * @return void
	 */
	public static function registerMeta ($phpClassName, $data) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:144: characters 3-28
		Boot::$meta[$phpClassName] = $data;
	}

	/**
	 * Register list of setters to be able to call getters using reflection
	 * 
	 * @param string $phpClassName
	 * @param array $list
	 * 
	 * @return void
	 */
	public static function registerSetters ($phpClassName, $list) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:103: characters 3-31
		Boot::$setters[$phpClassName] = $list;
	}

	/**
	 * Performs `left >>> right` operation
	 * 
	 * @param int $left
	 * @param int $right
	 * 
	 * @return int
	 */
	public static function shiftRightUnsigned ($left, $right) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:540: lines 540-546
		if ($right === 0) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:541: characters 4-15
			return $left;
		} else if ($left >= 0) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:543: characters 4-82
			return ($left >> $right) & ~((1 << (8 * \PHP_INT_SIZE - 1)) >> ($right - 1));
		} else {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:545: characters 4-56
			return ($left >> $right) & (2147483647 >> ($right - 1));
		}
	}

	/**
	 * Returns string representation of `value`
	 * 
	 * @param mixed $value
	 * @param int $maxRecursion
	 * 
	 * @return string
	 */
	public static function stringify ($value, $maxRecursion = 10) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:357: lines 357-420
		if ($maxRecursion === null) {
			$maxRecursion = 10;
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:358: lines 358-360
		if ($maxRecursion <= 0) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:359: characters 4-18
			return "<...>";
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:361: lines 361-363
		if ($value === null) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:362: characters 4-17
			return "null";
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:364: lines 364-366
		if (\is_string($value)) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:365: characters 4-16
			return $value;
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:367: lines 367-369
		if (\is_int($value) || \is_float($value)) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:368: characters 4-31
			return (string)($value);
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:370: lines 370-372
		if (\is_bool($value)) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:371: characters 11-35
			if ($value) {
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:371: characters 20-24
				return "true";
			} else {
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:371: characters 29-34
				return "false";
			}
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:373: lines 373-379
		if (\is_array($value)) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:374: characters 4-37
			$strings = [];
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:375: lines 375-377
			$collection = $value;
			foreach ($collection as $key => $value1) {
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:376: characters 5-82
				$strings[] = (((string)($key)??'null') . " => " . (Boot::stringify($value1, $maxRecursion - 1)??'null'));
			}
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:378: characters 4-52
			return "[" . (\implode(", ", $strings)??'null') . "]";
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:380: lines 380-418
		if (\is_object($value)) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:381: lines 381-383
			if (($value instanceof \Array_hx)) {
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:382: characters 12-75
				$arr = Boot::dynamicField($value, 'arr');
				$maxRecursion1 = $maxRecursion - 1;
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:382: characters 58-74
				if ($maxRecursion1 === null) {
					$maxRecursion1 = 10;
				}
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:382: characters 12-75
				$strings = [];
				foreach ($arr as $key => $value1) {
					$strings[$key] = Boot::stringify($value1, $maxRecursion1 - 1);
				}
				return "[" . (\implode(",", $strings)??'null') . "]";
			}
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:384: lines 384-392
			if (($value instanceof HxEnum)) {
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:385: characters 5-26
				$e = $value;
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:386: characters 5-24
				$result = $e->tag;
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:387: lines 387-390
				if (\count($e->params) > 0) {
					#C:\HaxeToolkit\haxe\std/php/Boot.hx:388: characters 6-109
					$strings = \array_map(function ($item) use (&$maxRecursion) {
						#C:\HaxeToolkit\haxe\std/php/Boot.hx:388: characters 52-97
						return Boot::stringify($item, $maxRecursion - 1);
					}, $e->params);
					#C:\HaxeToolkit\haxe\std/php/Boot.hx:389: characters 6-56
					$result = ($result??'null') . "(" . (\implode(",", $strings)??'null') . ")";
				}
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:391: characters 5-18
				return $result;
			}
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:393: lines 393-395
			if (\method_exists($value, "toString")) {
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:394: characters 5-28
				return HxDynamicStr::wrap($value)->toString();
			}
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:396: lines 396-398
			if (\method_exists($value, "__toString")) {
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:397: characters 5-30
				return $value->__toString();
			}
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:399: lines 399-409
			if (($value instanceof \StdClass)) {
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:400: lines 400-402
				if (isset($value->toString) && \is_callable(Boot::dynamicField($value, 'toString'))) {
					#C:\HaxeToolkit\haxe\std/php/Boot.hx:401: characters 6-29
					return HxDynamicStr::wrap($value)->toString();
				}
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:403: characters 18-50
				$this1 = [];
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:403: characters 5-51
				$result = $this1;
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:404: characters 5-46
				$data = \get_object_vars($value);
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:405: characters 17-34
				$data1 = \array_keys($data);
				$_g_current = 0;
				$_g_length = \count($data1);
				$_g_data = $data1;
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:405: lines 405-407
				while ($_g_current < $_g_length) {
					$key = $_g_data[$_g_current++];
					#C:\HaxeToolkit\haxe\std/php/Boot.hx:406: characters 6-75
					\array_push($result, "" . ($key??'null') . " : " . (Boot::stringify($data[$key], $maxRecursion - 1)??'null'));
				}
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:408: characters 5-54
				return "{ " . (\implode(", ", $result)??'null') . " }";
			}
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:410: lines 410-412
			if (($value instanceof \Closure) || ($value instanceof HxClosure)) {
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:411: characters 5-24
				return "<function>";
			}
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:413: lines 413-417
			if (($value instanceof HxClass)) {
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:414: characters 5-74
				return "[class " . (Boot::getClassName($value->phpClassName)??'null') . "]";
			} else {
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:416: characters 5-68
				return "[object " . (Boot::getClassName(\get_class($value))??'null') . "]";
			}
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:419: characters 3-8
		throw Exception::thrown("Unable to stringify value");
	}

	/**
	 * @param mixed[] $arr
	 * @param int $maxRecursion
	 * 
	 * @return string
	 */
	public static function stringifyNativeIndexedArray ($arr, $maxRecursion = 10) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:422: lines 422-428
		if ($maxRecursion === null) {
			$maxRecursion = 10;
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:423: characters 3-36
		$strings = [];
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:424: lines 424-426
		foreach ($arr as $key => $value) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:425: characters 4-60
			$strings[$key] = Boot::stringify($value, $maxRecursion - 1);
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:427: characters 3-50
		return "[" . (\implode(",", $strings)??'null') . "]";
	}

	/**
	 * Implementation for `cast(value, Class<Dynamic>)`
	 * @throws haxe.ValueError if `value` cannot be casted to this type
	 * 
	 * @param HxClass $hxClass
	 * @param mixed $value
	 * 
	 * @return mixed
	 */
	public static function typedCast ($hxClass, $value) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:323: lines 323-324
		if ($value === null) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:324: characters 4-15
			return null;
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:325: characters 11-31
		$__hx__switch = ($hxClass->phpClassName);
		if ($__hx__switch === "Bool") {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:335: lines 335-337
			if (\is_bool($value)) {
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:336: characters 6-18
				return $value;
			}
		} else if ($__hx__switch === "Float") {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:331: lines 331-333
			if (\is_int($value) || \is_float($value)) {
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:332: characters 6-29
				return \floatval($value);
			}
		} else if ($__hx__switch === "Int") {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:327: lines 327-329
			if (\is_int($value) || \is_float($value)) {
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:328: characters 6-33
				return \intval($value);
			}
		} else if ($__hx__switch === "String") {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:339: lines 339-341
			if (\is_string($value)) {
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:340: characters 6-18
				return $value;
			}
		} else if ($__hx__switch === "php\\NativeArray") {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:343: lines 343-345
			if (\is_array($value)) {
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:344: characters 6-18
				return $value;
			}
		} else {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:347: lines 347-349
			if (\is_object($value) && Boot::isOfType($value, $hxClass)) {
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:348: characters 6-18
				return $value;
			}
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:351: characters 3-8
		throw Exception::thrown("Cannot cast " . \Std::string($value) . " to " . (Boot::getClassName($hxClass->phpClassName)??'null'));
	}

	/**
	 * Get UTF-8 code of the first character in `s` without any checks
	 * 
	 * @param mixed $s
	 * 
	 * @return int
	 */
	public static function unsafeOrd ($s) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:641: characters 3-31
		$code = \ord($s[0]);
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:642: lines 642-650
		if ($code < 192) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:643: characters 4-15
			return $code;
		} else if ($code < 224) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:645: characters 4-57
			return (($code - 192) << 6) + \ord($s[1]) - 128;
		} else if ($code < 240) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:647: characters 4-93
			return (($code - 224) << 12) + ((\ord($s[1]) - 128) << 6) + \ord($s[2]) - 128;
		} else {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:649: characters 4-129
			return (($code - 240) << 18) + ((\ord($s[1]) - 128) << 12) + ((\ord($s[2]) - 128) << 6) + \ord($s[3]) - 128;
		}
	}

	/**
	 * @internal
	 * @access private
	 */
	static public function __hx__init ()
	{
		static $called = false;
		if ($called) return;
		$called = true;

		#C:\HaxeToolkit\haxe\std/php/Boot.hx:60: characters 3-39
		\mb_internal_encoding("UTF-8");
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:61: lines 61-81
		if (!\defined("HAXE_CUSTOM_ERROR_HANDLER") || !\HAXE_CUSTOM_ERROR_HANDLER) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:62: characters 4-60
			$previousLevel = \error_reporting(\E_ALL);
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:63: lines 63-75
			$previousHandler = \set_error_handler(function ($errno, $errstr, $errfile, $errline) {
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:64: lines 64-66
				if ((\error_reporting() & $errno) === 0) {
					#C:\HaxeToolkit\haxe\std/php/Boot.hx:65: characters 6-18
					return false;
				}
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:71: lines 71-73
				if (($errno === \E_WARNING) && ($errstr === "Division by zero")) {
					#C:\HaxeToolkit\haxe\std/php/Boot.hx:72: characters 6-17
					return true;
				}
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:74: characters 5-10
				throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
			});
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:77: lines 77-80
			if ($previousHandler !== null) {
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:78: characters 5-42
				\error_reporting($previousLevel);
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:79: characters 5-46
				\set_error_handler($previousHandler);
			}
		}

		$this1 = [];
		self::$aliases = $this1;
		$this1 = [];
		self::$classes = $this1;
		$this1 = [];
		self::$getters = $this1;
		$this1 = [];
		self::$setters = $this1;
		$this1 = [];
		self::$meta = $this1;
		$this1 = [];
		self::$staticClosures = $this1;
	}
}

require_once __DIR__.'/_polyfills.php';
Boot::__hx__init();
Boot::registerClass(Boot::class, 'php.Boot');

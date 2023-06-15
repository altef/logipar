<?php
/**
 */

use \php\Boot;

/**
 * The Std class provides standard methods for manipulating basic types.
 */
class Std {
	/**
	 * Converts any value to a String.
	 * If `s` is of `String`, `Int`, `Float` or `Bool`, its value is returned.
	 * If `s` is an instance of a class and that class or one of its parent classes has
	 * a `toString` method, that method is called. If no such method is present, the result
	 * is unspecified.
	 * If `s` is an enum constructor without argument, the constructor's name is returned. If
	 * arguments exists, the constructor's name followed by the String representations of
	 * the arguments is returned.
	 * If `s` is a structure, the field names along with their values are returned. The field order
	 * and the operator separating field names and values are unspecified.
	 * If s is null, "null" is returned.
	 * 
	 * @param mixed $s
	 * 
	 * @return string
	 */
	public static function string ($s) {
		#C:\HaxeToolkit\haxe\std/php/_std/Std.hx:47: characters 3-27
		return Boot::stringify($s);
	}
}

Boot::registerClass(Std::class, 'Std');

<?php
/**
 */

namespace php\_Boot;

use \php\Boot;

/**
 * `String` implementation
 */
class HxString {
	/**
	 * @param string $str
	 * @param int $index
	 * 
	 * @return string
	 */
	public static function charAt ($str, $index) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:759: characters 10-58
		if ($index < 0) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:759: characters 22-24
			return "";
		} else {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:759: characters 27-58
			return \mb_substr($str, $index, 1);
		}
	}

	/**
	 * @param string $str
	 * @param int $index
	 * 
	 * @return int
	 */
	public static function charCodeAt ($str, $index) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:763: lines 763-765
		if (($index < 0) || ($str === "")) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:764: characters 4-15
			return null;
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:766: lines 766-768
		if ($index === 0) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:767: characters 11-30
			$code = \ord($str[0]);
			if ($code < 192) {
				return $code;
			} else if ($code < 224) {
				return (($code - 192) << 6) + \ord($str[1]) - 128;
			} else if ($code < 240) {
				return (($code - 224) << 12) + ((\ord($str[1]) - 128) << 6) + \ord($str[2]) - 128;
			} else {
				return (($code - 240) << 18) + ((\ord($str[1]) - 128) << 12) + ((\ord($str[2]) - 128) << 6) + \ord($str[3]) - 128;
			}
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:769: characters 3-46
		$char = \mb_substr($str, $index, 1);
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:770: characters 10-50
		if ($char === "") {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:770: characters 23-27
			return null;
		} else {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:770: characters 30-50
			$code = \ord($char[0]);
			if ($code < 192) {
				return $code;
			} else if ($code < 224) {
				return (($code - 192) << 6) + \ord($char[1]) - 128;
			} else if ($code < 240) {
				return (($code - 224) << 12) + ((\ord($char[1]) - 128) << 6) + \ord($char[2]) - 128;
			} else {
				return (($code - 240) << 18) + ((\ord($char[1]) - 128) << 12) + ((\ord($char[2]) - 128) << 6) + \ord($char[3]) - 128;
			}
		}
	}

	/**
	 * @param int $code
	 * 
	 * @return string
	 */
	public static function fromCharCode ($code) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:865: characters 3-29
		return \mb_chr($code);
	}

	/**
	 * @param string $str
	 * @param string $search
	 * @param int $startIndex
	 * 
	 * @return int
	 */
	public static function indexOf ($str, $search, $startIndex = null) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:774: lines 774-787
		if ($startIndex === null) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:775: characters 4-18
			$startIndex = 0;
		} else {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:777: characters 4-28
			$length = mb_strlen($str);
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:778: lines 778-783
			if ($startIndex < 0) {
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:779: characters 5-25
				$startIndex += $length;
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:780: lines 780-782
				if ($startIndex < 0) {
					#C:\HaxeToolkit\haxe\std/php/Boot.hx:781: characters 6-20
					$startIndex = 0;
				}
			}
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:784: lines 784-786
			if (($startIndex >= $length) && ($search !== "")) {
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:785: characters 5-14
				return -1;
			}
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:788: lines 788-793
		$index = null;
		if ($search === "") {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:789: characters 4-28
			$length = mb_strlen($str);
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:788: lines 788-793
			$index = ($startIndex > $length ? $length : $startIndex);
		} else {
			$index = \mb_strpos($str, $search, $startIndex);
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:794: characters 10-39
		if ($index === false) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:794: characters 28-30
			return -1;
		} else {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:794: characters 33-38
			return $index;
		}
	}

	/**
	 * @param string $str
	 * @param string $search
	 * @param int $startIndex
	 * 
	 * @return int
	 */
	public static function lastIndexOf ($str, $search, $startIndex = null) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:798: characters 3-26
		$start = $startIndex;
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:799: lines 799-811
		if ($start === null) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:800: characters 4-13
			$start = 0;
		} else {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:802: characters 4-28
			$length = mb_strlen($str);
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:803: lines 803-810
			if ($start >= 0) {
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:804: characters 5-27
				$start -= $length;
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:805: lines 805-807
				if ($start > 0) {
					#C:\HaxeToolkit\haxe\std/php/Boot.hx:806: characters 6-15
					$start = 0;
				}
			} else if ($start < -$length) {
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:809: characters 5-20
				$start = -$length;
			}
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:812: lines 812-817
		$index = null;
		if ($search === "") {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:813: characters 4-28
			$length = mb_strlen($str);
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:812: lines 812-817
			$index = (($startIndex === null) || ($startIndex > $length) ? $length : $startIndex);
		} else {
			$index = \mb_strrpos($str, $search, $start);
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:818: lines 818-822
		if ($index === false) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:819: characters 4-13
			return -1;
		} else {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:821: characters 4-16
			return $index;
		}
	}

	/**
	 * @param string $str
	 * @param string $delimiter
	 * 
	 * @return string[]|\Array_hx
	 */
	public static function split ($str, $delimiter) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:826: lines 826-831
		$arr = null;
		if ($delimiter === "") {
			$arr = \preg_split("//u", $str, -1, \PREG_SPLIT_NO_EMPTY);
		} else {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:829: characters 4-49
			$delimiter = \preg_quote($delimiter, "/");
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:826: lines 826-831
			$arr = \preg_split("/" . ($delimiter??'null') . "/", $str);
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:832: characters 3-41
		return \Array_hx::wrap($arr);
	}

	/**
	 * @param string $str
	 * @param int $pos
	 * @param int $len
	 * 
	 * @return string
	 */
	public static function substr ($str, $pos, $len = null) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:836: characters 3-41
		return \mb_substr($str, $pos, $len);
	}

	/**
	 * @param string $str
	 * @param int $startIndex
	 * @param int $endIndex
	 * 
	 * @return string
	 */
	public static function substring ($str, $startIndex, $endIndex = null) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:840: lines 840-845
		if ($endIndex === null) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:841: lines 841-843
			if ($startIndex < 0) {
				#C:\HaxeToolkit\haxe\std/php/Boot.hx:842: characters 5-19
				$startIndex = 0;
			}
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:844: characters 4-44
			return \mb_substr($str, $startIndex);
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:846: lines 846-848
		if ($endIndex < 0) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:847: characters 4-16
			$endIndex = 0;
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:849: lines 849-851
		if ($startIndex < 0) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:850: characters 4-18
			$startIndex = 0;
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:852: lines 852-856
		if ($startIndex > $endIndex) {
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:853: characters 4-23
			$tmp = $endIndex;
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:854: characters 4-25
			$endIndex = $startIndex;
			#C:\HaxeToolkit\haxe\std/php/Boot.hx:855: characters 4-20
			$startIndex = $tmp;
		}
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:857: characters 3-66
		return \mb_substr($str, $startIndex, $endIndex - $startIndex);
	}

	/**
	 * @param string $str
	 * 
	 * @return string
	 */
	public static function toLowerCase ($str) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:755: characters 3-35
		return \mb_strtolower($str);
	}

	/**
	 * @param string $str
	 * 
	 * @return string
	 */
	public static function toString ($str) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:861: characters 3-13
		return $str;
	}

	/**
	 * @param string $str
	 * 
	 * @return string
	 */
	public static function toUpperCase ($str) {
		#C:\HaxeToolkit\haxe\std/php/Boot.hx:751: characters 3-35
		return \mb_strtoupper($str);
	}
}

Boot::registerClass(HxString::class, 'php._Boot.HxString');

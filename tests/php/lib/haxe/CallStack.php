<?php
/**
 * Generated by Haxe 4.0.0-rc.2+77068e10c
 */

namespace haxe;

use \php\Boot;
use \php\_Boot\HxString;

class CallStack {
	/**
	 * @var mixed
	 */
	static public $lastExceptionTrace;
	/**
	 * @var \Closure
	 * If defined this function will be used to transform call stack entries.
	 * @param String - generated php file name.
	 * @param Int - Line number in generated file.
	 */
	static public $mapPosition;

	/**
	 * Return the exception stack : this is the stack elements between
	 * the place the last exception was thrown and the place it was
	 * caught, or an empty array if not available.
	 * 
	 * @return \Array_hx
	 */
	static public function exceptionStack () {
		#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:42: characters 20-94
		$tmp = null;
		if (CallStack::$lastExceptionTrace === null) {
			#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:42: characters 49-73
			$this1 = [];
			#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:42: characters 20-94
			$tmp = $this1;
		} else {
			$tmp = CallStack::$lastExceptionTrace;
		}
		#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:42: characters 3-95
		return CallStack::makeStack($tmp);
	}

	/**
	 * @param mixed $native
	 * 
	 * @return \Array_hx
	 */
	static public function makeStack ($native) {
		#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:118: characters 3-19
		$result = new \Array_hx();
		#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:119: characters 3-36
		$count = count($native);
		#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:121: lines 121-150
		$_g = 0;
		$_g1 = $count;
		while ($_g < $_g1) {
			$i = $_g++;
			#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:122: characters 4-26
			$entry = $native[$i];
			#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:123: characters 4-20
			$item = null;
			#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:125: lines 125-137
			if (($i + 1) < $count) {
				#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:126: characters 5-30
				$next = $native[$i + 1];
				#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:128: characters 5-62
				if (!isset($next["function"])) {
					#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:128: characters 41-62
					$next["function"] = "";
				}
				#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:129: characters 5-56
				if (!isset($next["class"])) {
					#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:129: characters 38-56
					$next["class"] = "";
				}
				#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:131: lines 131-136
				if (HxString::indexOf($next["function"], "{closure}") >= 0) {
					#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:132: characters 6-28
					$item = StackItem::LocalFunction();
				} else if ((strlen($next["class"]) > 0) && (strlen($next["function"]) > 0)) {
					#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:134: characters 6-49
					$cls = Boot::getClassName($next["class"]);
					#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:135: characters 6-42
					$item = StackItem::Method($cls, $next["function"]);
				}
			}
			#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:138: lines 138-149
			if (isset($entry["file"])) {
				#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:139: lines 139-145
				if (CallStack::$mapPosition !== null) {
					#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:140: characters 6-58
					$pos = (CallStack::$mapPosition)($entry["file"], $entry["line"]);
					#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:141: lines 141-144
					if (($pos !== null) && ($pos->source !== null) && ($pos->originalLine !== null)) {
						#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:142: characters 7-33
						$entry["file"] = $pos->source;
						#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:143: characters 7-39
						$entry["line"] = $pos->originalLine;
					}
				}
				#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:146: characters 5-61
				$result->arr[$result->length] = StackItem::FilePos($item, $entry["file"], $entry["line"]);
				++$result->length;

			} else if ($item !== null) {
				#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:148: characters 5-22
				$result->arr[$result->length] = $item;
				++$result->length;
			}
		}

		#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:152: characters 3-16
		return $result;
	}

	/**
	 * @param \Throwable $e
	 * 
	 * @return void
	 */
	static public function saveExceptionTrace ($e) {
		#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:84: characters 3-36
		CallStack::$lastExceptionTrace = $e->getTrace();
		#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:87: characters 3-80
		$currentTrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
		#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:88: characters 3-42
		$count = count($currentTrace);
		#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:90: lines 90-100
		$_g = -($count - 1);
		$_g1 = 1;
		while ($_g < $_g1) {
			$i = $_g++;
			#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:91: characters 4-82
			$exceptionEntry = end(CallStack::$lastExceptionTrace);
			#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:93: lines 93-99
			if (!isset($exceptionEntry["file"]) || !isset($currentTrace[-$i]["file"])) {
				#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:94: characters 5-41
				array_pop(CallStack::$lastExceptionTrace);
			} else if (Boot::equal($currentTrace[-$i]["file"], $exceptionEntry["file"]) && Boot::equal($currentTrace[-$i]["line"], $exceptionEntry["line"])) {
				#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:96: characters 5-41
				array_pop(CallStack::$lastExceptionTrace);
			} else {
				#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:98: characters 5-10
				break;
			}
		}

		#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:103: characters 3-48
		$count1 = count(CallStack::$lastExceptionTrace);
		#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:104: lines 104-106
		$_g2 = 0;
		$_g3 = $count1;
		while ($_g2 < $_g3) {
			$i1 = $_g2++;
			#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:105: characters 36-53
			$this1 = [];
			#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:105: characters 4-53
			CallStack::$lastExceptionTrace[$i1]["args"] = $this1;

		}

		#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:108: characters 18-49
		$this2 = [];
		#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:108: characters 3-50
		$thrownAt = $this2;
		#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:109: characters 3-28
		$thrownAt["function"] = "";
		#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:110: characters 3-33
		$thrownAt["line"] = $e->getLine();
		#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:111: characters 3-33
		$thrownAt["file"] = $e->getFile();
		#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:112: characters 3-25
		$thrownAt["class"] = "";
		#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:113: characters 22-39
		$this3 = [];
		#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:113: characters 3-39
		$thrownAt["args"] = $this3;

		#C:\HaxeToolkit\haxe\std/php/_std/haxe/CallStack.hx:114: characters 3-53
		array_unshift(CallStack::$lastExceptionTrace, $thrownAt);
	}
}

Boot::registerClass(CallStack::class, 'haxe.CallStack');

<?php
/**
 * Generated by Haxe 4.0.0-rc.2+77068e10c
 */

namespace logipar;

use \php\Boot;

class Token {
	/**
	 * @var string
	 */
	public $literal;
	/**
	 * @var Syntax
	 */
	public $type;

	/**
	 * Instantiate!
	 * 
	 * @param Syntax $type
	 * @param string $literal
	 * 
	 * @return void
	 */
	public function __construct ($type, $literal = null) {
		#src/logipar/Token.hx:17: characters 3-19
		$this->type = $type;
		#src/logipar/Token.hx:18: characters 3-25
		$this->literal = $literal;
	}

	/**
	 * What's the precedence of this token?  For, you know, order of operations and all that.
	 * 
	 * @return int
	 */
	public function precedence () {
		#src/logipar/Token.hx:26: characters 10-14
		$__hx__switch = ($this->type->index);
		if ($__hx__switch === 0 || $__hx__switch === 3) {
			#src/logipar/Token.hx:28: characters 5-13
			return 2;
		} else if ($__hx__switch === 1 || $__hx__switch === 2) {
			#src/logipar/Token.hx:30: characters 5-13
			return 1;
		} else {
			#src/logipar/Token.hx:32: characters 5-13
			return 0;
		}
	}

	/**
	 * In case we want to display tokens, this'll make that easier.
	 * 
	 * @return string
	 */
	public function toString () {
		#src/logipar/Token.hx:41: lines 41-42
		if ($this->type === Syntax::LITERAL()) {
			#src/logipar/Token.hx:42: characters 4-42
			return "LITERAL(" . ($this->literal??'null') . ")";
		}
		#src/logipar/Token.hx:43: characters 3-31
		return \Std::string($this->type);
	}

	public function __toString() {
		return $this->toString();
	}
}

Boot::registerClass(Token::class, 'logipar.Token');

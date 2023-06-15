<?php
/**
 */

namespace logipar;

use \php\Boot;

class Token {
	/**
	 * @var string
	 */
	const AND = "AND";
	/**
	 * @var string
	 */
	const CLOSE = "CLOSE";
	/**
	 * @var string
	 */
	const LITERAL = "LITERAL";
	/**
	 * @var string
	 */
	const NOT = "NOT";
	/**
	 * @var string
	 */
	const OPEN = "OPEN";
	/**
	 * @var string
	 */
	const OR = "OR";
	/**
	 * @var string
	 */
	const XOR = "XOR";

	/**
	 * @var string
	 */
	public $literal;
	/**
	 * @var string
	 */
	public $type;

	/**
	 * Instantiate!
	 * 
	 * @param string $type
	 * @param string $literal
	 * 
	 * @return void
	 */
	public function __construct ($type, $literal = null) {
		#src/logipar/Token.hx:28: characters 3-19
		$this->type = $type;
		#src/logipar/Token.hx:29: characters 3-25
		$this->literal = $literal;
	}

	/**
	 * Compare it to another token.
	 * 
	 * @param Token $b
	 * 
	 * @return bool
	 */
	public function equals ($b) {
		#src/logipar/Token.hx:56: characters 10-48
		if ($this->type === $b->type) {
			#src/logipar/Token.hx:56: characters 28-48
			return $this->literal === $b->literal;
		} else {
			#src/logipar/Token.hx:56: characters 10-48
			return false;
		}
	}

	/**
	 * What's the precedence of this token?  For, you know, order of operations and all that.
	 * 
	 * @return int
	 */
	public function precedence () {
		#src/logipar/Token.hx:37: characters 10-14
		$__hx__switch = ($this->type);
		if ($__hx__switch === "AND") {
			#src/logipar/Token.hx:43: characters 5-13
			return 2;
		} else if ($__hx__switch === "LITERAL") {
			#src/logipar/Token.hx:39: characters 5-13
			return 4;
		} else if ($__hx__switch === "NOT") {
			#src/logipar/Token.hx:41: characters 5-13
			return 3;
		} else if ($__hx__switch === "OR" || $__hx__switch === "XOR") {
			#src/logipar/Token.hx:45: characters 5-13
			return 1;
		} else {
			#src/logipar/Token.hx:47: characters 5-13
			return 0;
		}
	}

	/**
	 * In case we want to display tokens, this'll make that easier.
	 * 
	 * @return string
	 */
	public function toString () {
		#src/logipar/Token.hx:64: lines 64-65
		if ($this->type === "LITERAL") {
			#src/logipar/Token.hx:65: characters 4-42
			return "LITERAL(" . ($this->literal??'null') . ")";
		}
		#src/logipar/Token.hx:66: characters 3-31
		return \Std::string($this->type);
	}

	public function __toString() {
		return $this->toString();
	}
}

Boot::registerClass(Token::class, 'logipar.Token');

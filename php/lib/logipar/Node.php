<?php
/**
 */

namespace logipar;

use \php\Boot;
use \haxe\Exception;

class Node {
	/**
	 * @var string
	 */
	static public $MAXIMAL_BRACKETS = "MAXIMAL_BRACKETS";
	/**
	 * @var string
	 */
	static public $MINIMAL_BRACKETS = "MINIMAL_BRACKETS";

	/**
	 * @var string
	 */
	public $bracketing;
	/**
	 * @var \Closure
	 */
	public $f;
	/**
	 * @var Node
	 */
	public $left;
	/**
	 * @var Node
	 */
	public $parent;
	/**
	 * @var Node
	 */
	public $right;
	/**
	 * @var Token
	 */
	public $token;

	/**
	 * Construct a new node.  That is all.
	 * 
	 * @param Token $token
	 * 
	 * @return void
	 */
	public function __construct ($token) {
		#src/logipar/Node.hx:18: characters 33-49
		$this->bracketing = Node::$MINIMAL_BRACKETS;
		#src/logipar/Node.hx:42: characters 3-21
		$this->token = $token;
	}

	/**
	 * @param Node $n
	 * @param \Closure $f
	 * 
	 * @return string
	 */
	public function _fancyString ($n, $f = null) {
		#src/logipar/Node.hx:97: characters 3-16
		$s = null;
		#src/logipar/Node.hx:98: lines 98-102
		if ($f !== null) {
			#src/logipar/Node.hx:99: characters 10-22
			$_g = Boot::getInstanceClosure($this, '_fancyString');
			#src/logipar/Node.hx:99: characters 31-32
			$f1 = $f;
			#src/logipar/Node.hx:99: characters 4-33
			$n->f = function ($n) use (&$f1, &$_g) {
				#src/logipar/Node.hx:99: characters 10-27
				return $_g($n, $f1);
			};
			#src/logipar/Node.hx:100: characters 4-12
			$s = $f($n);
			#src/logipar/Node.hx:101: characters 4-14
			$n->f = null;
		}
		#src/logipar/Node.hx:103: lines 103-104
		if ($s !== null) {
			#src/logipar/Node.hx:104: characters 4-12
			return $s;
		}
		#src/logipar/Node.hx:105: characters 10-22
		$__hx__switch = ($n->token->type);
		if ($__hx__switch === "LITERAL") {
			#src/logipar/Node.hx:107: characters 5-39
			return "{" . ($n->token->literal??'null') . "}";
		} else if ($__hx__switch === "NOT") {
			#src/logipar/Node.hx:109: characters 5-52
			return $this->bracket("NOT " . ($n->right->fancyString($f)??'null'));
		} else {
			#src/logipar/Node.hx:111: characters 5-106
			return $this->bracket(($n->left->fancyString($f)??'null') . " " . \Std::string($n->token->type) . " " . ($n->right->fancyString($f)??'null'));
		}
	}

	/**
	 * Minimally bracket things, when flattening to a string.
	 * 
	 * @param string $str
	 * 
	 * @return string
	 */
	public function bracket ($str) {
		#src/logipar/Node.hx:90: lines 90-91
		if (($this->bracketing === "MAXIMAL_BRACKETS") || (($this->parent !== null) && ($this->parent->token->precedence() > $this->token->precedence()))) {
			#src/logipar/Node.hx:91: characters 4-26
			return "(" . ($str??'null') . ")";
		}
		#src/logipar/Node.hx:92: characters 3-13
		return $str;
	}

	/**
	 * This function lets us execute the tree.  You know, to actually check against data.
	 * You shouldn't have to call it directly, but if you want to, look at
	 * Logipar.filterFunction() examples in the repo's readme.
	 * 
	 * @param mixed $a
	 * @param \Closure $f
	 * 
	 * @return bool
	 */
	public function check ($a, $f) {
		#src/logipar/Node.hx:122: characters 10-20
		$__hx__switch = ($this->token->type);
		if ($__hx__switch === "AND") {
			#src/logipar/Node.hx:124: characters 12-47
			if ($this->left->check($a, $f)) {
				#src/logipar/Node.hx:124: characters 31-47
				return $this->right->check($a, $f);
			} else {
				#src/logipar/Node.hx:124: characters 12-47
				return false;
			}
		} else if ($__hx__switch === "LITERAL") {
			#src/logipar/Node.hx:134: characters 5-31
			return $f($a, $this->token->literal);
		} else if ($__hx__switch === "NOT") {
			#src/logipar/Node.hx:132: characters 5-29
			return !$this->right->check($a, $f);
		} else if ($__hx__switch === "OR") {
			#src/logipar/Node.hx:126: characters 12-47
			if (!$this->left->check($a, $f)) {
				#src/logipar/Node.hx:126: characters 31-47
				return $this->right->check($a, $f);
			} else {
				#src/logipar/Node.hx:126: characters 12-47
				return true;
			}
		} else if ($__hx__switch === "XOR") {
			#src/logipar/Node.hx:128: characters 5-29
			$l = $this->left->check($a, $f);
			#src/logipar/Node.hx:129: characters 5-30
			$r = $this->right->check($a, $f);
			#src/logipar/Node.hx:130: characters 12-34
			if (!(!$l && $r)) {
				#src/logipar/Node.hx:130: characters 25-34
				if ($l) {
					#src/logipar/Node.hx:130: characters 31-33
					return !$r;
				} else {
					#src/logipar/Node.hx:130: characters 25-34
					return false;
				}
			} else {
				#src/logipar/Node.hx:130: characters 12-34
				return true;
			}
		} else {
			#src/logipar/Node.hx:136: characters 5-10
			throw Exception::thrown("Unexpected token encountered.");
		}
	}

	/**
	 * Compare two nodes, recursively.
	 * 
	 * @param Node $b
	 * 
	 * @return bool
	 */
	public function equals ($b) {
		#src/logipar/Node.hx:66: lines 66-71
		if ($this->token->equals($b->token)) {
			#src/logipar/Node.hx:67: characters 4-32
			if ($b === null) {
				#src/logipar/Node.hx:67: characters 20-32
				return false;
			}
			#src/logipar/Node.hx:69: lines 69-70
			if ((($this->left === null) && ($b->left === null)) || (($this->left !== null) && $this->left->equals($b->left))) {
				#src/logipar/Node.hx:70: characters 8-88
				if (!(($this->right === null) && ($b->right === null))) {
					#src/logipar/Node.hx:70: characters 47-87
					if ($this->right !== null) {
						#src/logipar/Node.hx:70: characters 65-86
						return $this->right->equals($b->right);
					} else {
						#src/logipar/Node.hx:70: characters 47-87
						return false;
					}
				} else {
					#src/logipar/Node.hx:70: characters 8-88
					return true;
				}
			} else {
				#src/logipar/Node.hx:69: lines 69-70
				return false;
			}
		}
		#src/logipar/Node.hx:72: characters 3-15
		return false;
	}

	/**
	 * Maybe you want to display a node in a certain way.  This function allows for that.
	 * You shouldn't have to call it directly, but if you want to, look at
	 * Logipar.stringify() examples in the repo's readme.
	 * 
	 * @param \Closure $f
	 * 
	 * @return string
	 */
	public function fancyString ($f = null) {
		#src/logipar/Node.hx:58: characters 3-31
		return $this->_fancyString($this, $f);
	}

	/**
	 * @param Node $n
	 * 
	 * @return Node
	 */
	public function set_left ($n) {
		#src/logipar/Node.hx:26: characters 3-18
		$n->parent = $this;
		#src/logipar/Node.hx:27: characters 3-18
		return $this->left = $n;
	}

	/**
	 * @param Node $n
	 * 
	 * @return Node
	 */
	public function set_right ($n) {
		#src/logipar/Node.hx:33: characters 3-18
		$n->parent = $this;
		#src/logipar/Node.hx:34: characters 3-19
		return $this->right = $n;
	}

	/**
	 * The toString() function is just an un-fancy version of fancyString().
	 * 
	 * @return string
	 */
	public function toString () {
		#src/logipar/Node.hx:49: characters 38-58
		return $this->fancyString();
	}

	/**
	 * @param \Closure $f
	 * 
	 * @return void
	 */
	public function walk ($f) {
		#src/logipar/Node.hx:77: characters 3-10
		$f($this);
		#src/logipar/Node.hx:78: lines 78-79
		if ($this->left !== null) {
			#src/logipar/Node.hx:79: characters 4-16
			$this->left->walk($f);
		}
		#src/logipar/Node.hx:80: lines 80-81
		if ($this->right !== null) {
			#src/logipar/Node.hx:81: characters 4-17
			$this->right->walk($f);
		}
	}

	public function __toString() {
		return $this->toString();
	}
}

Boot::registerClass(Node::class, 'logipar.Node');
Boot::registerSetters('logipar\\Node', [
	'right' => true,
	'left' => true
]);

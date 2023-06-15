<?php
/**
 */

namespace logipar;

use \php\Boot;
use \haxe\Exception;
use \haxe\ds\GenericCell;
use \haxe\ds\StringMap;
use \haxe\ds\GenericStack;

class Logipar {
	/**
	 * @var bool
	 */
	public $caseSensitive;
	/**
	 * @var bool
	 */
	public $mergeAdjacentLiterals;
	/**
	 * @var string[]|\Array_hx
	 */
	public $quotations;
	/**
	 * @var StringMap
	 */
	public $syntax;
	/**
	 * @var Node
	 */
	public $tree;

	/**
	 * Empty constructor.
	 * 
	 * @return void
	 */
	public function __construct () {
		#src/logipar/Logipar.hx:16: lines 16-23
		$_g = new StringMap();
		$_g->data["AND"] = "AND";
		$_g->data["OR"] = "OR";
		$_g->data["XOR"] = "XOR";
		$_g->data["NOT"] = "NOT";
		$_g->data["OPEN"] = "(";
		$_g->data["CLOSE"] = ")";
		$this->syntax = $_g;
		#src/logipar/Logipar.hx:15: characters 42-46
		$this->mergeAdjacentLiterals = true;
		#src/logipar/Logipar.hx:14: characters 34-38
		$this->caseSensitive = true;
		#src/logipar/Logipar.hx:13: characters 26-36
		$this->quotations = \Array_hx::wrap([
			"\"",
			"'",
		]);
	}

	/**
	 * Are two trees equal?
	 * 
	 * @param Logipar $b
	 * 
	 * @return bool
	 */
	public function equals ($b) {
		#src/logipar/Logipar.hx:106: characters 3-29
		return $this->tree->equals($b->tree);
	}

	/**
	 * Sometimes you just want to filter a list of rows, right?
	 * This function creates a filter function for you, based on your needs.
	 * It takes a function as its param, which in turn takes a row (an object of your data), and a value.
	 * The value is a particular literal on the tree - so you can deal with them however you want.
	 * I dunno, it's kind of hard to explain.. Check out the repo's readme for an example, hopefully that will help!
	 * 
	 * @param \Closure $f
	 * 
	 * @return \Closure
	 */
	public function filterFunction ($f) {
		#src/logipar/Logipar.hx:85: characters 3-23
		$enclosed = $this->tree;
		#src/logipar/Logipar.hx:86: lines 86-90
		return function ($a) use (&$f, &$enclosed) {
			#src/logipar/Logipar.hx:87: lines 87-88
			if ($enclosed === null) {
				#src/logipar/Logipar.hx:88: characters 5-16
				return true;
			}
			#src/logipar/Logipar.hx:89: characters 4-31
			return $enclosed->check($a, $f);
		};
	}

	/**
	 * @param Token[]|\Array_hx $tokens
	 * 
	 * @return Token[]|\Array_hx
	 */
	public function mergeLiterals ($tokens) {
		#src/logipar/Logipar.hx:112: characters 3-32
		$merged = new \Array_hx();
		#src/logipar/Logipar.hx:113: characters 12-16
		$_g = 0;
		#src/logipar/Logipar.hx:113: characters 16-29
		$_g1 = $tokens->length;
		#src/logipar/Logipar.hx:113: lines 113-121
		while ($_g < $_g1) {
			#src/logipar/Logipar.hx:113: characters 12-29
			$i = $_g++;
			#src/logipar/Logipar.hx:114: lines 114-119
			if (($tokens->arr[$i] ?? null)->type === "LITERAL") {
				#src/logipar/Logipar.hx:115: lines 115-118
				if (($i > 0) && (($merged->arr[$merged->length - 1] ?? null)->type === "LITERAL")) {
					#src/logipar/Logipar.hx:116: characters 6-64
					$merged[$merged->length - 1]->literal = ($merged[$merged->length - 1]->literal??'null') . " " . (($tokens->arr[$i] ?? null)->literal??'null');
					#src/logipar/Logipar.hx:117: characters 6-14
					continue;
				}
			}
			#src/logipar/Logipar.hx:120: characters 16-25
			$tokens1 = ($tokens->arr[$i] ?? null);
			#src/logipar/Logipar.hx:120: characters 4-26
			$merged->arr[$merged->length++] = $tokens1;
		}
		#src/logipar/Logipar.hx:122: characters 3-16
		return $merged;
	}

	/**
	 * Overwrite a particular operator with your own.
	 * 
	 * @param string $op
	 * @param string $value
	 * 
	 * @return void
	 */
	public function overwrite ($op, $value) {
		#src/logipar/Logipar.hx:38: lines 38-39
		if (\array_key_exists($op, $this->syntax->data)) {
			#src/logipar/Logipar.hx:39: characters 4-25
			$this->syntax->data[$op] = $value;
		}
	}

	/**
	 * Parse the logic string!  It returns a logipar.Node (the root of the tree), but you can pretty much ignore this.
	 * The tree is stored in the instance anyway.
	 * 
	 * @param string $logic_string
	 * 
	 * @return Node
	 */
	public function parse ($logic_string) {
		#src/logipar/Logipar.hx:48: characters 3-39
		$tokens = $this->tokenize($logic_string);
		#src/logipar/Logipar.hx:49: characters 3-31
		$types = $this->typeize($tokens);
		#src/logipar/Logipar.hx:50: lines 50-51
		if ($this->mergeAdjacentLiterals) {
			#src/logipar/Logipar.hx:51: characters 4-32
			$types = $this->mergeLiterals($types);
		}
		#src/logipar/Logipar.hx:52: characters 3-36
		$reversepolish = $this->shunt($types);
		#src/logipar/Logipar.hx:53: characters 3-32
		$this->tree = $this->treeify($reversepolish);
		#src/logipar/Logipar.hx:54: characters 3-14
		return $this->tree;
	}

	/**
	 * @param Token[]|\Array_hx $tokens
	 * 
	 * @return Token[]|\Array_hx
	 */
	public function shunt ($tokens) {
		#src/logipar/Logipar.hx:164: characters 3-48
		$output = new \Array_hx();
		#src/logipar/Logipar.hx:165: characters 3-65
		$operators = new GenericStack();
		#src/logipar/Logipar.hx:166: characters 12-16
		$_g = 0;
		#src/logipar/Logipar.hx:166: characters 16-29
		$_g1 = $tokens->length;
		#src/logipar/Logipar.hx:166: lines 166-200
		while ($_g < $_g1) {
			#src/logipar/Logipar.hx:166: characters 12-29
			$i = $_g++;
			#src/logipar/Logipar.hx:167: characters 4-26
			$token = ($tokens->arr[$i] ?? null);
			#src/logipar/Logipar.hx:168: characters 11-21
			$__hx__switch = ($token->type);
			if ($__hx__switch === "CLOSE") {
				#src/logipar/Logipar.hx:174: lines 174-181
				while (true) {
					#src/logipar/Logipar.hx:175: characters 16-31
					$k = $operators->head;
					#src/logipar/Logipar.hx:175: characters 7-32
					$op = null;
					#src/logipar/Logipar.hx:175: characters 16-31
					if ($k === null) {
						#src/logipar/Logipar.hx:175: characters 7-32
						$op = null;
					} else {
						#src/logipar/Logipar.hx:175: characters 16-31
						$operators->head = $k->next;
						#src/logipar/Logipar.hx:175: characters 7-32
						$op = $k->elt;
					}
					#src/logipar/Logipar.hx:176: lines 176-177
					if ($op->type === "OPEN") {
						#src/logipar/Logipar.hx:177: characters 8-13
						break;
					}
					#src/logipar/Logipar.hx:178: lines 178-179
					if ($operators->head === null) {
						#src/logipar/Logipar.hx:179: characters 8-13
						throw Exception::thrown("Mismatched parentheses.");
					}
					#src/logipar/Logipar.hx:180: characters 7-22
					$output->arr[$output->length++] = $op;
				}
			} else if ($__hx__switch === "LITERAL") {
				#src/logipar/Logipar.hx:170: characters 6-24
				$output->arr[$output->length++] = $token;
			} else if ($__hx__switch === "OPEN") {
				#src/logipar/Logipar.hx:172: characters 6-26
				$operators->head = new GenericCell($token, $operators->head);
			} else {
				#src/logipar/Logipar.hx:183: lines 183-196
				while ($operators->head !== null) {
					#src/logipar/Logipar.hx:187: characters 7-36
					$prev = ($operators->head === null ? null : $operators->head->elt);
					#src/logipar/Logipar.hx:189: lines 189-190
					if ($prev->type === "OPEN") {
						#src/logipar/Logipar.hx:190: characters 8-13
						break;
					}
					#src/logipar/Logipar.hx:192: lines 192-193
					if ($prev->precedence() <= $token->precedence()) {
						#src/logipar/Logipar.hx:193: characters 8-13
						break;
					}
					#src/logipar/Logipar.hx:195: characters 19-34
					$k1 = $operators->head;
					#src/logipar/Logipar.hx:195: characters 7-35
					$x = null;
					#src/logipar/Logipar.hx:195: characters 19-34
					if ($k1 === null) {
						#src/logipar/Logipar.hx:195: characters 7-35
						$x = null;
					} else {
						#src/logipar/Logipar.hx:195: characters 19-34
						$operators->head = $k1->next;
						#src/logipar/Logipar.hx:195: characters 7-35
						$x = $k1->elt;
					}
					$output->arr[$output->length++] = $x;
				}
				#src/logipar/Logipar.hx:197: characters 6-26
				$operators->head = new GenericCell($token, $operators->head);
			}
		}
		#src/logipar/Logipar.hx:203: lines 203-208
		while ($operators->head !== null) {
			#src/logipar/Logipar.hx:204: characters 12-27
			$k = $operators->head;
			#src/logipar/Logipar.hx:204: characters 4-28
			$o = null;
			#src/logipar/Logipar.hx:204: characters 12-27
			if ($k === null) {
				#src/logipar/Logipar.hx:204: characters 4-28
				$o = null;
			} else {
				#src/logipar/Logipar.hx:204: characters 12-27
				$operators->head = $k->next;
				#src/logipar/Logipar.hx:204: characters 4-28
				$o = $k->elt;
			}
			#src/logipar/Logipar.hx:205: lines 205-206
			if ($o->type === "OPEN") {
				#src/logipar/Logipar.hx:206: characters 5-10
				throw Exception::thrown("Mismatched parentheses.");
			}
			#src/logipar/Logipar.hx:207: characters 4-18
			$output->arr[$output->length++] = $o;
		}
		#src/logipar/Logipar.hx:209: characters 3-16
		return $output;
	}

	/**
	 * Sometimes you want your logic tree represented as a string.  Either for display,
	 * or maybe to use with SQL.  Hey, I'm not judging - this function should provide for
	 * all your stringifying needs.
	 * It takes a function as its param, which in turn takes a Node and provides a String.
	 * In this way you can have it display in pretty much any way you want.
	 * Anything you don't account for will use the default toString() function.
	 * Confused?  Don't worry, there should be an example on the repo's readme.
	 * 
	 * @param \Closure $f
	 * 
	 * @return string
	 */
	public function stringify ($f = null) {
		#src/logipar/Logipar.hx:68: characters 10-51
		if ($this->tree === null) {
			#src/logipar/Logipar.hx:68: characters 25-29
			return null;
		} else {
			#src/logipar/Logipar.hx:68: characters 32-51
			return $this->tree->fancyString($f);
		}
	}

	/**
	 * @param string $s
	 * 
	 * @return string
	 */
	public function tentativelyLower ($s) {
		#src/logipar/Logipar.hx:215: characters 10-57
		if ($this->caseSensitive) {
			#src/logipar/Logipar.hx:215: characters 26-27
			return $s;
		} else {
			#src/logipar/Logipar.hx:215: characters 30-57
			return \mb_strtolower(\Std::string($s));
		}
	}

	/**
	 * Returns a string representation of the tree.
	 * 
	 * @return string
	 */
	public function toString () {
		#src/logipar/Logipar.hx:98: characters 3-21
		return $this->stringify();
	}

	/**
	 * @param string $token
	 * 
	 * @return Token
	 */
	public function tokenType ($token) {
		#src/logipar/Logipar.hx:263: characters 14-27
		$data = \array_values(\array_map("strval", \array_keys($this->syntax->data)));
		$key_current = 0;
		$key_length = \count($data);
		$key_data = $data;
		while ($key_current < $key_length) {
			#src/logipar/Logipar.hx:263: lines 263-266
			$key = $key_data[$key_current++];
			#src/logipar/Logipar.hx:264: lines 264-265
			if ($this->tentativelyLower($token) === $this->tentativelyLower(($this->syntax->data[$key] ?? null))) {
				#src/logipar/Logipar.hx:265: characters 5-26
				return new Token($key);
			}
		}
		#src/logipar/Logipar.hx:268: characters 3-41
		return new Token("LITERAL", $token);
	}

	/**
	 * @param string $str
	 * 
	 * @return string[]|\Array_hx
	 */
	public function tokenize ($str) {
		#src/logipar/Logipar.hx:223: characters 3-33
		$tokens = new \Array_hx();
		#src/logipar/Logipar.hx:224: characters 28-66
		$_g = new \Array_hx();
		#src/logipar/Logipar.hx:224: characters 38-44
		$data = \array_values($this->syntax->data);
		$x_current = 0;
		$x_length = \count($data);
		$x_data = $data;
		while ($x_current < $x_length) {
			#src/logipar/Logipar.hx:224: characters 29-65
			$x = $x_data[$x_current++];
			#src/logipar/Logipar.hx:224: characters 46-65
			$x1 = $this->tentativelyLower($x);
			$_g->arr[$_g->length++] = $x1;
		}
		#src/logipar/Logipar.hx:224: characters 3-67
		$keys = $_g;
		#src/logipar/Logipar.hx:225: characters 3-31
		$quotation = null;
		#src/logipar/Logipar.hx:227: characters 3-27
		$current = "";
		#src/logipar/Logipar.hx:228: characters 12-16
		$_g = 0;
		#src/logipar/Logipar.hx:228: characters 16-26
		$_g1 = mb_strlen($str);
		#src/logipar/Logipar.hx:228: lines 228-254
		while ($_g < $_g1) {
			#src/logipar/Logipar.hx:228: characters 12-26
			$i = $_g++;
			#src/logipar/Logipar.hx:229: characters 4-26
			$c = ($i < 0 ? "" : \mb_substr($str, $i, 1));
			#src/logipar/Logipar.hx:232: lines 232-238
			if ($this->quotations->indexOf($c) !== -1) {
				#src/logipar/Logipar.hx:233: lines 233-237
				if ($quotation === null) {
					#src/logipar/Logipar.hx:234: characters 6-15
					$quotation = $c;
				} else if ($quotation === $c) {
					#src/logipar/Logipar.hx:236: characters 6-15
					$quotation = null;
				}
			}
			#src/logipar/Logipar.hx:240: lines 240-253
			if (($quotation !== null) || ($keys->indexOf($this->tentativelyLower($c)) === -1)) {
				#src/logipar/Logipar.hx:241: lines 241-246
				if (\StringTools::isSpace($c, 0) && ($quotation === null)) {
					#src/logipar/Logipar.hx:242: lines 242-243
					if (mb_strlen($current) > 0) {
						#src/logipar/Logipar.hx:243: characters 7-27
						$tokens->arr[$tokens->length++] = $current;
					}
					#src/logipar/Logipar.hx:244: characters 6-13
					$current = "";
				} else {
					#src/logipar/Logipar.hx:246: characters 6-18
					$current = ($current??'null') . ($c??'null');
				}
			} else {
				#src/logipar/Logipar.hx:248: lines 248-250
				if (mb_strlen($current) > 0) {
					#src/logipar/Logipar.hx:249: characters 6-26
					$tokens->arr[$tokens->length++] = $current;
				}
				#src/logipar/Logipar.hx:251: characters 5-12
				$current = "";
				#src/logipar/Logipar.hx:252: characters 5-19
				$tokens->arr[$tokens->length++] = $c;
			}
		}
		#src/logipar/Logipar.hx:255: lines 255-256
		if (mb_strlen(\trim($current)) > 0) {
			#src/logipar/Logipar.hx:256: characters 4-31
			$x = \trim($current);
			$tokens->arr[$tokens->length++] = $x;
		}
		#src/logipar/Logipar.hx:257: characters 3-16
		return $tokens;
	}

	/**
	 * @param Token[]|\Array_hx $tokens
	 * 
	 * @return Node
	 */
	public function treeify ($tokens) {
		#src/logipar/Logipar.hx:129: characters 3-59
		$stack = new GenericStack();
		#src/logipar/Logipar.hx:130: characters 12-16
		$_g = 0;
		#src/logipar/Logipar.hx:130: characters 16-29
		$_g1 = $tokens->length;
		#src/logipar/Logipar.hx:130: lines 130-151
		while ($_g < $_g1) {
			#src/logipar/Logipar.hx:130: characters 12-29
			$i = $_g++;
			#src/logipar/Logipar.hx:131: characters 4-26
			$token = ($tokens->arr[$i] ?? null);
			#src/logipar/Logipar.hx:132: characters 4-28
			$n = new Node($token);
			#src/logipar/Logipar.hx:137: lines 137-149
			if ($token->type !== "LITERAL") {
				#src/logipar/Logipar.hx:139: lines 139-140
				if ($stack->head === null) {
					#src/logipar/Logipar.hx:140: characters 6-11
					throw Exception::thrown("An '" . (($this->syntax->data[$token->type] ?? null)??'null') . "' is missing a value to operate on (on its right).");
				}
				#src/logipar/Logipar.hx:141: characters 15-26
				$k = $stack->head;
				$tmp = null;
				if ($k === null) {
					$tmp = null;
				} else {
					$stack->head = $k->next;
					$tmp = $k->elt;
				}
				#src/logipar/Logipar.hx:141: characters 5-26
				$n->set_right($tmp);
				#src/logipar/Logipar.hx:144: lines 144-148
				if ($token->type !== "NOT") {
					#src/logipar/Logipar.hx:145: lines 145-146
					if ($stack->head === null) {
						#src/logipar/Logipar.hx:146: characters 7-12
						throw Exception::thrown("An '" . (($this->syntax->data[$token->type] ?? null)??'null') . "' is missing a value to operate on (on its left).");
					}
					#src/logipar/Logipar.hx:147: characters 15-26
					$k1 = $stack->head;
					$tmp1 = null;
					if ($k1 === null) {
						$tmp1 = null;
					} else {
						$stack->head = $k1->next;
						$tmp1 = $k1->elt;
					}
					#src/logipar/Logipar.hx:147: characters 6-26
					$n->set_left($tmp1);
				}
			}
			#src/logipar/Logipar.hx:150: characters 4-16
			$stack->head = new GenericCell($n, $stack->head);
		}
		#src/logipar/Logipar.hx:152: characters 19-30
		$k = $stack->head;
		#src/logipar/Logipar.hx:152: characters 3-31
		$parsetree = null;
		#src/logipar/Logipar.hx:152: characters 19-30
		if ($k === null) {
			#src/logipar/Logipar.hx:152: characters 3-31
			$parsetree = null;
		} else {
			#src/logipar/Logipar.hx:152: characters 19-30
			$stack->head = $k->next;
			#src/logipar/Logipar.hx:152: characters 3-31
			$parsetree = $k->elt;
		}
		#src/logipar/Logipar.hx:153: lines 153-155
		if ($stack->head !== null) {
			#src/logipar/Logipar.hx:154: characters 4-9
			throw Exception::thrown("Invalid logic string.  Do you have parentheses in your literals?");
		}
		#src/logipar/Logipar.hx:157: characters 3-19
		return $parsetree;
	}

	/**
	 * @param string[]|\Array_hx $tokens
	 * 
	 * @return Token[]|\Array_hx
	 */
	public function typeize ($tokens) {
		#src/logipar/Logipar.hx:274: characters 10-65
		$_g = new \Array_hx();
		#src/logipar/Logipar.hx:274: characters 20-24
		$_g1 = 0;
		#src/logipar/Logipar.hx:274: characters 24-37
		$_g2 = $tokens->length;
		#src/logipar/Logipar.hx:274: characters 11-64
		while ($_g1 < $_g2) {
			#src/logipar/Logipar.hx:274: characters 20-37
			$i = $_g1++;
			#src/logipar/Logipar.hx:274: characters 39-64
			$x = $this->tokenType(($tokens->arr[$i] ?? null));
			$_g->arr[$_g->length++] = $x;
		}
		#src/logipar/Logipar.hx:274: characters 10-65
		return $_g;
	}

	/**
	 * @param \Closure $f
	 * 
	 * @return void
	 */
	public function walk ($f) {
		#src/logipar/Logipar.hx:73: characters 3-15
		$this->tree->walk($f);
	}

	public function __toString() {
		return $this->toString();
	}
}

Boot::registerClass(Logipar::class, 'logipar.Logipar');

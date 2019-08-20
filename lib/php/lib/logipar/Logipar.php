<?php
/**
 * Generated by Haxe 4.0.0-rc.2+77068e10c
 */

namespace logipar;

use \php\Boot;
use \haxe\ds\GenericCell;
use \haxe\ds\EnumValueMap;
use \php\_Boot\HxException;
use \haxe\ds\GenericStack;

class Logipar {
	/**
	 * @var bool
	 */
	public $caseSensitive;
	/**
	 * @var \Array_hx
	 */
	public $quotations;
	/**
	 * @var EnumValueMap
	 */
	public $syntax;
	/**
	 * @var Node
	 */
	public $tree;

	/**
	 * You can define a custom syntax when you instantiate this.  There should be an example in the repo's readme.
	 * 
	 * @param EnumValueMap $custom_syntax
	 * 
	 * @return void
	 */
	public function __construct ($custom_syntax = null) {
		#src/logipar/Logipar.hx:15: lines 15-22
		$_g = new EnumValueMap();
		$_g->set(Syntax::AND(), "AND");
		$_g->set(Syntax::OR(), "OR");
		$_g->set(Syntax::XOR(), "XOR");
		$_g->set(Syntax::NOT(), "NOT");
		$_g->set(Syntax::OPEN(), "(");
		$_g->set(Syntax::CLOSE(), ")");
		$this->syntax = $_g;
		#src/logipar/Logipar.hx:14: characters 34-38
		$this->caseSensitive = true;
		#src/logipar/Logipar.hx:13: characters 26-36
		$this->quotations = \Array_hx::wrap([
			"\"",
			"'",
		]);
		#src/logipar/Logipar.hx:32: lines 32-35
		if ($custom_syntax !== null) {
			#src/logipar/Logipar.hx:33: characters 15-35
			$key = $custom_syntax->keys();
			while ($key->hasNext()) {
				#src/logipar/Logipar.hx:33: lines 33-35
				$key1 = $key->next();
				#src/logipar/Logipar.hx:34: lines 34-35
				if ($this->syntax->exists($key1)) {
					#src/logipar/Logipar.hx:35: characters 6-45
					$this->syntax->set($key1, $custom_syntax->get($key1));
				}
			}
		}
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
		#src/logipar/Logipar.hx:74: characters 3-23
		$enclosed = $this->tree;
		#src/logipar/Logipar.hx:75: lines 75-77
		return function ($a)  use (&$f, &$enclosed) {
			#src/logipar/Logipar.hx:76: characters 4-31
			return $enclosed->check($a, $f);
		};
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
		#src/logipar/Logipar.hx:44: characters 3-39
		$tokens = $this->tokenize($logic_string);
		#src/logipar/Logipar.hx:45: characters 3-31
		$types = $this->typeize($tokens);
		#src/logipar/Logipar.hx:46: characters 3-36
		$reversepolish = $this->shunt($types);
		#src/logipar/Logipar.hx:47: characters 3-32
		$this->tree = $this->treeify($reversepolish);
		#src/logipar/Logipar.hx:48: characters 3-14
		return $this->tree;
	}

	/**
	 * @param \Array_hx $tokens
	 * 
	 * @return \Array_hx
	 */
	public function shunt ($tokens) {
		#src/logipar/Logipar.hx:118: characters 3-48
		$output = new \Array_hx();
		#src/logipar/Logipar.hx:119: characters 3-65
		$operators = new GenericStack();
		#src/logipar/Logipar.hx:120: lines 120-154
		$_g = 0;
		$_g1 = $tokens->length;
		while ($_g < $_g1) {
			$i = $_g++;
			#src/logipar/Logipar.hx:121: characters 4-26
			$token = ($tokens->arr[$i] ?? null);
			#src/logipar/Logipar.hx:122: characters 11-21
			$__hx__switch = ($token->type->index);
			if ($__hx__switch === 4) {
				#src/logipar/Logipar.hx:126: characters 6-26
				$operators->head = new GenericCell($token, $operators->head);
			} else if ($__hx__switch === 5) {
				#src/logipar/Logipar.hx:128: lines 128-135
				while (true) {
					#src/logipar/Logipar.hx:129: characters 16-31
					$k = $operators->head;
					$op = null;
					if ($k === null) {
						$op = null;
					} else {
						$operators->head = $k->next;
						$op = $k->elt;
					}
					#src/logipar/Logipar.hx:129: characters 7-32
					$op1 = $op;
					#src/logipar/Logipar.hx:130: lines 130-131
					if ($op1->type === Syntax::OPEN()) {
						#src/logipar/Logipar.hx:131: characters 8-13
						break;
					}
					#src/logipar/Logipar.hx:132: lines 132-133
					if ($operators->head === null) {
						#src/logipar/Logipar.hx:133: characters 8-13
						throw new HxException("Mismatched parentheses.");
					}
					#src/logipar/Logipar.hx:134: characters 7-22
					$output->arr[$output->length] = $op1;
					++$output->length;

				}
			} else if ($__hx__switch === 6) {
				#src/logipar/Logipar.hx:124: characters 6-24
				$output->arr[$output->length] = $token;
				++$output->length;
			} else {
				#src/logipar/Logipar.hx:137: lines 137-150
				while ($operators->head !== null) {
					#src/logipar/Logipar.hx:141: characters 7-36
					$prev = ($operators->head === null ? null : $operators->head->elt);
					#src/logipar/Logipar.hx:143: lines 143-144
					if ($prev->type === Syntax::OPEN()) {
						#src/logipar/Logipar.hx:144: characters 8-13
						break;
					}
					#src/logipar/Logipar.hx:146: lines 146-147
					if ($prev->precedence() <= $token->precedence()) {
						#src/logipar/Logipar.hx:147: characters 8-13
						break;
					}
					#src/logipar/Logipar.hx:149: characters 19-34
					$k1 = $operators->head;
					$x = null;
					if ($k1 === null) {
						$x = null;
					} else {
						$operators->head = $k1->next;
						$x = $k1->elt;
					}
					#src/logipar/Logipar.hx:149: characters 7-35
					$output->arr[$output->length] = $x;
					++$output->length;

				}
				#src/logipar/Logipar.hx:151: characters 6-26
				$operators->head = new GenericCell($token, $operators->head);
			}
		}

		#src/logipar/Logipar.hx:157: lines 157-162
		while ($operators->head !== null) {
			#src/logipar/Logipar.hx:158: characters 12-27
			$k2 = $operators->head;
			$o = null;
			if ($k2 === null) {
				$o = null;
			} else {
				$operators->head = $k2->next;
				$o = $k2->elt;
			}
			#src/logipar/Logipar.hx:158: characters 4-28
			$o1 = $o;
			#src/logipar/Logipar.hx:159: lines 159-160
			if ($o1->type === Syntax::OPEN()) {
				#src/logipar/Logipar.hx:160: characters 5-10
				throw new HxException("Mismatched parentheses.");
			}
			#src/logipar/Logipar.hx:161: characters 4-18
			$output->arr[$output->length] = $o1;
			++$output->length;

		}
		#src/logipar/Logipar.hx:163: characters 3-16
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
		#src/logipar/Logipar.hx:62: characters 10-51
		if ($this->tree === null) {
			#src/logipar/Logipar.hx:62: characters 25-29
			return null;
		} else {
			#src/logipar/Logipar.hx:62: characters 32-51
			return $this->tree->fancyString($f);
		}
	}

	/**
	 * @param string $s
	 * 
	 * @return string
	 */
	public function tentativelyLower ($s) {
		#src/logipar/Logipar.hx:169: characters 10-57
		if ($this->caseSensitive) {
			#src/logipar/Logipar.hx:169: characters 26-27
			return $s;
		} else {
			#src/logipar/Logipar.hx:169: characters 30-57
			return mb_strtolower(\Std::string($s));
		}
	}

	/**
	 * @param string $token
	 * 
	 * @return Token
	 */
	public function tokenType ($token) {
		#src/logipar/Logipar.hx:217: characters 14-27
		$key = $this->syntax->keys();
		while ($key->hasNext()) {
			#src/logipar/Logipar.hx:217: lines 217-220
			$key1 = $key->next();
			#src/logipar/Logipar.hx:218: lines 218-219
			if ($this->tentativelyLower($token) === $this->tentativelyLower($this->syntax->get($key1))) {
				#src/logipar/Logipar.hx:219: characters 5-26
				return new Token($key1);
			}
		}

		#src/logipar/Logipar.hx:222: characters 3-42
		return new Token(Syntax::LITERAL(), $token);
	}

	/**
	 * @param string $str
	 * 
	 * @return \Array_hx
	 */
	public function tokenize ($str) {
		#src/logipar/Logipar.hx:177: characters 3-33
		$tokens = new \Array_hx();
		#src/logipar/Logipar.hx:178: characters 28-66
		$_g = new \Array_hx();
		#src/logipar/Logipar.hx:178: characters 38-44
		$x = $this->syntax->iterator();
		while ($x->hasNext()) {
			#src/logipar/Logipar.hx:178: characters 29-65
			$x1 = $x->next();
			#src/logipar/Logipar.hx:178: characters 46-65
			$x2 = $this->tentativelyLower($x1);
			$_g->arr[$_g->length] = $x2;
			++$_g->length;
		}

		#src/logipar/Logipar.hx:178: characters 3-67
		$keys = $_g;
		#src/logipar/Logipar.hx:179: characters 3-31
		$quotation = null;
		#src/logipar/Logipar.hx:181: characters 3-27
		$current = "";
		#src/logipar/Logipar.hx:182: lines 182-208
		$_g1 = 0;
		$_g2 = mb_strlen($str);
		while ($_g1 < $_g2) {
			$i = $_g1++;
			#src/logipar/Logipar.hx:183: characters 4-26
			$c = ($i < 0 ? "" : mb_substr($str, $i, 1));
			#src/logipar/Logipar.hx:184: lines 184-207
			if ($keys->indexOf($this->tentativelyLower($c)) === -1) {
				#src/logipar/Logipar.hx:187: lines 187-193
				if ($this->quotations->indexOf($c) !== -1) {
					#src/logipar/Logipar.hx:188: lines 188-192
					if ($quotation === null) {
						#src/logipar/Logipar.hx:189: characters 7-20
						$quotation = $c;
					} else if ($quotation === $c) {
						#src/logipar/Logipar.hx:191: characters 7-23
						$quotation = null;
					}
				}
				#src/logipar/Logipar.hx:195: lines 195-200
				if (\StringTools::isSpace($c, 0) && ($quotation === null)) {
					#src/logipar/Logipar.hx:196: lines 196-197
					if (mb_strlen($current) > 0) {
						#src/logipar/Logipar.hx:197: characters 7-27
						$tokens->arr[$tokens->length] = $current;
						++$tokens->length;
					}
					#src/logipar/Logipar.hx:198: characters 6-18
					$current = "";
				} else {
					#src/logipar/Logipar.hx:200: characters 6-18
					$current = ($current??'null') . ($c??'null');
				}
			} else {
				#src/logipar/Logipar.hx:202: lines 202-204
				if (mb_strlen($current) > 0) {
					#src/logipar/Logipar.hx:203: characters 6-26
					$tokens->arr[$tokens->length] = $current;
					++$tokens->length;
				}
				#src/logipar/Logipar.hx:205: characters 5-17
				$current = "";
				#src/logipar/Logipar.hx:206: characters 5-19
				$tokens->arr[$tokens->length] = $c;
				++$tokens->length;

			}
		}

		#src/logipar/Logipar.hx:209: lines 209-210
		if (mb_strlen(trim($current)) > 0) {
			#src/logipar/Logipar.hx:210: characters 4-31
			$x3 = trim($current);
			$tokens->arr[$tokens->length] = $x3;
			++$tokens->length;
		}
		#src/logipar/Logipar.hx:211: characters 3-16
		return $tokens;
	}

	/**
	 * @param \Array_hx $tokens
	 * 
	 * @return Node
	 */
	public function treeify ($tokens) {
		#src/logipar/Logipar.hx:84: characters 3-59
		$stack = new GenericStack();
		#src/logipar/Logipar.hx:85: lines 85-106
		$_g = 0;
		$_g1 = $tokens->length;
		while ($_g < $_g1) {
			$i = $_g++;
			#src/logipar/Logipar.hx:86: characters 4-26
			$token = ($tokens->arr[$i] ?? null);
			#src/logipar/Logipar.hx:87: characters 4-28
			$n = new Node($token);
			#src/logipar/Logipar.hx:92: lines 92-104
			if ($token->type !== Syntax::LITERAL()) {
				#src/logipar/Logipar.hx:94: lines 94-95
				if ($stack->head === null) {
					#src/logipar/Logipar.hx:95: characters 6-11
					throw new HxException("An '" . ($this->syntax->get($token->type)??'null') . "' is missing a value to operate on (on its right).");
				}
				#src/logipar/Logipar.hx:96: characters 15-26
				$k = $stack->head;
				$tmp = null;
				if ($k === null) {
					$tmp = null;
				} else {
					$stack->head = $k->next;
					$tmp = $k->elt;
				}
				#src/logipar/Logipar.hx:96: characters 5-26
				$n->right = $tmp;
				#src/logipar/Logipar.hx:99: lines 99-103
				if ($token->type !== Syntax::NOT()) {
					#src/logipar/Logipar.hx:100: lines 100-101
					if ($stack->head === null) {
						#src/logipar/Logipar.hx:101: characters 7-12
						throw new HxException("An '" . ($this->syntax->get($token->type)??'null') . "' is missing a value to operate on (on its left).");
					}
					#src/logipar/Logipar.hx:102: characters 15-26
					$k1 = $stack->head;
					$tmp1 = null;
					if ($k1 === null) {
						$tmp1 = null;
					} else {
						$stack->head = $k1->next;
						$tmp1 = $k1->elt;
					}
					#src/logipar/Logipar.hx:102: characters 6-26
					$n->left = $tmp1;
				}
			}
			#src/logipar/Logipar.hx:105: characters 4-16
			$stack->head = new GenericCell($n, $stack->head);
		}

		#src/logipar/Logipar.hx:107: characters 19-30
		$k2 = $stack->head;
		$parsetree = null;
		if ($k2 === null) {
			$parsetree = null;
		} else {
			$stack->head = $k2->next;
			$parsetree = $k2->elt;
		}
		#src/logipar/Logipar.hx:107: characters 3-31
		$parsetree1 = $parsetree;
		#src/logipar/Logipar.hx:108: lines 108-109
		if ($stack->head !== null) {
			#src/logipar/Logipar.hx:109: characters 4-9
			throw new HxException("Uhoh, the stack isn't empty.  Do you have neighbouring literals?");
		}
		#src/logipar/Logipar.hx:111: characters 3-19
		return $parsetree1;
	}

	/**
	 * @param \Array_hx $tokens
	 * 
	 * @return \Array_hx
	 */
	public function typeize ($tokens) {
		#src/logipar/Logipar.hx:228: characters 10-65
		$_g = new \Array_hx();
		#src/logipar/Logipar.hx:228: characters 11-64
		$_g1 = 0;
		$_g2 = $tokens->length;
		while ($_g1 < $_g2) {
			$i = $_g1++;
			#src/logipar/Logipar.hx:228: characters 39-64
			$x = $this->tokenType(($tokens->arr[$i] ?? null));
			$_g->arr[$_g->length] = $x;
			++$_g->length;

		}

		#src/logipar/Logipar.hx:228: characters 10-65
		return $_g;
	}
}

Boot::registerClass(Logipar::class, 'logipar.Logipar');

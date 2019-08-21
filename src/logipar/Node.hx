package logipar;

// This is used to represent every node in the tree
@:expose
@:keep
class Node {

	public var token:Token;  // The token for this node
	public var left:Node;  // Its left-child (presumably preceding operand)
	public var right:Node;  // Its right-child (presumably succeeding operand)

	public var f:Node->String; // This is so custom fancyString functions can call themselves recursively

	/**
	 * Construct a new node.  That is all.
	 */
	public function new(token:Token) {
		this.token = token;
	}


	/**
	 * The toString() function is just an un-fancy version of fancyString().
	 */
	public function toString():String { return fancyString(); }


	/**
	 * Maybe you want to display a node in a certain way.  This function allows for that.
	 * You shouldn't have to call it directly, but if you want to, look at 
	 * Logipar.stringify() examples in the repo's readme.
	 */
	public function fancyString(f:(Node)->String = null):String {
		return _fancyString(this, f);
	}


	private function _fancyString(n:Node, f:(Node)->String = null):String {
		var s = null;
		if (f != null) {
			n.f = _fancyString.bind(_, f); 
			s = f(n);
			n.f = null; // There it goes again
		}
		if (s != null)
			return s;
		switch(n.token.type) {
			case Token.LITERAL:
				return "{" + n.token.literal + "}";
			case Token.NOT:
				return "NOT(" + n.right.fancyString(f) + ")";
			default:
				return "(" + n.left.fancyString(f) + " " + Std.string(n.token.type) + " " + n.right.fancyString(f) + ")";
		}
	}


	/**
	 * This function lets us execute the tree.  You know, to actually check against data.
	 * You shouldn't have to call it directly, but if you want to, look at 
	 * Logipar.filterFunction() examples in the repo's readme.
	 */
	public function check(a:Dynamic, f:(row:Dynamic, value:String)->Bool):Bool {
		switch(token.type) {
			case Token.AND:
				return left.check(a,f) && right.check(a,f);
			case Token.OR:
				return left.check(a,f) || right.check(a,f);
			case Token.XOR:
				var l = left.check(a,f);
				var r = right.check(a,f);
				return (!l && r) || (l && !r);
			case Token.NOT:
				return !right.check(a,f);
			case Token.LITERAL: // Leaf
				return f(a, token.literal);
			default:
				throw "Unexpected token encountered.";
		}
	}
}
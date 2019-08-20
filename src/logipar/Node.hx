package logipar;

// This is used to represent every node in the tree
class Node {

	public var token:Token;  // The token for this node
	public var left:Node;  // Its left-child (presumably preceding operand)
	public var right:Node;  // Its right-child (presumably succeeding operand)


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
		var s = null;
		if (f != null)
			s = f(this);
		if (s != null)
			return s;
		switch(token.type) {
			case Syntax.LITERAL:
				return "{" + token.literal + "}";
			case Syntax.NOT:
				return "NOT(" + right + ")";
			default:
				return "(" + left + " " + Std.string(token.type) + " " + right + ")";
		}
	}


	/**
	 * This function lets us execute the tree.  You know, to actually check against data.
	 * You shouldn't have to call it directly, but if you want to, look at 
	 * Logipar.filterFunction() examples in the repo's readme.
	 */
	public function check(a:Dynamic, f:(row:Dynamic, value:String)->Bool):Bool {
		switch(token.type) {
			case Syntax.AND:
				return left.check(a,f) && right.check(a,f);
			case Syntax.OR:
				return left.check(a,f) || right.check(a,f);
			case Syntax.XOR:
				var l = left.check(a,f);
				var r = right.check(a,f);
				return (!l && r) || (l && !r);
			case Syntax.NOT:
				return !right.check(a,f);
			case Syntax.LITERAL: // Leaf
				return f(a, token.literal);
			default:
				throw "Unexpected token encountered.";
		}
	}
}
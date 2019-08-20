package logipar;


// This is a token.  That is all.  It represents a portion of the logic string.
@:expose
@:keep
class Token {

	public var type:Syntax;  // Which part of the syntax it represents
	public var literal:String;  // Its literal value (if it has one)


	/**
	 * Instantiate!
	 */
	public function new(type:Syntax, literal:String = null) {
		this.type = type;
		this.literal = literal;
	}


	/**
	 * What's the precedence of this token?  For, you know, order of operations and all that.
	 */
	public function precedence():Int {
		switch(type) {
			case Syntax.AND | Syntax.NOT:
				return 2;
			case Syntax.OR | Syntax.XOR:
				return 1;
			default:
				return 0;
		}
	}


	/**
	 * In case we want to display tokens, this'll make that easier.
	 */
	public function toString():String {
		if (this.type == Syntax.LITERAL)
			return "LITERAL(" + this.literal + ")";
		return Std.string(this.type);
	}
}
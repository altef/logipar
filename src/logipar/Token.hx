package logipar;


// This is a token.  That is all.  It represents a portion of the logic string.
#if !php
@:native('Token')
@:expose('Token')
#end
@:keep
class Token {
	public static inline var AND = "AND";
	public static inline var OR = "OR";
	public static inline var XOR = "XOR";
	public static inline var NOT = "NOT";
	public static inline var OPEN = "OPEN";
	public static inline var CLOSE = "CLOSE";
	public static inline var LITERAL = "LITERAL";


	public var type:String;  // Which token type it represents
	public var literal:String;  // Its literal value (if it has one)


	/**
	 * Instantiate!
	 */
	public function new(type:String, literal:String = null) {
		this.type = type;
		this.literal = literal;
	}


	/**
	 * What's the precedence of this token?  For, you know, order of operations and all that.
	 */
	public function precedence():Int {
		switch(type) {
			case Token.AND | Token.NOT:
				return 2;
			case Token.OR | Token.XOR:
				return 1;
			default:
				return 0;
		}
	}


	/**
	 * In case we want to display tokens, this'll make that easier.
	 */
	public function toString():String {
		if (this.type == Token.LITERAL)
			return "LITERAL(" + this.literal + ")";
		return Std.string(this.type);
	}
}
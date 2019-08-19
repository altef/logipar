using StringTools;
import haxe.ds.GenericStack;




class Token {
	public var type:Syntax;
	public var literal:String;
	public function new(type:Syntax, literal:String = null) {
		this.type = type;
		this.literal = literal;
	}
	
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
	
	public function toString():String {
		if (this.type == Syntax.LITERAL)
			return "LITERAL(" + this.literal + ")";
		return Std.string(this.type);
	}
}

class Node {
	public var token:Token;
	public var left:Node;
	public var right:Node;
	public function new(token:Token) {
		this.token = token;
	}
	
	
	public function toString():String { return fancyString(); }
	
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





@:expose
@:keep
enum Syntax {
	AND;
	OR;
	XOR;
	NOT;
	OPEN;
	CLOSE;
	LITERAL;
}


@:expose
@:keep
class LogicString {
	private var logictree:Node;
	// Syntax
	private var syntax:Map<Syntax, String> = [
		AND => 'AND',
		OR => 'OR',
		XOR => 'XOR',
		NOT => 'NOT',
		OPEN => '(',
		CLOSE => ')',
	];

	public var quotations = ['"', "'"];
	public var caseSensitive:Bool = true;

	public function new(custom_syntax:Map<Syntax, String>):Void {
		//trace("This is a LogicString constructor");
		//trace(this.syntax);
		//trace("Overwriting..");
		for(key in custom_syntax.keys())
			if (syntax.exists(key))
				syntax.set(key, custom_syntax.get(key));
		//trace(this.syntax);
	}
	
	
	public function parse(logic_string:String):Dynamic { // This will be a logic tree
		trace(logic_string);
		var tokens = tokenize(logic_string);
		trace(tokens);
		var types = typeize(tokens);
		trace(types);
		var reversepolish = shunt(types);
		trace(reversepolish);
		var tree = tree(reversepolish);
		this.logictree = tree;
		return tree;
	}
	
	
	public function stringify(f:(Node)->String):String {
		return logictree.fancyString(f);
	}
	
	public function filterFunction(f:(row:Dynamic, value:String)->Bool):(Dynamic)->Bool {
		var enclosed = logictree;
		return function(a:Dynamic):Bool {
			// Go through the nodes...
			return enclosed.check(a, f);
		}
	}
	
	private function tree(tokens:Array<Token>):Node {
		var stack:GenericStack<Node> = new GenericStack<Node>();
		for(i in 0...tokens.length) {
			var token = tokens[i];
			var n = new Node(token);
			
			if (token.type != Syntax.LITERAL) {
				if (stack.isEmpty())
					throw "An '" + syntax.get(token.type) + "' is missing a value to operate on (on its right).";
				n.right = stack.pop();
				if (token.type != Syntax.NOT) {
					if (stack.isEmpty())
						throw "An '" + syntax.get(token.type) + "' is missing a value to operate on (on its left).";
					n.left = stack.pop();
				}
			
			}
			stack.add(n);
		}
		var parsetree = stack.pop();
		if (!stack.isEmpty()) {
			trace(stack);
			throw "I expected the stack to be empty but it's not!";
		}
		return parsetree;
	}
	
	
	private function shunt(tokens:Array<Token>):Array<Token> {
		var output:Array<Token> = new Array<Token>();
		var operators:GenericStack<Token> = new GenericStack<Token>();
		trace("Reverse polish:");
		for(i in 0...tokens.length) {
			trace(":::");
			trace(output);
			trace(operators);
			var token = tokens[i];
			switch(token.type) {
				case Syntax.LITERAL:
					output.push(token);
				case Syntax.OPEN:
					operators.add(token);
				case Syntax.CLOSE:
					while(true) {
						var op = operators.pop();
						if (op.type == Syntax.OPEN) 
							break;
						if (operators.isEmpty())
							throw "Mismatched parentheses.";
						output.push(op);
					}
				default:
					while(true) {
						if (operators.isEmpty())
							break;
						
						var prev = operators.first();
						
						if (prev.type == Syntax.OPEN) {
							break;
						}
						
						if (prev.precedence() <= token.precedence()) 
							break;
						
						output.push(operators.pop());
					}
					operators.add(token);

			}
		}
		
		while(!operators.isEmpty()) {
			var o = operators.pop();
			if (o.type == Syntax.OPEN)
				throw "Mismatched parentheses.";
			output.push(o);
		}
		return output;
	}

	
	private function tentativelyLower(s:String):String {
		return caseSensitive ? s : Std.string(s).toLowerCase();
	}
	
	// This assumes things.. Like you'll reasonably have spaces between things.
	// If you don't those things should only be one character (like brackets)
	private function tokenize(str:String):Array<String> {
		var tokens:Array<String> = [];
		var keys:Array<String> = [for(x in syntax) tentativelyLower(x)];
		var quotation:String = null;
		
		var current:String = '';
		for(i in 0...str.length) {
			var c = str.charAt(i);
			if (keys.indexOf(tentativelyLower(c)) == -1) {
				
				// Toggle in and out of quotation mode
				if (quotations.indexOf(c) != -1) {
					if (quotation == null) {
						quotation = c;
					} else if (quotation == c) {
						quotation = null;
					}
				}
				
				if (c.isSpace(0) && quotation == null) {
					if (current.length > 0)
						tokens.push(current);
					current = '';
				} else
					current += c;
			} else {
				if (current.length > 0) {
					tokens.push(current);
				}
				current = '';
				tokens.push(c);
			}
		}
		if (current.trim().length > 0) 
			tokens.push(current.trim());
		return tokens;
	}
	
	
	
	private function tokenType(token:String):Token {
		for(key in syntax.keys()) {
			if (tentativelyLower(token) == tentativelyLower(syntax.get(key)))
				return new Token(key);
		}
		return new Token(Syntax.LITERAL, token);
	}
	
	
	private function typeize(tokens:Array<String>):Array<Token> {
		return [for(i in 0...tokens.length) this.tokenType(tokens[i])];
	}
}
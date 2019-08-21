package logipar;


using StringTools;
import haxe.ds.GenericStack;


@:expose
@:keep
class Logipar {


	public var quotations = ['"', "'"];  // You can add to the list of quotation symbols for the whitepsace toggling
	public var caseSensitive:Bool = true;  // In case you don't want the operators to be case sensitive
	private var syntax:Map<String, String> = [  // Default syntax.
		Token.AND => 'AND',
		Token.OR => 'OR',
		Token.XOR => 'XOR',
		Token.NOT => 'NOT',
		Token.OPEN => '(',
		Token.CLOSE => ')',
	];

	private var tree:Node;  // This is the internal representation of the tree.  It's null.


	/**
	 * Empty constructor.
	 */
	public function new() {}


	/**
	 * Overwrite a particular operator with your own.
	 */
	public function overwrite(op:String, value:String):Void {
		if (syntax.exists(op))
			syntax.set(op, value);
	}


	/**
	 * Parse the logic string!  It returns a logipar.Node (the root of the tree), but you can pretty much ignore this.  
	 * The tree is stored in the instance anyway.
	 */
	public function parse(logic_string:String):Node { 
		var tokens = tokenize(logic_string);  // Lex that guy!
		var types = typeize(tokens);  // Figure out the what types each chunk represents
		var reversepolish = shunt(types);  // Ugh order of operations, am I right?
		tree = treeify(reversepolish);  // Arboriculture
		return tree;
	}


	/**
	 * Sometimes you want your logic tree represented as a string.  Either for display,
	 * or maybe to use with SQL.  Hey, I'm not judging - this function should provide for 
	 * all your stringifying needs.
	 * It takes a function as its param, which in turn takes a Node and provides a String.
	 * In this way you can have it display in pretty much any way you want.
	 * Anything you don't account for will use the default toString() function.
	 * Confused?  Don't worry, there should be an example on the repo's readme.
	 */
	public function stringify(f:(Node)->String = null):String {
		return tree == null ? null : tree.fancyString(f);
	}


	/**
	 * Sometimes you just want to filter a list of rows, right?
	 * This function creates a filter function for you, based on your needs.
	 * It takes a function as its param, which in turn takes a row (an object of your data), and a value.
	 * The value is a particular literal on the tree - so you can deal with them however you want.
	 * I dunno, it's kind of hard to explain.. Check out the repo's readme for an example, hopefully that will help!
	 */
	public function filterFunction(f:(row:Dynamic, value:String)->Bool):(Dynamic)->Bool {
		var enclosed = tree;
		return function(a:Dynamic):Bool {
			if (enclosed == null)
				return true;
			return enclosed.check(a, f);  // Recursively check through the tree
		}
	}


	public function toString():String {
		return stringify();
	}


	// This is a private function - what're you doing here?!
	// JK. This converts a list of tokens into a tree.  It assumes it's already been through the shunter so it ignores order of operations.
	private function treeify(tokens:Array<Token>):Node {
		var stack:GenericStack<Node> = new GenericStack<Node>(); //  Temporary buffer
		for(i in 0...tokens.length) {
			var token = tokens[i];  // Current token
			var n = new Node(token);  // A node wrapper for that guy
			
			
			// If it's a literal, we can just add it.
			// But if it's not, it should have a child - so let's link those up.
			if (token.type != Token.LITERAL) { 
				// They should ALL have a right-most child
				if (stack.isEmpty())
					throw "An '" + syntax.get(token.type) + "' is missing a value to operate on (on its right).";
				n.right = stack.pop();
				
				// All the BINARY ones have a left-most child
				if (token.type != Token.NOT) {
					if (stack.isEmpty())
						throw "An '" + syntax.get(token.type) + "' is missing a value to operate on (on its left).";
					n.left = stack.pop();
				}
			}
			stack.add(n);  // Add the node, now that it's all linked up
		}
		var parsetree = stack.pop();  // The first node should be the root of the tree
		if (!stack.isEmpty())  // But if it isn't, that means something's gone wrong.  Probably neighbouring LITERALs
			throw "Uhoh, the stack isn't empty.  Do you have neighbouring literals?";
		
		return parsetree;
	}


	// This should basically be the shunting-yard algorithm (https://en.wikipedia.org/wiki/Shunting-yard_algorithm)
	// It takes the list of tokens and shunts them into reverse polish notation (https://en.wikipedia.org/wiki/Reverse_Polish_notation)
	private function shunt(tokens:Array<Token>):Array<Token> {
		var output:Array<Token> = new Array<Token>();
		var operators:GenericStack<Token> = new GenericStack<Token>();
		for(i in 0...tokens.length) {
			var token = tokens[i];
			switch(token.type) {  // Do different things based on what type of token it is
				case Token.LITERAL:  // If it's a literal, go directly to the output
					output.push(token);
				case Token.OPEN:  // If it's an open parenthesis, go directly to the operators
					operators.add(token);
				case Token.CLOSE:  // If it's a close parenthesis, pop things off the operators until it finds an open parentheses on there
					while(true) {
						var op = operators.pop();
						if (op.type == Token.OPEN) 
							break;
						if (operators.isEmpty())
							throw "Mismatched parentheses.";
						output.push(op);
					}
				default: // Otherwise, it's an actual operator of some kind!
					while(true) {
						if (operators.isEmpty()) // We've reached the end
							break;
						
						var prev = operators.first();
						
						if (prev.type == Token.OPEN) 
							break;
						
						if (prev.precedence() <= token.precedence()) 
							break;
						
						output.push(operators.pop());
					}
					operators.add(token);

			}
		}
		
		// Finish off any operators left in their stack
		while(!operators.isEmpty()) {
			var o = operators.pop();
			if (o.type == Token.OPEN)
				throw "Mismatched parentheses.";
			output.push(o);
		}
		return output;
	}


	// Convert a string to lowercase IF the instance's caseSensitive property has been set to true
	private function tentativelyLower(s:String):String {
		return caseSensitive ? s : Std.string(s).toLowerCase();
	}


	// Turn the logic string into tokens!
	// This assumes things.. Like you'll reasonably have spaces between things.  It does support spaces within quotation marks though.
	// If you don't use spaces, operators should only be one character (like hopefully the parentheses are)
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


	// Turn a string into an actual Token instance
	private function tokenType(token:String):Token {
		for(key in syntax.keys()) { // Figure out which token it should be
			if (tentativelyLower(token) == tentativelyLower(syntax.get(key)))
				return new Token(key);
		}
		// Otherwise it's a literal, I guess!
		return new Token(Token.LITERAL, token);
	}


	// You know.. transform an array of string tokens into an array of Token instances
	private function typeize(tokens:Array<String>):Array<Token> {
		return [for(i in 0...tokens.length) this.tokenType(tokens[i])];
	}
}
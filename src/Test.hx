using StringTools;
import buddy.*;
using buddy.Should;


class Test extends buddy.SingleSuite {
	
	function unwrapped(n:logipar.Node):String {
		if (n.token.type == logipar.Token.LITERAL) {
			return Std.string(n.token.literal); // So literals aren't wrapped in {}
		}
		return null;
	}

	function binarylogic(row:Dynamic, value:String):Bool {
		return value.trim() == "1";
	}


	function test(parser:logipar.Logipar, str:String, expect:Bool):Array<Bool> {
		parser.parse(str);
		var parsertwo = new logipar.Logipar();
		
		// Test that the minimal string is logically equivalent
		parser.walk(function(n:logipar.Node):Void { n.bracketing = logipar.Node.MINIMAL_BRACKETS; });
		var minimal = parser.stringify(unwrapped);
		parsertwo.parse(minimal);
		var r1 = parser.equals(parsertwo);
		
		// Test that the maximal string is logically equivalent
		parser.walk(function(n:logipar.Node):Void { n.bracketing = logipar.Node.MAXIMAL_BRACKETS; });
		var maximal = parser.stringify(unwrapped);
		parsertwo.parse(maximal);
		var r2 = parser.equals(parsertwo);
		
		// Test that the expected value is what the logic string resolves to
		var r3 = parser.filterFunction(binarylogic)([]) == expect;
		
		// Make sure the strings match, just in case
		var r4 = parsertwo.stringify(unwrapped) == minimal;
		
		return [r1, r2, r3, r4];
	}

	public function new() {
		
		// Neighbouring literals wrt brackets....
		describe("Parsing tests", {
			// Let's try changing the syntax
			var l = new logipar.Logipar();
			l.quotations.push('`'); // Add a new fake quotation mark
			l.caseSensitive = false;
			l.overwrite(logipar.Token.AND, 'et');
			l.overwrite(logipar.Token.OR, 'ou');
			l.overwrite(logipar.Token.XOR, 'xou');
			l.overwrite(logipar.Token.NOT, 'non');
			l.overwrite(logipar.Token.OPEN, '[');
			l.overwrite(logipar.Token.CLOSE, ']');
			
			var tests = {
				"Do redefined operators work?": 		["[1 ou 1] et 0 ou non 1", "({1} OR {1}) AND {0} OR NOT {1}"],
				"Do custom quote characters work?": 	['one=`hello ou there`', "{one=`hello ou there`}"], 
				"Does case insensitivity work?": 		['"one et two" OU \'three\'', '{"one et two"} OR {\'three\'}'],
				"Are keywords inside quotes ignored?": 	['"a et b" ou c', "{\"a et b\"} OR {c}"],
				"Do brackets inside quotes work?": 		['"a et [b]" ou c', "{\"a et [b]\"} OR {c}"],
				"Does literal merging work?": 			['This is a  cat', "{This is a cat}"],
				//['This [was] a cat et a dog', '{This [was] a cat}'], // What about literals with brackets in them.. Should I support this?  Currently I don't.
				"Do literals epand the way we expect?": ['this is a cat ou this is a dog ou rat', "{this is a cat} OR {this is a dog} OR {rat}"],
				"Are nested brackets dealt with appropriately?": ['[[[[[[test]]] ou [[[[nothing]]]]]]]', '{test} OR {nothing}'],
			};
			
			for (n in Reflect.fields(tests)) {
				var t = Reflect.field(tests, n);
				it(n, {
					l.parse(t[0]);
					l.stringify().should.be(t[1]);
				});
			}
		});


		describe("Logic tests", {
			var l = new logipar.Logipar();
			l.caseSensitive = false;
			var tests:Array<Dynamic> = [
				["1", true],
				["0", false],
				["NOT 1", false],
				["NOT 0", true],
				["1 AND 1", true],
				["1 AND 0", false],
				["1 OR 1", true],
				["1 OR 0", true],
				["0 OR 0", false],
				["1 XOR 1", false],
				["1 XOR 0", true],
				["0 XOR 0", false],
				["NOT 1 OR 1", true],
				["not 0 and 1", true], // Case insensitive
				["1 AND 0 XOR 1 AND 0", false],
				["(1 or 0) and (not (1 and 1) or 0)", false],
				['1 and (0 or 1)', true],
				['not (1 or 0) and (1 or 0)', false],
				['1 OR (0 AND NOT(1 OR 0))', true],
				['(((1)) OR (0))', true], // Nested brackets?
			];
			for(t in tests) {
				it(t[0], {
					for (result in test(l, t[0], t[1]))
						result.should.be(true);
				});
			}
		});
	}
	
}
using StringTools;
import buddy.*;
using buddy.Should;


class Test extends buddy.SingleSuite {
	
	public function new() {
		
		describe("Overwriting syntax", {
		
			// Let's try changing the syntax
			var l = new logipar.Logipar();
			l.overwrite(logipar.Token.AND, 'et');
			l.overwrite(logipar.Token.OR, 'ou');
			l.overwrite(logipar.Token.XOR, 'xou');
			l.overwrite(logipar.Token.NOT, 'non');
			l.overwrite(logipar.Token.OPEN, '[');
			l.overwrite(logipar.Token.CLOSE, ']');
			
			var tests = [
				["[one ou two] et three ou non four", "((({one} OR {two}) AND {three}) OR NOT({four}))"],
			];
			
			for(t in tests) {
				it("Testing: " + t[0], {
					l.parse(t[0]);
					l.stringify().should.be(t[1]);
				});
			}
		});
		
		describe("Testing quotation marks", {
			var l = new logipar.Logipar();
			l.quotations.push('`'); // Add a new fake quotation mark
			var tests = [
				['one=`hello there`', "{one=`hello there`}"],
				['"one and two" OR \'three\'', '({"one and two"} OR {\'three\'})']
			];
			for(t in tests) {
				it("Testing: " + t[0], {
					l.parse(t[0]);
					l.stringify().should.be(t[1]);
				});
			}
		});
		
		
		describe("Testing case-insensitivity", {
			var l = new logipar.Logipar();
			l.caseSensitive = false;
			var tests = [
				['a and b or c', "(({a} AND {b}) OR {c})"],
			];
			for(t in tests) {
				it("Testing: " + t[0], {
					l.parse(t[0]);
					l.stringify().should.be(t[1]);
				});
			}
		});
		

		
		describe("Other random logic tests", {
			var l = new logipar.Logipar();
			var tests = [
				["NOT a OR b", "(NOT({a}) OR {b})"],
				["NOT a AND b", "(NOT({a}) AND {b})"],
				["a AND b XOR c AND d", "(({a} AND {b}) XOR ({c} AND {d}))"],
			];
			for(t in tests) {
				it("Testing: " + t[0], {
					l.parse(t[0]);
					l.stringify().should.be(t[1]);
				});
			}
		});
		
		
		describe("Testing a custom string", {
			var l = new logipar.Logipar();
			var tests = [
				["NOT a OR b", "(NOT(a) OR b)"],
				["NOT a AND b", "(NOT(a) AND b)"],
				["a XOR b", "((a AND NOT b) OR (NOT a AND b))"],
			];
			for(t in tests) {
				it("Testing: " + t[0], {
					l.parse(t[0]);
					l.stringify(function(n:logipar.Node):String {
						if (n.token.type == logipar.Token.XOR) {
							return "((" + n.f(n.left) + " AND NOT " + n.f(n.right) + ") OR (NOT " + n.f(n.left) + " AND " + n.f(n.right) + "))";
						}
						if (n.token.type == logipar.Token.LITERAL) {
							return Std.string(n.token.literal);
						}
						return null;
					}).should.be(t[1]);
				});
			}

		});
		
		describe("Does quoting still work?", {
			var l = new logipar.Logipar();
			l.caseSensitive = false;
			var tests = [
				['"a and b" or c', "({\"a and b\"} OR {c})"],
				['"a and (b)" or c', "({\"a and (b)\"} OR {c})"],
			];
			for(t in tests) {
				it("Testing: " + t[0], {
					l.parse(t[0]);
					l.stringify().should.be(t[1]);
				});
			}
		});
		
		describe("Do literals merge as expected?", {
			var l = new logipar.Logipar();
			l.caseSensitive = false;
			var tests = [
				['this is a cat OR this is a dog OR rat', "({this is a cat} OR ({this is a dog} OR {rat}))"],
			];
			for(t in tests) {
				it("Testing: " + t[0], {
					l.parse(t[0]);
					l.stringify().should.be(t[1]);
				});
			}
		});

		
		// I should test the filterFunction stuff, but I'm too lazy atm...
	}
	
}
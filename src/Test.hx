using StringTools;

class Test {
	static public function main():Void {
		
		
		var ls = new LogicString([
			LogicString.Syntax.AND => 'et',
			LogicString.Syntax.OR => 'ou',
			LogicString.Syntax.XOR => 'xou',
			LogicString.Syntax.NOT => 'non',
			LogicString.Syntax.OPEN => '[',
			LogicString.Syntax.CLOSE => ']'
		]);
		
		var ts = '[one="1 et 0" et two] ou three et non[five]';
		// ts = '[a ou b] et c ou non d';
		// ts = 'non a ou b';
		// ts = 'non a et b';
		// ts = 'et b []';
		//ts = 'a xou b';
		//ts = '0 et 1 ou 2 ou 3 et 4'; // Is this one right?
		ts = '[authors:"J." OU sea] xou guts';
		//ts = 'authors:J';
		ls.caseSensitive = false; // *** Implement this.  It's so our AND and OR etc can be any case
		var obj:LogicString.Node = ls.parse(ts);
		trace(obj);
		var s = ls.stringify(function(n:LogicString.Node):String {
			if (n.token.type == LogicString.Syntax.XOR) {
				return "((" + n.left + " AND NOT " + n.right + ") OR (NOT " + n.left + " AND " + n.right + "))";
			}
			return null;
		});
		trace(s);
		

		
		var f:(Dynamic)->Bool = ls.filterFunction(function(row:Dynamic, value:String):Bool {
			value = value.replace('"', '');
			if (value.indexOf(":") == -1) {
				// This is a dumb one that just checks the values against every column
				for (f in Reflect.fields(row)) {
					if (Std.string(Reflect.field(row, f)).toLowerCase().indexOf(value.toLowerCase()) != -1)
						return true;
				}
			} else {
				// We're specifying a specific field we want to look in
				var chunks = value.split(':');
				var field = chunks.shift();
				var val = chunks.join(':');
				if (Reflect.hasField(row, field)) {
					if (Std.string(Reflect.field(row, field)).toLowerCase().indexOf(val.toLowerCase()) != -1)
						return true;
				}
			}
			return false;
		});
		
		trace("Filtering sample data:");
		var d:Array<Dynamic> = [];
		for(i in 0...SampleData.data.length) {
			if (f(SampleData.data[i]))
				d.push(SampleData.data[i]);
		}
		
		for(book in d)
			//trace(book);
			trace(book.title + " by " + book.authors);
	}
	
}
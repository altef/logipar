(function ($hx_exports, $global) { "use strict";
function $extend(from, fields) {
	var proto = Object.create(from);
	for (var name in fields) proto[name] = fields[name];
	if( fields.toString !== Object.prototype.toString ) proto.toString = fields.toString;
	return proto;
}
var HxOverrides = function() { };
HxOverrides.__name__ = true;
HxOverrides.cca = function(s,index) {
	var x = s.charCodeAt(index);
	if(x != x) {
		return undefined;
	}
	return x;
};
HxOverrides.substr = function(s,pos,len) {
	if(len == null) {
		len = s.length;
	} else if(len < 0) {
		if(pos == 0) {
			len = s.length + len;
		} else {
			return "";
		}
	}
	return s.substr(pos,len);
};
HxOverrides.now = function() {
	return Date.now();
};
Math.__name__ = true;
var Std = function() { };
Std.__name__ = true;
Std.string = function(s) {
	return js_Boot.__string_rec(s,"");
};
var StringTools = function() { };
StringTools.__name__ = true;
StringTools.isSpace = function(s,pos) {
	var c = HxOverrides.cca(s,pos);
	if(!(c > 8 && c < 14)) {
		return c == 32;
	} else {
		return true;
	}
};
StringTools.ltrim = function(s) {
	var l = s.length;
	var r = 0;
	while(r < l && StringTools.isSpace(s,r)) ++r;
	if(r > 0) {
		return HxOverrides.substr(s,r,l - r);
	} else {
		return s;
	}
};
StringTools.rtrim = function(s) {
	var l = s.length;
	var r = 0;
	while(r < l && StringTools.isSpace(s,l - r - 1)) ++r;
	if(r > 0) {
		return HxOverrides.substr(s,0,l - r);
	} else {
		return s;
	}
};
StringTools.trim = function(s) {
	return StringTools.ltrim(StringTools.rtrim(s));
};
var haxe_Exception = function(message,previous,native) {
	Error.call(this,message);
	this.message = message;
	this.__previousException = previous;
	this.__nativeException = native != null ? native : this;
};
haxe_Exception.__name__ = true;
haxe_Exception.thrown = function(value) {
	if(((value) instanceof haxe_Exception)) {
		return value.get_native();
	} else if(((value) instanceof Error)) {
		return value;
	} else {
		var e = new haxe_ValueException(value);
		return e;
	}
};
haxe_Exception.__super__ = Error;
haxe_Exception.prototype = $extend(Error.prototype,{
	get_native: function() {
		return this.__nativeException;
	}
});
var haxe_ValueException = function(value,previous,native) {
	haxe_Exception.call(this,String(value),previous,native);
	this.value = value;
};
haxe_ValueException.__name__ = true;
haxe_ValueException.__super__ = haxe_Exception;
haxe_ValueException.prototype = $extend(haxe_Exception.prototype,{
});
var haxe_ds_GenericCell = function(elt,next) {
	this.elt = elt;
	this.next = next;
};
haxe_ds_GenericCell.__name__ = true;
var haxe_ds_GenericStack = function() {
};
haxe_ds_GenericStack.__name__ = true;
var haxe_ds_StringMap = function() {
	this.h = Object.create(null);
};
haxe_ds_StringMap.__name__ = true;
haxe_ds_StringMap.stringify = function(h) {
	var s = "{";
	var first = true;
	for (var key in h) {
		if (first) first = false; else s += ',';
		s += key + ' => ' + Std.string(h[key]);
	}
	return s + "}";
};
haxe_ds_StringMap.prototype = {
	toString: function() {
		return haxe_ds_StringMap.stringify(this.h);
	}
};
var haxe_iterators_ArrayIterator = function(array) {
	this.current = 0;
	this.array = array;
};
haxe_iterators_ArrayIterator.__name__ = true;
haxe_iterators_ArrayIterator.prototype = {
	hasNext: function() {
		return this.current < this.array.length;
	}
	,next: function() {
		return this.array[this.current++];
	}
};
var js_Boot = function() { };
js_Boot.__name__ = true;
js_Boot.__string_rec = function(o,s) {
	if(o == null) {
		return "null";
	}
	if(s.length >= 5) {
		return "<...>";
	}
	var t = typeof(o);
	if(t == "function" && (o.__name__ || o.__ename__)) {
		t = "object";
	}
	switch(t) {
	case "function":
		return "<function>";
	case "object":
		if(((o) instanceof Array)) {
			var str = "[";
			s += "\t";
			var _g = 0;
			var _g1 = o.length;
			while(_g < _g1) {
				var i = _g++;
				str += (i > 0 ? "," : "") + js_Boot.__string_rec(o[i],s);
			}
			str += "]";
			return str;
		}
		var tostr;
		try {
			tostr = o.toString;
		} catch( _g ) {
			return "???";
		}
		if(tostr != null && tostr != Object.toString && typeof(tostr) == "function") {
			var s2 = o.toString();
			if(s2 != "[object Object]") {
				return s2;
			}
		}
		var str = "{\n";
		s += "\t";
		var hasp = o.hasOwnProperty != null;
		var k = null;
		for( k in o ) {
		if(hasp && !o.hasOwnProperty(k)) {
			continue;
		}
		if(k == "prototype" || k == "__class__" || k == "__super__" || k == "__interfaces__" || k == "__properties__") {
			continue;
		}
		if(str.length != 2) {
			str += ", \n";
		}
		str += s + k + " : " + js_Boot.__string_rec(o[k],s);
		}
		s = s.substring(1);
		str += "\n" + s + "}";
		return str;
	case "string":
		return o;
	default:
		return String(o);
	}
};
var Logipar = $hx_exports["Logipar"] = function() {
	var _g = new haxe_ds_StringMap();
	_g.h["AND"] = "AND";
	_g.h["OR"] = "OR";
	_g.h["XOR"] = "XOR";
	_g.h["NOT"] = "NOT";
	_g.h["OPEN"] = "(";
	_g.h["CLOSE"] = ")";
	this.syntax = _g;
	this.mergeAdjacentLiterals = true;
	this.caseSensitive = true;
	this.quotations = ["\"","'"];
};
Logipar.__name__ = true;
Logipar.prototype = {
	overwrite: function(op,value) {
		if(Object.prototype.hasOwnProperty.call(this.syntax.h,op)) {
			this.syntax.h[op] = value;
		}
	}
	,prepareTokens: function(logic_string) {
		var tokens = this.tokenize(logic_string);
		var types = this.typeize(tokens);
		if(this.mergeAdjacentLiterals) {
			types = this.mergeLiterals(types);
		}
		return types;
	}
	,parse: function(logic_string) {
		var types = this.prepareTokens(logic_string);
		var reversepolish = this.shunt(types);
		this.tree = this.treeify(reversepolish);
		return this.tree;
	}
	,stringify: function(f) {
		if(this.tree == null) {
			return null;
		} else {
			return this.tree.fancyString(f);
		}
	}
	,walk: function(f) {
		this.tree.walk(f);
	}
	,filterFunction: function(f) {
		var enclosed = this.tree;
		return function(a) {
			if(enclosed == null) {
				return true;
			}
			return enclosed.check(a,f);
		};
	}
	,toString: function() {
		return this.stringify();
	}
	,equals: function(b) {
		return this.tree.equals(b.tree);
	}
	,mergeLiterals: function(tokens) {
		var merged = [];
		var _g = 0;
		var _g1 = tokens.length;
		while(_g < _g1) {
			var i = _g++;
			if(tokens[i].type == "LITERAL") {
				if(i > 0 && merged[merged.length - 1].type == "LITERAL") {
					merged[merged.length - 1].literal += " " + tokens[i].literal;
					continue;
				}
			}
			merged.push(tokens[i]);
		}
		return merged;
	}
	,treeify: function(tokens) {
		var stack = new haxe_ds_GenericStack();
		var _g = 0;
		var _g1 = tokens.length;
		while(_g < _g1) {
			var i = _g++;
			var token = tokens[i];
			var n = new Node(token);
			if(token.type != "LITERAL") {
				if(stack.head == null) {
					throw haxe_Exception.thrown("An '" + this.syntax.h[token.type] + "' is missing a value to operate on (on its right).");
				}
				var k = stack.head;
				var tmp;
				if(k == null) {
					tmp = null;
				} else {
					stack.head = k.next;
					tmp = k.elt;
				}
				n.set_right(tmp);
				if(token.type != "NOT") {
					if(stack.head == null) {
						throw haxe_Exception.thrown("An '" + this.syntax.h[token.type] + "' is missing a value to operate on (on its left).");
					}
					var k1 = stack.head;
					var tmp1;
					if(k1 == null) {
						tmp1 = null;
					} else {
						stack.head = k1.next;
						tmp1 = k1.elt;
					}
					n.set_left(tmp1);
				}
			}
			stack.head = new haxe_ds_GenericCell(n,stack.head);
		}
		var k = stack.head;
		var parsetree;
		if(k == null) {
			parsetree = null;
		} else {
			stack.head = k.next;
			parsetree = k.elt;
		}
		if(stack.head != null) {
			throw haxe_Exception.thrown("Invalid logic string.  Do you have parentheses in your literals?");
		}
		return parsetree;
	}
	,shunt: function(tokens) {
		var output = [];
		var operators = new haxe_ds_GenericStack();
		var _g = 0;
		var _g1 = tokens.length;
		while(_g < _g1) {
			var i = _g++;
			var token = tokens[i];
			switch(token.type) {
			case "CLOSE":
				while(true) {
					var k = operators.head;
					var op;
					if(k == null) {
						op = null;
					} else {
						operators.head = k.next;
						op = k.elt;
					}
					if(op.type == "OPEN") {
						break;
					}
					if(operators.head == null) {
						throw haxe_Exception.thrown("Mismatched parentheses.");
					}
					output.push(op);
				}
				break;
			case "LITERAL":
				output.push(token);
				break;
			case "OPEN":
				operators.head = new haxe_ds_GenericCell(token,operators.head);
				break;
			default:
				while(operators.head != null) {
					var prev = operators.head == null ? null : operators.head.elt;
					if(prev.type == "OPEN") {
						break;
					}
					if(prev.precedence() <= token.precedence()) {
						break;
					}
					var k1 = operators.head;
					var tmp;
					if(k1 == null) {
						tmp = null;
					} else {
						operators.head = k1.next;
						tmp = k1.elt;
					}
					output.push(tmp);
				}
				operators.head = new haxe_ds_GenericCell(token,operators.head);
			}
		}
		while(operators.head != null) {
			var k = operators.head;
			var o;
			if(k == null) {
				o = null;
			} else {
				operators.head = k.next;
				o = k.elt;
			}
			if(o.type == "OPEN") {
				throw haxe_Exception.thrown("Mismatched parentheses.");
			}
			output.push(o);
		}
		return output;
	}
	,tentativelyLower: function(s) {
		if(this.caseSensitive) {
			return s;
		} else {
			return (s == null ? "null" : "" + s).toLowerCase();
		}
	}
	,tokenize: function(str) {
		var tokens = [];
		var _g = [];
		var h = this.syntax.h;
		var x_h = h;
		var x_keys = Object.keys(h);
		var x_length = x_keys.length;
		var x_current = 0;
		while(x_current < x_length) {
			var x = x_h[x_keys[x_current++]];
			_g.push(this.tentativelyLower(x));
		}
		var keys = _g;
		var quotation = null;
		var current = "";
		var _g = 0;
		var _g1 = str.length;
		while(_g < _g1) {
			var i = _g++;
			var c = str.charAt(i);
			if(this.quotations.indexOf(c) != -1) {
				if(quotation == null) {
					quotation = c;
				} else if(quotation == c) {
					quotation = null;
				}
			}
			if(quotation != null || keys.indexOf(this.tentativelyLower(c)) == -1) {
				if(StringTools.isSpace(c,0) && quotation == null) {
					if(current.length > 0) {
						tokens.push(current);
					}
					current = "";
				} else {
					current += c;
				}
			} else {
				if(current.length > 0) {
					tokens.push(current);
				}
				current = "";
				tokens.push(c);
			}
		}
		if(StringTools.trim(current).length > 0) {
			tokens.push(StringTools.trim(current));
		}
		return tokens;
	}
	,tokenType: function(token) {
		var h = this.syntax.h;
		var key_h = h;
		var key_keys = Object.keys(h);
		var key_length = key_keys.length;
		var key_current = 0;
		while(key_current < key_length) {
			var key = key_keys[key_current++];
			if(this.tentativelyLower(token) == this.tentativelyLower(this.syntax.h[key])) {
				return new Token(key);
			}
		}
		return new Token("LITERAL",token);
	}
	,typeize: function(tokens) {
		var _g = [];
		var _g1 = 0;
		var _g2 = tokens.length;
		while(_g1 < _g2) {
			var i = _g1++;
			_g.push(this.tokenType(tokens[i]));
		}
		return _g;
	}
};
var Node = $hx_exports["Node"] = function(token) {
	this.bracketing = Node.MINIMAL_BRACKETS;
	this.token = token;
};
Node.__name__ = true;
Node.prototype = {
	set_left: function(n) {
		n.parent = this;
		return this.left = n;
	}
	,set_right: function(n) {
		n.parent = this;
		return this.right = n;
	}
	,toString: function() {
		return this.fancyString();
	}
	,fancyString: function(f) {
		return this._fancyString(this,f);
	}
	,equals: function(b) {
		if(this.token.equals(b.token)) {
			if(b == null) {
				return false;
			}
			if(this.left == null && b.left == null || this.left != null && this.left.equals(b.left)) {
				if(!(this.right == null && b.right == null)) {
					if(this.right != null) {
						return this.right.equals(b.right);
					} else {
						return false;
					}
				} else {
					return true;
				}
			} else {
				return false;
			}
		}
		return false;
	}
	,walk: function(f) {
		f(this);
		if(this.left != null) {
			this.left.walk(f);
		}
		if(this.right != null) {
			this.right.walk(f);
		}
	}
	,bracket: function(str) {
		if(this.bracketing == "MAXIMAL_BRACKETS" || this.parent != null && this.parent.token.precedence() > this.token.precedence()) {
			return "(" + str + ")";
		}
		return str;
	}
	,_fancyString: function(n,f) {
		var s = null;
		if(f != null) {
			var _g = $bind(this,this._fancyString);
			var f1 = f;
			n.f = function(n) {
				return _g(n,f1);
			};
			s = f(n);
			n.f = null;
		}
		if(s != null) {
			return s;
		}
		switch(n.token.type) {
		case "LITERAL":
			return "{" + n.token.literal + "}";
		case "NOT":
			return this.bracket("NOT " + n.right.fancyString(f));
		default:
			return this.bracket(n.left.fancyString(f) + " " + Std.string(n.token.type) + " " + n.right.fancyString(f));
		}
	}
	,check: function(a,f) {
		switch(this.token.type) {
		case "AND":
			if(this.left.check(a,f)) {
				return this.right.check(a,f);
			} else {
				return false;
			}
			break;
		case "LITERAL":
			return f(a,this.token.literal);
		case "NOT":
			return !this.right.check(a,f);
		case "OR":
			if(!this.left.check(a,f)) {
				return this.right.check(a,f);
			} else {
				return true;
			}
			break;
		case "XOR":
			var l = this.left.check(a,f);
			var r = this.right.check(a,f);
			if(!(!l && r)) {
				if(l) {
					return !r;
				} else {
					return false;
				}
			} else {
				return true;
			}
			break;
		default:
			throw haxe_Exception.thrown("Unexpected token encountered.");
		}
	}
};
var Token = $hx_exports["Token"] = function(type,literal) {
	this.type = type;
	this.literal = literal;
};
Token.__name__ = true;
Token.prototype = {
	precedence: function() {
		switch(this.type) {
		case "AND":
			return 2;
		case "LITERAL":
			return 4;
		case "NOT":
			return 3;
		case "OR":case "XOR":
			return 1;
		default:
			return 0;
		}
	}
	,equals: function(b) {
		if(this.type == b.type) {
			return this.literal == b.literal;
		} else {
			return false;
		}
	}
	,toString: function() {
		if(this.type == "LITERAL") {
			return "LITERAL(" + this.literal + ")";
		}
		return Std.string(this.type);
	}
};
var $_;
function $bind(o,m) { if( m == null ) return null; if( m.__id__ == null ) m.__id__ = $global.$haxeUID++; var f; if( o.hx__closures__ == null ) o.hx__closures__ = {}; else f = o.hx__closures__[m.__id__]; if( f == null ) { f = m.bind(o); o.hx__closures__[m.__id__] = f; } return f; }
$global.$haxeUID |= 0;
if(typeof(performance) != "undefined" ? typeof(performance.now) == "function" : false) {
	HxOverrides.now = performance.now.bind(performance);
}
String.__name__ = true;
Array.__name__ = true;
js_Boot.__toStr = ({ }).toString;
Node.MINIMAL_BRACKETS = "MINIMAL_BRACKETS";
Node.MAXIMAL_BRACKETS = "MAXIMAL_BRACKETS";
Token.AND = "AND";
Token.OR = "OR";
Token.XOR = "XOR";
Token.NOT = "NOT";
Token.OPEN = "OPEN";
Token.CLOSE = "CLOSE";
Token.LITERAL = "LITERAL";
})(typeof exports != "undefined" ? exports : typeof window != "undefined" ? window : typeof self != "undefined" ? self : this, typeof window != "undefined" ? window : typeof global != "undefined" ? global : typeof self != "undefined" ? self : this);

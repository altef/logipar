[![npm](https://img.shields.io/npm/v/logipar)](https://npmjs.com/package/logipar) [![pypi](https://img.shields.io/pypi/v/logipar)](https://pypi.org/project/logipar/) [![haxelib install logipar](https://img.shields.io/badge/haxelib-logipar-orange, "haxelib install logipar")](https://lib.haxe.org/p/logipar/) [![php phar](https://img.shields.io/badge/php-Logipar.phar-orange, "PHP Logipar.phar")](https://github.com/altef/logipar/blob/master/php/Logipar.phar)

<p align="center">
  <img src="https://altef.github.io/logipar/logo.png" alt="Logipar">
</p>

# Logipar
/lɑːdʒɪpɜːr/

*noun*: Your go-to polyglot **logic string parser**.

*verb*: Parse that logic string, my friend.


# What is a logic string parser?

Have you ever wanted to filter data based on a string of ANDs and ORs and NOTs?  Well now you can!  
In fact, that's exactly what **Logipar** is here to help you do.

If that doesn't help, check out our [Cat breed](https://altef.github.io/logipar) demo to see **Logipar** in action!  Or you can try it yourself on [Runkit](https://npm.runkit.com/logipar).

**Logipar** supports:
* AND
* OR
* NOT
* XOR
* and Parenthises
 
You can rename the operatores however you want!  You can also use it with basically whatever *literals/values* you want¹.  (1. There are *some* restrictions.)

A simple example: `one AND (two OR three)`  
A more complex example: `title=Cat XOR title contains dog`.  **Logipar** doesn't care about the literals you use, so you can add whatever complexity you is appropriate for your project there - I'm not judging!  In fact, _I endorse it_.

Logipar will automatically merge adjacent literals, _unless you don't want it to_. So `title contains dog` can count as a single literal, even without quotation marks.  Or it can count as three `title`, `contains`, and `dog` - the choice is yours! (By default it merges.)

# What can I do with it once it's parsed?

Oh, man, whatever you want really!  You can test objects against the parse tree (using a function **Logipar** returns - [see the filtering section](#filtering-data)).  You can flatten the parse tree to a string of your design (with the help of **Logipar**'s `stringify()` function - [see the stringing section](#stringification)).  
I guess.. I guess that's really all.  But come on, what more do you even want??


# Okay cool, can I use it though?
**Logipar** is written in [Haxe](https://haxe.org).  **WAIT DON'T BE SCARED**.  All that means for you is it can be (and is) compiled to multiple languages.  Maybe you've heard of one of these before:
* Javascript
* Python
* PHP

There are more, but I'm going to focus the examples on those for now.

# Installation
#### Javascript
* `npm i logipar`
* `yarn add logipar`
* Or you can just download and use [Logipar.js](https://github.com/altef/logipar/blob/master/js/logipar/Logipar.js) - say in a `<script src="Logipar.js"></scrip>` tag. 

#### Python
* `pip install logipar`
* Or you can just download and use [Logipar.py](https://github.com/altef/logipar/blob/master/python/logipar.py) in your project.

#### PHP
Bad news. **Logipar** isn't on Composer.  I probably won't bother to add it there unless someone *really* wants it, because of [this issue](https://github.com/composer/packagist/issues/472).
Good news! You can totally use this [Logipar.phar](https://github.com/altef/logipar/blob/master/php/Logipar.phar) instead.  Or, you know, donwload the files directly if you want.

#### Haxe
* `haxelib install logipar`
* Also the source files are [here](https://github.com/altef/logipar/blob/master/src/logipar).

# But how do I use it?
Great question!  Here are some examples in different languages. 
## Usage
##### Javascript
You can follow along with these javascript samples on  on [Runkit](https://npm.runkit.com/logipar).
```javascript
const logipar = require("logipar")
const lp = new logipar.Logipar();
lp.parse("a AND b");
console.log(lp.toString())
````
Or you can include [Logipar.js](https://github.com/altef/logipar/blob/master/js/Logipar.js) in your code.  Note that in this method the classes are accessed via `Logipar`, `Token`, and `Node` - rather than through a `logipar` constant, as above.
```html
<script src="Logipar.js"></script>
```
```javascript
    // Include the library however works for you.  You can see how I did it in js_sample.html
    var lp = new Logipar();
    lp.parse("a AND b");
    console.log(lp.stringify());
```
##### Python
```python
    import logipar
    lp = logipar.Logipar()
    lp.parse("a AND b")
    print(lp.toString())
```
##### Php
When you're using the PHAR, it should take care of loading the classes for you.
```php
    require_once("Logipar.phar");   
    $lp = new \logipar\Logipar();
    $lp->parse("a AND b");
    print($lp->toString());
```

## Quotations marks
While **Logipar** can automatically merge neigbouring literals, sometimes that's not enough.  It also supports quotation marks around literals.  This means you can have values that would otherwise be parsed as tokens _in_ the literals - if they're wrapped in quotation marks.  The quotation marks become part of the value, for you to deal with however you want.  This is also handy for supplying whitespace as a value.
Take the logic string `a="CAT OR DOG" OR This is a sentance.`.
`a="CAT OR DOG"` is a literal.  Even though it has and an OR in it.  You can then use it however is appropriate - split it on the equals sign maybe, and strip the quotation marks to check for the string "CAT OR DOG" in the "a" column.  I don't know, that's your journey!

`This is a sentance.` is also a literal since it'll be automatically merged by default.  **Logipar**'s `mergeAdjacentLiterals` (which defaults to `true`) controls this. So set it to false if you don't want to merge them.

So it'll parse out to: `a="CAT OR DOG"` *OR* `This is a sentance.`.

The default quotation mark characters are `"` and `'`.  But you can add to or change these through **Logipar**'s aptly-named `quotations` property - which is an array of strings denoting whatever you want to use as valid quotation mark characters.

##### Javascript
```javascript
lp.quotations.push("`"); // Add backtick 
lp.mergeAdjacentLiterals = true; // This is its default value
```
##### Python
```python
lp.quotations.append("`"); // Add backtick 
lp.mergeAdjacentLiterals = true; // This is its default value
```
##### Php
```php
$lp->quotations[] = "`"; // Add backtick
$lp->mergeAdjacentLiterals = true; // This is its default value
```

## Case sensitivity
By default the **Logipar**'s operators are case-sensitive, but they don't have to be.  Simply change the `caseSensitive` property to `false`, and YoU cAn TyPe ThEm HoWeVeR yOu Want!
##### Javascript
```javascript
lp.caseSensitive = false;
```
##### Python
```python
lp.caseSensitive = False;
```
##### Php
```php
$lp->caseSensitive = false;
```

## Custom operators
You can also replace the default strings for any or all of **logipar**'s operators.  Maybe you want to go the old `^ v` route.  Or maybe `&&` and `||`.  Or even just `+` and `*`.  I don't know what you want!
Valid operators are:
* `Token.AND` default: `AND`
* `Token.OR` default: `OR`
* `Token.XOR` default: `XOR`
* `Token.NOT` default: `NOT`
* `Token.OPEN` default: `(`
* `Token.CLOSE` default: `)`

You should probably keep the `OPEN` and `CLOSE` (parentheses) operators as single characters, unless you want to enforce whitespace between all tokens tokens. 

##### Javascript
```javascript
lp.overwrite(logipar.Token.AND, "&&");
```
##### Python
```python
lp.overwrite(logipar.logipar_Token.AND, "et")
```
##### Php
PHP is a little more problematic.  `AND`, `OR` and `XOR` are keywords in it, which makes it difficult to access those Token constants.  Rather than rename those variables, you can just use the string values. (You can do the same with the other ones, if you really want to - **all the string values are the same as the constant after `Token.`.**)
```php
$lp->overwrite("AND", "et");
```

## Stringification
Sometimes you want your logic tree flattened; pressed firmly into a string.  Maybe you just want to display it, or maybe you'd like to use it in your SQL. I don't know - and I'm not judging.  **Logipar** should provide for all your stringifying needs with it's `stringify()` function.
When you call `stringify`, you have the option of passing a function to it - this function is used to convert nodes to strings in any manner you like.  It will be called on each node in the tree.  Anything you don't account for will use the default `toString()` function provided by **Logipar**.

That's confusing right?  Well here, take a look at this function in Haxe:

```haxe
function mystringer(n:logipar.Node):String {
	if (n.token.type == logipar.Token.XOR) {
		return "((" + n.f(n.left) + " AND NOT " + n.f(n.right) + ") OR (NOT " + n.f(n.left) + " AND " + n.f(n.right) + "))";
	}
	return null;
}
```

There are some things going on there.  Definite things.  Let's start with the signature:  Your function should take a `logipar.Node` as a param, and return a `String`.  
Say whatever we're using doesn't support an `XOR` operation.  That's okay, `a XOR b` is just a fancy way of saying `(a AND NOT b) OR (NOT a AND b)`.  We can handle that.
First, we need to know if this is the type of node we want to change (`XOR`), so we check if the node passed in (`n`) is currently of type `logipar.Token.XOR`.  Then we just return the string the way we want it.  In this case:
`	return "((" + n.f(n.left) + " AND NOT " + n.f(n.right) + ") OR (NOT " + n.f(n.left) + " AND " + n.f(n.right) + "))";`

But wait.  What's going on there?   Well `XOR` nodes (and all binary nodes) have `left` and `right` properties, representing their preceeding and succeeding operands. (Unary nodes like `NOT` only have a `right` property; or rather, `left` will be null.)  So we're just saying:
`(({LEFT} AND NOT {RIGHT}) OR (NOT {LEFT} AND {RIGHT}))`

With one added wrinkle.  The `left` and `right` properties are nodes themselves.  They may contain `XOR`s of their own.  So we want to recursively call the same stringification function on them.  `f()` is a helper function available for the duration of the stringification process for this very purpose.  That's why you see `n.f(n.left)` above.

The `return null;` lets **Logipar** know it should display any other node as usual.  So in this case, anything that's not an `XOR` gets displayed as it normally would.

##### Javascript
```javascript
var str = lp.stringify(function(n) {
	if (n.token.type == logipar.Token.XOR) 
		return "((" + n.f(n.left) + " AND NOT " + n.f(n.right) + ") OR (NOT " + n.f(n.left) + " AND " + n.f(n.right) + "))";
	return null;
});
```
##### Python
```python
def expandXOR(n):
	if n.token.type == logipar.logipar_Token.XOR:
		l = n.f(n.left)
		r = n.f(n.right)
		return "(({} AND NOT {}) OR (NOT {} AND {}))".format(l, r, l, r)
	return None

flattened = lp.stringify(expandXOR)
```
##### Php
Note that again I'm just using the string "XOR" in PHP.  Also, I'm using [call_user_func](https://www.php.net/call_user_func) to call `$n->f()` on the child nodes.
```php
$flattened = $lp->stringify(function($n) {
	if ($n->token->type == "XOR") {
		$l = call_user_func($n->f, $n->left);
		$r = call_user_func($n->f, $n->right);
		return "((" . $l . " AND NOT " . $r . ") OR (NOT " . $l . " AND " . $r . "))";
	}
	return null;
});

```

The string returned will make use if minimal parentheses.  If for some reason you want everything wrapped in brackets, that's easy too:

```haxe
lp.walk(function(n:logipar.Node):Void { n.bracketing = logipar.Node.MAXIMAL_BRACKETS; });
```
That'll set the bracketing mode for each node in the tree to `MAXIMAL_BRACKETS`.  If you only want to change certain node types, you can check the value in `n.token.type` and act accordingly.

## Filtering data
Sometimes you just want to filter an array of rows. Nothing more, nothing less.  Well, maybe more.  Maybe you want to do it based on _a logic string_. 
**Logipar**'s `filterFunction` can help.  It creates a function you can use to filter your data.  But how does it work?  You handle the leaves, and we'll handle the logic tree.

> Basically, you just need to decide if a given leaf resolves `true` or `false` for a given row of data.  And then we'll figure out if it matches overall.

Here's an example in Haxe:

```haxe
var leafresolver = function(row:Dynamic, value:String):Bool {
	// This is  just checks a leaf node (value) against every column in the data (row), in a case-insensitive way.  
	// But you can get as complex as you'd like and parse the value variable however you like.
	for (f in Reflect.fields(row)) { // For each property of row
		if (Std.string(Reflect.field(row, f)).toLowerCase().indexOf(value.toLowerCase()) != -1) // If that property contains the leaf 
			return true;
	}
	return false;
}
var myfilter:(Dynamic)->Bool = ls.filterFunction(leafresolver);
```
Okay, so you can see above that **Logipar**'s `filterFunction()` takes a function as its argument, and returns a function.  The first function (`leafresolver()`) we supply, the second we use to actually do our filtering.
`leafresolver` takes a `row` of data.  This is probably an object of some sort, but that's your journey.  For the sake of our example, let's say it's `{title: "Harry potter", "author": "J.K. Rowling"}`.
It also takes a string `value`.  This is the value of the leaf we're checking.  For the sake of our example, `harry`.

The task of this function is to take `value` and see if it matches for `row`.  You can do this however you want.  This function is then run on every `LITERAL` (the leaves of the logic tree), and we use its result to decide if the logic tree resolves to `true` or `false` for `row`.

For this example, it'd check `Har` against each property in `row`: `title`, and `author`.  Since the title is `Harry Potter`, and we've specified in the function to convert to lowercase before checking, it'll match and return `true`.

`filterFunction` returns a function, whcih you can then  use on your data. For example, `myfilter(data[i])` will return `true` or `false` depending on if it matches the logic of the query.

That's still pretty confusing, but hopefully some more examples will clear it up.




##### Javascript
```javascript
function leafresolver(row, value) {
	// This is  just checks the values against every column, in a case-insensitive way
	for(var field in row)
		if (row[field].toString().toLowerCase().includes(value.toLowerCase()))
			return true;
	return false;
}
f = lp.filterFunction(leafresolver);
filtered_data = sample_data.filter(f);    // Javascript arrays have a filter function
```
##### Python
```python
def leafresolver(row, value):
	# This is  just checks the values against every column, in a case-insensitive way
	for field in row:
		if value.lower() in str(row[field]).lower():
			return True
	return False

f = lp.filterFunction(leafresolver)
data = list(filter(f, data)) # Python has a filter function too
```
##### Php
```php
$leafresolver = function($row, $value) {
	foreach($row as $field=>$v)
		if (stripos($row[$field], $value) !== false)
			return true;
	return false;
};

$f = $lp->filterFunction($leafresolver);
$data = array_filter($data, $f);    // Oh look, so does PHP
```

Now, let's try a more complex example in Haxe:

```Haxe
var f = ls.filterFunction(function(row:Dynamic, value:String):Bool {
	value = value.replace('"', ''); // Strip out the quotation marks
	if (value.indexOf(":") == -1) { // If there's no colon, just check if the value exists in any field
		for (f in Reflect.fields(row)) {
			if (Std.string(Reflect.field(row, f)).toLowerCase().indexOf(value.toLowerCase()) != -1)
				return true;
		}
	} else {
		// There was a colon.  Let's split it into field:value.
		var chunks = value.split(':');
		var field = chunks.shift(); // The field is before the first colon
		var val = chunks.join(':'); // Any subsequent colons should be part of the value we look for
		if (Reflect.hasField(row, field)) { // If that field exists, check if the value is in it
			if (Std.string(Reflect.field(row, field)).toLowerCase().indexOf(val.toLowerCase()) != -1)
				return true;
		}
	}
	return false;
});
```

What this function does is it allows for values in the format `column:value` and then checks if `value` exists in that column.  For example, a logic string we might support could be:  `title:harry and not "and"`.  This filter function will resolve true for any entries where:
1. the title column contains "harry" (case-insensitive)
2. the string "and" is not in any of the columns (case-insensitive)

**To see some more filtering examples, check out: [docs/filters.md](https://github.com/altef/logipar/blob/master/docs/filters.md).**

## The end
That's all for now.  Happy parsing!




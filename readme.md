# Logipar
/lɑːdʒɪpɜːr/
*noun*:. *Your go-to polyglot **logic string parser**.*
*verb*: parse that logic string, my friend.
 

# What is a logic string parser?

Have you ever wanted to filter data based on a string of ANDs and ORs and NOTs?  Well now you can!  
In fact, that's exactly what **Logipar** is here to help you do.
**Logipar** supports:
* AND
* OR
* NOT
* XOR
* and Parenthises
 
You can rename the operatores however you want!  You can also use it with basically whatever *literals/values* you want¹.  (1. There are *some* restrictions.)

A simple example: `one AND (two OR three)`  
A more complex example: `title=Cat XOR "title contains dog"`.  **Logipar** doesn't care about the literals you use, so you can add whatever complexity you is appropriate for your project there - I'm not judging!  In fact, _I endorse it_.

# What can I do with it once it's parsed?

Oh, man, whatever you want really!  You can test objects against the parse tree (using **Logipar**'s `filterFunction()` function - [***see the filtering section]()).  You can flatten the parse tree to a string of your design (with the help of **Logipar**'s `stringify()` function - [***see the stringing section]()).  I guess.. I guess that's really all.  But come on, what more do you even want??


# Okay cool, can I use it though?
**Logipar** is written in [Haxe](haxe.org).  **WAIT DON'T BE SCARED**.  All that means for you is it can be (and is) compiled to multiple languages.  Maybe you've heard of one of these before:
* Javascript
* Python
* PHP

There are more, but I'm going to focus the examples on those for now.

# Installation
Bad news.  Hopefully soon you'll be able to use your favourite package manager to install it, but I haven't set that up yet.  For now, if you're in a hurry, you'll have to download library files manually.  Eventually it should be more like:
* `pip install logipar`
* `yarn add logipar`
* `npn install logipar`
* `composer install logipar`
* ???

# But how do I use it?
Great question!  Here are some examples in different languages. 
## Usage
##### Javascript
You can include [logipar.js](lib/logipar.js) in your code, or *** or however you get packages in.
```javascript
    // Include the library however works for you.  You can see how I did it in js_sample.html
    var lp = new logipar.Logipar();
    lp.parse("a AND b");
    console.log(lp.stringify());
```
##### Python
```python
    import lib.logipar as logipar // it's lives in a lib directory, in this example
    lp = logipar.logipar_Logipar()
    lp.parse("a AND b")
    print(lp.stringify())
```
##### Php
*** wrap php into a PHAR
```php
    // Autoload it somehow here.  You can see how I do it in php_sample.php
    $lp = new \logipar\Logipar();
    $lp->parse("a AND b");
    print($lp->stringify());
```

## Quotations marks
**Logipar** supports quotation marks around literals.  This means you can have spaces in them - if they're wrapped in quotation marks.  The quotation marks become part of the value, for you to deal with however you want.
Take the logic string `a="CAT OR DOG" OR "This is a sentance."`.
`a="CAT OR DOG"` is a literal.  Even though it has spaces and an or in it.  You can then use it however is appropriate - split it on the equals sign maybe, and strip the quotation marks to check for the string "CAT OR DOG" in the "a" column.
`"This is a sentance."` is also a literal.
So it'll parse out to: `a="CAT OR DOG"` *OR* `"This is a sentance."`.

The default quotation mark characters are `"'`.  But you can add to or change these through **Logipar**'s aptly-named `quotations` property - which is an array of strings denoting whatever you want to use as valid quotation mark characters.

##### Javascript
```javascript
lp.quotations.push("`"); // Add backtick 
```
##### Python
```python
lp.quotations.append("`"); // Add backtick 
```
##### Php
```php
$lp->quotations[] = "`"; // Add backtick
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
PHP is a little more problematic.  `AND`, `OR` and `XOR` are keywords in it, which makes it difficult to access those Token constants.  Rather than rename those variables, you can just use the string values. (You can do the same with the other ones, if you really want to - all the string values are the same as the constant after `Token.`.)
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

With one added wrinkle.  The `left` and `right` properties are nodes themselves.  They may contain `XOR`s of their own.  So we want to recursively call the same stringification function on them.  `f()` is a helper function available for the duration of the stringification process for this purpose.  That's why you see `n.f(n.left)` above.

The `return null;` lets **Logipar** know it should display the node as usual.  So in this case, anything that's not an `XOR` gets displayed as it normally would.

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

## Filtering data
##### Javascript
```javascript
```
##### Python
```python
```
##### Php
```php
```



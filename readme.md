# Logipar
*Your go-to **logic string parser**.*

# What is a logic string parser?

Have you ever wanted to filter data based on a string of ANDs and ORs and NOTs?  Well now you can!  
In fact, that's exactly what **Logipar** is here to help with.

You can use it with basically whatever values you want¹.  You can also rename the operators however you want!
1. There are *some* restrictions.

**Logipar** supports:
* AND
* OR
* NOT
* XOR
* and Parenthises
 

A simple example: `one and (two or three)`  
A more complex example: `title=Cat xor "title contains dog"`.  **Logipar** doesn't care about the literals you use, so you can add whatever complexity you want there - I'm not judging!  In fact, _I endorse it_.

# What can I do with it once it's parsed?

Oh, man, whatever you want really!  You can test objects against the parse tree (using **Logipar**'s `filterFunction()` function - [***see the filtering section]()).  You can flatten the parse tree to a string of your design (with the help of **Logipar**'s `stringify()` function - [***see the stringing section]()).  I guess.. I guess that's really all.  But come on, what more do you even want??


# Okay cool, can I use it though?
**Logipar** is written in [Haxe](haxe.org).  **WAIT DON'T BE SCARED**.  All that means for you is it can be (and is) compiled to multiple languages.  Maybe one of these will strike your fancy:
* Javascript
* Python
* PHP

There are more, but I'm going to focus the examples on those for now.

# Installation
*** TODO
Hopefully you'll be able to use your favourite package manager to install it.  I have to test this still though.
* `pip install logipar`
* `yarn add logipar`
* `npn install logipar`
* `composer install logipar`
* ???

But for now none of that works! You'll have to manually download the library for your language if you want to use it.  *Ugh.*

# But how do I use it?
Examples in differnt languages.
## Usage
```javascript
```
```python
```
```php
```
## quotations marks
## case sensitivity
## custom syntax
You should probably leave parentheses as single character, unless you are going to require spaces between them and other tokens.
## stringify
## filterfunction



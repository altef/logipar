import sys

import math as python_lib_Math
import math as Math
import inspect as python_lib_Inspect
import sys as python_lib_Sys
import traceback as python_lib_Traceback
from io import StringIO as python_lib_io_StringIO


class Enum:
    _hx_class_name = "Enum"
    __slots__ = ("tag", "index", "params")
    _hx_fields = ["tag", "index", "params"]
    _hx_methods = ["__str__"]

    def __init__(self,tag,index,params):
        self.tag = tag
        self.index = index
        self.params = params

    def __str__(self):
        if (self.params is None):
            return self.tag
        else:
            return self.tag + '(' + (', '.join(str(v) for v in self.params)) + ')'



class Class: pass


class Std:
    _hx_class_name = "Std"
    __slots__ = ()
    _hx_statics = ["isOfType", "string"]

    @staticmethod
    def isOfType(v,t):
        if ((v is None) and ((t is None))):
            return False
        if (t is None):
            return False
        if (t == Dynamic):
            return (v is not None)
        isBool = isinstance(v,bool)
        if ((t == Bool) and isBool):
            return True
        if ((((not isBool) and (not (t == Bool))) and (t == Int)) and isinstance(v,int)):
            return True
        vIsFloat = isinstance(v,float)
        tmp = None
        tmp1 = None
        if (((not isBool) and vIsFloat) and (t == Int)):
            f = v
            tmp1 = (((f != Math.POSITIVE_INFINITY) and ((f != Math.NEGATIVE_INFINITY))) and (not python_lib_Math.isnan(f)))
        else:
            tmp1 = False
        if tmp1:
            tmp1 = None
            try:
                tmp1 = int(v)
            except BaseException as _g:
                None
                tmp1 = None
            tmp = (v == tmp1)
        else:
            tmp = False
        if ((tmp and ((v <= 2147483647))) and ((v >= -2147483648))):
            return True
        if (((not isBool) and (t == Float)) and isinstance(v,(float, int))):
            return True
        if (t == str):
            return isinstance(v,str)
        isEnumType = (t == Enum)
        if ((isEnumType and python_lib_Inspect.isclass(v)) and hasattr(v,"_hx_constructs")):
            return True
        if isEnumType:
            return False
        isClassType = (t == Class)
        if ((((isClassType and (not isinstance(v,Enum))) and python_lib_Inspect.isclass(v)) and hasattr(v,"_hx_class_name")) and (not hasattr(v,"_hx_constructs"))):
            return True
        if isClassType:
            return False
        tmp = None
        try:
            tmp = isinstance(v,t)
        except BaseException as _g:
            None
            tmp = False
        if tmp:
            return True
        if python_lib_Inspect.isclass(t):
            cls = t
            loop = None
            def _hx_local_1(intf):
                f = (intf._hx_interfaces if (hasattr(intf,"_hx_interfaces")) else [])
                if (f is not None):
                    _g = 0
                    while (_g < len(f)):
                        i = (f[_g] if _g >= 0 and _g < len(f) else None)
                        _g = (_g + 1)
                        if (i == cls):
                            return True
                        else:
                            l = loop(i)
                            if l:
                                return True
                    return False
                else:
                    return False
            loop = _hx_local_1
            currentClass = v.__class__
            result = False
            while (currentClass is not None):
                if loop(currentClass):
                    result = True
                    break
                currentClass = python_Boot.getSuperClass(currentClass)
            return result
        else:
            return False

    @staticmethod
    def string(s):
        return python_Boot.toString1(s,"")


class Float: pass


class Int: pass


class Bool: pass


class Dynamic: pass


class StringTools:
    _hx_class_name = "StringTools"
    __slots__ = ()
    _hx_statics = ["isSpace", "ltrim", "rtrim", "trim"]

    @staticmethod
    def isSpace(s,pos):
        if (((len(s) == 0) or ((pos < 0))) or ((pos >= len(s)))):
            return False
        c = HxString.charCodeAt(s,pos)
        if (not (((c > 8) and ((c < 14))))):
            return (c == 32)
        else:
            return True

    @staticmethod
    def ltrim(s):
        l = len(s)
        r = 0
        while ((r < l) and StringTools.isSpace(s,r)):
            r = (r + 1)
        if (r > 0):
            return HxString.substr(s,r,(l - r))
        else:
            return s

    @staticmethod
    def rtrim(s):
        l = len(s)
        r = 0
        while ((r < l) and StringTools.isSpace(s,((l - r) - 1))):
            r = (r + 1)
        if (r > 0):
            return HxString.substr(s,0,(l - r))
        else:
            return s

    @staticmethod
    def trim(s):
        return StringTools.ltrim(StringTools.rtrim(s))


class haxe_IMap:
    _hx_class_name = "haxe.IMap"
    __slots__ = ()
    _hx_methods = ["toString"]


class haxe_Exception(Exception):
    _hx_class_name = "haxe.Exception"
    __slots__ = ("_hx___nativeStack", "_hx___skipStack", "_hx___nativeException", "_hx___previousException")
    _hx_fields = ["__nativeStack", "__skipStack", "__nativeException", "__previousException"]
    _hx_methods = ["unwrap", "get_native"]
    _hx_statics = ["caught", "thrown"]
    _hx_interfaces = []
    _hx_super = Exception


    def __init__(self,message,previous = None,native = None):
        self._hx___previousException = None
        self._hx___nativeException = None
        self._hx___nativeStack = None
        self._hx___skipStack = 0
        super().__init__(message)
        self._hx___previousException = previous
        if ((native is not None) and Std.isOfType(native,BaseException)):
            self._hx___nativeException = native
            self._hx___nativeStack = haxe_NativeStackTrace.exceptionStack()
        else:
            self._hx___nativeException = self
            infos = python_lib_Traceback.extract_stack()
            if (len(infos) != 0):
                infos.pop()
            infos.reverse()
            self._hx___nativeStack = infos

    def unwrap(self):
        return self._hx___nativeException

    def get_native(self):
        return self._hx___nativeException

    @staticmethod
    def caught(value):
        if Std.isOfType(value,haxe_Exception):
            return value
        elif Std.isOfType(value,BaseException):
            return haxe_Exception(str(value),None,value)
        else:
            return haxe_ValueException(value,None,value)

    @staticmethod
    def thrown(value):
        if Std.isOfType(value,haxe_Exception):
            return value.get_native()
        elif Std.isOfType(value,BaseException):
            return value
        else:
            e = haxe_ValueException(value)
            e._hx___skipStack = (e._hx___skipStack + 1)
            return e



class haxe_NativeStackTrace:
    _hx_class_name = "haxe.NativeStackTrace"
    __slots__ = ()
    _hx_statics = ["saveStack", "exceptionStack"]

    @staticmethod
    def saveStack(exception):
        pass

    @staticmethod
    def exceptionStack():
        exc = python_lib_Sys.exc_info()
        if (exc[2] is not None):
            infos = python_lib_Traceback.extract_tb(exc[2])
            infos.reverse()
            return infos
        else:
            return []


class haxe_ValueException(haxe_Exception):
    _hx_class_name = "haxe.ValueException"
    __slots__ = ("value",)
    _hx_fields = ["value"]
    _hx_methods = ["unwrap"]
    _hx_statics = []
    _hx_interfaces = []
    _hx_super = haxe_Exception


    def __init__(self,value,previous = None,native = None):
        self.value = None
        super().__init__(Std.string(value),previous,native)
        self.value = value

    def unwrap(self):
        return self.value



class haxe_ds_GenericCell:
    _hx_class_name = "haxe.ds.GenericCell"
    __slots__ = ("elt", "next")
    _hx_fields = ["elt", "next"]

    def __init__(self,elt,next):
        self.elt = elt
        self.next = next



class haxe_ds_GenericStack:
    _hx_class_name = "haxe.ds.GenericStack"
    __slots__ = ("head",)
    _hx_fields = ["head"]

    def __init__(self):
        self.head = None



class haxe_ds_StringMap:
    _hx_class_name = "haxe.ds.StringMap"
    __slots__ = ("h",)
    _hx_fields = ["h"]
    _hx_methods = ["keys", "iterator", "toString"]
    _hx_interfaces = [haxe_IMap]

    def __init__(self):
        self.h = dict()

    def keys(self):
        return python_HaxeIterator(iter(self.h.keys()))

    def iterator(self):
        return python_HaxeIterator(iter(self.h.values()))

    def toString(self):
        s_b = python_lib_io_StringIO()
        s_b.write("{")
        it = self.keys()
        i = it
        while i.hasNext():
            i1 = i.next()
            s_b.write(Std.string(i1))
            s_b.write(" => ")
            s_b.write(Std.string(Std.string(self.h.get(i1,None))))
            if it.hasNext():
                s_b.write(", ")
        s_b.write("}")
        return s_b.getvalue()



class haxe_iterators_ArrayIterator:
    _hx_class_name = "haxe.iterators.ArrayIterator"
    __slots__ = ("array", "current")
    _hx_fields = ["array", "current"]
    _hx_methods = ["hasNext", "next"]

    def __init__(self,array):
        self.current = 0
        self.array = array

    def hasNext(self):
        return (self.current < len(self.array))

    def next(self):
        def _hx_local_3():
            def _hx_local_2():
                _hx_local_0 = self
                _hx_local_1 = _hx_local_0.current
                _hx_local_0.current = (_hx_local_1 + 1)
                return _hx_local_1
            return python_internal_ArrayImpl._get(self.array, _hx_local_2())
        return _hx_local_3()



class Logipar:
    _hx_class_name = "Logipar"
    __slots__ = ("quotations", "caseSensitive", "mergeAdjacentLiterals", "syntax", "tree")
    _hx_fields = ["quotations", "caseSensitive", "mergeAdjacentLiterals", "syntax", "tree"]
    _hx_methods = ["overwrite", "prepareTokens", "parse", "stringify", "walk", "filterFunction", "toString", "equals", "mergeLiterals", "treeify", "shunt", "tentativelyLower", "tokenize", "tokenType", "typeize"]

    def __init__(self):
        self.tree = None
        _g = haxe_ds_StringMap()
        _g.h["AND"] = "AND"
        _g.h["OR"] = "OR"
        _g.h["XOR"] = "XOR"
        _g.h["NOT"] = "NOT"
        _g.h["OPEN"] = "("
        _g.h["CLOSE"] = ")"
        self.syntax = _g
        self.mergeAdjacentLiterals = True
        self.caseSensitive = True
        self.quotations = ["\"", "'"]

    def overwrite(self,op,value):
        if (op in self.syntax.h):
            self.syntax.h[op] = value

    def prepareTokens(self,logic_string):
        tokens = self.tokenize(logic_string)
        types = self.typeize(tokens)
        if self.mergeAdjacentLiterals:
            types = self.mergeLiterals(types)
        return types

    def parse(self,logic_string):
        types = self.prepareTokens(logic_string)
        reversepolish = self.shunt(types)
        self.tree = self.treeify(reversepolish)
        return self.tree

    def stringify(self,f = None):
        if (self.tree is None):
            return None
        else:
            return self.tree.fancyString(f)

    def walk(self,f):
        self.tree.walk(f)

    def filterFunction(self,f):
        enclosed = self.tree
        def _hx_local_0(a):
            if (enclosed is None):
                return True
            return enclosed.check(a,f)
        return _hx_local_0

    def toString(self):
        return self.stringify()

    def equals(self,b):
        return self.tree.equals(b.tree)

    def mergeLiterals(self,tokens):
        merged = []
        _g = 0
        _g1 = len(tokens)
        while (_g < _g1):
            i = _g
            _g = (_g + 1)
            if ((tokens[i] if i >= 0 and i < len(tokens) else None).type == "LITERAL"):
                if ((i > 0) and ((python_internal_ArrayImpl._get(merged, (len(merged) - 1)).type == "LITERAL"))):
                    _hx_local_0 = python_internal_ArrayImpl._get(merged, (len(merged) - 1))
                    _hx_local_1 = _hx_local_0.literal
                    _hx_local_0.literal = (("null" if _hx_local_1 is None else _hx_local_1) + HxOverrides.stringOrNull(((" " + HxOverrides.stringOrNull((tokens[i] if i >= 0 and i < len(tokens) else None).literal)))))
                    _hx_local_0.literal
                    continue
            merged.append((tokens[i] if i >= 0 and i < len(tokens) else None))
        return merged

    def treeify(self,tokens):
        stack = haxe_ds_GenericStack()
        _g = 0
        _g1 = len(tokens)
        while (_g < _g1):
            i = _g
            _g = (_g + 1)
            token = (tokens[i] if i >= 0 and i < len(tokens) else None)
            n = Node(token)
            if (token.type != "LITERAL"):
                if (stack.head is None):
                    raise haxe_Exception.thrown((("An '" + HxOverrides.stringOrNull(self.syntax.h.get(token.type,None))) + "' is missing a value to operate on (on its right)."))
                k = stack.head
                tmp = None
                if (k is None):
                    tmp = None
                else:
                    stack.head = k.next
                    tmp = k.elt
                n.set_right(tmp)
                if (token.type != "NOT"):
                    if (stack.head is None):
                        raise haxe_Exception.thrown((("An '" + HxOverrides.stringOrNull(self.syntax.h.get(token.type,None))) + "' is missing a value to operate on (on its left)."))
                    k1 = stack.head
                    tmp1 = None
                    if (k1 is None):
                        tmp1 = None
                    else:
                        stack.head = k1.next
                        tmp1 = k1.elt
                    n.set_left(tmp1)
            stack.head = haxe_ds_GenericCell(n,stack.head)
        k = stack.head
        parsetree = None
        if (k is None):
            parsetree = None
        else:
            stack.head = k.next
            parsetree = k.elt
        if (stack.head is not None):
            raise haxe_Exception.thrown("Invalid logic string.  Do you have parentheses in your literals?")
        return parsetree

    def shunt(self,tokens):
        output = list()
        operators = haxe_ds_GenericStack()
        _g = 0
        _g1 = len(tokens)
        while (_g < _g1):
            i = _g
            _g = (_g + 1)
            token = (tokens[i] if i >= 0 and i < len(tokens) else None)
            _g2 = token.type
            _hx_local_0 = len(_g2)
            if (_hx_local_0 == 5):
                if (_g2 == "CLOSE"):
                    while True:
                        k = operators.head
                        op = None
                        if (k is None):
                            op = None
                        else:
                            operators.head = k.next
                            op = k.elt
                        if (op.type == "OPEN"):
                            break
                        if (operators.head is None):
                            raise haxe_Exception.thrown("Mismatched parentheses.")
                        output.append(op)
                else:
                    while (operators.head is not None):
                        prev = (None if ((operators.head is None)) else operators.head.elt)
                        if (prev.type == "OPEN"):
                            break
                        if (prev.precedence() <= token.precedence()):
                            break
                        k1 = operators.head
                        x = None
                        if (k1 is None):
                            x = None
                        else:
                            operators.head = k1.next
                            x = k1.elt
                        output.append(x)
                    operators.head = haxe_ds_GenericCell(token,operators.head)
            elif (_hx_local_0 == 4):
                if (_g2 == "OPEN"):
                    operators.head = haxe_ds_GenericCell(token,operators.head)
                else:
                    while (operators.head is not None):
                        prev = (None if ((operators.head is None)) else operators.head.elt)
                        if (prev.type == "OPEN"):
                            break
                        if (prev.precedence() <= token.precedence()):
                            break
                        k1 = operators.head
                        x = None
                        if (k1 is None):
                            x = None
                        else:
                            operators.head = k1.next
                            x = k1.elt
                        output.append(x)
                    operators.head = haxe_ds_GenericCell(token,operators.head)
            elif (_hx_local_0 == 7):
                if (_g2 == "LITERAL"):
                    output.append(token)
                else:
                    while (operators.head is not None):
                        prev = (None if ((operators.head is None)) else operators.head.elt)
                        if (prev.type == "OPEN"):
                            break
                        if (prev.precedence() <= token.precedence()):
                            break
                        k1 = operators.head
                        x = None
                        if (k1 is None):
                            x = None
                        else:
                            operators.head = k1.next
                            x = k1.elt
                        output.append(x)
                    operators.head = haxe_ds_GenericCell(token,operators.head)
            else:
                while (operators.head is not None):
                    prev = (None if ((operators.head is None)) else operators.head.elt)
                    if (prev.type == "OPEN"):
                        break
                    if (prev.precedence() <= token.precedence()):
                        break
                    k1 = operators.head
                    x = None
                    if (k1 is None):
                        x = None
                    else:
                        operators.head = k1.next
                        x = k1.elt
                    output.append(x)
                operators.head = haxe_ds_GenericCell(token,operators.head)
        while (operators.head is not None):
            k = operators.head
            o = None
            if (k is None):
                o = None
            else:
                operators.head = k.next
                o = k.elt
            if (o.type == "OPEN"):
                raise haxe_Exception.thrown("Mismatched parentheses.")
            output.append(o)
        return output

    def tentativelyLower(self,s):
        if self.caseSensitive:
            return s
        else:
            return Std.string(s).lower()

    def tokenize(self,_hx_str):
        tokens = []
        _g = []
        x = self.syntax.iterator()
        while x.hasNext():
            x1 = x.next()
            x2 = self.tentativelyLower(x1)
            _g.append(x2)
        keys = _g
        quotation = None
        current = ""
        _g = 0
        _g1 = len(_hx_str)
        while (_g < _g1):
            i = _g
            _g = (_g + 1)
            c = ("" if (((i < 0) or ((i >= len(_hx_str))))) else _hx_str[i])
            if (python_internal_ArrayImpl.indexOf(self.quotations,c,None) != -1):
                if (quotation is None):
                    quotation = c
                elif (quotation == c):
                    quotation = None
            if ((quotation is not None) or ((python_internal_ArrayImpl.indexOf(keys,self.tentativelyLower(c),None) == -1))):
                if (StringTools.isSpace(c,0) and ((quotation is None))):
                    if (len(current) > 0):
                        tokens.append(current)
                    current = ""
                else:
                    current = (("null" if current is None else current) + ("null" if c is None else c))
            else:
                if (len(current) > 0):
                    tokens.append(current)
                current = ""
                tokens.append(c)
        if (len(StringTools.trim(current)) > 0):
            x = StringTools.trim(current)
            tokens.append(x)
        return tokens

    def tokenType(self,token):
        key = self.syntax.keys()
        while key.hasNext():
            key1 = key.next()
            if (self.tentativelyLower(token) == self.tentativelyLower(self.syntax.h.get(key1,None))):
                return Token(key1)
        return Token("LITERAL",token)

    def typeize(self,tokens):
        _g = []
        _g1 = 0
        _g2 = len(tokens)
        while (_g1 < _g2):
            i = _g1
            _g1 = (_g1 + 1)
            x = self.tokenType((tokens[i] if i >= 0 and i < len(tokens) else None))
            _g.append(x)
        return _g



class Node:
    _hx_class_name = "Node"
    __slots__ = ("token", "left", "right", "parent", "bracketing", "f")
    _hx_fields = ["token", "left", "right", "parent", "bracketing", "f"]
    _hx_methods = ["set_left", "set_right", "toString", "fancyString", "equals", "walk", "bracket", "_fancyString", "check"]
    _hx_statics = ["MINIMAL_BRACKETS", "MAXIMAL_BRACKETS"]

    def __init__(self,token):
        self.f = None
        self.parent = None
        self.right = None
        self.left = None
        self.bracketing = Node.MINIMAL_BRACKETS
        self.token = token

    def set_left(self,n):
        n.parent = self
        def _hx_local_1():
            def _hx_local_0():
                self.left = n
                return self.left
            return _hx_local_0()
        return _hx_local_1()

    def set_right(self,n):
        n.parent = self
        def _hx_local_1():
            def _hx_local_0():
                self.right = n
                return self.right
            return _hx_local_0()
        return _hx_local_1()

    def toString(self):
        return self.fancyString()

    def fancyString(self,f = None):
        return self._fancyString(self,f)

    def equals(self,b):
        if self.token.equals(b.token):
            if (b is None):
                return False
            if (((self.left is None) and ((b.left is None))) or (((self.left is not None) and self.left.equals(b.left)))):
                if (not (((self.right is None) and ((b.right is None))))):
                    if (self.right is not None):
                        return self.right.equals(b.right)
                    else:
                        return False
                else:
                    return True
            else:
                return False
        return False

    def walk(self,f):
        f(self)
        if (self.left is not None):
            self.left.walk(f)
        if (self.right is not None):
            self.right.walk(f)

    def bracket(self,_hx_str):
        if ((self.bracketing == "MAXIMAL_BRACKETS") or (((self.parent is not None) and ((self.parent.token.precedence() > self.token.precedence()))))):
            return (("(" + ("null" if _hx_str is None else _hx_str)) + ")")
        return _hx_str

    def _fancyString(self,n,f = None):
        s = None
        if (f is not None):
            _g = self._fancyString
            f1 = f
            def _hx_local_0(n):
                return _g(n,f1)
            n.f = _hx_local_0
            s = f(n)
            n.f = None
        if (s is not None):
            return s
        _g1 = n.token.type
        _hx_local_1 = len(_g1)
        if (_hx_local_1 == 7):
            if (_g1 == "LITERAL"):
                return (("{" + HxOverrides.stringOrNull(n.token.literal)) + "}")
            else:
                return self.bracket(((((HxOverrides.stringOrNull(n.left.fancyString(f)) + " ") + Std.string(n.token.type)) + " ") + HxOverrides.stringOrNull(n.right.fancyString(f))))
        elif (_hx_local_1 == 3):
            if (_g1 == "NOT"):
                return self.bracket(("NOT " + HxOverrides.stringOrNull(n.right.fancyString(f))))
            else:
                return self.bracket(((((HxOverrides.stringOrNull(n.left.fancyString(f)) + " ") + Std.string(n.token.type)) + " ") + HxOverrides.stringOrNull(n.right.fancyString(f))))
        else:
            return self.bracket(((((HxOverrides.stringOrNull(n.left.fancyString(f)) + " ") + Std.string(n.token.type)) + " ") + HxOverrides.stringOrNull(n.right.fancyString(f))))

    def check(self,a,f):
        _g = self.token.type
        _hx_local_0 = len(_g)
        if (_hx_local_0 == 3):
            if (_g == "AND"):
                if self.left.check(a,f):
                    return self.right.check(a,f)
                else:
                    return False
            elif (_g == "NOT"):
                return (not self.right.check(a,f))
            elif (_g == "XOR"):
                l = self.left.check(a,f)
                r = self.right.check(a,f)
                if (not (((not l) and r))):
                    if l:
                        return (not r)
                    else:
                        return False
                else:
                    return True
            else:
                raise haxe_Exception.thrown("Unexpected token encountered.")
        elif (_hx_local_0 == 7):
            if (_g == "LITERAL"):
                return f(a,self.token.literal)
            else:
                raise haxe_Exception.thrown("Unexpected token encountered.")
        elif (_hx_local_0 == 2):
            if (_g == "OR"):
                if (not self.left.check(a,f)):
                    return self.right.check(a,f)
                else:
                    return True
            else:
                raise haxe_Exception.thrown("Unexpected token encountered.")
        else:
            raise haxe_Exception.thrown("Unexpected token encountered.")



class Token:
    _hx_class_name = "Token"
    __slots__ = ("type", "literal")
    _hx_fields = ["type", "literal"]
    _hx_methods = ["precedence", "equals", "toString"]
    _hx_statics = ["AND", "OR", "XOR", "NOT", "OPEN", "CLOSE", "LITERAL"]

    def __init__(self,_hx_type,literal = None):
        self.type = _hx_type
        self.literal = literal

    def precedence(self):
        _g = self.type
        _hx_local_0 = len(_g)
        if (_hx_local_0 == 3):
            if (_g == "AND"):
                return 2
            elif (_g == "NOT"):
                return 3
            elif (_g == "XOR"):
                return 1
            else:
                return 0
        elif (_hx_local_0 == 7):
            if (_g == "LITERAL"):
                return 4
            else:
                return 0
        elif (_hx_local_0 == 2):
            if (_g == "OR"):
                return 1
            else:
                return 0
        else:
            return 0

    def equals(self,b):
        if (self.type == b.type):
            return (self.literal == b.literal)
        else:
            return False

    def toString(self):
        if (self.type == "LITERAL"):
            return (("LITERAL(" + HxOverrides.stringOrNull(self.literal)) + ")")
        return Std.string(self.type)



class python_Boot:
    _hx_class_name = "python.Boot"
    __slots__ = ()
    _hx_statics = ["keywords", "toString1", "fields", "simpleField", "getInstanceFields", "getSuperClass", "getClassFields", "prefixLength", "unhandleKeywords"]

    @staticmethod
    def toString1(o,s):
        if (o is None):
            return "null"
        if isinstance(o,str):
            return o
        if (s is None):
            s = ""
        if (len(s) >= 5):
            return "<...>"
        if isinstance(o,bool):
            if o:
                return "true"
            else:
                return "false"
        if (isinstance(o,int) and (not isinstance(o,bool))):
            return str(o)
        if isinstance(o,float):
            try:
                if (o == int(o)):
                    return str(Math.floor((o + 0.5)))
                else:
                    return str(o)
            except BaseException as _g:
                None
                return str(o)
        if isinstance(o,list):
            o1 = o
            l = len(o1)
            st = "["
            s = (("null" if s is None else s) + "\t")
            _g = 0
            _g1 = l
            while (_g < _g1):
                i = _g
                _g = (_g + 1)
                prefix = ""
                if (i > 0):
                    prefix = ","
                st = (("null" if st is None else st) + HxOverrides.stringOrNull(((("null" if prefix is None else prefix) + HxOverrides.stringOrNull(python_Boot.toString1((o1[i] if i >= 0 and i < len(o1) else None),s))))))
            st = (("null" if st is None else st) + "]")
            return st
        try:
            if hasattr(o,"toString"):
                return o.toString()
        except BaseException as _g:
            None
        if hasattr(o,"__class__"):
            if isinstance(o,_hx_AnonObject):
                toStr = None
                try:
                    fields = python_Boot.fields(o)
                    _g = []
                    _g1 = 0
                    while (_g1 < len(fields)):
                        f = (fields[_g1] if _g1 >= 0 and _g1 < len(fields) else None)
                        _g1 = (_g1 + 1)
                        x = ((("" + ("null" if f is None else f)) + " : ") + HxOverrides.stringOrNull(python_Boot.toString1(python_Boot.simpleField(o,f),(("null" if s is None else s) + "\t"))))
                        _g.append(x)
                    fieldsStr = _g
                    toStr = (("{ " + HxOverrides.stringOrNull(", ".join([x1 for x1 in fieldsStr]))) + " }")
                except BaseException as _g:
                    None
                    return "{ ... }"
                if (toStr is None):
                    return "{ ... }"
                else:
                    return toStr
            if isinstance(o,Enum):
                o1 = o
                l = len(o1.params)
                hasParams = (l > 0)
                if hasParams:
                    paramsStr = ""
                    _g = 0
                    _g1 = l
                    while (_g < _g1):
                        i = _g
                        _g = (_g + 1)
                        prefix = ""
                        if (i > 0):
                            prefix = ","
                        paramsStr = (("null" if paramsStr is None else paramsStr) + HxOverrides.stringOrNull(((("null" if prefix is None else prefix) + HxOverrides.stringOrNull(python_Boot.toString1(o1.params[i],s))))))
                    return (((HxOverrides.stringOrNull(o1.tag) + "(") + ("null" if paramsStr is None else paramsStr)) + ")")
                else:
                    return o1.tag
            if hasattr(o,"_hx_class_name"):
                if (o.__class__.__name__ != "type"):
                    fields = python_Boot.getInstanceFields(o)
                    _g = []
                    _g1 = 0
                    while (_g1 < len(fields)):
                        f = (fields[_g1] if _g1 >= 0 and _g1 < len(fields) else None)
                        _g1 = (_g1 + 1)
                        x = ((("" + ("null" if f is None else f)) + " : ") + HxOverrides.stringOrNull(python_Boot.toString1(python_Boot.simpleField(o,f),(("null" if s is None else s) + "\t"))))
                        _g.append(x)
                    fieldsStr = _g
                    toStr = (((HxOverrides.stringOrNull(o._hx_class_name) + "( ") + HxOverrides.stringOrNull(", ".join([x1 for x1 in fieldsStr]))) + " )")
                    return toStr
                else:
                    fields = python_Boot.getClassFields(o)
                    _g = []
                    _g1 = 0
                    while (_g1 < len(fields)):
                        f = (fields[_g1] if _g1 >= 0 and _g1 < len(fields) else None)
                        _g1 = (_g1 + 1)
                        x = ((("" + ("null" if f is None else f)) + " : ") + HxOverrides.stringOrNull(python_Boot.toString1(python_Boot.simpleField(o,f),(("null" if s is None else s) + "\t"))))
                        _g.append(x)
                    fieldsStr = _g
                    toStr = (((("#" + HxOverrides.stringOrNull(o._hx_class_name)) + "( ") + HxOverrides.stringOrNull(", ".join([x1 for x1 in fieldsStr]))) + " )")
                    return toStr
            if (o == str):
                return "#String"
            if (o == list):
                return "#Array"
            if callable(o):
                return "function"
            try:
                if hasattr(o,"__repr__"):
                    return o.__repr__()
            except BaseException as _g:
                None
            if hasattr(o,"__str__"):
                return o.__str__([])
            if hasattr(o,"__name__"):
                return o.__name__
            return "???"
        else:
            return str(o)

    @staticmethod
    def fields(o):
        a = []
        if (o is not None):
            if hasattr(o,"_hx_fields"):
                fields = o._hx_fields
                if (fields is not None):
                    return list(fields)
            if isinstance(o,_hx_AnonObject):
                d = o.__dict__
                keys = d.keys()
                handler = python_Boot.unhandleKeywords
                for k in keys:
                    if (k != '_hx_disable_getattr'):
                        a.append(handler(k))
            elif hasattr(o,"__dict__"):
                d = o.__dict__
                keys1 = d.keys()
                for k in keys1:
                    a.append(k)
        return a

    @staticmethod
    def simpleField(o,field):
        if (field is None):
            return None
        field1 = (("_hx_" + field) if ((field in python_Boot.keywords)) else (("_hx_" + field) if (((((len(field) > 2) and ((ord(field[0]) == 95))) and ((ord(field[1]) == 95))) and ((ord(field[(len(field) - 1)]) != 95)))) else field))
        if hasattr(o,field1):
            return getattr(o,field1)
        else:
            return None

    @staticmethod
    def getInstanceFields(c):
        f = (list(c._hx_fields) if (hasattr(c,"_hx_fields")) else [])
        if hasattr(c,"_hx_methods"):
            f = (f + c._hx_methods)
        sc = python_Boot.getSuperClass(c)
        if (sc is None):
            return f
        else:
            scArr = python_Boot.getInstanceFields(sc)
            scMap = set(scArr)
            _g = 0
            while (_g < len(f)):
                f1 = (f[_g] if _g >= 0 and _g < len(f) else None)
                _g = (_g + 1)
                if (not (f1 in scMap)):
                    scArr.append(f1)
            return scArr

    @staticmethod
    def getSuperClass(c):
        if (c is None):
            return None
        try:
            if hasattr(c,"_hx_super"):
                return c._hx_super
            return None
        except BaseException as _g:
            None
        return None

    @staticmethod
    def getClassFields(c):
        if hasattr(c,"_hx_statics"):
            x = c._hx_statics
            return list(x)
        else:
            return []

    @staticmethod
    def unhandleKeywords(name):
        if (HxString.substr(name,0,python_Boot.prefixLength) == "_hx_"):
            real = HxString.substr(name,python_Boot.prefixLength,None)
            if (real in python_Boot.keywords):
                return real
        return name


class python_HaxeIterator:
    _hx_class_name = "python.HaxeIterator"
    __slots__ = ("it", "x", "has", "checked")
    _hx_fields = ["it", "x", "has", "checked"]
    _hx_methods = ["next", "hasNext"]

    def __init__(self,it):
        self.checked = False
        self.has = False
        self.x = None
        self.it = it

    def next(self):
        if (not self.checked):
            self.hasNext()
        self.checked = False
        return self.x

    def hasNext(self):
        if (not self.checked):
            try:
                self.x = self.it.__next__()
                self.has = True
            except BaseException as _g:
                None
                if Std.isOfType(haxe_Exception.caught(_g).unwrap(),StopIteration):
                    self.has = False
                    self.x = None
                else:
                    raise _g
            self.checked = True
        return self.has



class _hx_AnonObject:
    _hx_class_name = "_hx_AnonObject"
    __slots__ = ()


class python_internal_ArrayImpl:
    _hx_class_name = "python.internal.ArrayImpl"
    __slots__ = ()
    _hx_statics = ["indexOf", "_get"]

    @staticmethod
    def indexOf(a,x,fromIndex = None):
        _hx_len = len(a)
        l = (0 if ((fromIndex is None)) else ((_hx_len + fromIndex) if ((fromIndex < 0)) else fromIndex))
        if (l < 0):
            l = 0
        _g = l
        _g1 = _hx_len
        while (_g < _g1):
            i = _g
            _g = (_g + 1)
            if HxOverrides.eq(a[i],x):
                return i
        return -1

    @staticmethod
    def _get(x,idx):
        if ((idx > -1) and ((idx < len(x)))):
            return x[idx]
        else:
            return None


class HxOverrides:
    _hx_class_name = "HxOverrides"
    __slots__ = ()
    _hx_statics = ["eq", "stringOrNull"]

    @staticmethod
    def eq(a,b):
        if (isinstance(a,list) or isinstance(b,list)):
            return a is b
        return (a == b)

    @staticmethod
    def stringOrNull(s):
        if (s is None):
            return "null"
        else:
            return s


class python_internal_MethodClosure:
    _hx_class_name = "python.internal.MethodClosure"
    __slots__ = ("obj", "func")
    _hx_fields = ["obj", "func"]
    _hx_methods = ["__call__"]

    def __init__(self,obj,func):
        self.obj = obj
        self.func = func

    def __call__(self,*args):
        return self.func(self.obj,*args)



class HxString:
    _hx_class_name = "HxString"
    __slots__ = ()
    _hx_statics = ["charCodeAt", "substr"]

    @staticmethod
    def charCodeAt(s,index):
        if ((((s is None) or ((len(s) == 0))) or ((index < 0))) or ((index >= len(s)))):
            return None
        else:
            return ord(s[index])

    @staticmethod
    def substr(s,startIndex,_hx_len = None):
        if (_hx_len is None):
            return s[startIndex:]
        else:
            if (_hx_len == 0):
                return ""
            if (startIndex < 0):
                startIndex = (len(s) + startIndex)
                if (startIndex < 0):
                    startIndex = 0
            return s[startIndex:(startIndex + _hx_len)]

Math.NEGATIVE_INFINITY = float("-inf")
Math.POSITIVE_INFINITY = float("inf")
Math.NaN = float("nan")
Math.PI = python_lib_Math.pi

Node.MINIMAL_BRACKETS = "MINIMAL_BRACKETS"
Node.MAXIMAL_BRACKETS = "MAXIMAL_BRACKETS"
Token.AND = "AND"
Token.OR = "OR"
Token.XOR = "XOR"
Token.NOT = "NOT"
Token.OPEN = "OPEN"
Token.CLOSE = "CLOSE"
Token.LITERAL = "LITERAL"
python_Boot.keywords = set(["and", "del", "from", "not", "with", "as", "elif", "global", "or", "yield", "assert", "else", "if", "pass", "None", "break", "except", "import", "raise", "True", "class", "exec", "in", "return", "False", "continue", "finally", "is", "try", "def", "for", "lambda", "while"])
python_Boot.prefixLength = len("_hx_")
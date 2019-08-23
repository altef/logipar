# More filter examples

Here are some more examples!  They're all in Javascript because I was using [Runkit](https://npm.runkit.com/logipar) to write them.


#### Check if the value is in any column

```javascript
const logipar = require("logipar") 


let data = [{'value': 1}, {'value': 2}, {'value': 3}] 
var logic = '1 OR 3'    
var lp = new logipar.Logipar()
lp.parse(logic)

// A leaf resolver which just checks to make sure value is contained in one of the object's properties
function inAnyColumn(row, value) {
    for(var field in row)
        if (row[field].toString().toLowerCase().includes(value.toLowerCase()))
            return true
    return false
}

// Generate the filter function
var customFilter = lp.filterFunction(inAnyColumn) 
// Filter the array
data.filter(customFilter) 
```


#### Look in specific columns
We'll use a value format of `column name`:`value` and strip off `"` in case either have spaces or use reserved words.
```javascript
let data = [{'value': 1}, {'value': 2}, {'value': 3}] 
var logic = 'value:1 OR fakecolumn:3'
var lp = new logipar.Logipar()
lp.parse(logic)


function isInASpecificColumn(row, value) {
    // Strip out the quotation marks
    value = value.replace(/\"/g, '')
    
    if (value.indexOf(":") == -1)
        throw "Invalid value.  It should be in the format column:value"
    
    const chunks = value.split(':')
    // The field is before the first colon
    let field = chunks.shift()
    // Any subsequent colons should be part of the value we look for
    let val = chunks.join(':')
    // Case sensitive
    if (row.hasOwnProperty(field)) 
        return (row[field] + "").includes("" + val)
    return false
}

// Generate the filter function
var customFilter = lp.filterFunction(isInASpecificColumn) 
// Filter the array
data.filter(customFilter) 
```

#### Relational operators
We'll support a value format of `column name``[operator]``value` and allow the operators: `>`, `<`, `=`.  I'm not going to bother with `>=` and `!=` because those can be done in conjunction with **Logipar**'s NOT operator.
Note: In this example's logic string, I've wrapped some values in quotation marks.  This allows me to put spaces in them.

```javascript
const logipar = require("logipar") 


let data = [{'value': 0}, {'value': 1}, {'value': 2}, {'value': 3}] 
var logic = '"value < 1" OR "value > 2" OR value=1'
var lp = new logipar.Logipar()
lp.parse(logic)


function relationalOperatorLeafResolver(row, value) {
    // Strip out the quotation marks
    value = value.replace(/\"/g, '')
    
    
    // Check to make sure the value supplied is in the correct format
    const operators = ['=', '<', '>']
    let operator = null
    for(let i=0; i < operators.length; i++)
        if (value.indexOf(operators[i]) !== -1) {
            operator = operators[i]
            break
        }
    if (operator === null)
        throw "Invalid value. It should be [column][operator][value].  Valid operators are: >, <, and =." 
            
    const chunks = value.split(operator)
    // The field is before the first operator
    let field = chunks.shift().trim()
    // Any subsequent operators should be part of the value we look for
    let val = chunks.join(operator).trim()
    if (row.hasOwnProperty(field)) {
        switch(operator) {
            case '=':
                return row[field] == val
            case '>':
                return row[field] > val
            case '<':
                return row[field] < val
        }
    }
    return false
}

// Generate the filter function
var customFilter = lp.filterFunction(relationalOperatorLeafResolver) 
// Filter the array
data.filter(customFilter) 
```


### Relational operators on an Array
This is like the previous example, but without the column name.

```javascript

const logipar = require("logipar") 


let data = [0,1,2,3,4,5,6] 
var logic = '"x < 1" OR "x > 5" OR x=1'
var lp = new logipar.Logipar()
lp.parse(logic)

function relationalOperatorsOnArray(row, value) {
    // Strip out the quotation marks
    value = value.replace(/\"/g, '')
    
    
    // Check to make sure the value supplied is in the correct format
    const operators = ['=', '<', '>']
    let operator = null
    for(let i=0; i < operators.length; i++)
        if (value.indexOf(operators[i]) !== -1) {
            operator = operators[i]
            break
        }
    if (operator === null)
        throw "Invalid value. It should be [whatever][operator][value].  Valid operators are: >, <, and =." 
            
    const chunks = value.split(operator)
    // We don't really care about what is before the first operator
    let junk = chunks.shift().trim()
    // Any subsequent operators should be part of the value we look for
    let val = chunks.join(operator).trim()
    switch(operator) {
        case '=':
            return row == val
        case '>':
            return row > val
        case '<':
            return row < val
    }
    return false
}

// Generate the filter function
var customFilter = lp.filterFunction(relationalOperatorsOnArray) 
// Filter the array
data.filter(customFilter) 
```
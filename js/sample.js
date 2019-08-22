const logipar = require('./logipar/Logipar.js');
const cats = require("./cats.js");

const readline = require('readline').createInterface({
	input: process.stdin,
	output: process.stdout
})

console.log("-- Welcome to the cat library --")
let s = ""
readline.question("Please enter an input string: ", (str) => {
	s = str
	readline.close()
		
	lp = new logipar.Logipar()
	lp.overwrite(logipar.Token.AND, "et")
	lp.caseSensitive = false
	lp.parse(s)


	function expandXOR(n) {
		if (n.token.type === logipar.Token.XOR) {
			l = n.f(n.left)
			r = n.f(n.right)
			return "((" + l + " AND NOT " + r + ") OR (NOT " + l + " AND " + r + "))"
		}
		return null
	}

	const flattened = lp.stringify(expandXOR)

	console.log("\nOkay, it looks like you're looking for:", flattened, "\n\n")


	function fancyFilter(row, value) {
		value = value.replace('"', '')
		if (!value.includes(":")) { 
			// This is a dumb one that just checks the values against every column
			for(let field in row) {
				if (("" + row[field]).toLowerCase().includes(value.toLowerCase()))
					return true
			}
		} else {
			// We're specifying a specific field we want to look in
			let chunks = value.split(':');
			const field = chunks.shift();
			let val = ':'.join(chunks)
			if (row.hasOwnProperty(field))
				if (("" + row[field]).toLowerCase().includes(val.lower()))
					return true
		}
		return false
	}

	const f = lp.filterFunction(fancyFilter)


	data = cats.filter(f)
	if (data.length == 0)
		console.log("No matching entries found.")
		
	for(let i=0; i < data.length; i++)
		console.log(data[i]['Breed'])

	console.log("\nFound", data.length, "entries.")	
	
})



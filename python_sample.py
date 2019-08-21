import lib.logipar as logipar
import json

print(dir(logipar))

with open('sample_data.json') as json_file:
	data = json.load(json_file)

print("-- Welcome to the book library --")
s = input("Please enter an input string: ")

l = logipar.logipar_Logipar()
l.overwrite(logipar.logipar_Token.AND, "et")
l.caseSensitive = False
l.parse(s)


def expandXOR(n):
	if n.token.type == logipar.logipar_Token.XOR:
		l = n.f(n.left)
		r = n.f(n.right)
		return "(({} AND NOT {}) OR (NOT {} AND {}))".format(l, r, l, r)
	return None

flattened = l.stringify(expandXOR)

print("\nOkay, it looks like you're looking for: {}\n\n".format(flattened))


def fancyFilter(row, value):
	value = value.replace('"', '')
	if ":" not in value: 
		# This is a dumb one that just checks the values against every column
		for field in row:
			if value.lower() in str(row[field]).lower():
				return True
	else:
		# We're specifying a specific field we want to look in
		chunks = value.split(':');
		field = chunks[0];
		val = ':'.join(chunks[1:])
		if field in row:
			if val.lower() in str(row[field]).lower():
				return True
	return False

f = l.filterFunction(fancyFilter)

data = list(filter(f, data))
if len(data) == 0:
	print("No matching entries found.")
	
for book in data:
	print("{} by {}".format(book['title'], book['authors']))

print("Found {} entries.".format(len(data)))
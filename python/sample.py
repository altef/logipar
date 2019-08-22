import logipar
import json, os


with open(os.path.dirname(os.path.realpath(__file__)) + os.sep + 'cats.json') as json_file:
	data = json.load(json_file)

print("-- Welcome to the cat library --")
s = input("Please enter an input string: ")

l = logipar.Logipar()
l.overwrite(logipar.Token.AND, "et")
l.caseSensitive = False
l.parse(s)


def expandXOR(n):
	if n.token.type == logipar.Token.XOR:
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
	
for d in data:
	print("{}".format(d['Breed']))

print("\nFound {} entries.".format(len(data)))
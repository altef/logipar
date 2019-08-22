import logipar

lp = logipar.Logipar();
lp.parse("a AND b");
print(lp.stringify())

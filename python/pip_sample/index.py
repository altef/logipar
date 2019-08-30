import logipar

lp = logipar.Logipar();
lp.parse("NOT a AND b");
print(lp.stringify())

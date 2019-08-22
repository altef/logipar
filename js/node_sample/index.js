const logipar = require("logipar")

const lp = new logipar.Logipar();
lp.parse("a AND b");
console.log(lp.stringify())

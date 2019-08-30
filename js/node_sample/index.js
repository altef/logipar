const logipar = require("logipar")

const lp = new logipar.Logipar();
lp.parse("NOT a AND b");
console.log(lp.stringify())

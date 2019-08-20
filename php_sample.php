<?php

# Haxe requires the mbstring extension for mb_internal_encoding
spl_autoload_register(function ($name) {
	require_once("lib/php/lib/".str_replace('\\', '/', $name).".php");
});


$data = file_get_contents('sample_data.json');
$data = json_decode($data, true);

print("-- Welcome to the book library --\n");
$s = readline("Please enter an input string: ");

$l = new \logipar\Logipar();
$l->caseSensitive = False;
$l->parse($s);
print("\nOkay, it looks like you're looking for: ".$l->stringify()."\n\n");


$fancyFilter = function($row, $value) {
	$value = str_replace('"', '', $value);
	if (strpos($value, ":") === false) {
		foreach($row as $field=>$v)
			if (stripos($row[$field], $value) !== false)
				return true;
	} else {
		# We're specifying a specific field we want to look in
		$chunks = explode(":", $value);
		$field = array_shift($chunks);
		$val = implode(':', $chunks);
		if (array_key_exists($field, $row)) {
			if (stripos($row[$field], $val) !== false)
				return true;
		}
	}
	return false;
};

$f = $l->filterFunction($fancyFilter);

$data = array_filter($data, $f);
if (count($data) == 0)
	print("No matching entries found.");

foreach($data as $k=>$book)
	print($book['title'] . " by " . $book['authors']."\n");
print("Found ".count($data)." entries.");

?>
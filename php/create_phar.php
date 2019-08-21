<?php
# This requires that phar.readonly be set to 0 or Off in php.ini

chdir(dirname(__FILE__));
$phar = 'Logipar.phar';

@unlink($phar);

$p = new Phar($phar);
$p->buildFromDirectory('lib');
$p->addFile('stub.php');
$p->setDefaultStub('stub.php', 'stub.php');

// deltree('phar-contents');
// $p->extractTo('phar-contents');


function deltree($dir) { 
	$files = array_diff(scandir($dir), array('.','..')); 
	foreach ($files as $file) { 
		(is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file"); 
	} 
	return rmdir($dir); 
} 
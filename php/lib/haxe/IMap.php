<?php
/**
 */

namespace haxe;

use \php\Boot;

interface IMap {
	/**
	 * @return string
	 */
	public function toString () ;
}

Boot::registerClass(IMap::class, 'haxe.IMap');

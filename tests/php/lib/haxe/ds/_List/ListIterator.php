<?php
/**
 * Generated by Haxe 4.0.0-rc.2+77068e10c
 */

namespace haxe\ds\_List;

use \php\Boot;

class ListIterator {
	/**
	 * @var ListNode
	 */
	public $head;

	/**
	 * @param ListNode $head
	 * 
	 * @return void
	 */
	public function __construct ($head) {
		#C:\HaxeToolkit\haxe\std/haxe/ds/List.hx:277: characters 3-19
		$this->head = $head;
	}

	/**
	 * @return bool
	 */
	public function hasNext () {
		#C:\HaxeToolkit\haxe\std/haxe/ds/List.hx:281: characters 3-22
		return $this->head !== null;
	}

	/**
	 * @return mixed
	 */
	public function next () {
		#C:\HaxeToolkit\haxe\std/haxe/ds/List.hx:285: characters 3-23
		$val = $this->head->item;
		#C:\HaxeToolkit\haxe\std/haxe/ds/List.hx:286: characters 3-19
		$this->head = $this->head->next;
		#C:\HaxeToolkit\haxe\std/haxe/ds/List.hx:287: characters 3-13
		return $val;
	}
}

Boot::registerClass(ListIterator::class, 'haxe.ds._List.ListIterator');
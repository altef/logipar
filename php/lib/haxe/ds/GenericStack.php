<?php
/**
 */

namespace haxe\ds;

use \php\Boot;

/**
 * A stack of elements.
 * This class is generic, which means one type is generated for each type
 * parameter T on static targets. For example:
 * - `new GenericStack<Int>()` generates `GenericStack_Int`
 * - `new GenericStack<String>()` generates `GenericStack_String`
 * The generated name is an implementation detail and should not be relied
 * upon.
 * @see https://haxe.org/manual/std-GenericStack.html
 */
class GenericStack {
	/**
	 * @var GenericCell
	 */
	public $head;

	/**
	 * Creates a new empty GenericStack.
	 * 
	 * @return void
	 */
	public function __construct () {
	}
}

Boot::registerClass(GenericStack::class, 'haxe.ds.GenericStack');

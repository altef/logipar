<?php
/**
 * Generated by Haxe 4.0.0-rc.2+77068e10c
 */

namespace haxe;

use \php\Boot;

class MainEvent {
	/**
	 * @var \Closure
	 */
	public $f;
	/**
	 * @var bool
	 * Tells if the event can lock the process from exiting (default:true)
	 */
	public $isBlocking;
	/**
	 * @var MainEvent
	 */
	public $next;
	/**
	 * @var float
	 */
	public $nextRun;
	/**
	 * @var MainEvent
	 */
	public $prev;
	/**
	 * @var int
	 */
	public $priority;

	/**
	 * @param \Closure $f
	 * @param int $p
	 * 
	 * @return void
	 */
	public function __construct ($f, $p) {
		#C:\HaxeToolkit\haxe\std/haxe/MainLoop.hx:12: characters 33-37
		$this->isBlocking = true;
		#C:\HaxeToolkit\haxe\std/haxe/MainLoop.hx:17: characters 3-13
		$this->f = $f;
		#C:\HaxeToolkit\haxe\std/haxe/MainLoop.hx:18: characters 3-20
		$this->priority = $p;
		#C:\HaxeToolkit\haxe\std/haxe/MainLoop.hx:19: characters 3-15
		$this->nextRun = -1;
	}
}

Boot::registerClass(MainEvent::class, 'haxe.MainEvent');

<?php
/**
 * Generated by Haxe 4.0.0-rc.2+77068e10c
 */

namespace promhx;

use \php\Boot;
use \promhx\base\AsyncBase;

class Deferred extends AsyncBase {
	/**
	 * @return void
	 */
	public function __construct () {
		#C:\HaxeToolkit\haxe\lib\promhx/1,1,0/src/main/promhx/Deferred.hx:7: characters 27-34
		parent::__construct();
	}

	/**
	 * Returns a new promise based on the current deferred instance
	 * 
	 * @return Promise
	 */
	public function promise () {
		#C:\HaxeToolkit\haxe\lib\promhx/1,1,0/src/main/promhx/Deferred.hx:20: characters 9-33
		return new Promise($this);
	}

	/**
	 * Returns a stream based on the current deferred instance
	 * 
	 * @return PublicStream
	 */
	public function publicStream () {
		#C:\HaxeToolkit\haxe\lib\promhx/1,1,0/src/main/promhx/Deferred.hx:34: characters 9-38
		return new PublicStream($this);
	}

	/**
	 * The public write interface
	 * 
	 * @param mixed $val
	 * 
	 * @return void
	 */
	public function resolve ($val) {
		#C:\HaxeToolkit\haxe\lib\promhx/1,1,0/src/main/promhx/Deferred.hx:12: characters 36-54
		$this->handleResolve($val);
	}

	/**
	 * Returns a new stream based on the current deferred instance
	 * 
	 * @return Stream
	 */
	public function stream () {
		#C:\HaxeToolkit\haxe\lib\promhx/1,1,0/src/main/promhx/Deferred.hx:27: characters 9-32
		return new Stream($this);
	}

	/**
	 * @param mixed $e
	 * 
	 * @return void
	 */
	public function throwError ($e) {
		#C:\HaxeToolkit\haxe\lib\promhx/1,1,0/src/main/promhx/Deferred.hx:14: characters 50-64
		$this->handleError($e);
	}
}

Boot::registerClass(Deferred::class, 'promhx.Deferred');

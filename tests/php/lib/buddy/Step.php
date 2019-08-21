<?php
/**
 * Generated by Haxe 4.0.0-rc.2+77068e10c
 */

namespace buddy;

use \php\Boot;
use \php\_Boot\HxEnum;

class Step extends HxEnum {
	/**
	 * @param Spec $s
	 * 
	 * @return Step
	 */
	static public function TSpec ($s) {
		return new Step('TSpec', 1, [$s]);
	}

	/**
	 * @param Suite $s
	 * 
	 * @return Step
	 */
	static public function TSuite ($s) {
		return new Step('TSuite', 0, [$s]);
	}

	/**
	 * Returns array of (constructorIndex => constructorName)
	 *
	 * @return string[]
	 */
	static public function __hx__list () {
		return [
			1 => 'TSpec',
			0 => 'TSuite',
		];
	}

	/**
	 * Returns array of (constructorName => parametersCount)
	 *
	 * @return int[]
	 */
	static public function __hx__paramsCount () {
		return [
			'TSpec' => 1,
			'TSuite' => 1,
		];
	}
}

Boot::registerClass(Step::class, 'buddy.Step');

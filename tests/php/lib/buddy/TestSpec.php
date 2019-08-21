<?php
/**
 * Generated by Haxe 4.0.0-rc.2+77068e10c
 */

namespace buddy;

use \php\Boot;
use \php\_Boot\HxEnum;

class TestSpec extends HxEnum {
	/**
	 * @param TestSuite $suite
	 * @param bool $included
	 * 
	 * @return TestSpec
	 */
	static public function Describe ($suite, $included) {
		return new TestSpec('Describe', 0, [$suite, $included]);
	}

	/**
	 * @param string $description
	 * @param TestFunc $test
	 * @param bool $included
	 * @param object $pos
	 * @param float $time
	 * 
	 * @return TestSpec
	 */
	static public function It ($description, $test, $included, $pos, $time) {
		return new TestSpec('It', 1, [$description, $test, $included, $pos, $time]);
	}

	/**
	 * Returns array of (constructorIndex => constructorName)
	 *
	 * @return string[]
	 */
	static public function __hx__list () {
		return [
			0 => 'Describe',
			1 => 'It',
		];
	}

	/**
	 * Returns array of (constructorName => parametersCount)
	 *
	 * @return int[]
	 */
	static public function __hx__paramsCount () {
		return [
			'Describe' => 2,
			'It' => 5,
		];
	}
}

Boot::registerClass(TestSpec::class, 'buddy.TestSpec');

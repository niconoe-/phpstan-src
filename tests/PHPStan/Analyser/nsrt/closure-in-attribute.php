<?php // lint >= 8.5

namespace ClosureInAttributeTypes;

use Attribute;
use Closure;
use function PHPStan\Testing\assertType;

#[Attribute]
class AttrWithCallback
{

	/**
	 * @param Closure(int): void $callback
	 */
	public function __construct(public Closure $callback)
	{

	}

}

class AttrWithCallback2
{

	/**
	 * @param Closure(positive-int): void $callback
	 */
	public function __construct(public Closure $callback)
	{

	}

}

class Bar
{

	public function doBar(
		#[AttrWithCallback(static function ($i) {
			assertType('int', $i);
		})]
		$three,
		#[AttrWithCallback2(static function ($i) {
			assertType('int<1, max>', $i);
		})]
		$four,
		#[AttrWithCallback2(static function (int $i) {
			assertType('int<1, max>', $i);
		})]
		$five,
	)
	{

	}

}

<?php // lint >= 8.5

namespace ClosureInAttribute;

use Attribute;
use Closure;

#[Attribute]
class AttrWithCallback
{

	public function __construct(public Closure $callback)
	{

	}

}

class Foo
{

	public static function doFoo(int $i): void
	{

	}

}

function doFoo(int $i): void {

}

class Bar
{

	public function doBar(
		#[AttrWithCallback(Foo::doFoo(...))]
		$one,
		#[AttrWithCallback(doFoo(...))]
		$two,
		#[AttrWithCallback(static function (int $i) {
			return;
		})]
		$three,
		#[AttrWithCallback(static function (int $i) {
			return 'foo';
		})]
		$four,
		#[AttrWithCallback(static function (int $i): string {
			return 'foo';
		})]
		$five,
	)
	{

	}

}

#[Attribute]
class AttrWithCallback2
{

	/**
	 * @param Closure(int): mixed $callback
	 */
	public function __construct(public Closure $callback)
	{

	}

}

class Baz
{

	public function doBaz(
		#[AttrWithCallback2(static function ($i) {
			return $i;
		})]
		$one,
		#[AttrWithCallback2(static function (int $i = 1) {
			return $i;
		})]
		$two,
		#[AttrWithCallback2(static function (int ...$i) {
			return $i;
		})]
		$three,
	): void
	{

	}

}

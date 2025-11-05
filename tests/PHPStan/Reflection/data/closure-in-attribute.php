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
	)
	{

	}

}

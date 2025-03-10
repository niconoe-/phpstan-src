<?php

namespace Bug12398;

use function PHPStan\Testing\assertType;

class Foo
{

	public function doFoo(string $foo): void
	{
		$bar = 'foo';
		assertType('string', $$bar);
	}

}

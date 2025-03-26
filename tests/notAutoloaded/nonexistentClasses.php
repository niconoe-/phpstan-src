<?php declare(strict_types = 1);

namespace NamespaceForNonexistentClasses;

use function PHPStan\Testing\assertType;

class Foo
{

	public function doFoo(): void
	{
		assertType('1', 1);
	}

}

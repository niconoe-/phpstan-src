<?php declare(strict_types = 1);

namespace NamespaceForNonexistentClassesError;

use function PHPStan\Testing\assertType;

class Foo
{

	public function doFoo(): void
	{
		assertType('string', 1);
		error();
	}

}

trait FooTrait
{

}

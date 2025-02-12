<?php

namespace ArrayShapeFromGeneralArrayWithSingleFiniteKey;

use function PHPStan\Testing\assertType;

class Foo
{

	/**
	 * @param array<1, string> $a
	 */
	public function doFoo(array $a): void
	{
		assertType('array{}|array{1: string}', $a);
	}

	/**
	 * @param non-empty-array<1, string> $a
	 */
	public function doBar(array $a): void
	{
		assertType('array{1: string}', $a);
	}

}

<?php // lint >= 8.0

namespace Bug13552;

use function PHPStan\Testing\assertType;

function doSomething(mixed $value): void
{
	if (trim($value) === '') {
		return;
	}
	assertType('mixed', $value);
}

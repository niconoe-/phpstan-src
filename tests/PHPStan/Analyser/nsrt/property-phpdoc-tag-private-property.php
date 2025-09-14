<?php // lint >= 8.2

namespace PropertyPhpDocTagPrivateProperty;

use function PHPStan\Testing\assertType;

/**
 * @property non-empty-string $bar
 * @property non-empty-string $baz
 */
class Foo
{

	private string $bar;

	public string $baz;

}

function (Foo $foo): void {
	assertType('string', $foo->bar);
	assertType('non-empty-string', $foo->baz);
};

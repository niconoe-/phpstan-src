<?php declare(strict_types = 1);

namespace GeneratorNodeScopeResolverTest;

use function PHPStan\Testing\assertType;

class Foo
{

	public function doFoo(): ?string
	{
		return 'foo';
	}

}

function (): void {
	$foo = new Foo();
	assertType(Foo::class, $foo);
	assertType('string|null', $foo->doFoo());
	assertType($a = '1', (int) $a);
};

function (): void {
	assertType('array{foo: \'bar\'}', ['foo' => 'bar']);
	$a = [];
	assertType('array{}', $a);

};

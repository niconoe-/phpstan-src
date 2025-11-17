<?php declare(strict_types = 1);

namespace GeneratorNodeScopeResolverTest;

use function PHPStan\Testing\assertNativeType;
use function PHPStan\Testing\assertType;

class Foo
{

	public function doFoo(): ?string
	{
		return 'foo';
	}

	public function doImplicitArrayCreation(): void
	{
		$a['bla'] = 1;
		assertType('array{bla: 1}', $a);
	}

	/**
	 * @param int $a
	 * @param int $b
	 * @return void
	 */
	public function doPlus($a, $b, int $c, int $d): void
	{
		assertType('int', $a + $b);
		assertNativeType('(array|float|int)', $a + $b);
		assertType('2', 1 + 1);
		assertNativeType('2', 1 + 1);
		assertType('int', $c + $d);
		assertNativeType('int', $c + $d);
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

function (): void {
	$a['bla'] = 1;
	assertType('array{bla: 1}', $a);
};

function (): void {
	$cb = fn () => 1;
	assertType('Closure(): 1', $cb);

	$cb = fn (string $s) => (int) $s;
	assertType('Closure(string): int', $cb);

	$cb = function () {
		return 1;
	};
	assertType('Closure(): 1', $cb);

	$cb = function (string $s) {
		return $s;
	};
	assertType('Closure(string): string', $cb);
};

function (): void {
	$a = 0;
	$cb = function () use (&$a): void {
		$a = 's';
	};
	assertType('0|\'s\'', $a);
};

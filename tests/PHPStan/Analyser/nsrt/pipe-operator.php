<?php // lint >= 8.5

namespace PipeOperatorTypes;

use function PHPStan\Testing\assertType;

class Foo
{

	public function doFoo(string $s): int
	{
		return 1;
	}

	public function doBar(): void
	{
		assertType('int', 'foo' |> $this->doFoo(...));
	}

}

class StaticFoo
{

	public static function doFoo(string $s): int
	{
		return 1;
	}

	public function doBar(): void
	{
		assertType('int', 'foo' |> self::doFoo(...));
	}

}

function doFoo(): int
{

}

class FunctionFoo
{

	public function doBar(): void
	{
		assertType('int', 'foo' |> doFoo(...));
		assertType('int', 'foo' |> 'PipeOperatorTypes\\doFoo');
	}

}

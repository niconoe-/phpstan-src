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

/**
 * @return positive-int
 */
function doFoo(): int
{

}

class FunctionFoo
{

	public function doBar(): void
	{
		assertType('int<1, max>', 'foo' |> doFoo(...));
		assertType('int<1, max>', 'foo' |> 'PipeOperatorTypes\\doFoo');
	}

	public function doBaz(): void
	{
		doFoo() |> function ($x) {
			assertType('int<1, max>', $x);
		};

		doFoo() |> fn ($x) => assertType('int<1, max>', $x);

		doFoo() |> function (int $x) {
			assertType('int<1, max>', $x);
		};

		doFoo() |> fn (int $x) => assertType('int<1, max>', $x);
	}

	public function doBaz2(): void
	{
		(function ($x) {
			assertType('int<1, max>', $x);
		})(doFoo());

		(fn ($x) => assertType('int<1, max>', $x))(doFoo());

		(function (int $x) {
			assertType('int<1, max>', $x);
		})(doFoo());

		(fn (int $x) => assertType('int<1, max>', $x))(doFoo());
	}

}

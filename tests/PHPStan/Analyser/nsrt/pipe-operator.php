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

	/**
	 * @param array<positive-int> $ints
	 */
	public function doArrayMap(array $ints): void
	{
		assertType('array<int<1, max>>', $ints |>
			(fn ($x) => array_map(static fn ($i) => $i, $x)),
		);
		assertType('array<int<1, max>>', $ints |>
			(fn ($x) => array_map(static fn (int $i) => $i, $x)),
		);
		assertType('array<int<1, max>>', $ints |>
			(fn (array $x) => array_map(static fn ($i) => $i, $x)),
		);
		assertType('array<int<1, max>>', $ints |>
			(fn (array $x) => array_map(static fn (int $i) => $i, $x)),
		);

		assertType('array<\'foo\'>', $ints |>
			(fn (array $x) => array_map(static fn (int $i) => 'foo', $x)),
		);

		assertType('array<int<1, max>>', $ints |> function ($x) {
			assertType('array<int<1, max>>', $x);
			return array_map(function ($i) {
				assertType('int<1, max>', $i);

				return $i;
			}, $x);
		});

		assertType('array<int<1, max>>', $ints |> function (array $x) {
			assertType('array<int<1, max>>', $x);
			return array_map(function (int $i) {
				assertType('int<1, max>', $i);

				return $i;
			}, $x);
		});
	}

	/**
	 * @param array<positive-int> $ints
	 */
	public function doArrayFilter(array $ints): void
	{
		assertType('array<int<1, max>>', $ints |> function ($x) {
			assertType('array<int<1, max>>', $x);
			return array_filter($x, function ($i) {
				assertType('int<1, max>', $i);

				return true;
			});
		});
		assertType('array<int<1, max>>', $ints |> function (array $x) {
			assertType('array<int<1, max>>', $x);
			return array_filter($x, function (int $i) {
				assertType('int<1, max>', $i);

				return true;
			});
		});
		assertType('array<int<1, max>>', $ints |> fn ($x) => array_filter($x, function ($i) {
			assertType('int<1, max>', $i);

			return true;
		}));
		assertType('array<int<1, max>>', $ints |> fn (array $x) => array_filter($x, function (int $i) {
			assertType('int<1, max>', $i);

			return true;
		}));
		assertType('array<0|1|2, 1|2|3>', (function (array $x) {
			assertType('array{1, 2, 3}', $x);
			return array_filter($x, function (int $i) {
				assertType('1|2|3', $i);

				return true;
			});
		})([1, 2, 3]));
		assertType('array<0|1|2, 1|2|3>', (function ($x) {
			assertType('array{1, 2, 3}', $x);
			return array_filter($x, function ($i) {
				assertType('1|2|3', $i);

				return true;
			});
		})([1, 2, 3]));
		assertType('array<0|1|2, 1|2|3>', (function (array $x) {
			assertType('array{1, 2, 3}', $x);
			return array_filter($x, function (int $i) {
				assertType('1|2|3', $i);

				return true;
			});
		})([1, 2, 3]));
		assertType('array<0|1|2, 1|2|3>', (function ($x) {
			assertType('array{1, 2, 3}', $x);
			return array_filter($x, function ($i) {
				assertType('1|2|3', $i);

				return true;
			});
		})([1, 2, 3]));
		assertType('array<0|1|2, 1|2|3>', (fn (array $x) => array_filter($x, function (int $i) {
			assertType('1|2|3', $i);

			return true;
		}))([1, 2, 3]));
		assertType('array<0|1|2, 1|2|3>', (fn ($x) => array_filter($x, function ($i) {
			assertType('1|2|3', $i);

			return true;
		}))([1, 2, 3]));
		assertType('1', (fn ($x) => $x)(1));
		assertType('1', (function ($x) {
			assertType('1', $x);
			return $x;
		})(1));
	}

}

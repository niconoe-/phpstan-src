<?php // lint >= 8.4

declare(strict_types=1);

namespace Bug12586;

interface Foo
{
	public string $bar {
		get;
	}
}

readonly class FooImpl implements Foo
{
	public function __construct(
		public string $bar,
	)
	{
	}
}

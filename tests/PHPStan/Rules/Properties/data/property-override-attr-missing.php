<?php // lint >= 8.5

namespace PropertyOverrideAttrMissing;

class Foo
{

	public int $foo;

}

class Bar extends Foo
{

	public int $foo;

}

class BarPromoted extends Foo
{

	public function __construct(
		public int $foo
	) {}

}

class Baz extends Foo
{

	#[\Override]
	public int $foo;

	#[\Override]
	public int $bar;

	public function __construct(
		#[\Override]
		public int $baz
	) {}

}

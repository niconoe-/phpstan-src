<?php

namespace RestrictedUsage;

class Foo
{

	public function doTest(Nonexistent $c): void
	{
		$c->test();
		$this->doNonexistent();
		$this->doBar();
		$this->doFoo();
	}

	public function doBar(): void
	{

	}

	public function doFoo(): void
	{

	}

}

class FooStatic
{

	public static function doTest(): void
	{
		Nonexistent::test();
		self::doNonexistent();
		self::doBar();
		self::doFoo();
	}

	public static function doBar(): void
	{

	}

	public static function doFoo(): void
	{

	}

}

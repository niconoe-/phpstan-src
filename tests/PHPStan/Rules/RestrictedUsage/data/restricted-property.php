<?php

namespace RestrictedUsageProperty;

class Foo
{

	public $bar;

	public $foo;

	public function doTest(Nonexistent $c): void
	{
		$c->test;
		$this->doNonexistent;
		$this->bar;
		$this->foo;
	}

}

class FooStatic
{

	public static $bar;

	public static $foo;

	public static function doTest(): void
	{
		Nonexistent::$test;
		self::$nonexistent;
		self::$bar;
		self::$foo;
	}

}
